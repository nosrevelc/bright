<?php

/**
 * Pay for order form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-pay.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;


remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);

global $woocommerce;
$woocommerce->cart->empty_cart();
$params = array(
    'post_type' => 'product',
    'posts_per_page' => 1,
    'meta_query' => array(
        array(
            'key' => '_product_type_meta_key',
            'value' => 'listing_plan',
            'compare' => '='
        )
    )
);
$wc_query = new WP_Query($params);
global $post, $product;
if( $wc_query->have_posts() ) {  
    while( $wc_query->have_posts() ) {
        $wc_query->the_post();
        $pid= get_the_ID();
        WC()->cart->add_to_cart( $pid);
    }
}
wp_reset_query();



do_action( 'add_or_remove_coupons' );


$order_id= wc_get_order_id_by_order_key($_GET["key"]);
$order=wc_get_order($order_id);
$order->calculate_totals();
$order->save(); 

//$points_used = (int) get_post_meta( $order_id, 'used_points', 1 );
//echo $points_used;

$totals = $order->get_order_item_totals(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
?>
<form id="order_review" method="post" novalidate>
    <input type="hidden" name="order_id" id="order_id" value="<?php echo wc_get_order_id_by_order_key($_GET["key"]); ?>" />

    <div class="row">
        <div class="col-md-9" id="customer_details">

            <div class="block stroke dropshadow p-30 m-b-0 b-r-5 white--background woocommerce-billing-fields">

                <!-- Form -->
                <h3 class="woocommerce-column__title text"><?php esc_html_e('Billing details', 'woocommerce'); ?></h3>
                <?php do_action('woocommerce_before_checkout_billing_form', $order); ?>
                <div class="woocommerce-billing-fields__field-wrapper">
                    <?php
                    $fields = WC()->checkout->get_checkout_fields('billing');
                    foreach ($fields as $key => $field) {
                        $field_name = $key;

                        if (is_callable(array($order, 'get_' . $field_name))) {
                            $field['value'] = $order->{"get_$field_name"}('edit');
                        } else {
                            $field['value'] = $order->get_meta('_' . $field_name);
                        }

                        $field['value'] = get_user_meta(get_current_user_id(), $field_name, true);

                        woocommerce_form_field($key, $field, $field['value']);
                    }
                    ?>
                </div>
                <?php do_action('woocommerce_after_checkout_billing_form', $order); ?>

            </div>
        </div>
        <div class="col1-set col-md-3" id="payment" style="background: none;">
            <div id="order_review" class="woocommerce-checkout-review-order">

                <div class="block">
                    <table class="shop_table woocommerce-checkout-review-order-table widget woocommerce widget_shopping_cart block stroke dropshadow p-t-25 p-b-30 m-b-25 b-r-5 white--background">
                        <thead>
                            <tr>
                                <th class="product-name text-center">
                                    <h2 id="order_review_heading"><?php _e('Your Order', 'idealbiz'); ?></h2>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($order->get_items()) > 0) : ?>
                                <?php foreach ($order->get_items() as $item_id => $item) : ?>
                                    <?php
                                            if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
                                                continue;
                                            }
                                            ?>
                                    <tr class="<?php echo esc_attr(apply_filters('woocommerce_order_item_class', 'order_item', $item, $order)); ?>">
                                        <td class="product-name">

                                            <div class="text-left d-flex">
                                                <p class="font-weight-semi-bold w-p-100 l-h-35 m-b-0">
                                                    <?php
                                                            echo apply_filters('woocommerce_order_item_name', esc_html($item->get_name()), $item, false); // @codingStandardsIgnoreLine
                                                            do_action('woocommerce_order_item_meta_start', $item_id, $item, $order, false);
                                                            wc_display_item_meta($item);
                                                            do_action('woocommerce_order_item_meta_end', $item_id, $item, $order, false);

                                                            ?>
                                                </p>
                                                <p class="w-60px m-b-0 l-h-38 text-right"><?php echo $order->get_formatted_line_subtotal($item); ?></p>
                                            </div>
                                        </td><?php // @codingStandardsIgnoreLine 
                                                        ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <?php if ($totals) : ?>
                                <?php foreach ($totals as $total) : ?>
                                    <tr class="sub-total">
                                        <td><strong><?php echo $total['label']; ?></strong>
                                            <span class="woocommerce-Price-amount">
                                                <span class="woocommerce-Price-amount amount"><?php echo $total['value']; ?></span>
                                            </span>
                                        </td><?php // @codingStandardsIgnoreLine 
                                                        ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tfoot>
                    </table>
                </div>
            </div>

            <style>
                .product-name .woocommerce-Price-taxLabel {
                    display: none;
                }

                .sub-total .tax_label {
                    margin-right: 5px;
                }

                #order_review #payment ul.payment_methods li img.stripe-icon, #payment ul.payment_methods li img.stripe-icon {
                    max-width: 40px;
                    padding-left: 3px;
                    margin: 0;
                }

                #order_review #payment div.payment_box {
                    position: relative;
                    box-sizing: border-box;
                    width: 100%;
                    padding: 1em;
                    margin: 1em 0;
                    font-size: 0.92em;
                    border-radius: 2px;
                    line-height: 1.5;
                    background-color: #f3f5fa;
                    color: #515151;
                }

                #order_review #payment div.payment_box:before {
                    border-color: #f3f5fa!important;
                    border-right-color: transparent!important;
                    border-left-color: transparent!important;
                    border-top-color: transparent!important;
                     content: "";
                    display: block;
                    border: 1em solid #dfdcde;
                    position: absolute;
                    top: -0.75em;
                    left: 0;
                    margin: -1em 0 0 2em;
                }

            </style>

            <div class="block stroke dropshadow p-t-25 p-b-30 m-b-25 p-r-12 p-l-12 b-r-5 white--background">
                <?php if ($order->needs_payment()) : ?>
                    <ul class="wc_payment_methods payment_methods methods">
                        <?php
                            if (!empty($available_gateways)) {
                                foreach ($available_gateways as $gateway) {
                                    wc_get_template('checkout/payment-method.php', array('gateway' => $gateway));
                                }
                            } else {
                                echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters('woocommerce_no_available_payment_methods_message', esc_html__('Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce')) . '</li>'; // @codingStandardsIgnoreLine
                            }
                            ?>
                    </ul>
                <?php endif; ?>
                <div class="form-row">
                    <input type="hidden" name="woocommerce_pay" value="1" />

                    <?php wc_get_template('checkout/terms.php'); ?>

                    <?php do_action('woocommerce_pay_order_before_submit'); ?>

                    <?php echo apply_filters('woocommerce_pay_order_button_html', '<button type="submit" class="button alt" id="place_order" value="' . esc_attr($order_button_text) . '" data-value="' . esc_attr($order_button_text) . '">' . esc_html($order_button_text) . '</button>'); // @codingStandardsIgnoreLine 
                    ?>

                    <?php do_action('woocommerce_pay_order_after_submit'); ?>

                    <?php wp_nonce_field('woocommerce-pay', 'woocommerce-pay-nonce'); ?>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="coupons">
    <?php

    do_action('billing_footer', $checkout);
    ?>
</div>

<?php


	add_action('billing_footer', 'woocommerce_checkout_coupon_form', 10);

	do_action('woocommerce_before_checkout_form');
	



?>
<style>
#yith-par-message-cart, #yith-par-message-reward-cart{
    margin-top:30px;
}
    </style>

<script>
    jQuery(document).ready(function($) {

        if ($('#yith-par-message-cart.woocommerce-cart-notice').length > 1) {
            $('#yith-par-message-cart.woocommerce-cart-notice').first().hide();
        }

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

        $('#billing_nif').val('<?php echo get_user_meta(get_current_user_id(), 'vat_number', true); ?>');

        $("input[type='text']").change(function() {

            var orderID = $("#order_id").val();
            var firstName = $("#billing_first_name").val();
            var lastName = $("#billing_last_name").val();
            var billingCompany = $("#billing_company").val();
            var billingAddress_1 = $("#billing_address_1").val();
            var billing_address_2 = $("#billing_address_2").val();
            var billing_city = $("#billing_city").val();
            var billing_state = $("#billing_state").val();
            var billing_postcode = $("#billing_postcode").val();
            var billing_phone = $("#billing_phone").val();
            var billing_email = $("#billing_email").val();
            var billing_country = $("#billing_country").val();
            var billing_nif = $("#billing_nif").val();
            var vat_number = $("#billing_nif").val();



            $.ajax({
                    method: "POST",
                    url: favorites_data.ajaxurl,
                    data: {
                        action: "edit_order_billing",
                        orderID: orderID,
                        firstName: firstName,
                        lastName: lastName,
                        billingCompany: billingCompany,
                        billing_country: billing_country,
                        billing_address_2: billing_address_2,
                        billingAddress_1: billingAddress_1,
                        billing_city: billing_city,
                        billing_state: billing_state,
                        billing_postcode: billing_postcode,
                        billing_phone: billing_phone,
                        billing_email: billing_email,
                        billing_nif: billing_nif,
                        vat_number: vat_number
                    }
                })
                .done(function(msg) {
                    console.log(msg);
                });
        });
        $("input[id='billing_first_name']").trigger('change');
    });
</script>