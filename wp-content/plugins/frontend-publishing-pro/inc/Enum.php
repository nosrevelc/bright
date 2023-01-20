<?php
namespace WPFEPP;

if (!defined('WPINC')) die;

class Enum
{
	private static $constCacheArray = NULL;

	public static function is_valid_name($name, $strict = false)
	{
		$constants = self::values();

		if ($strict) {
			return array_key_exists($name, $constants);
		}

		$keys = array_map('strtolower', array_keys($constants));
		return in_array(strtolower($name), $keys);
	}

	public static function values()
	{
		if (self::$constCacheArray == NULL) {
			self::$constCacheArray = [];
		}
		$calledClass = get_called_class();
		if (!array_key_exists($calledClass, self::$constCacheArray)) {
			$reflect = new \ReflectionClass($calledClass);
			self::$constCacheArray[ $calledClass ] = $reflect->getConstants();
		}
		return self::$constCacheArray[ $calledClass ];
	}

	public static function is_valid_value($value)
	{
		$values = array_values(self::values());
		return in_array($value, $values, $strict = true);
	}
}