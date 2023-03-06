<?php
/**
 * Theme Functions &
 * Functionality
 *
 */

require_once(ABSPATH . 'wp-admin/includes/screen.php');
require_once(ABSPATH . 'wp-content/plugins/idealbiz-service-request/lib/WooCommerce/EndpointServiceRequest.php');
require_once(ABSPATH . 'wp-content/plugins/idealbiz-service-request/lib/Gforms/HelperServiceSatisfaction.php');

add_action('after_setup_theme', 'wpdocs_theme_setup');
function wpdocs_theme_setup(){
    load_theme_textdomain('idealbiz', get_template_directory() . '/languages');
}


/* define default wp_content of multisite */
define ( 'DEFAULT_WP_CONTENT', get_site_url(1).'/wp-content' );



/* =========================================
		ACTION HOOKS & FILTERS
   ========================================= */

/**--- Actions ---**/

add_action( 'after_setup_theme',  'theme_setup' ); 

add_action( 'wp_enqueue_scripts', 'theme_styles' );

add_action( 'wp_enqueue_scripts', 'theme_scripts' ); 

// expose php variables to js. just uncomment line 
// below and see function theme_scripts_localize
// add_action( 'wp_enqueue_scripts', 'theme_scripts_localize', 20 );


/* =========================================
		HOOKED Functions | 
        Variaveis do Pay Per Contact Cleverson
   ========================================= */
define('WEBSITE_SYSTEM',get_field('website_sr_system', 'options')["value"]);
define('LISTING_SYSTEM',get_field('listing_system', 'options')["value"]);
define('ID_FORM_RECOM_BUSINESS',get_field('id_gform_recommended_business', 'option'));
define('OPPORTUNITY_SYSTEM',get_field('opportunity_system', 'option')["value"]);

/* get strign between two strings */
function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}


require_once(get_template_directory() . '/includes/strings.php'); 
require_once(get_template_directory() . '/woocommerce/woocommerce.php');
require_once(get_template_directory() . '/woocommerce/broker.php');
require_once(get_template_directory() . '/woocommerce/premium-buyer.php');
require_once(get_template_directory() . '/woocommerce/listing.php');
require_once(get_template_directory() . '/includes/email.php');
require_once(get_template_directory() . '/includes/countries.php'); 
require_once(get_template_directory() . '/includes/services-aux.php');



/**
 * Setup the theme
 * 
 * @since 1.0
 */
if ( ! function_exists( 'theme_setup' ) ) {
	function theme_setup() {

		// Let wp know we want to use html5 for content
		add_theme_support( 'html5', array(
			'comment-list',
			'comment-form',
			'search-form',
			'gallery',
			'caption'
		) );


		// Let wp know we want to use post thumbnails

		add_theme_support( 'post-thumbnails' );


		// Add Custom Logo Support.
		
		add_theme_support( 'custom-logo', array(
			'width'       => 1000, // Example Width Size
			'height'      => 1000,  // Example Height Size
			'flex-width'  => true,
		) );
		

		/**
         * Filter the except length to 20 words.
         *
         * @param int $length Excerpt length.
         * @return int (Maybe) modified excerpt length.
         */
        function custom_excerpt_length( $length ) {
            return 20;
        }
        add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

////////////////////////////////////////////////////////////////////
// Register Menus
////////////////////////////////////////////////////////////////////

		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );


		// Remove toolbar for all users in front end
		show_admin_bar( false );


		// WPML configuration
		// disable plugin from printing styles and js
		// we are going to handle all that ourselves.
		if ( ! is_admin() ) {
			define( 'ICL_DONT_LOAD_NAVIGATION_CSS', true );
			define( 'ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true );
			define( 'ICL_DONT_LOAD_LANGUAGES_JS', true );
		}

		// Register Autoloaders Loader
		$theme_dir = get_template_directory();
		include "$theme_dir/library/library-loader.php";
		include "$theme_dir/includes/includes-loader.php";
        include "$theme_dir/components/components-loader.php";
        
        $library_includes_components = array(
            'listings'                  => new IDB_Listings,
            'wanted'                    => new IDB_Wanted,
            'duplication'               => new IDB_Duplication,
            'duplication_site_form'     => new Component_Duplication_Form,
            'homepage_country_select'   => new IDB_Country_Select/*,
            'experts'                   => new IDB_Experts*/
        );
        /**
         * Remove/add library/includes/components.
         *
         * Note: if you add a library/include/component, make sure it implements a method "ready()".
         */
    
        foreach ( $library_includes_components as $instance ) {
            if ( method_exists( $instance, 'ready' ) ) {
                $instance->ready();
            }
        }

	}
}

/*
* Enquee CSS in admin area
*/
add_action('admin_head', 'css_admin_area');

function css_admin_area() {
  echo '<style>
            #wpadminbar #wp-admin-bar-languages .ab-item img,
            .pll-select-flag img,
            th.manage-column img,
            .pll-language-column img{
                width: 25px;
                height: auto;
            }
            .select2-container--default .select2-selection--single .select2-selection__clear {
                z-index: 9999999;
            }
        </style>';
}


function get_owner() {
    $owner= get_field('owner');
    return $owner;
}
add_shortcode('get_owner', 'get_owner');


function get_current_user_email() {
    return wp_get_current_user()->user_email;
}
add_shortcode('get_current_user_email', 'get_current_user_email');



/**
 * Register and/or Enqueue
 * Styles for the theme
 *
 * @since 1.0
 */
if ( ! function_exists( 'theme_styles' ) ) {
	function theme_styles() {

        $tp = get_page_template_slug();
        if(!$tp){
            global $template;
            $auxTp = explode('/',strrev($template));
            $tp = strrev($auxTp[0]);
        }


		$theme_dir = get_stylesheet_directory_uri();
		wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
		if($tp == 'homepage_v2.php'){
            wp_enqueue_style( 'main', "$theme_dir/assets/css/main.css", array(), null, 'all' );
            /* cl_alerta($tp); */
        }else{
            wp_enqueue_style( 'main', "$theme_dir/assets/css/main.css", array(), null, 'all' );
            
            /* cl_alerta($tp); */
        }


        
	}
}


/**
 * Register and/or Enqueue
 * Scripts for the theme
 *
 * @since 1.0
 */
if ( ! function_exists( 'theme_scripts' ) ) {
	function theme_scripts() {
        $theme_dir = get_stylesheet_directory_uri();
        


	wp_enqueue_script( 'main', "$theme_dir/assets/js/main.js", array(), null, true );
	//wp_enqueue_script( 'jquery','https://code.jquery.com/jquery-3.3.1.slim.min.js', array( 'jquery' ),'',true );
    wp_enqueue_script('jquery');
    wp_enqueue_script( 'jqueryui','https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js', array( 'jquery' ),'',true );
	wp_enqueue_script( 'popper','https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', array( 'jquery' ),'',true );
	wp_enqueue_script( 'bootstrap-js','https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array( 'jquery' ),'',true );
	}
}

/*
* Allow upload svg
*/
function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
   }
add_filter('upload_mimes', 'cc_mime_types');


/**
 * Attach variables we want
 * to expose to our JS
 *
 * @since 3.12.0
 */
if ( ! function_exists( 'theme_scripts_localize' ) ) {
	function theme_scripts_localize() {
		$ajax_url_params = array();

		// You can remove this block if you don't use WPML
		if ( function_exists( 'wpml_object_id' ) ) {
			/** @var $sitepress SitePress */
			global $sitepress;

			$current_lang = $sitepress->get_current_language();
			wp_localize_script( 'main', 'i18n', array(
				'lang' => $current_lang
			) );

			$ajax_url_params['lang'] = $current_lang;
		}

		wp_localize_script( 'main', 'urls', array(
			'home'  => home_url(),
			'theme' => get_stylesheet_directory_uri(),
			'ajax'  => add_query_arg( $ajax_url_params, admin_url( 'admin-ajax.php' ) )
		) );
	}
}

if (!function_exists('dd')) {
    function dd($data)
    {
        ini_set("highlight.comment", "#969896; font-style: italic");
        ini_set("highlight.default", "#FFFFFF");
        ini_set("highlight.html", "#D16568");
        ini_set("highlight.keyword", "#7FA3BC; font-weight: bold");
        ini_set("highlight.string", "#F2C47E");
        $output = highlight_string("<?php\n\n" . var_export($data, true), true);
        echo "<div style=\"background-color: #1C1E21; padding: 1rem\">{$output}</div>";
        die();
    }
}


function str_replace_first($from, $to, $content)
{
    $from = '/'.preg_quote($from, '/').'/';
    return preg_replace($from, $to, $content, 1);
}

/************************************* make white background until end of page */
$whitebg = false;
function whiteBackground(){
    echo '<div class="h-20px"></div>
    <hr class="clear"><div class="h-20px"></div>'; 
    return;
    $h= '300';
        if(get_field('background_with_edge_size'))
            $h=get_field('background_with_edge_size');

    if(is_singular('listing')) {
        $s = '0';
        $b = '#fbfbfc';
        $bbrr = '0';
    }

    else {

        if(get_field('skew'))
            $s=get_field('skew');
        else
            $s= '-22';
    
        if(get_field('background'))
            $b=get_field('background');
        else
            $b= '#fbfbfc';

        $bbrr = 32;
    }

  echo '
  <div class="h-80px"></div>
  <hr class="clear">
  <div class="whiteTriangle"></div>
    <style>
    .whiteTriangle::before{ top:-'.$h.'px; height:'.$h.'px;  }
    .whiteTriangle::after{ background:'.$b.'; 
        top:-'.$h.'px; 
        height:'.$h.'px;
        transform:skew('.$s.'deg);
        border-bottom-right-radius: '.$bbrr.'px };
    </style>
  <div class="whiteBackground">
  ';
  $whitebg = true;
}

/************************************* make info popups modals 
* @param int $length Excerpt length.
* @return int (Maybe) modified excerpt length.
*/
global $idModal;
$idModal=1;
function infoModal($html = NULL, $id  = NULL, $button_classes = NULL, $autoopen = NULL){
    global $idModal;
    if(!$id){
        $id = 'info-modal--'.$idModal;
        $idModal++;
    }
    if(!$html){
        $html='Info Message Missing!';
    }
    if(!$button_classes){
        $button_classes='';
    }
    if(!$autoopen){
        $autoopen='';
    }

    if($html == 'get_post_content'){
        $html = apply_filters('the_content', get_post_field('post_content', $id));
    }
      echo sprintf('<a href="#" data-izimodal-open="#%s" class="info-balloon info-modal %s">i</a>
                    <div id="%s" data-autoopen="%s"  class="infoModal iziModal">
                        <div class="content">
                            <button data-izimodal-close="" class="icon-close"></button>
                            <div class="clear"></div>
                            %s
                        </div>    
                    </div>',
                    $id, 
                    $button_classes,
                    $id,
                    $autoopen,
                    $html);


}

global $idModal;
$idModal=1;
function get_infoModal($html = NULL, $id  = NULL){
    global $idModal;
    if(!$id){
        $id = 'info-modal--'.$idModal;
        $idModal++;
    }
    if(!$html){
        $html='Info Message Missing!';
    }

    if($html == 'get_post_content'){
        $html = apply_filters('the_content', get_post_field('post_content', $id));
    }
      return sprintf('<a href="#" data-izimodal-open="#%s" class="info-balloon info-modal">i</a>
                    <div id="%s"  class="infoModal iziModal">
                        <div class="content p-x-20">
                            <button data-izimodal-close="" class="icon-close"></button>
                            <div class="clear"></div>
                            %s
                        </div>    
                    </div>',
                    $id, 
                    $id,
                    $html);
}



function info_modal_shortcode($atts, $content = "" ) {
    if ( wp_doing_ajax() ){
        return;
    }
    if ( is_admin() ){
        return;
    }
    global $idModal;
    if(!$id){
        $id = 'info-modal--'.$idModal;
        $idModal++;
    }
    $html= $content;
    if(!$html){
        $html='Info Message Missing!';
    }

      return sprintf('<a href="#" data-izimodal-open="#%s" class="info-balloon info-modal" style="font-weight: bold;line-height: 15px;top: 0px;margin-left: 5px;">i</a>
                    <span id="%s"  class="infoModal iziModal">
                        <span class="content" style="padding: 15px 25px 25px; top:0px;display: block;">
                            <button data-izimodal-close="" style="right: 0px;top: 0px;" class="icon-close"></button>
                            %s
                        </span>    
                    </span>',
                    $id, 
                    $id,
                    $html);
}
add_shortcode('i', 'info_modal_shortcode');





/************************************* image popups modals 
* @param int attachmente id.
* @return html
*/
global $modal_Id;
$modal_Id = 0;
function imageModal($id, $classes = NULL){
    global $modal_Id;
    $modal_Id++; 
    ?>
<a href="#" data-izimodal-open="#image-modal<?php echo $modal_Id ?>" class="modal-img <?php echo $classes; ?>">
    <?php
    $image_attributes = wp_get_attachment_image_src($id);
    if ( $image_attributes ) : ?>
    <img class="w-p-100 h-p-100" src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>"
        height="<?php echo $image_attributes[2]; ?>" />
    <?php endif; ?>
</a>
<div id="image-modal<?php echo $modal_Id ?>" class="infoModal iziModal">
    <div class="content">
        <button data-izimodal-close="" class="icon-close"></button>
        <div class="clear"></div>
        <img <?php echo $classes; ?> src="<?php echo $image_attributes[0]; ?>" class="w-p-100"
            style="max-width: 1200px;" />
    </div>
</div>
<?php  
}


add_filter('upload_mimes', 'custom_upload_mimes');
function custom_upload_mimes ( $existing_mimes=array() ) {
    // add your extension to the mimes array as below
    $existing_mimes['zip'] = 'application/zip';
    $existing_mimes['gz'] = 'application/x-gzip';
    return $existing_mimes;
}


function my_files_only( $wp_query ) {
    if(current_user_can('administrator')){
    }else{
        if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/upload.php' ) !== false ) {
            if ( !current_user_can( 'level_5' ) ) {
                global $current_user;
                $wp_query->set( 'author', $current_user->id );
            }
        }
    }
}
add_filter('parse_query', 'my_files_only' );



