<?php

namespace WPFEPP\Forms;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Form_Meta_Keys;

class Form_Settings_Form extends Backend_DB_Form
{
	function __construct($form_id, $current_settings)
	{
		parent::__construct($form_id, Form_Meta_Keys::SETTINGS, 'form-settings');

		$element_container = new \WPFEPP\Element_Containers\Form_Settings_Container(
			$current_settings
		);

		$this->set_element_container($element_container);
	}
}