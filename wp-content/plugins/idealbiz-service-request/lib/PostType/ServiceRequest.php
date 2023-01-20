<?php





/**
 * Registers the `base_cpt` custom post type
 *
 * @link  http://widgilabs.com/
 * @since 1.0.0
 */

namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request\PostType;

use WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Gforms\HelperServiceSatisfaction;
use WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Email\EmailTemplate;

/**
 * Registers the `base_cpt` custom post type.
 *
 * @since  1.0.0
 * @author WidgiLabs <dev@widgilabs.com>
 */


class ServiceRequest extends \WidgiLabs\WP\Plugin\IdealBiz\Service\Request\PostType
{

	


	/**
	 * Register custom post type.
	 *
	 * @since 1.0.0
	 */
	public function register()
	{

		// Use johnbillion/extended-cpts
		\register_extended_post_type(
			$this->slug,
			array(
				'menu_icon'  => 'dashicons-businessman',
				'supports'   => array('title'),
				'admin_cols' => array(
					"{$this->slug}_published" => [
						'title'       => 'Published',
						'post_field'  => 'post_date',
						'date_format' => 'd/m/Y',
						'default'     => 'DESC',
					],
					"{$this->slug}_author"    => [
						'title'    => 'Customer',
						'function' => function () {
							echo esc_html(get_the_author_meta('user_email'));
						},
					],
				),
			)
		);
	}

	/**
	 * Register hooks.
	 *
	 * @since 1.0.0
	 */
	public function register_hooks()
	{
		add_filter('enter_title_here', array($this, 'enter_title_here'), 10, 2);
		add_filter("manage_{$this->slug}_posts_columns", array($this, 'manage_columns'), 20);

		//	add_filter( 'acf/update_value/name=consultant', array( $this, 'service_request_updated_send_email' ), 10, 3 );
		//add_filter('acf/update_value/name=customer', array($this, 'set_customer_author'), 99, 3);
	}

	/**
	 * Modifies the service request post author on customer change.
	 *
	 * @param string $value Id for the selected customer.
	 * @param [type] $post_id
	 * @param [type] $field
	 * @return void
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	public function set_customer_author($value, $post_id, $field)
	{

		$service_request = get_post($post_id);

		// If not service request continue.
		if ($service_request->post_type !== 'service_request') {
			return $value;
		}

		// Update post author.
		if ($service_request->post_author !== $value) {
			// change messages author
			$message_query = new \WP_Query(array(
				'post_type'      => 'service_message',
				'posts_per_page' => -1,
				'author'         => (int) $service_request->post_author,
			));

			if ($message_query->have_posts()) {
				foreach ($message_query->posts as $message) {
					$message_arr = array(
						'ID'          => $message->ID,
						'post_author' => $value,
					);

					wp_update_post($message_arr);
				}
			}

			// Change service request post author.
			$postarr = array(
				'ID'          => $post_id,
				'post_author' => $value,
			);

			wp_update_post($postarr);
			/* 			cl_alerta($post_id); */

			// Noitfy the new customer about the service request.
			// Codigo do email - #ACSEPS01U01 - Confirmação de Solicitação de serviço
			$new_user = get_user_by('id', $value);
			if (!$new_user) {
				return $value;
			}



			
			$cl_prev_ref = get_field('origin_sr', $post_id);
			$cl_msg = get_field('message', $post_id);
			$cl_member_id = get_field('consultant', $post_id,'');
			$cl_member_data = get_userdata($cl_member_id);
			$cl_member_f_name = $cl_member_data->first_name;
			$cl_member_l_name = $cl_member_data->last_name;
			$cl_valor_referencia = get_field('reference_value', $post_id);
			$cl_date = get_field('delivery_date', $post_id);
			$new_user_email = $new_user->user_email;

			//cl_alerta('Passo2');