// Limit media library access
add_filter( 'ajax_query_attachments_args', 'wpb_show_current_user_attachments' );
function wpb_show_current_user_attachments( $query ) {
    $user_id = get_current_user_id();
    if ( $user_id && !current_user_can('activate_plugins') && !current_user_can('edit_others_posts
') ) {
        $query['author'] = $user_id;
    }
    return $query;
} 


$role_object = get_role( 'customer' );
    $role_object->add_cap('upload_files');
    $role_object->add_cap( 'edit_posts' );
    $role_object->add_cap( 'edit_pages' );  //$role_object->remove_cap( 'edit_pages' );
    $role_object->add_cap( 'delete_posts' );
    $role_object->add_cap( 'delete_published_posts' );
    $role_object->add_cap( 'delete_pages' );
    $role_object->add_cap( 'delete_published_pages' );

$contributor = get_role('subscriber');
    $contributor->add_cap('upload_files');
    $contributor->add_cap( 'edit_posts' );
    $contributor->add_cap( 'edit_pages' );
    $contributor->add_cap( 'delete_posts' );
    $contributor->add_cap( 'delete_published_posts' );
    $contributor->add_cap( 'delete_pages' );
    $contributor->add_cap( 'delete_published_pages' );

    if( current_user_can('editor') || current_user_can('administrator') ) {

    }else{    
        add_action( 'trashed_post', 'wpse132196_redirect_after_trashing', 10 );
        function wpse132196_redirect_after_trashing() {
            wp_redirect( home_url() );
            exit;
        }    
    }
$u = new WP_User(get_current_user_id());
$u->add_role( 'customer' );
    


/**
 * Get Social Networks in WP-Admin options Page
 *
 * @since  1.0.0
 * @return array social networks
 */
function get_social_networks() {
    $networks = array(
        'facebook' => array(
            'url'   => '',
            'title' => esc_html_x( 'Facebook', 'idealbiz' ),
        ),
        'googleplus' => array(
            'url'   => '',
            'title' => esc_html_x( 'Google+', 'idealbiz' ),
        ),
        'instagram' => array(
            'url'   => '',
            'title' => esc_html_x( 'Instagram', 'idealbiz' ),
        ),
        'linkedin' => array(
            'url'   => '',
            'title' => esc_html_x( 'LinkedIn', 'idealbiz' ),
        ),
        'twitter' => array(
            'url'   => '',
            'title' => esc_html_x( 'Twitter', 'idealbiz' ),
        ),
        'youtube' => array(
            'url'   => '',
            'title' => esc_html_x( 'Youtube', 'idealbiz' ),
        ),
    );
    foreach ( $networks as $network => $url ) {
        $url = get_field($network, 'option');
        if ( empty( $url ) ) {
            unset( $networks[ $network ] );
            continue;
        }
        $networks[ $network ]['url'] = $url;
    }
    return $networks;
}



add_filter( 'woocommerce_currencies', 'add_ibz_currency' );
function add_ibz_currency( $cw_currency ) {
     $cw_currency['iBz'] = __( 'iDealBiz Currency', 'woocommerce' );
     return $cw_currency;
}
add_filter('woocommerce_currency_symbol', 'add_ibz_currency_symbol', 10, 2);
function add_ibz_currency_symbol( $custom_currency_symbol, $custom_currency ) {
     switch( $custom_currency ) {
         case 'iBz': $custom_currency_symbol = 'iBz'; break;
     }
     return $custom_currency_symbol;
}


add_action('admin_menu', 'wpdocs_register_my_custom_submenu_page');
function wpdocs_register_my_custom_submenu_page() {
    add_submenu_page(
        'options-general.php',
        'iDealBiz Coin Management',
        'iDealBiz Coin',
        'manage_options',
        'ibzcoinmanagement',
        'coinmanagement_page_callback' );
	//call register settings function
	add_action( 'admin_init', 'register_my_cool_plugin_settings' );
}


function register_my_cool_plugin_settings() {
	//register our settings
	register_setting( 'my-cool-plugin-settings-group', 'new_option_name' );
	register_setting( 'my-cool-plugin-settings-group', 'some_other_option' );
	register_setting( 'my-cool-plugin-settings-group', 'option_etc' );
}

function coinmanagement_page_callback() {
?>
<div class="wrap">
    <h1>Your Plugin Name</h1>
    <form method="post" action="options.php">
        <?php settings_fields( 'my-cool-plugin-settings-group' ); ?>
        <?php do_settings_sections( 'my-cool-plugin-settings-group' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">New Option Name</th>
                <td><input type="text" name="new_option_name"
                        value="<?php echo esc_attr( get_option('new_option_name') ); ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row">Some Other Option</th>
                <td><input type="text" name="some_other_option"
                        value="<?php echo esc_attr( get_option('some_other_option') ); ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row">Options, Etc.</th>
                <td><input type="text" name="option_etc" value="<?php echo esc_attr( get_option('option_etc') ); ?>" />
                </td>
            </tr>
        </table>

        <?php submit_button(); ?>

    </form>
</div>
<?php }



function myplugin_register_settings() {
    $currs = get_woocommerce_currencies();
    foreach ($currs as $c => $coin){
        add_option( $c.'_coin', 1);
    }

    add_option( 'myplugin_option_name', 'This is my option value.');
    register_setting( 'myplugin_options_group', 'myplugin_option_name', 'myplugin_callback' );
 }
 add_action( 'admin_init', 'myplugin_register_settings' );



/* get attachments id by name */
function get_attachment_id_by_slug( $slug) {
    $args = array(
        'post_type' => 'attachment',
        'name' => sanitize_title($slug),
        'posts_per_page' => 1,
        'post_status' => 'inherit',
    );
    $_header = get_posts( $args );
    $header = $_header ? array_pop($_header) : null;
    return $header ? $header->ID : '';
}

/* import featured image */
function image_featured_id( $imageurl = null) {
    $aux = strrev(get_string_between(strrev($imageurl),'.','/'));
    $current = get_current_blog_id();
    $aux2= get_attachment_id_by_slug($aux,1);
    return $aux2;
}

/* import images in gallery */
function serialize_images_id_to_gallery( $gallery_items = null) {
    $r=''; 
    $aux='';
	if ( !empty( $gallery_items ) ) {
        $imgs_array=explode('|',$gallery_items);
        foreach($imgs_array as $k => $url){
            /* get name as slug */ 
            $aux = strrev(get_string_between(strrev($url),'.','/'));
            /* get id */
            //echo $aux;
            $img_id = get_attachment_id_by_slug($aux);
            /* construct json */
            $r.= 'i:'.$k.';s:'.strlen((string)$img_id).':'.'"'.$img_id.'";';
            // i:0;s:4:"3149"; https://idealbiz.pt/content/uploads/2018/01/bl-5.jpg|https://idealbiz.pt/content
        }
    }
    $a= sprintf('a:%d:{%s}',count($imgs_array),$r);
    //echo $a.'<br/>';
    //a:2:{i:0;s:4:"3183";i:1;s:4:"3184";}
    return $a;
}

/* this code emulated the savig acf fields in browser */
if(isset($_GET['save_all_posts_54912168'])){
    add_action( 'admin_footer', 'insert_footer_wpse_51023' );
    function insert_footer_wpse_51023()
    {
        ?>
<script>
var x = 0;

function myFunction() {
    jQuery(document).ready(function() {
        jQuery(".wpseo-score-readability .wpseo-score-icon").each(function(evt) {
            if (jQuery(this).hasClass('na')) {
                var elem = jQuery(this).closest('tr').find('.row-title')
                var link = elem.attr('href') + '&save_post_by_js_54912168';
                setTimeout(function() {
                    var win = window.open(link, '_blank');
                    if (win) {
                        win.focus();
                    }
                }, 22000 * x);
                x++;
            }
        });
    });
}
myFunction();
</script>
<?php
    }
}
if(isset($_GET['save_post_by_js_54912168'])){
    add_action( 'admin_footer', 'insert_footer_wpse_51024' );

    function insert_footer_wpse_51024()
    {
        $sss = get_post_thumbnail_id($_GET['post']);
        ?>
<script>
function closeWin() {
    window.close();
}
jQuery(document).ready(function() {
    jQuery('div.acf-field.acf-field-image.acf-field-5e5d3048cb56e > div.acf-input > div > input[type=hidden]')
        .val(<?php echo $sss; ?>);

    setTimeout(function() {
        jQuery(
                '#editor > div > div > div > div.edit-post-header > div.edit-post-header__settings > div:nth-child(3) > button'
            )
            .click();
    }, 3000);

    setTimeout(function() {
        closeWin()
    }, 20000);
});
</script>
<?php
    }
}


add_action( 'admin_footer', 'insert_footer_css' );

function insert_footer_css(){ ?>

    <style>
    #multibanco_ifthen_callback_notice {
        display: none;
    }

    .stripe-ssl-message {
        display: none;
    }
    </style>
    <?php
    /* WEBSITE_SYSTEM */
    if(WEBSITE_SYSTEM == '0'){ ?> 
        <style>
            div[data-name="competency_factor"],
            div[data-name="idb_tax"],
            div[data-name="idb_competency_factor_percentage"],
            div[data-name="idb_competency_factor_earnings"],
            div[data-name="reference_value"],
            div[data-name="budget_min"], 
            div[data-name="budget_max"]
            { display: none !important; }
        </style> 
    <?php }
}

function expert_email() {
    $html='<input type="hidden" name="toexpert" value="'.get_field('expert_email',get_the_ID()).'"/>';
    return $html; 
}
add_shortcode('expert_email', 'expert_email');


function councelingemail() {
    $to_email = get_field('to_email',get_the_ID()) ? get_field('to_email',get_the_ID()) : 'serv.suporte@idealbiz.pt';
    $html='<input type="hidden" name="counceling-email" value="'.$to_email.'"/>';
    return $html; 
}
add_shortcode('councelingemail', 'councelingemail');

function councelingtitle() {
    $html='<input type="hidden" name="counceling-title" value="'.get_the_title().'"/>';
    return $html; 
}
add_shortcode('councelingtitle', 'councelingtitle');

function fromemail() {
    $current_user = wp_get_current_user();
    $current_user->user_email;
    $html='<input type="hidden" name="fromemail" value="'.$current_user->user_email.'"/>';
    return $html; 
}
add_shortcode('fromemail', 'fromemail');

function franchisedetails() {
    $html='<input type="hidden" name="franchisedetails" value="'.get_the_title().'"/>';
    return $html; 
}
add_shortcode('franchisedetails', 'franchisedetails');

function customer_care_email() {
    $html='<input type="hidden" name="customer_care_email" value="'.get_field('costumer_care_email', 'options').'"/>';
    return $html; 
}
add_shortcode('customer_care_email', 'customer_care_email');

function partner_email() {
    $html='<input type="hidden" name="partner_email" value="'.get_field('email').'"/>';
    return $html; 
}
add_shortcode('partner_email', 'partner_email');

function site_lang() {
    $html='<input type="hidden" name="lang" value="'.get_user_locale().'"/>';
    return $html; 
}
add_shortcode('site_lang', 'site_lang');

add_filter( 'wpcf7_form_elements', 'mycustom_wpcf7_form_elements' );
function mycustom_wpcf7_form_elements( $form ) {
    $form = do_shortcode( $form );
    return $form;
}


function getDefExpertId(){
    $post_list = get_posts(array(
        'numberposts'	=> -1,
        'post_type'		=> 'expert',
        'meta_key'		=> 'idealbiz_support_expert',
        'meta_value'	=> '1'
    ));
    $def_expert= '';
    foreach ( $post_list as $post ) {
        return $post->ID;
    }
    return 0;
}


function listinge1() {
    $h='<select name="owner" style="margin-bottom:10px;">';

    $expert_id=get_field('expert',get_the_ID())->ID;
    if($expert_id){
        $sec_expert= get_field('expert_email',$expert_id);
        $h.='<option value="'.$sec_expert.'">'.get_the_title($expert_id).'</option>';
    }

    $post_list = get_posts(array(
        'numberposts'	=> -1,
        'post_type'		=> 'expert',
        'meta_key'		=> 'idealbiz_support_expert',
        'meta_value'	=> '1'
    ));
    $def_expert= '';
    foreach ( $post_list as $post ) {
        $def_expert= get_field('expert_email',$post->ID);
        $def_expert_name=get_the_title($post->ID);
    }
    if($sec_expert != $def_expert){
        $h.='<option value="'.$def_expert.'">'.$def_expert_name.'</option>';
    }
    
    $h.='</select><br/>';


    return $h; 
}
add_shortcode('listinge1', 'listinge1');




function cc_wpse_278096_disable_admin_bar() {
   if (current_user_can('administrator') || current_user_can('editor') || is_super_admin() ) {
     // user can view admin bar
     show_admin_bar(true); // this line isn't essentially needed by default...
   } else {
     // hide admin bar
     show_admin_bar(false);
   }
}
add_action('after_setup_theme', 'cc_wpse_278096_disable_admin_bar');


/* show plugin analitics settings */
if(is_super_admin()){
    $user = get_userdata(get_current_user_id());
    $user->add_role('administrator');
}

/* show plugin analitics settings */
if(!is_super_admin()){
    if(isset($_GET['page']))
    if($_GET['page'] == 'beehive-settings'){
        die();
    }
    function frontfooter() {
        echo '<style>
                #toplevel_page_beehive { display:none; }

                #threewp_broadcast .taxonomies.html_section,
                #threewp_broadcast .custom_fields.html_section,
                #threewp_broadcast .link.html_section
                {   
                    display:none;
                }
             </style>';
    }
    add_action('admin_footer', 'frontfooter');
} 
/* renaming Menu items */
function rename_AdminMenus() {
    global $menu;
    foreach($menu as $key => $item) {
      if ( $item[0] === 'Beehive Pro' ) {
          $menu[$key][0] = __('Analytics','textdomain');     //change name
      }
    }
   return false;
}
add_action( 'admin_menu', 'rename_AdminMenus', 999 );



add_action('acf/save_post', 'my_acf_save_post');
function my_acf_save_post( $post_id ) {
    // Get newly saved values.
    $values = get_fields( $post_id );

    if($values["publish_in"]){
        pll_set_post_language($post_id, $values["publish_in"]->slug);
    }
}




add_action('acf/save_post', 'idealbiz_new_expert_send_email');
function idealbiz_new_expert_send_email( $post_id ) {

    if(get_field('notification_email_sent',$post_id)=='1'){
        return;
    }

	if( get_post_type($post_id) !== 'expert' && get_post_status($post_id) !== 'draft' ) {
		return;
	}

	if( is_admin() ) {
        return;
    }


    
    if( get_post_type($post_id) !== 'listing'){

   
        update_field( 'notification_email_sent', 1, $post_id );

        // to expert
        $subject = pll__('Confirmation of application for Specialist');
        $hi = $subject;
        $to = get_field('expert_email',$post_id);
        $headers = array('Content-Type: text/html; charset=UTF-8');
    
        $message = pll__('Hi {{expert}}, We have received your Specialist application form. We appreciate your trust in our platform');
        $message = str_replace('{{expert}}',get_field('expert_email',$post_id),$message);
    
        $emailHtml  = get_email_header();
        $emailHtml .= get_email_intro('', $message, $hi);
        $emailHtml .= get_email_footer();
    
         wp_mail($to, 
            $subject, 
            $emailHtml, 
            $headers);


        //to customer care
        if(  get_post_type($post_id) !== 'wanted'){
            $subject = pll__('New application for Specialist'); 
            $hi = $subject;
            $to = get_field('costumer_care_email', 'option');
        
            $message = pll__('Hello Support, New Specialist registration on our iDealBiz.pt platform. Username: {{expert}} E-mail: {{email}} You can access {{link}} to view the registered application. ');
            $message = str_replace('{{email}}',get_field('expert_email',$post_id),$message);
            $message = str_replace('{{expert}}',get_the_title($post_id),$message);
        
            $url = admin_url( 'post.php?post='.$post_id.'&action=edit', 'https' );
            $message = str_replace('{{link}}',$url,$message);
            $headers = array('Content-Type: text/html; charset=UTF-8');
        
            
            $emailHtml  = get_email_header();
            $emailHtml .= get_email_intro('', $message, $hi);
            $emailHtml .= get_email_footer();

            wp_mail($to, 
                $subject, 
                $emailHtml, 
                $headers);
        }
    }    

}

function genericform_shortcode($atts = [], $content = null)
{
    $content = '<div class="generic-form"><div class="acf-form">'.do_shortcode($content).'</div></div>';
    return $content;
}
add_shortcode('genericform', 'genericform_shortcode');

function cf($atts = [], $content = null)
{
    $content = '<div class="popWrapper" id="contact_form_id" style="z-index: 999999999 !important;">
        <div class="popWrapper_screen" style="z-index: 999999999 !important;"></div>
        <div class="iziModal formPopUp col-md-12 col-sm-12 col-xs-12" style="overflow-y: scroll;max-height: 80vh;">
            <div class="iziModal-wrap" style="height: auto;">
                <div class="iziModal-content" style="padding: 0px;">
                    <div class="content generic-form p-b-20"> 
                        <button data-izimodal-close="" class="icon-close popUpForm" href="#contact_form_id"></button>
                        <div class="clear"></div>
                        <div class="acf-form">
                        '. do_shortcode($content).'
                        </div>
                    </div>    
                </div>
            </div>    
        </div>
    </div>';
    // always return
    return $content;
}
add_shortcode('cf', 'cf');
add_shortcode('modal', 'cf');




function makeSRLeadModal($srid,$viewLead = null)
{
    /* if (get_field('is_referral',$srid)){
        $srid = get_field('sr_original',$srid);
    } */

    if ($viewLead != null){

        cl_alerta('Teste de Chamada de função makeSRLeadModal');

    }

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
    $idealbiz_logo   = get_option( 'woocommerce_email_header_image' );

    
    $m1 = '<div style="text-align:center;"><img src="'.$idealbiz_logo.'" alt="Logo" width="200" height="100"></div><br/><br/>'.pll__('Hi {{expert}}, Here are the contacts of your Service Request.').'<br/>';
    $m1 = str_replace('{{expert}}',$expert_name, $m1);
    $m1 .= '<b>'.pll__('Customer:').'</b> '.get_field('customer',$srid)->first_name.' '.get_field('customer',$srid)->last_name;

    $m2 = '<div style="text-align:center;"><img src="'.$idealbiz_logo.'" alt="Logo" width="200" height="100"></div><br/><br/>'.pll__('Hi {{customer}}, Here are details of your Service Request.').'<br/>';
    $m2 = str_replace('{{customer}}',get_field('customer',$srid)->first_name.' '.get_field('customer',$srid)->last_name,$m2);
    $m2.= '<b>'.pll__('Expert:').'</b> '.get_the_title($expert_id);
    $m2.='<br/>';
    $m2.= '<b>'.pll__('Expert email:').'</b> '.get_field('consultant',$srid)->user_email;
    $m2.='<br/><br/>';
    $m2.= '<b>'.pll__('Your Details:').'</b>' ;
    $m2.= ' </b>'.get_field('customer',$srid)->first_name.' '.get_field('customer',$srid)->last_name;

    $m='<br/>';
    $m.= '<b>'.pll__('Email:').'</b> '.get_field('customer',$srid)->user_email;
    $m.='<br/>';
    $m.= '<b>'.pll__('Phone:').'</b> '.get_field('service_request_phone',$srid);
    $m.='<br/>';
    $m.= '<b>'.pll__('Delivery Date:').'</b> '.get_field('delivery_date',$srid);
    $m.='<br/>';
    $m.= '<b>'.pll__('Reference Value:').'</b> '.get_field('reference_value',$srid).__('Money Simbol');
    $m.='<br/>';
    /* $m.= pll__('Budget Min:').' '.get_field('budget_min',$srid).' '.pll__('Budget Max:').' '.get_field('budget_max',$srid);
    $m.='<br/>'; */
    $m.= '<b>'.pll__('Message:').'</b><br />'.get_field('message',$srid);
    $m.='<br/><br/>';
    $m.= pll__('Thank you.');


    $message=$m2.$m;
    $current_user= wp_get_current_user();
    if(get_field('consultant',$srid)->ID == $current_user->ID){
        $message=$m1.$m;
    }

    $content = '<div class="popWrapper" id="post-'.$srid.'" style="z-index: 999999999 !important;">
        <div class="popWrapper_screen" style="z-index: 999999999 !important;"></div>
        <div class="iziModal formPopUp col-md-12 col-sm-12 col-xs-12" style="overflow-y: scroll;max-height: 80vh;">
            <div class="iziModal-wrap" style="height: auto;">
                <div class="iziModal-content" style="padding: 0px;">
                    <div class="content generic-form p-b-20"> 
                        <button data-izimodal-close="" class="icon-close popUpForm" href="#post-'.$srid.'"></button>
                        <div class="clear"></div>
                        <div class="acf-form" style="text-align:left; color: #14307b; font-size:1.2em; line-height: 1.8em; font-weight: 500;">
                        '.$message.'
                        </div>
                    </div>    
                </div>
            </div>    
        </div>
    </div>';
    // always return
    return $content;
}

function makeSRLeadModalRecommended($srid)
{
    /* if (get_field('is_referral',$srid)){
        $srid = get_field('sr_original',$srid);
    } */

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
    $idealbiz_logo   = get_option( 'woocommerce_email_header_image' );

    

    $m2 = '<div style="text-align:center;"><img src="'.$idealbiz_logo.'" alt="Logo" width="200" height="100%"></div><br/><br/>'.'<b>'.pll__('Contact Details').'</b>' ;
    $m2.= ' </b>'.get_field('customer',$srid)->first_name.' '.get_field('customer',$srid)->last_name;

    $m='<br/>';
    $m.= '<b>'.pll__('Name:').'</b> '.get_field('rb_recommended_name',$srid);
    $m.='<br/>';
    $m.= '<b>'.pll__('Email:').'</b> '.get_field('rb_recommended_email',$srid);
    $m.='<br/>';
    $m.= '<b>'.pll__('Phone:').'</b> '.get_field('rb_recommended_phone',$srid);
    $m.='<br/>';
    $m.= '<b>'.pll__('Message:').'</b><br />'.get_field('rb_recommended_information',$srid);



    $message=$m2.$m;
    $current_user= wp_get_current_user();
    if(get_field('consultant',$srid)->ID == $current_user->ID){
        $message=$m1.$m;
    }

    $content = '<div class="popWrapper" id="post-'.$srid.'" style="z-index: 999999999 !important;">
        <div class="popWrapper_screen" style="z-index: 999999999 !important;"></div>
        <div class="iziModal formPopUp col-md-12 col-sm-12 col-xs-12" style="overflow-y: scroll;max-height: 80vh;">
            <div class="iziModal-wrap" style="height: auto;">
                <div class="iziModal-content" style="padding: 0px;">
                    <div class="content generic-form p-b-20"> 
                        <button data-izimodal-close="" class="icon-close popUpForm" href="#post-'.$srid.'"></button>
                        <div class="clear"></div>
                        <div class="acf-form" style="text-align:left; color: #14307b; font-size:1.2em; line-height: 1.8em; font-weight: 500;">
                        '.$message.'
                        </div>
                    </div>    
                </div>
            </div>    
        </div>
    </div>';
    // always return
    return $content;
}



add_role(
    'consultant',
    __( 'iDealBiz Consultant','idealbiz' ),
    array(
    'read'         => true,  // true allows this capability
    )
);
 
add_action( 'save_post_expert', 'my_save_post_function', 10, 3 );
function my_save_post_function( $post_ID, $post, $update ) {
    //$author_id=$post->post_author;
    $expert_user =  get_user_by( 'email', get_field('expert_email',$post_ID));
    if($expert_user){
        if ( get_post_status ( $post_ID ) == 'publish' ) {
            $user = get_userdata($expert_user->ID);
            $user->add_role('consultant');
        } else {
            $user = get_userdata($expert_user->ID);
            $user->remove_role( 'consultant' );
        }
    }
}

if(isset($_GET['fepaction'])){
    if($_GET['fepaction'] == 'newmessage'){
        die();
    }
}



add_filter( 'woocommerce_default_address_fields', 'customise_postcode_fields' );
function customise_postcode_fields( $address_fields ) {
    $address_fields['postcode']['required'] = false;
    return $address_fields;
}

add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields', 99 );
function custom_override_checkout_fields( $fields ) {
    unset($fields['billing']['billing_postcode']['validate']);
    unset($fields['shipping']['shipping_postcode']['validate']);
    return $fields;
}

if ( ! function_exists( 'function_post_counter' ) ) :    
    function function_post_counter($postID) {   
        $count_key = 'wpb_post_views_count';    
        $count = get_post_meta($postID, $count_key, true);    
    if($count ==''){        
        $count = 1;        
        delete_post_meta($postID, $count_key);        
        add_post_meta($postID, $count_key, '1');    
    } else {        
        $count++;        
        update_post_meta($postID, $count_key, $count);    
    }
}
endif; 

function my_acf_update_value( $value, $post_id, $field  ) {
    // only do it to certain custom fields


    if( $field['name'] == 'responsible' ) {
        
        // get the old (saved) value
        $old_value = get_field('responsible', $post_id);
        
        // get the new (posted) value
        $new_value = $_POST['acf']['field_5eeb5412460b1'];
        //$post_author_id = get_post_field( 'post_author', $new_value );

        $resp = get_user_by( 'id', $new_value );

        // check if the old value is the same as the new value
        if( $old_value != $new_value ) {

            global $wpdb;
            $table = FEP_MESSAGE_TABLE;
            $data =   array('mgs_author' => $resp->ID, 
                            'mgs_parent' => 0,
                            'mgs_created' => date("Y-m-d h:i:s"),
                            'mgs_title' => __('Listing #','idealbiz').$post_id. ' // '.get_the_title($post_id),
                            'mgs_type' => 'message',
                            'mgs_content' => ''.$resp->display_name.' '.__('is assigned now to yor listing. You can send messages to him/her now.','idealbiz'),
                            'mgs_status' => 'publish', 
                            'mgs_last_reply_by' => $resp->ID,
                            'mgs_last_reply_time' => date("Y-m-d h:i:s"),
                            'mgs_last_reply_excerpt' => ''.$resp->display_name.' '.__('is assigned now to yor listing','idealbiz')
                    );


            $wpdb->insert($table,$data);
            $my_id = $wpdb->insert_id;

            $table = FEP_PARTICIPANT_TABLE;
            $data =   array('mgs_id' => $my_id, 
                            'mgs_participant' => $resp->ID,
                            'mgs_read' => 0,
                            'mgs_parent_read' => 0,
                            'mgs_deleted' => 0,
                            'mgs_archived' => 0
                    );
            $wpdb->insert($table,$data);
            $wpdb->insert_id;

            $owner = get_field('owner', $post_id);
            $table = FEP_PARTICIPANT_TABLE;
            $data2 =   array('mgs_id' => $my_id, 
                            'mgs_participant' => $owner['ID'],
                            'mgs_read' => 0,
                            'mgs_parent_read' => 0,
                            'mgs_deleted' => 0,
                            'mgs_archived' => 0
                    );
            $wpdb->insert($table,$data2);
            $wpdb->insert_id;

        } else {
            // Do something if they are the same
        }
    }
	// don't forget to return to be saved in the database
    return $value;
    
} add_filter('acf/update_value', 'my_acf_update_value', 10, 3);



/* get search options post types for global search in site */
function getSearchTerms(){

    $homeId= getIdByTemplate('homepage-country.php');
    $business_options = get_field('business_options', $homeId);
    $si = array();
    foreach ($business_options as $option) {
        if ($option['button_link'] == '') { } else {
            $page_template= get_page_template_slug(url_to_postid($option['button_link']));
            if($page_template == 'page-services.php'){
                $si['counseling'] = __('Services','idealbiz');
            }
            if($page_template == 'page-sell.php'){
                $si['listing'] = __('Listings','idealbiz');
            }
            if($page_template == 'page-experts.php'){
                $si['expert'] = __('Experts','idealbiz');
            }
            if($page_template == 'page-experts.php'){
                $si['expert'] = __('Experts','idealbiz');
            }
            if($page_template == 'page-franchises.php'){
                $si['franchise'] = __('Franchises','idealbiz');
            }
            if($page_template == 'page-jobs-resumes.php'){
                $si['job_listing'] = __('Jobs','idealbiz');
                $si['resume'] = __('Resumes','idealbiz');
            }
        }
    }
    $si['post'] = __('News','idealbiz');
    return $si;
}

function getBreadcrumbs($post_type, $post_id = '', $post_title = '', $post_link = ''){
    $h='';

    $h.='<ul class="breadcrumbs hidden-mobile">';
        $h.='<li><a href="'.get_home_url().'" title="'.get_bloginfo().'">'.get_bloginfo().'</a></li>';

        if($post_type=='listing'){
            $h.='<li><a href="'.getLinkByTemplate('page-listings.php').'" title="'.__('Listings','idealbiz').'">'.__('Listings','idealbiz').'</a></li>';
            $h.='<li><a href="'.$post_link.'" title="'.$post_title.'">'. $post_title.'</a></li>';
        }
        if($post_type=='expert'){
            $h.='<li><a href="'.getLinkByTemplate('page-experts.php').'" title="'.__('Experts','idealbiz').'">'.__('Experts','idealbiz').'</a></li>';
            $h.='<li><a class="" href="'.$post_link.'" title="'.$post_title.'">'. $post_title.'</a></li>';
        }
        if($post_type=='counseling'){
            $h.='<li><a href="'.getLinkByTemplate('page-services.php').'" title="'.__('Services','idealbiz').'">'.__('Services','idealbiz').'</a></li>';
            $h.='<li><a class="" href="'.$post_link.'" title="'.$post_title.'">'. $post_title.'</a></li>';
        }
        if($post_type=='franchise'){
            $h.='<li><a href="'.getLinkByTemplate('page-franchises.php').'" title="'.__('Franchises','idealbiz').'">'.__('Franchises','idealbiz').'</a></li>';
            $h.='<li><a href="'.$post_link.'" title="'.$post_title.'">'. $post_title.'</a></li>';
        }
        if($post_type=='post'){
            $h.='<li><a href="'.get_permalink( get_option( 'page_for_posts' ) ).'" title="'.__('News & Advices','idealbiz').'">'.__('News & Advices','idealbiz').'</a></li>';
            $h.='<li><a href="'.$post_link.'" title="'.$post_title.'">'. $post_title.'</a></li>';
        }
        if($post_type=='job_listing'){
            $h.='<li><a href="'.get_permalink(get_option('job_manager_jobs_page_id')).'" title="'.__('Jobs','idealbiz').'">'.__('Jobs','idealbiz').'</a></li>';
            $h.='<li><a href="'.$post_link.'" title="'.$post_title.'">'. $post_title.'</a></li>';
        }
        if($post_type=='resume'){
            $h.='<li><a href="'.get_permalink(get_option('resume_manager_resumes_page_id')).'" title="'.__('Resumes','idealbiz').'">'.__('Resumes','idealbiz').'</a></li>';
            $h.='<li><a href="'.$post_link.'" title="'.$post_title.'">'. $post_title.'</a></li>';
        }
    $h.='</ul>';
    return $h;
}

function getFeaturedSearchImage($post_id, $post_type='', $title){
    $h= '';
    if( $post_type=='listing' ){
        $h= '<img src="'.get_field( "featured_image", $post_id )['sizes']['thumbnail'].'" class="img-cover w-100 h-100" title="'.$title.'" />';

    }elseif( $post_type == 'expert' ){   
        $h= '<img src="'.get_field( "foto", $post_id )['sizes']['thumbnail'].'" class="img-cover w-100 h-100" title="'.$title.'" />';

    }elseif( $post_type == 'franchise' ){   
        $h= '<img src="'.get_the_post_thumbnail_url($post_id,'full').'" class="img-contain w-100 h-100" title="'.$title.'" />';
        
    }elseif( $post_type == 'post' ){   
        $h= '<img src="'.get_the_post_thumbnail_url($post_id,'full').'" class="img-cover w-100 h-100" title="'.$title.'" />';
        
    }elseif( $post_type == 'counseling' ){   
         $ico = get_field( "icon", $post_id );
         $col = get_field( "icon_color", $post_id );
         if($ico){
            $h= '<div class="w-100 h-100 icon-cs" style=" background-color: '.$col.'">
                     <i class="white--color '.$ico.'"></i>
                  </div>';
         }else{
             $img = get_field( "foto", $post_id )['sizes']['thumbnail'];
             $tdes = get_field( "title_desktop", $post_id );
             $h= '<img src="'. $img.'" class="img-cover w-100 h-100" alt="'.$tdes.'" title="'.$tdes.'" />';
         }
    }elseif( $post_type == 'job_listing' ){       
        $job_bm_company_logo = get_the_post_thumbnail_url($post_id,'medium');
        if(!empty($job_bm_company_logo)){
            $h= '<img src="'.$job_bm_company_logo.'" class="img-contain w-100 h-100" title="'.$title.'" />';
        }else{
            $h= '<img src="'.get_site_url().'/wp-content/themes/idealbiz/assets/img/default.jpg'.'" class="img-cover w-100 h-100" title="'.$title.'" />';
        }
    }elseif( $post_type == 'resume' ){       
        $resume_img = get_field( "_candidate_photo", $post_id );
        if(!empty($resume_img)){
            $h= '<img src="'.$resume_img.'" class="img-cover w-100 h-100" title="'.$title.'" />';
        }else{
            $h= '<img src="'.get_site_url().'/wp-content/themes/idealbiz/assets/img/default.jpg'.'" class="img-cover w-100 h-100" title="'.$title.'" />';
        }
    }
    else{
        $h= '<img src="'.get_site_url().'/wp-content/themes/idealbiz/assets/img/default.jpg'.'" class="img-cover w-100 h-100" title="'.$title.'" />';
    }
    return $h;
}

function change_url_parameter($url,$parameter,$parameterValue)
{
    $url=parse_url($url);
    parse_str($url["query"],$parameters);
    unset($parameters[$parameter]);
    $parameters[$parameter]=$parameterValue;
    return  $url["path"]."?".http_build_query($parameters);
}

// Register Custom pagination
function pagination($cpage, $pages = '', $range = 4, $k)
{
    
    if ($k=='expert'){
        $K='expert';
        $cl_ancora = '#anchor_member';
    }

    if ($k=='expert2'){
        $K='expert';
        $cl_ancora = '#anchor_member2';
    }
    
 

    $h='';
    $showitems = ($range * 2)+1;
    global $page;
    $paged = $cpage;

    if($pages == '')
    {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if(!$pages)
        {
            $pages = 1;
        }
    }
    if(1 != $pages)
    {
        $h.="<div class='d-flex justify-content-center listing-page'>";
        $h.="<nav class='listings'>
                <ul class='pagination m-b-0'>";
        if($paged > 2 && $paged > $range+1 && $showitems < $pages) 
            $h.= "<li class='page-item '><a class=' no-ajax page-numbers' href='".change_url_parameter(get_pagenum_link(1),'type',$k).$cl_ancora ."'>".__('First','idealbiz')."</a></li>";

        if($paged > 1 && $showitems < $pages) 
            $h.= "<li class='page-item'><a class='prev no-ajax page-numbers' href='".change_url_parameter(get_pagenum_link($paged-1),'type',$k).$cl_ancora ."'> </a></li>";
            for ($i=1; $i <= $pages; $i++)
            {
                if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
                {
                    $h.= ($paged == $i)? "<li class=\"page-item\"><a class='current no-ajax page-numbers'>".$i."</a></li>":"<li class='page-item'> <a href='".change_url_parameter(get_pagenum_link($i),'type',$k).$cl_ancora ."' class=\" no-ajax page-numbers\">".$i."</a></li>";
                }
            }
        if ($paged < $pages && $showitems < $pages) 
            $h.= " <li class='page-item'><a class='next no-ajax page-numbers' href=\"".change_url_parameter(get_pagenum_link($paged+1),'type',$k).$cl_ancora ."\"> </a></li>";

        if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) 
            $h.= " <li class='page-item'><a class=' no-ajax page-numbers' href='".change_url_parameter(get_pagenum_link($pages),'type',$k).$cl_ancora ."'>".__('Last','idealbiz')."</a></li>";

        $h.= "</ul>
        </nav>";
        $h.='</div>';
        $h.="<div class='d-flex justify-content-center'>
            <span class='m-t-8'>".__("Page","idealbiz")." ".$paged." ".__("of","idealbiz")." ".$pages."</span>
        </div>";
    }
    return $h;
}

