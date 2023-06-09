<?php
/**
 * Recurring totals
 *
 * @author 		Prospress
 * @package 	WooCommerce Subscriptions/Templates
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$display_th = true;

?>
<style>
.order-total{display: none;}
.order-total.recurring-total{display: block;}
</style>
			<tr class="recurring-totals">
				<th><?php esc_html_e( 'Subscription Details', 'idealbiz' ); ?></th>
			</tr>

			<?php /* foreach ( $recurring_carts as $recurring_cart_key => $recurring_cart ) : ?>
				<?php if ( 0 == $recurring_cart->next_payment_date ) : ?>
					<?php continue; ?>
				<?php endif; ?>
				<tr class="cart-subtotal recurring-total">
					<div class="text-left d-flex">
					<?php if ( $display_th ) : $display_th = false; ?>
						<p class="font-weight-semi-bold w-p-100 l-h-35 m-b-0"><?php esc_html_e( 'Subtotal', 'woocommerce-subscriptions' ); ?></p>
						<p class="w-60px m-b-0 l-h-38 text-right"><?php wcs_cart_totals_subtotal_html( $recurring_cart ); ?></p>
					<?php else : ?>
						<p class="font-weight-semi-bold w-p-100 l-h-35 m-b-0"><?php wcs_cart_totals_subtotal_html( $recurring_cart ); ?></p>
					<?php endif; ?>
					</div>
				</tr>
			<?php endforeach; */ ?>
			<?php $display_th = true; ?>

			<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
				<?php foreach ( $recurring_carts as $recurring_cart_key => $recurring_cart ) : ?>
					<?php if ( 0 == $recurring_cart->next_payment_date ) : ?>
						<?php continue; ?>
					<?php endif; ?>
					<?php foreach ( $recurring_cart->get_coupons() as $recurring_code => $recurring_coupon ) : ?>
						<?php if ( $recurring_code !== $code ) { continue; } ?>
							<tr class="cart-discount coupon-<?php echo esc_attr( $code ); ?> recurring-total">
								<?php if ( $display_th ) : $display_th = false; ?>
									<th rowspan="<?php echo esc_attr( $carts_with_multiple_payments ); ?>"><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
									<td data-title="<?php wc_cart_totals_coupon_label( $coupon ); ?>"><?php wcs_cart_totals_coupon_html( $recurring_coupon, $recurring_cart ); ?></td>
								<?php else : ?>
									<td><?php wcs_cart_totals_coupon_html( $recurring_coupon, $recurring_cart ); ?></td>
								<?php endif; ?>
							</tr>
					<?php endforeach; ?>
				<?php endforeach; ?>
				<?php $display_th = true; ?>
			<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
			<?php wcs_cart_totals_shipping_html(); ?>
		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<?php foreach ( $recurring_carts as $recurring_cart_key => $recurring_cart ) : ?>
				<?php if ( 0 == $recurring_cart->next_payment_date ) : ?>
					<?php continue; ?>
				<?php endif; ?>
				<?php foreach ( $recurring_cart->get_fees() as $recurring_fee ) : ?>
					<?php if ( $recurring_fee->id !== $fee->id ) { continue; } ?>
					<tr class="fee recurring-total">
						<th><?php echo esc_html( $fee->name ); ?></th>
						<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endforeach; ?>
		<?php endforeach; ?>

		<?php if ( WC()->cart->tax_display_cart === 'excl' ) : ?>
			<?php if ( get_option( 'woocommerce_tax_total_display' ) === 'itemized' ) : ?>

				<?php foreach ( WC()->cart->get_taxes() as $tax_id => $tax_total ) : ?>
					<?php foreach ( $recurring_carts as $recurring_cart_key => $recurring_cart ) : ?>
						<?php if ( 0 == $recurring_cart->next_payment_date ) : ?>
							<?php continue; ?>
						<?php endif; ?>
						<?php foreach ( $recurring_cart->get_tax_totals() as $recurring_code => $recurring_tax ) : ?>
							<?php if ( ! isset( $recurring_tax->tax_rate_id ) || $recurring_tax->tax_rate_id !== $tax_id ) { continue; } ?>
							<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $recurring_code ) ); ?> recurring-total">
								<?php if ( $display_th ) : $display_th = false; ?>
									<th><?php echo esc_html( $recurring_tax->label ); ?></th>
									<td data-title="<?php echo esc_attr( $recurring_tax->label ); ?>"><?php echo wp_kses_post( wcs_cart_price_string( $recurring_tax->formatted_amount, $recurring_cart ) ); ?></td>
								<?php else : ?>
									<th></th>
									<td><?php echo wp_kses_post( wcs_cart_price_string( $recurring_tax->formatted_amount, $recurring_cart ) ); ?></td>
								<?php endif; ?>
							</tr>
						<?php endforeach; ?>
					<?php endforeach; ?>
					<?php $display_th = true; ?>
				<?php endforeach; ?>

			<?php else : ?>

				<?php foreach ( $recurring_carts as $recurring_cart_key => $recurring_cart ) : ?>
					<?php if ( 0 == $recurring_cart->next_payment_date ) : ?>
						<?php continue; ?>
					<?php endif; ?>
					<tr class="tax-total recurring-total d-none">
						<?php if ( $display_th ) : $display_th = false; ?>
						    <td data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?>
							<?php echo wp_kses_post( wcs_cart_price_string( $recurring_cart->get_taxes_total(), $recurring_cart ) ); ?></td>
						<?php else : ?>
							<td><?php echo wp_kses_post( wcs_cart_price_string( $recurring_cart->get_taxes_total(), $recurring_cart ) ); ?></td>
						<?php endif; ?>
					</tr>
				<?php endforeach; ?>
				<?php $display_th = true; ?>
			<?php endif; ?>
		<?php endif; ?>


		

		<?php foreach ( $recurring_carts as $recurring_cart_key => $recurring_cart ) : ?>
			<?php if ( 0 == $recurring_cart->next_payment_date ) : ?>
				<?php continue; ?>
			<?php endif; ?>
			<tr class="order-total recurring-total">
				<td class="d-inline-block m-0" data-title="<?php esc_attr_e( 'Recurring Total', 'woocommerce-subscriptions' ); ?>">
					<?php _e('Total','woocommerce'); ?> <?php wcs_cart_totals_order_total_html( $recurring_cart ); ?>
				</td>
			</tr>
		<?php endforeach; ?>
