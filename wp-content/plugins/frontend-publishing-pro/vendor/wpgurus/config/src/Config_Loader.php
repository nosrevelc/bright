<?php

namespace WPGurus\Config;

if (!defined('WPINC')) die;

class Config_Loader
{
	/**
	 * An instance of the class. This is used for accessing the non-static methods of the class.
	 * @var Config_Loader
	 */
	private static $instance;

	/**
	 * Once the configuration array for an object is retrieved, it is stored in a static cache for quick repeated access.
	 * @var array Configuration arrays of various plugins.
	 */
	private static $config_cache = array();

	/**
	 * The name of the config file.
	 */
	const CONFIG_FILE = 'plugin-config.php';

	/**
	 * A private function that returns the currently available instance of the class. If one is not available, it is created.
	 * @return Config_Loader Working instance of the class.
	 */
	public static function get_instance()
	{
		if (!self::$instance) {
			self::$instance = new Config_Loader();
		}

		return self::$instance;
	}

	public static function set_instance($instance)
	{
		self::$instance = $instance;
	}

	/**
	 * A public function that returns the contents of a plugin configuration file.
	 * @return array|null A configuration array or null if one couldn't be found.
	 */
	public static function load()
	{
		$loader = self::get_instance();

		$plugin_file_path = $loader->get_calling_plugin_file_path();
		$plugin_name = $loader->get_plugin_name_from_path($plugin_file_path);

		$calling_plugin_directory = $loader->get_plugins_directory() . $plugin_name;
		$config_path = trailingslashit($calling_plugin_directory) . self::CONFIG_FILE;

		if (!isset(self::$config_cache[ $config_path ])) {
			self::$config_cache[ $config_path ] = $loader->include_file($config_path);
		}

		return self::$config_cache[ $config_path ];
	}

	/**
	 * A public function meant to return a single item from the configuration array.
	 * @param $key string A key for looking up an item from the configuration array.
	 * @return mixed An item from the configuration array.
	 */
	static function get_config($key)
	{
		$all_config = self::load();
		$config = null;

		if ($all_config != null && isset($all_config[ $key ])) {
			$config = $all_config[ $key ];
		}

		return $config;
	}

	/**
	 * Gets the WordPress plugin directory path.
	 * @return null|string WordPress plugin directory path.
	 */
	private function get_plugins_directory()
	{
		$plugins_dir = '';

		if (defined('WP_PLUGIN_DIR')) {
			$plugins_dir = wp_normalize_path(trailingslashit(WP_PLUGIN_DIR));
		} else if (defined(ABSPATH)) {
			// If not defined, try to construct the path using ABSPATH and 'wp-content/plugins'. Even the constructed path will work on most sites.
			$plugins_dir = wp_normalize_path(trailingslashit(ABSPATH)) . 'wp-content/plugins/';
		}

		return $plugins_dir;
	}

	/**
	 * Fetches the file path of the plugin currently making the call by looking at debug backtrace.
	 * @return string
	 */
	private function get_calling_plugin_file_path()
	{
		$loader = self::get_instance();
		$plugins_directory = $loader->get_plugins_directory();
		$file = '';

		$backtrace = debug_backtrace();
		for ($index = 0; $index < count($backtrace); $index++) {
			if (!isset($backtrace[ $index ]['file'])) {
				continue;
			}

			$backtrace_file = wp_normalize_path($backtrace[ $index ]['file']);

			if (strpos($backtrace_file, $plugins_directory) !== false) {
				$file = $backtrace_file;
			}
		}

		return $file;
	}

	/**
	 * Extracts the plugin name from the path of one of its files.
	 * @param $plugin_file_path string Plugin path.
	 */
	private function get_plugin_name_from_path($plugin_file_path)
	{
		$plugin_basename = plugin_basename($plugin_file_path);
		$plugin_basename_parts = explode('/', wp_normalize_path($plugin_basename));
		return $plugin_basename_parts[0];
	}

	/**
	 * Includes the file if it is valid otherwise returns null. Unfortunately this internal function must me kept public for testing because language constructs like include can not be mocked.
	 * @param $config_path string File path.
	 * @return array|null Configuration array or null.
	 */
	public function include_file($config_path)
	{
		return is_file($config_path) && file_exists($config_path) ? include $config_path : null;
	}
}