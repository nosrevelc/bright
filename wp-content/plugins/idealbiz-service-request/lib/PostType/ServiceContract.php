<?php
/**
 * Registers the `base_cpt` custom post type
 *
 * @link  http://widgilabs.com/
 * @since 1.0.0
 */

namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request\PostType;

use WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Gforms\HelperServiceMessage;
use WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Email\EmailTemplate;

/**
 * Registers the `base_cpt` custom post type.
 *
 * @since  1.0.0
 * @author WidgiLabs <dev@widgilabs.com>
 */
class ServiceContract extends \WidgiLabs\WP\Plugin\IdealBiz\Service\Request\PostType {

	/**
	 * Register custom post type.
	 *
	 * @since 1.0.0
	 */
	public function register() {

		// Use johnbillion/extended-cpts
		\register_extended_post_type(
			$this->slug, array(
				'menu_icon' => 'dashicons-lock',
				'supports'  => array( 'title' ),
			)
		);
	}

	/**
	 * Register hooks.
	 *
	 * @since 1.0.0
	 */
	public function register_hooks() {
		add_filter( 'enter_title_here', array( $this, 'enter_title_here' ), 10, 2 );
		add_filter( "manage_{$this->slug}_posts_columns", array( $this, 'manage_columns' ), 20 );

		add_action( 'save_post', array( $this, 'add_to_service_request' ), 20, 2 );
		add_action( 'save_post', array( $this, 'maybe_create_order' ), 30, 2 );

		add_action( 'admin_post_accept_contract', array( $this, 'accept_contract' ) );
		add_action( 'admin_post_nopriv_accept_contract', array( $this, 'accept_contract' ) );
		add_action( 'admin_post_stage_completed', array( $this, 'stage_completed' ) );
		add_action( 'admin_post_nopriv_stage_completed', array( $this, 'stage_completed' ) );

		add_action( 'woocommerce_order_status_processing', array( $this, 'maybe_payment_complete' ), 10, 2 );
		add_action( 'woocommerce_order_status_completed', array( $this, 'maybe_payment_complete' ), 10, 2 );

	}

	/**
	 * Filter the title field placeholder text.
	 *
	 * @since  1.0.0
	 * @param  string   $text Placeholder text. Default 'Enter title here'.
	 * @param  \WP_Post $post Post object.
	 * @return string         Possibly-modified placeholder text.
	 */
	public function enter_title_here( $text, $post ) {

		if ( empty( $post->post_type ) ) {
			return $text;
		}

		if ( $post->post_type !== $this->slug ) {
			return $text;
		}

		return __( 'Enter base cpt name here', 'wlbase' );
	}

	/**
	 * Filter posts columns.
	 *
	 * @since  1.0.0
	 * @param  array $columns An array of column names.
	 * @return array          Possibly-modified array of column names.
	 */
	public function manage_columns( $columns ) {
		unset( $columns['comments'] );
		unset( $columns['date'] );
		return $columns;
	}

	/**
	 * Register custom fields.
	 *
	 * @since 1.0.0
	 */
	public function register_fields() {}

	public function add_to_service_request( $post_id, $post ) {
		// Get out if is not correct post_type.
		if ( $post->post_type !== 'service_contract' ) {
			return;
		}

		$service_request = get_field( 'service_request', $post_id );
		$contracts       = get_field( 'contracts', $service_request->ID );

		$contracts[] = $post_id;
		update_field( 'contracts', $contracts, $service_request->ID );

	}

	/**
	 * Used to check if an order is to be created or if already exists for the current
	 * stage.
	 *
	 * @param int $post_id Service contract id.
	 * @param WP_Post $post Service contract cpt.
	 * @return mix Returns WC_Order or false.
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	public function maybe_create_order( $post_id, $post ) {
		// Get out if is not correct post_type.


		if ( $post->post_type !== 'service_contract' ) {
			return;
		}

		if ( ! function_exists( 'get_field' ) ) {
			return;
		}

		// Check the current stage.
		$stage = strtolower( get_field( 'stage', $post_id ) );
		if ( ! $stage ) {
			return;
		}
	

		// Check the number of orders.
		$service_request = get_field( 'service_request', $post_id );
		if ( is_numeric( $service_request ) ) {
			$service_request = get_post( $service_request );
		}




		$orders = get_field( 'orders', $service_request->ID );
		if ( ! $orders ) {
			$orders = array();
		}


		$orders_count = count( $orders );

		// If stage and order don´t match create the stage order.



		switch ( $stage ) {
			case 'adjudication':

/*
				var_dump($stage);
				echo '<br/>';
				var_dump($post_id);
				echo '<br/>';
				var_dump($service_request);
				die();
*/
			
				
				if ( ! $orders ) {
					$order = $this->create_order( $stage, $post_id, $service_request );
				} else {
					$order = new \WC_Order( array_shift( $orders ) );
				}
				
