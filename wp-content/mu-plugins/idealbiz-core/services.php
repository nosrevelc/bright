<?php

add_theme_support('post-thumbnails');
add_post_type_support( 'service', 'thumbnail' );  

// Register Custom Post Type - Service
function service() {
	$labels = array(
		'name'                  => _x( 'Services', 'Post Type General Name', 'idealbiz' ),
		'singular_name'         => _x( 'Service', 'Post Type Singular Name', 'idealbiz' ),
		'menu_name'             => __( 'Services', 'idealbiz' ),
		'name_admin_bar'        => __( 'Services', 'idealbiz' ),
		'archives'              => __( 'Item Archives', 'idealbiz' ),
		'attributes'            => __( 'Item Attributes', 'idealbiz' ),
		'parent_item_colon'     => __( 'Parent Item:', 'idealbiz' ),
		'all_items'             => __( 'All Items', 'idealbiz' ),
		'add_new_item'          => __( 'Add New Item', 'idealbiz' ),
		'add_new'               => __( 'Add New Service', 'idealbiz' ),
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
		'label'                 => __( 'Service', 'idealbiz' ),
		'description'           => __( 'Service Post type', 'idealbiz' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnails' ),
		'taxonomies'            => array( 'location', 'service_cat' ),
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
	register_post_type( 'service', $args );

}

// Register Taxonomy - Service Categories
function service_cat_taxonomy() {
    $labels = array(
        'name'                       => _x( 'Service Category', 'Service Category General Name', 'idealbiz' ),
        'singular_name'              => _x( 'Service Category', 'Service Category Singular Name', 'idealbiz' ),
        'menu_name'                  => __( 'Service Categories', 'idealbiz' ),
        'all_items'                  => __( 'All Service Categories', 'idealbiz' ),
        'parent_item'                => __( 'Service Category Item', 'idealbiz' ),
        'parent_item_colon'          => __( 'Service Category Item:', 'idealbiz' ),
        'new_item_name'              => __( 'New Service Category Name', 'idealbiz' ),
        'add_new_item'               => __( 'Add New Service Category', 'idealbiz' ),
        'edit_item'                  => __( 'Edit Service Category', 'idealbiz' ),
        'update_item'                => __( 'Update Service Category', 'idealbiz' ),
        'view_item'                  => __( 'View Service Category', 'idealbiz' ),
        'separate_items_with_commas' => __( 'Separate Service Categories with commas', 'idealbiz' ),
        'add_or_remove_items'        => __( 'Add or remove Service Categories', 'idealbiz' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'idealbiz' ),
        'popular_items'              => __( 'Popular Service Categories', 'idealbiz' ),
        'search_items'               => __( 'Search Service Categories', 'idealbiz' ),
        'not_found'                  => __( 'Not Found', 'idealbiz' ),
        'no_terms'                   => __( 'No Service Categories', 'idealbiz' ),
        'items_list'                 => __( 'Service Categories list', 'idealbiz' ),
        'items_list_navigation'      => __( 'Service Categories list navigation', 'idealbiz' ),
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
    register_taxonomy( 'service_cat', array( 'service', 'expert', 'counseling','partnership','couns_partnership' ), $args );
}

// Register Taxonomy - Service Categories
function member_cat_taxonomy() {
    $labels = array(
        'name'                       => _x( 'Member Category', 'Member Category General Name', 'idealbiz' ),
        'singular_name'              => _x( 'Member Category', 'Member Category Singular Name', 'idealbiz' ),
        'menu_name'                  => __( 'Member Categories', 'idealbiz' ),
        'all_items'                  => __( 'All Member Categories', 'idealbiz' ),
        'parent_item'                => __( 'Member Category Item', 'idealbiz' ),
        'parent_item_colon'          => __( 'Member Category Item:', 'idealbiz' ),
        'new_item_name'              => __( 'New SMember Category Name', 'idealbiz' ),
        'add_new_item'               => __( 'Add New Member Category', 'idealbiz' ),
        'edit_item'                  => __( 'Edit Member Category', 'idealbiz' ),
        'update_item'                => __( 'Update Service Category', 'idealbiz' ),
        'view_item'                  => __( 'View Member Category', 'idealbiz' ),
        'separate_items_with_commas' => __( 'Separate Member Categories with commas', 'idealbiz' ),
        'add_or_remove_items'        => __( 'Add or remove Member Categories', 'idealbiz' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'idealbiz' ),
        'popular_items'              => __( 'Popular Member Categories', 'idealbiz' ),
        'search_items'               => __( 'Search Member Categories', 'idealbiz' ),
        'not_found'                  => __( 'Not Found', 'idealbiz' ),
        'no_terms'                   => __( 'No Member Categories', 'idealbiz' ),
        'items_list'                 => __( 'Member Categories list', 'idealbiz' ),
        'items_list_navigation'      => __( 'Member Categories list navigation', 'idealbiz' ),
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
    register_taxonomy( 'member_cat', array( 'service', 'expert', 'counseling','partnership','couns_partnership' ), $args );
}
 

/* init post types and taxonomies */
add_action( 'init', 'service', 0 );
add_action( 'init', 'service_cat_taxonomy', 2 );
add_action( 'init', 'member_cat_taxonomy', 2 );


/* images in post type */
add_theme_support('post-thumbnails');
add_post_type_support( 'service', 'thumbnail' );


