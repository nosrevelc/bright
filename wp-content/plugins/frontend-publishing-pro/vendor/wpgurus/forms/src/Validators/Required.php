<?php

namespace WPGurus\Forms\Validators;

if (!defined('WPINC')) die;

/**
 * Ensures that the user has submitted some value against a required field.
 *
 * Class Required
 * @package WPGurus\Forms\Validators
 */
class Required extends \WPGurus\Forms\Validator
{
	/**
	 * Required constructor.
	 * @param string $message
	 */
	function __construct($message = '')
	{
		parent::__construct($message);
	}

	/**
	 * @inheritdoc
	 */
	function is_valid( $value ) {
		switch( true ){
			// Single selects with an empty first element
			case is_array($value) && count($value) == 1 && count(array_filter($value)) == 0:
				return false;

			// Empty strings
			case is_string($value) && trim($value) === '':
				return false;

			// Multi-selects with nothing selected
			case is_null($value):
				return false;
		}

		return true;
	}
}