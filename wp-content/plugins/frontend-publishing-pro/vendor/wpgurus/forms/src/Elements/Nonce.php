<?php

namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

use WPGurus\Forms\Element;
use WPGurus\Forms\Validators\Nonce as Nonce_Validator;

/**
 * This element is a wrapper around the wp_nonce_field function provided by WordPress.
 * It not only renders the hidden nonce field but also takes care of the nonce validation using the Nonce validator. This validator is added by default so when using the element you don't have to add it separately.
 *
 * Class Nonce
 * @package WPGurus\Forms\Elements
 */
class Nonce extends Element implements Suppressed
{
	const ACTION = 'nonce_action';

	private $action;

	private $validator;

	function __construct($args)
	{
		parent::__construct($args);

		$args = wp_parse_args(
			$args,
			array(
				self::ACTION => '-1'
			)
		);

		$this->action = $args[ self::ACTION ];
		$this->validator = new Nonce_Validator(
			$args[ self::ACTION ],
			__('Security token expired. Please try reloading the page.', 'frontend-publishing-pro')
		);
		$this->add_validator($this->validator);
	}

	function get_action()
	{
		return $this->action;
	}

	function set_action($action)
	{
		$this->action = $action;
		$this->validator->set_action($action);
	}

	function print_errors()
	{
	}

	function render_field_html()
	{
		$this->set_value(wp_create_nonce($this->action));
		$this->set_attribute('value', $this->get_value());
		$this->set_attribute('type', 'hidden');

		?>
		<input <?php $this->print_attributes(); ?> />
		<?php
	}
}