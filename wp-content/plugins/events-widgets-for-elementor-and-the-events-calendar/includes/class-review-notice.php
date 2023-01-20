<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!class_exists('ReviewNotice')) {
    class ReviewNotice {
        /**
         * The Constructor
         */
        public function __construct() {
            // register actions
            if(is_admin()){

                add_action( 'admin_notices',array($this,'admin_notice_for_reviews'));
                add_action( 'wp_ajax_ectbe_dismiss_notice',array($this,'dismiss_review_notice' ) );
                add_action( 'admin_enqueue_scripts', array($this,'reviews_notice_external_script' ));
            }
        }

    public function reviews_notice_external_script(){
        wp_register_style( 'ectbe-review-css', ECTBE_URL . 'assets/css/ectbe-review.css', null, null, 'all' );
        wp_register_script( 'ectbe-review-js', ECTBE_URL . 'assets/js/ectbe-review.js', array( 'jquery' ), null, true );
    }

    public function dismiss_review_notice(){
        $wp_nonce = 'review_nonce_ectbe';
        if( check_ajax_referer( $wp_nonce,  '_nonce'  )){
            $rs = update_option( 'ectbe-ratingDiv','yes' );
            echo  json_encode( array("success"=>"true") );
            die();
        }
        else{
            die( 'nounce verification failed!' );
        }
    }
    
    public function admin_notice_for_reviews(){

        if( !current_user_can( 'update_plugins' ) ){
            return;
         }
         // get installation dates and rated settings
         $installation_date = get_option( 'ectbe-installDate' );
         $alreadyRated      = get_option( 'ectbe-ratingDiv' ) !=false ? get_option( 'ectbe-ratingDiv'):"no";

         // check user already rated 
        if( $alreadyRated=="yes") {
              return;
            } 

            // grab plugin installation date and compare it with current date
            $display_date = date( 'Y-m-d h:i:s' );
            $install_date= new DateTime( $installation_date );
            $current_date = new DateTime( $display_date );
            $difference = $install_date->diff($current_date);
            $diff_days= $difference->days;
          
            // check if installation days is greator then week
         if (isset($diff_days) && $diff_days>=3) {             
                wp_enqueue_style('ectbe-review-css');
                wp_enqueue_script('ectbe-review-js');
                echo wp_kses_post($this->create_notice_content());
            }
       }  

       // generated review notice HTML
       function create_notice_content(){
        $ajax_url = admin_url( 'admin-ajax.php' );
        $ajax_callback='ectbe_dismiss_notice';
        $wrap_cls="notice notice-info is-dismissible";
        $img_path=ECTBE_URL.'assets/images/events-widgets-elementor-logo.png';
        $p_name="Events Widgets For Elementor And The Events Calendar";
        $like_it_text='Rate Now! ★★★★★';
        $already_rated_text=esc_html__( 'I already rated it', 'ectbe' );
        $not_interested=esc_html__( 'Not Interested', 'ectbe' );
        $not_like_it_text=esc_html__( 'No, not good enough, i do not like to rate it!', 'ectbe' );
        $p_link=esc_url('https://wordpress.org/support/plugin/events-widgets-for-elementor-and-the-events-calendar/reviews/?filter=5#new-post');
        $review_nonce = wp_create_nonce('review_nonce_ectbe' ); 
        $output='';
        $message="Thanks for using <b>$p_name</b> WordPress plugin. We hope it meets your expectations!
         Please give us a quick rating, it works as a boost for us to keep working on more
          <a href='". esc_url('https://coolplugins.net') ."' target='_blank'>cool plugins</a>!<br/>";
      
        $html='<div data-ajax-url="%8$s"  data-ajax-callback="%9$s" data-wp-nonce="%11$s" class="ectbe-rating-notice-wrapper %1$s" style="display:table;max-width: 820px;">
        <div class="logo_container" style="display:table-cell"><a href="%5$s"><img src="%2$s" alt="%3$s"></a></div>
        <div class="message_container" style="display:table-cell"><p style="font-size:14px">%4$s</p>
        <div class="callto_action">
        <ul>
            <li class="love_it"><a href="%5$s" class="like_it_btn button button-primary" target="_new" title="%6$s">%6$s</a></li>
            <li class="already_rated"><a href="#" class="already_rated_btn button ectbe_dismiss_notice" title="Already Rated! Close This Box.">%7$s</a></li>
            <li class="already_rated"><a href="#" class="already_rated_btn button ectbe_dismiss_notice" title="Not Interested! Close This Box.">%10$s</a></li>
           
        </ul>
        <div class="clrfix"></div>
        </div>
        </div>
        </div>';
   
  $output=sprintf($html,
        $wrap_cls,
        $img_path,
        $p_name,
        $message,
        $p_link,
        $like_it_text,
        $already_rated_text,
        $ajax_url,// 8
        $ajax_callback,//9
        $not_interested,
        $review_nonce
        );
        return $output;
       }



  

    } //class end

} 



