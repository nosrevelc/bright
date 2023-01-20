<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC Dependency Checker.
 *
 * Checks if WooCommerce is enabled.
 *
 * @since  0.0.1
 * @author WidgiLabs <dev@widgilabs.com>
 */
class WC_Dependencies {

	private static $active_plugins;

	static function init() {

		static::$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			static::$active_plugins = array_merge(
				static::$active_plugins,
				get_site_option( 'active_sitewide_plugins', array() )
			);
		}
	}

	static function woocommerce_active_check() {

		if ( empty( static::$active_plugins ) ) {
			static::init();
		}

		return in_array( 'woocommerce/woocommerce.php', static::$active_plugins, true ) ||
			array_key_exists( 'woocommerce/woocommerce.php', static::$active_plugins );
	}
}
