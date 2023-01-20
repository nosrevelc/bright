<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

global $pID;
global $bought_upgrades;


do_action( 'woocommerce_before_mini_cart' ); ?>

<?php if ( ! WC()->cart->is_empty() ) : ?>

	<ul class="woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr( $args['list_class'] ); ?>">
		<?php
		do_action( 'woocommerce_before_mini_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );	
			$pID= $product_id;
			

			?> 

			<?php
			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
				$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
				?>
				<li class="woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
					<div class="text-left d-flex">
						<p class="font-weight-semi-bold w-p-100 l-h-35 m-b-0"><?php echo $product_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p> 
						<?php 
						echo '<p class="w-60px m-b-0 l-h-38 text-right">';
							if(get_post_meta($pID, '_product_type_meta_key', true) == 'upgrade_plan'){
								
							}else{
								echo get_woocommerce_currency_symbol().$_product->get_regular_price();
							}
						echo '</p>'; ?>
					</div>
					<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php // echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . $product_price . '</span>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</li>
				<?php
			}

			?>

			<script>
				

				console.log("<?php echo $product_id; ?>");
				//jQuery('li[data-pid="<?php echo $product_id; ?>"]').find('.single_add_to_cart_button').click();

				//jQuery('li[data-pid="<?php echo $product_id; ?>"] form').submit();


				if (!jQuery('li[data-pid="<?php echo $product_id; ?>"]').hasClass("active")) {
					jQuery('.plist li').removeClass('active');
					jQuery('.plist li .btn-blue').removeClass('activeB');

					jQuery('li[data-pid="<?php echo $product_id; ?>"]').find('.single_add_to_cart_button').click();

					jQuery('li[data-pid="<?php echo $product_id; ?>"]').addClass('active');
					jQuery('li[data-pid="<?php echo $product_id; ?>"] .btn-blue').addClass('activeB');
				}


				
				


				jQuery('input[data-check]').attr( 'checked', false );
				jQuery('p[data-select]').each(function(){
					var s=jQuery(this).data('select');
					console.log(s);
					jQuery('input[data-check="'+s+'"]').attr( 'checked', true );
				});
			</script> 


			<?php
		}

		do_action( 'woocommerce_mini_cart_contents' );
		?>
	</ul>

	<?php if( WC()->cart->get_taxes( )){?>
		<p class="woocommerce-mini-cart__total total">
			<?php
			/**
			 * Hook: woocommerce_widget_shopping_cart_total.
			 *
			 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
			 */
			do_action( 'woocommerce_widget_shopping_cart_total' );
			?>
		</p>
		<p class="woocommerce-mini-cart__total total_taxes">
			<strong><?php _e('Total VAT', 'woocommerce' ); ?>:</strong>
			<?php 
				 echo wc_cart_totals_taxes_total_html();
			?>
		</p>
	<?php } ?>
	
	<p class="woocommerce-mini-cart__total total_with_Taxes">
		<strong><?php _e('Total', 'woocommerce' ); ?>:</strong>
		<span class="woocommerce-Price-amount amount">
			<span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol();?></span><?php echo WC()->cart->total; ?>
		</span>
	</p>

	<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

	<p class="woocommerce-mini-cart__buttons buttons"><?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?></p>

	<?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>

<?php else : ?>

	<p class="woocommerce-mini-cart__empty-message"><?php esc_html_e( 'No products in the cart.', 'woocommerce' ); ?></p>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>



