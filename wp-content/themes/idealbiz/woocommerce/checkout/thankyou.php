<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
$cl_rb_lsid = $_SESSION['rb-lsid'];
?>

<div class="woocommerce-order">

	<?php if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</p>

		<?php else : ?>

			<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

			<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

				<li class="woocommerce-order-overview__order order">
					<?php esc_html_e( 'Order number:', 'woocommerce' ); ?>
					<strong><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</li>

				<li class="woocommerce-order-overview__date date">
					<?php esc_html_e( 'Date:', 'woocommerce' ); ?>
					<strong><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</li>

				<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
					<li class="woocommerce-order-overview__email email">
						<?php esc_html_e( 'Email:', 'woocommerce' ); ?>
						<strong><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>
				<?php endif; ?>

				<li class="woocommerce-order-overview__total total">
					<?php esc_html_e( 'Total:', 'woocommerce' ); ?>
					<strong><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</li>

				<?php if ( $order->get_payment_method_title() ) : ?>
					<li class="woocommerce-order-overview__payment-method method">
						<?php esc_html_e( 'Payment method:', 'woocommerce' ); ?>
						<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
					</li>
				<?php endif; ?>

			</ul>
			
		    <div class="row m-b-35 backtobroker" style="display:none;">
				<a href="#" class="btn btn-blue blue--hover" style="margin: 0 auto"><?php echo __('Back to Subscriptions', 'idealbiz' ); ?></a>
			</div>
			
			
			<?php
			//NPMM - Atualiza na Recomendação de Negócios o campo de Order e Status

			if(isset($cl_rb_lsid )){
				$rb_id = $cl_rb_lsid ;
				/* cl_alerta($rb_id); */
				$rb_status_order = $order->status;
				$rb_number_order = get_field('rb_number_order');
				$rb_order_number = $order->get_order_number();
				update_field( 'rb_number_order' ,$rb_order_number, $rb_id );
				update_field( 'rb_status_order' ,$rb_status_order, $rb_id );
			}

			if(isset($_SESSION['cl-rs-lsid'])){
				//NPMM - Atualiza na Recomendação de Negócios o campo de Order e Status. 				
				$cl_rs_id = $_SESSION['cl-rs-lsid'];
				
				$cl_rs_status_order = $order->status;
				$cl_order_subtotal = $order->get_subtotal();
				$cl_rs_number_order = get_field('rs_order_id');
				$cl_rs_order_number = $order->get_order_number();
				$cl_rs_currency = get_woocommerce_currency();
				update_field( 'rs_order_id' ,$cl_rs_order_number, $cl_rs_id );
				update_field( 'rs_status_order' ,$cl_rs_status_order, $cl_rs_id );
				update_field( 'rs_comission' ,$cl_order_subtotal, $cl_rs_id );
				update_field( 'rs_currency' ,$cl_rs_currency, $cl_rs_id );
				//update_field( 'rs_id_request_type' ,$cl_rs_id, $cl_rs_id );
					
			}
			
			foreach( $order->get_items() as $item ){ 
				$product_id = $item->get_product_id();
				$ptype = get_post_meta($product_id, '_product_type_meta_key', true);
				if($ptype == 'wanted_plan' || $ptype == 'listing_plan'){ 
					foreach( $order->get_items() as $item ){
						$product_id = $item->get_product_id(); 
						$lid = get_post_meta( $product_id, 'related_post_id', true );
					?>
						<div class="row m-b-35 backtolistings" style="display:block;">
							<a href="<?php echo wc_get_endpoint_url('mylistings', '', get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="btn btn-blue blue--hover" style="margin: 0 auto">
								<?php echo __('Back to My Listings', 'idealbiz' ); ?>
							</a>
						</div>
				<?php }
				}
			}

			?>

		


			<script>
				function getCookie(name) {
				var nameEQ = name + "=";
				var ca = document.cookie.split(';');
				for(var i=0;i < ca.length;i++) {
					var c = ca[i];
					while (c.charAt(0)==' ') c = c.substring(1,c.length);
					if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
				}
				return null;
				}
				function eraseCookie(name) {
					document.cookie = name + '=; Max-Age=0'
				}
				jQuery(document).ready(($) => {
					var x = getCookie('backToSubscription');
					if (x) {
						$('.backtobroker a').attr('href',x);
						$('.backtobroker').css('display','block'); 
						//eraseCookie('backToSubscription');  
					}

					var x = getCookie('backToListing');
					if (x) {
						//console.log('asd');
						//$('.backtomylistings a').attr('href',x);
						//$('.backtomylistings').css('display','block'); 
					}

				});
			</script>

		<?php endif; ?>

		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

	<?php endif; ?>

</div>
