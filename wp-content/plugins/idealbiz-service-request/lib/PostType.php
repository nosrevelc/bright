<?php
/**
 * Post type handler
 *
 * @link  http://widgilabs.com/
 * @since 1.0.0
 */

namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request;

/**
 * Post type handler.
 *
 * @since  1.0.0
 * @author WidgiLabs <dev@widgilabs.com>
 */
abstract class PostType {

	/**
	 * The plugin's instance.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    Plugin
	 */
	protected $plugin;

	/**
	 * The custom post type slug.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $slug;

	/**
	 * A list of taxonomies associated with the custom post type.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array
	 */
	protected $taxonomies = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param Plugin $plugin     This plugin's instance.
	 * @param string $slug       The post type slug.
	 * @param array  $taxonomies A list of taxonomies associated with the post type.
	 */
	public function __construct( Plugin $plugin, $slug, $taxonomies = array() ) {
		$this->plugin     = $plugin;
		$this->slug       = $slug;
		$this->taxonomies = $taxonomies;
	}

	/**
	 * Retrieve the post type slug.
	 *
	 * @since  1.0.0
	 * @return string The post type slug.
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Retrieve the list of taxonomies associated with the post type.
	 *
	 * @since  1.0.0
	 * @return string The list of taxonomies associated with the post type.
	 */
	public function get_taxonomies() {
		return $this->slug;
	}

	/**
	 * Register custom post type.
	 *
	 * @since 1.0.0
	 */
	abstract public function register();

	/**
	 * Register hooks.
	 *
	 * @since 1.0.0
	 */
	abstract public function register_hooks();

	/**
	 * Register custom fields.
	 *
	 * @since 1.0.0
	 */
	abstract public function register_fields();
}
