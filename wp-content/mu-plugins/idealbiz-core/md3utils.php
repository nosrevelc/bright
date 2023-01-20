<?php



function _ms_config_wp_siteurl( $url = '' ) {
  if (is_multisite()):
      global $blog_id, $current_site;
      $cur_blog_id = defined('BLOG_ID_CURRENT_SITE')? BLOG_ID_CURRENT_SITE : 1;
      $key = ($blog_id!=$cur_blog_id)? $blog_id.'_' : '';
      $constant = 'WP_'.$key.'SITEURL';
      if ( defined( $constant ) )
          return untrailingslashit( constant($constant) );
  endif;
  return $url;
}
add_filter( 'option_siteurl', '_ms_config_wp_siteurl' );

function _ms_config_wp_home( $url = '' ) {
  if (is_multisite()):
      global $blog_id;
      $cur_blog_id = defined('BLOG_ID_CURRENT_SITE')? BLOG_ID_CURRENT_SITE : 1;
      $key = ($blog_id!=$cur_blog_id)? $blog_id.'_' : '';
      $constant = 'WP_'.$key.'HOME';
      if ( defined( $constant ) )
          return untrailingslashit( constant($constant) );
  endif;
  return $url;
}
add_filter( 'option_home',    '_ms_config_wp_home'    );



function add_theme_scripts() {
    wp_enqueue_style( 'style', get_stylesheet_uri() );

    /* bootstrap */
    //wp_enqueue_style('bootstrap', plugins_url( '/assets/css/bootstrap.min.css', dirname(__FILE__) ),'','','all');
    //wp_enqueue_script( 'bootstrap', plugins_url( '/assets/js/bootstrap.js', dirname(__FILE__) ),'','',true);
  
    /* select2 */
    wp_enqueue_style('select2', plugins_url( '/assets/css/select2.css', dirname(__FILE__) ),'','','all');
    wp_enqueue_script( 'select2', plugins_url( '/assets/js/select2.js', dirname(__FILE__) ),'','',true);
    wp_enqueue_script( 'select2lang', plugins_url( '/assets/i18n/'.pll_current_language().'.js', dirname(__FILE__) ),'','',true);

    /* icofont */
    wp_enqueue_style('izimodal', plugins_url( '/idealbiz-core/assets/icofont/icofont.min.css', dirname(__FILE__) ),'','','all');

    wp_enqueue_script('pofw_product_options', plugins_url( 'product-options-for-woocommerce/view/frontend/web/product/main.js'), array('jquery', 'jquery-ui-widget'));
    wp_enqueue_style('pofw_product_options', plugins_url( 'product-options-for-woocommerce/view/frontend/web/product/main.css'));	
    




    /* custom */
    //wp_enqueue_style('md3utils', plugins_url( 'md3utils/assets/css/front.css', dirname(__FILE__) ),'','','all');

      if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
      }
  }
  add_action( 'wp_enqueue_scripts', 'add_theme_scripts' );


  function register_my_menus() {
    register_nav_menus(
      array(
        'header-menu' => __( 'Header Menu' ) ,
        'footer-menu' => __( 'Footer Menu' ),
        'jobs-menu' => __( 'Jobs Menu' )
       )
     );
   }
   add_action( 'init', 'register_my_menus' );

add_filter( 'nav_menu_link_attributes', 'cfw_add_data_atts_to_nav', 10, 4 );
function cfw_add_data_atts_to_nav( $atts, $item, $args )
{
  $value = $item->classes[current(preg_grep('/^hex-/', array_keys($item->classes)))];
  if (strpos($value, 'hex-') !== false) {
    $atts['data-iconbg'] = ''.str_replace('hex-','#',$value);
  }
  return $atts;
}



/**
 * Search Within a Taxonomy
 * 
 * Support search with tax_query args
 *
 * $query = new WP_Query( array(
 *  'search_tax_query' => true,
 *  's' => $keywords,
 *  'tax_query' => array( array(
 *      'taxonomy' => 'country',               
 *      'field' => 'id',                      
 *      'terms' => $country,   
 *  ) ),
 * ) );
 */
class WP_Query_Taxonomy_Search {
  public function __construct() {
      add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
  }

  public function pre_get_posts( $q ) {
      if ( is_admin() ) return;

      $wp_query_search_tax_query = filter_var( 
          $q->get( 'search_tax_query' ), 
          FILTER_VALIDATE_BOOLEAN 
      );

      // WP_Query has 'tax_query', 's' and custom 'search_tax_query' argument passed
      if ( $wp_query_search_tax_query && $q->get( 'tax_query' ) && $q->get( 's' ) ) {
          add_filter( 'posts_groupby', array( $this, 'posts_groupby' ), 10, 1 );
      }
  }

  public function posts_groupby( $groupby ) {
      return '';
  }
}

new WP_Query_Taxonomy_Search();