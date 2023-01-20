<?php
/**
 * IdealBiz Service Request
 *
 * @link  http://widgilabs.com/
 * @since 1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       iDealBiz - Service Request
 * Plugin URI:        http://widgilabs.com/
 * Description:       Add service request feature to platform.
 * Version:           1.0.0
 * Author:            WidgiLabs
 * Author URI:        http://widgilabs.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       idealbiz-service-request
 * Domain Path:       /languages
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
add_action(
	'plugins_loaded', function () {
		$plugin = new \WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Plugin( 'idealbiz-service-request', '1.0.0' );
		$plugin->run();
	}
);