				break;

			case 'intermediate':
/*

				*/



				if ( $orders_count < 2 ) {
					$order = $this->create_order( $stage, $post_id, $service_request );
				}
				break;

			case 'conclusion':
				if ( $orders_count < 3 ) {
					$order = $this->create_order( $stage, $post_id, $service_request );
				}
				break;

			default:
				$order = false;
				break;
		}

	//	var_dump($order);
	//	die();

		// Add order to correspondent service request.
		if ( $order && ! in_array( $order->id, $orders, true ) ) {
			$orders[] = $order->id;

			update_field( 'orders', $orders, $service_request->ID );
		}



		return $order;
	}

	/**
	 * Creates an order with custom values for each completed stage.
	 *
	 * @param string  $stage The stage to create order for.
	 *                       Stages: adjudication, intermediate, conclusion.
	 * @param int     $contract_id ID for the contract that originates the order.
	 * @param WP_Post $service_request Service request post object.
	 * @return WC_Order The newlly created order object.
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	private function create_order( $stage, $contract_id, \WP_Post $service_request ) {
		// Create order.
		$order = \wc_create_order();

		// Get customer id from contract.
		$customer = get_field( 'customer', $service_request->ID );
		$order->set_customer_id( $customer->ID );

		// Get product id for current stage.

		$params = array(
			'post_type' => 'product',
			'meta_query' => array(
				array(
					'key' => '_product_type_meta_key',
					'value' => $stage.'_product',
					'compare' => '='
				)
			) 
		);
		$wcp = new \WP_Query($params);
		global $post, $product;
		if ($wcp->have_posts()) {
			while ($wcp->have_posts()) {
				$wcp->the_post();
				$pid = get_the_ID();
			}
		}

		$product = \wc_get_product( $pid );

		// Modify product price.
		$contract_value   = (int) get_field( 'proposal_value', $contract_id );
		$stage_percentage = (int) get_field( "percentage_{$stage}", $contract_id );





		$product->set_price( ( $contract_value * $stage_percentage ) / 100 );
		$product->set_name( 'REF#'. $service_request->ID.' '.$product->get_name(). ' ('.$stage_percentage.'%)' );


		// Add product to order.
		$order->add_product( $product );
		$order->calculate_totals();



		// Save order meta with service contract id.
		$order->add_meta_data( 'contract_id', $contract_id, true );

		
		//$order->save();

		$YITH_WC = YITH_WC_Points_Rewards_Earning();
		$points = floatval($YITH_WC->calculate_product_points( $product, false ));
		yit_save_prop( $order, 'ywpar_points_from_cart', $points );

		$expert_id = get_post_meta( $service_request->ID, 'consultant')[0];
		YITH_WC_Points_Rewards()->add_point_to_customer( $expert_id, $points, 'admin_action', __('Expert Bonus for SR#','idealbiz').$service_request->ID.' ('.$stage.')' );

		//var_dump($product);
		//var_dump($points); 
		//die();

		return $order;

	}

	/**
	 * When the user clicks on the acceptance link.
	 *
	 * Creates the order for the first stage and redirects to the
	 * checkout page for payment.
	 *
	 * @return void
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	public function accept_contract() {
		// phpcs:ignore WordPress.CSRF.NonceVerification.NoNonceVerification
		if ( ! isset( $_GET['id'] ) ) {
			return;
		}

		$contract_id = (int) $_GET['id'];
		$contract    = get_post( $contract_id );


		update_field( 'stage', 'Adjudication', $contract_id );

		$order = $this->maybe_create_order( $contract_id, $contract );

		$checkout_url = $this->get_translated_payment_url( $order );

		wp_redirect( $checkout_url); //var_dump($checkout_url);
		exit;
	}

	/**
	 * When an order is paid this triggers the service request, a new message and
	 * notification to the consultant.
	 *
	 * First validates if the order is transitioning from a pre-paid state to a paid state.
	 *
	 * @param int      $order_id Order id.
	 * @param WC_Order $order WC_Order object.
	 * @return void
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	public function maybe_payment_complete( $order_id, $order ) {

		$contract_id = $order->get_meta( 'contract_id' );
		
		/**
		 * If no contract id it's not a service request order.
		 */
		if ( ! $contract_id ) {
			return;
		}

		// Move contract forward.
		$stage = get_field( 'stage', $contract_id );

		switch ( strtolower( $stage ) ) {
			case 'adjudication':
				update_field( 'stage', 'Intermediate', $contract_id );
				update_field( 'progress', 'In Progress', $contract_id );
				//$stage = get_field( 'stage', $contract_id );
				break;

			case 'intermediate':
				update_field( 'stage', 'Conclusion', $contract_id );
				update_field( 'progress', 'In Progress', $contract_id );
				break;

			case 'conclusion':
				update_field( 'progress', 'Closed', $contract_id );
		}

		// Move service request to waiting on consultant.
		$service_request_id = get_field( 'service_request', $contract_id );
		if ( strtolower( $stage ) === 'conclusion' ) {

			update_field( 'state', 'Closed', $service_request_id );
		} else {

			update_field( 'state', 'Waiting on Consultant', $service_request_id );
		}

		// Create message:
		$post_data = array(
			'post_type'   => 'service_message',
			'post_status' => 'publish',
			'post_title'  => time(),
			'post_author' => $order->get_customer_id(),
		);

		//$post_data['post_content'] = //get_field( 'payment_completed_template', 'option' );
		$pc = $this->gt( 'Payment for the').' %STAGE% '.$this->gt('completed.', 'idealbiz-service-request' );

		$expert_id = get_post_meta( $service_request_id, 'consultant')[0];

		$points_used = (int) get_post_meta( $order_id, 'used_points', 1 );
		if (strpos($points_used, 'used_') !== false) {
			
		}else{
			$points_used = -1 * abs($points_used);
			$user_id = $order->get_user_id( );
			WC()->cart->remove_coupons();
			WC()->cart->calculate_totals();
		//	YITH_WC_Points_Rewards()->add_point_to_customer( $user_id, $points_used, 'admin_action', __('Service Request: #','idealbiz').$service_request_id.' ('.$stage.')' );
		//	YITH_WC_Points_Rewards()->add_point_to_customer( $expert_id, $points_used, 'admin_action', __('Expert Bonus: SR#','idealbiz').$service_request_id.' ('.$stage.')' );
			if($points_used !=0 ){
				YITH_WC_Points_Rewards()->add_point_to_customer( $user_id, $points_used, 'admin_action', $this->gt('Points used in SR: #','idealbiz').$service_request_id.' ('.$stage.')' );
			}
			update_post_meta( $order_id, 'used_points', 'used_'.$points_used );
		}
 

		$post_data['post_content'] = $pc; 

		$post_data['post_content'] .= '<br/><br/>
		<p style="font-size: 15px;">
		'.$this->gt('Order:','idealbiz').'#'.$order_id.' ('.get_field('percentage_'.strtolower($stage),$contract_id).'%) 
		<b>'.$order->get_formatted_order_total().'</b>
		</p>';
		$stageTranslation = '';
		if ( false !== strpos( $post_data['post_content'], '%STAGE%' ) ) {

			switch ( strtolower( $stage ) ) {
				case 'adjudication':
					$stage = 'adjudication';
					$stageTranslation = $this->gt('SR Adjudication');
					break;
				case 'intermediate':
					$stage = 'intermediate';
					$stageTranslation = $this->gt('SR Intermediate');
					break;
				case 'conclusion':
					$stage = 'conclusion';
					$stageTranslation = $this->gt('SR Conclusion');
					break;
			}

			$post_data['post_content'] = str_replace( '%STAGE%', $stageTranslation, $post_data['post_content'] );
		}

		$message_id = wp_insert_post( $post_data );

		if ( $message_id ) {
			update_field( 'service_request', $service_request_id, $message_id );

			$pay_com = $this->gt('Payment for the').' '.$stageTranslation.' '.$this->gt('completed.');

			update_field('aux_message', $pay_com, $service_request_id );

			$pay_com_email ='<p style="font-size: 15px; font-weight:400;">
						'.$this->gt('Service Request:','idealbiz').' <b>REF# '.$service_request_id.'</b><br/>
						'.$pay_com.'<br/>
						'.get_user_by('id',$order->get_customer_id())->display_name.'<br/>
						'.$this->gt('Order:','idealbiz').'#'.$order_id.' ('.get_field('percentage_'.strtolower($stage),$contract_id).'%) 
						<b>'.$order->get_formatted_order_total().'</b><br/>
						</p>';

			$service_message = new HelperServiceMessage();
			$payment_message = true;
			$service_message->send_service_message_notification( $message_id, $payment_message, $pay_com_email );
		}

		/**
		 * Send email whith satisfaction form link.
		 */
		if ( strtolower( $stage ) === 'conclusion' ) {
			// Send message for customer satisfaction.
			$current_user     = get_user_by( 'id', $order->get_customer_id() );
			$user_compliment  = $this->gt( 'Hello', 'idealbiz-service-request' );
			$user_compliment .= ' ' . $current_user->get( 'first_name' ) . ',';

			$subject = $this->gt( 'Please fillout our customer satisfaction form.', 'idealbiz-service-request' );

			$service_sat_url = getLinkByTemplate('single-quest.php');

			$form_link = sprintf(
				'<a href="%1$s" title="Customer satisfaction from link">%2$s</a>',
				esc_url( $service_sat_url. '?contract_id=' . $contract_id ), 
				esc_html__( 'click to form', 'idealbiz-service-request' )
			);

			$body = $this->gt('Service satisfaction', 'irs' ).': %FORM_LINK%';

			$body_template = EmailTemplate::get_email_body( wpautop( $body ) );

			$body_template = str_replace( '%%HEAD_MESSAGE%%', __( 'Service satisfaction', 'idealbiz-service-request' ), $body_template );
			$body_template = str_replace( '%%USER_COMPLIMENT%%', $user_compliment, $body_template );
			$body_template = str_replace( '%FORM_LINK%', $form_link, $body_template );

			$headers = array( 'Content-Type: text/html; charset=UTF-8' );
			$result = wp_mail( $current_user->get( 'user_email' ), $subject, $body_template, $headers );

 

			// Send message for customer redeem his share.
			//	$current_user     = get_user_by( 'id', $order->get_customer_id() );
			global $wpdb;
			$service_request_id = get_field( 'service_request', $contract_id );
			$service_cat_id = get_post_meta( $service_request_id, 'request_type' )[0];
			$user_id = get_post_meta( $service_request_id, 'consultant')[0];
			$user_email = get_user_by('id', $user_id)->user_email;
			//$expert_email = get_post_meta($service_request_id, 'expert_email')[0];

			$user_compliment  = $this->gt( 'Hello', 'idealbiz-service-request' );
			$user_compliment .= ' ' . get_user_by('id', $user_id)->get( 'first_name' ) . ',';
			$subject = $this->gt( 'Your Service Request is completed. Redeem your winnings.', 'idealbiz-service-request' );

			$contract_value = get_field('proposal_value',$contract_id);
			$percentage = 0;
			$expert_earn_part= 0;
			$idealbiz_earn_part= 0;
			$querystr = "
				SELECT $wpdb->posts.* 
				FROM $wpdb->posts, $wpdb->postmeta
				WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
				AND $wpdb->postmeta.meta_key = 'expert_email' 
				AND $wpdb->postmeta.meta_value = '".$user_email."' 
				AND $wpdb->posts.post_status = 'publish' 
				AND $wpdb->posts.post_type = 'expert'
				ORDER BY $wpdb->posts.post_date DESC
			";
			$pageposts = $wpdb->get_results($querystr, OBJECT);
			$tax_id=0;
			foreach ($pageposts as $post):
				$expert_id= $post->ID;
				$post_metas = get_post_meta($expert_id);
				$services_royalties_num= get_post_meta($expert_id,'services_royalties');
				for($i=0;$i<$services_royalties_num[0];$i++){
					if($service_cat_id == $post_metas['services_royalties_'.$i.'_service'][0]){
						$percentage = get_post_meta($expert_id, 'services_royalties_'.$i.'_royalty' )[0];
						$idealbiz_earn_part = (($contract_value * get_post_meta($expert_id, 'services_royalties_'.$i.'_royalty' )[0])/100);
						$tax_id = $post_metas['services_royalties_'.$i.'_service'][0];
					}
				}
			endforeach;
			$expert_earn_part = $contract_value - $idealbiz_earn_part;
			/*
			echo '<br/>contact-value'.$contract_value; 
			echo '<br/>ib-part'.$idealbiz_earn_part;
			echo '<br/>percentage'. $percentage;
			echo '<br/>expert-share'.$expert_earn_part;
			*/
			$body= $this->gt('Your Service Request is completed.', 'idealbiz-service-request' );
			$body.='<br/><br/>';
			$body.=$this->gt('Total Service Request:', 'idealbiz-service-request' ).' '.wc_price($contract_value);
			$body.='<br/>';
			$body.=$this->gt('Idealbiz Royalty','idealbiz-service-request').'('.get_the_title($service_request_id).'): '.$percentage.'%';
			$body.='<br/>';
			$body.=$this->gt('Expert Earnings: ','idealbiz-service-request').' '.wc_price($expert_earn_part);
			$body.='<br/><br/>';
			$body.='<b>'.$this->gt('Please make your invoice to Idealbiz with the amount of:','idealbiz-service-request').' <u>'.wc_price($expert_earn_part).'</u></b><br/>'.__('With the REF#','idealbiz-service-request').$service_request_id.' ';
			
			$body.='<br/>'.$this->gt('Invoice details:','irs');
			$body.='<br/><b>'.$this->gt('Name','irs').'</b> '.$this->gt('Idealbiz, S.A','irs');
			$body.='<br/><b>'.$this->gt('NIF','irs').'</b> '.$this->gt('515242438','irs');
			$body.='<br/><b>'.$this->gt('Address','irs').'</b> '.$this->gt('Rua Casal do Cego, Cci Covinhas 2415-315 Leiria','irs');
			
			$body.='<br/><br/>'.$this->gt('Thank you,','idealbiz-service-request');
			$body.='<br/><br/>';

			$body_template = EmailTemplate::get_email_body( wpautop( $body ) );

			$body_template = str_replace( '%%HEAD_MESSAGE%%', $this->gt( 'Submit your invoice', 'idealbiz-service-request' ), $body_template );
			$body_template = str_replace( '%%USER_COMPLIMENT%%', $user_compliment, $body_template );

			$headers = array( 'Content-Type: text/html; charset=UTF-8' );
			$customer_care = ', '.get_field('costumer_care_email', 'option');
			$result = wp_mail( $user_email.$customer_care, $subject, $body_template, $headers );


			// Send message for who made the referral
			// $current_user = get_user_by( 'id', $order->get_customer_id() ;
			
			
			if(get_field('is_referral', $service_request_id)){

				$referral_user_email = get_field( 'referral', $service_request_id );
				$referral_user = get_user_by('email', $referral_user_email);

				$user_compliment  = $this->gt( 'Hello', 'idealbiz-service-request' );
				$user_compliment .= ' ' . $referral_user->get( 'first_name' ) . ',';
				$subject = $this->gt('The Service Request you referred is completed. Redeem your winnings.', 'irs' );

				$contract_value = get_field('proposal_value',$contract_id);
				$percentage = 0;
				$expert_earn_part= 0;
				$idealbiz_earn_part= 0;
				$querystr = "
					SELECT $wpdb->posts.* 
					FROM $wpdb->posts, $wpdb->postmeta
					WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
					AND $wpdb->postmeta.meta_key = 'expert_email' 
					AND $wpdb->postmeta.meta_value = '".$user_email."' 
					AND $wpdb->posts.post_status = 'publish' 
					AND $wpdb->posts.post_type = 'expert'
					ORDER BY $wpdb->posts.post_date DESC
				";
				$pageposts = $wpdb->get_results($querystr, OBJECT);
				$tax_id=0;
				$referral_percentage=70;
				$idealbiz_earn_part = (($contract_value * 10)/100);
				foreach ($pageposts as $post):
					$expert_id= $post->ID;
					$post_metas = get_post_meta($expert_id);
					$services_royalties_num= get_post_meta($expert_id,'services_royalties');
					for($i=0;$i<$services_royalties_num[0];$i++){
						if($service_cat_id == $post_metas['services_royalties_'.$i.'_service'][0]){
							$percentage = get_post_meta($expert_id, 'services_royalties_'.$i.'_royalty' )[0];
							$idealbiz_earn_part = (($contract_value * get_post_meta($expert_id, 'services_royalties_'.$i.'_royalty' )[0])/100);
							$referral_percentage = get_post_meta($expert_id, 'services_royalties_'.$i.'_referral_percentage' )[0];
						}
					}
				endforeach;
				//$expert_earn_part = $contract_value - $idealbiz_earn_part;
				$referral_earn_part = ($idealbiz_earn_part * $referral_percentage)/100;

				update_field( 'earned', $referral_earn_part, $service_request_id );
				
				$body= $this->gt('Your Service Request is completed.', 'irs' );
				$body.='<br/><br/>';
				$body.=$this->gt('Total Service Request:', 'idealbiz-service-request' ).' '.wc_price($contract_value);
				$body.='<br/>';
				$body.=$this->gt('Your Referral Percentage','irs').'('.get_the_title($service_request_id).'): '.$referral_percentage.'%';
				$body.='<br/>';
				$body.=$this->gt('Your Earnings: ','irs').' '.wc_price($referral_earn_part);
				$body.='<br/><br/>';
				$body.='<b>'.$this->gt('Please make your invoice to Idealbiz with the amount of:','idealbiz-service-request').' <u>'.wc_price($referral_earn_part).'</u></b><br/>'.__('With the REF#','idealbiz-service-request').$service_request_id.' ';
				
				$body.='<br/>'.$this->gt('Invoice details:','irs');
				$body.='<br/><b>'.$this->gt('Name','irs').'</b> '.$this->gt('Idealbiz, S.A','irs');
				$body.='<br/><b>'.$this->gt('NIF','irs').'</b> '.$this->gt('515242438','irs');
				$body.='<br/><b>'.$this->gt('Address','irs').'</b> '.$this->gt('Rua Casal do Cego, Cci Covinhas 2415-315 Leiria','irs');

				$body.='<br/><br/>'.$this->gt('Thank you,','idealbiz-service-request');
				$body.='<br/><br/>';

				$body_template = EmailTemplate::get_email_body( wpautop( $body ) );
				$body_template = str_replace( '%%HEAD_MESSAGE%%', $this->gt( 'Submit your invoice', 'idealbiz-service-request' ), $body_template );
				$body_template = str_replace( '%%USER_COMPLIMENT%%', $user_compliment, $body_template );

				$headers = array( 'Content-Type: text/html; charset=UTF-8' );
				$customer_care = ', '.get_field('costumer_care_email', 'option');
				$result = wp_mail( $referral_user_email.$customer_care, $subject, $body_template, $headers );

			}


		}
	}

	public function gt($toTranslate, $domain = ''){
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

			if($_GET['lang']==$lang){
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

	public function stage_completed() {
		if ( ! isset( $_GET['id'], $_GET['stage'] ) ) {
			return;
		}
		

		$contract_id = (int) $_GET['id'];
		$contract    = get_post( $contract_id );

		$stage = (string) $_GET['stage'];


		$service_request = get_field( 'service_request', $contract_id );
		if ( is_numeric( $service_request ) ) {
			$service_request = get_post( $service_request );
		}

		// Create order.
		$order = $this->maybe_create_order( $contract_id, $contract );

		// Message & notification.
		$this->create_message_thread( $contract, $service_request, $order );

		// Set Waiting on customer.
		update_field( 'progress', 'Pending Payment', $contract_id );
		
		// Redirect back to service request page.
		update_field( 'state', 'Waiting on Customer', $service_request->ID );
		wp_redirect( get_permalink( $service_request ) );
		exit;
	}

	/**
	 * Creates the first message to the service request message thread.
	 *
	 * @param int $post_id ID for the newly created service request.
	 * @return void
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	public function create_message_thread( $contract, $service_request, $order ) {
		if ( $contract->post_type !== 'service_contract' ) {
			return;
		}

		if ( ! $order ) {
			return;
		}

		$post_data = array(
			'post_type'   => 'service_message',
			'post_status' => 'publish',
			'post_title'  => time(),
			'post_author' => $contract->post_author,
		);

		/**
		 * Append the template after the consultant message.
		 */
		$post_data['post_content'] .= '%STAGE% '.$this->gt('completed.','idealbiz-service-request').' %ORDER_LINK%';


		if ( false !== strpos( $post_data['post_content'], '%ORDER_LINK%' ) ) {

			$checkout_url = $this->get_translated_payment_url( $order );

			$order_link = sprintf(
				'<div class="d-table m-auto m-t-10 proposal_'.$contract->ID.' stage_h_'.strtolower(get_field( 'stage', $contract->ID ) ).'">
				<a class="btn btn-blue blue--hover h-expert" style="font-size: 15px;" href="%1$s" title="%2$s">%3$s</a></div>',
				$checkout_url,
				$this->gt( 'Accept and payment link', 'idealbiz-service-request' ),
				$this->gt( 'Accept and payment', 'idealbiz-service-request' )
			);

			$post_data['post_content'] = str_replace( '%ORDER_LINK%', $order_link, $post_data['post_content'] );
		}

		if ( false !== strpos( $post_data['post_content'], '%STAGE%' ) ) {

			$stage = get_field( 'stage', $contract->ID );

			$stageTranslation = '';
			switch ( strtolower( $stage ) ) { 
				case 'adjudication':
					$stageTranslation = $this->gt('Adjudication');
					$stage = 'Adjudication';
					break;

				case 'intermediate':
					$stageTranslation = $this->gt('Intermediate');
					$stage = 'Intermediate';
					break;

				case 'conclusion':
					$stageTranslation = $this->gt('Conclusion');
					$stage = 'Conclusion';
					break;
			}

			$post_data['post_content'] = str_replace( '%STAGE%', $stageTranslation, $post_data['post_content'] );
			update_field('aux_message', $stageTranslation.' '.$this->gt('completed.'), $service_request->ID );

			if(strtolower($stage) == 'conclusion')
				update_field('aux_message', $this->gt('Service Completed by Expert','idealbiz-service-request'), $service_request->ID );
		}


		$message_id = wp_insert_post( $post_data );

		if ( $message_id ) {
			update_field( 'service_request', $service_request->ID, $message_id );

			$service_message = new HelperServiceMessage();
			if(strtolower($stage) == 'conclusion'){
				$service_message->send_service_message_notification( $message_id, false, $this->gt('Service has been completed.','idealbiz-service-request') );
			}else{
				$service_message->send_service_message_notification( $message_id );
			}
		}
	}

	/**
	 * Handles the i18n for the order checkout url.
	 *
	 * @param \WC_Order $order
	 * @return string $checkout_url i18n checkout payment url.
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	private function get_translated_payment_url( \WC_Order $order ) {

		$order_param = wp_parse_url( $order->get_checkout_payment_url() );

		$query_params = array();
		parse_str( $order_param['query'], $query_params );

		$icl_language_code = ICL_LANGUAGE_CODE;

		$checkout_url_id = icl_object_id( get_option( 'woocommerce_checkout_page_id' ), 'page', false, $icl_language_code );
		$checkout_url    = get_the_permalink( $checkout_url_id );

		// clean checkout url
		$neddle_index = strpos( $checkout_url, '/?' );
		if ( false !== $neddle_index ) {
			$checkout_url = substr( $checkout_url, 0, $neddle_index );
		}
		$checkout_url .= "/order-pay/{$order->id}?lang={$icl_language_code}&pay_for_order=true&key={$query_params['key']}";

		return $checkout_url;
	}
}
