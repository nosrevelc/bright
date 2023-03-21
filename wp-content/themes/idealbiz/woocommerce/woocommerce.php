<?php

use WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Email\EmailTemplate;

define('WEBSITE_SYSTEM',get_field('website_sr_system', 'options')["value"]);
define('LISTING_SYSTEM',get_field('listing_system', 'options')["value"]);


require_once(ABSPATH . 'wp-content/plugins/idealbiz-service-request/lib/WooCommerce/EndpointServiceRequest.php');
$epsr = new EndpointServiceRequest;

global $bought_upgrades;


function it($toTranslate, $domain= '', $l= ''){
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
            if($l!=''){ 
                $test=$l;
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
 * Add Wocommerce Theme Support - (required)
 *
 * @since  1.0.0
 */
function mytheme_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );


/**
 * Register sidebar peview-cart chosen packs 
 *
 * @since  1.0.0
 */
function listing_packs_sidebar() {
    $args = array(
        'id' => 'listing-packs-sidebar',
        'name' => __('Listing Widget Sidebar','idealbiz'),
        'description' => __('This is my widget description.','idealbiz'),
        'before_title' => '<h2 class="widget-title m-b-10">',
        'after_title' => '</h2>',
        'before_widget' => '<section id="%1$s" class="widget %2$s block stroke dropshadow p-t-25 p-b-10 m-b-25 b-r-5 white--background">',
        'after_widget' => '</section>'
    );
register_sidebar( $args );
}
add_action( 'widgets_init', 'listing_packs_sidebar' );


/**
 * Adds a Metabox on side in w-admin for product type choose
 *
 * @since  1.0.0
 */
function product_type_add_custom_box()
{
    $screens = ['product'];
    foreach ($screens as $screen) {
        add_meta_box(
            'product_type_box_id',  // Unique ID
            'Choose Product Type',  // Box title
            'product_type_custom_box_html',  // Content callback, must be of type callable
            $screen,                   // Post type
            'side'
        );
    }
}
add_action('add_meta_boxes', 'product_type_add_custom_box');


/**
 * Add options to product metabox sidebar
 *
 * @since  1.0.0
 * @param  WP_Post Current Post
 */
function product_type_custom_box_html($post) 
{
    $value = get_post_meta($post->ID, '_product_type_meta_key', true);
    ?>
    <label for="product_type_field">Select Product Type <span class="acf-required">*</span></label>
    <select name="product_type_field" id="product_type_field" class="postbox" style="margin-top: 15px;">
        <option value="">Select something...</option>
        <option value="listing_plan" <?php selected($value, 'listing_plan'); ?>>Listing Plan</option>
        <option value="broker_plan" <?php selected($value, 'broker_plan'); ?>>Broker Plan</option>
        <option value="premium_buyer_plan" <?php selected($value, 'premium_buyer_plan'); ?>>Premium Buyer Plan</option>
        <option value="adjudication_product" <?php selected($value, 'adjudication_product'); ?>>Service Adjudication</option>
        <option value="intermediate_product" <?php selected($value, 'intermediate_product'); ?>>Intermediate service</option>
        <option value="conclusion_product" <?php selected($value, 'conclusion_product'); ?>>Service Conclusion</option>
        <option value="wanted_plan" <?php selected($value, 'wanted_plan'); ?>>Wanted Business</option>
        <option value="upgrade_plan" <?php selected($value, 'upgrade_plan'); ?>>Upgrade Plan</option>

        <?php if(WEBSITE_SYSTEM == '1'){  ?>
        <option value="lead" <?php selected($value, 'lead'); ?>>Lead Purchase</option>
        <option value="expert_fee" <?php selected($value, 'expert_fee'); ?>>Expert Fee</option>
        <option value="contact_lead" <?php selected($value, 'contact_lead'); ?>>Expert Contact Purchase</option>
        <option value="advertiser_lead" <?php selected($value, 'advertiser_lead'); ?>>Advertiser Contact Purchase</option>
        <?php } ?>
        
        
    </select>
    <script>
        jQuery('#product_type_field').change(function(){
            jQuery('#publish').click();
        });
    </script>
    <?php
}


/**
 * Save Product choose Metabox Value
 *
 * @since  1.0.0
 * @param  int Post_ID.
 */
function product_type_save_postdata($post_id)
{
    if (array_key_exists('product_type_field', $_POST)) {
        update_post_meta(
            $post_id,
            '_product_type_meta_key',
            $_POST['product_type_field']
        );
    }
}
add_action('save_post', 'product_type_save_postdata');
 

/**
 * Modify the Posts Query for Products of listings 
 *
 * @since  1.0.0
 */
if(isset($_GET['ptype'])){
    if( current_user_can('editor') || current_user_can('administrator') || get_current_user_id()==get_field( 'owner', $_GET['id'] )['ID'] ){
                add_action( 'woocommerce_product_query', 'so_27971630_product_query' );
                function so_27971630_product_query( $q ){
                    $meta_query = $q->get( 'meta_query' );
                    $meta_query[] = array(
                        'key' => '_product_type_meta_key',
                        'value' => $_GET['ptype'].'_plan',
                        'compare' => '='
                        );
                    $q->set( 'meta_query', $meta_query );
                }
    }else{
        wp_redirect( home_url(), 302 );
        exit;
    }
}


/**
 * Allow only one Product in cart at the time
 *
 * @since  1.0.0
 * @param  Woocommerce_cart
 * @return Wocommerce_cart
 */
add_filter( 'woocommerce_add_cart_item_data', 'woo_custom_add_to_cart' );
function woo_custom_add_to_cart( $cart_item_data ) {
    global $woocommerce;
    $woocommerce->cart->empty_cart();
    return $cart_item_data;
}


/**
 * Check product to virtual on creating product.
 *
 * @since  1.0.0
 */
add_filter( 'product_type_options', 'autocheck_vd');
function autocheck_vd( $arr ){
    $arr['virtual']     ['default'] = "yes"; 
    return $arr;
}
function my_woocommerce_product_after_variable_attributes( $loop, $variation_data, $variation ) {
    echo "
    <script>
        jQuery('input.variable_is_virtual').attr( 'checked', 'checked' ).trigger( 'change' );
    </script>
    <style>
        .plan_duration_field{display: inline-block;}
    </style>
    ";
}add_action( "woocommerce_product_after_variable_attributes", "my_woocommerce_product_after_variable_attributes" , 10, 3 );



/**
 * Outputs a checkout/address form field. // default function
 *
 * @param string $key Key.
 * @param mixed  $args Arguments.
 * @param string $value (default: null).
 * @return string
 */
function woocommerce_form_field( $key, $args, $value = null ) {
    $defaults = array(
        'type'              => 'text',
        'label'             => '',
        'description'       => '',
        'placeholder'       => '',
        'maxlength'         => false,
        'required'          => false,
        'autocomplete'      => false,
        'id'                => $key,
        'class'             => array(),
        'label_class'       => array(),
        'input_class'       => array(),
        'return'            => false,
        'options'           => array(),
        'custom_attributes' => array(),
        'validate'          => array(),
        'default'           => '',
        'autofocus'         => '',
        'priority'          => '',
    );
    $args = wp_parse_args( $args, $defaults );
    $args = apply_filters( 'woocommerce_form_field_args', $args, $key, $value );
    if ( $args['required'] ) {
        $args['class'][] = 'validate-required';
        $required        = '&nbsp;<abbr class="required" title="' . esc_attr__( 'required', 'woocommerce' ) . '">*</abbr>';
    } else {
        $required = '&nbsp;<span class="optional">(' . esc_html__( 'optional', 'woocommerce' ) . ')</span>';
    }
    if ( is_string( $args['label_class'] ) ) {
        $args['label_class'] = array( $args['label_class'] );
    }
    if ( is_null( $value ) ) {
        $value = $args['default'];
    }
    $custom_attributes         = array();
    $args['custom_attributes'] = array_filter( (array) $args['custom_attributes'], 'strlen' );
    if ( $args['maxlength'] ) {
        $args['custom_attributes']['maxlength'] = absint( $args['maxlength'] );
    }
    if ( ! empty( $args['autocomplete'] ) ) {
        $args['custom_attributes']['autocomplete'] = $args['autocomplete'];
    }
    if ( true === $args['autofocus'] ) {
        $args['custom_attributes']['autofocus'] = 'autofocus';
    }
    if ( $args['description'] ) {
        $args['custom_attributes']['aria-describedby'] = $args['id'] . '-description';
    }
    if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
        foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
            $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
        }
    }
    if ( ! empty( $args['validate'] ) ) {
        foreach ( $args['validate'] as $validate ) {
            $args['class'][] = 'validate-' . $validate;
        }
    }
    $field           = '';
    $label_id        = $args['id'];
    $sort            = $args['priority'] ? $args['priority'] : '';
    $field_container = '<div class="form-row woocommerce-form input-group %1$s" id="%2$s" data-priority="' . esc_attr( $sort ) . '">%3$s</div>';
    switch ( $args['type'] ) {
        case 'country':
            $countries = 'shipping_country' === $key ? WC()->countries->get_shipping_countries() : WC()->countries->get_allowed_countries();
            if ( 1 === count( $countries ) ) {
                $field .= '<strong>' . current( array_values( $countries ) ) . '</strong>';
                $field .= '<input type="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . current( array_keys( $countries ) ) . '" ' . implode( ' ', $custom_attributes ) . ' class="country_to_state" readonly="readonly" />';
            } else {
                $field = '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="country_to_state country_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . '><option value="">' . esc_html__( 'Select a country&hellip;', 'woocommerce' ) . '</option>';
                foreach ( $countries as $ckey => $cvalue ) {
                    $field .= '<option value="' . esc_attr( $ckey ) . '" ' . selected( $value, $ckey, false ) . '>' . $cvalue . '</option>';
                }
                $field .= '</select>';
                $field .= '<noscript><button type="submit" name="woocommerce_checkout_update_totals" value="' . esc_attr__( 'Update country', 'woocommerce' ) . '">' . esc_html__( 'Update country', 'woocommerce' ) . '</button></noscript>';
            }
            break;
        case 'state':
            $for_country = isset( $args['country'] ) ? $args['country'] : WC()->checkout->get_value( 'billing_state' === $key ? 'billing_country' : 'shipping_country' );
            $states      = WC()->countries->get_states( $for_country );
            if ( is_array( $states ) && empty( $states ) ) {
                $field_container = '<div class="form-row woocommerce-form input-group %1$s" id="%2$s" style="display: none">%3$s</div>';
                $field .= '<input type="hidden" class="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="" ' . implode( ' ', $custom_attributes ) . ' placeholder=" " readonly="readonly" data-input-classes="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '"/>';
            } elseif ( ! is_null( $for_country ) && is_array( $states ) ) {
                $field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="state_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder=" "  data-input-classes="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '">
                    <option value="">' . esc_html__( 'Select an option&hellip;', 'woocommerce' ) . '</option>';
                foreach ( $states as $ckey => $cvalue ) {
                    $field .= '<option value="' . esc_attr( $ckey ) . '" ' . selected( $value, $ckey, false ) . '>' . $cvalue . '</option>';
                }
                $field .= '</select>';
            } else {
                $field .= '<input type="text" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" value="' . esc_attr( $value ) . '"  placeholder=" " name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" ' . implode( ' ', $custom_attributes ) . ' data-input-classes="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '"/>';
            }
            break;
        case 'textarea':
            $field .= '<textarea name="' . esc_attr( $key ) . '" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder=" " ' . ( empty( $args['custom_attributes']['rows'] ) ? ' rows="2"' : '' ) . ( empty( $args['custom_attributes']['cols'] ) ? ' cols="5"' : '' ) . implode( ' ', $custom_attributes ) . '>' . esc_textarea( $value ) . '</textarea>';
            break;
        case 'checkbox':
            $field = '<label class="checkbox ' . implode( ' ', $args['label_class'] ) . '" ' . implode( ' ', $custom_attributes ) . '>
                    <input type="' . esc_attr( $args['type'] ) . '" class="input-checkbox ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="1" ' . checked( $value, 1, false ) . ' /> ' . $args['label'] . $required . '</label>';
            break;
        case 'text':
        case 'password':
        case 'datetime':
        case 'datetime-local':
        case 'date':
        case 'month':
        case 'time':
        case 'week':
        case 'number':
        case 'email':
        case 'url':
        case 'tel':
            $field .= '<input  type="' . esc_attr( $args['type'] ) . '" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder=" "  value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />';
            break;
        case 'select':
            $field   = '';
            $options = '';
            if ( ! empty( $args['options'] ) ) {
                foreach ( $args['options'] as $option_key => $option_text ) {
                    if ( '' === $option_key ) {
                        if ( empty( $args['placeholder'] ) ) {
                            $args['placeholder'] = $option_text ? $option_text : __( 'Choose an option', 'woocommerce' );
                        }
                        $custom_attributes[] = 'data-allow_clear="true"';
                    }
                    $options .= '<option value="' . esc_attr( $option_key ) . '" ' . selected( $value, $option_key, false ) . '>' . esc_attr( $option_text ) . '</option>';
                }
                $field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ) . '">
                        ' . $options . '
                    </select>';
            }
            break;
        case 'radio':
            $label_id .= '_' . current( array_keys( $args['options'] ) );
            if ( ! empty( $args['options'] ) ) {
                foreach ( $args['options'] as $option_key => $option_text ) {
                    $field .= '<input type="radio" class="input-radio ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" value="' . esc_attr( $option_key ) . '" name="' . esc_attr( $key ) . '" ' . implode( ' ', $custom_attributes ) . ' id="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '"' . checked( $value, $option_key, false ) . ' />';
                    $field .= '<label for="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '" class="radio ' . implode( ' ', $args['label_class'] ) . '">' . $option_text . '</label>';
                }
            }
            break;
    }
    if ( ! empty( $field ) ) {
        $field_html = '';
        $field_html .= '' . $field;


        if($args['label']=='Country / Region'){
            $args['label'] = pll__('Country / Region');
        }

        if ( $args['label'] && 'checkbox' !== $args['type'] ) {
            $field_html .= '<label for="' . esc_attr( $label_id ) . '" class="">' . $args['label'] . $required . '</label>';
        }
        if ( $args['description'] ) {
            $field_html .= '<span class="description" id="' . esc_attr( $args['id'] ) . '-description" aria-hidden="true">' . wp_kses_post( $args['description'] ) . '</span>';
        }
        $field_html .= '';
        $container_class = esc_attr( implode( ' ', $args['class'] ) );
        $container_id    = esc_attr( $args['id'] ) . '_field';
        $field           = sprintf( $field_container, $container_class, $container_id, $field_html );
    }
    $field = apply_filters( 'woocommerce_form_field_' . $args['type'], $field, $key, $args, $value );
    $field = apply_filters( 'woocommerce_form_field', $field, $key, $args, $value );
    if ( $args['return'] ) {
        return $field;
    } else {
        echo $field; // WPCS: XSS ok.
    }
}
    

