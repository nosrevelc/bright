<?php
namespace WPGurus\Forms\Validators;

if (!defined('WPINC')) die;

abstract class String_Validator extends \WPGurus\Forms\Validator
{
	protected function is_applicable($value)
	{
		return is_string($value)
		&& trim($value) !== ''; // This condition is enforced by the required validator so validator not applicable.
	}
}