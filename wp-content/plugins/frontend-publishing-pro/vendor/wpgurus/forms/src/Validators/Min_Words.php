<?php
namespace WPGurus\Forms\Validators;

if (!defined('WPINC')) die;

/**
 * Ensures that the user submitted value does not have less than the minimum allowed number of words.
 *
 * Class Min_Words
 * @package WPGurus\Forms\Validators
 */
class Min_Words extends Word_Count
{
	/**
	 * The minimum allowed word count.
	 * @var int
	 */
	private $min_words;

	/**
	 * Min_Words constructor.
	 * @param string $min_words The minimum allowed word count.
	 * @param string $message
	 */
	public function __construct($min_words, $message = '')
	{
		$this->min_words = $min_words;

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

		return $this->word_count($value) >= $this->min_words;
	}
}