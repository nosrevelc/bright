<?php
namespace WPGurus\Forms\Validators;

if (!defined('WPINC')) die;

/**
 * Makes sure the user submitted value is a valid email address.
 *
 * Class Email_Format
 * @package WPGurus\Forms\Validators
 */
class Email_Format extends Regex
{
	public function __construct($message = '')
	{
		$email_regex = '/^([a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4})*$/';
		parent::__construct($email_regex, $message);
	}
}