/**
 * Remove State from addresses
 *
 * @since  1.0.0
 * @param  Address_Fields
 * @return Address_Fields
 */
function ib_remove_state_field( $fields ) {
    unset( $fields['state'] );
    return $fields;
}
add_filter( 'woocommerce_default_address_fields', 'ib_remove_state_field' );


/**
 * Get Link of Page implementing Template .php
 *
 * @since  1.0.0
 * @param  String Page Template
 * @return String Link of template
 */
function getLinkByTemplate($template = 'page.php'){
    $args = ['post_type' => 'page','fields' => 'ids','nopaging' => true, 'meta_key' => '_wp_page_template', 
    'meta_value' => $template,'suppress_filters' => false];
    $postL = get_posts( $args );
    return get_page_link( $postL[0] );
}


/**
 * Get id of Page implementing Template .php
 *
 * @since  1.0.0
 * @param  String Page Template
 * @return String id of template
 */
function getIdByTemplate($template = 'page.php'){
    $args = ['post_type' => 'page','fields' => 'ids','nopaging' => true, 'meta_key' => '_wp_page_template', 
    'meta_value' => $template,'suppress_filters' => false];
    $postL = get_posts( $args );
    return $postL[0];
}


/**
 * Get Plan Defaults Duration
 *
 * @since  1.0.0
 * @return array Plans Duration key = days, value = String 
 */
function plansDuration(){
    $plans= array();
    $plans_repeater =  get_field('plans_duration_settings', 'options');
    if($plans_repeater){
        foreach($plans_repeater as $r)
        {
            $plans[$r['days']]=  __($r['label'],'idealbiz');
        }
    }else{
        return array(
            '30'  => __( '1 Month','idealbiz'),
            '90'  => __( '3 Months','idealbiz'),
            '182' => __( '6 Months','idealbiz'),
            '365' => __( '1 Year','idealbiz')
        );
    }

    return $plans;
}


/**
 * Transform Days into Months
 *
 * @since  1.0.0
 * @return array Days To Month key = days , value = months
 */
function daysToMonth(){
    $dm= array();
    $plans_repeater =  get_field('plans_duration_settings', 'options');
    if($plans_repeater){
        foreach($plans_repeater as $r)
        {
            $dm[$r['days']]= floor($r['days']/30);
        }
    }else{
        return array(
            '90'  => '3',
            '182' => '6',
            '365' => '12'
        );
    } 
    return $dm;
}


/**
 * Transform post status into String
 *
 * @since  1.0.0
 * @param string post status
 * @return array Days To Month key = days , value = months
 */
function getPostStatus($status = 'publish'){
    if($status == 'pending')
        return __( 'Pending','idealbiz');
    elseif($status == 'draft')
        return __( 'Draft','idealbiz');    
    else
        return __( 'Published','idealbiz');
}


/**
 * Add Plan duration to Litings Products
 *
 * @since  1.0.0
 */
function plan_duration() {
    $args = array(
    'label' => __( 'Plan Duration','idealbiz'),
    'placeholder' => __( 'Enter plan duration here in days (number)','idealbiz'),
    'id' => 'plan_duration',
    'desc_tip' => true,
    'description' => __( 'Duration of plan e.g: 30,60,90,365 days','idealbiz'),
    'options' => plansDuration()
    );
woocommerce_wp_select( $args );
}
add_action( 'woocommerce_product_options_pricing', 'plan_duration' );   // Generic & listing Product

/**
 * Save the custom field
 * @since 1.0.0
 */
function cfwc_save_custom_field( $post_id ) {
    $product = wc_get_product( $post_id );
    $pd = isset( $_POST['plan_duration'] ) ? $_POST['plan_duration'] : '30';
    $product->update_meta_data( 'plan_duration', $pd );
    $product->save();
   }
add_action( 'woocommerce_process_product_meta', 'cfwc_save_custom_field' );




/**
 * Product to listing post association 
 *
 * @since  1.0.0
 * @param  WP_Post Current Post
 * @return 
 */
function cfwc_create_custom_field() { 
	$args = array(
		'id'            => 'related_post_id',
		'label'         => __( 'Post ID', 'idealbiz' ),
		'class'			=> 'hidden',
		'desc_tip'      => true,
		'description'   => __( 'Post listing id of order', 'idealbiz' ),
	);
	woocommerce_wp_text_input( $args );
}add_action( 'woocommerce_product_options_general_product_data', 'cfwc_create_custom_field' );

 /* valite custom product in cart */
function so_validate_add_cart_item( $passed, $product_id, $quantity, $variation_id = '', $variations= '' ) {
    $product = wc_get_product( $product_id );
    $title = isset( $_POST['related_post_id'] ) ? $_POST['related_post_id'] : '';
	$product->update_meta_data( 'related_post_id', sanitize_text_field( $title ) );
    $product->save();
    return true;
}add_filter( 'woocommerce_add_to_cart_validation', 'so_validate_add_cart_item', 10, 5 );

/* *display custom product field in cart */
function cfwc_display_custom_field() {
	global $post;
    printf('<input type="hidden" id="related_post_id" name="related_post_id" value="%s">', $_GET['id']);
}add_action( 'add_listing_id', 'cfwc_display_custom_field' );

