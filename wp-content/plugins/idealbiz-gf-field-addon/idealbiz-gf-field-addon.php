<?php
/**
 * @wordpress-plugin
 * Plugin Name:       iDealBiz - Gravity Forms Field Add-On
 * Plugin URI:        http://widgilabs.com
 * Description:       Adds fields types to GravityForms using the Add-On Framework. Field types: custom taxonomy.
 * Version:           1.0.0
 * Author:            WidgiLabs
 * Author URI:        http://widgilabs.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       idealbiz-gf-field-addon
 * Domain Path:       /languages
 *
 * This plugin is yet to be in the WidgiLabs standard with namespaces and composer.
 * @link https://github.com/richardW8k/simplefieldaddon
 */

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Begins execution of the plugin.
 *
 * @since 1.0.0
 */
$bootstrap = new \WidgiLabs\WP\Plugin\iDealBiz\GFFieldAddon\Bootstrap();
$bootstrap->register_hooks();
