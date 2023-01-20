<?php
namespace WPFEPP\Forms;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Form_Meta_Keys;
use WPFEPP\Element_Containers\Post_List_Settings_Container;

class Form_Post_List_Form extends Backend_DB_Form
{
	function __construct($form_id, $current_settings)
	{
		parent::__construct($form_id, Form_Meta_Keys::POST_LIST, 'form-post-list-settings');

		$element_container = new Post_List_Settings_Container(
			$current_settings
		);

		$this->set_element_container($element_container);
	}
}