/* add custom field to cart item */
function cfwc_add_custom_field_item_data( $cart_item_data, $product_id, $variation_id, $quantity ) {
	if( ! empty( $_POST['related_post_id'] ) ) {
		$cart_item_data['related_post_id'] = $_POST['related_post_id'];
	}
	return $cart_item_data;
}add_filter( 'woocommerce_add_cart_item_data', 'cfwc_add_custom_field_item_data', 10, 4 );
/* admin layout custom product beautifier */
function adminfooterscript() {
    echo '<style>
            .variable_subscription_trial .form-row input[type=text], .variable_subscription_pricing .form-row input[type=text], .variable_subscription_trial .form-row select, .variable_subscription_pricing .form-row select {
                min-height: 40px;
            }
            th[data-name="button_link"]{
                min-width: 150px;
            }
          </style>
          <script> 
              jQuery("div[data-name=\'active_order\']").find(\'\input\').prop(\'disabled\', \'disabled\'); 
          </script>';
}add_action('admin_footer', 'adminfooterscript');


/**
 * Add custom field to order object 
 *
 * @since  1.0.0
 * @param  WP_Cart item
 * @param int cart_key
 * @param string value
 * @param WP_Order order
 */
function cfwc_add_custom_data_to_order( $item, $cart_item_key, $values, $order ) {
	foreach( $item as $cart_item_key=>$values ) {
		if( isset( $values['related_post_id'] ) ) {
            $item->add_meta_data( __( 'Listing ID', 'idealbiz' ), $values['related_post_id'], true );
		}
	}
}add_action( 'woocommerce_checkout_create_order_line_item', 'cfwc_add_custom_data_to_order', 10, 4 );



add_filter( 'woocommerce_payment_complete_order_status', 'rfvc_update_order_status', 10, 2 );
function rfvc_update_order_status( $order_status, $order_id ) {
 $order = new WC_Order( $order_id );
 if ( 'processing' == $order_status && ( 'on-hold' == $order->status || 'pending' == $order->status || 'failed' == $order->status ) ) {
    return 'completed';
 }
 return $order_status;
}



/**
 * Add order id to listing, define the woocommerce_order_status_completed callback 
 * ass highlight to product
 *
 * @since  1.0.0
 * @param  int order_id
 * @return 
 */
function act_woocommerce_new_order( $order_id ) { 
    $o = new WC_Order( $order_id );

    $up = 0;

    foreach( $o->get_items() as $item ){
        $product_id = $item->get_product_id();
        $ptype = get_post_meta($product_id, '_product_type_meta_key', true);
        if($ptype == 'upgrade_plan'){
            $up = 1;
        }
    }

    //remove all highlights or other packs
    foreach( $o->get_items() as $item ){
        $product_id = $item->get_product_id();
        $lid = get_post_meta( $product_id, 'related_post_id', true );
        wp_remove_object_terms( $lid, 'highlight', 'boost' );
    }

    foreach( $o->get_items() as $item ){
        $product_id = $item->get_product_id();

        $lid = get_post_meta( $product_id, 'related_post_id', true );
        // load meta data - set listing as highlight
        foreach ($item->get_meta_data() as $metaData) {
            $attribute = $metaData->get_data();
            $slug = $attribute['key'];
            global $wpdb;
            $select = "SELECT *
            FROM ".$wpdb->prefix."pofw_product_option
            WHERE title ='".$slug."'"; 
            //echo $select;
            $rows = $wpdb->get_results($select, ARRAY_A);
                if($rows[0]['slug']=='highlight'){
                    //echo  $lid;
                    wp_set_object_terms( $lid, 'highlight', 'boost' );
                }
                if($rows[0]['slug']=='certified'){
                    //wp_set_object_terms( $lid, 'certified', 'boost' );
                    update_field( 'listing_certification_status', 'certification_ongoing', $lid );
                }
        }
        if (get_post_type($lid) == 'listing' && $up == 0 ) {
            update_post_meta($lid, 'active_order', $order_id);
            pll_set_post_language($lid, pll_current_language());


            //marca o que foi publicado como broker
            $values = get_fields( $lid );
            //var_dump($values);
            if(isBroker()){
                //var_dump($lid);
                $x= countListsOfBroker(NULL,strtolower($values["publish_in"]->slug));
                //var_dump($x);
                if($x < 10){
                    update_post_meta($lid, 'broker_list', '1');
                }
            }
            
        }

        if (get_post_type($lid) == 'wanted') {
            update_post_meta($lid, 'active_order', $order_id);
            pll_set_post_language($lid, pll_current_language());
        }

        if (get_post_type($lid) == 'listing' || get_post_type($lid) == 'wanted') {
            $product_days = get_post_meta( $product_id, 'plan_duration', true );
            $expire_date = get_field('expire_date', $lid);
            if(!$expire_date){
                $expire_date=date('Ymd');
            }
            $new_expire_date = date('Ymd', strtotime($expire_date. ' +'. $product_days . ' days'));
            if( $up == 0 ){
                update_field('expire_date', $new_expire_date, $lid); // senao for upgrade
            }
            sendExpertHelpEmail($lid,$order_id);
            update_post_meta($order_id, 'listing_id', $lid);
        }
    }


    if (!session_id()) {
        session_start();
    }
    if(isset($_SESSION['srid'])){
        if($_SESSION['srid']!=''){
            update_post_meta($order_id, 'srid', $_SESSION['srid']);
            update_post_meta($_SESSION['srid'], 'orderid', $order_id);
            $_SESSION['srid']='';
        }
    }

    if(isset($_SESSION['lsid'])){
        if($_SESSION['lsid']!=''){
            update_post_meta($order_id, 'lsid', $_SESSION['lsid']);
            update_post_meta($_SESSION['lsid'], 'orderid', $order_id);
            $_SESSION['lsid']='';
            listingsystem_woocommerce_order_status_completed($order_id);
        }
    }

}add_action( 'woocommerce_thankyou', 'act_woocommerce_new_order', 10, 1 ); 


if(isset($_GET['sendtestemail'])){
    $body    = it('You are being selected to help in this business:','irs').' '; 
    $body_template = EmailTemplate::get_email_body( wpautop( $body ) );
    $user_compliment  = it('Hello','irs');
    $user_compliment .= ' asd,<br/><br/>';
    $body_template = str_replace( '%%HEAD_MESSAGE%%', it('New business collaboration', 'irs' ), $body_template );
    $body_template = str_replace( '%%USER_COMPLIMENT%%', $user_compliment, $body_template );
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
    /* wp_mail( 'ricardo21ferreira@gmail.com', 'teste', $body_template, $headers ); */
    wp_mail( 'customercare.pt@idealbiz.io', 'teste', $body_template, $headers );



    $subject = pll__('Confirmation of application for Specialist'); 
    $hi = $subject;
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $message = pll__('Hi {{expert}}, We have received your Specialist application form. We appreciate your trust in our platform');
    $message = str_replace('{{expert}}',get_field('expert_email',$post_id),$message);
    $emailHtml  = get_email_header();
    $emailHtml .= get_email_intro('', $message, $hi);
    $emailHtml .= get_email_footer();
    /* wp_mail( 'ricardo21ferreira@gmail.com', 'teste2', $emailHtml, $headers ); */
    wp_mail( 'customercare.pt@idealbiz.io', 'teste2', $emailHtml, $headers );

}





function sendExpertHelpEmail($lid,$order_id){
    //to expert
    $subject = '[idealBiz] '. it('New business collaboration', 'irs' ) . ' ';
    $body    = it('You are being selected to help in this business: {{list}}','irs').' '; 
    $expert_id= get_field('expert', $lid)->ID;
    $to= get_field('expert_email', $expert_id);
    /*
    $user = get_user_by( 'email', $expert_email );
    $user_info = get_userdata($user->ID);
    $first_name = $user_info->first_name;
    $userId = $user->ID;
    */

    $body    = str_replace('{{list}}','<br/><a href="'.get_the_permalink($lid).'"><b>'.get_the_title($lid).'</b></a>',$body);

    $body_template = EmailTemplate::get_email_body( wpautop( $body ) );
    $user_compliment  = it('Hello','irs');
    $user_compliment .= ' ' . get_the_title($expert_id) . ',<br/><br/>';
    $body_template = str_replace( '%%HEAD_MESSAGE%%', it('New business collaboration', 'irs' ), $body_template );
    $body_template = str_replace( '%%USER_COMPLIMENT%%', $user_compliment, $body_template );
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
    // notify client
    $customer_care = ', '.get_field('costumer_care_email', 'option');
    /*v
    var_dump($to.$customer_care);
    var_dump($subject);
    var_dump($body_template);
    var_dump($headers);
    */
    wp_mail( $to.$customer_care, $subject, $body_template, $headers );

    //to client  Cleverson dia 26/07/21 Vou iniciar Alteração de email
    $order = new WC_Order($order_id);
    $order->get_billing_email();

    $order2 = wc_get_order( $order_id );
    $order_data = $order2->get_data();
    $order_billing_first_name = $order_data['billing']['first_name'];

    $subject = '[idealBiz] '. it('ad registration confirmation', 'irs' ) . ' ';
    $body    = it('You selected this expert: {{expert}} to help in your business: {{list}}','irs').'<br/>'.it('Regards, The iDealBiz team','idealbiz'); 
    // Abaixo Exibe lint e texto dos Meus Anúncios.
    $body    = str_replace('{{list}}','<br/><a href="'.wc_get_endpoint_url('mylistings', '', get_permalink(get_option('woocommerce_myaccount_page_id'))).'"><b>'.wc_get_endpoint_url('mylistings', '', get_permalink(get_option('woocommerce_myaccount_page_id'))).'</b></a><br/>',$body);
    // Abaixo Exibe Titulo e link do Anúncio
    //$body    = str_replace('{{list}}','<br/><a href="'.get_the_permalink($lid).'"><b>'.get_the_title($lid).'</b></a><br/>',$body);

    $body    = str_replace('{{expert}}',get_the_title($expert_id),$body);

    

    $body_template = EmailTemplate::get_email_body( wpautop( $body ) );
    $user_compliment  = it('Hello','irs');
    $user_compliment .= ' ' .$order_billing_first_name.',<br/><br/>';
    $body_template = str_replace( '%%HEAD_MESSAGE%%', it('ad registration confirmation', 'irs' ), $body_template );
    $body_template = str_replace( '%%USER_COMPLIMENT%%', $user_compliment, $body_template );
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
    $customer_care = ', '.get_field('costumer_care_email', 'option');
    wp_mail( $order->get_billing_email().$customer_care, $subject, $body_template, $headers );


}
//testing
/*
add_action('init', 'process_function');
function process_function(){
    echo pll__('New Contact in ');
}
*/





/*
$args = array(  
    'post_type' => 'wanted',
    'posts_per_page' => -1, 
);
$loop = new WP_Query( $args ); 
while ( $loop->have_posts() ) : $loop->the_post(); 
    echo get_the_ID().'-'.get_field('expire_date').'<br/>'; 

    $new_expire_date = date('Ymd', strtotime("+364 days"));
    update_field('expire_date', $new_expire_date, get_the_ID()); // senao for upgrade

endwhile;
wp_reset_postdata(); 

$args = array(  
    'post_type' => 'listing',
    'posts_per_page' => -1, 
);
$loop = new WP_Query( $args ); 
while ( $loop->have_posts() ) : $loop->the_post(); 
    echo get_the_ID().'-'.get_field('expire_date').'<br/>'; 

    $new_expire_date = date('Ymd', strtotime("+364 days"));
    update_field('expire_date', $new_expire_date, get_the_ID()); // senao for upgrade

endwhile;
wp_reset_postdata(); 
*/


/**
 * Display the extra data in the order admin panel
 *
 * @since  1.0.0
 * @param  WP_Order Current Order object
 * @return html
 */
function kia_display_order_data_in_admin( $order ){  ?>
    <div class="order_data_column">
       
        <?php 
            foreach( $order->get_items() as $item ){
                $product_id = $item->get_product_id();

                //echo '<pre>';
                if($item->get_meta_data()[0]){
                    $lid = $item->get_meta_data()[0]->get_data()['value'];
                    //echo '</pre>';
    
                    //$lid = get_post_meta( $product_id, 'related_post_id', true );
                    if (get_post_type($lid) == 'listing' ) {
                        echo '
                        <style>
                        #order_data > div.order_data_column_container > div:nth-child(1) > div{
                            width: 100% !important;
                            margin-top: 80px;
                        } 
                        </style>
                        <div class="width:100%; margin-top: 60px;">';
                        $post = get_post($lid);
                        echo '
                            <div style="width: 50%; float:left;">
                                <h4 style="margin-top:10px;">'.__( 'Listing Details','idealbiz' ).'</h4>
                                <p><strong>' . __( 'ID', 'idealbiz' ) . ': </strong>' . $lid . '</p>
                                <h3>'.$post->post_title.'</h3>
                                <span class="text-uppercase">'.esc_html(get_field('location',$lid)->name).'</span>
                                <br/><br/>
                                <span class="price">'.wc_price(get_field('price_manual',$lid)).'</span>
                            </div>
                            <div style="width: 50%; float:left;">
                                <img style="max-width:100%;"src="'.get_field('featured_image',$lid)['sizes']['medium'].'">
                            </div>
                        </div>';
                    }
                }
                
            }
        ?>
    </div>
<?php }add_action( 'woocommerce_admin_order_data_after_order_details', 'kia_display_order_data_in_admin' );


/**
 * Define the woocommerce_get_checkout_url callback 
 *
 * @since  1.0.0
 * @param  array url array
 * @return array url
 */
function filter_woocommerce_get_checkout_url( $array ) {
    return $array;
}; 
add_filter( 'woocommerce_get_checkout_url', 'filter_woocommerce_get_checkout_url', 10, 1 ); 


/**
 * 1. Add support for variable duration
 *
 * @since  1.0.0
 * @param  WP_Post Current Post
 * @return 
 */
