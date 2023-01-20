<?php
namespace WPFEPP\Element_Containers;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Data_Settings;
use WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Element;
use WPGurus\Forms\Factories\Element_Factory;

class Data_Settings_Container extends Backend_Element_Container
{
	public function __construct($current_values = array())
	{
		parent::__construct($current_values);

		$this->build_form_elements();
	}

	private function build_form_elements()
	{
		$this->add_element(
			Element_Factory::make_element(
				Elements::CHECKBOX,
				array(
					Element::KEY     => Data_Settings::SETTINGS_DELETE_DATA,
					Element::LABEL   => __('Delete All Data on Uninstallation', 'frontend-publishing-pro'),
					Element::POSTFIX => __('If you want to permanently remove this plugin then you might want to set this to true.', 'frontend-publishing-pro'),
					Element::VALUE   => false
				)
			)
		);
	}
}