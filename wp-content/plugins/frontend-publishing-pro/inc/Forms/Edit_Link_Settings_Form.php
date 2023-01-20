<?php
namespace WPFEPP\Forms;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Option_Ids;
use WPFEPP\Element_Containers\Edit_Link_Settings_Container;

class Edit_Link_Settings_Form extends Backend_Options_Form
{
	function __construct()
	{
		parent::__construct(
			'edit-link-settings',
			Option_Ids::OPTION_EDIT_LINK_SETTINGS
		);

		$element_container = new Edit_Link_Settings_Container(
			$this->get_current_values()
		);

		$this->set_element_container($element_container);
	}
}