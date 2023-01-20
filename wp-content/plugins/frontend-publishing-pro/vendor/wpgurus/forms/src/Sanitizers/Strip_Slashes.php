<?php

namespace WPGurus\Forms\Sanitizers;

if (!defined('WPINC')) die;

/**
 * Strips slashes from a string using the native stripslashes PHP function.
 *
 * Class Strip_Slashes
 * @package WPGurus\Forms\Sanitizers
 */
class Strip_Slashes extends \WPGurus\Forms\Sanitizer
{
	function sanitize( $value ) {
		if( is_string( $value ) )
			return stripslashes( $value );

		return $value;
	}
}