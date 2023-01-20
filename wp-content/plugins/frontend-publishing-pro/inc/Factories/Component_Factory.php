<?php
namespace WPFEPP\Factories;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Plugin_Components;
use WPGurus\Forms\Factory;

class Component_Factory extends Factory
{
	public static function make_component($component_type, $args = array())
	{
		$component = null;
		$component_class = '';

		switch ($component_type) {
			case Plugin_Components::ADMIN_MESSAGES:
				$component_class = '\WPFEPP\Components\Admin_Messages';
				break;

			case Plugin_Components::ASSET_MANAGER:
				$component_class = '\WPFEPP\Components\Asset_Manager';
				break;

			case Plugin_Components::DATA_DELETER:
				$component_class = '\WPFEPP\Components\Data_Deleter';
				break;

			case Plugin_Components::DB_SETUP:
				$component_class = '\WPFEPP\Components\DB_Setup';
				break;

			case Plugin_Components::EMAIL_MANAGER:
				$component_class = '\WPFEPP\Components\Email_Manager';
				break;

			case Plugin_Components::MEDIA_RESTRICTIONS:
				$component_class = '\WPFEPP\Components\Media_Restrictions';
				break;

			case Plugin_Components::PLUGIN_COMPONENTS:
				$component_class = '\WPFEPP\Components\Plugin_Components';
				break;

			case Plugin_Components::POST_DELETER:
				$component_class = '\WPFEPP\Components\Post_Deleter';
				break;

			case Plugin_Components::POST_PREVIEWS:
				$component_class = '\WPFEPP\Components\Post_Previews';
				break;

			case Plugin_Components::REWRITES:
				$component_class = '\WPFEPP\Components\Rewrites';
				break;

			case Plugin_Components::TINYMCE_VALIDATION_FIX:
				$component_class = '\WPFEPP\Components\TinyMCE_Validation_Fix';
				break;
		}

		if($component_class)
		{
			$component = self::create_instance($component_class, $args);
		}

		$component = self::filter_object('_manufactured_component', array($component, $component_type, $args));

		return $component;
	}
}