<?php
namespace WPFEPP\Elements;

use WPFEPP\Constants\Plugin_Actions;
use WPGurus\Forms\Element;

class Action_Element extends Element
{
	const CURRENT_VALUES = 'action_element_current_values';
	const FORM_ID = 'action_element_form_id';

	private $form_id;

	private $current_values;

	function __construct($args = array())
	{
		parent::__construct($args);

		$args = wp_parse_args(
			$args,
			array(
				self::FORM_ID        => 0,
				self::CURRENT_VALUES => array()
			)
		);

		$this->form_id = $args[ self::FORM_ID ];
		$this->current_values = $args[ self::CURRENT_VALUES ];
	}

	function render()
	{
		$this->render_field_html();
	}

	function render_field_html()
	{
		do_action(
			sprintf(Plugin_Actions::AFTER_FORM_FIELDS, $this->form_id),
			$this->current_values
		);

		do_action(
			Plugin_Actions::AFTER_FORM_FIELDS_GENERAL,
			$this->current_values
		);
	}
}