<?php
/**
 * Gravity form processing helper class.
 *
 * @link  http://widgilabs.com/
 * @since 1.0.0
 */

namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Gforms;

use WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Email\EmailTemplate;

class HelperServiceSatisfaction {

	public function register_hooks() {
		//remover cupao apos service request #222
		//add_filter( 'gform_after_submission', array( $this, 'send_woo_coupon' ), 10, 2 );
	}

	/**
	 * Creates a new woocommerce coupon and sends it to customer.
	 *
	 * @param array $entry
	 * @param array $form
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	public function send_woo_coupon( $entry, $form ) {




		// Check if is service satisfaction form.
		if ( $form['cssClass'] !== 'service_satisfaction_form' ) {
			return;
		}


		$contract_id = 0;
		$user_email  = '';

		foreach ( $form['fields'] as $field ) {

			if ( $field->type === 'hidden' && $field->label === 'contract_id' ) {

				$contract_id = $entry[ $field->id ];
			}

			/**
			 * Link coupon with user email.
			 */
			if ( $field->type === 'hidden' && $field->label === 'user_email' ) {

				$user_email = $entry[ $field->id ];
			}
		}


		$service_request_id = str_replace('service_contract_','',explode(':',get_the_title($contract_id))[0]);
		$user_email = get_field('customer',$service_request_id)->user_email;


		// check if this contract has no submited form already.
		$already_submitted = get_post_meta($contract_id , 'already_submitted', true );
		if ( $already_submitted ) {
			return;
		}

		$current_user     = get_user_by( 'email', $user_email );
		$user_compliment  = __( 'Hello', 'idealbiz-service-request' );
		$user_compliment .= ' ' . $current_user->get( 'first_name' ) . ',';

		$coupon_code = $this->get_coupon_code();

		$coupon = array(
			'post_title'  => $coupon_code,
			'post_status' => 'publish',
			'post_author' => 1,
			'post_type'   => 'shop_coupon',
		);

		$new_coupon_id = wp_insert_post( $coupon );

		// Add coupon meta data.
		update_post_meta( $new_coupon_id, 'discount_type', 'percent' );
		// TODO: get value from option
		update_post_meta( $new_coupon_id, 'coupon_amount', '20' );
		update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
		// TODO: Get product from option.
		update_post_meta( $new_coupon_id, 'product_ids', '' );
		update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
		update_post_meta( $new_coupon_id, 'usage_limit', '1' );
		update_post_meta( $new_coupon_id, 'expiry_date', '' );
		update_post_meta( $new_coupon_id, 'apply_before_tax', 'no' );
		update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
		update_post_meta( $new_coupon_id, 'exclude_sale_items', 'no' );
		update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
		update_post_meta( $new_coupon_id, 'product_categories', '' );
		update_post_meta( $new_coupon_id, 'exclude_product_categories', '' );
		update_post_meta( $new_coupon_id, 'minimum_amount', '' );
		update_post_meta( $new_coupon_id, 'customer_email', array( $user_email ) );

		/**
		 * Update contract to mark user has reclaimed the coupon.
		*/
		update_post_meta( $contract_id, 'already_submitted', true );

		// Send email with coupon code.

		// TODO: templates for the title and the message body for notifications.
		$subject = __( 'Here is you coupon code', 'idealbiz-service-request' );
		$body    = __('Coupon code: %COUPON_CODE%', 'idealbiz-service-request' ); 

		$body_template = EmailTemplate::get_email_body( wpautop( $body ) );

		$body_template = str_replace( '%%HEAD_MESSAGE%%', __( 'Your coupon code', 'idealbiz-service-request' ), $body_template );
		$body_template = str_replace( '%COUPON_CODE%', $coupon_code, $body_template );
		$body_template = str_replace( '%%USER_COMPLIMENT%%', $user_compliment, $body_template );

		$headers = array( 'Content-Type: text/html; charset=UTF-8' ); 
		$result = wp_mail( $user_email, $subject, $body_template, $headers );
	}

	/**
	 * Generate a random string for the coupon code.
	 *
	 * Can accept a length to return a string macthing that lenght.
	 *
	 * @param integer $code_length
	 * @return string
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	private function get_coupon_code( $code_length = 20 ) {

		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		return substr( str_shuffle( $permitted_chars ), 0, $code_length );
	}
}
