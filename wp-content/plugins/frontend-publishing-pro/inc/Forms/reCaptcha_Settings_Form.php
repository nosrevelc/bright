<?php

namespace WPFEPP\Forms;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Option_Ids;

class reCaptcha_Settings_Form extends Backend_Options_Form
{
	function __construct()
	{
		parent::__construct(
			'recaptcha-settings',
			Option_Ids::OPTION_RECAPTCHA_SETTINGS
		);

		$element_container = new \WPFEPP\Element_Containers\reCaptcha_Settings_Container(
			$this->get_current_values()
		);

		$this->set_element_container($element_container);
	}
}