<?php
namespace WPGurus\Forms\Validators;

if (!defined('WPINC')) die;

/**
 * Ensures that the user submitted value matches a regex pattern.
 *
 * Class Regex
 * @package WPGurus\Forms\Validators
 */
class Regex extends String_Validator
{
	/**
	 * The regex pattern to match against.
	 * @var string
	 */
	private $regex;

	/**
	 * Regex constructor.
	 * @param string $regex The regex pattern to match against.
	 * @param string $message
	 */
	public function __construct($regex, $message = '')
	{
		$this->regex = $regex;
		parent::__construct($message);
	}

	/**
	 * @inheritdoc
	 */
	public function is_valid($value)
	{
		if(!$this->is_applicable($value)){
			return true;
		}

		// The value is valid if it is not a string OR it is empty OR matches the regex.
		$is_valid = preg_match($this->regex, $value);

		if($is_valid === false){
			// TODO: Log
		}

		return (boolean) $is_valid;
	}
}