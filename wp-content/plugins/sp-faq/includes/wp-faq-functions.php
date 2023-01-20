<?php
/**
 * Functions File
 *
 * @package WP FAQ
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Function to unique number value
 * 
 * @package WP FAQ Pro
 * @since 1.1.3
 */
function wp_faq_get_unique() {
	static $unique = 0;
	$unique++;

	// For Elementor & Beaver Builder
	if( ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_POST['action'] ) && $_POST['action'] == 'elementor_ajax' )
	|| ( class_exists('FLBuilderModel') && ! empty( $_POST['fl_builder_data']['action'] ) )
	|| ( function_exists('vc_is_inline') && vc_is_inline() ) ) {
		$unique = current_time('timestamp') . '-' . rand();
	}

	return $unique;
}

/**
 * Sanitize Multiple HTML class
 * 
 * @package WP FAQ
 * @since 1.0.0
 */
function sp_faq_sanitize_html_classes($classes, $sep = " ") {
	$return = "";

	if( $classes && ! is_array($classes) ) {
		$classes = explode($sep, $classes);
	}

	if( ! empty( $classes ) ) {
		foreach($classes as $class){
			$return .= sanitize_html_class($class) . " ";
		}
		$return = trim( $return );
	}

	return $return;
}