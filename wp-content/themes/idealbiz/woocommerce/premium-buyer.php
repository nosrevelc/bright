<?php


/**
 * Test if user has a valid Premium Buyer Plan Active
 *
 * @since  1.0.0
 * @param  int User Id
 * @return array Premium buyer plan details or false
 */
function isPremiumBuyer($id = NULL){
    global $wpdb;
    if(!$id){ 
        $id = get_current_user_id();
    }

   // if ( false === ( $premium_buyer_data = get_transient( 'is_premium_buyer_'.$id) ) || isset($_GET['cleartransients']) ) {
        $premium_buyer_data = array();

        $ows = extractOrdersFromSubscriptions($id);
        if(empty($ows)){
            return 0;
        }
        $args = array( 'post__in' => $ows );
        $orders = wc_get_orders($args);


        foreach ($orders as $order){
            $items = $order->get_items();
            foreach ( $items as $item ) {
                $product_id = $item->get_product_id();
                $pType = get_post_meta($product_id, '_product_type_meta_key', true);
                if($pType == 'premium_buyer_plan'){
                    $variation_id = $item->get_variation_id();
                    $variation_duration = get_post_meta( $variation_id, 'variation_duration', true );
                    
                    $data = $order->get_date_completed();
                    $expdate = date('Y-m-d', strtotime($data. ' + '.$variation_duration.' days'));
                    $today = new DateTime();
                    $planend = new DateTime($expdate);

                    if ($planend > $today) {
                        $premium_buyer_data= array(
                            'status' => 3,
                            'variation_id' => $variation_id,
                            'variation_duration' => $variation_duration
                        );
                    }elseif ($planend == $today) {
                        $premium_buyer_data= array(
                            'status' => 2,
                            'variation_id' => $variation_id,
                            'variation_duration' => $variation_duration
                        );
                    }else{
                        $premium_buyer_data= 0;
                    }

                }
            }
        }
  /*      set_transient( 'is_premium_buyer_'.$id, $premium_buyer_data, 24 * HOUR_IN_SECONDS );
    } */
    return $premium_buyer_data;

}


/**
 * Get the active subscriptions of User Broker or Premium Buyer
 *
 * file broker.php getSubscriptionsOfUser($id = NULL){ }
 */


/**
 * Create premium user's email template
 *
 * @param Post     $listing_ids  The new listing Id
 * @param string  $post_date   The scheduled formatted date
 * @return void
 */
