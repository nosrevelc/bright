<?php

namespace WPFEPP;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Admin_Pages;
use WPFEPP\Constants\Plugin_Components;
use WPFEPP\Factories\Admin_Page_Factory;
use WPFEPP\Factories\Component_Factory;

/**
 * The main plugin class.
 */
class Frontend_Publishing_Pro extends \WPGurus\Components\Component
{
	function __construct()
	{
		parent::__construct();

		$this->register_sub_component(Admin_Page_Factory::make_admin_page(Admin_Pages::FORM_MANAGER));

		$this->register_sub_component(Admin_Page_Factory::make_admin_page(Admin_Pages::PLUGIN_SETTINGS));

		$this->register_sub_component(new \WPFEPP\Shortcodes\Frontend_Form());

		$this->register_sub_component(new \WPFEPP\Shortcodes\Post_Table());

		$this->register_sub_component(new \WPFEPP\Components\Rewrites());

		$this->register_sub_component(new \WPFEPP\Ajax\Frontend_Form_Ajax());

		$this->register_sub_component(new \WPFEPP\Ajax\Form_Details_Form_Ajax());

		$this->register_sub_component(new \WPFEPP\Ajax\Custom_Fields_Form_Ajax());

		$this->register_sub_component(new \WPFEPP\Ajax\Field_Validation_Ajax());

		$this->register_sub_component(new \WPFEPP\Ajax\Dismiss_Nag());

		$this->register_sub_component(new \WPGurus\Forms\Components\Manager());

		$this->register_sub_component(new \WPFEPP\Components\Asset_Manager());

		$this->register_sub_component(new \WPFEPP\Components\Media_Restrictions());

		$this->register_sub_component(new \WPFEPP\Components\TinyMCE_Validation_Fix());

		$this->register_sub_component(new \WPFEPP\Components\DB_Setup());

		$this->register_sub_component(Component_Factory::make_component(Plugin_Components::POST_DELETER));

		$this->register_sub_component(new \WPFEPP\Components\Email_Manager());

		$this->register_sub_component(new \WPFEPP\Components\Post_Previews());

		$this->register_sub_component(new \WPFEPP\Components\Data_Deleter());

		$this->register_sub_component(new \WPFEPP\Data_Mappers\Mapping_Manager());

		$this->register_sub_component(new \WPFEPP\Components\Admin_Messages());

		$this->register_sub_component(new \WPFEPP\Components\Post_Form_Link_Meta_Box());

		$this->register_sub_component(new \WPFEPP\Components\Post_Form_Link_Quick_Edit_Fields());

		$this->register_sub_component(new \WPFEPP\Components\Edit_Post_Link());

		$this->register_sub_component(new \WPFEPP\Components\Custom_Field_Value_Displayer());

		$this->register_sub_component(new \WPFEPP\Components\Custom_Fields_Meta_Box());

		if(is_multisite())
		{
			$this->register_sub_component(new \WPFEPP\Components\WPMU());

			$this->register_sub_component(new \WPFEPP\Admin_Pages\Network_Settings());
		}

		$this->register_action('init', array($this, 'ob_start'));
	}

	public function ob_start()
	{
		ob_start();
	}
}