/* Highlight search word

*/
function excerpt($text, $phrase, $radius = 100, $ending = "...") { 
    $phraseLen = strlen($phrase); 
    if ($radius < $phraseLen) {  $radius = $phraseLen;  } 
    $phrases = explode (' ',$phrase);
    foreach ($phrases as $phrase) { $pos = strpos(strtolower($text), strtolower($phrase)); if ($pos > -1) break; }
    $startPos = 0; 
    if ($pos > $radius) { $startPos = $pos - $radius; } 
    $textLen = strlen($text); 
    $endPos = $pos + $phraseLen + $radius; 
    if ($endPos >= $textLen) {  $endPos = $textLen;  } 
    $excerpt = substr($text, $startPos, $endPos - $startPos); 
    if ($startPos != 0) {  $excerpt = substr_replace($excerpt, $ending, 0, $phraseLen);  } 
    if ($endPos != $textLen) {  $excerpt = substr_replace($excerpt, $ending, -$phraseLen);   } 
    return $excerpt; 
} 

function highlight($c,$q, $colors){ 
    $q=explode(' ',str_replace(array('','\\','+','*','?','[','^',']','$','(',')','{','}','=','!','<','>','|',':','#','-','_'),'',$q));
    for($i=0;$i<sizeOf($q);$i++) 
        $c=preg_replace("/($q[$i])(?![^<]*>)/i","<span class=\"highlight\" style=\"".$colors."\">\${1}</span>",$c);
    return $c;
}

function search_content_highlight($word, $colors = '', $radius = 100) {
    $content = apply_filters('the_content',get_the_content());
    $content = highlight(excerpt(wp_strip_all_tags($content), $word, $radius), $word, $colors);
    return $content;
}

/********** Email send to customer care on edit listing **********/
if(isset($_GET['edited'])){
    if(get_post_type($_GET['edited'])=='listing'){
        $message = get_bloginfo().' - '.__( 'Listing#', 'idealbiz' ).' <a href="'.get_permalink($_GET['edited']).'">'.$_GET['edited'].'</a> "'.get_the_title($_GET['edited']).'" '.__('was edited by the user.','idealbiz');
        $emailHtml  = get_email_header();
        $emailHtml .= get_email_intro(NULL, $message);
        $emailHtml .= get_email_footer();
        /* Email Params */
        $subject = get_bloginfo().' - '.__( 'Listing#', 'idealbiz' ).' '.$_GET['edited'].' "'.get_the_title($_GET['edited']).'" '.__('was edited by the user.','idealbiz');
        $headers = array('Content-Type: text/html; charset=UTF-8');
        //echo $emailHtml;
        wp_mail(
            get_field('costumer_care_email', 'option'),
            $subject, 
            $emailHtml,
            $headers);
        /********** Email **********/        
    }
}

/**
* WooCommerce My Account
* Returns custom html / css class for WooCommerce default wrapper on My Account pages
* @see https://github.com/woocommerce/woocommerce/blob/857c5cbc5edc0451cf965b19788e3993804d4131/includes/class-wc-shortcodes.php#L59
*
**/
if ( class_exists( 'woocommerce' ) ) {
    function wp_wc_my_account_shortcode_handler( $atts ) {
        $whichClass = new WC_Shortcodes();
        $wrapper = array(
        'class'  => 'woocommerce w-100',
        'before' => null,
        'after'  => null
        );
        return $whichClass->shortcode_wrapper( array( 'WC_Shortcode_My_Account', 'output' ), $atts , $wrapper );
    }
    add_shortcode( 'new_woocommerce_my_account', 'wp_wc_my_account_shortcode_handler' );
}

function calculatePriceWithTax($product,$price){
    $tax_rate_info = 0;
    // get Tax info:
    $rprice = $price;
    $tax_rates = WC_Tax::get_rates( $product->get_tax_class() );
    if (!empty($tax_rates)) {
        $tax_rate = reset($tax_rates);
        $tax_rate_info = (int)$tax_rate['rate'];
        $rprice = number_format( round(($price + ($price*($tax_rate_info/100))), 1), 2 );
    }
    if (strpos($rprice, '.00') !== false) {
        return substr($rprice, 0, -3);
    }
   return $rprice;
}

function calculatePricePerMonthWithTax($product,$price,$plan_duration_days){
    $num = (int)str_replace(',', '', calculatePriceWithTax($product,$price));
    return round(($num/(round($plan_duration_days/30))),2);
}

function general_admin_notice(){
if (isset($_GET['post'])){
$to_sites = get_field( "broadcast_post");
if($to_sites){
    ?>
<script>
jQuery(document).ready(($) => {
    $('body').prepend(
        '<p class="sites-request notice notice-info is-dismissible">User requested publication on other sites. (Check "Broadcast" Section)</p>'
    );
    $('.blogs.html_section .show_hide.howto').click();
    $('.blogs.html_section').prepend(
        '<p style="color: #007cba; font-weight: 700;">Request listing on site: </p>');
});
</script>
<style>
.sites-request {
    z-index: 99999;
    position: absolute;
    top: 2px !important;
    width: calc(100% - 60px) !important;
    padding: 12px !important;
}

<?php foreach ($to_sites as $s) {
    echo '.form_item_blogs_'.$s.'{ color: #007cba;  font-weight: 700; }';
}

?>
</style>
<?php     
}
}
}
add_action('admin_notices', 'general_admin_notice');

