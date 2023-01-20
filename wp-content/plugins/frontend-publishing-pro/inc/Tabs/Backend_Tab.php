<?php

namespace WPFEPP\Tabs;

if (!defined('WPINC')) die;

/**
 * This abstract class represents a tab that can be displayed in the WordPress admin area.
 *
 * Class Backend_Tab
 * @package WPFEPP\Tabs
 */
abstract class Backend_Tab extends \WPGurus\Tabs\Tab
{

	const QUERY_VAR_TAB = 'tab';

	function __construct($name, $label)
	{
		parent::__construct($name, $label);
	}

	/**
	 * Matches the name of the current tab with the query variable used to build the URL to check if this is the active tab.
	 *
	 * @param $is_first
	 * @return bool
	 */
	function is_active($is_first)
	{
		if (!isset($_GET[ self::QUERY_VAR_TAB ])) {
			if ($is_first)
				return true;
			else
				return false;
		}

		return $_GET[ self::QUERY_VAR_TAB ] == $this->get_name();
	}

	/**
	 * Adds the name of the tab to the query string to build a URL for the tab.
	 *
	 * @return string
	 */
	function make_url()
	{
		$tab_url = esc_url_raw(remove_query_arg(array(self::QUERY_VAR_TAB, 'success')));
		$tab_url = esc_url(add_query_arg(array(self::QUERY_VAR_TAB => $this->get_name()), $tab_url));
		return $tab_url;
	}
}