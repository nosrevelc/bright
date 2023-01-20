<?php
namespace WPFEPP\Element_Containers;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Update_Settings;
use WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Element;
use WPGurus\Forms\Elements\Select;
use WPGurus\Forms\Factories\Element_Factory;

class Update_Settings_Container extends Backend_Element_Container
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
				Elements::TEXT,
				array(
					Element::KEY     => Update_Settings::SETTING_PURCHASE_CODE,
					Element::LABEL   => __('Purchase Code', 'frontend-publishing-pro'),
					Element::POSTFIX => sprintf(
						__('Your Envato purchase code. Auto-updates will not work if you use the purchase code on more than one websites. You can get this code %s.', 'frontend-publishing-pro'),
						sprintf(
							'<a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-">%s</a>',
							__('here', 'frontend-publishing-pro')
						)
					)
				)
			)
		);
	}
}