function get_orders_ids_with_upgrades( $order_status = array( 'wc-completed' ), $inLang = false ){
    global $wpdb;

    $rp = $wpdb->get_col("
    SELECT *
    FROM  {$wpdb->prefix}posts b INNER JOIN {$wpdb->prefix}postmeta a ON a.post_id = b.ID
        WHERE
        a.meta_key = '_product_type_meta_key' 
        AND a.meta_value = 'upgrade_plan'
        AND b.post_type = 'product'");

            if ($inLang &&  function_exists('pll_the_languages') ) {
                $rp[0] = pll_get_post($rp[0], $inLang);
            }

        $product_id = $rp[0];  
       // echo $product_id;

    $results = $wpdb->get_col(" 
        SELECT order_items.order_id
        FROM {$wpdb->prefix}woocommerce_order_items as order_items
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
        LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
        WHERE posts.post_type = 'shop_order'
        AND posts.post_status IN ( '" . implode( "','", $order_status ) . "' )
        AND order_items.order_item_type = 'line_item'
        AND order_item_meta.meta_key = '_product_id'
        AND order_item_meta.meta_value = '$product_id'
    ");

    return $results;
}

function orderby_tax_clauses( $clauses, $wp_query ) {
    $orderby_arg = $wp_query->get('orderby');
    if ( ! empty( $orderby_arg ) && substr_count( $orderby_arg, 'taxonomy.' ) ) {
      global $wpdb;
      $bytax = "GROUP_CONCAT({$wpdb->terms}.name ORDER BY name ASC)";
      $array = explode( ' ', $orderby_arg ); 
      if ( ! isset( $array[1] ) ) {
        $array = array( $bytax, "{$wpdb->posts}.post_date" );
        $taxonomy = str_replace( 'taxonomy.', '', $orderby_arg );
      } else {
        foreach ( $array as $i => $t ) {
          if ( substr_count( $t, 'taxonomy.' ) )  {
            $taxonomy = str_replace( 'taxonomy.', '', $t );
            $array[$i] = $bytax;
          } elseif ( $t === 'meta_value' || $t === 'meta_value_num' ) {
            $cast = ( $t === 'meta_value_num' ) ? 'SIGNED' : 'CHAR';
            $array[$i] = "CAST( {$wpdb->postmeta}.meta_value AS {$cast} )";
          } else {
            $array[$i] = "{$wpdb->posts}.{$t}";
          }
        }
      }
      $order = strtoupper( $wp_query->get('order') ) === 'ASC' ? ' ASC' : ' DESC';
      $ot = strtoupper( $wp_query->get('ordertax') );
      $ordertax = $ot === 'DESC' || $ot === 'ASC' ? " $ot" : " $order";
      $clauses['orderby'] = implode(', ',
        array_map( function($a) use ( $ordertax, $order ) {
          return ( strpos($a, 'GROUP_CONCAT') === 0 ) ? $a . $ordertax : $a . $order;
        }, $array )
      );
      $clauses['join'] .= " LEFT OUTER JOIN {$wpdb->term_relationships} ";
      $clauses['join'] .= "ON {$wpdb->posts}.ID = {$wpdb->term_relationships}.object_id";
      $clauses['join'] .= " LEFT OUTER JOIN {$wpdb->term_taxonomy} ";
      $clauses['join'] .= "USING (term_taxonomy_id)";
      $clauses['join'] .= " LEFT OUTER JOIN {$wpdb->terms} USING (term_id)";
      $clauses['groupby'] = "object_id";
      $clauses['where'] .= " AND (taxonomy = '{$taxonomy}' OR taxonomy IS NULL)";
    }
    return $clauses;
  }

/* 
filter form messages 
replace ids for taxonomy name
strings
*/
add_filter( 'gform_pre_send_email', function ( $email, $message_format ) {
    $id= get_string_between( $email['message'], '{', '}');
    $email['message'] = str_replace('{'.$id.'}',get_term_by('id', $id, 'service_cat')->name,$email['message']);
    if($email['subject'] == 'contact_expert_idealbiz' || $email['subject'] == 'contact_expert') {
        return $email;
    }
    $here= get_string_between( $email['message'], '$', '$');
    $my_account_link = ' <a href="'.get_permalink( get_option('woocommerce_myaccount_page_id')).'service_request/">'.$here.'</a>';
    $email['message'] = str_replace('$'.$here.'$',$my_account_link,$email['message']);

    $to_email =get_string_between( $email['message'], '@', '@');
    $expert_email = get_field('expert_email',$to_email);
    
    $email['message'] = str_replace('@'.$to_email.'@',$expert_email,$email['message']);
    if($expert_email)
        $email['to']=$expert_email;

    $customer_care = ', '.get_field('costumer_care_email', 'option');
    $email['to'] = $email['to'].$customer_care;
    return $email; 
    }, 10, 2 );

    $current_user = wp_get_current_user();
    if(isset($_GET['accept'])){
        if(get_field('consultant',$_GET['accept'])->ID == $current_user->ID){
            update_field( 'state', 'Pending Proposal', $_GET['accept'] );
        }
    }

if(isset($_GET['reject_contract_id'])){
    $title = get_the_title($_GET['reject_contract_id']);
    $service_request_id= explode('_',get_string_between($title, '_', ':'))[1];

    if(get_field('customer',$service_request_id)->ID == $current_user->ID){
       
        update_field( 'progress', 'Rejected', $_GET['reject_contract_id'] );
        update_field( 'state', 'Pending Proposal', $service_request_id );
    }
}




//NPMM - Dados que vem da caixa de rejeição.
if(isset($_POST['reject'])){

    if(get_field('consultant',$_POST['proposal_id'])->ID == $current_user->ID ){
        if(get_field('state',$_POST['proposal_id']) == 'Pending Expert Acceptance'){
            $cl_referencia_servico = $_POST['proposal_id'];
            $cl_member_id = get_field('consultant', $cl_referencia_servico,'');
            $cl_member_data = get_userdata($cl_member_id);
            $cl_member_f_name = $cl_member_data->first_name;
            $cl_member_l_name = $cl_member_data->last_name;
            $customer = get_field('customer',$_POST['proposal_id']);

            if(WEBSITE_SYSTEM=='1'){


                //Email de rejeição de Service Request para Customer Care
                //update_field( 'consultant', get_user_by( 'email', get_field('costumer_care_email', 'option') )->ID, $_POST['proposal_id'] );
                $subject = pll__( 'New Service Request rejected!', 'idealbiz' );
                $hi = __('Service Request Rejected', 'idealbiz' ).' #'.'<i>'.$cl_referencia_servico.'</i>';
                $message = __('One Service Request', 'idealbiz' ).' "'.get_the_title($_POST['proposal_id']).'" '.__('has been rejected and now it is assigned to Customer Care.','idealbiz').': <br/><b><i>'.$cl_member_f_name.' '.$cl_member_l_name.' </b></i>';
                $message .= '<br/><br/>'.__('Reason:','idealbiz').'<br/>"'.$_POST['reason'].'"<br/>';
                $message .= '<br/><br/>'. __('It is necessary to verify that the user').' <b><i>'.$customer->user_email.' </b></i>'.' <br/>'. __('needs follow-up or if a new Service Request was created by it').'.';
                
                $emailHtml  = get_email_header(pll__('Customer Care'), ''); 
                $emailHtml .= get_email_intro(pll__('Customer Care'), $message, $hi);
                $emailHtml .= get_email_footer();
                $headers = array('Content-Type: text/html; charset=UTF-8');
                wp_mail(get_field('costumer_care_email', 'option'), 
                        $subject, 
                        $emailHtml, 
                        $headers); 
            }
                //Email de rejeição de Service Request para Utilizador
                $reason = ' '.__('by Expert','idealbiz').'<br/> <span class="small"><b>"'.$_POST['reason'] .'".</b></span>';
                update_field( 'state', 'Rejected', $_POST['proposal_id'] );
                update_field( 'rejected', $reason, $_POST['proposal_id'] );
                $subject = __('Your Service Request has been rejected!', 'idealbiz' );
                $hi = __('Service Request Rejected', 'idealbiz' ).' #'.'<i>'.$cl_referencia_servico.'</i>';
                $message = __( 'Your Service Request', 'idealbiz' ).' <b><i>'.' "'.get_the_title($_POST['proposal_id']).' " </b></i>'.__('has been rejected by the Expert.','idealbiz').'.';
                $message .= '<br/><br/>'.__('Please modify your Order or select another Professional, and resubmit the form','idealbiz').'.';
                $message.='<br/><br/>'.__('If you have any questions, please contact us','idealbiz');
               /*  $message .= '<a href="'.__('Contact Link Page','Reject Mail').'" title="'.__('Get in touch','Reject Mail').'">'.' '.__('Get in touch','idealbiz').'</a>'; */
                $message .= '<br/><br/>'.__('Thank you.');
                
                $emailHtml  = get_email_header($customer->ID, ''); 
                $emailHtml .= get_email_intro($customer->ID, $message, $hi);
                $emailHtml .= get_email_footer();
                $headers = array('Content-Type: text/html; charset=UTF-8');
                wp_mail($customer->user_email,$subject,$emailHtml,$headers); 





                            
        }else{
            if( get_field('state',$_POST['proposal_id']) != 'Closed' &&
                get_field('state',$_POST['proposal_id']) != 'Rejected' &&
                get_field('state',$_POST['proposal_id']) != 'Canceled' ){

                $reason = ' '.__('by Expert','idealbiz').'<br/> <span class="small"><b>"'.$_POST['reason'] .'".</b></span>';
                update_field( 'state', 'Canceled', $_POST['proposal_id'] );
                update_field( 'rejected', $reason, $_POST['proposal_id'] );
                $customer = get_field('customer',$_POST['proposal_id']);
                $message = __( 'Your Service Request', 'idealbiz' ).' "'.get_the_title($_POST['proposal_id']).'" '.__('has been canceled by the Expert.','idealbiz');
                //$message .= '<br/><br/><br/>'.__('Reason:','idealbiz').'<br/>"'.$_POST['reason'].'"<br/><br/><br/>';
                $message .= '<br/><br/><a href="'.get_permalink( get_option('woocommerce_myaccount_page_id') ).'" title="'.__('My Account','idealbiz').'">'.__('My Account','idealbiz').'</a>';
                $emailHtml  = get_email_header($customer->ID, '');
                $emailHtml .= get_email_intro($customer->ID, $message);
                $emailHtml .= get_email_footer();
                $subject = get_bloginfo().' - '.__( 'Your Service Request has been canceled!', 'idealbiz' );
                $headers = array('Content-Type: text/html; charset=UTF-8');
                wp_mail($customer->user_email, 
                        $subject, 
                        $emailHtml, 
                        $headers); 


                // get ongoing contract
                $sc_title_aux = 'service_contract_'.$_POST['proposal_id'];
                $ongoing_contrect_id=0;
                $q_service_contract="
                SELECT {$wpdb->posts}.*
                FROM {$wpdb->posts}  
                INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )  
                INNER JOIN {$wpdb->postmeta} AS mt1 ON ( {$wpdb->posts}.ID = mt1.post_id )  
                WHERE 1=1  
                AND mt1.meta_key = 'progress' 
                AND (mt1.meta_value = 'In Progress' OR  mt1.meta_value = 'Pending Payment')
                AND {$wpdb->posts}.post_title LIKE '%".$sc_title_aux."%'
                AND {$wpdb->posts}.post_type = 'service_contract'
                GROUP BY {$wpdb->posts}.ID";
                $result_service_contracts = $wpdb->get_results($q_service_contract, ARRAY_A);
                foreach($result_service_contracts as $kq_service_contract => $service_contract){
                    $ongoing_contract_id=$service_contract['ID'];
                }

                //send email to customer care
                if($ongoing_contract_id){
                    $expert = get_field('consultant',$_POST['proposal_id']);
                    $customer = get_field('customer',$_POST['proposal_id']);

                    $message = __( 'The Service Request', 'idealbiz' ).' "'.get_the_title($_POST['proposal_id']).'" REF#'.$_POST['proposal_id'].'<br/>
                    '.__('has been canceled by the Expert:','idealbiz').'<br/>'.$expert->display_name.'';
                    $message .= '<br/><br/><br/>'.__('Reason:','idealbiz').'<br/>
                                    "'.$_POST['reason'].'"<br/><br/><br/>';
                    $message .= '<br/><br/>'.__('Please make the refund to the Customer:','idealbiz').'<br/> '.$customer->display_name.'<br/><br/>';
                    $emailHtml  = get_email_header('customer_care', '');
                    $emailHtml .= get_email_intro('customer_care', $message);
                    $emailHtml .= get_email_footer();
                    $subject = get_bloginfo().' - '.__( 'Service Request REF#'.$_POST['proposal_id'].' has been canceled by the Expert!', 'idealbiz' );
                    $headers = array('Content-Type: text/html; charset=UTF-8');
                    wp_mail(get_field('costumer_care_email', 'option'), 
                            $subject, 
                            $emailHtml, 
                            $headers); 
                }
            }
            
        }
    }

    if(
        (get_field('customer',$_POST['proposal_id'])->ID == $current_user->ID 
        && get_field('state',$_POST['proposal_id']) == 'Pending Expert Acceptance')
        ||
        (get_field('customer',$_POST['proposal_id'])->ID == $current_user->ID 
        && get_field('state',$_POST['proposal_id']) == 'Pending Proposal')
    ){
        $reason = ' '.__('by Customer','idealbiz').'<br/> <span class="small"><b>"'.$_POST['reason'] .'"</b></span>';
        update_field( 'state', 'Canceled', $_POST['proposal_id'] );
        update_field( 'rejected', $reason, $_POST['proposal_id'] );
        $expert = get_field('consultant',$_POST['proposal_id']);
        $message = __( 'Your Service Request', 'idealbiz' ).' "'.get_the_title($_POST['proposal_id']).'" '.__('has been rejected by the Customer.','idealbiz');
        $message .= '<br/><br/><br/>'.__('Reason:','idealbiz').'<br/>
                        "'.$_POST['reason'].'"<br/><br/><br/>';
        $message .= '<br/><br/><a href="'.get_permalink( get_option('woocommerce_myaccount_page_id') ).'" title="'.__('My Account','idealbiz').'">'.__('My Account','idealbiz').'</a>';
        $emailHtml  = get_email_header($expert->ID, '');
        $emailHtml .= get_email_intro($expert->ID, $message);
        $emailHtml .= get_email_footer();
        $subject = get_bloginfo().' - '.__( 'Your Service Request has been rejected!', 'idealbiz' );
        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail(
            $expert->user_email, 
            $subject, 
            $emailHtml, 
            $headers); 
    }
}

//NPMM - Dados que vem da caixa de Confirmação.
if(isset($_POST['confirm'])){
    
    if(get_field('consultant',$_POST['confirmLead_id'])->ID == $current_user->ID ){


            $cl_referencia_servico = $_POST['confirmLead_id'];
            $cl_member_id = get_field('consultant', $cl_referencia_servico)->ID;
            $cl_member_data = get_userdata($cl_member_id);
            $cl_member_f_name = $cl_member_data->first_name;
            $cl_member_l_name = $cl_member_data->last_name;
            $cl_member_email = $cl_member_data->user_email;
            $customer = get_field('customer',$_POST['confirmLead_id']);
            $checkout_url  = $_POST['checkout_url'];
            


            if ($_POST['comment']=="" || $_POST['comment']==null || !isset($_POST['comment'])){
                $cl_contentComment = __('_str No comment available','idealbiz');
            }else{
                $cl_contentComment = $_POST['comment'];
            }
           
            $comment = ' '.__('_STR COMMENT OF','idealbiz').'<br/> <span class="small"><b>"'.$cl_contentComment.'".</b></span>';
                
                //Email de Confirmação de Service Request para Customer Care
                
                $date_by_server = current_time( 'd-m-y H:i:s');

                update_field( 'state','Confirmed Lead', $_POST['confirmLead_id'] );
                update_field( 'sr_confirmed', $comment, $_POST['confirmLead_id'] );
                update_field( 'sr_confirmation_date', $date_by_server, $_POST['confirmLead_id'] );
                

                $subject = __( '_str Confirmed - Service Request', 'idealbiz' ).' '.$cl_referencia_servico;
                $hi = __('_str Service Request Confirmed', 'idealbiz' );
                $message  = __('Hello','idealbiz').' '.__('_str Customer','idealbizio').'<br/><br/>';
                $message .= __('_str One Service Confirmed', 'idealbiz' ).' "'.get_the_title($_POST['confirmLead_id']).'". ';
                $message .= '<br/>'.__('_str Service Request Number','').': <b>'.$cl_referencia_servico.'.</b>';
                $message .= '<br/>'.__('_str has been confirmed by ','idealbiz').': <b>'.$cl_member_f_name.' '.$cl_member_l_name.'. </b>';
                $message .= '<br/>'.__('_str Date confirmed by ','idealbiz').': <b>'.$date_by_server.'. </b>';
                $message .= '<br/><br/>'.__('_str Comment upon confirmation','idealbiz').': '.'<br/>"'.$_POST['comment'].'"<br/>';
                $message .= '<br/><br/>'.__('The iDealBiz Team','idealbizio').'.';
                $message .= '<br/><span style="color:#ffffff;font-size:0.5em;">FUN01</span>';
                
                $emailHtml  = get_email_header(pll__('Customer Care'), ''); 
                $emailHtml .= get_email_intro(pll__('Customer Care'), $message, $hi);
                $emailHtml .= get_email_footer();
                $headers = array('Content-Type: text/html; charset=UTF-8');
                wp_mail(get_field('costumer_care_email', 'option'),$subject,$emailHtml,$headers); 

               

                $subject = __( '_str Confirmed - Service Request', 'idealbiz' ).' '.$cl_referencia_servico;
                $hi = __('_str Service Request Confirmed', 'idealbiz' );
                $message = __('Hello','idealbiz').' '.$cl_member_f_name.' '.$cl_member_l_name.'. ';           
                $message .= '<br/><br/>'.__('_str We are pleased to hear that the deal was concluded, in the area of', 'idealbiz' ).': "'.get_the_title($_POST['confirmLead_id']).'". ';
                $message .= '<br/>'.__('_str Service Request Number','').': <b>'.$cl_referencia_servico.'.</b>';
                $message .= '<br/>'.__('_str Date confirmed by ','idealbiz').': <b>'.$date_by_server.'. </b>';
                $message .= '<br/><br/>'.__('_str Comment upon confirmation','idealbiz').': '.'<br/>"'.$_POST['comment'].'"<br/>';
                $message .= '<br/><br/>'.__('_str Thank you for making this confirmation, we ask that you kindly execute the payment for this Lead and so that you can do this we send the link below','idealbiz').'.';
                $message .= '<br/><a href="'.$checkout_url.'">'.__('_str Click here to pay','idealbiz').'</a>';
                $message .= '<br/><br/>'.__('The iDealBiz Team','idealbizio').'.';
                $message .= '<br/><span style="color:#ffffff;font-size:0.5em;">FUN02</span>';
                
                $emailHtml  = get_email_header(pll__('Customer Care'), ''); 
                $emailHtml .= get_email_intro(pll__('Customer Care'), $message, $hi);
                $emailHtml .= get_email_footer();
                $headers = array('Content-Type: text/html; charset=UTF-8');
                wp_mail($cl_member_email,$subject,$emailHtml,$headers); 
                //cl_alerta('Passei pelo envio de email');

    }
}

//NPMM - Dados que vem da caixa de Confirmação de Oportunidade.
if(isset($_POST['confirmOpportunity'])){
    $current_user = wp_get_current_user();
    $id_member = isExpert($current_user->ID);
    $id_member_Recommended = get_field('rb_id_owner_of_listing',$_POST['confirmLeadOpportunity_id']);

    if($id_member_Recommended == $id_member[0]->ID){

        //var_dump($id_member);

            $cl_referencia_servico = $_POST['confirmLeadOpportunity_id'];
            $cl_member_id =$id_member[0]->ID;

            $cl_member_name = $id_member[0]-> post_title;
            $cl_member_email = $id_member[0]->meta_value;


            /* $cl_member_data = get_userdata($cl_member_id);
            $cl_member_f_name = $cl_member_data->first_name;
            $cl_member_l_name = $cl_member_data->last_name;
            $cl_member_email = $cl_member_data->user_email; */




            $customer = get_field('customer',$_POST['confirmLeadOpportunity_id']);
            $checkout_url  = $_POST['checkout_url'];
            


            if ($_POST['comment']=="" || $_POST['comment']==null || !isset($_POST['comment'])){
                $cl_contentComment = __('_str No comment available','idealbiz');
            }else{
                $cl_contentComment = $_POST['comment'];
            }
           
            $comment = ' '.__('_STR COMMENT OF','idealbiz').'<br/> <span class="small"><b>"'.$cl_contentComment.'".</b></span>';
                
                //Email de Confirmação de Recommended Opportunity para Customer Care
                
                $date_by_server = current_time( 'd-m-y H:i:s');

                //update_field( 'state','Confirmed Lead', $_POST['confirmLeadOpportunity_id'] );
                update_field( 'rb_confirmed', $comment, $_POST['confirmLeadOpportunity_id'] );
                update_field( 'rb_confirmation_date', $date_by_server, $_POST['confirmLeadOpportunity_id'] );
                

                $subject = __( '_str Confirmed - Recommended Opportunity', 'idealbiz' ).' '.$cl_referencia_servico;
                $hi = __('_str Recommended Opportunity Confirmed', 'idealbiz' );
                $message  = __('Hello','idealbiz').' '.__('_str Customer','idealbizio').'<br/><br/>';
                $message .= __('_str One Recommended Opportunity is Confirmed', 'idealbiz' ).' "'.get_the_title($_POST['confirmLeadOpportunity_id']).'". ';
                $message .= '<br/>'.__('_str Recommended Opportunity Number','').': <b>'.$cl_referencia_servico.'.</b>';
                $message .= '<br/>'.__('_str has been confirmed by ','idealbiz').': <b>'.$cl_member_name.' - '.$cl_member_email.'. </b>';
                $message .= '<br/>'.__('_str Date confirmed by ','idealbiz').': <b>'.$date_by_server.'. </b>';
                $message .= '<br/><br/>'.__('_str Comment upon confirmation','idealbiz').': '.'<br/>"'.$_POST['comment'].'"<br/>';
                $message .= '<br/><br/>'.__('The iDealBiz Team','idealbizio').'.';
                
                $emailHtml  = get_email_header(pll__('Customer Care'), ''); 
                $emailHtml .= get_email_intro(pll__('Customer Care'), $message, $hi);
                $emailHtml .= get_email_footer();
                $headers = array('Content-Type: text/html; charset=UTF-8');
                wp_mail(get_field('costumer_care_email', 'option'),$subject,$emailHtml,$headers); 

               

                $subject = __( '_str Confirmed - Recommended Opportunity', 'idealbiz' ).' '.$cl_referencia_servico;
                $hi = __('_str Recommended Opportunity Confirmed', 'idealbiz' );
                $message = __('Hello','idealbiz').' '.$cl_member_name.'. ';           
                $message .= '<br/><br/>'.__('_str We are pleased to hear that the deal was concluded,', 'idealbiz' ).': "'.get_the_title($_POST['confirmLeadOpportunity_id']).'". ';
                $message .= '<br/>'.__('_str Recommended Opportunity Number','').': <b>'.$cl_referencia_servico.'.</b>';
                $message .= '<br/>'.__('_str Date confirmed by ','idealbiz').': <b>'.$date_by_server.'. </b>';
                $message .= '<br/><br/>'.__('_str Comment upon confirmation','idealbiz').': '.'<br/>"'.$_POST['comment'].'"<br/>';
                $message .= '<br/><br/>'.__('_str Thank you for making this confirmation, we ask that you kindly execute the payment for this Lead and so that you can do this we send the link below','idealbiz').'.';
                $message .= '<br/><a href="'.$checkout_url.'">'.__('_str Click here to pay','idealbiz').'</a>';
                $message .= '<br/><br/>'.__('The iDealBiz Team','idealbizio').'.';
                
                $emailHtml  = get_email_header(pll__('Customer Care'), ''); 
                $emailHtml .= get_email_intro(pll__('Customer Care'), $message, $hi);
                $emailHtml .= get_email_footer();
                $headers = array('Content-Type: text/html; charset=UTF-8');
                wp_mail($cl_member_email,$subject,$emailHtml,$headers); 
                //cl_alerta('Passei pelo envio de email');

    }
}


add_action( 'wp_ajax_f711_get_post_content', 'f711_get_post_content_callback' );
add_action( 'wp_ajax_nopriv_f711_get_post_content', 'f711_get_post_content_callback' );
function f711_get_post_content_callback(){
    $postID = $_POST['service_cat'];

    if($postID!=''){
        $tax = array(
                array(
                    'taxonomy' => 'service_cat', // you can change it according to your taxonomy
                    'field' => 'term_id', // this can be 'term_id', 'slug' & 'name'
                    'terms' => $postID,
                )
            );
    }
    $post_args = array(
            'posts_per_page' => -1,
            'post_type' => 'expert', 
            'post_status' => 'publish',
            'tax_query' => $tax
    );
    $myposts = get_posts($post_args); 
    $opts.='<option value=""></option>';
    if ( $myposts ) {
        foreach($myposts as $post) {
            $opts.='<option value="'.$post->ID.'">'.$post->post_title.'</option>';
        }
        wp_reset_postdata();
    }
    print $opts;
    exit;
}

add_action( 'init', 'script_enqueuer' );
function script_enqueuer() {
   wp_localize_script( 'liker_script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));        
   wp_enqueue_script( 'jquery' );
}

add_action( 'wp_ajax_edit_order_billing', 'edit_order_billing_callback' );
add_action( 'wp_ajax_nopriv_edit_order_billing', 'edit_order_billing_callback' );
function edit_order_billing_callback(){
    $orderID = $_POST["orderID"];
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $billingCompany = $_POST["billingCompany"];
    $billingAddress_1 = $_POST["billingAddress_1"];
    $billingAddress_2 = $_POST["billing_address_2"];
    $billing_city = $_POST["billing_city"];
    $billing_state = $_POST["billing_state"];
    $billing_postcode = $_POST["billing_postcode"];
    $billing_phone = $_POST["billing_phone"];
    $billing_email = $_POST["billing_email"];
    $billing_nif = $_POST["billing_nif"];
    $vat_number = $_POST["vat_number"];
    update_post_meta( $orderID, '_billing_first_name', $firstName );
    update_post_meta( $orderID, '_billing_last_name', $lastName );
    update_post_meta( $orderID, '_billing_company', $billingCompany );
    update_post_meta( $orderID, '_billing_address_1', $billingAddress_1 );
    update_post_meta( $orderID, '_billing_address_2', $billingAddress_2 );
    update_post_meta( $orderID, '_billing_city', $billing_city );
    update_post_meta( $orderID, '_billing_state', $billing_state );
    update_post_meta( $orderID, '_billing_postcode', $billing_postcode );
    update_post_meta( $orderID, '_billing_phone', $billing_phone );
    update_post_meta( $orderID, '_billing_email', $billing_email );
    update_post_meta( $orderID, '_billing_country', $billing_country );
    update_post_meta( $orderID, 'vat_number', $billing_nif );
    update_user_meta( get_current_user_id(), 'billing_first_name', $firstName );
    update_user_meta( get_current_user_id(), 'billing_last_name', $lastName );
    update_user_meta( get_current_user_id(), 'billing_company', $billingCompany );
    update_user_meta( get_current_user_id(), 'billing_address_1', $billingAddress_1 );
    update_user_meta( get_current_user_id(), 'billing_address_2', $billingAddress_2 );
    update_user_meta( get_current_user_id(), 'billing_city', $billing_city );
    update_user_meta( get_current_user_id(), 'billing_state', $billing_state );
    update_user_meta( get_current_user_id(), 'billing_postcode', $billing_postcode );
    update_user_meta( get_current_user_id(), 'billing_phone', $billing_phone );
    update_user_meta( get_current_user_id(), 'billing_email', $billing_email );
    update_user_meta( get_current_user_id(), 'billing_country', $billing_country );
    update_user_meta( get_current_user_id(), 'vat_number', $billing_nif );
echo 1;
    exit;
}

add_filter('acf/validate_value/key=field_5ef20b3fd7689', 'unique_repeater_sub_field', 20, 4);

function unique_repeater_sub_field($valid, $value, $field, $input) {
    if (!$valid) {
      return $valid;
    }
    
    // get list of array indexes from $input
    // [ <= this fixes my IDE, it has problems with unmatched brackets
    preg_match_all('/\[([^\]]+)\]/', $input, $matches);
    if (!count($matches[1])) {
      // this should actually never happen
    return $valid;
    }
    $matches = $matches[1];
    // walk the acf input to find the repeater and current row      
    $array = $_POST['acf'];
    
    $repeater_key = false;
    $repeater_value = false;
    $row_key = false;
    $row_value = false;
    $field_key = false;
    $field_value = false;
    
    for ($i=0; $i<count($matches); $i++) {
    if (isset($array[$matches[$i]])) {
        $repeater_key = $row_key;
        $repeater_value = $row_value;
        $row_key = $field_key;
        $row_value = $field_value;
        $field_key = $matches[$i];
        $field_value = $array[$matches[$i]];
        if ($field_key == $field['key']) {
          break;
        }
        $array = $array[$matches[$i]];
      }
    }
    if (!$repeater_key) {
      // this should not happen, but better safe than sorry
      return $valid;
    }
    // look for duplicate values in the repeater
    foreach ($repeater_value as $index => $row) {
      if ($index != $row_key && $row[$field_key] == $value) {
        // this is a different row with the same value
        $valid = 'This value is not unique';
        break;
      }
    }
    return $valid;
  }
  
/**
 * Add a discount to an Orders programmatically
 * (Using the FEE API - A negative fee)
 *
 * @since  3.2.0
 * @param  int     $order_id  The order ID. Required.
 * @param  string  $title  The label name for the discount. Required.
 * @param  mixed   $amount  Fixed amount (float) or percentage based on the subtotal. Required.
 * @param  string  $tax_class  The tax Class. '' by default. Optional.
 */
function wc_order_add_discount( $order_id, $title, $amount, $tax_class = '' ) {
    $order    = wc_get_order($order_id);
    $subtotal = $order->get_subtotal();
    $item     = new WC_Order_Item_Fee();

    if ( strpos($amount, '%') !== false ) {
        $percentage = (float) str_replace( array('%', ' '), array('', ''), $amount );
        $percentage = $percentage > 100 ? -100 : -$percentage;
        $discount   = $percentage * $subtotal / 100;
    } else {
        $discount = (float) str_replace( ' ', '', $amount );
        $discount = $discount > $subtotal ? -$subtotal : -$discount;
    }

    $item->set_tax_class( $tax_class );
    $item->set_name( $title );
    $item->set_amount( $discount );
    $item->set_total( $discount );

    if ( '0' !== $item->get_tax_class() && 'taxable' === $item->get_tax_status() && wc_tax_enabled() && 1==0 ) {
        $tax_for   = array(
            'country'   => $order->get_shipping_country(),
            'state'     => $order->get_shipping_state(),
            'postcode'  => $order->get_shipping_postcode(),
            'city'      => $order->get_shipping_city(),
            'tax_class' => $item->get_tax_class(),
        );
        $tax_rates = WC_Tax::find_rates( $tax_for );
        $taxes     = WC_Tax::calc_tax( $item->get_total(), $tax_rates, false );
        //print_r($taxes);

        if ( method_exists( $item, 'get_subtotal' ) ) {
            $subtotal_taxes = WC_Tax::calc_tax( $item->get_subtotal(), $tax_rates, false );
            $item->set_taxes( array( 'total' => $taxes, 'subtotal' => $subtotal_taxes ) );
            $item->set_total_tax( array_sum($taxes) );
        } else {
            $item->set_taxes( array( 'total' => $taxes ) );
            $item->set_total_tax( array_sum($taxes) );
        }
        $has_taxes = true;
    } else {
        $item->set_taxes( false );
        $has_taxes = false;
    }
    $item->save();
    $order->add_item( $item );
    $order->calculate_totals( $has_taxes );
    $order->save(); 
}

// on coupon add to cart add negative fee to order (service requests)
add_action( 'woocommerce_applied_coupon', function($coupon_code ) {
    $order_id= wc_get_order_id_by_order_key($_GET["key"]);
    if($order_id){
        $order=wc_get_order($order_id);
        $totals = $order->get_order_item_totals();
        $has_coupon = 0;
        $fees = $order->get_fees();
        foreach($fees as $key=>$fee){
            if (strpos($fee['name'], 'idealbiz_points_') !== false) {
                $has_coupon = $fee['name'];
            }
        }
        if($has_coupon){
            return;
        }
        $max_points   = $_POST['ywpar_points_max'];
        $input_points = $_POST['ywpar_input_points'];
        $input_points = ( $input_points > $max_points ) ? $max_points : $input_points;
        foreach ( WC()->cart->get_coupons() as $coupon ){
            wc_order_add_discount( wc_get_order_id_by_order_key($_GET["key"]), '<i class="remove_cop icofont-close"></i> '.__('Coupon: ','idealbiz').$coupon->code.'', $coupon->amount );
            yit_save_prop( $order, 'used_points', $input_points );
        }
    }
});

//remove coupon from cart
add_action('wp_footer', function() {
    if(isset($_GET['remove_discounts'])){ 
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $actual_link = str_replace('remove_discounts','rd',$actual_link);
        WC()->cart->remove_coupons();
        WC()->cart->calculate_totals();
        header('Location: '.$actual_link);
    }
    //hide coupon if order has coupon
    if(isset($_GET['key'])){ 
        $order_id= wc_get_order_id_by_order_key($_GET["key"]);
        if($order_id){
            $order=wc_get_order($order_id);
            $has_coupon = 0;
            $fees = $order->get_fees();
            foreach($fees as $key=>$fee){
                if (strpos($fee['name'], 'idealbiz_points_') !== false) {
                    $has_coupon = $fee['name'];
                }
            }
        }
        if($has_coupon){
            echo '<style>#yith-par-message-reward-cart{display:none;}</style>';
        }
    }
});

//remove fee line from order when click to delete coupon 
add_action('add_or_remove_coupons', function() {
    if(isset($_GET['remove_discounts'])){ 
        $order_id= wc_get_order_id_by_order_key($_GET["key"]);
        $order=wc_get_order($order_id);
        $fees = $order->get_fees();
        foreach($fees as $key=>$fee){
            if (strpos($fee['name'], 'idealbiz_points_') !== false) {
                wc_delete_order_item($key);
                yit_save_prop( $order, 'used_points', 0 );
            }
        }
   }    
});

add_action( 'woocommerce_before_calculate_totals', 'misha_recalc_price' );
function misha_recalc_price( $cart_object ) {
    if(is_wc_endpoint_url( 'order-pay' )){
        foreach ( $cart_object->get_cart() as $hash => $value ) {
            $value['data']->set_price( 999999999 );
        }
    }
}

if( current_user_can('editor') || current_user_can('administrator') ) {

}else{
if (!is_admin() && $GLOBALS['pagenow'] != 'wp-login.php') {

    if (defined('DOING_AJAX') && DOING_AJAX) {

    }else{
        $blog_id = get_current_blog_id();
        $user_id= get_current_user_id();
        $get_user_meta_blog_id = get_user_meta($user_id, 'gotoblog' , true );
        $get_user_meta_blog_lang = get_user_meta($user_id, 'gotobloglang' , true );
        if($user_id){
            if($blog_id==1 && $get_user_meta_blog_id!=1){
                $toblog = get_site_url($get_user_meta_blog_id).'/';
                if($get_user_meta_blog_lang){
                    $toblog.= $get_user_meta_blog_lang.'/';
                }
                header('Location: '.$toblog);
                die();
            }
        }else{
            if( ! session_id() ) {
                session_start();
            }
            if($blog_id!=1){
                $_SESSION['gotoblog'] = $blog_id;
            }
            if($_SESSION['gotoblog'] && $_SESSION['gotoblog']!=1){
                if($blog_id==1){
                    $toblog = get_site_url($_SESSION['gotoblog']);
                    header('Location: '.$toblog);
                    die();
                }
            }
        }
        if($user_id){
            update_user_meta( $user_id, 'gotoblog', get_current_blog_id());
            update_user_meta( $user_id, 'gotobloglang', pll_current_language() );
        }
    }
}
}

add_action('admin_head', 'viewcounter_column_width');
function viewcounter_column_width() {
    echo '<style type="text/css">';
    echo '.column-viewcounter { text-align: center; width:80px !important; overflow:hidden }';
    echo '</style>';
}

/* Display custom column */
function display_posts_viewcounter( $column, $post_id ) {
    if ($column == 'viewcounter'){
            echo '<span class="dashicons dashicons-visibility"></span> <b> ';                         
            if ( get_post_meta( get_the_ID() , 'wpb_post_views_count', true) == '') {                                 
                echo '0' ;                            
            } else { 
            echo get_post_meta( get_the_ID() , 'wpb_post_views_count', true); };    
            echo '</b>';                 
    }
}
add_action( 'manage_posts_custom_column' , 'display_posts_viewcounter', 10, 2 );
add_action( 'manage_pages_custom_column' , 'display_posts_viewcounter', 10, 2 );
add_action( 'manage_expert_posts_custom_columns' , 'display_posts_viewcounter', 10, 2 );
add_action( 'manage_listing_posts_custom_columns' , 'display_posts_viewcounter', 10, 2 );
add_action( 'manage_service_posts_custom_columns' , 'display_posts_viewcounter', 10, 2 );
add_action( 'manage_wanted_posts_custom_columns' , 'display_posts_viewcounter', 10, 2 );
add_action( 'manage_franchise_posts_custom_columns' , 'display_posts_viewcounter', 10, 2 );
add_action( 'manage_service_request_custom_posts_columns' , 'display_posts_viewcounter', 10, 2 );
add_action( 'manage_service_contract_custom_posts_columns' , 'display_posts_viewcounter', 10, 2 );
add_action( 'manage_resume_posts_custom_columns' , 'display_posts_viewcounter', 10, 2 );
add_action( 'manage_counseling_posts_custom_columns' , 'display_posts_viewcounter', 10, 2 );

/* Add custom column to post list */
function add_viewcounter_column( $columns ) {
    return array_merge( $columns, 
        array( 'viewcounter' => __( 'Views', 'your_text_domain' ) ) );
}
add_filter( 'manage_posts_columns' , 'add_viewcounter_column' );
add_filter( 'manage_pages_columns' , 'add_viewcounter_column' );
add_action( 'manage_expert_posts_columns' , 'add_viewcounter_column', 10, 2 );
add_action( 'manage_listing_posts_columns' , 'add_viewcounter_column', 10, 2 );
add_action( 'manage_service_posts_columns' , 'add_viewcounter_column', 10, 2 );
add_action( 'manage_wanted_posts_columns' , 'add_viewcounter_column', 10, 2 );
add_action( 'manage_franchise_posts_columns' , 'add_viewcounter_column', 10, 2 );
add_action( 'manage_service_request_posts_columns' , 'add_viewcounter_column', 10, 2 );
add_action( 'manage_service_contract_posts_columns' , 'add_viewcounter_column', 10, 2 );
add_action( 'manage_resume_posts_columns' , 'add_viewcounter_column', 10, 2 );
add_action( 'manage_counseling_posts_columns' , 'add_viewcounter_column', 10, 2 );


function makeReplacements($msg){
    
    //adiciona dados do referral
    if (!session_id()) {
        session_start();
    }

    if (strpos($msg, '«referral_system»') !== false) {
        if($_SESSION['referral_system']!=''){
            $msg = str_replace('«referral_system»',$_SESSION['referral_system'],$msg);
            $_SESSION['referral_system']=''; 
        }else{
            $msg = str_replace('«referral_system»','',$msg);
        }
    }

    if (strpos($msg, '«referral_system_client»') !== false) {
        if($_SESSION['referral_system_client']!=''){
            $msg = str_replace('«referral_system_client»',$_SESSION['referral_system_client'],$msg);
            $_SESSION['referral_system_client']=''; 
        }else{
            $msg = str_replace('«referral_system_client»','',$msg);
        }
    }
    return $msg;
}

if(pll_current_language()!=false){
    session_start();
    /* $_SESSION['current_lang']= pll_current_language(); */
    //NPMM - Alteri esta linha pouis o email de Buy Lead não esta va chegando no Idioma correto.
    $_SESSION['current_lang']= strtolower(get_option('country_market'));
}


/* before send mail */
add_filter( 'wp_mail', 'my_wp_mail_filter' );
function my_wp_mail_filter( $args ) {
    $country_iso = get_option('country_market');
    $country_name = getCountry($country_iso)['country'];
    $country_flag= DEFAULT_WP_CONTENT.'/plugins/polylang/flags/'.strtolower($country_iso).'.png';
    $flag = '<img style="width:40px height:27px" width="40" height="27" src="'.$country_flag.'" />';

    $new_msg= str_replace('«flag»',$flag,$args['message']);


    
    $new_msg= str_replace('«sociallinks»','<p style="padding-top:5px;"></p>'.get_social_links().'<p></p>',$new_msg);

    $new_msg = makeReplacements($new_msg);

    $new_msg= str_replace('%LOGO_IDEALBIZ%',get_option( 'woocommerce_email_header_image' ), $new_msg);


    $str_part = get_string_between($new_msg, 's_cat1', 's_cat2');
    $term_name = get_term_by('term_taxonomy_id', $str_part,'service_cat')->name;
    $new_msg= str_replace('s_cat1'.$str_part.'s_cat2',''.$term_name, $new_msg);



    session_start();
    $ln=$_SESSION['current_lang'];


    if (strpos($new_msg, 'listingsystem-') !== false) {
        
        if (strpos($new_msg, 'listingsystem-0') !== false) {
            $new_msg= str_replace('«listingsystem-0»','', $new_msg);
        }else{
            $args['subject'] = pll__('(Copy of contact): ').$args['subject'];
            $args['to']=get_field('costumer_care_email', 'option');
            $new_msg = replaceListingSystem($new_msg,$ln);
        }
    }

    //test 
    //$args['to'].= ',ricardo.ferreira@md3studio.com'; 

   // $args['to']= 'ricardo21ferreira@gmail.com';
 
    //$args['headers'][] = 'Bcc: ricardo21ferreira@gmail.com';

 
        $new_wp_mail = array(
            'to'          => $args['to'],
            'subject'     => $args['subject'], 
            'message'     => $new_msg,
            'headers'     => $args['headers'],
            'attachments' => $args['attachments'],
        );
        return $new_wp_mail;
}


//$new_user_email = get_field('customer','71696')->user_email;
//echo $new_user_email;

//get_field('invoice_logo', 'option')['url']

function tt($atts = [], $content = null)
{
    $ft = pll__($content);
    return $ft;
}
add_shortcode('tt', 'tt');


add_filter( 'wp_mail', 'wp_mail_footer' );
function wp_mail_footer( $email ) {
    $lang = get_user_locale();


    switch_to_locale($lang);
    load_theme_textdomain('idealbiz');
    $email['message'] = str_replace('{footer_link}', get_site_url(), $email['message']);
   //$email['message'] = str_replace('{footer_text}', __('iDealBiz - A marketplace for businesses', 'idealbiz'), $email['message']);

   $email['message']= do_shortcode($email['message']);

	$new_wp_mail = array( 
		'to'          => $email['to'],
		'subject'     => $email['subject'],
		'message'     => $email['message'],
		'headers'     => $email['headers'],
		'attachments' => $email['attachments'],
    );


    restore_current_locale();
    load_theme_textdomain('idealbiz');
	return $new_wp_mail;
}

add_action( 'after_setup_theme', function() {
    add_theme_support( 'responsive-embeds' );
} );


add_filter( 'wp_new_user_notification_email_admin', 'new_user_notification_email_filter');
function new_user_notification_email_filter( $wp_new_user_notification_email_admin) {
    $filtered = array(
        'to' => get_field('costumer_care_email', 'option'),
        'subject' => $wp_new_user_notification_email_admin['subject'],
        'message' => $wp_new_user_notification_email_admin['message'],
        'headers' => $wp_new_user_notification_email_admin['headers'],
    );
	return $filtered;
}

add_action('save_post' , 'new_listing_notify' );

function new_listing_notify( $post_id ) {
    if(get_field('notification_customer_email_sent',$post_id)=='1'){
        return;
    }

	if( get_post_type($post_id) == 'listing' && get_post_status($post_id) == 'publish' ) {

    $cl_customer_array =get_field('owner',$post_id);

      
    update_field( 'notification_customer_email_sent', 1, $post_id );

    $message =  '<br/>'. pll_translate_string('_str Hi',pll_get_post_language($post_id)).', {{user}}';
    $message .= '<br/><br/>'. pll_translate_string('Welcome to our professional platform.',pll_get_post_language($post_id) );
    /* $message .= '<br/>'.get_the_title($post_id); */
    $message .= '<br/>'.pll_translate_string('You can view it at the following link:',pll_get_post_language($post_id));
    $message .= '<br/>'.'{{link}}';
    $message .= '<br/><br/>'.pll_translate_string('Regards, The iDealBiz team',pll_get_post_language($post_id));

    $hi = pll_translate_string('iDealBiz | Announcement Publication',pll_get_post_language($post_id) );

    $author_name = get_the_author_meta('first_name',$post_id).''.get_the_author_meta('last_name',$post_id);

    $message = str_replace('{{link}}','<a href="'.get_the_permalink($post_id).'"><b>'.get_the_permalink($post_id).'</b></a>',$message);
    $message = str_replace('{{user}}', $cl_customer_array["display_name"],$message);

    $emailHtml  = get_email_header();
    $emailHtml .= get_email_intro('', $message, $hi);
    $emailHtml .= get_email_footer();
    $headers = 'Content-Type: text/html; charset=UTF-8';



    wp_mail(
        $cl_customer_array["user_email"], 
        $hi, 
        $emailHtml,
        $headers);     

    }
        
        
        return $post_id;
}

add_filter( 'gform_pre_send_email', 'contact_expert_email', 10, 1 );
function contact_expert_email($email) {
    if($email['subject'] == 'contact_expert_idealbiz' || $email['subject'] == 'contact_expert') {
        $site_name = get_bloginfo();
        $lang = get_user_locale(); 
        switch_to_locale($lang);
        load_theme_textdomain('idealbiz');
        switch($email['subject']) {
            case 'contact_expert_idealbiz':
                $subject = $site_name . ' - ' . esc_html__('An expert has been contacted', 'idealbiz');
                $email['subject'] = $subject;
                $email['message'] = str_replace('«expert_has_been_contacted»', esc_html__('An expert has been contacted', 'idealbiz'), $email['message']);
                break;
            case 'contact_expert':
                $subject = $site_name . ' - ' . esc_html__("You've been contacted", 'idealbiz');
                $email['subject'] = $subject;
                $email['message'] = str_replace('«expert_has_been_contacted»', esc_html__("You have been contacted", 'idealbiz'), $email['message']);
                break;
        }
        $email['message'] = str_replace('«expert»', esc_html__('Expert', 'idealbiz'), $email['message']);
        $email['message'] = str_replace('«area_of_expertise»', esc_html__('Area of Expertise', 'idealbiz'), $email['message']);
        $email['message'] = str_replace('«contact_details»', esc_html__('Contact Details', 'idealbiz'), $email['message']);
        $email['message'] = str_replace('«name»', esc_html__('Contact Name', 'idealbiz'), $email['message']);
        $email['message'] = str_replace('«email»', esc_html__('Email', 'idealbiz'), $email['message']);
        $email['message'] = str_replace('«phone»', esc_html__('Phone', 'idealbiz'), $email['message']);
        $email['message'] = str_replace('«message»', __('Contact Message', 'idealbiz'), $email['message']);
        $email['message'] = str_replace('«thanks»', esc_html__('Thank you', 'idealbiz'), $email['message']);
        restore_current_locale();
        load_theme_textdomain('idealbiz');
        return $email;
    }
    else {
        return $email;
    }
}

add_filter( 'woocommerce_email_recipient_customer_completed_order', 'bbloomer_order_completed_email_add_to', 9999, 3 );
add_filter( 'woocommerce_email_recipient_customer_processing_order', 'bbloomer_order_completed_email_add_to', 9999, 3 );
function bbloomer_order_completed_email_add_to( $email_recipient, $email_object, $email ) {
   $email_recipient .= ', '.get_field('costumer_care_email', 'option');
   return $email_recipient;
}

add_filter( 'woocommerce_default_address_fields', 'custom_override_default_checkout_fields', 10, 1 );
function custom_override_default_checkout_fields( $address_fields ) {
    //$address_fields['address_2']['placeholder'] = '';
    $address_fields['address_1']['placeholder'] = ' ';
    return $address_fields;
}

add_filter('woocommerce_account_menu_items', 'webtoffee_remove_my_account_links');
function webtoffee_remove_my_account_links($menu_links) {
    unset($menu_links['payment-methods']); // Payment methods
    return $menu_links;
}
/*
add_action("wpcf7_before_send_mail", "wpcf7_contact_seller");
function wpcf7_contact_seller($WPCF7_ContactForm)
{
    if (2935 == $WPCF7_ContactForm->id() || 5040 == $WPCF7_ContactForm->id()) {
        $wpcf7      = WPCF7_ContactForm::get_current();
        $submission = WPCF7_Submission::get_instance();
        if ($submission) {
            $data = $submission->get_posted_data();
            if (empty($data))
                return;

            $lang         = isset($data['lang']) ? $data['lang'] : "";
            $mail         = $wpcf7->prop('mail');
            $site_name = get_bloginfo();
            switch_to_locale($lang);
            load_theme_textdomain('idealbiz');
            $mail['body'] = str_replace('«new_listing_contact»', esc_html__('Listing contact', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«seller»', esc_html__('Seller', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«listing»', esc_html__('Listing', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«contact_details»', esc_html__('Contact Details', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«name»', esc_html__('Contact Name', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«email»', esc_html__('Email', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«phone»', esc_html__('Phone', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«address»', esc_html__('Address', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«message»', __('Contact Message', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«thanks»', esc_html__('Thank you', 'idealbiz'), $mail['body']);
            $wpcf7->set_properties(array(
                "mail" => $mail  
            ));
            restore_current_locale();
            load_theme_textdomain('idealbiz');
            return $wpcf7;
        }
    }
}

add_action("wpcf7_before_send_mail", "wpcf7_contact_buyer");

function wpcf7_contact_buyer($WPCF7_ContactForm)
{
    if (5610 == $WPCF7_ContactForm->id() || 5614 == $WPCF7_ContactForm->id()) {
        $wpcf7      = WPCF7_ContactForm::get_current();
        $submission = WPCF7_Submission::get_instance();
        if ($submission) {
            $data = $submission->get_posted_data();
            if (empty($data))
                return;

            $lang         = isset($data['lang']) ? $data['lang'] : "";
            $mail         = $wpcf7->prop('mail');

            $site_name = get_bloginfo();
            switch_to_locale($lang);
            load_theme_textdomain('idealbiz');
            $mail['body'] = str_replace('«new_wanted_contact»', esc_html__('Wanted business contact', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«buyer»', esc_html__('Buyer', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«wanted_business»', esc_html__('Wanted Business', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«contact_details»', esc_html__('Contact Details', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«name»', esc_html__('Contact Name', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«email»', esc_html__('Email', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«phone»', esc_html__('Phone', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«address»', esc_html__('Address', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«message»', __('Contact Message', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«thanks»', esc_html__('Thank you', 'idealbiz'), $mail['body']);
            $wpcf7->set_properties(array(
                "mail" => $mail
            ));
            restore_current_locale();
            load_theme_textdomain('idealbiz');
            return $wpcf7;
        }
    }
}

add_action("wpcf7_before_send_mail", "wpcf7_contact_partner");

function wpcf7_contact_partner($WPCF7_ContactForm)
{
    if (5135 == $WPCF7_ContactForm->id() || 5136 == $WPCF7_ContactForm->id()) {
        $wpcf7      = WPCF7_ContactForm::get_current();
        $submission = WPCF7_Submission::get_instance();
        if ($submission) {
            $data = $submission->get_posted_data();
            if (empty($data))
                return;

            $lang         = isset($data['lang']) ? $data['lang'] : "";
            $mail         = $wpcf7->prop('mail');

            $site_name = get_bloginfo();
            switch_to_locale($lang);
            load_theme_textdomain('idealbiz');
            $mail['body'] = str_replace('«new_partner_contact»', esc_html__('Partner contact', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«partner»', esc_html__('Partner', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«contact_details»', esc_html__('Contact Details', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«name»', esc_html__('Contact Name', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«email»', esc_html__('Email', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«company»', esc_html__('Company', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«phone»', esc_html__('Phone', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«address»', esc_html__('Address', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«message»', __('Contact Message', 'idealbiz'), $mail['body']);
            $mail['body'] = str_replace('«thanks»', esc_html__('Thank you', 'idealbiz'), $mail['body']);
            $wpcf7->set_properties(array(
                "mail" => $mail
            ));
            restore_current_locale();
            load_theme_textdomain('idealbiz');
            return $wpcf7;
        }
    }
}
*/

function get_broadcast_parent_id($child_id) {
    $this_post_id = $child_id;
    $broadcast_data = ThreeWP_Broadcast()->get_post_broadcast_data( get_current_blog_id(), $this_post_id );
    $parent = $broadcast_data->get_linked_parent();
    $parent_post_id = $parent['post_id'];
    return $parent_post_id; 
}

add_filter('fep_page_id_filter', 'fep_page_id_filter');

function fep_query_url_filter($page_id) {
    return $page_id;
}


add_filter( 
    'password_change_email', 
    'wpse207879_change_password_mail_message', 
    10, 
    3 
  );
  function wpse207879_change_password_mail_message( 
    $pass_change_mail, 
    $user, 
    $userdata 
  ) {
    $new_message_txt = pll__( 'Hi ###USERNAME###, This notice confirms that your password was changed on ###SITENAME###. If you did not change your password, please contact the Site Administrator at ###ADMIN_EMAIL### This email has been sent to ###EMAIL### Regards,  All at ###SITENAME###  ###SITEURL###' );
        $pass_change_mail[ 'message' ] = $new_message_txt;
    return $pass_change_mail;
  }



/*
$toTranslate = '[idealBiz] You have a new message in your service request';
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

			if($_GET['lang']==$lang){
				$strings = maybe_unserialize( $t->meta_value );
				foreach($strings as $k => $str){
					if($str[0]==$toTranslate){
						return $str[1];
					}
				}
			}
        }
        */


// Register ACF strings on PLL
add_action('init', function () {
    $i=0;
	foreach (acf_get_field_groups() as $group) {
		$fields = acf_get_fields($group['ID']);
		if (is_array($fields) && count($fields)) {
			foreach ($fields as &$field) {
                pll_register_string('form_field_group'.$group['ID'].'_label_'.$field['name'], $field['label'], 'acf_form_fields');
                pll_register_string('form_field_group'.$group['ID'].'_instructions_'.$field['name'], $field['instructions'], 'acf_form_fields');
                if (array_key_exists('message', $field)) { 
                    pll_register_string('form_field_group'.$group['ID'].'_message_i'.$i, $field['message'], 'acf_form_fields');
                }
                if (array_key_exists('choices', $field)) {  
                    foreach($field['choices'] as $k => $val){
                        pll_register_string('form_field_group'.$group['ID'].'_choices_i'.$i, $field['choices'][$k], 'acf_form_fields');
                        $i++;
                    }
                }
                $i++;
			}
		}
    }
});

add_filter('acf/prepare_field', function ($field) {
	if (!is_admin()) {
        $field['label'] = pll__($field['label']);
        $field['instructions'] = pll__($field['instructions']);
        $field['message'] = pll__($field['message']);
        if($field['choices']){
            foreach($field['choices'] as $k => $val){
                $field['choices'][$k]= pll__($field['choices'][$k]);
            }
        }
	}
	return $field;
}, 10, 1);

function filter_gettext_with_context( $translation, $text, $context, $domain ) { 
    return $translation; 
}
add_filter( 'gettext_with_context', 'filter_gettext_with_context', 10, 4 ); 

add_action( 'gform_after_create_post', 'delete_post_contact_expert', 10, 3 );
function delete_post_contact_expert( $post_id, $entry, $form ) {
    $post = get_post( $post_id );
    if(get_the_title($post_id)=='contact_expert'){
        wp_delete_post($post_id, true);
    }
}

add_filter( 'woocommerce_account_menu_items', 'rename_my_account_menu_items', 100, 1 );
function rename_my_account_menu_items( $items ) {
    $ordered_items = array();

    $neworder = array();
    $keys = array_keys($items);
    /*
    $i=0;
    foreach($items as $k => $item){
        echo $i.' - '.$item.'<br/>';
        $i++;
    }
    $items = array('customer-logout' => $items['customer-logout']) + $items;
    $items = array('subscriptions' => $items['subscriptions']) + $items;
    $items = array('chat' => $items['chat']) + $items;
    $items = array('favorites' => $items['favorites']) + $items;
    $items = array('my-wallet' => $items['my-wallet']) + $items;
    $items = array('edit-address' => $items['edit-address']) + $items;
    $items = array('orders' => $items['orders']) + $items;
    $items = array('service_request' => $items['service_request']) + $items;
    $items = array('mylistings' => $items['mylistings']) + $items;
    $items = array('dashboard' => $items['dashboard']) + $items;
    $items = array('edit-account' => $items['edit-account']) + $items;
    */
    $x = 9; $neworder+= array($keys[$x] => $items[$keys[$x]]);
    $x = 0; $neworder+= array($keys[$x] => $items[$keys[$x]]);
    $x = 1; $neworder+= array($keys[$x] => $items[$keys[$x]]);
    $x = 4; $neworder+= array($keys[$x] => $items[$keys[$x]]);
    $x = 5; $neworder+= array($keys[$x] => $items[$keys[$x]]);
    $x = 8; $neworder+= array($keys[$x] => $items[$keys[$x]]);
    $x = 10; $neworder+= array($keys[$x] => $items[$keys[$x]]);
    $x = 3; $neworder+= array($keys[$x] => $items[$keys[$x]]);
    $x = 2; $neworder+= array($keys[$x] => $items[$keys[$x]]);
    $x = 6; $neworder+= array($keys[$x] => $items[$keys[$x]]);
    $x = 11; $neworder+= array($keys[$x] => $items[$keys[$x]]);
    return $neworder;
} 


remove_action('personal_options_update', 'send_confirmation_on_profile_email');


/*
add_action( 'init', 'process_post' );
function process_post() {
	$args = array(  
		'post_type' => 'expert',
		'posts_per_page' => -1
	); 
	$fmam_posts = new WP_Query($args);
	while ($fmam_posts->have_posts()) : $fmam_posts->the_post();
		echo get_the_ID().'<br/>';
	//	$t= explode('"', explode('"text":"',get_the_content())[1])[0];
    //	echo $t;

        $t= get_the_content();

        //echo $t;

        //update_field( 'pitch', $t, get_the_ID() );

        
	//	$t= str_replace('u0022','"',$t);
	//	echo $t;
		$my_post = array(
			'ID'           => get_the_ID(),
			'post_content' => '',
		);
		wp_update_post( $my_post );
        

	//if ( ! add_post_meta( get_the_ID(), 'oldlayout', get_the_content(), true ) ) { 
	//		update_post_meta ( get_the_ID(), 'oldlayout',  get_the_content());
	//	 }
	endwhile;
}
*/

if(WEBSITE_SYSTEM == '1'){ 

    add_filter( 'gform_field_validation', 'custom_validation', 10, 4 );
    function custom_validation( $result, $value, $form, $field ) {
        if (strpos($field['cssClass'], 'valor_referencia') !== false) {
            if($value=='' || $value < 1){
                $result['is_valid'] = false;
                $result['message'] = ' '.pll__('Please enter a value for reference.');
            }
        }
        if (strpos($field['cssClass'], 'minimo') !== false) {
            if($value=='' || $value < 0){
                $result['is_valid'] = false;
                $result['message'] = pll__('Please enter a value for minimum.');
            }
        }
        if (strpos($field['cssClass'], 'maximo') !== false) {
            if($value=='' || $value < 0){
                $result['is_valid'] = false;
                $result['message'] = pll__('Please enter a value for maximum.');
            }
        }
        return $result;
    }

}

//Alterado Pelo Cleverson.
add_filter( 'pll_get_post_types', 'traduz_faq', 10, 2 );
 
function traduz_faq( $post_types, $is_settings ) {
    if ( $is_settings ) {
        // hides 'my_cpt' from the list of custom post types in Polylang settings
        unset( $post_types['sp_faq'] );
    } else {
        // enables language and translation management for 'my_cpt'
        $post_types['sp_faq'] = 'sp_faq';
    }
    return $post_types;
}

//Criado pelo Cleverson 
function cl_alerta($msg){

    echo '<script type="text/javascript">';
    echo ' alert("Conteudo : '.$msg.'")';  //not showing an alert box.
    echo '</script>';


}

/// in CODIGO DE TROCA DE SITE AQUI

function cl_objectToArray($object)
{ // convert object to array, required for get_sites() loop
    if ( !is_object($object) && !is_array($object))
        return $object ;
    return array_map( 'cl_objectToArray', (array) $object ) ;
}




function cl_troca_de_site($conteudo = null, $id=null){
    

    $subsites_object = get_sites() ;
    $subsites = cl_objectToArray($subsites_object) ;
    $subsites_copy = $subsites;	

    $subsites_exclude=array ('1','312','377','346','319','342','344','331','329','328','325','324','315','300');

    if ($subsites_exclude) {
        $subsites_copy = array();
        foreach ($subsites as $subsite) {
            $found_exclude = in_array($subsite['blog_id'], $subsites_exclude);
            // add if not excluded
            if (! $found_exclude) {
                $subsites_copy[] = $subsite;
                /* var_dump($subsite['blog_id']); */	// add site
            }
            
        }
    } 

    $subsites = $subsites_copy;

    $valores = array();
    foreach ($subsites as $subsite)
    
    {
        if ($conteudo == 'site_original'){
            $subsite_id = $id;
            switch_to_blog($subsite_id) ;           
                $valores[] = get_blog_details($subsite_id)->blogname;
            restore_current_blog();   
            } 
        
        if ($conteudo == 'paises'){
        $subsite_id = $subsite['blog_id'];
        switch_to_blog($subsite_id) ;           
            $valores[] = get_blog_details($subsite_id)->blogname;
        restore_current_blog();   
        } 
        if ($conteudo == 'id_pais'){
        $subsite_id = $subsite['blog_id'];
        switch_to_blog($subsite_id) ;           
            $valores[] = $subsite_id;
        restore_current_blog();   
        } 
        if ($conteudo=='nome_pais'){
            $valores[] = get_blog_details($subsite_id)->blogname;
        }
        if ($conteudo=='site_atual'){
            $valores[] = $subsite['blog_id'];
        }

        if ($conteudo=='id_form_referral'){
            $subsite_id = $subsite['blog_id']; 
            switch_to_blog($subsite_id) ;
                if ($id==$subsite_id){
                    $cl_shortcode_old = get_post_field('post_content', getIdByTemplate('single-counseling.php'));
                    $cl_shortcode_satizado = filter_var($cl_shortcode_old, FILTER_SANITIZE_STRING);
                    $replace = array("\"",",","'","’"," ","[","]",'"','="',' "','= ','&#34;',"t");
                    $cl_shortcode = str_replace( $replace,"",$cl_shortcode_satizado);
                    $cl_par = 'id=';
                    $cl_posicao = /* intval */(strpos($cl_shortcode, $cl_par));
                    $cl_id_old = substr($cl_shortcode,14,3);
                    $replace = array("\"",",","'","’"," ","[","]",'"','="',' "','= ','&#34;',"t","i","l");
                    $cl_id = str_replace( $replace,"",$cl_id_old);
                    $valores[] = $cl_id;
                }
            restore_current_blog();           
        }

        if ($conteudo=='cl_id_form'){

            $cl_shortcode_old = get_post_field('post_content', getIdByTemplate('single-counseling.php'));
            $cl_shortcode_satizado = filter_var($cl_shortcode_old, FILTER_SANITIZE_STRING);
            $replace = array("\"",",","'","’"," ","[","]",'"','="',' "','= ','&#34;',"t");
            $cl_shortcode = str_replace( $replace,"",$cl_shortcode_satizado);
            $cl_par = 'id=';
            $cl_posicao = /* intval */(strpos($cl_shortcode, $cl_par));
            $cl_id_old = substr($cl_shortcode,14,3);
            $replace = array("\"",",","'","’"," ","[","]",'"','="',' "','= ','&#34;',"t","i","l");
            $cl_id_form = str_replace( $replace,"",$cl_id_old);
            $valores[] = $cl_id_form;
        }


      
    }
    return $valores;
}

//ALTERAÇÕES NOVA HOME
//NOVO MENU DA HOME
if ( function_exists('register_sidebar') )
//Código para o widget.
register_sidebar(array(
'name' => __('Menu do Topo iDealBiz'),
'id' => 'area-de-widget-menu-idealbiz',
'description' => __( 'Area do menu Superior Fundo Azul do Site iDealBiz' ),
'before_widget' => '<li id="%1$s" class="%2$s">',
'after_widget' => '</li>',
'before_title' => '<h3>',
'after_title' => '</h3>',
));

function cria_area_de_menu_superior() {
    if ( is_active_sidebar( 'area-de-widget-menu-idealbiz' ) ) :
    dynamic_sidebar( 'area-de-widget-menu-idealbiz' );
    endif;
    }
add_action( 'mostra_menu_superior', 'cria_area_de_menu_superior' );

//Utlizar icones do WordPress:

function my_theme_styles() {
    wp_enqueue_style( 'dashicons' );
    }
 
 // ** * Ativar upload para arquivos de imagem webp. * /
function webp_upload_mimes($existing_mimes) {
    $existing_mimes ['webp'] = 'image / webp';
    return $existing_mimes;
}
add_filter ('mime_types', 'webp_upload_mimes');



function webp_is_displayable($result, $path) {
    if ($result === false) {
        $displayable_image_types = array( IMAGETYPE_WEBP );
        $info = @getimagesize( $path );

        if (empty($info)) {
            $result = false;
        } elseif (!in_array($info[2], $displayable_image_types)) {
            $result = false;
        } else {
            $result = true;
        }
    }

    return $result;
}
add_filter('file_is_displayable_image', 'webp_is_displayable', 10, 2);

// FIM ALTERAÇÕES NOVA HOME

//EXIBIR DASH ICONS

function ww_load_dashicons(){
    wp_enqueue_style('dashicons');
}
add_action('wp_enqueue_scripts', 'ww_load_dashicons');

//FORCE SHOW ADMIN BAR
function admin_bar(){

    if(is_user_logged_in() && current_user_can('administrator')){
      add_filter( 'show_admin_bar', '__return_true' , 1000 );
    }
  }

    add_action('init', 'admin_bar' );


  //Define o Caminho do Theme

  if (!defined("MY_THEME_DIR")) define("MY_THEME_DIR", trailingslashit( get_template_directory() ));

//Alterado Pelo Cleverson Exibe a o idioma do browser.
/* $acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
var_dump('Pais do Browser '.$acceptLanguage); */

/*
$allposts= get_posts( array('post_type'=>'leadmessages','numberposts'=>-1, 'post_status' => 'draft') );
foreach ($allposts as $eachpost) {
if(WEBSITE_SYSTEM == '1'){ 
    //var_dump(WEBSITE_SYSTEM);
    //wp_delete_post( $eachpost->ID, true );
    //echo $eachpost->ID.'<br/>';
}
}
*/


//Criado pelo Cleverson Teste Job
function cl_alerta_refrenciacao(){




   $cl_email = get_user_by('email', get_current_user_id());
    

/*     $length = 10;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        } */
          

    $to = 'customercare.pt@idealbiz.io';
    $subject = 'Teste Status';
    $headers = array('Content-Type: text/html; charset=UTF-8');

    
    $hi = '<sapn style="font-size: 25px !important;">'.$subject.'</span>';
    $m = $cl_email.' - Conteudo do email, teste aviso de referenciação em <br/>';

    $args = array(
            'post_per_page' => -1,
            'post_type' =>'service_request',
        
        );

        $query = new WP_Query($args);

        foreach($query as $k =>$y){
            $m .= '<br/>'.$k ;
        }

        var_dump($query);


    $emailHtml  = get_email_header();
    $emailHtml .= get_email_intro('', $m, $hi);
    $emailHtml .= get_email_footer();
    wp_mail($to,$subject,$emailHtml,$headers);
        /* cl_alerta('Site Modo Teste de Agendamento de serviço. Cod :'.$randomString.'user_conectado'.$cl_email) */;
}
//cl_alerta_refrenciacao();
//add_action( 'gancho_alerta_cl', 'cl_alerta_refrenciacao' );

function cl_checa_membro_true(){


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

    /* if ($cl_member_cat !=  false){
    echo '<p><a href="'.getLinkByTemplate('single-counseling.php').'?refer=1" class="nav-link" >Serviços</a></p>';
    } */


}

add_shortcode('cl_checa_membro_true', 'cl_checa_membro_true');



//FAZER CONSULTA PARA ALTERAR STATUS NA FICHA DA RECOMENDAÇÃO DE NEGÓCIO
function so_status_completed($order_id, $old_status, $new_status)
{
    
    $args= (array(  
        'post_type' => 'recommended_business',                 
        'posts_per_page' => 1,
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'rb_number_order',
                'value' => $order_id,
                'compare' => '='   // or if you want like then use 'compare' => 'LIKE'
                )
            )
        ) 
    );    
    $postData = new WP_Query($args);   
    $dados = (array) $postData;   
    $id_fichaAtualizarStatus = get_field('rb_post_origin',$dados['post']->ID);
    update_field('rb_status_order', $new_status, $id_fichaAtualizarStatus);

    //$order_total = $order->get_formatted_order_total();
    /* $order_total = $order->get_total();

    error_log(print_r('order total: ' . $order_total, true)); */
    /* die($dados); */

}
add_action('woocommerce_order_status_changed', 'so_status_completed', 10, 3);


