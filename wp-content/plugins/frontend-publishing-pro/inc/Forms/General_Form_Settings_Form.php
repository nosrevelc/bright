<?php
namespace WPFEPP\Forms;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Option_Ids;
use WPFEPP\Element_Containers\Form_Settings_Container;

class General_Form_Settings_Form extends Backend_Options_Form
{
	function __construct()
	{
		parent::__construct(
			'general-form-settings',
			Option_Ids::OPTION_GENERAL_FORM_SETTINGS
		);

		$element_container = new Form_Settings_Container(
			$this->get_current_values()
		);

		$this->set_element_container($element_container);
	}
}