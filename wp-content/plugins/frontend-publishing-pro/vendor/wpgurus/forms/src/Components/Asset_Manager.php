<?php
namespace WPGurus\Forms\Components;

if (!defined('WPINC')) die;

use \WPGurus\Forms\Constants\Assets;

/**
 * Registers all the JS and CSS assets required by the different parts of the package. These assets can later be enqueued by the elements themselves.
 *
 * Class Asset_Manager
 * @package WPGurus\Forms\Components
 */
class Asset_Manager extends \WPGurus\Components\Component
{
	function __construct()
	{
		parent::__construct();

		$this->register_action('init', array($this, 'register_assets'));
	}

	/**
	 * Builds and returns the URL of the assets folder.
	 *
	 * @return string
	 */
	static public function get_assets_url()
	{
		return plugin_dir_url(dirname(dirname(__FILE__))) . 'assets/';
	}

	/**
	 * Builds and returns the URL of the folder which contains external libraries installed with bower.
	 *
	 * @return string
	 */
	static public function get_libs_url()
	{
		return self::get_assets_url() . 'libs/';
	}

	/**
	 * Registers all the JS and CSS assets with WordPress.
	 */
	function register_assets()
	{
		// TODO: This needs to be tested rigorously.
		$assets_url = self::get_assets_url();
		$libs_url = self::get_libs_url();

		$common_deps = array(
			Assets::ELEMENTS_JS,
			'jquery'
		);

		wp_register_script(Assets::ELEMENTS_JS, $assets_url . 'js/elements.js', 'jquery', $this->plugin_version, true);

		// Font Awesome
		wp_register_style(Assets::FONT_AWESOME_CSS, $libs_url . 'font-awesome/css/font-awesome.min.css', array(), $this->plugin_version);

		// Raty jquery plugin
		wp_register_script(Assets::RATY_JS, $libs_url . 'raty/lib/jquery.raty.js', $common_deps, $this->plugin_version, true);
		wp_register_style(Assets::RATY_CSS, $libs_url . 'raty/lib/jquery.raty.css', array(), $this->plugin_version);

		// Select2 jquery plugin
		wp_register_script(Assets::SELECT2_JS, $libs_url . 'select2/dist/js/select2.full.min.js', $common_deps, $this->plugin_version, true);
		wp_register_style(Assets::SELECT2_CSS, $libs_url . 'select2/dist/css/select2.min.css', array(), $this->plugin_version);

		// WP Media Lib Element
		wp_register_script(Assets::WP_MEDIA_LIB_ELEMENT_JS, $assets_url . 'js/media-element.js', $common_deps, $this->plugin_version, true);
		wp_register_style(Assets::WP_MEDIA_LIB_ELEMENT_CSS, $assets_url . 'css/media-element.css', array(Assets::FONT_AWESOME_CSS), $this->plugin_version);

		// WP Media Lib Element
		wp_register_script(Assets::MEDIA_FILE_ELEMENT_JS, $assets_url . 'js/media-file.js', $common_deps, $this->plugin_version, true);
		wp_register_style(Assets::MEDIA_FILE_ELEMENT_CSS, $assets_url . 'css/media-file.css', array(Assets::FONT_AWESOME_CSS), $this->plugin_version);

		// DateTimePicker
		wp_register_script(Assets::DATE_TIME_PICKER_JS, $libs_url . 'datetimepicker/build/jquery.datetimepicker.full.min.js', $common_deps, $this->plugin_version, true);
		wp_register_style(Assets::DATE_TIME_PICKER_CSS, $libs_url . 'datetimepicker/jquery.datetimepicker.css', array(), $this->plugin_version);

		wp_register_script(Assets::COLOR_PICKER_JS, admin_url('js/iris.min.js'), array(Assets::ELEMENTS_JS, 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch'), $this->plugin_version, false);

		wp_register_style(Assets::ADMIN_CSS, admin_url('css/wp-admin.css'), array(), $this->plugin_version);
		wp_register_style(Assets::OPEN_SANS_CSS, 'https://fonts.googleapis.com/css?family=Open+Sans:400,300', array(), $this->plugin_version);

		wp_register_script(Assets::IFRAME_RESIZER_JS, $libs_url . 'iframe-resizer/js/iframeResizer.min.js', array(), $this->plugin_version, true);
		wp_register_script(Assets::IFRAME_RESIZER_CONTENT_WINDOW_JS, $libs_url . 'iframe-resizer/js/iframeResizer.contentWindow.min.js', array(), $this->plugin_version, true);

		wp_register_script(Assets::RICHTEXT_IFRAME_JS, $assets_url . 'js/richtext-iframe.js', array('jquery', Assets::IFRAME_RESIZER_JS), $this->plugin_version, true);
		wp_register_style(Assets::RICHTEXT_IFRAME_CSS, $assets_url . 'css/richtext-iframe.css', array(), $this->plugin_version);

		wp_register_script(Assets::RICHTEXT_IFRAME_CONTENT_JS, $assets_url . 'js/richtext-iframe-content.js', array('jquery', Assets::IFRAME_RESIZER_CONTENT_WINDOW_JS), $this->plugin_version, true);
		wp_register_style(Assets::RICHTEXT_IFRAME_CONTENT_CSS, $assets_url . 'css/richtext-iframe-content.css', array(Assets::ADMIN_CSS, Assets::OPEN_SANS_CSS), $this->plugin_version);

		wp_register_script(ASSETS::GOOGLE_RECAPTCHA, 'https://www.google.com/recaptcha/api.js', array(), $this->plugin_version);
	}
}