//FAZER CONSULTA PARA ALTERAR STATUS NA FICHA DA RECOMENDAÇÃO DE NEGÓCIO
function sr_status_completed($order_id, $old_status, $new_status)
{
    
    $args= (array(  
        'post_type' => 'service_request',                 
        'posts_per_page' => 1,
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'rs_order_id',
                'value' => $order_id,
                'compare' => '='   // or if you want like then use 'compare' => 'LIKE'
                )
            )
        ) 
    );    
    $postData = new WP_Query($args);   
    $dados = (array) $postData;   
    $id_fichaAtualizarStatus = get_field('rs_id_request_type',$dados['post']->ID);
    update_field('rs_status_order', $new_status, $id_fichaAtualizarStatus);

    //$order_total = $order->get_formatted_order_total();
    /* $order_total = $order->get_total();

    error_log(print_r('order total: ' . $order_total, true)); */
    //die($dados);

}
add_action('woocommerce_order_status_changed', 'sr_status_completed', 10, 3);





function getLinkTemplateFistPage($page_php) {
    //NPMM - Pega o LINK da primeira pagina do referido tempalte.
    $archive_page = get_pages(
        array(
            'meta_key' => '_wp_page_template',
            'meta_value' => $page_php
        )
    );
    $archive_id = $archive_page[0]->ID;
    $cl_link_page = get_permalink( $archive_id );
    return $cl_link_page;
}


