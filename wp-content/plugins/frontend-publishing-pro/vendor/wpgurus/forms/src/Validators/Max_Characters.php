<?php
namespace WPGurus\Forms\Validators;

if (!defined('WPINC')) die;

/**
 * Makes sure the user submitted value does not have more than the maximum allowed character count.
 *
 * Class Max_Characters
 * @package WPGurus\Forms\Validators
 */
class Max_Characters extends String_Validator
{
	/**
	 * The maximum allowed characters.
	 * @var int
	 */
	private $max_characters;

	/**
	 * Max_Characters constructor.
	 *
	 * @param int $max_characters The maximum allowed characters.
	 * @param string $message
	 */
	public function __construct($max_characters, $message = '')
	{
		$this->max_characters = $max_characters;
		parent::__construct($message);
	}

	/**
	 * @inheritdoc
	 */
	public function is_valid($value)
	{
		if(!$this->is_applicable($value))
			return true;

		return strlen(utf8_decode($value)) <= $this->max_characters;
	}
}