add_action( 'woocommerce_variation_options_pricing', 'bbloomer_add_variation_duration_to_variations', 10, 3 );
function bbloomer_add_variation_duration_to_variations( $loop, $variation_data, $variation ) {
woocommerce_wp_select( array(
'id' => 'variation_duration[' . $loop . ']',
'class' => 'short',
'label' => __( 'Plan Duration', 'woocommerce' ),
'desc_tip' => true,
'description' => __( 'Duration of plan e.g: 30,60,90,365 days','idealbiz'),
'options' => plansDuration(),
'value' => get_post_meta( $variation->ID, 'variation_duration', true )
)
);
woocommerce_wp_text_input( array(
    'id'            => 'variation_listing_price_override[' . $loop . ']',
    'class'         => 'wc_input_subscription_price wc_input_price',
    'wrapper_class' => '_subscription_price_field',
    'label'         => sprintf( __( '<span style="font-weight:bold">BROKER ACCOUNT</span> Listing Price (%s): ', 'idealbiz' ), get_woocommerce_currency_symbol() ),
    'placeholder'   => __( 'e.g. 9.90', 'example price', 'idealbiz' ),
    'style'         => 'max-width: 100px;',
    'value'         => get_post_meta( $variation->ID, 'variation_listing_price_override', true ),
    'type'          => 'number',
    'description' => __( 'Override Price os listing if user has broker account','idealbiz'),
    'custom_attributes' => array(
            'step' => 'any',
            'min'  => '0',
        ),
    )
);
woocommerce_wp_text_input( array(
    'id'            => 'variation_listing_reduced_slots[' . $loop . ']',
    'class'         => 'variation_listing_reduced_slots wc_input_price',
    'wrapper_class' => '_subscription_price_field',
    'label'         => sprintf( __( '<span style="font-weight:bold">BROKER ACCOUNT</span> Listing Slots: ', 'idealbiz' ) ),
    'placeholder'   => __( 'e.g. 10', 'example price', 'idealbiz' ),
    'style'         => 'max-width: 100px;',
    'value'         => get_post_meta( $variation->ID, 'variation_listing_reduced_slots', true ),
    'type'          => 'number',
    'description' => __( 'Number of Listings at reduced price','idealbiz'),
    'custom_attributes' => array(
            'step' => 'any',
            'min'  => '0',
        ),
    )
);

woocommerce_wp_text_input( array(
    'id'            => 'variation_listing_price_overbroker[' . $loop . ']',
    'class'         => 'variation_listing_price_overbroker wc_input_price',
    'wrapper_class' => '_subscription_price_field',
    'label'         => sprintf( __( '<span style="font-weight:bold">BROKER ACCOUNT</span> Extra Listing Price (%s): ', 'idealbiz' ), get_woocommerce_currency_symbol() ),
    'placeholder'   => __( 'e.g. 9.90', 'example price', 'idealbiz' ),
    'style'         => 'max-width: 100px;',
    'value'         => get_post_meta( $variation->ID, 'variation_listing_price_overbroker', true ),
    'type'          => 'number',
    'description' => __( 'Override Price after Broker uses all free slots','idealbiz'),
    'custom_attributes' => array(
            'step' => 'any',
            'min'  => '0',
        ),
    )
);


}
/**
 * 2.Save Plan Duration on product variation save
 *
 * @since  1.0.0
 */
add_action( 'woocommerce_save_product_variation', 'bbloomer_save_variation_duration_variations', 10, 2 );
function bbloomer_save_variation_duration_variations( $variation_id, $i ) {
    $variation_duration = $_POST['variation_duration'][$i];
    if ( isset( $variation_duration ) ) 
        update_post_meta( $variation_id, 'variation_duration', esc_attr( $variation_duration ) );
    $variation_listing_price_override = $_POST['variation_listing_price_override'][$i];
    if ( isset( $variation_listing_price_override ) ) 
        update_post_meta( $variation_id, 'variation_listing_price_override', esc_attr( $variation_listing_price_override ) );
    $variation_listing_reduced_slots = $_POST['variation_listing_reduced_slots'][$i];
    if ( isset( $variation_listing_reduced_slots ) ) 
        update_post_meta( $variation_id, 'variation_listing_reduced_slots', esc_attr( $variation_listing_reduced_slots ) );

    $variation_listing_price_overbroker = $_POST['variation_listing_price_overbroker'][$i];
    if ( isset( $variation_listing_price_overbroker ) ) 
        update_post_meta( $variation_id, 'variation_listing_price_overbroker', esc_attr( $variation_listing_price_overbroker ) );
}
/**
 * 3.Store Plan Duration value into variation data
 *
 * @since  1.0.0
 * @param  WP_Product variations
 * @return WP_Product variations
 */
add_filter( 'woocommerce_available_variation', 'bbloomer_add_variation_duration_variation_data' );
function bbloomer_add_variation_duration_variation_data( $variations ) {
        $variations['variation_duration'] = '<div class="woocommerce_variation_duration">Plan Duration: <span>' . get_post_meta( $variations[ 'variation_id' ], 'variation_duration', true ) . '</span></div>';
    return $variations;
}


/**
 * Get user valid subscriptions
 *
 * @since  1.0.0
 * @param  int user_id
 * @return WP_Query active subscriptions
 */
function getSubscriptions($id = NULL){
    global $wpdb;
    if(!$id){ $id = get_current_user_id(); }



    $queryString = "SELECT *
                    FROM {$wpdb->prefix}posts as p
                    JOIN {$wpdb->prefix}postmeta as pm 
                        ON p.ID = pm.post_id
                    WHERE p.post_type = 'shop_subscription' 
                    AND p.post_status = 'wc-active'
                    AND pm.meta_key = '_customer_user' 
                    AND pm.meta_value > 0
                    AND pm.meta_value = '$id'";
                   // echo $queryString;

    $activeSubscriptions = $wpdb->get_results($queryString, OBJECT);

    return $activeSubscriptions;
}


/**
 * Extract orders from active subscriptions ; 
 *
 * @since  1.0.0
 * @param  int user_id
 * @return array of ids
 */
function extractOrdersFromSubscriptions($id = NULL){
    global $wpdb;
    if(!$id){ $id = get_current_user_id(); }
    $acs = getSubscriptions($id);
    $ordersWithSubscriptions = array();
    foreach ($acs as $post){
        $s = new WC_Subscription($post->ID);
        foreach($s->get_related_orders() as $o){
            $ordersWithSubscriptions[] = $o;
        }
    }
    //var_dump($ordersWithSubscriptions);
    //die();
    return $ordersWithSubscriptions;
}



/**
 * GET ALL orders from active subscriptions ; 
 *
 * @since  1.0.0
 * @return array of ids
 */
function getAllextractOrdersFromSubscriptions(){
    global $wpdb;
    $queryString = "SELECT *
        FROM {$wpdb->prefix}posts as p
        JOIN {$wpdb->prefix}postmeta as pm 
            ON p.ID = pm.post_id
        WHERE p.post_type = 'shop_subscription' 
        AND p.post_status = 'wc-active'
        AND pm.meta_key = '_customer_user' ";
    $acs = $wpdb->get_results($queryString, OBJECT);
    $ordersWithSubscriptions = array();
    foreach ($acs as $post){
        $s = new WC_Subscription($post->ID);
        foreach($s->get_related_orders() as $o){
            $ordersWithSubscriptions[] = $o;
        }
    }
    return $ordersWithSubscriptions;
}


/**
 * Get lang Slugs
 *
 * @since  1.0.0
 * @param  string Lang to query
 * @return string or array of active langs
 */
function getLangSlug($default = 'default'){ // return string or array
    $act_langs = array();
    $def_pub_lang='es_US';
    $dfp = get_option( 'default_published_language' );
    if($dfp){
    $def_pub_lang=$dfp;
    }
    if (isset($GLOBALS["polylang"])) { 
    $arrayLanguages = $GLOBALS["polylang"]->model->get_languages_list(); 
        foreach($arrayLanguages as $lang){
            if($default=='all'){
                $act_langs[]=$lang->slug;
            }else{
                if($default == 'default'){
                    if($lang->locale==$def_pub_lang){
                        return $lang->slug;
                    }
                }else{
                    if($default == $lang->slug){
                        return $lang->slug;
                    }
                }
            }
            
        }
    }
    return $act_langs;
}


/**
 * Add to cart redirection to checkout
 *
 * @since  1.0.0
 */

add_action( 'template_redirect', 'skip_cart_redirect' );
function skip_cart_redirect(){
    // Redirect to checkout (when cart is not empty)
    if ( ! WC()->cart->is_empty() && is_cart() ) {
        wp_safe_redirect( wc_get_checkout_url() ); 
        exit();
    }
    // Redirect to shop if cart is empty 
    elseif ( WC()->cart->is_empty() && is_cart() ) {
        wp_safe_redirect( wc_get_page_permalink( 'shop' ) );
        exit();
    }
}



/**
 * Remove add to cart messsage
 *
 * @since  1.0.0
 */
add_filter( 'wc_add_to_cart_message', function( $string, $product_id = 0 ) {
	$start = strpos( $string, '<a href=' ) ?: 0;
	$end = strpos( $string, '</a>', $start ) ?: 0;
	return substr( $string, $end ) ?: $string;
} );
add_filter( 'wc_add_to_cart_message_html', '__return_null' );



/**
 * My account Endpoints - favorites page  
 *
 * @since  1.0.0
 */
/*NPMM -SUSPENÇO DIA 05/12/2022 PELA REORGANIZAÇÃO DE MENUS FEITA POR DR. ALBERTO
/* function add_items_to_my_account_RecommendedBusines( $items ) {
    $res = array_slice($items, 0, 1, true) +
    array("?recommended=1" => __( '_str Business opportunity', 'idealbiz' )) +
    array_slice($items, 1, count($items) - 1, true) ;
    return $res;
}add_filter( 'woocommerce_account_menu_items', 'add_items_to_my_account_RecommendedBusines', 35, 1 ); */

function add_items_to_my_account_favorites( $items ) {
    $res = array_slice($items, 0, 1, true) +
    array("favorites" => __( 'My Favorites', 'idealbiz' )) +
    array_slice($items, 1, count($items) - 1, true) ;
    return $res;
}add_filter( 'woocommerce_account_menu_items', 'add_items_to_my_account_favorites', 35, 1 );


function is_it_a_shop_order($givenNumber)
{   
    if(get_post_type($givenNumber) == "shop_order")
    {
        return true;
    }
    else
    {
        return false;
    }
}


