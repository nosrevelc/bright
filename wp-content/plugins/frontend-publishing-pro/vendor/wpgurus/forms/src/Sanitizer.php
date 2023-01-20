<?php

namespace WPGurus\Forms;

if (!defined('WPINC')) die;

/**
 * Cleans a user submitted value.
 *
 * Class Sanitizer
 * @package WPGurus\Forms
 */
abstract class Sanitizer
{
	/**
	 * Cleans a user submitted value.
	 * @param $value mixed The value to be cleaned.
	 * @return mixed The clean value.
	 */
	abstract function sanitize( $value );
}