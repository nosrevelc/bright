<?php
/**
 * Gravity form processing helper class.
 *
 * @link  http://widgilabs.com/
 * @since 1.0.0
 */

namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Gforms;

use WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Email\EmailTemplate;

class HelperServiceMessage {

	public function register_hooks() {

		add_filter( 'gform_replace_merge_tags', array( $this, 'replace_user_role' ), 10 );

		add_filter( 'gform_post_data', array( $this, 'create_service_message' ), 10, 3 );
		add_action( 'gform_after_create_post', array( $this, 'save_service_message_data' ), 10, 3 );
		add_action( 'gform_after_create_post', array( $this, 'send_service_message_notification' ), 11 );
	}

	/**
	 * Creates a new service message on form submission.
	 *
	 * @param array $post_data
	 * @param array $form
	 * @param array $entry
	 * @return array $post_data
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	public function create_service_message( $post_data, $form, $entry ) {

		if ( $post_data['post_title'] !== 'service_message' ) {
			return $post_data;
		}

		$post_data['post_type'] = 'service_message';

		foreach ( $form['fields'] as $field ) {

			if ( $field->type === 'textarea' && ! empty( $entry[ $field->id ] ) ) {

				$post_data['post_content'] = $entry[ $field->id ];
			}
		}
		$post_data['post_title'] = time();

		return $post_data;
	}

	/**
	 * Used to update the custom fields for the service message, with the form data.
	 *
	 * @param int   $post_id ID for the newly created service message.
	 * @param array $entry
	 * @param array $form
	 * @return void
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	public function save_service_message_data( $post_id, $entry, $form ) {
		$post = get_post( $post_id );

		if ( $post->post_type !== 'service_message' ) {
			return;
		}

		foreach ( $form['fields'] as $field ) {

			if ( $field->type === 'hidden' && $field->label === 'service_request_id' ) {

				if ( ! empty( $entry[ $field->id ] ) ) {

					update_field( 'service_request', $entry[ $field->id ], $post_id );
				}
			}

			if ( $field->type === 'fileupload' ) {

				if ( ! empty( $entry[ $field->id ] ) ) {

					update_field( 'message_file_url', $entry[ $field->id ], $post_id );
				}
			}
		}
	}

	public function replace_user_role( $text ) {

		$custom_merge_tag = '{user_role}';

		if ( strpos( $text, $custom_merge_tag ) === false ) {
			return $text;
		}

		$current_user = wp_get_current_user();
		if ( ! in_array( 'consultant', $current_user->roles, true ) ) {
			return $text;
		}

		return 'consultant';
	}


	public function gt($toTranslate, $domain= ''){


		global $wpdb;
		$translations = $wpdb->get_results( 
			"SELECT *
			FROM $wpdb->postmeta
			WHERE meta_key='_pll_strings_translations'");
		foreach ( $translations as $t )  
		{
			$posts_arr= $wpdb->get_results( 
				"SELECT post_title
				FROM $wpdb->posts
				WHERE ID=$t->post_id");
				$langterm = str_replace('polylang_mo_','',$posts_arr[0]->post_title);
 
			$lang_test= $wpdb->get_results( 
				"SELECT slug
				FROM $wpdb->terms
				WHERE term_id=$langterm");
				$lang = $lang_test[0]->slug;

				$test= 'en';
				if(pll_current_language()){
					$test = pll_current_language();
				}
				if(isset($_GET['lang'])){
					$test = $_GET['lang'];
				}

			if($test==$lang){
				$strings = maybe_unserialize( $t->meta_value );
				foreach($strings as $k => $str){
					if($str[0]==$toTranslate){
						return $str[1];
					}
				}
			}
		}
		return $toTranslate;
	}

	/**
	 * Used to notify the message thread participants that a new message has been posted.
	 *
	 * @param int  $post_id ID for the newly created service message.
	 * @param bool $payment_message Only used in case of paymente message. Because it´s
	 *             possible to get the current user. In this case the system gets the
	 *             the service request auther.
	 * @return void
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	public function send_service_message_notification( $post_id, $payment_message = false, $extra_msg = '' ) {
		$post = get_post( $post_id );

		if ( $post->post_type !== 'service_message' ) {
			return;
		}

		$current_user = wp_get_current_user();
		if ( $payment_message ) {
			$current_user = get_user_by( 'id', $post->post_author );
		}
 
		$service_request_id   = get_field( 'service_request', $post_id );
		$service_request_post = get_post( $service_request_id );

		$service_request_author = get_userdata( $service_request_post->post_author );

		/**
		 * Default notification is sent to service request author.
		 */
		$to               = $service_request_author->get( 'user_email' );
		$user_compliment  = $this->gt('Hello');
		$user_compliment .= ' ' . $service_request_author->get( 'first_name' ) . ',';

		/**
		 * If a message is from service request author, change the destination
		 * email and the compliment to the consultant user.
		 */
		if ( $current_user->ID === $service_request_author->ID ) {

			$consultant = get_field( 'consultant', $service_request_id );

			if ( is_a( $consultant, 'WP_User' ) ) {
				$to               = $consultant->get( 'user_email' );
				$user_compliment  = $this->gt('Hello');
				$user_compliment .= ' ' . $consultant->get( 'first_name' ) . ',';
			}
		}

		if($extra_msg!=''){
			$extra_msg='<b>'.$extra_msg.'</b><br/><br/>';
		}


		$pc = preg_replace('#<div class="d-table m-auto m-t-10 propose proposal_'.$post_id.'">(.*?)</div>#', '', $post->post_content);

		$post_message='<b>'.$this->gt('Message:','irs').'</b><br/>'.$pc.'<br/>';

		$subject = $this->gt( '[idealBiz] You have a new message in your service request', 'irs' ) . ' ' . $service_request_post->post_title;
		$body    = $this->gt('You have a new message related to your service request for Business Support Services.','irs').'<br/><br/>'.$extra_msg.''.$post_message.$this->gt('To view this message and reply please go to your personal area using the following link %SERVICE_REQUEST_LINK%').' <br/><br/>'.$this->gt('Thank you,').'<br/>'; 

		if ( false !== strpos( $body, '%SERVICE_REQUEST_LINK%' ) ) {

			$service_request_link = sprintf(
				'<a href="%1$s" title="%2$s">%3$s</a>',
				get_the_permalink( $service_request_post ),
				$this->gt( 'View your service request link'),
				$this->gt( 'View your service request')
			);

			$body = str_replace( '%SERVICE_REQUEST_LINK%', $service_request_link, $body );
		}

		$body_template = EmailTemplate::get_email_body( wpautop( $body ) );

		$body_template = str_replace( '%%HEAD_MESSAGE%%', $service_request_post->post_title, $body_template );
		$body_template = str_replace( '%%USER_COMPLIMENT%%', $user_compliment, $body_template );

		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		// notify client

		$customer_care = ', '.get_field('costumer_care_email', 'option');

		wp_mail( $to.$customer_care, $subject, $body_template, $headers );
		
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
	public static function get_idealbiz_option( $key, $default = false ) {


		if ( empty( $key ) ) {
			return '';
		}

		$option_name = "options_{$key}";

		if ( function_exists( 'icl_object_id' ) && ( pll_current_language() !== pll_default_language() ) ) {
			$option_name = 'options_' . ICL_LANGUAGE_CODE . "_{$key}";
		}

		return get_option( $option_name, $default );
	}
}
