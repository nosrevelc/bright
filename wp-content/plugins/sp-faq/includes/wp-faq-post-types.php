<?php
/**
 * Register Post type functionality
 *
 * @package WP FAQ
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Function to register post type
 * 
 * @package WP FAQ
 * @since 1.0.0
 */
function sp_faq_setup_post_types() {
	$festivals_labels =  apply_filters( 'sp_faq_labels', array(
				'name'					=> _x( 'FAQs', 'sp-faq' ),
				'singular_name'			=> _x( 'FAQ', 'sp-faq' ),
				'add_new'				=> _x( 'Add New', 'sp-faq' ),
				'add_new_item'			=> __( 'Add New FAQ', 'sp-faq' ),
				'edit_item'				=> __( 'Edit FAQ', 'sp-faq' ),
				'new_item'				=> __( 'New FAQ', 'sp-faq' ),
				'all_items'				=> __( 'All FAQ', 'sp-faq' ),
				'view_item'				=> __( 'View FAQ', 'sp-faq' ),
				'search_items'			=> __( 'Search FAQ', 'sp-faq' ),
				'not_found'				=> __( 'No FAQ found', 'sp-faq' ),
				'not_found_in_trash'	=> __( 'No FAQ found in Trash', 'sp-faq' ),
				'parent_item_colon'		=> '',
				'menu_name'				=> __( 'FAQ', 'sp-faq' ),
				'exclude_from_search'	=> true
	) );
	$faq_args = array(
				'labels' 				=> $festivals_labels,
				'public' 				=> true,
				'publicly_queryable'	=> true,
				'show_ui'				=> true,
				'show_in_menu'			=> true,
				'query_var'				=> true,
				'capability_type'		=> 'post',
				'has_archive' 			=> true,
				'hierarchical'			=> false,
				'menu_icon'				=> 'dashicons-info',
				'supports'				=> array( 'title','editor','thumbnail','excerpt' ),
	);
	register_post_type( 'sp_faq', apply_filters( 'sp_faq_post_type_args', $faq_args ) );
}

// Action to register plugin post type
add_action('init', 'sp_faq_setup_post_types');

/**
 * Function to register taxonomy
 * 
 * @package WP FAQ
 * @since 1.0.0
 */
function sp_faq_taxonomies() {
	$labels = array(
				'name'				=> _x( 'Category', 'sp-faq' ),
				'singular_name'		=> _x( 'Category', 'sp-faq' ),
				'search_items'		=> __( 'Search Category', 'sp-faq' ),
				'all_items'			=> __( 'All Category', 'sp-faq' ),
				'parent_item'		=> __( 'Parent Category', 'sp-faq' ),
				'parent_item_colon'	=> __( 'Parent Category' , 'sp-faq' ),
				'edit_item'			=> __( 'Edit Category', 'sp-faq' ),
				'update_item'		=> __( 'Update Category', 'sp-faq' ),
				'add_new_item'		=> __( 'Add New Category', 'sp-faq' ),
				'new_item_name'		=> __( 'New Category Name', 'sp-faq' ),
				'menu_name'			=> __( 'FAQ Category', 'sp-faq' ),
	);

	$args = array(
				'hierarchical'		=> true,
				'labels'			=> $labels,
				'show_ui'			=> true,
				'show_admin_column'	=> true,
				'query_var'			=> true,
				'rewrite'			=> array( 'slug' => 'faq_cat' ),
	);

	register_taxonomy( 'faq_cat', array( 'sp_faq' ), $args );
}

// Action to register plugin taxonomies
add_action( 'init', 'sp_faq_taxonomies');