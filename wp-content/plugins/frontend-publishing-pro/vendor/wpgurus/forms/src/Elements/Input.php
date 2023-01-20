<?php

namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

/**
 * Creates an input field.
 *
 * Class Input
 * @package WPGurus\Forms\Elements
 */
class Input extends \WPGurus\Forms\Element
{
	function __construct( $args )
	{
		parent::__construct( $args );
	}

	function render_field_html() {
		$this->set_attribute('value', $this->get_value());

		?>
			<input <?php $this->print_attributes(); ?> />
		<?php
	}
}