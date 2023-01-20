<?php

namespace WPGurus\Forms\Validators;

if (!defined('WPINC')) die;

/**
 * Makes sure the user submitted value is a valid key.
 *
 * Class Key
 * @package WPGurus\Forms\Validators
 */
class Key extends String_Validator
{
	/**
	 * @inheritdoc
	 */
	function is_valid( $string ) {
		if( !$this->is_applicable($string) ){
			return true;
		}

		return sanitize_key( $string ) == strtolower( $string );
	}
}