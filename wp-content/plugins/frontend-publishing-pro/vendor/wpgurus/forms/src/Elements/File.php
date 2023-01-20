<?php
namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

/**
 * Creates a file field.
 *
 * Class File
 * @package WPGurus\Forms\Elements
 */
class File extends Input
{
	function render_field_html()
	{
		$this->set_attribute('type', 'file');

		parent::render_field_html();
	}
}