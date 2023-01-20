<?php
namespace WPFEPP\Factories;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Admin_Pages;
use WPGurus\Forms\Factory;

class Admin_Page_Factory extends Factory
{
	public static function make_admin_page($page_type, $args = array())
	{
		$page = null;
		$page_class = '';

		switch($page_type)
		{
			case Admin_Pages::FORM_MANAGER:
				$page_class = '\WPFEPP\Admin_Pages\Form_Manager';
				break;

			case Admin_Pages::PLUGIN_SETTINGS:
				$page_class = '\WPFEPP\Admin_Pages\Plugin_Settings';
				break;
		}

		if ($page_class) {
			$page = self::create_instance($page_class, $args);
		}

		$page = self::filter_object('_manufactured_admin_page', array($page, $page_type, $args));

		return $page;
	}
}