<?php
/**
 * Registers the `base_cpt_type` taxonomy.
 *
 * @link  http://widgilabs.com/
 * @since 1.0.0
 */

namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Taxonomy;

/**
 * Registers the `base_cpt_type` taxonomy.
 *
 * @since  1.0.0
 * @author WidgiLabs <dev@widgilabs.com>
 */
class WLBaseType extends \WidgiLabs\WP\Plugin\IdealBiz\Service\Request\Taxonomy {

	/**
	 * Register custom taxonomy.
	 *
	 * @since 1.0.0
	 */
	public function register() {

		$labels = array(
			'name'                       => _x( 'BaseCPT Types', 'Taxonomy General Name', 'wlbase' ),
			'singular_name'              => _x( 'BaseCPT Type', 'Taxonomy Singular Name', 'wlbase' ),
			'menu_name'                  => __( 'Types', 'wlbase' ),
			'all_items'                  => __( 'All BaseCPT Types', 'wlbase' ),
			'parent_item'                => __( 'Parent BaseCPT Type', 'wlbase' ),
			'parent_item_colon'          => __( 'Parent BaseCPT Type:', 'wlbase' ),
			'new_item_name'              => __( 'New BaseCPT Type Name', 'wlbase' ),
			'add_new_item'               => __( 'Add New BaseCPT Type', 'wlbase' ),
			'edit_item'                  => __( 'Edit BaseCPT Type', 'wlbase' ),
			'update_item'                => __( 'Update BaseCPT Type', 'wlbase' ),
			'view_item'                  => __( 'View BaseCPT Type', 'wlbase' ),
			'separate_items_with_commas' => __( 'Separate types with commas', 'wlbase' ),
			'add_or_remove_items'        => __( 'Add or remove types', 'wlbase' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'wlbase' ),
			'popular_items'              => __( 'Popular BaseCPT Types', 'wlbase' ),
			'search_items'               => __( 'Search BaseCPT Types', 'wlbase' ),
			'not_found'                  => __( 'Not Found', 'wlbase' ),
			'no_terms'                   => __( 'No types', 'wlbase' ),
			'items_list'                 => __( 'BaseCPT types list', 'wlbase' ),
			'items_list_navigation'      => __( 'BaseCPT types list navigation', 'wlbase' ),
		);

		$capabilities = array(
			'assign_terms' => 'edit_posts',
			'manage_terms' => 'manage_categories',
			'edit_terms'   => 'manage_categories',
			'delete_terms' => 'manage_categories',
		);

		// TODO: this should be improved.
		$permalinks = (array) get_option( 'wlbase_permalinks', array() );

		$rewrite = array(
			'slug'         => ! empty( $permalinks[ "{$this->slug}_base" ] ) ? $permalinks[ "{$this->slug}_base" ] : $this->slug,
			'with_front'   => true,
			'hierarchical' => false,
		);

		$args = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => false,
			'capabilities'      => $capabilities,
			'show_in_rest'      => true,
			'rewrite'           => $rewrite,
		);

		register_taxonomy( $this->slug, null, $args );
	}

	/**
	 * Register hooks.
	 *
	 * @since 1.0.0
	 */
	public function register_hooks() {
		add_filter( "manage_edit-{$this->slug}_columns", array( $this, 'manage_columns' ), 20 );
	}

	/**
	 * Filter taxonomies columns.
	 *
	 * @since  1.0.0
	 * @param  array $columns An array of column names.
	 * @return array          Possibly-modified array of column names.
	 */
	public function manage_columns( $columns ) {
		unset( $columns['slug'] );
		unset( $columns['posts'] );
		return $columns;
	}
}
