<?php
namespace WPFEPP\Utils;

if (!defined('WPINC')) die;

class String_Utils
{
	public static function parse_choices_string($string)
	{
		if ($string == '')
			return array();

		$choices = array();
		$lines = explode(PHP_EOL, $string);
		foreach ($lines as $key => $line) {
			$key_val = explode('|', $line);

			if (count($key_val) > 1) {
				$choices[ $key_val[0] ] = $key_val[1];
			} else {
				$choices[ $key_val[0] ] = $key_val[0];
			}
		}

		return $choices;
	}
}