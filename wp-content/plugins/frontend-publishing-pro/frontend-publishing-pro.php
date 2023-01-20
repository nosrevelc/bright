<?php
/*
Plugin Name: Frontend Publishing Pro
Plugin URI: http://wpfrontendpublishing.com/
Description: Allow your users to create, edit and delete posts directly from the WordPress frontend area.
Version: 3.8.9
Author: Hassan Akhtar
Author URI: http://wpgurus.net/
Text Domain: frontend-publishing-pro
Domain Path: /languages
*/

if (!defined('WPINC')) die;

if (version_compare(PHP_VERSION, '5.4', '<')) {
	wp_die(sprintf('Frontend Publishing Pro plugin requires PHP 5.4 or higher. You’re still on %s. Please upgrade.', PHP_VERSION));
}

require_once 'constants.php';

require_once 'autoloader.php';

require_once 'global-functions.php';

function wpfepp_run()
{
	$plugin = new \WPFEPP\Frontend_Publishing_Pro();
	$plugin->run();
}

wpfepp_run();

function wpfepp_updates()
{
	$updater = \WPFEPP\Utils\Update_Helper::initialize_updater(
		WPFEPP_PLUGIN_VERSION,
		1183,
		plugin_basename(__FILE__)
	);

	if ($updater) {
		$updater->run();
	}
}

add_action('init', 'wpfepp_updates');

function wpfepp_activation($network_wide)
{
	do_action('wpfepp_activation', $network_wide);
}

register_activation_hook(__FILE__, 'wpfepp_activation');

function wpfepp_uninstall()
{
	do_action('wpfepp_uninstall');
}

register_uninstall_hook(__FILE__, 'wpfepp_uninstall');

/**
 * Loads the plugin's text domain for localization.
 **/
function wpfepp_load_plugin_textdomain()
{
	load_plugin_textdomain('frontend-publishing-pro', false, plugin_basename(dirname(__FILE__)) . '/languages');
}

add_action('plugins_loaded', 'wpfepp_load_plugin_textdomain');