<?php
namespace WPGurus\Forms\Validators;

if (!defined('WPINC')) die;

/**
 * Ensures that the user-submitted value is a valid URL.
 *
 * Class URL_Format
 * @package WPGurus\Forms\Validators
 */
class URL_Format extends Regex
{
	public function __construct($message = '')
	{
		$url_regex = '/^(?:(?:https?|ftp):\/\/|www\.)[-a-zA-Z0-9+&@#\/%?=~_|!:,.;]*[-a-zA-Z0-9+&@#\/%=~_|]$/';
		parent::__construct($url_regex, $message);
	}
}