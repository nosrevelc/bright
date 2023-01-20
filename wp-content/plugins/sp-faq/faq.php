<?php
/**
 * Plugin Name: WP FAQ
 * Plugin URL: https://www.essentialplugin.com/wordpress-plugin/sp-responsive-wp-faq-with-category-plugin/
 * Description: A simple FAQ plugin created with WordPress custom post type. Also work with Gutenberg shortcode block.
 * Text Domain: sp-faq
 * Domain Path: /languages/
 * Version: 3.6.4
 * Author: WP OnlineSupport, Essential Plugin
 * Author URI: https://www.essentialplugin.com/wordpress-plugin/sp-responsive-wp-faq-with-category-plugin/
 *
 * @package WordPress
 * @author WP OnlineSupport
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! defined( 'SP_FAQ_VERSION' ) ) {
	define( 'SP_FAQ_VERSION', '3.6.4' ); // Version of plugin
}

if( ! defined( 'SP_FAQ_DIR' ) ) {
	define( 'SP_FAQ_DIR', dirname( __FILE__ ) ); // Plugin dir
}

if( ! defined( 'SP_FAQ_URL' ) ) {
	define( 'SP_FAQ_URL', plugin_dir_url( __FILE__ ) ); // Plugin dir
}

if( ! defined( 'SP_FAQ_POST_TYPE' ) ) {
	define( 'SP_FAQ_POST_TYPE', 'sp_faq' ); // Plugin post type
}

if( ! defined( 'SP_FAQ_PLUGIN_BUNDLE_LINK' ) ) {
	define('SP_FAQ_PLUGIN_BUNDLE_LINK','https://www.essentialplugin.com/wordpress-plugin/sp-responsive-wp-faq-with-category-plugin/?utm_source=WP&utm_medium=FAQ&utm_campaign=Bundle-Banner#wpos-epb'); // Plugin link
}

if( ! defined( 'SP_FAQ_PLUGIN_LINK_UNLOCK' ) ) {
	define('SP_FAQ_PLUGIN_LINK_UNLOCK','https://www.essentialplugin.com/wordpress-plugin/sp-responsive-wp-faq-with-category-plugin/?utm_source=WP&utm_medium=FAQ&utm_campaign=Features-PRO#wpos-epb'); // Plugin link
}

if( ! defined( 'SP_FAQ_PLUGIN_LINK_UPGRADE' ) ) {
	define('SP_FAQ_PLUGIN_LINK_UPGRADE','https://www.essentialplugin.com/wordpress-plugin/sp-responsive-wp-faq-with-category-plugin/?utm_source=WP&utm_medium=FAQ&utm_campaign=Upgrade-PRO#wpos-epb'); // Plugin Check link
}

if( ! defined( 'SP_FAQ_PLUGIN_LINK_WELCOME' ) ) {
	define('SP_FAQ_PLUGIN_LINK_WELCOME','https://www.essentialplugin.com/wordpress-plugin/sp-responsive-wp-faq-with-category-plugin/?utm_source=WP&utm_medium=FAQ&utm_campaign=Welcome-Screen#wpos-epb'); // Plugin Check link
}

if( ! defined( 'SP_FAQ_SITE_LINK' ) ) {
	define('SP_FAQ_SITE_LINK','https://www.essentialplugin.com'); // Plugin link
}


/**
 * Function to load text domain
 * 
 * @package WP FAQ
 * @since 3.2.5
 */ 
add_action('plugins_loaded', 'sp_faq_load_textdomain');
function sp_faq_load_textdomain() {
	global $wp_version;

	// Set filter for plugin's languages directory
	$sp_faq_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$sp_faq_lang_dir = apply_filters( 'sp_faq_languages_directory', $sp_faq_lang_dir );

	// Traditional WordPress plugin locale filter.
	$get_locale = get_locale();

	if ( $wp_version >= 4.7 ) {
		$get_locale = get_user_locale();
	}

	// Traditional WordPress plugin locale filter
	$locale = apply_filters( 'plugin_locale',  $get_locale, 'sp-faq' );
	$mofile = sprintf( '%1$s-%2$s.mo', 'sp-faq', $locale );

	// Setup paths to current locale file
	$mofile_global  = WP_LANG_DIR . '/plugins/' . basename( SP_FAQ_DIR ) . '/' . $mofile;

	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/plugin-name folder
		load_textdomain( 'sp-faq', $mofile_global );
	} else { // Load the default language files
		load_plugin_textdomain( 'sp-faq', false, $sp_faq_lang_dir );
	}
} 

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @package WP FAQ
 * @since 3.2.5
 */
