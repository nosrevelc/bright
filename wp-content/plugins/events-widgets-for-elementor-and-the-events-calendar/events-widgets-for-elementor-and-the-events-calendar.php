<?php
/**
 * Plugin Name: Events Widgets For Elementor And The Events Calendar
 * Description: Events Calendar Templates Builder For Elementor to create a beautiful calendar in  page and post.
 * Plugin URI:  https://coolplugins.net
 * Version:     1.6.2
 * Author:      Cool Plugins
 * Author URI:  https://coolplugins.net/
 * Text Domain: ectbe
 * Elementor tested up to: 3.9.1
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( defined( 'ECTBE_VERSION' ) ) {
	return;
}
define( 'ECTBE_VERSION', '1.6.2' );
define( 'ECTBE_FILE', __FILE__ );
define( 'ECTBE_PATH', plugin_dir_path( ECTBE_FILE ) );
define( 'ECTBE_URL', plugin_dir_url( ECTBE_FILE ) );

register_activation_hook( ECTBE_FILE, array( 'Events_Calendar_Addon', 'ectbe_activate' ) );
register_deactivation_hook( ECTBE_FILE, array( 'Events_Calendar_Addon', 'ectbe_deactivate' ) );
/**
 * Class Events_Calendar_Addon
 */
final class Events_Calendar_Addon {
	/**
	 * Plugin instance.
	 *
	 * @var Events_Calendar_Addon
	 * @access private
	 */
	private static $instance = null;
	/**
	 * Get plugin instance.
	 *
	 * @return Events_Calendar_Addon
	 * @static
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	/**
	 * Constructor.
	 *
	 * @access private
	 */
	private function __construct() {
		$this->include_files();
		// Load the plugin after Elementor (and other plugins) are loaded.
		add_action( 'plugins_loaded', array( $this, 'ectbe_plugins_loaded' ) );
	}
	function include_files() {
		require_once __DIR__ . '/admin/events-addon-page/events-addon-page.php';
		cool_plugins_events_addon_settings_page( 'the-events-calendar', 'cool-plugins-events-addon', '📅 Events Addons For The Events Calendar' );
	}
	/**
	 * Code you want to run when all other plugins loaded.
	 */
	function ectbe_plugins_loaded() {
		// Notice if the Elementor is not active
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'ectbe_fail_to_load' ) );
			return;
		}
		if ( ! class_exists( 'Tribe__Events__Main' ) || ! defined( 'Tribe__Events__Main::VERSION' ) ) {
			add_action( 'admin_notices', array( $this, 'ectbe_Install_ECT_Notice' ) );
		}
		load_plugin_textdomain( 'ectbe', false, basename( dirname( __FILE__ ) ) . '/languages/' );
		
		// Require the main plugin file
		require __DIR__ . '/includes/functions.php';
		require __DIR__ . '/includes/class-ectbe.php';
		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'ectbe_show_upgrade_notice' ) );
			require __DIR__ . '/includes/class-admin-notice.php';
			require_once __DIR__ . '/feedback/admin-feedback-form.php';
		}
	}   // end of ctla_loaded()
	public function ectbe_show_upgrade_notice() {

		$installed_version = get_option('ectbe-v');
		if(version_compare( $installed_version,'1.6', '<' )){
			ectbe_create_admin_notice(
				array(
					'id' => 'ectbe-major-update-notice',
					'message' => '<strong>Major Update Notice!</strong> Please update your events widget settings if you face any style issue after an update of <strong> Events Widgets For Elementor And The Events Calendar</strong>.',
					'review_interval' => 0,
				)
			);
		}


			/*** Plugin review notice file */
			ectbe_create_admin_notice(
				array(
					'id'              => 'ectbe-review-box',  // required and must be unique
					'slug'            => 'ectbe',      // required in case of review box
					'review'          => true,     // required and set to be true for review box
					'review_url'      => esc_url( 'https://wordpress.org/support/plugin/events-widgets-for-elementor-and-the-events-calendar/reviews/?filter=5#new-post' ), // required
					'plugin_name'     => 'Events Widgets For Elementor And The Events Calendar',    // required
					'logo'            => ECTBE_URL . 'assets/images/events-widgets-elementor-logo.png',    // optional: it will display logo
					'review_interval' => 3,                    // optional: this will display review notice
															// after 5 days from the installation_time
															// default is 3
				)
			);
	}
	// notice for installation TEC parent plugin installation
	public function ectbe_Install_ECT_Notice() {
		if ( current_user_can( 'activate_plugins' ) ) {
			$url   = 'plugin-install.php?tab=plugin-information&plugin=the-events-calendar&TB_iframe=true';
			$title = __( 'The Events Calendar', 'tribe-events-ical-importer' );
			printf(
				'<div class="error CTEC_Msz"><p>' .
				esc_html( __( '%1$s %2$s', 'ectbe' ) ),
				esc_html( __( 'In order to use Events Widgets For Elementor And The Events Calendar plugin, Please first install the latest version of', 'ectbe' ) ),
				sprintf(
					'<a href="%s" class="thickbox" title="%s">%s</a>',
					esc_url( $url ),
					esc_html( $title ),
					esc_html( $title )
				) . '</p></div>'
			);
		}
	}
	function ectbe_fail_to_load() {
		if ( ! is_plugin_active( 'elementor/elementor.php' ) ) : ?>
			<div class="notice notice-warning is-dismissible">
			<p><?php echo '<a href="https://wordpress.org/plugins/elementor/"  target="_blank" >' . esc_html__( 'Elementor Page Builder', 'ccew' ) . '</a>' . wp_kses_post( __( ' must be installed and activated for using "<strong>Events Widgets For Elementor And The Events Calendar</strong>" ', 'ectbe' ) ); ?></p>
			</div>
			<?php
		endif;
	}
	/**
	 * Run when activate plugin.
	 */
	public static function ectbe_activate() {
		update_option( 'ectbe-v', ECTBE_VERSION );
		update_option( 'ectbe-type', 'FREE' );
		update_option( 'ectbe-installDate', gmdate( 'Y-m-d h:i:s' ) );
	}
	/**
	 * Run when deactivate plugin.
	 */
	public static function ectbe_deactivate() {
	}
}
function Events_Calendar_Addon() {
	return Events_Calendar_Addon::get_instance();
}
Events_Calendar_Addon();
