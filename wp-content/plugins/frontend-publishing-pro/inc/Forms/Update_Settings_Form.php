<?php
namespace WPFEPP\Forms;

use WPFEPP\Constants\Option_Ids;

if (!defined('WPINC')) die;

class Update_Settings_Form extends Backend_Options_Form
{
	function __construct()
	{
		parent::__construct(
			'update-settings',
			Option_Ids::OPTION_UPDATE_SETTINGS,
			true
		);

		$element_container = new \WPFEPP\Element_Containers\Update_Settings_Container(
			$this->get_current_values()
		);

		$this->set_element_container($element_container);
	}
}