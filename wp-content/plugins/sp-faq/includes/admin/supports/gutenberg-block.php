<?php
/**
 * Blocks Initializer
 * 
 * @package WP FAQ
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function spfaq_register_guten_block() {

	// Block Editor Script
	wp_register_script( 'spfaq-block-js', SP_FAQ_URL.'assets/js/blocks.build.js', array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components' ), SP_FAQ_VERSION, true );
	wp_localize_script( 'spfaq-block-js', 'SPFAQ_Block', array(
																'pro_demo_link' 	=> 'https://demo.wponlinesupport.com/prodemo/pro-faq-plugin-demo/',
																'free_demo_link' 	=> 'https://demo.wponlinesupport.com/sp-faq/',
																'pro_link' 			=> SP_FAQ_PLUGIN_LINK_UNLOCK,
																));

	// Register block and explicit attributes for FAQ
	register_block_type( 'spfaq/sp-faq', array(
		'attributes' => array(
			'single_open' => array(
							'type'		=> 'string',
							'default'	=> 'true',
						),
			'transition_speed' => array(
							'type'		=> 'number',
							'default'	=> 300,
						),
			'limit' => array(
							'type'		=> 'number',
							'default'	=> -1,
						),
			'category' => array(
						'type'		=> 'string',
						'default'	=> '',
					),
			'align' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'className' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
		),
		'render_callback' => 'sp_faq_shortcode',
	));

	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'spfaq-block-js', 'sp-faq', SP_FAQ_DIR . '/languages' );
	}
}
add_action( 'init', 'spfaq_register_guten_block' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * 
 * @package WP FAQ
 * @since 1.0.0
 */
function spfaq_editor_assets() {

	// Block Editor CSS
	if( ! wp_style_is( 'wpos-guten-block-css', 'registered' ) ) {
		wp_register_style( 'wpos-guten-block-css', SP_FAQ_URL.'assets/css/blocks.editor.build.css', array( 'wp-edit-blocks' ), SP_FAQ_VERSION );
	}

	// Block Editor Script
	wp_enqueue_style( 'wpos-guten-block-css' );
	wp_enqueue_script( 'spfaq-block-js' );
}
add_action( 'enqueue_block_editor_assets', 'spfaq_editor_assets' );

/**
 * Adds an extra category to the block inserter
 *
 * @package WP FAQ
 * @since 1.0.0
 */
function spfaq_add_block_category( $categories ) {

	$guten_cats = wp_list_pluck( $categories, 'slug' );

	if( ! in_array( 'wpos_guten_block', $guten_cats ) ) {
		$categories[] = array(
							'slug'	=> 'wpos_guten_block',
							'title'	=> __('WPOS Blocks', 'sp-faq'),
							'icon'	=> null,
						);
	}

	return $categories;
}
add_filter( 'block_categories_all', 'spfaq_add_block_category' );