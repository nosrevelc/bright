<?php
/**
 * Taxonomy handler
 *
 * @link  http://widgilabs.com/
 * @since 1.0.0
 */

namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request;

/**
 * Taxonomy handler.
 *
 * @since  1.0.0
 * @author WidgiLabs <dev@widgilabs.com>
 */
abstract class Taxonomy {

	/**
	 * The plugin's instance.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    Plugin
	 */
	protected $plugin;

	/**
	 * The taxonomy slug.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $slug;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param Plugin $plugin This plugin's instance.
	 * @param string $slug   The taxonomy slug.
	 */
	public function __construct( Plugin $plugin, $slug ) {
		$this->plugin = $plugin;
		$this->slug   = $slug;
	}

	/**
	 * Register custom taxonomy.
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
}
