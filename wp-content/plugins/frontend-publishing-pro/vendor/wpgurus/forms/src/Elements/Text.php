<?php

namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

/**
 * A simple text input.
 *
 * Class Text
 * @package WPGurus\Forms\Elements
 */
class Text extends \WPGurus\Forms\Elements\Input
{
	function __construct($args)
	{
		parent::__construct($args);

		$this->add_sanitizer(new \WPGurus\Forms\Sanitizers\Strip_Slashes());
	}

	function render_field_html()
	{
		$this->set_attribute('type', 'text');

		parent::render_field_html();
	}
}