			$user_compliment  = __('Hello', 'idealbiz-service-request');
			$user_compliment .= ' ' . $new_user->get('first_name') . ',';
			if (get_field('reference_value', $post_id)){	
				$user_compliment .= '<br /><br />'.__('We appreciate your trust in iDealBiz. Please be advised that your Service Request has been successfully submitted.');
				$user_compliment .= '<br /><br />'.__('Details data');
				$user_compliment .= '<br />'.__('Reference:').' #'.$post_id;
				$user_compliment .= '<br />' . __('Conclusion Date: ') .' '. $cl_date;
				$user_compliment .= '<br />' . __('Reference Value:') .' '. $cl_valor_referencia.__('Money Simbol');
				$user_compliment .= '<br />' . __('Member:') .' '. $cl_member_f_name.' '.$cl_member_l_name;
				$user_compliment .='<br /><br />' . __('Message:').'<br/>'. $cl_msg.'<br/><br />';
				$user_compliment .= '<br/><span style="color:#ffffff;font-size:0.5em;">SR01</span>';
				$subject              = __('[idealBiz] You have a new service request in your account', 'idealbiz-service-request');
			}
			if (get_field('is_referral', $post_id)){

				if ($_SESSION['membro']){
					$user_compliment .= '<br /><br />'.__('_str Please be informed that the referral to another member has been successfully submitted.');
				}else{
					$user_compliment .= '<br /><br />'.__('The Specialist you selected for your Service Request is not currently available to carry out the same.A new Specialist with experience in the same area of competence was referred to follow up on the Request.');
				}
				
				$user_compliment .= '<br /><br />'.__('We remember your Order details');	
				/* $user_compliment .= '<br />'.__('Previous reference:').' #'.$post_id; */
				$user_compliment .= '<br />'.__('New Reference:').' #'.'<b><i>'.$post_id.'</i></b>';
				$user_compliment .= '<br />' . __('Conclusion Date: ') .' '. $cl_date;
				$user_compliment .= '<br />' . __('Reference Value:') .' '. $cl_valor_referencia.__('Money Simbol');
				$user_compliment .= '<br />' . __('Member:') .' '.'<b><i>'. $cl_member_f_name.' '.$cl_member_l_name.'</i></b>';
				$user_compliment .='<br /><br />' . __('Message:').'<br/>'. $cl_msg.'<br/><br />';
				$user_compliment .= '<br/><span style="color:#ffffff;font-size:0.5em;">SR02</span>';
				$subject = __('[idealBiz] _str New referral service request from:');




			}
			

			$service_requests_url = get_permalink(get_option('woocommerce_myaccount_page_id')) . 'service_request';
			

			/*$body                 =
			__( 'You have a new message related to your service request for Business Support Services.', 'idealbiz-service-request' ).'<br/>'.
			__( 'To view this message and reply please go to your personal area using the following link %SERVICE_REQUEST_LINK%', 'idealbiz-service-request' ).'<br/>'.
			__( 'Thank you,', 'idealbiz-service-request' );
			*/
			$referral_user_email = get_field('referral', $post_id);
			/* cl_alerta($referral_user_email); */
			$body =__('My account Requests: %MY_SERVICE_REQUESTS%', 'idealbiz-service-request');


			/* translators: The placeholder id for the service request title */
			$head_message = sprintf(__('%1$s service request', 'idealbiz-service-request'), get_the_title($post_id));

			if (false !== strpos($body, '%MY_SERVICE_REQUESTS%')) {

				$service_request_link = sprintf(
					'<a href="%1$s" title="%2$s">%3$s</a>',
					$service_requests_url,
					__('View your service request link', 'idealbiz-service-request'),
					__('View your service request', 'idealbiz-service-request')
				);

				$body = str_replace('%MY_SERVICE_REQUESTS%', $service_request_link, $body);
			}



			$body_template = EmailTemplate::get_email_body(wpautop($body));
			$body_template = str_replace('%%HEAD_MESSAGE%%', $head_message, $body_template);
			$body_template = str_replace('%%USER_COMPLIMENT%%', $user_compliment, $body_template);
			$body_template .= '<br/><span style="color:#ffffff;font-size:0.5em;">SR03</span>';



			// Send email to consultant.
			$headers = array('Content-Type: text/html; charset=UTF-8');

			$customer_care = get_field('costumer_care_email', 'option');

			//$new_user_email = get_field('customer',$post_id)->user_email; bug 03/12/2020
			/*
			$mailResult = wp_mail( $new_user_email.', '.$customer_care, 'test if mail works', $body_template, $headers );
			echo $mailResult;
			echo $new_user_email.', '.$customer_care;
			*/

