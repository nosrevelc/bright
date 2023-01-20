<?php
namespace WPFEPP\Factories;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Tabbed_Interfaces;
use WPGurus\Forms\Factory;

class Tabbed_Interface_Factory extends Factory
{
	public static function make_interface($interface_type, $args = array())
	{
		$interface = null;
		$interface_class = '';

		switch($interface_type)
		{
			case Tabbed_Interfaces::POST_LIST_INTERFACE:
				$interface_class = '\WPFEPP\Tabbed_Interfaces\Post_List_Interface';
				break;

			case (Tabbed_Interfaces::PLUGIN_SETTINGS):
			case (Tabbed_Interfaces::FORM_MANAGER):
				$interface_class = '\WPGurus\Tabs\Tabbed_Interface';
				break;
		}

		if ($interface_class) {
			$interface = self::create_instance($interface_class, $args);
		}

		$interface = self::filter_object('_manufactured_tabbed_interfaces', array($interface, $interface_type, $args));

		return $interface;
	}
}