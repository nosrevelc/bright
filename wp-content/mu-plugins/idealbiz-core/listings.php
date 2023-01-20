<?php

// Register Custom Post Type - Listing

add_theme_support('post-thumbnails');
add_post_type_support( 'news', 'thumbnail' ); 

function listing() {
	$labels = array(
		'name'                  => _x( 'Listings', 'Post Type General Name', 'idealbiz' ),
		'singular_name'         => _x( 'Listing', 'Post Type Singular Name', 'idealbiz' ),
		'menu_name'             => __( 'Listings', 'idealbiz' ),
		'name_admin_bar'        => __( 'Listings', 'idealbiz' ),
		'archives'              => __( 'Item Archives', 'idealbiz' ),
		'attributes'            => __( 'Item Attributes', 'idealbiz' ),
		'parent_item_colon'     => __( 'Parent Item:', 'idealbiz' ),
		'all_items'             => __( 'All Items', 'idealbiz' ),
		'add_new_item'          => __( 'Add New Item', 'idealbiz' ),
		'add_new'               => __( 'Add New Listing', 'idealbiz' ),
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
		'label'                 => __( 'Listing', 'idealbiz' ),
		'description'           => __( 'Listing Post type', 'idealbiz' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail','comments' ),
		'taxonomies'            => array( 'location', 'boost' ),
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
        'show_in_rest'          => true,
        'capability_type'       => 'page'
	);
	register_post_type( 'listing', $args  );

}

// Register Taxonomy - Listing Cat
function listing_cat_taxonomy() {
    $labels = array(
        'name'                       => _x( 'Categories', 'Taxonomy General Name', 'idealbiz' ),
        'singular_name'              => _x( 'Categorie', 'Taxonomy Singular Name', 'idealbiz' ),
        'menu_name'                  => __( 'Categories', 'idealbiz' ),
        'all_items'                  => __( 'All Categories', 'idealbiz' ),
        'parent_item'                => __( 'Categorie Item', 'idealbiz' ),
        'parent_item_colon'          => __( 'Categorie Item:', 'idealbiz' ),
        'new_item_name'              => __( 'New Categorie Name', 'idealbiz' ),
        'add_new_item'               => __( 'Add New Categorie', 'idealbiz' ),
        'edit_item'                  => __( 'Edit Categorie', 'idealbiz' ),
        'update_item'                => __( 'Update Categorie', 'idealbiz' ),
        'view_item'                  => __( 'View Categorie', 'idealbiz' ),
        'separate_items_with_commas' => __( 'Separate Categories with commas', 'idealbiz' ),
        'add_or_remove_items'        => __( 'Add or remove Categories', 'idealbiz' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'idealbiz' ),
        'popular_items'              => __( 'Popular Categories', 'idealbiz' ),
        'search_items'               => __( 'Search Categories', 'idealbiz' ),
        'not_found'                  => __( 'Not Found', 'idealbiz' ),
        'no_terms'                   => __( 'No Categories', 'idealbiz' ),
        'items_list'                 => __( 'Categories list', 'idealbiz' ),
        'items_list_navigation'      => __( 'Categories list navigation', 'idealbiz' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_rest'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );
     // here we add/register taxonomies to post type
    register_taxonomy( 'listing_cat', array( 'listing', 'wanted' ), $args );
}

//xxxxxxxxxxxxxxxxxxxxxx

// Register Taxonomy - Type Listing
function type_listing() {
    $labels = array(
        'name'                       => _x( 'Type Listing', 'Taxonomy General Name', 'idealbiz' ),
        'singular_name'              => _x( 'Type Listing', 'Taxonomy Singular Name', 'idealbiz' ),
        'menu_name'                  => __( 'Type Listing', 'idealbiz' ),
        'all_items'                  => __( 'All Type Listing', 'idealbiz' ),
        'parent_item'                => __( 'Type Listing Item', 'idealbiz' ),
        'parent_item_colon'          => __( 'Type Listing Item:', 'idealbiz' ),
        'new_item_name'              => __( 'New Type Listing Name', 'idealbiz' ),
        'add_new_item'               => __( 'Add New Type Listing', 'idealbiz' ),
        'edit_item'                  => __( 'Edit Type Listing', 'idealbiz' ),
        'update_item'                => __( 'Update Type Listing', 'idealbiz' ),
        'view_item'                  => __( 'View Type Listing', 'idealbiz' ),
        'separate_items_with_commas' => __( 'Separate Type Listing with commas', 'idealbiz' ),
        'add_or_remove_items'        => __( 'Add or remove Type Listing', 'idealbiz' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'idealbiz' ),
        'popular_items'              => __( 'Popular Type Listing', 'idealbiz' ),
        'search_items'               => __( 'Search Type Listing', 'idealbiz' ),
        'not_found'                  => __( 'Not Found', 'idealbiz' ),
        'no_terms'                   => __( 'No Type Listing', 'idealbiz' ),
        'items_list'                 => __( 'Type Listing list', 'idealbiz' ),
        'items_list_navigation'      => __( 'Type Listing list navigation', 'idealbiz' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_rest'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );
     // here we add/register taxonomies to post type
    register_taxonomy( 'type_listing', array( 'listing', 'wanted' ), $args );
}


//yyyyyyyyyyyyyyyyyyyyyy

// Register Taxonomy - Location
function location_taxonomy() {
    $labels = array(
        'name'                       => _x( 'Location', 'Taxonomy General Name', 'idealbiz' ),
        'singular_name'              => _x( 'Location', 'Taxonomy Singular Name', 'idealbiz' ),
        'menu_name'                  => __( 'Locations', 'idealbiz' ),
        'all_items'                  => __( 'All Locations', 'idealbiz' ),
        'parent_item'                => __( 'Location Item', 'idealbiz' ),
        'parent_item_colon'          => __( 'Location Item:', 'idealbiz' ),
        'new_item_name'              => __( 'New Location Name', 'idealbiz' ),
        'add_new_item'               => __( 'Add New Location', 'idealbiz' ),
        'edit_item'                  => __( 'Edit Location', 'idealbiz' ),
        'update_item'                => __( 'Update Location', 'idealbiz' ),
        'view_item'                  => __( 'View Location', 'idealbiz' ),
        'separate_items_with_commas' => __( 'Separate Locations with commas', 'idealbiz' ),
        'add_or_remove_items'        => __( 'Add or remove Locations', 'idealbiz' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'idealbiz' ),
        'popular_items'              => __( 'Popular Locations', 'idealbiz' ),
        'search_items'               => __( 'Search Locations', 'idealbiz' ),
        'not_found'                  => __( 'Not Found', 'idealbiz' ), 
        'no_terms'                   => __( 'No Locations', 'idealbiz' ),
        'items_list'                 => __( 'Locations list', 'idealbiz' ),
        'items_list_navigation'      => __( 'Locations list navigation', 'idealbiz' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_in_rest'          => true,
        'show_tagcloud'              => true,
    );
    // here we add/register taxonomies to post type 
    register_taxonomy( 'location', array( 'listing', 'wanted', 'ipost', 'expert', 'franchise' ), $args );
}

// Register Taxonomy - Boosts
function boost_taxonomy() {
    $labels = array(
        'name'                       => _x( 'Boost', 'Boost General Name', 'idealbiz' ),
        'singular_name'              => _x( 'Boost', 'Boost Singular Name', 'idealbiz' ),
        'menu_name'                  => __( 'Boosts', 'idealbiz' ),
        'all_items'                  => __( 'All Boosts', 'idealbiz' ),
        'parent_item'                => __( 'Boost Item', 'idealbiz' ),
        'parent_item_colon'          => __( 'Boost Item:', 'idealbiz' ),
        'new_item_name'              => __( 'New Boost Name', 'idealbiz' ),
        'add_new_item'               => __( 'Add New Boost', 'idealbiz' ),
        'edit_item'                  => __( 'Edit Boost', 'idealbiz' ),
        'update_item'                => __( 'Update Boost', 'idealbiz' ),
        'view_item'                  => __( 'View Boost', 'idealbiz' ),
        'separate_items_with_commas' => __( 'Separate Boosts with commas', 'idealbiz' ),
        'add_or_remove_items'        => __( 'Add or remove Boosts', 'idealbiz' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'idealbiz' ),
        'popular_items'              => __( 'Popular Boosts', 'idealbiz' ),
        'search_items'               => __( 'Search Boosts', 'idealbiz' ),
        'not_found'                  => __( 'Not Found', 'idealbiz' ),
        'no_terms'                   => __( 'No Boosts', 'idealbiz' ),
        'items_list'                 => __( 'Boosts list', 'idealbiz' ),
        'items_list_navigation'      => __( 'Boosts list navigation', 'idealbiz' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_in_rest'          => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );
    // here we add/register taxonomies to post type
    register_taxonomy( 'boost', array( 'listing' ), $args );
}
  

/* init post types and taxonomies */
add_action( 'init', 'listing', 0 );
add_action( 'init', 'listing_cat_taxonomy', 1 );
add_action( 'init', 'location_taxonomy', 2 );
add_action( 'init', 'boost_taxonomy', 2 );
add_action( 'init', 'type_listing', 2 );

/* images in post type */
add_theme_support('post-thumbnails');
add_post_type_support( 'listing', 'thumbnail' );