			//Alteração Cleverson Deixar de enviar email de New Resquest duplicado
			/* wp_mail( $new_user_email.','.$customer_care, $subject, $body_template, $headers ); */
			wp_mail($new_user_email, $subject, $body_template, $headers);
			if (get_field('is_referral', $post_id)) {
				$referral_user_email = get_field('referral', $post_id);
				$referral_user = get_user_by('email', $referral_user_email);
				$referral_user_name  = $referral_user->get('first_name') . ' ' . $referral_user->get('last_name');

				if ($_SESSION['membro']){
					$subject = __('_str Referral Between Members');

					session_start();
					unset($_SESSION['membro']);
					unset($_SESSION['rid']);
					unset($_SESSION['sr']);
					unset($_SESSION['email_referenciado']);

				}else{
					$subject = __('_str New referencing between members');
				}


				
				wp_mail($customer_care, $subject, $body_template, $headers,);
			} else {
				$subject = __('[idealBiz] New service request', 'idealbiz-service-request');
				wp_mail($customer_care, $subject, $body_template, $headers, get_field('delivery_date'));
			}
		}

		return $value;
	}

	/**
	 * Notify the consultant when he is assigned to a service.
	 *
	 * @param [type] $value
	 * @param [type] $post_id
	 * @param [type] $field
	 * @return void
	 */
	public function service_request_updated_send_email($value, $post_id, $field)
	{

		$old_value = get_post_meta($post_id, $field['name'], true);

		if ($old_value !== $value) {
			// it changed
			$consultant_user_obj = get_user_by('id', $value);
			if (!$consultant_user_obj) {
				return;
			}

			$consultant_email = $consultant_user_obj->user_email;

			$user_compliment  = __('Hello', 'idealbiz-service-request');
			$user_compliment .= ' ' . $consultant_user_obj->get('first_name') . ',';
			$user_compliment .= '<br/><span style="color:#ffffff;font-size:0.5em;">SR04</span>';

			$service_requests_url = get_permalink(get_option('woocommerce_myaccount_page_id')) . 'service_request';
			$subject              = __('[idealBiz] You have a new service request in your account', 'idealbiz-service-request');

			$body = __('Dear iDealBiz Consultant,<br/>
			<br/>
						You have been assigned to a service request on iDealBiz.<br/>
						Click the link below to visit your account:<br/>
						<br/>
						%MY_SERVICE_REQUESTS% <br/>
						<br/>
						Make sure you collect all necessary information to present a proposal.<br/>
						<br/>
						Best Regards,<br/>
						<br/>
						', 'idealbiz-service-request');


			/* translators: The placeholder id for the service request title */
			$head_message = sprintf(__('%1$s service request', 'idealbiz-service-request'), get_the_title($post_id));

			if (false !== strpos($body, '%MY_SERVICE_REQUESTS%')) {

				$service_request_link = sprintf(
					'<a href="%1$s" title="%2$s">%3$s</a>',
					$service_requests_url,
					__('View your service request link', 'idealbiz-service-request'),
					__('View your service request', 'idealbiz-service-request')
				);

				$body = str_replace('%MY_SERVICE_REQUESTS%', $service_request_link, $body);
			}

			$body_template = EmailTemplate::get_email_body(wpautop($body));
			$body_template = str_replace('%%HEAD_MESSAGE%%', $head_message, $body_template);
			$body_template = str_replace('%%USER_COMPLIMENT%%', $user_compliment, $body_template);

			// Send email to consultant.
			$headers = array('Content-Type: text/html; charset=UTF-8');

			$customer_care = ', ' . get_field('costumer_care_email', 'option');
			wp_mail($consultant_email . $customer_care, $subject, $body_template, $headers);
		}
		return $value;
	}

	/**
	 * Get idealbiz option by complete key depending on lang
	 *
	 * @param $key
	 * @param bool $default
	 *
	 * @return mixed|string|void
	 * @since 1.0.0
	 * @author Ana Aires ( ana@widgilabs.com )
	 */
	public static function get_idealbiz_option($key, $default = false)
	{
		global $sitepress;

		if (empty($key)) {
			return '';
		}

		$option_name = "options_{$key}";

		if (function_exists('icl_object_id') && (pll_current_language() !== pll_default_language())) {
			$option_name = 'options_' . ICL_LANGUAGE_CODE . "_{$key}";
		}

		return get_option($option_name, $default);
	}

	/**
	 * Filter the title field placeholder text.
	 *
	 * @since  1.0.0
	 * @param  string   $text Placeholder text. Default 'Enter title here'.
	 * @param  \WP_Post $post Post object.
	 * @return string         Possibly-modified placeholder text.
	 */
	public function enter_title_here($text, $post)
	{

		if (empty($post->post_type)) {
			return $text;
		}

		if ($post->post_type !== $this->slug) {
			return $text;
		}

		return __('Enter base cpt name here', 'wlbase');
	}

	/**
	 * Filter posts columns.
	 *
	 * @since  1.0.0
	 * @param  array $columns An array of column names.
	 * @return array          Possibly-modified array of column names.
	 */
	public function manage_columns($columns)
	{
		unset($columns['comments']);
		unset($columns['date']);
		return $columns;
	}

	/**
	 * Register custom fields.
	 *
	 * @since 1.0.0
	 */
	public function register_fields()
	{
	}
}
