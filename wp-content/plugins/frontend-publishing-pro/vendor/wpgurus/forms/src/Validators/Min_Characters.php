<?php
namespace WPGurus\Forms\Validators;

if (!defined('WPINC')) die;

/**
 * Ensures that the user submitted value does not have less than the minimum allowed character count.
 *
 * Class Min_Characters
 * @package WPGurus\Forms\Validators
 */
class Min_Characters extends String_Validator
{
	/**
	 * The minimum allowed character count.
	 * @var int
	 */
	private $min_characters;

	/**
	 * Min_Characters constructor.
	 * @param int $min_characters The minimum allowed character count.
	 * @param string $message
	 */
	public function __construct($min_characters, $message = '')
	{
		$this->min_characters = $min_characters;
		parent::__construct($message);
	}

	/**
	 * @inheritdoc
	 */
	public function is_valid($value)
	{
		if(!$this->is_applicable($value))
			return true;

		return strlen(utf8_decode($value)) >= $this->min_characters;
	}
}