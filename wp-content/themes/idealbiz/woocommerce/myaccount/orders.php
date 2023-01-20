<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
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

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php if ( $has_orders ) : ?>
	<?php if ( WC_Subscriptions::is_woocommerce_pre( '2.6' ) ) : ?>
	<h2><?php esc_html_e( 'My Orders', 'woocommerce-orders' ); ?></h2>
	<?php endif; ?>

	<h2><?php esc_html_e( 'My Orders', 'woocommerce-orders' ); ?></h2>
	<table class="
	woocommerce-orders-table woocommerce-MyAccount-orders shop_table 
	shop_table_responsive my_account_orders account-orders-table
	block stroke dropshadow p-30 m-b-25 b-r-5 white--backgroun
	">
		<thead>
			<tr>
				<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
					<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><h2 class="nobr"><?php echo esc_html( $column_name ); ?></h2></th>
				<?php endforeach; ?>
			</tr>
		</thead>

		<tbody>
			<?php
			foreach ( $customer_orders->orders as $customer_order ) {
				$order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
				$item_count = $order->get_item_count() - $order->get_item_count_refunded();
				?>
				<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
					<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
						<td class="woocommerce-orders-table__cell min-w-130 woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?> <?php if ( 'order-actions' === $column_id ){ echo ' d-inline-flex '; } ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
							<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
								<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

							<?php elseif ( 'order-number' === $column_id ) : ?>
								<div>
								<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
									<?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ); ?>
									<?php
										$order = wc_get_order( $customer_order );
										$items = $order->get_items();
										foreach ( $items as $item ) {
											//print_r($items);
											

												$lid = get_post_meta( $item->get_product_id(), 'related_post_id', true );
												if (get_post_type($lid) == 'listing' ) {
													$post = get_post($lid);
													echo '
															&bull; <a class="font-weight-bold" href="'.getLinkByTemplate('submit-listing.php').'?listing_id='.$post->ID.'">
															<span class="font-weight-bold">'.$post->post_title.'</span>
															<span class="font-weight-normal ">'.esc_html(get_field('location',$lid)->name).'</span>
															<span>'.wc_price(get_field('price_manual',$lid)).'</span></a>
														';

														$value = get_post_meta($item->get_product_id(), '_product_type_meta_key', true);
														if($value == 'listing_plan' || $value == 'wanted_plan'){
															echo '<span class="small">(' . __('Listing', 'idealbiz') . ': ';
														}
														echo $item->get_name();
														if(count($items)>1){
															echo '...';
														}
														echo ')</span>'.
														imageModal(get_field('featured_image',$lid)['id'],'m-h-38 m-w-38 d-block pull-right');
													} 
		
											break;
										}
									?>
								</a>
								</div>

							<?php elseif ( 'order-date' === $column_id ) : ?>
								<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

							<?php elseif ( 'order-status' === $column_id ) : ?>
								<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>

							<?php elseif ( 'order-total' === $column_id ) : ?>
								<?php
								/* translators: 1: formatted order total 2: total order items */
								echo $order->get_formatted_order_total();
								?>

							<?php elseif ( 'order-actions' === $column_id ) : ?>
								<?php
								$actions = wc_get_account_orders_actions( $order );

								if ( ! empty( $actions ) ) {
									foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
										echo '<a href="' . esc_url( $action['url'] ) . '" class="btn btn-blue blue--hover ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
									}
								}
								?>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
			<?php endif; ?>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<?php esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
