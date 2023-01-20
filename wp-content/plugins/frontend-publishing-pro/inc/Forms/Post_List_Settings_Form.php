<?php
namespace WPFEPP\Forms;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Option_Ids;
use WPFEPP\Element_Containers\Post_List_Settings_Container;

class Post_List_Settings_Form extends Backend_Options_Form
{
	function __construct()
	{
		parent::__construct(
			'post-list-settings',
			Option_Ids::OPTION_POST_LIST_SETTINGS
		);

		$element_container = new Post_List_Settings_Container(
			$this->get_current_values()
		);

		$this->set_element_container($element_container);
	}
}