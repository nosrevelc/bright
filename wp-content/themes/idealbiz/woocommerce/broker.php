<?php

global $broker;

/**
 * Test if user has a valid Broker Plan Active 
 *
 * @since  1.0.0
 * @param  int user_id
 * @param  string Language to query
 * @return array of broker plan details or false
 */
function isBroker($id = NULL, $inLang = NULL)
{
    global $wpdb;
    if (!$id) {
        $id = get_current_user_id();
    }
    if (!$inLang) {
        $inLang = pll_current_language();
    }
    if ( false === ( $broker_data = get_transient( 'is_broker_'.$id.'_'.$inLang ) ) || isset($_GET['cleartransients']) ) {
        $broker_data= array();
        if (function_exists('wcs_get_users_active_subscriptions')) {
            $subscriptions = wcs_get_users_active_subscriptions($id);

            foreach ($subscriptions as $subscription_id => $subscription) :
                $args = array('post__in' => $subscription->get_related_orders());
                $orders = wc_get_orders($args);
                foreach ($orders as $order) {
                    $items = $order->get_items();
                    foreach ($items as $item) {
                        $product_id = $item->get_product_id();
                        $product = wc_get_product($product_id);

                        $pType = get_post_meta($product_id, '_product_type_meta_key', true);
                        if ($pType == 'broker_plan') { 

                            $ordered_variation_id = false;
                            if ($product->is_type('variable')) {
                                $ordered_variation_id = $item->get_variation_id();
                                //echo $ordered_variation_id;
                            //  die();
                            }
                            $pAtts = $product->get_attributes();
                            $variations = $product->get_available_variations(); 
                            foreach ($pAtts as $pk => $pa) { 
                                foreach ($pa['options'] as $pot) {
                                    foreach ($variations as $key => $value) {
                                        if (in_array($pot, $value['attributes'])) {

                                            $bought_var = new WC_Product_Variation($ordered_variation_id);
                                            $bought_lang = $bought_var->get_variation_attributes()['attribute_langs'];
                                            $bought_duration = $bought_var->get_variation_attributes()['attribute_pa_duration'];
                                            global $wpdb;
                                            $bought_term_id = $wpdb->get_col("SELECT term_id FROM $wpdb->terms WHERE slug = '$bought_duration'")[0];

                                            $test_var = new WC_Product_Variation($value['variation_id']);
                                            $test_lang = $test_var->get_variation_attributes()['attribute_langs'];
                                            $test_duration = $test_var->get_variation_attributes()['attribute_pa_duration'];
                                            $term = get_term_by('slug', $test_duration, 'pa_duration');
                                            $term_id = $term->term_id;
                                            $termIdInInLang= pll_get_term($term_id, strtolower($test_lang));

                                            //echo '"'.$bought_term_id.'" == "'.$termIdInInLang.'" &&'.$test_lang.' == '.$bought_lang.'<br/>';

                                            if( ($bought_term_id == $termIdInInLang) && ($test_lang == $bought_lang) && strtolower($inLang) == strtolower($bought_lang)){

                                                    $variation_id = $item->get_variation_id();
                                                    $variation_duration = get_post_meta($variation_id, 'variation_duration', true);
                                                    $variation_listing_price_override = get_post_meta($variation_id, 'variation_listing_price_override', true);
                                                    $variation_listing_reduced_slots = get_post_meta($variation_id, 'variation_listing_reduced_slots', true);
                                                    $variation_listing_price_overbroker = get_post_meta($variation_id, 'variation_listing_price_overbroker', true);

                                                    $data = $order->get_date_created()->format('Y-m-d H:i:s');
                                                    //var_dump($order);
                                                    $expdate = date('Y-m-d', strtotime($data . ' + ' . $variation_duration . ' days'));
                                                    $today = new DateTime();
                                                    $planend = new DateTime($expdate);

                                                    if ($planend > $today) {
                                                        $broker_data= array(
                                                            'status' => 3,
                                                            'variation_id' => $variation_id,
                                                            'variation_duration' => $variation_duration,
                                                            'listing_price_override' => $variation_listing_price_override,
                                                            'list_slots' => $variation_listing_reduced_slots,
                                                            'publish_in_language' => strtolower($pot),
                                                            'order_date' => $data,
                                                            'extra_list_price ' => $variation_listing_price_overbroker
                                                        );
                                                    } elseif ($planend == $today) {
                                                        $broker_data= array(
                                                            'status' => 2,
                                                            'variation_id' => $variation_id,
                                                            'variation_duration' => $variation_duration,
                                                            'listing_price_override' => $variation_listing_price_override,
                                                            'list_slots' => $variation_listing_reduced_slots,
                                                            'publish_in_language' => strtolower($pot),
                                                            'order_date' => $data,
                                                            'extra_list_price ' => $variation_listing_price_overbroker
                                                        );
                                                    } else {
                                                        $broker_data= 0;
                                                    }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            endforeach;
            set_transient( 'is_broker_'.$id.'_'.$inLang, $broker_data, 10 );
        }
        }
    return $broker_data;
}

/**
 * Get all active brokers
 *
 * @since  1.0.0
 * @return array Brokers IDS
 */
function getAllBrokers()
{
    $brokers = array();
    $users = get_users(array('fields' => 'ID'));

    foreach ($users as $user_id) {
        if (isBroker($user_id, pll_current_language())) {
            $brokers[] = $user_id;
        }
    }

    return $brokers;
}

/**
 * Get the active subscriptions of User Broker or Premium Buyer
 *
 * @since  1.0.0
 * @param  int User ID
 * @return string Html
 */
function getSubscriptionsOfUser($id = NULL, $subtype = NULL)
{
    global $product, $post;
    global $wpdb;
    if (!$id) {
        $id = get_current_user_id();
    }
     if (function_exists('wcs_get_users_active_subscriptions')) {
        $subscriptions = wcs_get_users_active_subscriptions($id);
    } else {
        $subscriptions = array();
    }

    $h = '';
    $b = '';
    $p = '';

    foreach ($subscriptions as $subscription_id => $subscription) :

        if(isset($_GET['md31'])){
        echo $subscription_id.'<br/>';

        }
        

        $args = array('post__in' => $subscription->get_related_orders());
        $orders = wc_get_orders($args);
      
        foreach ($orders as $order) {

            $items = $order->get_items();
            foreach ($items as $product_line) {



            if(isset($_GET['md1'])){
                var_dump($product_line);
                echo '<br/>';
            }


                $product_id = $product_line->get_product_id();
                $product = wc_get_product($product_id);
                $pType = get_post_meta($product_id, '_product_type_meta_key', true);
              //  echo $product_id;
                if ($pType == 'broker_plan' || $pType == 'premium_buyer_plan') {

                    $ordered_variation_id = false;
                    if ($product->is_type('variable')) {
                        $ordered_variation_id = $product_line->get_variation_id();
                    }

                    $pAtts = $product->get_attributes();
                    $variations = $product->get_available_variations();


                    if(isset($_GET['md2'])){ 
                        
                        echo '<pre>';
                                    var_dump($ordered_variation_id);
                                    echo '</pre>';

                        foreach ($pAtts as $pk => $pa) {
                            //prodcut atributes
                            foreach ($pa['options'] as $pot) {
                                echo '<pre>';
                                var_dump($pot);
                                echo '</pre>';
                            }
                            
                            
                        }


                        die();
                    }

                    foreach ($pAtts as $pk => $pa) {
                        foreach ($pa['options'] as $pot) { // $pot = language var_dump($pot);
                            //  if(count($pa['options']) > 1){  }


                            
                            foreach ($variations as $key => $value) {

                                if(isset($_GET['md3'])){
                                   /* echo '<pre>';
                                    var_dump($value['variation_description']);
                                    echo '</pre>';*/
                                 }
                   
                                if (in_array($pot, $value['attributes'])) { 

                                    $bought_var = new WC_Product_Variation($ordered_variation_id);
                                    $bought_lang = $bought_var->get_variation_attributes()['attribute_langs'];

                                    #239
                                    $bought_duration = $bought_var->get_variation_attributes()['attribute_pa_duration'];
                                    //$bought_duration = $bought_var->get_variation_attributes()['attribute_duration'];

                                    
                                    global $wpdb;
                                    $bought_term_id = $wpdb->get_col("SELECT term_id FROM $wpdb->terms WHERE slug = '".$bought_duration."'")[0];

                                    $test_var = new WC_Product_Variation($value['variation_id']);
                                    $test_lang = $test_var->get_variation_attributes()['attribute_langs'];
                                    $test_duration = $test_var->get_variation_attributes()['attribute_pa_duration'];
                                    $term = get_term_by('slug', $test_duration, 'pa_duration');
                                    $term_id = $term->term_id;
                                    $termIdInInLang= pll_get_term($term_id, strtolower($test_lang));
  
                                    if(isset($_GET['md31'])){
                                        /*
                                        echo '<pre>';
                                        var_dump($bought_var->get_variation_attributes());
                                        echo '</pre>';
                                        */

                                        //echo '</br></br></br>';
                                        //echo "SELECT term_id FROM $wpdb->terms WHERE slug = '".$bought_duration."'";

                                        
                                        echo '</br>
                                        "'.$bought_term_id.'" == "'.$termIdInInLang.'" &&'.$test_lang.' == '.$bought_lang.'<br/>';
                                    }

                                    if( ($bought_term_id == $termIdInInLang) && ($test_lang == $bought_lang) ){

                                        $line = '
                                        <tr class="order">
                                            <td data-' . $pType . '="' . $pot . '" class="subscription-id order-number">
                                                <p class="text-left">';

                                        $pname = '';
                                        if ($pType == 'broker_plan') {
                                            $line .= __('To publish in', 'idealbiz') . ' ' . $pot . ': ';
                                            $pname = $term ->name .'/'.$bought_lang;
                                        } elseif ($pType == 'premium_buyer_plan') {
                                            $line .= __('Premium Buyer ', 'idealbiz') . '';
                                        }

                                        $line .= '<span class="font-weight-bold">' . $pname . '</span> 
                                            </td>
                                            <td class="subscription-status order-status" >
                                            ' . esc_attr(wcs_get_subscription_status_name($subscription->get_status())) . '
                                            </td>

                                            <td class="subscription-next-payment order-date">
                                                ' . esc_attr($subscription->get_date_to_display('next_payment')) . '
                                            </td>
                                            <td class="subscription-total order-total">
                                                ' . wp_kses_post($subscription->get_formatted_order_total()) . '
                                            </td>
                                            <td class="subscription-actions order-actions" style="text-align: center;">
                                                <a href="' . esc_url($subscription->get_view_order_url()) . '" class="btn btn-blue blue--hover ">
                                                    ' . __('View', 'woocommerce-subscriptions') . '
                                                </a>
                                                ';
                                                $x= countListsOfBroker($id,strtolower($pot));
                                                if ($pType == 'broker_plan') {
                                                    if($x < 10){
                                                        $line.='
                                                        <a href="'.getLinkByTemplate('submit-listing.php').'" class="btn btn-blue blue--hover backto--subscription">
                                                            ' . __('Add listing', 'woocommerce-subscriptions') . '
                                                        </a>';
                                                        $line.='<br/><span> ('.(10-$x).' '. __('Available this Subscription', 'idealbiz').')</span>';
                                                    }else{
                                                        $line.='
                                                        <div class="btn btn-blue" style="background: #777; opacity: 0.7;">
                                                            ' . __('Add listing', 'woocommerce-subscriptions') . '
                                                        </div>';
                                                        $line.='<br/><span> (0 '. __('Available this Subscription', 'idealbiz').')</span>';
                                                    }
                                                }
                                                $line.='
                                            </td>
                                        </tr>';
                                        if ($pType == 'broker_plan') {
                                            $b.=$line;

                                             //a default
                   
                                        } elseif ($pType == 'premium_buyer_plan') {
                                            $p.=$line;
                                        }
                                    }
                                }
                            }
                        }
                        break;
                    }
                }
            }
        }

        if(isset($_GET['md31'])){
           die();
        }

    endforeach;
    

    if ($subscriptions) {
        if ($b!='') {
            $b='<div class="row broker-subscriptions"><div class="col-md-12">
                <div class="block stroke dropshadow p-30 m-b-25 b-r-5 white--background">
                <h2 class="text-left m-b-15">
                '.__('Broker', 'idealbiz').'
                </h2>
                <table class="shop_table shop_table_responsive my_account_orders m-b-0 w-p-100">
                    <thead>
                        <tr>
                            <th class="order-number"><span class="nobr">' . __('Subscription', 'woocommerce-subscriptions') . '</span></th>
                            <th class="order-status"><span class="nobr">' . __('Status', 'woocommerce-subscriptions') . '</span></th>
                            <th class="order-date"><span class="nobr">' . __('Next Payment', 'woocommerce-subscriptions') . '</span></th>
                            <th class="order-total"><span class="nobr">' . __('Total', 'woocommerce-subscriptions') . '</span></th>
                            <th class="order-actions">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                    ' .$b. '
                    </tbody>
                </table>
                </div>     
            </div></div>';
        }
        if ($p!='') {
            $p='<div class="row premium-buyer-subscriptions"><div class="col-md-12">
                <div class="block stroke dropshadow p-30 m-b-25 b-r-5 white--background">
                <h2 class="text-left m-b-15">
                ' . __('Premium Buyer', 'idealbiz') . '
                </h2>
                <table class="shop_table shop_table_responsive my_account_orders m-b-0 w-p-100">
                    <thead>
                        <tr>
                            <th class="order-number"><span class="nobr">' . __('Subscription', 'woocommerce-subscriptions') . '</span></th>
                            <th class="order-status"><span class="nobr">' . __('Status', 'woocommerce-subscriptions') . '</span></th>
                            <th class="order-date"><span class="nobr">' . __('Next Payment', 'woocommerce-subscriptions') . '</span></th>
                            <th class="order-total"><span class="nobr">' . __('Total', 'woocommerce-subscriptions') . '</span></th>
                            <th class="order-actions">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                    ' .$p. '
                    </tbody>
                </table>
                </div>     
            </div></div>';
        }
    }

    if($subtype=='broker'){
        return $b;
    }
    if($subtype=='premium_buyer'){
        return $p;
    }

    $h=$b.$p;
    return $h;
}


/**
 * Count the posts of the Broker by language
 *
 * @since  1.0.0
 * @param  int user_id
 * @param  string language to query
 * @return int number of posts
 */
function countListsOfBroker($id = NULL, $lang = NULL)
{
    global $wpdb;
    if (!$id) {
        $id = get_current_user_id();
    }
    if (!$lang) {
        $lang =pll_current_language();
    }

    $broker = isBroker($id, $lang);
    $broker_after_date = $broker['order_date'];
    //echo $broker_after_date;

    //var_dump(get_post(70500));

    $args = [
        'author' => $id,
        'post_type' => 'listing',
        'date_query' => [
            array(
                'relation'    => 'AND',
                array( 'after' => date('01-m-Y') ),
                array( 'before' => date('t-m-Y 23:59:59') ),
                array( 'after' => date($broker_after_date) ),
            ),
        ],
        'post_status' => ['publish', 'draft'],
       // 'inclusive' => true,
        'suppress_filters' => true,
        'posts_per_page' => -1,
        'lang' => $lang,
        'meta_query' => array(
            array(
                'key' => 'broker_list',
                'value' => '1'
            )
        ),
    ];

    $query1 = new WP_Query($args);
   // echo "Last SQL-Query: {$query1->request}";

    // Get the posts from this coauthor, but not this author.
        $coauthor_not_author = array(
            'post_type' => 'listing',
            'date_query' => [
                array(
                    'relation'    => 'AND',
                    array( 'after' => date('01-m-Y') ),
                    array( 'before' => date('t-m-Y 23:59:59') ),
                    array( 'after' => date($broker_after_date) ),
                ),
            ],
            'post_status' => ['publish', 'draft'],
            'posts_per_page' => -1,
            'lang' => $lang,
            'author__not_in'         => array( $id ),
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'owner',
                    'value' => $id,
                ),
                array(
                    'key' => 'broker_list',
                    'value' => '1'
                )
            ),
        );
        $query2 = new WP_Query($coauthor_not_author);
      //  echo "Last SQL-Query: {$query2->request}";

/*
        var_dump($query1->post_count);
        var_dump($query2->post_count);
        die();
*/
        
        return $query1->post_count+$query2->post_count;
        /*
        $wp_query = new WP_Query();
        $wp_query->posts = array_merge( $query1->posts, $query2->posts );
        $wp_query->post_count = $query1->post_count + $query2->post_count;


    $count_per_author = $query->post_count;
    return $count_per_author;
    */
}




/**
 * get the posts of the Broker by language
 *
 * @since  1.0.0
 * @param  int user_id
 * @param  string language to query
 * @return int number of posts
 */
function getListsOfBroker($id = NULL, $lang = NULL)
{
    global $wpdb;
    if (!$id) {
        $id = get_current_user_id();
    }
    if (!$lang) {
        $lang =pll_current_language();
    }

    $broker = isBroker($id, $lang);
    $broker_after_date = $broker['order_date'];
    //echo $broker_after_date;

    $args = [
        'author' => $id,
        'post_type' => 'listing',
        'date_query' => [
            array(
                'relation'    => 'AND',
                array( 'after' => date('01-m-Y') ),
                array( 'before' => date('t-m-Y') ),
                array( 'after' => date($broker_after_date) ),
            ),
        ],
        'post_status' => ['publish', 'draft'],
       // 'inclusive' => true,
        'suppress_filters' => true,
        'posts_per_page' => -1,
        'lang' => $lang,
        'meta_query' => array(
            array(
                'key' => 'broker_list',
                'value' => '1'
            )
        ),
    ];

    $query1 = new WP_Query($args);
   // echo "Last SQL-Query: {$query1->request}";

    // Get the posts from this coauthor, but not this author.
        $coauthor_not_author = array(
            'post_type' => 'listing',
            'date_query' => [
                array(
                    'relation'    => 'AND',
                    array( 'after' => date('01-m-Y') ),
                    array( 'before' => date('t-m-Y') ),
                    array( 'after' => date($broker_after_date) ),
                ),
            ],
            'post_status' => ['publish', 'draft'],
            'posts_per_page' => -1,
            'lang' => $lang,
            'author__not_in'         => array( $id ),
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'owner',
                    'value' => $id,
                ),
                array(
                    'key' => 'broker_list',
                    'value' => '1'
                )
            ),
        );
        $query2 = new WP_Query($coauthor_not_author);
      //  echo "Last SQL-Query: {$query2->request}";

        $wp_query = new WP_Query();
        $wp_query->posts = array_merge( $query1->posts, $query2->posts );
        $wp_query->post_count = $query1->post_count + $query2->post_count;

    return $wp_query;
}




/*
* Need to save current lang of user in database in order to do ajax calls
*
*/
/*
function pll_current_language(){
    return 'pt';
}
function pll_register_string(){
    return;
}
*/

$c_lang = get_user_meta(get_current_user_id(),  'active_lang', true);
if ($c_lang != pll_current_language() && !wp_doing_ajax()) {
    update_user_meta(get_current_user_id(), 'active_lang', pll_current_language());
}


/**
 * Calculates & Reduces Price if User is a valid broker and have slots avaliable
 *
 * @since  1.0.0
 * @param  WP_Post id
 * @return 
 */

function calcPrice($postID)
{

    
    if(is_admin()){

    }else{

    
    $price = get_post_meta($postID, '_regular_price')[0];
    // echo wp_doing_ajax();
    $current_lang = pll_current_language();
    if (wp_doing_ajax()) {
        $current_lang = get_user_meta(get_current_user_id(),  'active_lang', true);
    }
    // var_dump(wp_doing_ajax(). $current_lang);

    $broker = isBroker(NULL, $current_lang);
    if(array_key_exists('status', $broker))
    if (is_array($broker) && $broker['status'] > 1) {
        if (countListsOfBroker() < $broker['list_slots']) {
            $pType = get_post_meta($postID, '_product_type_meta_key', true);
            if ($pType) {
                if ($pType == 'listing_plan' || $pType == 'wanted_plan') {
                    $monthsMultiplier = daysToMonth()[get_post_meta($postID, 'plan_duration', true)];
                    $price = $broker['listing_price_override'] * $monthsMultiplier;
                }
            }
        }else{
            if(!$broker['extra_list_price']){
                $broker['extra_list_price']=10;
            }
            return $broker['extra_list_price'];
        }
    }
    return $price;
}
}


/**
 * Listing Price changer
 *
 * @since  1.0.0
 * @param  float $price, WP_Post $product
 * @return float $price
 */
function pr_reseller_price($price, $product)
{
    global $post, $blog_id;
    //$price =  calcPrice($product->get_id());
    $price = calcPrice($product->get_id());
    if(LISTING_SYSTEM=='1'){
        //return 0;
    }
    return $price;
}
add_filter('woocommerce_product_get_regular_price', 'pr_reseller_price', 10, 2);
