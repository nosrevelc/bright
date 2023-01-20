<?php

namespace WPFEPP\Forms;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Option_Ids;

class Data_Settings_Form extends Backend_Options_Form
{
	function __construct()
	{
		parent::__construct(
			'data-settings',
			Option_Ids::OPTION_DATA_SETTINGS,
			true
		);

		$element_container = new \WPFEPP\Element_Containers\Data_Settings_Container(
			$this->get_current_values()
		);

		$this->set_element_container($element_container);
	}
}