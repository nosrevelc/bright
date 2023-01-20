<?php

namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

/**
 * A simple submit button input.
 *
 * Class Submit_Button
 * @package WPGurus\Forms\Elements
 */
class Submit_Button extends Input
{
	function render_field_html()
	{
		$this->set_attribute('type', 'submit');

		parent::render_field_html();
	}
}