register_activation_hook( __FILE__, 'install_premium_version_faq' );

/**
 * Plugin Setup (On Activation)
 * 
 * Does the initial setup,
 * 
 * @package WP FAQ
 * @since 3.2.5
 */
function install_premium_version_faq(){
	if( is_plugin_active('wp-faq-pro/faq.php') ){
	 add_action('update_option_active_plugins', 'deactivate_premium_version_faq');
	}

	// Add option for solutions & features
	add_option( 'sp_faq_sf_optin', true );
}

/**
 * Deactivate pro plugin
 * 
 * @package WP FAQ
 * @since 3.2.5
 */
function deactivate_premium_version_faq(){
   deactivate_plugins('wp-faq-pro/faq.php',true);
}

// Action to add admin notice
add_action( 'admin_notices', 'sp_faq_admin_notice' );

/**
 * Admin notice
 * 
 * @package WP FAQ
 * @since 3.2.5
 */
function sp_faq_admin_notice() {

	global $pagenow;

	// If PRO plugin is active and free plugin exist
	$dir                = WP_PLUGIN_DIR . '/wp-faq-pro/faq.php';
	$notice_link        = add_query_arg( array('message' => 'sp-faq-plugin-notice'), admin_url('plugins.php') );
	$notice_transient   = get_transient( 'sp_faq_install_notice' );

	if ( $notice_transient == false &&  $pagenow == 'plugins.php' && file_exists($dir) && current_user_can( 'install_plugins' ) ) {
		echo '<div class="updated notice" style="position:relative;">
				<p>
					<strong>'.sprintf( __('Thank you for activating %s', 'sp-faq'), 'WP FAQ').'</strong>.<br/>
					'.sprintf( __('It looks like you had PRO version %s of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it.', 'sp-faq'), '<strong>(<em>WP FAQ PRO</em>)</strong>' ).'
				</p>
				<a href="'.esc_url( $notice_link ).'" class="notice-dismiss" style="text-decoration:none;"></a>
			</div>';
	}
}

// Admin Class File
require_once( SP_FAQ_DIR . '/includes/admin/class-spfaq-admin.php' );

// Script File
require_once( SP_FAQ_DIR . '/includes/class-wp-faq-script.php' );

// Post Type File
require_once( SP_FAQ_DIR . '/includes/wp-faq-post-types.php' );

// Post Type File
require_once( SP_FAQ_DIR . '/includes/wp-faq-functions.php' );

// Shortcode File
require_once( SP_FAQ_DIR . '/includes/shortcode/wp-faq.php' );

// Gutenberg Block Initializer
if ( function_exists( 'register_block_type' ) ) {
	require_once( SP_FAQ_DIR . '/includes/admin/supports/gutenberg-block.php' );
}

/* Recommended Plugins Starts */
if ( is_admin() ) {
	require_once( SP_FAQ_DIR . '/wpos-plugins/wpos-recommendation.php' );

	wpos_espbw_init_module( array(
							'prefix'	=> 'spfaq',
							'menu'		=> 'edit.php?post_type='.SP_FAQ_POST_TYPE,
						));
}
/* Recommended Plugins Ends */

/* Plugin Wpos Analytics Data Starts */
function wpos_analytics_anl36_load() {

	require_once dirname( __FILE__ ) . '/wpos-analytics/wpos-analytics.php';

	$wpos_analytics =  wpos_anylc_init_module( array(
							'id'			=> 36,
							'file'			=> plugin_basename( __FILE__ ),
							'name'			=> 'WP responsive FAQ with category plugin',
							'slug'			=> 'wp-responsive-faq-with-category-plugin',
							'type'			=> 'plugin',
							'menu'			=> 'edit.php?post_type=sp_faq',
							'text_domain'	=> 'sp-faq',
						));

	return $wpos_analytics;
}

// Init Analytics
wpos_analytics_anl36_load();
/* Plugin Wpos Analytics Data Ends */