function premium_buyer_get_email_template( $posts, $username ) {
    
    $site_url = get_home_url();
    $site_title = get_bloginfo( 'name' );

    $social_networks = get_social_networks();

    $img = get_option( 'woocommerce_email_header_image' );

    $style = '
    /* Raleway latin-ext */
    @font-face {
        font-family: "Raleway";
        font-style: normal;
        font-weight: 700;
        src: local("Raleway Bold"), local("Raleway-Bold"), url("https://fonts.gstatic.com/s/raleway/v11/WmVKXVcOuffP_qmCpFuyzQsYbbCjybiHxArTLjt7FRU.woff") format("woff");
        unicode-range: U+0100-024F, U+1E00-1EFF, U+20A0-20AB, U+20AD-20CF, U+2C60-2C7F, U+A720-A7FF;
    }

    /* Roboto latin-ext */
    @font-face {
        font-family: "Roboto";
        font-style: normal;
        font-weight: 400;
        src: local("Roboto"), local("Roboto-Regular"), url("https://fonts.gstatic.com/s/roboto/v16/Ks_cVxiCiwUWVsFWFA3Bjn-_kf6ByYO6CLYdB4HQE-Y.woff") format("woff");
        unicode-range: U+0100-024F, U+1E00-1EFF, U+20A0-20AB, U+20AD-20CF, U+2C60-2C7F, U+A720-A7FF;
    }
    .status-badge {display: none;}

    .listing {
        max-width: 33%;
        border-radius: .2rem;
        width: 33%;
        overflow: hidden;
    }

    ';

    $head = sprintf(
        '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>%1$s</title>
                <style>
                    %2$s
                </style>
            </head>',
        esc_html( sprintf( __( 'New Listings on %1$s', 'idealbiz' ), $site_title ) ),
        $style
    );

    $header = sprintf(
        '<header class="template__header" style="background-color: #FFF;padding: 22px 25px;margin-bottom: 25px;">
            <a href="%1$s" style="color: #1397E1;text-decoration: none;font-size: 20px;">
                <img src="%2$s" alt="%3$s" class="brand" style="width: 130px;">
            </a>«flag»
            </header>',
        esc_url( $site_url ),
        esc_url( $img ),
        esc_attr__( 'IdealBiz Logo', 'idealbiz' )
    );
    $listing='';
    $x=0;

    $listing.='<div style="width: 100%">';
    if($posts->have_posts() ) {
        while($posts->have_posts() ) {
            $posts->the_post();
                $var = '<a href="'.get_permalink().'" style="width: 33%;">
                            <img class="w-100" style="100%" src="'.get_field('featured_image')['sizes']['medium'].'">
                            <div class="location m-t-10 m-b-5">
                                <span style="font-size: 10px; color:#222;" class="text-uppercase">'.esc_html(get_field('location')->name).'</span>
                            </div>
                            <p style="font-size: 12px; color:#222222;"> '.get_the_title().'</p>
                            
                            <div class="d-flex flex-row justify-content-between align-items-end m-t-15">
                                <span class="category" style="font-size: 10px; color:#222;">'.get_field('category')->name.'</span>
                                <span class="price" style="font-size: 10px; color:#222;">'.IDB_Listing_Data::get_listing_value(get_the_ID(), 'price_type').'</span>
                            </div>
                        </a>';
            $listing.='<div style="width: 33%; float:left;">'.$var.'</div>';
        }
    }
    $listing.='</div>';


    foreach ($posts as $l){
            if($x>2){
                break;
            }else{
                $x++;
            }    
            //var_dump($l);
        break;
        if($x>2){

        }
    }

    /* deadline countdown  $deadline = array();    $now = new \DateTime();    $date = new \DateTime();   $date->setTimestamp( $post_date );  $difff = $date->diff( $now );    $days = $difff->format( '%d' );    if ( ! empty( $days ) ) {     $deadline[] = sprintf( __( '%1$s days', 'idealbiz' ), $difff->format( '%d' ) );    } $hours = $difff->format( '%h' );   if ( ! empty( $hours ) ) {       $deadline[] = sprintf( __( '%1$s hours', 'idealbiz' ), $difff->format( '%h' ) );   }   $deadline_html = ''; if ( ! empty( $deadline ) ) {  $deadline = implode( ', ', $deadline );    $deadline_html = sprintf('<p class="template__welcome-text" style="color: #84898F;margin: 0;">%1$s</p>', esc_html( sprintf( __( 'This Listing will be made public in %1$s.', 'idealbiz' ), $deadline ) )); }*/

    $main = sprintf(
        '<main class="template__content">
            <section class="template__welcome" style="padding: 0 25px;">
                <h2 class="template__user-name" style="font-family: \'Raleway-Bold\', \'Lucida Grande\', \'Lucida Sans Unicode\', Sans-Serif;font-weight: 700;font-size: 20px;margin-bottom: 10px;">%1$s</h2>
                <p class="template__welcome-text" style="color: #84898F;margin: 0;">%2$s</p>
                <p class="template__welcome-text" style="color: #84898F;margin: 0;">%3$s</p>
                %4$s
            </section>
            %5$s

        </main>',
        esc_html( __('Hello', 'idealbiz').' '.$username.',' ),
        esc_html( sprintf( __( 'New Listings have been added on %1$s.', 'idealbiz' ), $site_title ) ),
        esc_html__( 'See the listings now with your premium access.', 'idealbiz' ),
        $deadline_html,
        $listing
    );

    $social_links = '';
    $asset_uri = get_stylesheet_directory_uri() . '/assets/img/email';
    foreach ( $social_networks as $network => $social_network ) {
        if ( empty( $social_network['url'] ) ) {
            continue;
        }

        $social_links .= sprintf(
            '<li class="social__item" style="display: inline-block;height: 38px;width: 38px;margin: 0;">
                <a href="%1$s" class="social__link" style="color: #1397E1;text-decoration: none;font-size: 20px;padding: 8px 10px;">
                    <img src="%2$s" alt="%3$s">
                </a>
            </li>',
            esc_url( $social_network['url'] ),
            esc_url( sprintf( '%1$s/%2$s.png', $asset_uri, $network ) ),
            esc_attr( $social_network['title'] )
        );
    }

    $year = esc_html( date( 'Y' ) );
    $year = '';
    $footer = sprintf(
        '<footer style="background-color: #fff;margin-top: 70px;padding: 25px 30px 30px;">
            <a href="%1$s" style="color: #1397E1;text-decoration: none;font-size: 20px;">
                <img src="%2$s" alt="%3$s" class="brand" style="width: 130px;">
            </a>
            <span class="rights" style="color: #84898F;display: inline-block;margin-left: 30px;vertical-align: middle;margin-bottom: 30px;">%4$s</span>
            <ul class="social" style="list-style: none;padding: 0;margin: 0 0 0 -20px;">
                %5$s
            </ul>
        </footer>',
        esc_url( $site_url ),
        esc_url( $img ),
        esc_attr__( 'IdealBiz Logo', 'idealbiz' ),
        $year,
        $social_links
    );

    $body = sprintf( '
        <body class="tempalte" style="font-family: \'Roboto-Regular\', \'Lucida Grande\', \'Lucida Sans Unicode\', Sans-Serif;background-color: #FBFBFC;max-width: 600px;width:100%%;margin: 0 auto;font-size: 16px;">
            %1$s
            %2$s
            %3$s
        </body>
    </html>',
        $header,
        $main,
        $footer
    );
    return $body;
}

/**
 * Set Title no new post
 *
 * @since  1.0.0
 */
