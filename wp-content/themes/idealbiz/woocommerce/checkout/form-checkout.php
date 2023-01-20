<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

$hidden_points = get_field('hidden_points');
/* var_dump($hidden_points); */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );



// If checkout registration is disabled and not logged in, the user cannot checkout.
/* if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
} */

if (!is_user_logged_in()) {
    $redirect = ( strpos( $_SERVER['REQUEST_URI'], '/options.php' ) && wp_get_referer() ) ? wp_get_referer() : set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
    wp_redirect(get_site_url().'/'.pll_languages_list()[0].'/login-register/?redirect_to='.$redirect );
}
 
?> 

	<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
		<div class="row">

		<?php
		$related_post_id=$_POST['related_post_id'];
		if(!$_POST['related_post_id']){
			$related_post_id= explode('&id=',$_SERVER['HTTP_REFERER'])[1];
		}
		?>
		<input type="hidden" name="related_post_id" value="<?php echo $related_post_id; ?>" />

			<div class="col-md-9" id="customer_details"> 
				<div class="block stroke dropshadow p-30 m-b-25 b-r-5 white--background">
					<?php if ( $checkout->get_checkout_fields() ) : ?>
						<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
							<?php do_action( 'woocommerce_checkout_billing' ); ?>
							<?php /*
							<div class="col-2">
								<?php do_action( 'woocommerce_checkout_shipping' ); ?>
							</div> */ ?>
						
						<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
					<?php endif; ?>
					<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
				</div>
			</div>

			<div class="col1-set col-md-3" id="customer_details">
				
				<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
				
				
				
				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

				<div id="order_review" class="woocommerce-checkout-review-order">
					<?php 
					do_action( 'woocommerce_checkout_order_review' ); ?>
				</div>	
				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

			</div>
		</div>
	</form>
	<div class="coupons">
		<?php
			do_action( 'billing_footer', $checkout );
		?>
	</div>

	<?php
	if ($hidden_points){

	add_action('billing_footer', 'woocommerce_checkout_coupon_form', 10);

	do_action('woocommerce_before_checkout_form', $checkout);
	}
	?>


<script>
    jQuery(document).ready(function($) {
        if($('#billing_first_name').val()==''){
            <?php
            $user_info = get_userdata(get_current_user_id());
            $first_name = $user_info->first_name;
            ?>
            $('#billing_first_name').val('<?php echo $first_name ?>')
        }
        if($('#billing_last_name').val()==''){
            <?php
            $user_info = get_userdata(get_current_user_id());
            $last_name = $user_info->last_name;
            ?>
            $('#billing_last_name').val('<?php echo $last_name ?>')
        }
	});
</script>
