<?php

namespace WPFEPP\Forms;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Form_Meta_Keys;

class Form_Fields_Form extends Backend_DB_Form
{
	function __construct($form_id, $post_type, $current_fields = array())
	{
		parent::__construct($form_id, Form_Meta_Keys::FIELDS, 'form-fields');

		if ($this->is_submission()) {
			$current_values = $this->get_data();
		} else {
			$current_values = $current_fields;
		}

		$element_container = new \WPFEPP\Element_Containers\Form_Fields_Container(
			$post_type,
			$current_values
		);

		$this->set_element_container($element_container);
	}
}