function wpb_lastvisit_the_title ( $title, $id ) {
if(is_user_logged_in()){
    if(!get_user_meta(get_current_user_id(), 'read_post_' . $id, false)){
        $title = $title . '<span class="new-article">'.__('New','idealbiz').'</span>';
    }
}
return $title;
}
//add_filter( 'the_title', 'wpb_lastvisit_the_title', 10, 2);




function cron_do_this_daily_premium_buyer_Hook_451524fa() {
    do_this_daily_premium_buyer();
}

add_action( 'do_this_daily_premium_buyer_Hook', 'cron_do_this_daily_premium_buyer_Hook_451524fa', 10, 0 );


//CUSTOM TIMER, you can change for your won
function do_this_daily_premium_buyer_cron_schedule( $schedules ) {
    $schedules['1Day'] = array(
                                   'interval' => 86400, // 24hours in secounds
                                   'display'  => __( '1Day' ),
                                    );
    return $schedules;
 }

 //ADD it
 add_filter( 'cron_schedules', 'do_this_daily_premium_buyer_cron_schedule' );
 
 //Schedule an action if it's not already scheduled
   if ( ! wp_next_scheduled( 'do_this_daily_premium_buyer_Hook' ) ) {
        wp_schedule_event( time(), '1Day', 'do_this_daily_premium_buyer_Hook' );
   }
 
 ///Hook into that action that'll fire every 
 add_action( 'do_this_daily_premium_buyer_Hook', 'do_this_daily_premium_buyer' );
 



function do_this_daily_premium_buyer() {
    // do something every daily
    $args_makepublished = array(
        'post_type' => 'listing',
        'date_query' => array(
            array(
                'before' => /*$last_date[0],*/ '-7 days',
                'inclusive' => true
            )
        ),
        'post_status' => ['pending'],
        'suppress_filters' => true,
        'posts_per_page' => -1/*,wordpres
        'lang' => $inLang*/
    );



    $makepublished = new WP_Query( $args_makepublished );  
    if($makepublished->have_posts() ) {
        while($makepublished->have_posts() ) {
            $makepublished->the_post();
            wp_update_post(array(
                'ID'    =>  get_the_ID(),
                'post_status'   =>  'publish'
            ));
        }
    }
    wp_reset_postdata();



    $$count_per_author=0;

    $args = array( 'post__in' => getAllextractOrdersFromSubscriptions() );
    $orders = wc_get_orders($args);

    foreach ($orders as $order){

        $items = $order->get_items();
        foreach ( $items as $item ) {
            $product_id = $item->get_product_id();
            $pType = get_post_meta($product_id, '_product_type_meta_key', true);

            if($pType == 'premium_buyer_plan'){
                $count_per_author=0;
                $unreadPosts=0;
                $premium_buyer_user = $order->get_user();



                // last date user entered here
                $last_date = get_user_meta( $premium_buyer_user->ID, 'premium_buyer_last_view', false );
                //echo $last_date[0];
                if(!$inLang){
                    $inLang = getLangSlug('default');
                }
                $args2 = array(
                    'post_type' => 'listing',
                    'date_query' => array(
                        array(
                            'after' => /*$last_date[0],*/ '-7 days',
                            'inclusive' => true
                        )
                    ),
                    'post_status' => ['publish', 'pending'],
                    'inclusive' => true,
                    'suppress_filters' => true,
                    'posts_per_page' => -1/*,wordpres
                    'lang' => $inLang*/
                );
                $listings = new WP_Query( $args2 );  
                $count_per_author = $listings->found_posts;

                if($count_per_author>0){
                    $user_info = get_userdata($premium_buyer_user->ID);
                    $username = $user_info->user_login;

                    if($listings->have_posts() ) {
                        while($listings->have_posts() ) {
                            $listings->the_post();
                            if(!get_user_meta($premium_buyer_user->ID, 'read_post_' . get_the_ID(), false)){
                                //echo 'unread-post.'.the_title(); 
                                $unreadPosts++;
                            }
                        }
                    }

                    if($unreadPosts>0){

                        /********** Email **********/
                        $message = __( 'New Listings have been added on your', 'idealbiz' ).' <b>'.__('Premium Buyer Account.','idealbiz').'</b>';
                        $emailHtml  = get_email_header($premium_buyer_user->ID, $email_title);
                        $emailHtml .= get_email_intro($premium_buyer_user->ID, $message);
                        $emailHtml .= get_email_listings($listings, 3);
                        $emailHtml .= get_email_footer();
                        /* Email Params */
                        $subject = get_bloginfo().' - '.__( 'You have', 'idealbiz' ).' '.$unreadPosts.' '.__('New Listings in your Premium Buyer Account.','idealbiz').'';
                        $headers = array('Content-Type: text/html; charset=UTF-8');
                        //echo $emailHtml;
                        wp_mail(
                            $user_info->user_email, 
                            $subject, 
                            $emailHtml,
                            $headers);
                        /********** Email **********/
                    }
                    //echo $user_info->user_email.$unreadPosts;
                }
            }
        }
    }



    
    die();
}





