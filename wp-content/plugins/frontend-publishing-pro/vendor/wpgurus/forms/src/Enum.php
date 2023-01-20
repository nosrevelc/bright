<?php
namespace WPGurus\Forms;

if (!defined('WPINC')) die;

class Enum
{
	/**
	 * Once the constants of a class have been fetched, this array saves them for repeated use.
	 * @var array
	 */
	private static $const_cache_array = null;

	/**
	 * Uses the reflection API to fetch and return an array containing constants and their values.
	 * @return array An array containing constants and their values.
	 */
	public static function values() {
		if (self::$const_cache_array == null) {
			self::$const_cache_array = array();
		}
		$called_class = get_called_class();
		if (!array_key_exists($called_class, self::$const_cache_array)) {
			$reflect = new \ReflectionClass($called_class);
			self::$const_cache_array[$called_class] = $reflect->getConstants();
		}
		return self::$const_cache_array[$called_class];
	}

	/**
	 * Checks if a particular string is a valid constant name.
	 * @param $name string The constant name to check.
	 * @param bool $strict Whether case should have an impact on the result.
	 * @return bool Whether a constant with the passed name exists.
	 */
	public static function is_valid_name($name, $strict = false) {
		$constants = self::values();

		if ($strict) {
			return array_key_exists($name, $constants);
		}

		$keys = array_map('strtolower', array_keys($constants));
		return in_array(strtolower($name), $keys);
	}

	/**
	 * Checks if a value exists as a class constant.
	 * @param $value mixed The value to check.
	 * @return bool
	 */
	public static function is_valid_value($value) {
		$values = array_values(self::values());
		return in_array($value, $values, $strict = true);
	}
}