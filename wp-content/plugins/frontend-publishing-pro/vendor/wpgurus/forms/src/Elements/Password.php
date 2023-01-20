<?php
namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

/**
 * Creates a basic password input field.
 *
 * Class Email
 * @package WPGurus\Forms\Elements
 */
class Password extends Input
{
	function render_field_html()
	{
		$this->set_attribute('type', 'password');

		parent::render_field_html();
	}
}