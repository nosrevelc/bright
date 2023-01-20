<?php

namespace WPFEPP\Forms;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Option_Ids;

class Frontend_Messages_Form extends Backend_Options_Form
{
	function __construct()
	{
		parent::__construct(
			'messages',
			Option_Ids::OPTION_MESSAGES
		);

		$element_container = new \WPFEPP\Element_Containers\Frontend_Messages_Container(
			$this->get_current_values()
		);

		$this->set_element_container($element_container);
	}
}