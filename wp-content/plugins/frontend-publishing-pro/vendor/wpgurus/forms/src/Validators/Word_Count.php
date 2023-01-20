<?php
namespace WPGurus\Forms\Validators;

if (!defined('WPINC')) die;

/**
 * Calculates word count.
 *
 * Class Word_Count
 * @package WPGurus\Forms\Validators
 */
abstract class Word_Count extends String_Validator
{
	/**
	 * Calculates word count.
	 * @param $str string The string.
	 * @return int The word count.
	 */
	protected function word_count($str)
	{
		$str = preg_replace('/\s+/', ' ', strip_tags(trim($str)));
		return (substr_count($str, ' ') + 1);
	}
}