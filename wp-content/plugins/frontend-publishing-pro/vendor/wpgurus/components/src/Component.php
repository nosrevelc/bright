<?php

namespace WPGurus\Components;

if (!defined('WPINC')) die;

use WPGurus\Config\Config_Loader;

/**
 *
 */
abstract class Component
{
	/**
	 * @var Component[]
	 */
	private $sub_components = array();

	protected $plugin_prefix;

	protected $plugin_version;

	protected $debug;

	private $actions = array();

	private $filters = array();

	private $activation_actions = array();

	private $installation_actions = array();

	private $activation_action;

	private $version_option;

	function __construct()
	{
		// TODO: Get the config object once and use it everywhere
		$this->plugin_prefix = Config_Loader::get_config('prefix');
		$this->plugin_version = Config_Loader::get_config('version');
		$this->plugin_version = $this->plugin_version ? $this->plugin_version : '1.0';
		$this->debug = Config_Loader::get_config('debug');

		$this->activation_action = $this->plugin_prefix . '_activation';
		$this->version_option = $this->plugin_prefix . '_version';
	}

	public function get_version()
	{
		return $this->plugin_version;
	}

	public function get_last_version()
	{
		return get_site_option($this->version_option, '0');
	}

	public function register_sub_component($component)
	{
		$this->sub_components[] = $component;
	}

	private function register($hooks, $hook, $callback, $priority, $args)
	{

		$hooks[] = array(
			'hook'     => $hook,
			'callback' => is_array($callback) ? $callback : array($this, $callback),
			'priority' => $priority,
			'args'     => $args
		);

		return $hooks;
	}

	protected function register_action($hook, $callback, $priority = 10, $args = 1)
	{
		$this->actions = $this->register($this->actions, $hook, $callback, $priority, $args);
	}

	protected function register_activation_action($callback, $priority = 10, $args = 1)
	{
		$this->activation_actions = $this->register($this->activation_actions, $this->activation_action, $callback, $priority, $args);
	}

	protected function register_installation_action($callback, $priority = 10, $args = 1)
	{
		$this->installation_actions = $this->register($this->installation_actions, $this->activation_action, $callback, $priority, $args);
	}

	private function add_actions($actions)
	{
		if (is_array($actions)) {
			foreach ($actions as $action) {
				add_action($action['hook'], $action['callback'], $action['priority'], $action['args']);
			}
		}
	}

	protected function register_filter($hook, $callback, $priority = 10, $args = 1)
	{
		$this->filters = $this->register($this->filters, $hook, $callback, $priority, $args);
	}

	private function add_filters($filters)
	{
		if (is_array($filters)) {
			foreach ($filters as $filter) {
				add_filter($filter['hook'], $filter['callback'], $filter['priority'], $filter['args']);
			}
		}
	}

	public function update_plugin_version()
	{
		update_site_option($this->version_option, $this->plugin_version);
	}

	protected function updates_required()
	{
		return version_compare($this->get_version(), $this->get_last_version(), '>') || $this->debug;
	}

	public function run()
	{

		// Before performing the actions of this class lets perform the actions of its sub-components
		foreach ($this->sub_components as $component) {
			$component->run();
		}

		// Register all the actions and filters with WordPress
		$this->add_filters($this->filters);

		$this->add_actions($this->actions);

		// Register the activation function with WordPress
		$this->add_actions($this->activation_actions);

		// Register the installation function with WordPress
		if (
		$this->updates_required()
		) {
			$this->register_installation_action(array($this, 'update_plugin_version'), PHP_INT_MAX);

			$this->add_actions($this->installation_actions);
		}
	}
}