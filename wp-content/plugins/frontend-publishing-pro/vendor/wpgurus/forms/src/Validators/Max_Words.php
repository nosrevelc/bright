<?php
namespace WPGurus\Forms\Validators;

if (!defined('WPINC')) die;

/**
 * Ensures that the user submitted value does not have more than the maximum allowed number of words.
 *
 * Class Max_Words
 * @package WPGurus\Forms\Validators
 */
class Max_Words extends Word_Count
{
	/**
	 * The maximum allowed number of words.
	 * @var int
	 */
	private $max_words;

	/**
	 * Max_Words constructor.
	 * @param int $max_words The maximum allowed number of words.
	 * @param string $message
	 */
	public function __construct($max_words, $message = '')
	{
		$this->max_words = $max_words;

		parent::__construct($message);
	}

	/**
	 * @inheritdoc
	 */
	public function is_valid($value)
	{
		if(!$this->is_applicable($value))
			return true;

		return !is_string($value) || $this->word_count($value) <= $this->max_words;
	}
}