/** * Add favorites endpoint */
function favorites_add_my_account_endpoint() {
    add_rewrite_endpoint( 'favorites', EP_PAGES );

    if(isset($_GET['ptype'])){
        if($_GET['ptype'] == 'upgrade'){
            $order_of_product = get_field('active_order',$_GET['id']);
            $bought_upgrades= array();
            $all_purchased_days=90;
            if(is_it_a_shop_order($order_of_product)){
                $o = new WC_Order( $order_of_product );
                foreach( $o->get_items() as $item ){
                    $product_id = $item->get_product_id();
                    $all_purchased_days = get_post_meta( $product_id, 'plan_duration', true );
            
                    $bought_upgrades['product_id'] = $product_id;
                    $bought_upgrades['all_purchased_days'] = $all_purchased_days;
            
                    foreach ($item->get_meta_data() as $metaData) {
                        $attribute = $metaData->get_data();
                        $bought_upgrades[] = $attribute['key'];
                    }  
            
                }
            }
            if(!$bought_upgrades['product_id']){
                global $wpdb;
                    $posts = $wpdb->get_results("SELECT * FROM $wpdb->postmeta
                    WHERE meta_key = '_product_type_meta_key' AND  meta_value = 'upgrade_plan' LIMIT 1", ARRAY_A);  
        
                    $bought_upgrades['product_id']=$posts[0]["post_id"];
            }
        
            $date_expire='';
            $format_in = 'Ymd';
            $format_out = 'Y-m-d';
            if(get_field('expire_date',$_GET['id'])){
                $date_aux = DateTime::createFromFormat($format_in, get_field('expire_date',$_GET['id']));
                $date_expire= $date_aux->format( $format_out ) . ' 00:00:00';
            }else if($o->order_date){
                $date_expire = $o->order_date;
            }else{
                $date_expire = '2020-12-31 00:00:00';
            }
        
            if($all_purchased_days <= 0){
                $all_purchased_days=90;
                $bought_upgrades['all_purchased_days'] = $all_purchased_days;
            }
            $now = time();
            $your_date = strtotime($date_expire);
            $datediff = $your_date - $now;
            $billing_days =  round($datediff / (60 * 60 * 24));
            $bought_upgrades['billing_days'] = $billing_days;
        }
    }

    if(isset($_GET['certify'])){
        if($_GET['certify']==1){
            global $woocommerce;

            $woocommerce->cart->empty_cart();
        }
    }


}add_action( 'init', 'favorites_add_my_account_endpoint' );


/** * favorites content */
function favorites_endpoint_content() { 
    global $wpdb;

    $favs = array_map('intval', get_user_favorites(get_current_user_ID(), get_current_blog_id(), ''));
    if(count($favs)==1 && $favs[0]==1){

    }else{
        $querystr = "
            SELECT DISTINCT $wpdb->posts.id, $wpdb->posts.* 
            FROM $wpdb->posts
            INNER JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
            WHERE $wpdb->posts.ID IN (" . implode(',', $favs) . ")
            AND $wpdb->posts.post_status='publish'";
        $listings = $wpdb->get_results($querystr, OBJECT);
    }

    if ($listings):
    global $post;
    echo '
    <div class="listing-page">
    <div class="font-weight-bold m-r-50 text-left">
        <h2 class="base_color--color">'.__('My Favorites','idealbiz').'</h2>
        <span class="blue--color">'.count($listings).' '.(count($listings)>1 ? __('results','idealbiz') : __('result','idealbiz') ).'</span>
    </div>
    <div class="row listings listings-container m-t-20">';
    foreach ($listings as $post):
    setup_postdata($post);
        echo '<div class="col col-md-3 text-left">';
       // echo $post->post_type;
        get_template_part('/elements/listings'); 
        echo ' </div>
       ';
    endforeach;
    else :
        ?>
        <div class="m-t-20 m-b-20 m-auto">
            <h1><?php echo _e('No results.', 'idealbiz'); ?></h1>
        </div>
        <?php
    endif;
    echo '</div>
    <style>
        .status-badge {display: none !important;}
    </style>
    </div>';

 }add_action( 'woocommerce_account_favorites_endpoint', 'favorites_endpoint_content' );





/**
 * My account Endpoints - chat page  
 *
 * @since  1.0.0
 */
function add_items_to_my_account_chat( $items ) {
    $res = array_slice($items, 0, 1, true) +
    array("chat" => __( 'Chat Messages', 'idealbiz' )) +
    array_slice($items, 1, count($items) - 1, true) ;
    return $res;
}add_filter( 'woocommerce_account_menu_items', 'add_items_to_my_account_chat', 35, 1 );

/** * Add chat endpoint */
function chat_add_my_account_endpoint() {
    add_rewrite_endpoint( 'chat', EP_PAGES );
}add_action( 'init', 'chat_add_my_account_endpoint' );

/** * chat content */
function chat_endpoint_content() { 
    global $post;
    echo '
    <div class="row">
    <div class="w-100 text-center">
    <h1 class="base_color--color">'.__('Chat Messages','idealbiz').'</h1>
    <br/> 
    <br/>
    </div>
    <div class="col-md-12  dropshadow d-flex flex-column black--color white--background">
    <div class="chat container position-relative p-b-15  m-t-30 text-left">
    <div class="generic-form">
    <div class="acf-form"> '.do_shortcode('[front-end-pm fepaction="messagebox" fep-filter="show-all"]').'
    </div>
    </div>
    </div>
    </div>
    </div>';

 }add_action( 'woocommerce_account_chat_endpoint', 'chat_endpoint_content' );



 // get bought upgrades
 function getBoughtUpgrades($postid){

    global $wpdb;

    $inLang = false;
    if ( function_exists('pll_the_languages') ) {
        $inLang = pll_get_post_language($postid, 'slug');
    }

    $order_of_product = get_field('active_order',$postid);
    //echo $order_of_product.'-';
    $bought_upgrades= array();
    if(is_it_a_shop_order($order_of_product)){
        $o = new WC_Order($order_of_product);
        foreach( $o->get_items() as $item ){
            $product_id = $item->get_product_id();
            $bought_upgrades['product_id'] = $product_id;
            foreach ($item->get_meta_data() as $metaData) {
                $attribute = $metaData->get_data();

                $product_opt = $wpdb->get_row( 'SELECT * 
                    FROM ib_'.get_current_blog_id().'_pofw_product_option_value 
                    WHERE product_id = '.$product_id.'
                    AND title="'.$attribute['key'].'"');
                    
                $bought_upgrades[] = $attribute['key'];
                //echo ''.$attribute['key'].'<br/><br/>';
            }  
        }
    }
    $statuses = array( 'wc-completed', 'wc-processing');
    $orders_ids = get_orders_ids_with_upgrades( $statuses, $inLang );
    foreach($orders_ids as $uo){
         // echo $uo . '<br/>';
        $no = new WC_Order($uo);
        foreach( $no->get_items() as $item ){
            $pid = $item->get_product_id();
            $lid = $item->get_meta_data()[0]->get_data()['value'];
          // var_dump($lid);

            if($postid == $lid){
            //    echo $postid;
                foreach ($item->get_meta_data() as $metaData) {
                    $attribute = $metaData->get_data();
                    $product_addon= $wpdb->get_results('
                    SELECT * 
                    FROM ib_'.get_current_blog_id().'_pofw_product_option
                    WHERE  title="'.$attribute['key'].'" AND product_id='.$pid);

                    $bought_upgrades[] = $product_addon[0]->title;
                }  
            }
        }
    }
    return $bought_upgrades;
 }






// get available upgrades not bought for listing
function getAvailableUpgrades($postid){

    global $wpdb;

    $inLang = false;
    if ( function_exists('pll_the_languages') ) {
        $inLang = pll_get_post_language($postid, 'slug');
    }

       $order_of_product = get_field('active_order',$postid);
    //echo $order_of_product.'-';
    $bought_upgrades= array();
    $available_upgrades= array();
    if(is_it_a_shop_order($order_of_product)){
        $o = new WC_Order($order_of_product);
        foreach( $o->get_items() as $item ){
            $product_id = $item->get_product_id();
            $bought_upgrades['product_id'] = $product_id;
            foreach ($item->get_meta_data() as $metaData) {
                $attribute = $metaData->get_data();

                $product_opt = $wpdb->get_row( 'SELECT * 
                    FROM ib_'.get_current_blog_id().'_pofw_product_option_value 
                    WHERE product_id = '.$product_id.'
                    AND title="'.$attribute['key'].'"');
                    
                $bought_upgrades[] = $attribute['key'];
                //echo ''.$attribute['key'].'<br/><br/>';
            }  
        }
    }

    //get all prodcut options
    $product_available_addons= $wpdb->get_results('
    SELECT * 
    FROM ib_'.get_current_blog_id().'_pofw_product_option 
    WHERE product_id='.$product_id);
    foreach ($product_available_addons as $pav) {
        $available_upgrades[$pav->option_id] = $pav->title;
    }
    $statuses = array( 'wc-completed', 'wc-processing');
    $orders_ids = get_orders_ids_with_upgrades( $statuses, $inLang );
    //var_dump($orders_ids);
    //die();
    foreach($orders_ids as $uo){
         // echo $uo . '<br/>';
        $no = new WC_Order($uo);
        foreach( $no->get_items() as $item ){
            $pid = $item->get_product_id();
            $lid = $item->get_meta_data()[0]->get_data()['value'];
          // var_dump($lid);

            if($postid == $lid){
            //    echo $postid;
                foreach ($item->get_meta_data() as $metaData) {
                    $attribute = $metaData->get_data();
                    $product_addon= $wpdb->get_results('
                    SELECT * 
                    FROM ib_'.get_current_blog_id().'_pofw_product_option
                    WHERE  title="'.$attribute['key'].'" AND product_id='.$pid);

                    $bought_upgrades[] = $product_addon[0]->title;
                }  
            }
        }
    }
    
    foreach($bought_upgrades as $bu){
       // echo $bu.'<br/>';
        if (($key = array_search($bu, $available_upgrades)) !== false) {
            unset($available_upgrades[$key]);
        }
    }

    return $available_upgrades;
}




/**
 * My account Endpoints - listings page  
 *
 * @since  1.0.0
 */
function add_items_to_my_account_menu_items( $items ) {
    $res = array_slice($items, 0, 1, true) +
    array("mylistings" => __( 'My Listings', 'idealbiz' )) +
    array_slice($items, 1, count($items) - 1, true) ;
    return $res;
}add_filter( 'woocommerce_account_menu_items', 'add_items_to_my_account_menu_items', 55, 1 );


/** * Add mylistings endpoint */
function mylistings_add_my_account_endpoint() {
    add_rewrite_endpoint( 'mylistings', EP_PAGES );
}add_action( 'init', 'mylistings_add_my_account_endpoint' );

/** * mylistings content */
function iconic_information_endpoint_content() { 

    global $wpdb;

    $w=" AND ($wpdb->posts.post_type = 'listing' OR $wpdb->posts.post_type = 'wanted') ";
    if(isset($_GET['ltype'])){
        $w=" AND $wpdb->posts.post_type = 'listing' ";
    }
    if(isset($_GET['wtype'])){
        $w=" AND $wpdb->posts.post_type = 'wanted' ";
    }
    if(isset($_GET['btype'])){
        $w=" AND ($wpdb->posts.post_type = 'wanted' OR $wpdb->posts.post_type = 'listing') 
        AND ( m2.meta_key LIKE 'broker_list' AND m2.meta_value LIKE '1')";
    }


    $querystr = "
        SELECT DISTINCT $wpdb->posts.id, $wpdb->posts.* 
        FROM $wpdb->posts
        INNER JOIN $wpdb->postmeta m1 ON $wpdb->posts.ID = m1.post_id 
        INNER JOIN $wpdb->postmeta m2 ON $wpdb->posts.ID = m2.post_id 
        WHERE (( m1.meta_key LIKE 'owner' AND m1.meta_value LIKE ".get_current_user_ID().") ".$w."
        AND ($wpdb->posts.post_status='draft' OR $wpdb->posts.post_status='publish'))
    ";

    $listings = $wpdb->get_results($querystr, OBJECT);

    echo ' 
    <div class="listing-page">
    <div class="font-weight-bold m-r-0 text-left">
        <h2 class="base_color--color pull-left">'.__('My Listings','idealbiz').'</h2>
        <span class="blue--color  pull-right m-t-10">'.count($listings).' '.(count($listings)>1 ? __('results','idealbiz') : __('result','idealbiz') ).'</span>
    </div>';

    echo '<div style="clear:both;"></div>
        
        <ul class="nav nav-pills mb-3 m-t-15" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a href="'.wc_get_endpoint_url('mylistings', '', get_permalink(get_option('woocommerce_myaccount_page_id'))).'" class="nav-link '.(!isset($_GET['ltype']) && !isset($_GET['wtype']) && !isset($_GET['btype']) ? 'active' : '').'">
                '.__('All','idealbiz').'
            </a>
        </li>
        <!-- <li class="nav-item ">
            <a href="'.wc_get_endpoint_url('mylistings', '', get_permalink(get_option('woocommerce_myaccount_page_id'))).'?ltype=1" class="nav-link '.(isset($_GET['ltype']) ? 'active' : '').'">
                '.__('Business Listings','idealbiz').'
            </a>
        </li> -->

        <!-- <li class="nav-item">
            <a href="'.wc_get_endpoint_url('mylistings', '', get_permalink(get_option('woocommerce_myaccount_page_id'))).'?wtype=1" class="nav-link '.(isset($_GET['wtype']) ? 'active' : '').'">
                '.__('Wanted Business','idealbiz').'
            </a> 
        </li> -->';
        
        if(isBroker()){
            echo '<li class="nav-item">
                    <a href="'.wc_get_endpoint_url('mylistings', '', get_permalink(get_option('woocommerce_myaccount_page_id'))).'?btype=1" class="nav-link '.(isset($_GET['btype']) ? 'active' : '').'">
                        '.__('Broker Account Lists','idealbiz').'
                    </a>
                  </li>';
        }
        
    echo '</ul>';

    if ($listings):
    global $post;

    echo '<div class="row listings listings-container m-t-20">';
    foreach ($listings as $post):
    setup_postdata($post);
        echo '<div class=" col-lg-3 col-md-4 col-sm-6 col-xs-12 text-left m-b-20 edit-list">';
        get_template_part('/elements/listings'); 
        $opts_id_lang = pll_get_post( wc_get_page_id( 'shop' ), get_field('publish_in',$post->ID)->slug );
        if(!$opts_id_lang){
            $opts_id_lang='en';
        }
        // edita a publicação e retorna ao my-listings
        if($post->post_type=='listing'){
            echo '<div class="text-center m-t-0 m-b-5 d-flex flex-direction-row edit-opts">
            <a class="edit btn-blue" href="'.getLinkByTemplate('submit-listing.php').'?listing_id='.$post->ID.'&edit=1">
                '.__('Edit','idealbiz').'
            </a>';
        }
        if($post->post_type=='wanted'){
            echo '<div class="text-center m-t-0 m-b-5 d-flex flex-direction-row edit-opts">
            <a class="edit btn-blue" href="'.getLinkByTemplate('submit-wanted.php').'?listing_id='.$post->ID.'&edit=1">
                '.__('Edit','idealbiz').'
            </a>';
        }
        if(get_post_status()=='draft'){ // tem a publicação po publicar
            if(get_field('active_order',$post->ID)!=''){ // comprou a publicação mas aguarda validação no BO
                echo '<a class="awating-publish btn-blue" href="#" onclick="return false;" style="cursor:default;">
                '.__('Waiting Validation','idealbiz').'
                </a>';
            }else{ // Criou publicação mas ainda nao comprou o plano
                echo '<a class="publish btn-blue" href="'.get_site_url().'/'.get_field('publish_in',$post->ID)->slug.'/?p='.$opts_id_lang.'&ptype='.get_post_type($post->ID).'&id='.$post->ID.'">
                '.__('Publish','idealbiz').'
                </a>';
            }
        }else{ // já tem publicação comprada e aprovada
/*             if(get_post_type($post->ID)=='listing'){ //publicação é uma listing consegue fazer upgrade às opções
                $au = getAvailableUpgrades($post->ID);
               // var_dump($au);
                if(count($au)>0){
                    echo '<a class="upgrade btn-blue" href="'.get_site_url().'/'.get_field('publish_in',$post->ID)->slug.'/?p='.$opts_id_lang.'&ptype=upgrade&id='.$post->ID.'">
                    '.__('Upgrade','idealbiz').'
                    </a>';
                }
            } */

            // renova a publicação comprando novo plano
/*             echo '<a class="renew btn-blue" href="'.get_site_url().'/'.get_field('publish_in',$post->ID)->slug.'/?p='.$opts_id_lang.'&ptype='.get_post_type($post->ID).'&id='.$post->ID.'&renew=1">
                '.__('Renew','idealbiz').'
                </a>'; */
        }
/*         if(get_field('listing_certification_status',$post->ID) == 'certification_not') { // compra certificação se for uma listing
            echo '<a class="certif btn-blue" href="'.get_site_url().'/'.get_field('publish_in',$post->ID)->slug.'/?p='.$opts_id_lang.'&ptype=upgrade&id='.$post->ID.'&certify=1">
                '.__('Certify','idealbiz').'
                </a>';
        } */
        echo '</div>';


        $expire_date = get_field('expire_date', $post->ID);
        if($expire_date){
            echo '<div class="expire-date small d-block">
                <span class="pull-left">'.__('Expires:','idealbiz').' '.date('d-m-Y', strtotime($expire_date)).'</span>';
                if(get_field('listing_certification_status',$post->ID) == 'certification_ongoing') {
                    echo '<span class="pull-right">'.__('Ongoing Certification','idealbiz').'</span>';
                }
            echo '</div>';
        }
        echo '</div>';
       
    endforeach;
    else :
        ?>
        <div class="m-t-20 m-b-20 m-auto">
            <h1><?php echo _e('No results.', 'idealbiz'); ?></h1>
        </div>
        <?php
    endif;
    echo '</div>
    </div>';

 }add_action( 'woocommerce_account_mylistings_endpoint', 'iconic_information_endpoint_content' );
//echo $idealbiz_logo   = $img = get_option( 'woocommerce_email_header_image' );
 
/* delete post */
if(isset($_GET['dpost'])){
    if( current_user_can('editor') || current_user_can('administrator') || get_current_user_id()==get_post_field( 'post_author', $_GET['dpost'] ) || get_current_user_id() == get_field( 'owner', $_GET['dpost'] )['ID'] ){
        if ( get_post_type( $_GET['dpost'] ) == 'listing' || get_post_type( $_GET['dpost'] ) == 'wanted' ) {
            global $wpdb;
            $wpdb->delete( $wpdb->posts, array( 'ID' => $_GET['dpost'] ) );
        }
    }
}


 require_once(ABSPATH . 'wp-content/plugins/idealbiz-service-request/lib/WooCommerce/EndpointServiceRequest.php');
 /**
 * My account Endpoints - service_request
 *
 * @since  1.0.0
 */
/*NPMM -SUSPENÇO DIA 05/12/2022 PELA REORGANIZAÇÃO DE MENUS FEITA POR DR. ALBERTO
/* function add_service_requests_menu_items( $items ) {
    $res = array_slice($items, 0, 1, true) +
    array("service_request/?home=1" => __( 'Service Requests', 'idealbiz' )) +
    array_slice($items, 1, count($items) - 1, true) ;
    return $res;
}add_filter( 'woocommerce_account_menu_items', 'add_service_requests_menu_items', 10, 1 ); */

/** * Add service_request endpoint */
function service_requests_add_my_account_endpoint() {
    add_rewrite_endpoint( 'service_request', EP_PAGES );
}add_action( 'init', 'service_requests_add_my_account_endpoint' );


function isExpert($id = NULL, $inLang = NULL)
{
    global $wpdb;
    if (!$id) {
        $id = get_current_user_id();
    }
    if (!$inLang) { 
        $inLang = pll_current_language();
    }
    $user_info = get_userdata($id);
    $queryString = "SELECT *
                    FROM {$wpdb->prefix}posts as p
                    JOIN {$wpdb->prefix}postmeta as pm 
                        ON p.ID = pm.post_id
                    WHERE p.post_type = 'expert' 
                    AND p.post_status = 'publish'
                    AND pm.meta_key = 'expert_email' 
                    AND pm.meta_value = '".$user_info->user_email."'";
                //   echo $queryString;
    $expert = $wpdb->get_results($queryString, OBJECT);
    //var_dump($expert); 
    return $expert;
}


/** * service_request content */
function service_request_endpoint_content() { 
    
    global $epsr;

    $expert = isExpert(); 
    /* if($expert){ */

        echo '<div style="text-align:left;">'.cl_voltar(-1).'</div>';
        echo ' 
        
        <div class="listing-page">
        
        <div class="font-weight-bold m-r-0 text-left">
        <span class="container text-center m-b-30"><h1>'.__('Service Requests','idealbiz').'</h1></span>
            
            <!--<span class="blue--color  pull-right m-t-10">'.count($listings).' '.(count($listings)>1 ? __('results','idealbiz') : __('result','idealbiz') ).'</span>
        --></div>
        <style>
            .bref{
                border: 2px solid #14307b !important;
                line-height: 13px !important;   
                font-weight: bold !important; 
            }.bref:hover{
                border: 2px solid #408efc !important;
                line-height: 13px !important;   
            }
        </style>
        ';
        echo '<div style="clear:both;"></div>

        <style>
        @media only screen and (max-width: 768px) {
            .rb_botoes_rs{
                margin-bottom:5px;
                flex-direction: column;    
            }
        }
        
            a:hover{
                text-decoration: none !important;
                text-align: left !important;
                
            }
        
            .rb_botoes_rs a{
                width: 100%;
                padding: 3px;
                font-weight: normal !important;
                text-align: left !important;
                
            }
        
            .rb_botoes_rs{
                margin-bottom:5px;
                display:flex;
                justify-content:space-between ;
                
            }
        
            .bota_quadrado_sr{
                
                height: 70px;
                padding: 10px;
                background-color: #ffffff;
                margin-bottom:7px;
            }
        
            .bota_quadrado_sr .dashicons{
                font-size: 50px;
                margin:0px 6px;
            }
        
            .bota_quadrado_sr p{
                margin-top:-18px !important;
                margin-left:70px;
                font-size:1.15em;
            }

        </style>

            <div class="rb_botoes_rs">';

            if(OPPORTUNITY_SYSTEM == '1'){

               echo ' <a href="'.wc_get_endpoint_url('service_request', '', get_permalink(get_option('woocommerce_myaccount_page_id'))).'?home=1" class="nav-link '.(!isset($_GET['referrals']) && !isset($_GET['refer']) ? 'active' : '').'"><div class="bota_quadrado_sr stroke dropshadow "><span class="dashicons dashicons-feedback"></span><p>
                    '.__('My Service Requests','idealbiz').'
                    </p></div></a>';
            }        
 
//BOTÃO ABAIXO DESATIVADO CONFORME CONVERSA COM DR ALBERTO DIA 20/10/22 - CONFORME BRAFING DO EXCEL
// BOTÕES APARECEM EM : wp-content/plugins/idealbiz-service-request/lib/WooCommerce/EndpointServiceRequest.php

                    /* echo '    <a style="font-weight: bold;" href="'.wc_get_endpoint_url('service_request', '', get_permalink(get_option('woocommerce_myaccount_page_id'))).'?referrals=2" class="nav-link '.(isset($_GET['referrals']) ? 'active' : '').'"><div class="bota_quadrado_sr stroke dropshadow "><span class="dashicons dashicons-upload"></span><p>
                        '.__('_str Forwarding Sent','idealbiz').'
                        </p></div></a>';                    


                echo '    <a style="font-weight: bold;" href="'.wc_get_endpoint_url('service_request', '', get_permalink(get_option('woocommerce_myaccount_page_id'))).'?referrals=1" class="nav-link '.(isset($_GET['referrals']) ? 'active' : '').'"><div class="bota_quadrado_sr stroke dropshadow "><span class="dashicons dashicons-download"></span><p>
                    '.__('_str Forwarding Received','idealbiz').'
                    </p></div></a>'; 
                    
                    echo '</div>'; */


        //Exibe ou oculta botão para mebro fazer nova referenciação na área Minha Conta.
        $user_id = get_current_user_id(); 
        $user_info = get_userdata($user_id);
        $mailadresje = $user_info->user_email;
    
       
        $args = array(
            'numberposts'	=> 1,
            'post_type'		=> 'expert',
            'meta_query'	=> array(
                'relation'		=> 'AND',
                array(
                    'key'	 	=> 'expert_email',
                    'value'	  	=> $mailadresje,
                ),
    
            ),
        );
    
        $query = new WP_Query($args);
    
        $cl_user = $query->posts[0]->ID;
    
    
        $cl_member_cat = get_field('member_category_store',$cl_user);


        if ($cl_member_cat !=  false){

            //BOTÃO ABAIXO DESATIVADO CONFORME CONVERSA COM DR ALBERTO DIA 20/10/22 - CONFORME BREFING DO EXCEL
            /* echo '<div class="rb_botoes_rs">'; */


            echo '<a style="font-weight: bold;" href="'.getLinkByTemplate('single-counseling.php').'?refer=1" class="nav-link"><div class="bota_quadrado_sr stroke dropshadow "><span class="dashicons dashicons-id-alt"></span><p>
            '.__('Add new Referral','idealbiz').'
            </p></div></a> ';  
            

                echo'<a style="font-weight: bold;" href="'.wc_get_endpoint_url('service_request', '', get_permalink(get_option('woocommerce_myaccount_page_id'))).'?recommended_service=sent" class="nav-link '.(isset($_GET['referrals']) ? 'active' : '').'"><div class="bota_quadrado_sr stroke dropshadow "><span class="dashicons dashicons-upload"></span><p>
                '.__('_str Recommended Sent','idealbiz').'
                </p></div></a>';
    

                echo'<a style="font-weight: bold;" href="'.wc_get_endpoint_url('service_request', '', get_permalink(get_option('woocommerce_myaccount_page_id'))).'?recommended_service=received" class="nav-link '.(isset($_GET['referrals']) ? 'active' : '').'"><div class="bota_quadrado_sr stroke dropshadow "><span class="dashicons dashicons-download"></span></span><p>
                    '.__('_str Recommended Received','idealbiz').'
                    </p></div></a>';
  

    }   

        echo '</div>'; 
    echo $epsr->render();

    /* }else{
       echo '<span style="color:#8f8686" class="dashicons dashicons-info-outline"></span>'.'   '; _e('_str Msg No Member','idealbiz');
    } */

    

 }add_action( 'woocommerce_account_service_request_endpoint', 'service_request_endpoint_content' );


function isHighlight($post_id){

    $active_order = get_field('active_order',$post_id);


    return $active_order;

    $args = array(
        'meta_query' => array(
            array(
                'key' => 'related_post_id',
                'value' => $post_id
            )
        ),
        'post_type' => 'shop_order',
        'posts_per_page' => -1
    );


    $posts = get_posts($args);
    return $posts;
}



function userHasActiveExpertFeeSubscription($user_id = NULL){
    if(!$user_id){
        $user_id= get_current_user_id();
    }
    $subscriptions = wcs_get_users_active_subscriptions($user_id);
    //var_dump($subscriptions);
    foreach ($subscriptions as $subscription_id => $subscription) :
        $args = array('post__in' => $subscription->get_related_orders());
        $orders = wc_get_orders($args);
        foreach ($orders as $order) {
            $items = $order->get_items();
            foreach ($items as $item) {
                $product_id = $item->get_product_id();
                $pType = get_post_meta($product_id, '_product_type_meta_key', true);
                if ($pType == 'expert_fee') { 
                    return true;
                }
            }
        }
    endforeach;
    return false;
}

function getExpertsWithActiveFees($post_in = NULL){
    $expertsIds= array();

    $includeIds= array();
    if(!$post_in){
        $includeIds = $post_in; //array of experts ids
    }

    $args = array(
        'post_type' => array ( 'expert' ),
        'post_status' => array('publish', 'pending', 'draft', 'trash') ,
        'post__in' => $includeIds,
        'posts_per_page' => -1 
    );
    $userIds = array();
    $eposts = new WP_Query( $args );
    $status = '';
    if( $eposts->have_posts() ) {
        while( $eposts->have_posts()) { 
            $p = $eposts->the_post();
            $user = get_user_by( 'email', get_field('expert_email',get_the_ID()) );
            $userIds[]= array('expertId' => get_the_ID() , 'userId' => $user->ID);
        }
    }


    if(isset($_GET['debug'])){
    
        var_dump($userIds);
    }

    

    foreach($userIds as $u){
        //echo $u['userId'].'<br/>';
        if(userHasActiveExpertFeeSubscription($u['userId'])){
            $expertsIds[]=$u['expertId'];
        }
    }
    return $expertsIds;
}

//NPMM - getLeadSRValue Faz calculo do Service Request.
function getLeadSRValue($sr){
    $cl_ppc_fixo = get_field('sr_fixed_ppc_value',$sr);

    if($cl_ppc_fixo == NULL) {
        $budget_max = floatval(get_field('budget_max', $sr));
        return $budget_max;
    } else {
        return $cl_ppc_fixo;
    }
}


function getProductByType($type, $arr= NULL){
    $arrIds= array();
    global $wpdb;
    $queryString = "SELECT *
                    FROM {$wpdb->prefix}posts as p
                    JOIN {$wpdb->prefix}postmeta as pm 
                        ON p.ID = pm.post_id
                    WHERE p.post_type = 'product' 
                    AND p.post_status = 'publish'
                    AND pm.meta_key = '_product_type_meta_key' 
                    AND pm.meta_value = '".$type."'";
                //   echo $queryString;
    $prods = $wpdb->get_results($queryString, OBJECT);
    foreach ($prods as $p){
        $arrIds[]=$p->ID;
    }
    if(!$arr){
        return $arrIds[0];
    }else{
        return $arrIds;
    }
}


if(WEBSITE_SYSTEM == '1'){

    if(isset($_GET['sr-lead'])){
        if(isset($_GET['add-to-cart'])){
            //echo $_GET['add-to-cart'];
            //echo getProductByType('lead');
            if($_GET['add-to-cart'] == getProductByType('lead')){
                if (!session_id()) {
                    session_start();
                }
                $_SESSION['srid']=$_GET['sr-lead'];
            }
        }
        //cl_alerta('wc2024_ID do PPC'.$_SESSION['srid']); //Teste de sessão
    }
    add_filter('woocommerce_product_get_price', 'custom_price', 99, 2 );
    function custom_price( $price, $product ) {
        if (!session_id()) {
            session_start();
        }
        $id = $product->get_id();
        if($id == getProductByType('lead')){
            if(isset($_SESSION['srid'])){
                if($_SESSION['srid']!=''){
                    return (float) getLeadSRValue($_SESSION['srid']);
                }
            }
        }
        return $price;
    }
    function system1_woocommerce_order_status_completed( $order_id ) {


        $order = new WC_Order( $order_id );
        $sc = 0;
        $id=0;
        foreach( $order->get_items() as $item ){
            $product_id = $item->get_product_id();
            $ptype = get_post_meta($product_id, '_product_type_meta_key', true);
            if($ptype == 'expert_fee'){
                $sc = 1;
                $id = $product_id;
            }
        }


        $has_subscription = get_post_meta($order_id, 'has_subscription', true);

        if(!$has_subscription){ 
        if($sc==1){

            add_post_meta($order_id, 'has_subscription', 1); 

            $product = wc_get_product( $id );
            // add subscription to user
            $sub = wcs_create_subscription(array(
                'order_id' => $order_id,
                'status' => 'pending', // Status should be initially set to pending to match how normal checkout process goes
                'billing_period' => WC_Subscriptions_Product::get_period( $product ),
                'billing_interval' => WC_Subscriptions_Product::get_interval( $product )
            ));

            if( is_wp_error( $sub ) ){
                return false;
            }

            // Modeled after WC_Subscriptions_Cart::calculate_subscription_totals()
            $start_date = gmdate( 'Y-m-d H:i:s' );
            // Add product to subscription
            $sub->add_product( $product, 1 );

            $dates = array(
                'trial_end'    => WC_Subscriptions_Product::get_trial_expiration_date( $product, $start_date ),
                'next_payment' => WC_Subscriptions_Product::get_first_renewal_payment_date( $product, $start_date ),
                'end'          => WC_Subscriptions_Product::get_expiration_date( $product, $start_date ),
            );

            $sub->update_dates( $dates );
            $sub->calculate_totals();

            //$sub->update_status( 'pending', $note, true );
            //var_dump($dates);
            //die();

            // Update order status with custom note
            $note = ! empty( $note ) ? $note : __( 'Programmatically added order and subscription.' );
            $order->update_status( 'completed', $note, true );
            // Also update subscription status to active from pending (and add note)
            $sub->update_status( 'active', $note, true );
        }
        }

        

    $srid = get_post_meta( $order_id, 'srid', true );

    if($srid!=''){
        
        $subject =__('_str Lead purchase confirmation for','idealbiz').' '.get_the_title($srid);
        $hi = $subject;
        $to = get_field('expert_email',$post_id);
        $headers = array('Content-Type: text/html; charset=UTF-8');
    
        $args = array(
            'numberposts'	=> -1,
            'post_type'		=> 'expert',
            'meta_key'		=> 'expert_email',
            'meta_value'	=> get_field('consultant',$srid)->user_email
        );
        $the_query = new WP_Query( $args );
        if( $the_query->have_posts() ):
            while( $the_query->have_posts() ) : $the_query->the_post();
                $expert_id=get_the_ID();
                $expert_name= get_the_title();
            endwhile;
        endif;
    
        $m1 ='' /* __('_str Hi {{expert}},  The information regarding the lead you purchased is now available in your service request panel.','idealbiz').'<br/>' */;

        $cl_expert_email = get_field('consultant',$srid)->user_email;
        $cl_customer_email = get_field('customer',$srid)->user_email;;
        $message='';
        $cl_my_dashboard = '<a href="'.wc_get_endpoint_url('service_request', '', get_permalink(get_option('woocommerce_myaccount_page_id'))).'">'.__('_str My Leads','idealbiz').'</a>';
        $message .= '<br />'.__('_str To view your leads, access the dashboard','idealbiz').' : '.$cl_my_dashboard;
        $message .=  '<br /><br />'.__('_str Thank you.','idealbiz');       
        $emailHtml  = get_email_header();
        $emailHtml .= get_email_intro('', $m1.$message, $hi);
        $emailHtml .= get_email_footer();

/*         if (get_field('is_referral',$srid) == 1 ){ 
        wp_mail($cl_expert_email, $subject,$emailHtml,$headers);
        } 

        if (get_field('referral',$srid) == '' ){ 
            wp_mail($cl_expert_email, $subject,$emailHtml,$headers);
            $cl_lead_to_user = 0;
            } 
        
        if($cl_lead_to_user != 0){    
            if (!get_field('is_referral',$srid) == 1 ){ 
            wp_mail($cl_customer_email, $subject.'>>>'.$cl_lead_to_user,$emailHtml,$headers);
            }
        } */
        
    }

}
add_action( 'woocommerce_order_status_completed', 'system1_woocommerce_order_status_completed', 10, 1 );
add_action( 'woocommerce_payment_complete', 'system1_woocommerce_order_status_completed', 10, 1 );
//Criado pelo Cleverson tentnado enviar Lead para expert.
/* add_action('woocommerce_order_payment_status_changed','system1_woocommerce_order_status_completed', 10, 1);
add_action('woocommerce_order_status_','system1_woocommerce_order_status_completed', 10, 1);
add_action('woocommerce_payment_complete_order_status_','system1_woocommerce_order_status_completed', 10, 1); */


/*
echo $m1.$message;

echo '<br/><br/><br/>';

echo $m2.$message;

die();
*/
}


function getLeadCostListingSystem(){
    $leadCost = get_field('listing_system', 'options');
}



function replaceListingSystem($html, $ln=''){



    $listing_system = get_field('listing_system', 'options')["value"];

    $data_string = get_string_between($html, '«listingsystem-', '»');
    $new_msg= str_replace('«listingsystem-'.$data_string.'»','', $html);
    
    
        $data_string_contato = $_SESSION["contato_para_listing"];
    
    //Estava Faltando Esta Linha
    $data_arr = explode('|',$data_string_contato);




  
    $expert_id=0;
    global $wpdb;
    $queryString = "SELECT *
                    FROM {$wpdb->prefix}posts as p
                    JOIN {$wpdb->prefix}postmeta as pm 
                        ON p.ID = pm.post_id
                    WHERE p.post_type = 'expert' 
                    AND p.post_status = 'publish'
                    AND pm.meta_key = 'expert_email' 
                    AND pm.meta_value = '".$data_arr[1]."'";
                  //echo $queryString;
    $exps = $wpdb->get_results($queryString, OBJECT);
    foreach ($exps as $p){
        $expert_id=$p->ID;
    }

    //if(get_field('idealbiz_support_expert',$expert_id)==''){ // Esta linha desta maneira não distorce o email do mediador.
    if(get_field('idealbiz_support_expert',$expert_id)=='1'){ // Esta linha desta maneira não distorce o email do mediador.
    
        $subject = it('New Contact to the Ad','',$ln);
        $hi = $subject;
        $m = $new_msg;

        $to = $data_arr[1];
        $headers = array('Content-Type: text/html; charset=UTF-8');
 
        $emailHtml  = get_email_header();
        $emailHtml .= get_email_intro('', $m, $hi);
        $emailHtml .= get_email_footer();
        wp_mail($to,$subject,$emailHtml,$headers);
        
    }else{

            //BUSCA SE USER É BROKER(Cleverson)

            $cl_checa_email = $data_arr[1];
            $user = get_user_by( 'email', $cl_checa_email );
            $cl_user_id = $user->ID; 

            $subscriptions = wcs_get_subscriptions(['customer_id' => $cl_user_id,'subscriptions_per_page' => -1]);

            // Loop through subscriptions protected objects
            
            foreach ( $subscriptions as $subscription) {
                $items = $subscription->get_items();
                    foreach ($items as $item) {
                        $product_id = $item->get_product_id();
                        $cl_product_id = wc_get_product($product_id);
                    }    
            
            }
            
            $pType = get_post_meta($cl_product_id, '_product_type_meta_key', true);

            //FIM BUSCA SE É BROKER


        $post_content= get_string_between($new_msg,'<!-- Content -->','<!-- End Content -->');

        $post_data = array(
            'post_title' => $data_string,
            'post_content' => $post_content,
            'post_type' => 'leadmessages',
        );
        $post_id = wp_insert_post( $post_data );

        update_post_meta($post_id, 'listing_id', $data_arr[0]);
        update_post_meta($post_id, 'expert_id', $expert_id);
        update_post_meta($post_id, 'expert_email', $data_arr[1]);
        update_post_meta($post_id, 'user_id', $data_arr[2]);

        //para o expert
        $subject = it('New Contact to the Ad','',$ln).' Ref: '.$data_arr[0];
        $hi = '<sapn style="font-size: 25px !important;">'.$subject.'</span>';
        $m = it('Hi Member','',$ln).' '.'{{expert}}.';
        $m = str_replace('{{expert}}',get_the_title($expert_id),$m);
        $m .= '<br/><br/>'.it('You have one contact in your account','',$ln).' Ref: '.$data_arr[0];
        /* $m .= ' '.get_the_title($data_arr[0]).','; */
        $m .= '<br/><a href="'.get_post_permalink($data_arr[0]).'">'.get_post_permalink($data_arr[0]).'</a>';
        /* $m .= it('of which he is appointed as manager','',$ln); */
        $m .= '<br/><br/>'.it('To pay the lead and receive the contact details, do it here','',$ln).':<br />';
        $checkout_url =  wc_get_checkout_url() . '?add-to-cart=' . getProductByType('contact_lead').'&listing-lead='.$post_id;
        $m .= '<a href="'.$checkout_url.'">'.it('Buy Lead','',$ln).'</a>'.'.';
        $m .= '<br/><br/>'.it('Regards, The iDealBiz team','',$ln);
        /* $m .=  'CHEGA PARA BROKER'.' USER ID-> '.$cl_user_id.'-'.' Order '.$pType; */



        $to = $data_arr[1];
        $headers = array('Content-Type: text/html; charset=UTF-8');

        $emailHtml  = get_email_header();
        $emailHtml .= get_email_intro('', $m, $hi);
        $emailHtml .= get_email_footer();
        wp_mail($to,$subject,$emailHtml,$headers);


        //para o anunciante
        if ($pType != 'broker_plan') { 
        $subject = it('New Contact to the Ad','',$ln).' Ref: '.$data_arr[0];
        $hi = '<sapn style="font-size: 25px !important;">'.$subject.'</span>';
        $m = it('Hi Member','',$ln).' '.'{{expert}}.';
        $m = str_replace('{{expert}}',get_field('owner',$data_arr[0])['display_name'],$m);
        $m .= '<br/><br/>'.it('You have one contact in your account','',$ln).' Ref: '.$data_arr[0];
        /* $m .= ' '.get_the_title($data_arr[0]).','; */
        $m .= '<br/><a href="'.get_post_permalink($data_arr[0]).'">'.get_post_permalink($data_arr[0]).'</a>';
        /* $m .= it('who published on our platform','',$ln); */
        $m .= '<br/><br/>'.it('To pay the lead and receive the contact details, do it here','',$ln).':<br />';
        $checkout_url =  wc_get_checkout_url() . '?add-to-cart=' . getProductByType('contact_lead').'&listing-lead='.$post_id;
        $m .= '<a href="'.$checkout_url.'">'.it('Buy Lead','',$ln).'</a>'.'.';
        $m .= '<br/><br/>'.it('Regards, The iDealBiz team','',$ln);
        /* $m .=  'CHEGA PARA ANUNCIANTE'.' USER ID-> '.$cl_user_id.'-'.' Order '.$pType;
 */
    
        $to = get_field('owner',$data_arr[0])['user_email'];
        $headers = array('Content-Type: text/html; charset=UTF-8');
    
        $emailHtml  = get_email_header();
        $emailHtml .= get_email_intro('', $m, $hi);
        $emailHtml .= get_email_footer();
        wp_mail($to,$subject,$emailHtml,$headers);
        }

    }

    
    return $new_msg;
}


if($_GET['test']=='1'){

 cl_alerta($new_msg);
 //para o anunciante
/*  $subject = it('New Contact in ').'teste3';
 $hi = $subject;
 $m = it('Hi {{expert}}, You have one contact in your account.').'<br/>';
 $m = str_replace('{{expert}}','asd',$m);
 $m.= it('You can buy this contact at: '); 
 $m.= '<a href="">'.it('Buy').'</a>';
 $m.='<br/>';
 $m.= it('Thank you').get_locale().'-+--';
  
 var_dump($m); */

 /* $to = 'ricardo21ferreira@gmail.com'; */
/*  $to = 'cleverson.vieira@idealbiz.io';
 $headers = array('Content-Type: text/html; charset=UTF-8');

 $emailHtml  = get_email_header();
 $emailHtml .= get_email_intro('', $m, $hi);
 $emailHtml .= get_email_footer();
 wp_mail($to,  $subject, $emailHtml,  $headers);  */


}


       




if(LISTING_SYSTEM=='1'){


    register_post_type( 'leadmessages',
        array(
                'labels' => array(
                        'name' => __( 'Leads' ),
                        'singular_name' => __( 'Lead' )
                ),
        'public' => true,
        'has_archive' => true,
        'show_in_menu' => 'edit.php?post_type=expert'
        )
    );


    if(isset($_GET['listing-lead'])){
        if(isset($_GET['add-to-cart'])){
            //echo $_GET['add-to-cart'];
            //echo getProductByType('lead');
            if($_GET['add-to-cart'] == getProductByType('contact_lead')){
                if (!session_id()) {
                    session_start();
                }
                $_SESSION['lsid']=$_GET['listing-lead'];
            }
            if($_GET['add-to-cart'] == getProductByType('advertiser_lead')){
                if (!session_id()) {
                    session_start();
                }
                $_SESSION['lsid']=$_GET['listing-lead'];
            }
            //cl_alerta('wc2396'.$_SESSION['lsid']); //Teste de sessão
        }
    }
    //NPMM - Iniciar a sessão com Id do Listing que será recomendado.
    if(isset($_GET['listing-recommended'])){
        if(isset($_GET['add-to-cart'])){
            if($_GET['add-to-cart'] == get_field('rb_id_porduct_coin', 'option')){
                if (!session_id()) {
                    session_start();
                }
                $_SESSION['rb-lsid']=$_GET['listing-recommended'];
            }
        } 
    }  
    
        //NPMM - Iniciar a sessão com Id do Listing que será Pago.
        if(isset($_GET['sr-lead'])){
            if(isset($_GET['add-to-cart'])){

                    if (!session_id()) {
                        session_start();
                    }
                    $_SESSION['cl-rs-lsid']=$_GET['sr-lead'];

            } 
        }  

    


  
    function listingsystem_woocommerce_order_status_completed( $order_id ) {
        $lsid = get_post_meta( $order_id, 'lsid', true );
        if($lsid){
            $order = new WC_Order( $order_id );
            if($order->get_status()=='completed'){
                $buyer_email= $order->get_billing_email();

                $user = get_user_by( 'email', $buyer_email );
    
                $data_arr = explode('|',get_the_title($lsid));
    
                /* $subject = pll__('Contact purchase confirmation for ').' Ref: '.$data_arr[0]; */
                $ln = strtolower(get_option('country_market'));
                $subject = it('Contact purchase confirmation for ','',$ln).'  '.$order_id;
                


                $hi = $subject;
                $to = $user->display_name;
                //$expert_email = get_field('expert_email',$lsid);
                //$expert_id=get_field('expert_id',$lsid);
                $expert_name= $user->display_name;
    
    
                $headers = array('Content-Type: text/html; charset=UTF-8');
            
                $hi = it('Hi {{expert}}, here are the message of your contact: ','',$ln).'  '.$order_id;
                $hi = str_replace('{{expert}}',$expert_name, $hi);
        
                
                $m= str_replace('padding: 48px','padding: 0px',get_post_field('post_content', $lsid));
                $m= str_replace('padding:48px','padding: 0px',$m);
                $m= str_replace('<p>','<p style="margin:0;">',$m);

                /* $msg = $hello.$m.' Mercado '.$ln.' Order ID '.$order_id; */

                /* $m = $m.' Mercado  '.$order; */
            
                $emailHtml  = get_email_header();
                $emailHtml .= get_email_intro('', $m, $hi);
                $emailHtml .= get_email_footer();
                wp_mail($buyer_email, $subject, $emailHtml, $headers);
            }
        }

    }
    add_action( 'woocommerce_order_status_completed', 'listingsystem_woocommerce_order_status_completed', 10, 1 );
}








/* function cl_channge_status_order($order_id){
    $order = wc_get_order($order_id);
    cl_alerta('Order:'.$order_id.' Order Status:'.$order->status);
}
add_action('woocommerce_order_status_changed','cl_channge_status_order',10,1); */





/* add_action('woocommerce_order_status_changed', 'so_status_completed', 10, 3);

function so_status_completed($order_id, $old_status, $new_status)
{

    $order = wc_get_order($order_id);

    cl_alerta('Order:'.$order_id.' Order Status:'.$order->status);

    //$order_total = $order->get_formatted_order_total();
    $order_total = $order->get_total();

    error_log(print_r('order total: ' . $order_total, true));
} */
