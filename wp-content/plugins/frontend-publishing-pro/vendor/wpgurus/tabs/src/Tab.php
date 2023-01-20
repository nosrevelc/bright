<?php

namespace WPGurus\Tabs;

if (!defined('WPINC')) die;

/**
 * An abstract class representing a single tab in a tabbed interface.
 */
abstract class Tab extends \WPGurus\Templating\Renderable
{
	/**
	 * @var string The visible label of the tab.
	 */
	private $label;

	/**
	 * @var string The name of the tab that will be used in the URL.
	 */
	private $name;

	/**
	 * The position in the tabbed interface at which this tab should be displayed.
	 * @var int
	 */
	private $order;

	function __construct($name, $label, $order = 0)
	{
		$this->name = $name;
		$this->label = $label;
		$this->order = $order;
	}

	/**
	 * Can be used to check for the currently active tab.
	 *
	 * @param $is_first
	 * @return boolean
	 */
	abstract function is_active($is_first);

	/**
	 * Creates the URL for the current tab.
	 *
	 * @return string
	 */
	abstract function make_url();

	/**
	 * Getter for label.
	 * @return string
	 */
	public function get_label()
	{
		return $this->label;
	}

	/**
	 * Getter for name.
	 *
	 * @return string
	 */
	public function get_name()
	{
		return $this->name;
	}

	/**
	 * Sets tab order.
	 * @return int
	 */
	public function get_order()
	{
		return $this->order;
	}

	/**
	 * Returns tab order.
	 * @param int $order
	 */
	public function set_order($order)
	{
		$this->order = $order;
	}
}