<?php

namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

use \WPGurus\Forms\Element;
use \WPGurus\Forms\Sanitizers\Typecast;

/**
 * Represents a checkbox form element. Only allows boolean values.
 */
class Checkbox extends Element
{
	function __construct($args)
	{
		parent::__construct($args);

		// Since the value returned by a checkbox element should always be boolean.
		$this->add_sanitizer(new Typecast(Typecast::TYPE_BOOL));
	}

	/**
	 * Prints the checkbox HTML. It also creates and renders a hidden field with the exact same name and a value of 0 so that some value is still generated when the user does not check the checkbox.
	 */
	function render_field_html()
	{
		$this->add_html_attributes();

		$hidden_element = new \WPGurus\Forms\Elements\Hidden(
			array(
				Element::KEY       => $this->get_key(),
				Element::VALUE     => '0',
				Element::ID_PREFIX => 'hidden-input-'
			)
		);
		$hidden_element->render();

		?>
		<input <?php $this->print_attributes(); ?> />
		<?php
	}

	private function add_html_attributes()
	{
		$this->set_attribute('type', 'checkbox');
		$this->set_attribute('value', '1');

		if ($this->get_value()) {
			$this->set_attribute('checked', 'checked');
		} else {
			$this->remove_attribute('checked');
		}
	}
}