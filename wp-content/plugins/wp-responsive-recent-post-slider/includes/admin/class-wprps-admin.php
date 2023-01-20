<?php
/**
 * Admin Class
 *
 * Handles the admin functionality of plugin
 *
 * @package WP Responsive Recent Post Slider
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wprps_Admin {

	function __construct() {

		// Admin init process
		add_action( 'admin_init', array( $this, 'wprps_admin_init_process') );

		// Admin for the Solutions & Features
		add_action( 'admin_init', array($this, 'wprps_admin_init_sf_process') );

		// Action to add admin menu
		add_action( 'admin_menu', array( $this, 'wprps_register_menu') );

		// Action to add custom column to post listing
		add_filter('manage_edit-category_columns', array( $this, 'wppsac_category_manage_columns' ) ); 

		// Action to add custom column data to post listing
		add_filter('manage_category_custom_column', array( $this, 'wppsac_category_columns' ), 10, 3);

		// Action to add little JS code in admin footer
		//add_action( 'admin_footer', array($this, 'wprps_upgrade_page_link_blank') );
	}

	/**
	 * Function to add menu
	 * 
	 * @package WP Responsive Recent Post Slider
	 * @since 1.0.0
	 */
	function wprps_register_menu() {

		// Getting Started page
		add_menu_page( __('Recent Post Slider', 'wp-responsive-recent-post-slider'), __('Recent Post Slider', 'wp-responsive-recent-post-slider'), 'manage_options', 'wprps-about', array($this, 'wprps_getting_started_page'), 'dashicons-sticky', 6 );

		// Setting page
		add_submenu_page( 'wprps-about', __('Solutions & Features - Responsive Recent Post Slider', 'wp-responsive-recent-post-slider'), '<span style="color:#2ECC71">'. __('Solutions & Features', 'wp-responsive-recent-post-slider').'</span>', 'manage_options', 'wprps-solutions-features', array($this, 'wprps_solutions_features_page') );

		// Premium page load
		add_submenu_page( 'wprps-about', __('Upgrade To PRO - Responsive Recent Post Slider', 'wp-responsive-recent-post-slider'), '<span style="color:#ff2700">' . __('Upgrade To Premium ', 'wp-responsive-recent-post-slider') . '</span>', 'manage_options', 'wprps-premium', array($this, 'wprps_premium_page') );

		// Register plugin premium page
		//add_submenu_page( 'wprps-about', __('Upgrade To PRO - Responsive Recent Post Slider', 'wp-responsive-recent-post-slider'), '<span class="wpos-upgrade-pro" style="color:#ff2700">' . __('Upgrade To Premium ', 'wp-responsive-recent-post-slider') . '</span>', 'manage_options', 'wprps-upgrade-pro', array($this, 'wprps_redirect_page') );
		//add_submenu_page( 'wprps-about', __('Bundle Deal - Responsive Recent Post Slider', 'wp-responsive-recent-post-slider'), '<span class="wpos-upgrade-pro" style="color:#ff2700">' . __('Bundle Deal', 'wp-responsive-recent-post-slider') . '</span>', 'manage_options', 'wprps-bundle-deal', array($this, 'wprps_redirect_page') );
	}

	/**
	 * Getting Started Page Html
	 * 
	 * @since 1.0
	 */
	function wprps_getting_started_page() {
		include_once( WPRPS_DIR . '/includes/admin/wprps-how-it-work.php' );
	}

	function wprps_solutions_features_page() {
		include_once( WPRPS_DIR . '/includes/admin/settings/solutions-features.php' );	
	}

	/**
	 * Premium Page Html
	 * 
	 * @package WP Responsive Recent Post Slider
	 * @since 1.0.0
	 */
	function wprps_premium_page() {
		include_once( WPRPS_DIR . '/includes/admin/settings/premium.php' );
	}

	/**
	 * How It Work Page Html
	 * 
	 * @since 1.0
	 */
	// function wprps_redirect_page() {
	// }

	/**
	 * Function to notification transient
	 * 
	 * @package WP Responsive Recent Post Slider
	 * @since 1.4.3
	 */
	function wprps_admin_init_process() {

		// global $pagenow;

		// $current_page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';

		// If plugin notice is dismissed
	    if( isset($_GET['message']) && $_GET['message'] == 'wprps-plugin-notice' ) {
	    	set_transient( 'wprps_install_notice', true, 604800 );
	    }

	    // Redirect to external page for upgrade to menu
	    // if( $pagenow == 'admin.php' ) {

	    // 	if( $current_page == 'wprps-upgrade-pro' ) {

	    // 		wp_redirect( WPRPS_PLUGIN_LINK_UPGRADE );
	    // 		exit;
	    // 	}

	    // 	if( $current_page == 'wprps-bundle-deal' ) {

	    // 		wp_redirect( WPRPS_PLUGIN_BUNDLE_LINK );
	    // 		exit;
	    // 	}
	    // }
	}

	/**
	 * Admin Prior Process for Solutions & Features Page Redirect
	 * 
	 * @package WP Responsive Recent Post Slider
	 * @since 2.0.11
	 */
	function wprps_admin_init_sf_process() {

		if ( get_option( 'wprps_sf_optin', false ) ) {

			delete_option( 'wprps_sf_optin' );

			$redirect_link = add_query_arg( array( 'page' => 'wprps-solutions-features' ), admin_url( 'admin.php' ) );

			wp_safe_redirect( $redirect_link );

			exit;
		}
	}

	/**
	 * Add custom column to Logo listing page
	 * 
	 * @package WP Responsive Recent Post Slider
	 * @since 1.0.0
	 */
	function wppsac_category_manage_columns($columns) {
	    $new_columns['wpos_shortcode'] = __( 'Category ID', 'wp-responsive-recent-post-slider' );
		$columns = wppsac_add_array( $columns, $new_columns, 2 );
		return $columns;
	}

	/**
	 * Add custom column data to Logo listing page
	 * 
	 * @package WP Responsive Recent Post Slider
	 * @since 1.0.0
	 */
	function wppsac_category_columns($ouput, $column_name, $tax_id) {
		if( $column_name == 'wpos_shortcode' ) {
			$ouput .= $tax_id;
		}
	    return $ouput;
	}

	/**
	 * Add JS snippet to admin footer to add target _blank in upgrade link
	 * 
	 * @package WP Responsive Recent Post Slider
	 * @since 1.0.0
	 */
	/*function wprps_upgrade_page_link_blank() {

		global $wpos_upgrade_link_snippet;

		// Redirect to external page
		if( empty( $wpos_upgrade_link_snippet ) ) {

			$wpos_upgrade_link_snippet = 1;
	?>
		<script type="text/javascript">
			(function ($) {
				$('.wpos-upgrade-pro').parent().attr( { target: '_blank', rel: 'noopener noreferrer' } );
			})(jQuery);
		</script>
	<?php }
	} */
}

$wprps_admin = new Wprps_Admin();