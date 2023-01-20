<?php
namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

/**
 * Creates an HTML5 number field.
 *
 * Class Email
 * @package WPGurus\Forms\Elements
 */
class Number extends Input
{
	function render_field_html()
	{
		$this->set_attribute('type', 'number');

		parent::render_field_html();
	}
}