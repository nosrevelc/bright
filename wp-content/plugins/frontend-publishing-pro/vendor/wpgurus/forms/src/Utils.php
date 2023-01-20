<?php
namespace WPGurus\Forms;

use WPGurus\Utils\Array_Utils;

if (!defined('WPINC')) die;

/**
 * Contains common utility functions.
 *
 * Class Utils
 * @package WPGurus\Forms
 */
class Utils
{
	/**
	 * Simple helper function that checks whether a string is empty. Uses strict matching.
	 * @param  string $str The string to be checked.
	 * @return boolean
	 */
	private static function is_non_empty_string($str)
	{
		return $str !== '';
	}

	/**
	 * Takes an array of strings and removes all the empty ones from them.
	 * @param  array $array An array of strings.
	 * @return array The passed array with all the empty strings removed from it.
	 */
	private static function remove_empty_strings($array)
	{
		return array_filter($array, array('\WPGurus\Forms\Utils', 'is_non_empty_string'));
	}

	/**
	 * Extracts a value from an array. It removes empty strings from the keys before using them.
	 * @param  array $data The array from which we want to extract the value.
	 * @param  array|string $keys An array of indices. For instance in order to extract the value $array['foo']['bar'], the keys passed would have to be array('foo', 'bar').
	 * @return mixed The intended value.
	 */
	public static function get_from_array($data, $keys)
	{
		if (is_string($keys)) {
			$keys = array($keys);
		}

		// This removes the empty values that were added just for an additional [] at the end of the field name.
		$keys = self::remove_empty_strings($keys);

		return Array_Utils::get($data, $keys);
	}

	/**
	 * In a way quite similar to get_from_array, this function adds a value to a particular position in an array. It removes empty strings from the keys before using them.
	 * @param mixed $value The value to be inserted.
	 * @param array &$array The array in which the value need to be inserted. THIS IS PASSED BY REFERENCE.
	 * @param array|string $keys See the keys parameter of get_from_array.
	 */
	public static function add_to_array($value, &$array, $keys)
	{
		if (is_string($keys)) {
			$keys = array($keys);
		}

		// This removes the empty values that were added just for an additional [] at the end of the field name.
		$keys = self::remove_empty_strings($keys);

		Array_Utils::add($value, $array, $keys);
	}

	/**
	 * Takes a callback that prints something and converts that something into a string.
	 *
	 * @param $callback callable A callback that prints something.
	 * @param array $callback_args Arguments for the callable.
	 * @return string The visible output of the callback function converted to a string.
	 */
	public static function make_string($callback, $callback_args = array())
	{
		ob_start();

		if (is_callable($callback))
			call_user_func_array($callback, $callback_args);

		return ob_get_clean();
	}
}