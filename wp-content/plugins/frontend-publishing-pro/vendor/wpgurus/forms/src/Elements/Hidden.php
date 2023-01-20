<?php

namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

/**
 * Creates a hidden input field.
 */
class Hidden extends Input implements Suppressed
{
	function __construct($args)
	{
		parent::__construct($args);

		$this->add_sanitizer(new \WPGurus\Forms\Sanitizers\Strip_Slashes());
	}

	function render_field_html()
	{
		$this->set_attribute('type', 'hidden');

		parent::render_field_html();
	}
}