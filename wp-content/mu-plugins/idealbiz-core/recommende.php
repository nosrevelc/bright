<?php

// Register Custom Post Type - Wanted

add_theme_support('post-thumbnails');
add_post_type_support( 'news', 'thumbnail' ); 

function recommended_business() {
	$labels = array(
		'name'                  => _x( 'Business recommendations', 'Post Type General Name', 'idealbiz' ),
		'singular_name'         => _x( 'Business recommendations', 'Post Type Singular Name', 'idealbiz' ),
		'menu_name'             => __( 'Business recommendations', 'idealbiz' ),
		'name_admin_bar'        => __( 'Business recommendations', 'idealbiz' ),
		'archives'              => __( 'Item Archives', 'idealbiz' ),
		'attributes'            => __( 'Item Attributes', 'idealbiz' ),
		'parent_item_colon'     => __( 'Parent Item:', 'idealbiz' ),
		'all_items'             => __( 'All Items', 'idealbiz' ),
		'add_new_item'          => __( 'Add New Item', 'idealbiz' ),
		'add_new'               => __( 'Add New Business recommendations', 'idealbiz' ),
		'new_item'              => __( 'New Item', 'idealbiz' ),
		'edit_item'             => __( 'Edit Item', 'idealbiz' ),
		'update_item'           => __( 'Update Item', 'idealbiz' ),
		'view_item'             => __( 'View Item', 'idealbiz' ),
		'view_items'            => __( 'View Items', 'idealbiz' ),
		'search_items'          => __( 'Search Item', 'idealbiz' ),
		'not_found'             => __( 'Not found', 'idealbiz' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'idealbiz' ),
		'featured_image'        => __( 'Featured Image', 'idealbiz' ),
		'set_featured_image'    => __( 'Set featured image', 'idealbiz' ),
		'remove_featured_image' => __( 'Remove featured image', 'idealbiz' ),
		'use_featured_image'    => __( 'Use as featured image', 'idealbiz' ),
		'insert_into_item'      => __( 'Insert into item', 'idealbiz' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'idealbiz' ),
		'items_list'            => __( 'Items list', 'idealbiz' ),
		'items_list_navigation' => __( 'Items list navigation', 'idealbiz' ),
		'filter_items_list'     => __( 'Filter items list', 'idealbiz' ),
	);
	$args = array(
		'label'                 => __( 'Business recommendations', 'idealbiz' ),
		'description'           => __( 'Business recommendations Post type', 'idealbiz' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail' ),
		'taxonomies'            => array( 'location', 'listing_cat' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-share-alt2',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => true,
        'publicly_queryable'    => true,
        'show_in_rest'          => true,
        'capability_type'       => 'page'
	);
	register_post_type( 'recommended_business', $args  );

}

/* init post types and taxonomies */
add_action( 'init', 'recommended_business', 0 );

/* images in post type */
add_theme_support('post-thumbnails');
add_post_type_support( 'recommended_business', 'thumbnail' );