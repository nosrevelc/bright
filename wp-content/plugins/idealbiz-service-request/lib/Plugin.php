<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link  http://widgilabs.com/
 * @since 1.0.0
 */

namespace WidgiLabs\WP\Plugin\IdealBiz\Service\Request;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since  1.0.0
 * @author WidgiLabs <dev@widgilabs.com>
 */
class Plugin {

	/**
	 * Service request post type slug.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	const POST_TYPE_SERVICE_REQUEST = 'service_request';

	/**
	 * Service message post type slug.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	const POST_TYPE_SERVICE_MESSAGE = 'service_message';

	/**
	 * Service contract post type slug.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	const POST_TYPE_SERVICE_CONTRACT = 'service_contract';

	/**
	 * Sponsor type taxonomy slug.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	const TAXONOMY_WLBASE_TYPE = 'base_cpt_type';

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $name;

	/**
	 * The current version of the plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $version;

	/**
	 * The custom post types.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array
	 */
	protected $post_types = array();

	/**
	 * The custom taxonomies.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array
	 */
	protected $taxonomies = array();

	/**
	 * Define the core functionality of the plugin.
	 *
	 * @since 1.0.0
	 * @param string $name    Plugin name.
	 * @param string $version Plugin version.
	 */
	public function __construct( $name, $version ) {
		$this->name    = $name;
		$this->version = $version;
	}

	/**
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function run() {
		$this->set_locale();
		$this->register_settings();
		$this->register_helper_hooks();
		$this->register_post_types();
		$this->define_admin_hooks();
		$this->extend_rest();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since  1.0.0
	 * @return string The name of the plugin.
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since  1.0.0
	 * @return string The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve the registered custom post types.
	 *
	 * @since  1.0.0
	 * @return string The registered custom post types.
	 */
	public function get_post_types() {
		return $this->post_types;
	}

	/**
	 * Retrieve the registered custom taxonomies.
	 *
	 * @since  1.0.0
	 * @return string The registered custom taxonomies.
	 */
	public function get_taxonomies() {
		return $this->taxonomies;
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function set_locale() {
		$i18n = new I18n();
		$i18n->set_domain( $this->get_name() );
		$i18n->load_plugin_textdomain();
	}

	/**
	 * Register the custom post types.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function register_post_types() {

		$post_types = array(
			static::POST_TYPE_SERVICE_REQUEST  => new PostType\ServiceRequest( $this, static::POST_TYPE_SERVICE_REQUEST ),
			static::POST_TYPE_SERVICE_CONTRACT => new PostType\ServiceContract( $this, static::POST_TYPE_SERVICE_CONTRACT ),
			static::POST_TYPE_SERVICE_MESSAGE  => new PostType\ServiceMessage( $this, static::POST_TYPE_SERVICE_MESSAGE ),
		);

		$this->post_types = array_keys( $post_types );

		foreach ( $post_types as $post_type ) {
			add_action( 'init', array( $post_type, 'register' ) );
			add_action( 'init', array( $post_type, 'register_hooks' ), 20 );
		}
	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function define_admin_hooks() {
		$admin = new Admin( $this );
		add_action( 'admin_menu', array( $admin, 'add_settings_fields' ), 20 );
		add_action( 'admin_init', array( $admin, 'save_permalinks_settings' ) );
	}

	private function register_helper_hooks() {
		// Gravity forms helper hooks.
		$gforms_helpers = array(
			new Gforms\HelperServiceRequest(),
			new Gforms\HelperServiceMessage(),
			new Gforms\HelperServiceContract(),
			new Gforms\HelperServiceSatisfaction(),
		);

		foreach ( $gforms_helpers as $helper ) {

			$helper->register_hooks();
		}

		// WooCommerce helper.
		$woocommerce_helper = new WooCommerce\Helper();
		$woocommerce_helper->register_hooks();
	}

	/**
	 * Register rest api response extended custom fields.
	 *
	 * @since 1.0.0
	 * @access private
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	private function extend_rest() {
		// Register "agendalx/v1" namespace routes.
		$service_request_rest = new Rest\ServiceRequestRest();
		add_action( 'rest_api_init', array( $service_request_rest, 'register_routes' ) );
	}

	/**
	 * Load the service settings fields on idealbiz settings page.
	 *
	 * @return void
	 * @since 1.0.0
	 * @author Telmo Teixeira <telmo@widgilabs.com>
	 */
	public function register_settings() {
		$settings = new Fields\Settings();
		add_filter( 'idealbiz_settings_page_fields', array( $settings, 'plugin_settings_fields' ) );
	}
}
