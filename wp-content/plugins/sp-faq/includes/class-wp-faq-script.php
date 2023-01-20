<?php
/**
 * Script Class
 *
 * Handles the script and style functionality of plugin
 *
 * @package WP FAQ
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wp_Faq_Script {

	function __construct() {

		// Action to add style in backend
		add_action( 'admin_enqueue_scripts', array( $this, 'wp_faq_admin_style_script' ) );

		// Action to add style at front side
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_faq_front_style' ) );

		// Action to add script at front side
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_faq_front_script' ) );
	}

	/**
	 * Function to register admin scripts and styles
	 * 
	 * @package Blog Designer - Post and Widget Pro
	 * @since 1.3
	 */
	function spfaq_register_admin_assets() {

		global $wp_version;

		/* Styles */
		// Registring admin css
		wp_register_style( 'spfaq-admin-style', SP_FAQ_URL.'assets/css/spfaq-admin.css', array(), SP_FAQ_VERSION );


		/* Scripts */
		// Registring admin script
		wp_register_script( 'spfaq-admin-script', SP_FAQ_URL.'assets/js/spfaq-admin.js', array('jquery'), SP_FAQ_VERSION, true );
	}

	/**
	 * Enqueue admin styles
	 * 
	 * @package WP FAQ
	 * @since 1.2.1
	 */
	function wp_faq_admin_style_script( $hook ) {

		global $typenow;

		$this->spfaq_register_admin_assets();

		// Taking pages array
		$pages_arr = array( SP_FAQ_POST_TYPE );

		if( in_array($typenow, $pages_arr) ) {
			wp_enqueue_style( 'spfaq-admin-style' );
		}

		// If page is plugin setting page then enqueue script
		if( $hook == SP_FAQ_POST_TYPE.'_page_spfaq-designs' ) {

			/* Style */
			// Enqueue admin Style
			wp_enqueue_script( 'spfaq-admin-script' );
		}
	}

	/**
	 * Function to add style at front side
	 * 
	 * @package WP FAQ
	 * @since 1.0.0
	 */
	function wp_faq_front_style() {

		// Registring public style
		wp_register_style( 'accordioncssfree', SP_FAQ_URL."assets/css/jquery.accordion.css", array(), SP_FAQ_VERSION );
		wp_enqueue_style('accordioncssfree');
	}

	/**
	 * Function to add script at front side
	 * 
	 * @package WP FAQ
	 * @since 1.0.0
	 */
	function wp_faq_front_script() {

		global $post;

		// Registring public script
		wp_register_script( 'accordionjsfree', SP_FAQ_URL."assets/js/jquery.accordion.js", array('jquery'), SP_FAQ_VERSION, true );
		wp_enqueue_script( 'accordionjsfree' );

		// Register Elementor script
		wp_register_script( 'spfaq-elementor-script', SP_FAQ_URL.'assets/js/elementor/wp-faq-elementor.js', array('jquery'), SP_FAQ_VERSION, true );

		//Registring public script
		wp_register_script( 'spfaq-public-js', SP_FAQ_URL."assets/js/spfaq-public.js", array('jquery'), SP_FAQ_VERSION, true );

		// Enqueue Script for Elementor Preview
		if ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_GET['elementor-preview'] ) && $post->ID == (int) $_GET['elementor-preview'] ) {

			wp_enqueue_script( 'accordionjsfree' );
			wp_enqueue_script( 'spfaq-public-js' );
			wp_enqueue_script( 'spfaq-elementor-script' );
		}

		// Enqueue Style & Script for Beaver Builder
		if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {

			$this->spfaq_register_admin_assets();

			wp_enqueue_script( 'spfaq-admin-script' );
			wp_enqueue_script( 'accordionjsfree' );
			wp_enqueue_script( 'spfaq-public-js' );
		}

		// Enqueue Admin Style & Script for Divi Page Builder
		if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_GET['et_fb'] ) && $_GET['et_fb'] == 1 ) {
			$this->spfaq_register_admin_assets();

			wp_enqueue_style( 'spfaq-admin-style');
		}

		// Enqueue Admin Style for Fusion Page Builder
		if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) ) ) {
			$this->spfaq_register_admin_assets();

			wp_enqueue_style( 'spfaq-admin-style');
		}

	}
}

$wp_faq_script = new Wp_Faq_Script();