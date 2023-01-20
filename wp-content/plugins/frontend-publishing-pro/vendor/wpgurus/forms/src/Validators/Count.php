<?php
namespace WPGurus\Forms\Validators;

if (!defined('WPINC')) die;

/**
 * Counts the number of items in an array or string. When processing a string, it breaks the value down into an array using a delimiter.
 *
 * Class Count
 * @package WPGurus\Forms\Validators
 */
abstract class Count extends \WPGurus\Forms\Validator
{
	private $delimiter;

	/**
	 * Count constructor.
	 * @param string $message The error message to display when validation fails.
	 * @param string $delimiter When a string is processed, this delimiter is used to break it down into an array.
	 */
	function __construct($message = '', $delimiter = ',')
	{
		$this->delimiter = $delimiter;

		parent::__construct($message);
	}

	/**
	 * Calculates the number of items in an array or string.
	 *
	 * @param $value string|array The string or array to be validated.
	 * @return int
	 */
	function calculate_count($value)
	{
		if (!is_array($value)) {
			$value = explode($this->delimiter, trim($value));
		}

		$value = array_filter($value, array($this, 'is_non_empty_string'));

		return count($value);
	}

	/**
	 * Checks whether the passed string is non-empty.
	 *
	 * @param $string string
	 * @return bool
	 */
	function is_non_empty_string($string)
	{
		return $string !== '';
	}
}