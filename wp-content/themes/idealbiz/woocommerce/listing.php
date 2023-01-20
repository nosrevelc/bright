<?php

/**
 * Send every day notice emails to premium buyer
 * Runs Every day.
 *
 * @since  1.0.0
 */




//CUSTOM TIMER, you can change for your won
function do_this_daily_expired_listings_cron_schedule( $schedules ) {
    $schedules['1Day'] = array(
                                   'interval' => 86400, // 24hours in secounds 86400
                                   'display'  => __( '1Day' ),
                                    );
    return $schedules;
 }

 //ADD it
 add_filter( 'cron_schedules', 'do_this_daily_expired_listings_cron_schedule' );
 
 //Schedule an action if it's not already scheduled
   if ( ! wp_next_scheduled( 'do_this_daily_expired_listings_Hook' ) ) {
        wp_schedule_event( time(), '1Day', 'do_this_daily_expired_listings_Hook' );
   }
 
 ///Hook into that action that'll fire every 
 add_action( 'do_this_daily_expired_listings_Hook', 'do_this_daily_expired_listings' );
 
function do_this_daily_expired_listings() {
    // do something every daily
    $count_per_author=0;

        global $wpdb;
        $result = $wpdb->get_results ( "
            SELECT * 
            FROM  $wpdb->posts
                WHERE post_type = 'listing'
                AND post_status = 'publish' 
        " );
        $removeid='';    
        $num_acts=0; $num_deletes=0;
        $expire_warning=Date('Ymd', strtotime('+10 days'));

        foreach ( $result as $post )
        {
        $rid='';    
        $owner =  get_post_meta($post->ID,'owner', true);
        $expire_date =  get_post_meta($post->ID,'expire_date', true);

            if($expire_date){
                if($expire_date < date('Ymd') && !isBroker($owner, pll_current_language())){ // expirou
                    $rid= $post->ID;
                    $num_deletes++;
                }
            }

            if($rid != ''){
                $posttodraft = array( 'ID' => $post->ID, 'post_status' => 'draft' );
                wp_update_post($posttodraft);
            }
            

            if($rid == ''){
                if($expire_date <= $expire_warning && $expire_date!=''){
                  //  echo $post->ID.'<br/>';

                    $user_info = get_userdata($owner);

                     /********** Email **********/
                     $message = __( 'You have listings about to expire!', 'idealbiz' );
                     $bodymessage = $post->post_title." ".__('is about to expire, please check you account and renew it!','idealbiz');
                     $emailHtml  = get_email_header($owner, '');
                     $emailHtml .= get_email_intro($owner, $message);
                     $emailHtml .= get_email_myaccount( $bodymessage );
                     $emailHtml .= get_email_footer();
                     /* Email Params */
                     $subject = get_bloginfo().' - '.__( 'You have listings about to expire!', 'idealbiz' );
                     $headers = array('Content-Type: text/html; charset=UTF-8');
                    // echo $emailHtml;
                     wp_mail(
                         $user_info->user_email, 
                         $subject, 
                         $emailHtml,
                         $headers); 
                     /********** Email **********/


                }
            }
        }
     //   echo $num_deletes;
     //   echo $num_acts;

       
        echo $NewDate;



/*
    foreach ($orders as $order){

        $items = $order->get_items();
        foreach ( $items as $item ) {
            $product_id = $item->get_product_id();
            $pType = get_post_meta($product_id, '_product_type_meta_key', true);
            
            if($pType == 'listing_plan' || $pType == 'broker_plan' || $pType == 'wanted_plan'){

                $duration = get_post_meta($product_id, 'related_post_id', true);

                echo get_post_meta( $product_id, 'plan_duration', true);

                echo  $order->get_id().'-'.$listing_id.'-';




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
                            'after' => '-30 days',
                            'inclusive' => true
                        )
                    ),
                    'post_status' => ['publish', 'pending', 'draft'],
                    'inclusive' => true,
                    'suppress_filters' => true,
                    'posts_per_page' => -1
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

                  
                        $message = __( 'New Listings have been added on your', 'idealbiz' ).' <b>'.__('Premium Buyer Account.','idealbiz').'</b>';
                        $emailHtml  = get_email_header($premium_buyer_user->ID, $email_title);
                        $emailHtml .= get_email_intro($premium_buyer_user->ID, $message);
                        $emailHtml .= get_email_listings($listings, 3);
                        $emailHtml .= get_email_footer();

                        $subject = get_bloginfo().' - '.__( 'You have', 'idealbiz' ).' '.$unreadPosts.' '.__('New Listings in your Premium Buyer Account.','idealbiz').'';
                        $headers = array('Content-Type: text/html; charset=UTF-8');
                        //echo $emailHtml;
                        wp_mail(
                            $user_info->user_email, 
                            $subject, 
                            $emailHtml,
                            $headers);
                
                    }
                }
              
            }
        }
    }
    die();  */
}







