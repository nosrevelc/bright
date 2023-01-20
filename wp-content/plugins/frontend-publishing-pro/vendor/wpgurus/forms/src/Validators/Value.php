<?php
namespace WPGurus\Forms\Validators;

use WPGurus\Forms\Validator;

if (!defined('WPINC')) die;

class Value extends Validator
{
	/**
	 * The required value.
	 * @var string
	 */
	private $required_value;

	/**
	 * Value validator constructor.
	 * @param string $required_value
	 */
	public function __construct($required_value, $message = '')
	{
		parent::__construct($message);

		$this->required_value = $required_value;
	}

	public function is_valid($value)
	{
		return $value == $this->required_value;
	}

	/**
	 * Getter for required value.
	 * @return string
	 */
	public function get_required_value()
	{
		return $this->required_value;
	}

	/**
	 * Setter for required value.
	 * @param string $required_value
	 */
	public function set_required_value($required_value)
	{
		$this->required_value = $required_value;
	}
}