function member_recomemded_business(){
    //NPMM - Verifica se membro está habilitado participar do eco sistema de recomendação
        $current_user = wp_get_current_user();

        
        $id_expert = isExpert($current_user->ID); 

        $check_member = get_field('rb_member_of_recommended_business',$id_expert[0]->ID);


        if($check_member == true){           
            $cl_cacheLink = bin2hex(openssl_random_pseudo_bytes(16));
            session_start();            
            if ($_SESSION['cl_cacheLink'] === NULL){
            $_SESSION['cl_cacheLink'] = $cl_cacheLink;          
            }
        }else{
            $_SESSION['cl_cacheLink'] = NULL;           
        }
}
add_action('wp_head', 'member_recomemded_business');


function listingFilterHeadRecomemdedBusines($showHead){
    //NPMM - Cria cabecalho e minha conta para filtrar os anuncios marcados como sendo do sistema de recomedação de negócios.
    if(isset($_GET['recommended'])==1){
        $cl_link = $_SESSION['cl_cacheLink'];
        $cl_page = getLinkTemplateFistPage('page-listings.php'); 
        if (isset($cl_link)!= NULL){
            if($showHead == true){
                echo '<span class="container text-center m-b-30"><h1>'.__('_str Business opportunity','idealbiz').'</h1><h2>'.__('_str Exclusive to business recommenders','idealbiz').'</h2></span>';
            }

        }
    }    
}
add_action('head_lsitingRecomendaveis','listingFilterHeadRecomemdedBusines',10,1);


