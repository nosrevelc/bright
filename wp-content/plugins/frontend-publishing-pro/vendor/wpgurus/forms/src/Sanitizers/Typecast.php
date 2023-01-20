<?php

namespace WPGurus\Forms\Sanitizers;

if (!defined('WPINC')) die;

/**
 * Casts a value into a particular data type to make it safer for use.
 *
 * Class Typecast
 * @package WPGurus\Forms\Sanitizers
 */
class Typecast extends \WPGurus\Forms\Sanitizer
{
	/**
	 * The different types that the sanitizer can cast into.
	 */
	const TYPE_STRING = 'string';
	const TYPE_INT = 'int';
	const TYPE_BOOL = 'bool';
	const TYPE_FLOAT = 'float';
	const TYPE_ARRAY = 'array';

	/**
	 * Whether or not should empty values be left alone.
	 * @var bool
	 */
	private $allow_empty;

	/**
	 * The type to cast into.
	 *
	 * @var string
	 */
	private $type;

	function __construct( $type = self::TYPE_STRING, $allow_empty = true ) {
		$this->allow_empty = $allow_empty;
		$this->type = $type;
	}

	/**
	 * Casts the passed to value to a particular data type.
	 *
	 * @param $value mixed The raw value.
	 * @return array|bool|float|int The casted value.
	 */
	function sanitize( $value ) {
		if( $this->allow_empty && $value === '' )
			return $value;

		if( is_array( $value ) && $this->type != self::TYPE_ARRAY ){
			return array_map(
				array($this, 'sanitize'),
				$value
			);
		}

		switch ( $this->type ) {
			case self::TYPE_INT:
				return intval( $value );

			case self::TYPE_BOOL:
				return (boolean) $value;

			case self::TYPE_FLOAT:
				return (float) $value;

			case self::TYPE_ARRAY:
				return (array) $value;

			default:
				return $value;
		}
	}
}