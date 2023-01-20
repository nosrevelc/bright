<?php
namespace WPGurus\Updates;

use WPGurus\Components\Component;

if (!defined('WPINC')) die;

class Plugin_Updates extends Component
{
	/**
	 * The current version of the plugin
	 * @var string
	 */
	private $current_version;

	/**
	 * Remote URL for fetching plugin info.
	 * @var string
	 */
	private $info_url;

	/**
	 * The plugin slug (plugin_directory/plugin_file.php)
	 * @var string
	 */
	private $plugin;

	/**
	 * The plugin slug (plugin_file)
	 * @var string
	 */
	private $slug;

	/**
	 * Initialize a new instance of the WordPress Auto-Update class
	 *
	 * @param $current_version string
	 * @param $info_url string
	 * @param $plugin string
	 */
	function __construct($current_version, $info_url, $plugin)
	{
		parent::__construct();

		$this->current_version = $current_version;
		$this->info_url = $info_url;
		$this->plugin = $plugin;
		$this->slug = $this->get_slug_for_plugin($plugin);

		$this->register_action('pre_set_site_transient_update_plugins', array($this, 'check_update'));
		$this->register_filter('plugins_api', array($this, 'check_info'), 10, 3);
	}

	/**
	 * Add our self-hosted auto-update information to the filtered transient
	 *
	 * @param $value
	 * @return object $transient
	 */
	public function check_update($value)
	{
		if (isset($value->response[ $this->plugin ])) {
			return $value;
		}

		// Get the remote version
		$plugin_info = $this->fetch_plugin_information();

		// If a newer version is available, add the update
		if ($plugin_info && version_compare($this->current_version, $plugin_info->new_version, '<')) {
			$value->response[ $this->plugin ] = $plugin_info;
		}
		return $value;
	}

	/**
	 * Add our self-hosted plugin description.
	 *
	 * @param boolean $false
	 * @param array $action
	 * @param object $arg
	 * @return bool|object
	 */
	public function check_info($false, $action, $arg)
	{
		if (!isset($arg->slug) || $arg->slug !== $this->slug) {
			return $false;
		}

		$plugin_information = $this->fetch_plugin_information();

		if (!$plugin_information) {
			return $false;
		}

		return $plugin_information;
	}

	/**
	 * Get information about the remote version
	 * @return bool|object
	 */
	public function fetch_plugin_information()
	{
		$request = wp_remote_get($this->info_url);
		if (!is_wp_error($request) && wp_remote_retrieve_response_code($request) === 200) {
			return (object)json_decode($request['body'], true);
		}
		return false;
	}

	/**
	 * Gets slug for the passed plugin name.
	 *
	 * @param $plugin string The plugin name in the form (plugin_directory/plugin_file.php)
	 * @return string The slug in the form plugin_file
	 */
	private function get_slug_for_plugin($plugin)
	{
		list ($t1, $t2) = explode('/', $plugin);
		return str_replace('.php', '', $t2);
	}

	/**
	 * @return string
	 */
	public function get_current_version()
	{
		return $this->current_version;
	}

	/**
	 * @param string $current_version
	 */
	public function set_current_version($current_version)
	{
		$this->current_version = $current_version;
	}

	/**
	 * @return string
	 */
	public function get_info_url()
	{
		return $this->info_url;
	}

	/**
	 * @param string $info_url
	 */
	public function set_info_url($info_url)
	{
		$this->info_url = $info_url;
	}

	/**
	 * @return string
	 */
	public function get_plugin()
	{
		return $this->plugin;
	}

	/**
	 * @param string $plugin
	 */
	public function set_plugin($plugin)
	{
		$this->plugin = $plugin;
	}

	/**
	 * @return string
	 */
	public function get_slug()
	{
		return $this->slug;
	}

	/**
	 * @param string $slug
	 */
	public function set_slug($slug)
	{
		$this->slug = $slug;
	}
}