function viewButtomSubmitListingRecomemdedBusiness($cl_acao='',$cl_showButtons=''){
    if(isset($_GET['recommended'])==1 || $cl_showButtons === true){
        $cl_tituloBotao = __('_str InsertNewBusiness','idealbiz');
        $cl_checaSESS = $_SESSION['cl_cacheLink'];    
            if(isset($cl_checaSESS)){   
                echo '<a href="'.getLinkByTemplate('submit-listing.php').'"><div class="bota_quadrado stroke dropshadow woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--dashboard is-active swiper-slide-next"><span class="dashicons dashicons-table-col-before"></span><p>'.$cl_tituloBotao.'</p></div></a>';
            }
    }    
}
add_action('botao_lsitingRecomendaveis','viewButtomSubmitListingRecomemdedBusiness',10,2);



function viewButtomRecomemdedBusiness($cl_acao,$cl_showButtons){
    if(isset($_GET['recommended'])==1 || $cl_showButtons === true){    
    $cl_printBtn = '';
        if($cl_acao==='received'){
            $cl_tituloBotao = __('_str View Recommendations Received','idealbiz');
            $cl_dashicon = '<span class="dashicons dashicons-download"></span>';
            $color_buttom = '#28a745';
            $cl_printBtn = '1';
        }
        if($cl_acao==='sent'){
            $cl_tituloBotao = __('_str View Recommendations Sent','idealbiz');
            $color_buttom = '#007bff';
            $cl_dashicon = '<span class="dashicons dashicons-upload"></span>';
            $cl_printBtn = '1';
        } 
        if($cl_printBtn==='1'){  
            echo '<a href="'.getLinkByTemplate('RecommendedBusiness.php').'?'.$cl_acao.'=1"><div class="bota_quadrado stroke dropshadow woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--dashboard is-active swiper-slide-next">'.$cl_dashicon.'<p>'.$cl_tituloBotao.'</p></div></a>';
        }
    }    
}
add_action('botao_lsitingRecomendaveis','viewButtomRecomemdedBusiness',10,2);


function listingFilterButtonRecomemdedBusines($cl_acao=null,$cl_showButtons){
    //NPMM - Cria botão e minha conta para filtrar os anuncios marcados como sendo do sistema de recomedação de negócios.
    if(isset($_GET['recommended'])==1 || $cl_showButtons ==true){
        
        $cl_link = $_SESSION['cl_cacheLink'];
        $cl_page = getLinkTemplateFistPage('page-listings.php'); 
        $cl_checaSESS = $_SESSION['cl_cacheLink'];    
        if(isset($cl_checaSESS)){   
           
            echo '<a href="'.$cl_page.'?rb='.$cl_link.'""><div class="bota_quadrado stroke dropshadow woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--dashboard is-active swiper-slide-next"> <span class="dashicons dashicons-filter"></span><p>'.__('_srt View recommended business opportunities','idealbiz').'</p></div></a>';
            
        }
    }    
}

add_action('botao_lsitingRecomendaveis','listingFilterButtonRecomemdedBusines',10,2);


function buttomRecomemded($cl_acao=null,$cl_showButtons){
    if(isset($_GET['recommended'])==1 || $cl_showButtons ==true){    
        $cl_page_Recommended = getLinkByTemplate('RecommendedBusiness.php');
        $cl_link = '<a href="'.$cl_page_Recommended.'?received=1"><div class="bota_quadrado stroke dropshadow woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--dashboard is-active swiper-slide-next"><span class="dashicons dashicons-list-view"></span><p>'.__('_str Report Recommended Business').'</p></div></a>';
        $cl_urlAtual = get_permalink(); 

        if($cl_urlAtual != $cl_page_Recommended){
            $cl_checaSESS = $_SESSION['cl_cacheLink'];    
            if(isset($cl_checaSESS)){   
                if (!isset($_GET['sent'])){
                    if (!isset($_GET['received'])){
                        echo $cl_link;
                    }
                }
            }
        }
    }     
    
}
add_action('botao_lsitingRecomendaveis','buttomRecomemded',10,2);




function my_add_product_to_cart($rb_id_porduct_coin) {
    //NPMM _ Adiciona produto automaticamnte no carrinho
    if ( ! is_admin() ) {
      $product_id = $rb_id_porduct_coin; //your predeterminate product id
      $found = false;
      //check if product is not already in cart
      if ( sizeof( WC()->cart->get_cart() ) > 0 ) {
        foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
          $_product = $values['data'];
          if ( $_product->get_id() == $product_id )
            $found = true;
        }
        // if product not found, add it
        if ( ! $found )
          WC()->cart->add_to_cart( $product_id );
      } else {
        // if no products in cart, add it
        WC()->cart->add_to_cart( $product_id );
      }
    }
  }
  add_action( 'auto_add_prod_cart', 'my_add_product_to_cart',10,1 );


//NPMM - Calculo Comissão  será paga por recomendação de negócios.

function calculaComissaoRecomendacao($cl_type_commission, $cl_id_meber, $cl_preco,$cl_comissao){

    $cl_idb_tax = (float)get_field('rb_idb_tax_recommended_business',$cl_id_meber);

    if($cl_type_commission ==='percentage_value'){
        
        $cl_valor_comissao_bruta = (float)($cl_preco/100)*$cl_comissao; //2000
        $cl_valor_comissao_idb = ($cl_valor_comissao_bruta/100)*$cl_idb_tax;//200
        $cl_comissao_membro = (int)$cl_valor_comissao_bruta-(int)$cl_valor_comissao_idb; //1000-200

    }else{

        $cl_valor_comissao_bruta = (float)$cl_comissao; //2000
        $cl_valor_comissao_idb = ($cl_valor_comissao_bruta/100)*$cl_idb_tax;//200
        $cl_comissao_membro = (int)$cl_valor_comissao_bruta-(int)$cl_valor_comissao_idb; //1000-200 

    }
    
    return [$cl_idb_tax, $cl_valor_comissao_idb, $cl_comissao_membro,$cl_valor_comissao_bruta,$cl_comissao];



    
}

function cl_voltar($qnt=null,$cl_title=null){
    if($qnt == null){
        $qtn = -1;
    }

    if($cl_title == null){
        $cl_title =__('_str Back', 'idealbiz');
    }

    echo '<div class="container"><a href="javascript: history.go('.$qnt.')"><i class="dashicons-before dashicons-undo"></i><span class="font-weight-bold m-l-5">'.__($cl_title, 'idealbiz').'</span></a></div>';
}
add_action('goBack','cl_voltar',10,2);



//NPMM - Verifica erro do plugin.
/* define('temp_file', ABSPATH.'/_temp_out.txt' );

add_action("activated_plugin", "activation_handler1");
function activation_handler1(){
    $cont = ob_get_contents();
    if(!empty($cont)) file_put_contents(temp_file, $cont );
}

add_action( "pre_current_active_plugins", "pre_output1" );
function pre_output1($action){
    if(is_admin() && file_exists(temp_file))
    {
        $cont= file_get_contents(temp_file);
        if(!empty($cont))
        {
            echo '<div class="error"> Error Message:' . $cont . '</div>';
            @unlink(temp_file);
        }
    }
} */
//NPMM - Codigo Abixo Cria novas opções no ACF para HAbilitar e desabilitar comapos no BackOffice
//FONTE : https://support.advancedcustomfields.com/forums/topic/read-only-field-2/
add_action('acf/render_field_settings/type=text', 'add_readonly_and_disabled_to_text_field');
  function add_readonly_and_disabled_to_text_field($field) {
    acf_render_field_setting( $field, array(
      'label'      => __('Read Only?','acf'),
      'instructions'  => '',
      'type'      => 'radio',
      'name'      => 'readonly',
      'choices'    => array(
        0        => __("No",'acf'),
        1        => __("Yes",'acf'),
      ),
      'layout'  =>  'horizontal',
    ));
    acf_render_field_setting( $field, array(
      'label'      => __('Disabled?','acf'),
      'instructions'  => '',
      'type'      => 'radio',
      'name'      => 'disabled',
      'choices'    => array(
        0        => __("No",'acf'),
        1        => __("Yes",'acf'),   
      ),
      'layout'  =>  'horizontal',
    ));
  }
//NPMM - Adiciona Seletor de Somente Admin no Campo ACF   
//FONTE : https://www.advancedcustomfields.com/resources/adding-custom-settings-fields/
  function my_admin_only_render_field_settings( $field ) {
    acf_render_field_setting( $field, array(
        'label'        => __( 'Admin Only?', 'my-textdomain' ),
        'instructions' => '',
        'name'         => 'admin_only',
        'type'         => 'true_false',
        'ui'           => 1,
    ), true ); // If adding a setting globally, you MUST pass true as the third parameter!
}
add_action( 'acf/render_field_settings', 'my_admin_only_render_field_settings' );
//NPMM - Verificar Se o campo ACF é Admin para exibir Sim ou Não 
//FONTE : https://www.advancedcustomfields.com/resources/adding-custom-settings-fields/
function my_admin_only_prepare_field( $field ) {
    //
    // Bail early if no 'admin_only' setting or if set to false.
    if ( empty( $field['admin_only'] ) ) {
        return $field;
    }

    // Prevent field from displaying if current user is not an admin.
    if ( ! current_user_can( 'manage_options' ) ) {
        return false;
    }

    // Return the original field otherwise.
    return $field;
}
add_filter( 'acf/prepare_field', 'my_admin_only_prepare_field' );

