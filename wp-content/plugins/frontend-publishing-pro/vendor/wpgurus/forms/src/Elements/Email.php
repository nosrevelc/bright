<?php
namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

/**
 * Creates an HTML5 email field.
 *
 * Class Email
 * @package WPGurus\Forms\Elements
 */
class Email extends Input
{
	function render_field_html()
	{
		$this->set_attribute('type', 'email');

		parent::render_field_html();
	}
}