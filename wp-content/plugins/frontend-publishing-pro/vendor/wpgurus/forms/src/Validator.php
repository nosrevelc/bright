<?php

namespace WPGurus\Forms;

if (!defined('WPINC')) die;

/**
 * Validates a user-submitted value.
 *
 * Class Validator
 * @package WPGurus\Forms
 */
abstract class Validator
{
	/**
	 * The error message to display when the submitted value is invalid.
	 * @var string
	 */
	protected $message;

	public function __construct($message = '')
	{
		$this->message = $message;
	}

	/**
	 * Checks if the passed value is valid.
	 * @param $value mixed The value to be validated.
	 * @return boolean Whether or not is the value valid.
	 */
	public abstract function is_valid($value);

	/**
	 * Setter for the error message.
	 * @param $message string
	 */
	public function set_message($message)
	{
		$this->message = $message;
	}

	/**
	 * Getter for the error message.
	 * @return string
	 */
	public function get_message()
	{
		return $this->message;
	}
}