function registerViewLead($cl_id_view_lead){



    if ($_SESSION['cl_id_view_lead']!==$cl_id_view_lead){

        unset($_SESSION['cl_id_view_lead']);
        session_start();       
        $_SESSION['cl_id_view_lead'] = $cl_id_view_lead;          
        

        $member = isExpert(get_current_user_id());
        
        $id_member = $member[0]->ID;

        $date_by_server = current_time( 'd-m-y H:i:s');


        $email_member = $member[0]->meta_value;
        $display_member = $member[0]->post_title;
        $profile_member = $member[0]->guid;

        $row = array(
            'sr_id_member_saw_lead'=> $id_member,
            'sr_date_saw_lead'=> $date_by_server,
            'email_member_saw_lead'=>$email_member,
            'sr_member_view_lead' =>$display_member,
            'sr_profile_member_saw_lead'=> $profile_member
        );

        //echo 'TEXTO TEMPORÁRIO SERVE PARA CLEVERSON FAZER TESTES';

        
        add_row('sr_view_lead',$row,$cl_id_view_lead);


        $view ='';
		$cl_n_view = 0;
		$cl_sr_view_lead = get_field('sr_view_lead',$cl_id_view_lead);
		foreach($cl_sr_view_lead as $viewLead){
			
				$cl_n_view++;				
				$view .= '<p style="border-bottom:1px solid #cccccc;">'.$cl_n_view.'→'.$viewLead['sr_member_view_lead'].'→'.$viewLead['sr_date_saw_lead'].'</p>';

		}

        $customer_care = 'customercare.pt@idealbiz.io'; //Somente Customercare
        $to = $customer_care;
        $subject = __('_str A Lead was Viewed','idealbizio').' - '.$cl_id_view_lead;
        $headers = array('Content-Type: text/html; charset=UTF-8');
    
        
        $hi = '<sapn style="font-size: 25px !important;">'.$subject.'</span>';
        $m = __('Hello','idealbizio').' '.__('_str Customercare','idealbizio').'<br/><br/>';
        $m .=  '<b>'.__('_str Service Resquest ID','idealbizio').'</b> - '.$cl_id_view_lead.'<br/><br/>';
        $m .=  '<b>'.__('_str Member Saw Lead','idealbizio').'</b><br/> - '.'ID'.$id_member.' - '.$display_member.' - '.$email_member.'<br/><br/>';
        $m .=  '<b>'.__('_str Profiler','idealbizio').'</b> - '.$profile_member.'<br/><br/><br/>';
        $m .= '<b>'.__('_str All views of this service request','idealbiz').'</b><br/><br/>'.$view;
        $m .= '<br/><br/>'.__('The iDealBiz Team','idealbizio');
        
    
    
        $emailHtml  = get_email_header();
        $emailHtml .= get_email_intro('', $m, $hi);
        $emailHtml .= get_email_footer();
        //DESABILITADO O ENVIO DESTE EMAIL
        //wp_mail($to,$subject,$emailHtml,$headers);

    }
                     
}

function opportunityRegisterViewLead($cl_id_view_lead_opportunity){



    if ($_SESSION['cl_id_view_lead_opportunity']!==$cl_id_view_lead_opportunity){
    
        unset($_SESSION['cl_id_view_lead_opportunity']);
        session_start();       
        $_SESSION['cl_id_view_lead_opportunity'] = $cl_id_view_lead_opportunity;          
        
    
        $member = isExpert(get_current_user_id());
        
        $id_member = $member[0]->ID;
    
        $date_by_server = current_time( 'd-m-y H:i:s');
    
    
        $email_member = $member[0]->meta_value;
        $display_member = $member[0]->post_title;
        $profile_member = $member[0]->guid;
    
        $row = array(
            'rb_id_member_saw_lead'=> $id_member,
            'rb_date_saw_lead'=> $date_by_server,
            'rb_email_member_saw_lead'=>$email_member,
            'rb_member_view_lead' =>$display_member,
            'rb_profile_member_saw_lead'=> $profile_member
        );
    
        echo 'TEXTO TEMPORÁRIO SERVE PARA CLEVERSON FAZER TESTES EM OPORTUNIDADES';
    
        
        add_row('rb_view_lead',$row,$cl_id_view_lead_opportunity);
    
    
        $view ='';
        $cl_n_view = 0;
        $cl_rb_view_lead = get_field('rb_view_lead',$cl_id_view_lead_opportunity);
        foreach($cl_rb_view_lead as $viewLead){
            
                $cl_n_view++;				
                $view .= '<p style="border-bottom:1px solid #cccccc;">'.$cl_n_view.'→'.$viewLead['rb_member_view_lead'].'→'.$viewLead['rb_date_saw_lead'].'</p>';
    
        }
    
        $customer_care = 'customercare.pt@idealbiz.io'; //Somente Customercare
        $to = $customer_care;
        $subject = __('_str A Lead was Viewed in Opportunity','idealbizio').' - '.$cl_id_view_lead_opportunity;
        $headers = array('Content-Type: text/html; charset=UTF-8');
    
        
        $hi = '<sapn style="font-size: 25px !important;">'.$subject.'</span>';
        $m = __('Hello','idealbizio').' '.__('_str Customercare','idealbizio').'<br/><br/>';
        $m .=  '<b>'.__('_str Service Resquest ID','idealbizio').'</b> - '.$cl_id_view_lead_opportunity.'<br/><br/>';
        $m .=  '<b>'.__('_str Member Saw Lead','idealbizio').'</b><br/> - '.'ID'.$id_member.' - '.$display_member.' - '.$email_member.'<br/><br/>';
        $m .=  '<b>'.__('_str Profiler','idealbizio').'</b> - '.$profile_member.'<br/><br/><br/>';
        $m .= '<b>'.__('_str All views of this service request','idealbiz').'</b><br/><br/>'.$view;
        $m .= '<br/><br/>'.__('The iDealBiz Team','idealbizio');
        
    
    
        $emailHtml  = get_email_header();
        $emailHtml .= get_email_intro('', $m, $hi);
        $emailHtml .= get_email_footer();
        //DESABILITADO O ENVIO DESTE EMAIL
        //wp_mail($to,$subject,$emailHtml,$headers);
    
    }
                     
}

function consultLeadModeServieceRequest($id_member,$cl_onlyMode=null){

    $cl_expert = isExpert();
    $cl_expertDsplayName = $cl_expert[0]->post_title;
    $cl_sr_pay_lead_mode = get_field('sr_pay_lead_mode',$id_member);

    if($cl_sr_pay_lead_mode === NULL){
        $cl_sr_pay_lead_mode = ['value'=>'sr_pay_before','label'=>'Pay Before'];
    }

    if($cl_sr_pay_lead_mode['value']==='sr_pay_before'){
    $mode = __('_str Pay Before','idealbiz');
    }
    if($cl_sr_pay_lead_mode['value']==='sr_pay_later'){
    $mode = __('_str Pay Later','idealbiz');
    }    
      
    if($cl_sr_pay_lead_mode['value']==='sr_not_pay'){
    $mode = __('_str No Pay','idealbiz');
    }

    $to = __('_str To','idealbiz');


    if($cl_onlyMode == null){
    echo
    '<style>
        .cl_Msg{
            color:#005882;
            font-size: 1.5em;
        }
        .cl_icon{
            padding-top:5px;
        }
    </style>';

    $msg = '<div class="text-left cl_Msg m-b-10"><span class="dashicons dashicons-yes-alt cl_icon"></span>'.__('_str Mode','idealbiz').' <b>'.$mode.'</b> '.$to.' <b>'.$cl_expertDsplayName.'</b></div>';
    
    echo $msg;


    echo '<pre style="color:red; class="text-left">';
    //echo 'Nota ultimo movimento order in on-old';
    //print_r($cl_expert);
    echo '</pre>';
    }

    if($cl_onlyMode == true){
        return $mode;
    }


}

function consultLeadModeRecomendation($id_member,$cl_onlyMode=null){

    $cl_expert = isExpert();
    $cl_expertDsplayName = $cl_expert[0]->post_title;
    $cl_rb_pay_lead_mode = get_field('rb_pay_lead_mode',$id_member);

    if($cl_rb_pay_lead_mode === NULL){
        $cl_rb_pay_lead_mode = ['value'=>'rb_pay_before','label'=>'Pay Before'];
    }

    if($cl_rb_pay_lead_mode['value']==='rb_pay_before'){
    $mode = __('_str Pay Before','idealbiz');
    }
    if($cl_rb_pay_lead_mode['value']==='rb_pay_later'){
    $mode = __('_str Pay Later','idealbiz');
    }    
      
    if($cl_rb_pay_lead_mode['value']==='rb_not_pay'){
    $mode = __('_str No Pay','idealbiz');
    }

    $to = __('_str To','idealbiz');


    if($cl_onlyMode == null){
    echo
    '<style>
        .cl_Msg{
            color:#005882;
            font-size: 1.5em;
        }
        .cl_icon{
            padding-top:5px;
        }
    </style>';

    $msg = '<div class="text-left cl_Msg m-b-10"><span class="dashicons dashicons-yes-alt cl_icon"></span>'.__('_str Mode','idealbiz').' <b>'.$mode.'</b> '.$to.' <b>'.$cl_expertDsplayName.'</b></div>';
    
    echo $msg;

    //var_dump(isExpert());

    echo '<pre style="color:red; class="text-left">';
    //echo 'Nota ultimo movimento order in on-old';
    //print_r($cl_expert);
    echo '</pre>';
    }

    if($cl_onlyMode == true){
        return $mode;
    }

}

//NPMM - Dados que vem da caixa de rejeição das Oportunidade.
if(isset($_POST['reject_opportunity'])){
    $current_user = wp_get_current_user();
    $id_member = isExpert($current_user->ID);

    if(get_field('rb_id_member_indicate',$_POST['proposal_id'])->ID == $id_member->ID ){

        $cl_referencia_servico = $_POST['proposal_id'];
        
            
            $cl_member_id = $id_member;
            $cl_member_data = get_userdata($cl_member_id);
            $cl_member_f_name = $cl_member_data->first_name;
            $cl_member_l_name = $cl_member_data->last_name;
            $customer = get_field('customer',$_POST['proposal_id']);
            
            $cl_idMemberReject = get_field('rb_id_owner_of_listing', $cl_referencia_servico);
            $cl_NameMemberReject = get_field('rb_name_owner_of_listng', $cl_referencia_servico);
            $cl_emailMemberReject = get_field('rb_email_owner_of_listing', $cl_referencia_servico);

            $cl_idMemberIndicate = get_field('rb_id_member_indicate', $cl_referencia_servico);
            $cl_NameMemberIncate = get_field('rb_name_member_indicate', $cl_referencia_servico);
            $cl_emailMemberIndicate = get_field('rb_email_member_indicate', $cl_referencia_servico);
            
            $reason = ' '.'<span class="small">'.__('_str Orginal Member','idealbiz').' :<br/> '.$cl_idMemberReject.' - '.$cl_NameMemberReject.' - '.$cl_emailMemberReject.'<br/><br/>'.__('_str Reason','idealbiz').': <br/>'.$_POST['reason'].'</span>';

            $reasonToMember ='<br/><br/>'.__('_str Reason','idealbiz').': <br/>'.$_POST['reason'].'</span>';

            $date_by_server = current_time( 'd-m-y H:i:s');
            
            $cl_id_Costumercare = get_user_by( 'email', get_field('costumer_care_email', 'option') )->ID;
            $cl_display_name_Costumercare  = get_user_by( 'email', get_field('costumer_care_email', 'option') )->display_name;
            $cl_emailCoustumerCare = get_field('costumer_care_email', 'option');



            update_field( 'rb_reject_reason', $reason, $cl_referencia_servico);
            update_field( 'rb_reject_date', $date_by_server, $cl_referencia_servico);


            update_field('rb_id_owner_of_listing',$cl_id_Costumercare,$cl_referencia_servico);
            update_field('rb_name_owner_of_listng',$cl_display_name_Costumercare,$cl_referencia_servico);
            update_field('rb_email_owner_of_listing', $cl_emailCoustumerCare,$cl_referencia_servico);



                
                update_field( 'consultant', get_user_by( 'email', get_field('costumer_care_email', 'option') )->ID, $_POST['proposal_id'] );

                //Email de rejeição de Oportunidade para Customer Care
                $subject = __( '_str New Recommended Opportunity rejected!', 'idealbiz' );
                $hi = __('_str Recommended Opportunity Rejected', 'idealbiz' );
                $message  = __('Hello','idealbiz').' '.__('_str Customer','idealbizio').'<br/><br/>';
                $message .= '<br/><br/>'.__('_str One Recommended opportunity', 'idealbiz' ).' "'.get_the_title($_POST['proposal_id']).'" '.__('_str has been rejected and now it is assigned to Customer Care','idealbiz').'.<br/>';
                $message .= '<br/><br/>'.$reason.'<br/><br/>';               
                $message .= '<br/><br/>'.__('The iDealBiz Team','idealbizio');
                $message .= '<br/><span style="color:#ffffff;font-size:0.5em;">FNC1</span>';

               $emailHtml  = get_email_header(pll__('Customer Care'), ''); 
                $emailHtml .= get_email_intro(pll__('Customer Care'), $message, $hi);
                $emailHtml .= get_email_footer();
                $headers = array('Content-Type: text/html; charset=UTF-8');
                wp_mail($cl_emailCoustumerCare,$subject,$emailHtml,$headers); 

                //Email de rejeição de Oportunidade para Membro
                $subject = __( '_str New Recommended Opportunity rejected!', 'idealbiz' );
                $hi = __('_str Recommended Opportunity Rejected', 'idealbiz' );
                $message  = __('Hello','idealbiz').' '.$cl_NameMemberIncate.'<br/><br/>';
                $message .= '<br/><br/>'.__('_str One Recommended opportunity', 'idealbiz' ).' "'.get_the_title($_POST['proposal_id']).'" '.__('_str has been rejected and now it is assigned to Customer Care','idealbiz').'.';
                $message .= '<br/>'.$reasonToMember.'<br/>';               
                $message .= '<br/><br/>'.__('The iDealBiz Team','idealbizio');
                $message .= '<br/><span style="color:#ffffff;font-size:0.5em;">FNC0</span>';
                
                $emailHtml  = get_email_header($customer->ID, ''); 
                $emailHtml .= get_email_intro($customer->ID, $message, $hi);
                $emailHtml .= get_email_footer();
                $headers = array('Content-Type: text/html; charset=UTF-8');
                wp_mail($cl_emailMemberIndicate ,$subject,$emailHtml,$headers);

    }
            
    
}


add_action('check_admin_referer', 'scratchcode_logout_without_confirm', 10, 2);
function scratchcode_logout_without_confirm($action, $result){
    /**
    * Allow logout without confirmation
    */
    if ($action == "log-out" && !isset($_GET['_wpnonce'])):
        $redirectUrl = 'https://'.get_current_site(); 
        wp_redirect( str_replace( '&', '&', wp_logout_url( $redirectUrl.'?logout=true' ) ) );
        exit;
    endif;
}



function checkisMember(){
            $cl_ismember = false;
            $current_user = wp_get_current_user();
            
            $cl_expert = isExpert($current_user->ID);
            $cl_expert_id = $cl_expert[0]->ID;               
         
            $cl_rb_member_of_recommended_business = get_field('rb_member_of_recommended_business', $cl_expert_id);
            $member_category = get_field('member_category_store', $cl_expert_id);

            if ($cl_rb_member_of_recommended_business == true){
                $cl_ismember = true;
            }
            if($member_category != Null){
                $cl_ismember = true;
            }
            return $cl_ismember;

}

function meberPITModal($srid)
{
    



    $args = array(

        'posts_per_page' => -1,
        'post_type' => 'expert',
        'post_status' => 'publish',
       
    );
    
    //$experts = new WP_Query($args);
    $the_query = new WP_Query( $args );

        $post_id = get_the_ID();
        $image = get_field('foto')['sizes']['full'];
        $permalink = get_permalink();
        $title = get_the_title();
        $is_certified = get_field('listing_certification_status') == 'certification_finished';
        $cl_youtube = get_field('youtube_of_member');
        $badge = get_template_directory_uri() . '/assets/img/badge.png';
        $expert_schedule_available = get_field('youtube_of_member', $post_id);
        $experts_professional_experience = get_field('experts_professional_experience', $post_id);

        $is_certified = get_field('listing_certification_status') == 'certification_finished';
        $cl_company_associate = get_field('company_associate');
        
        $cl_lable_service = __('_str service','idealbiz').' : ';
        $cl_lable_opportunuty = __('_str Opportunity','idealbiz').' : ';
        $cl_lable_company = __('_str Company','idealbiz').' : ';
        
        $cl_sr_pay_lead_mode = get_field('sr_pay_lead_mode');
        if ($cl_sr_pay_lead_mode === NULL) {
            $cl_sr_pay_lead_mode = ['value' => 'sr_pay_before', 'label' => 'Pay Before'];
        }
        
        $cl_rb_pay_lead_mode = get_field('rb_pay_lead_mode');
        if($cl_rb_pay_lead_mode === NULL){
            $cl_rb_pay_lead_mode = ['value'=>'rb_pay_before','label'=>'Pay Before'];
        
        }    
    $cl_linkPitch = __('_str know more about','idealbiz').' '. $title;
    $idealbiz_logo   = get_option( 'woocommerce_email_header_image' );

    $cl_logo = '<div class="cl_pop_logo" style="text-align:center;">
    <img src="'.$idealbiz_logo.'" alt="Logo" width="" height="100">
    </div>
    <div class="cl_img_mobile w-100px h-100px b-r o-hidden no-decoration cl_mobile_show">
    <img class="w-100 h-100 object-cover" src="' . get_field('foto')['sizes']['medium'] . '">
</div>
    ';



    $cl_pitch = '<div class="d-flex center-content" style="margin-left:20px;">
            <div class="w-100px h-100px b-r o-hidden no-decoration cl_mobile_hidden">
                <img class="w-100 h-100 object-cover" src="' . get_field('foto')['sizes']['medium'] . '">
            </div>
                <div style="margin-left:20px;">

                        <div class="d-flex flex-row">
                            <div>
                                <h3 class="font-weight-semi-bold">
                                    <span class="dashicons dashicons-businessman" style="font-size:1.3em;">
                                    </span>
                                </h3>
                            </div>
                            <div>
                                <h3 class="cl_pop_title" style="margin-left:10px;">' . $title . '
                                </h3>
                            </div>
                        </div>
                        <div class="">
                            <span class=" dashicons dashicons-yes" style="font-size:1.9em;">
                            </span><h7 class="cl_h7">'.$cl_lable_opportunuty.$cl_rb_pay_lead_mode['label'].'
                        </h7></div>
                        <div class="">
                            <span class=" dashicons dashicons-yes" style="font-size:1.9em;">
                            </span><h7 class="cl_h7">'.$cl_lable_service.$cl_sr_pay_lead_mode['label'].'
                        </h7></div>
                        <div >
                       '.cl_services_member_list().'
                        </div>
                </div>
        </div>
    ';
    $cl_pitch .= '<div class=" m-t-10 calc-100-120 h-100 d-flex justify-content-between flex-column ">
    <div class="p-t-10 text-center" style="border:1px solid #cccccc;background-color:#f1f1f1;"><h3 class="cl_h3">'.__('_str Short Presentation', 'idealbiz').'</h3></div>

        <div class="cl_pop_pitch">
        <div class="font-weight-semi-bold">' . get_field('pitch', $post_id) . '
        </div>
        </div>
    </div>';

    $cl_pitch .= '<div class="m-t-10 calc-100-120 h-100 d-flex justify-content-between flex-column ">


        <div style="border:1px solid #cccccc;border-radius:0px;padding:15px;">
        <div class="font-weight-bold text-center cl_h3"><a class="btn-blue" href="'. get_permalink( $post_id ).'"><b>' .$cl_linkPitch.'</b></a>
        </div>
        </div>
    </div>';


    $message=$cl_logo.$cl_pitch;
    $current_user= wp_get_current_user();


    $content = '<div class="popWrapper" id="post-'.$srid.'" style="z-index: 999999999 !important;">
        <div class="popWrapper_screen" style="z-index: 999999999 !important;"></div>
        <div class="iziModal formPopUp col-md-11 col-sm-11 col-xs-11" style="overflow-y: scroll;max-height: 80vh;">
            <div class="iziModal-wrap" style="height: auto;">
                <div class="iziModal-content" style="padding: 0px;">
                    <div class="content generic-form p-b-20"> 
                        <button data-izimodal-close="" class="icon-close popUpForm" href="#post-'.$srid.'"></button>
                        <div class="clear"></div>
                        <div class="acf-form" style="text-align:left; color: #343434; font-size:1.2em; line-height: 1.8em; font-weight: 500;">
                        '.$message.'
                        </div>
                    </div>    
                </div>
            </div>    
        </div>
    </div>';
    // always return
    return $content;
}

add_action( 'wp_ajax_single_counseling_search_members', 'single_counseling_search_members' );
function single_counseling_search_members() {
    //check_ajax_referer( 'single_counseling_search_members' );

    echo get_template_part('elements/member-search/member-search', null, array(
        'service_category' => $_POST['service_category'],
        'amount'   => $_POST['amount'],
        'location' => $_POST['location']
    ));

    wp_die();
}