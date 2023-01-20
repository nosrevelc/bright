<?php
/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link  http://widgilabs.com/
 * @since 1.0.0
 */

namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request;

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @since  1.0.0
 * @author WidgiLabs <dev@widgilabs.com>
 */
class Admin {

	/**
	 * The plugin's instance.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    Plugin
	 */
	private $plugin;

	/**
	 * Permalink settings.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    array
	 */
	private $permalinks = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param Plugin $plugin This plugin's instance.
	 */
	public function __construct( Plugin $plugin ) {

		$permalinks = (array) get_option( 'wlbase_permalinks', array() );

		// Ensure rewrite slugs are set.
		$permalinks['wlbase_type_base'] = untrailingslashit( ! empty( $permalinks['wlbase_type_base'] ) ? $permalinks['wlbase_type_base'] : '' );

		$this->plugin     = $plugin;
		$this->permalinks = $permalinks;
	}

	/**
	 * Adds settings fields to the permalinks admin settings page.
	 *
	 * @since 1.0.0
	 */
	public function add_settings_fields() {
		foreach ( $this->plugin->get_taxonomies() as $taxonomy ) {

			$taxonomy = get_taxonomy( $taxonomy );
			if ( ! $taxonomy ) {
				continue;
			}

			$callback = "{$taxonomy->name}_slug_input";
			if ( ! is_callable( array( $this, $callback ) ) ) {
				continue;
			}

			add_settings_field(
				"wlbase_{$taxonomy->name}_slug",
				$taxonomy->label,
				array( $this, $callback ),
				'permalink',
				'optional'
			);
		}
	}

	/**
	 * Show a `sponsor_type` slug input box.
	 *
	 * @since 1.0.0
	 */
	public function wlbase_type_slug_input() {
		printf(
			'<input type="text" id="wlbase_type_slug" name="wlbase_type_slug" class="regular-text code" value="%s" placeholder="%s">',
			esc_attr( $this->permalinks['wlbase_type_base'] ),
			esc_attr( Plugin::TAXONOMY_SPONSOR_TYPE )
		);
	}

	/**
	 * Save the permalinks settings.
	 *
	 * We need to save the options ourselves; Settings API does not trigger
	 * save for the permalinks page.
	 *
	 * @since 1.0.0
	 */
	public function save_permalinks_settings() {

		if ( ! is_admin() ) {
			return;
		}

		if ( ! isset( $_POST['permalink_structure'] ) ) {
			return;
		}

		if ( function_exists( 'switch_to_locale' ) ) {
			switch_to_locale( get_locale() );
		}

		$permalinks = (array) get_option( 'wlbase_permalinks', array() );

		$wl_base_plugin_type_base = untrailingslashit( ! empty( $_POST['wlbase_type_slug'] ) ? sanitize_text_field( $_POST['wlbase_type_slug'] ) : '' );
		$permalinks['wlbase_type_base'] = $wl_base_plugin_type_base;

		update_option( 'wlbase_permalinks', $permalinks );

		if ( function_exists( 'restore_current_locale' ) ) {
			restore_current_locale();
		}
	}
}
