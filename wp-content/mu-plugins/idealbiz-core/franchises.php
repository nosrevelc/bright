<?php

add_theme_support('post-thumbnails');
add_post_type_support( 'franchise', 'thumbnail' );  

// Register Custom Post Type - Partner
function franchise() {
	$labels = array(
		'name'                  => _x( 'Partners', 'Post Type General Name', 'idealbiz' ),
		'singular_name'         => _x( 'Partner', 'Post Type Singular Name', 'idealbiz' ),
		'menu_name'             => __( 'Partners', 'idealbiz' ),
		'name_admin_bar'        => __( 'Partners', 'idealbiz' ),
		'archives'              => __( 'Item Archives', 'idealbiz' ),
		'attributes'            => __( 'Item Attributes', 'idealbiz' ),
		'parent_item_colon'     => __( 'Parent Item:', 'idealbiz' ),
		'all_items'             => __( 'All Items', 'idealbiz' ),
		'add_new_item'          => __( 'Add New Item', 'idealbiz' ),
		'add_new'               => __( 'Add New Partner', 'idealbiz' ),
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
		'label'                 => __( 'Partner', 'idealbiz' ),
		'description'           => __( 'Partner Post type', 'idealbiz' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnails' ),
		'taxonomies'            => array( 'franchise_cat', 'location' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true, 
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-media-text',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'franchise', $args );

}

// Register Taxonomy - Partner Categories
function franchise_cat_taxonomy() {
    $labels = array(
        'name'                       => _x( 'Partner Category', 'Partner Category General Name', 'idealbiz' ),
        'singular_name'              => _x( 'Partner Category', 'Partner Category Singular Name', 'idealbiz' ),
        'menu_name'                  => __( 'Partner Categories', 'idealbiz' ),
        'all_items'                  => __( 'All Partner Categories', 'idealbiz' ),
        'parent_item'                => __( 'Partner Category Item', 'idealbiz' ),
        'parent_item_colon'          => __( 'Partner Category Item:', 'idealbiz' ),
        'new_item_name'              => __( 'New Partner Category Name', 'idealbiz' ),
        'add_new_item'               => __( 'Add New Partner Category', 'idealbiz' ),
        'edit_item'                  => __( 'Edit Partner Category', 'idealbiz' ),
        'update_item'                => __( 'Update Partner Category', 'idealbiz' ),
        'view_item'                  => __( 'View Partner Category', 'idealbiz' ),
        'separate_items_with_commas' => __( 'Separate Partner Categories with commas', 'idealbiz' ),
        'add_or_remove_items'        => __( 'Add or remove Partner Categories', 'idealbiz' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'idealbiz' ),
        'popular_items'              => __( 'Popular Partner Categories', 'idealbiz' ),
        'search_items'               => __( 'Search Partner Categories', 'idealbiz' ),
        'not_found'                  => __( 'Not Found', 'idealbiz' ),
        'no_terms'                   => __( 'No Partner Categories', 'idealbiz' ),
        'items_list'                 => __( 'Partner Categories list', 'idealbiz' ),
        'items_list_navigation'      => __( 'Partner Categories list navigation', 'idealbiz' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );
    // here we add/register taxonomies to post type
    register_taxonomy( 'franchise_cat', array( 'franchise' ), $args );
}


 

/* init post types and taxonomies */
add_action( 'init', 'franchise', 1 );
add_action( 'init', 'franchise_cat_taxonomy', 2 );


/* images in post type */
add_theme_support('post-thumbnails');
add_post_type_support( 'franchise', 'thumbnail' );


