<?php
namespace WPGurus\Utils;

class Array_Utils
{
	/**
	 * Gets a value from an array.
	 * @param $array array The array.
	 * @param $keys array|string|int The key(s) to use when retrieving the value.
	 * @return mixed|null Returns the required value or null.
	 */
	public static function get($array, $keys)
	{
		if (!self::is_valid_single_key($keys) && !self::is_valid_multi_key($keys)) {
			return null;
		}

		if (self::is_valid_single_key($keys)) {
			$keys = array($keys);
		}

		$value = $array;
		foreach ($keys as $key) {
			$value = isset($value[ $key ]) ? $value[ $key ] : NULL;
		}

		return $value;
	}

	/**
	 * Adds a value to an array.
	 * @param $value mixed The value to add.
	 * @param $array array The array.
	 * @param $keys array|string|int The key(s) to use when adding value.
	 */
	public static function add($value, &$array, $keys)
	{
		if (!self::is_valid_single_key($keys) && !self::is_valid_multi_key($keys)) {
			return;
		}

		if (self::is_valid_single_key($keys)) {
			$keys = array($keys);
		}

		$pointer = &$array;
		foreach ($keys as $key) {
			if (!isset($pointer[ $key ]))
				$pointer[ $key ] = array();
			$pointer = &$pointer[ $key ];
		}
		$pointer = $value;
	}

	/**
	 * Takes an array of strings and prints them in the form of an HTML list
	 * @param $strings
	 */
	public static function print_ul($strings)
	{
		if (!is_array($strings) || empty($strings))
			return;

		?>
		<ul>
			<?php foreach ($strings as $string): ?>
				<?php if ($string): ?>
					<li><?php echo $string; ?></li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
		<?php
	}

	/**
	 * Sorts an array of objects based on the value of a field.
	 * @param $array array The array to sort.
	 * @param $field_name string The name of the field that is to be used for sorting.
	 */
	public static function sort_objects(&$array, $field_name)
	{
		array_walk(
			$array,
			function (&$value, $key) use ($field_name) {
				$value = array(Array_Utils::get_field_value($value, $field_name), $key, $value);
			}
		);

		asort($array);

		array_walk(
			$array,
			function (&$value) {
				$value = $value[2];
			}
		);

		$array = array_values($array);
	}

	private static function get_field_value($object, $field_name)
	{
		$getter = array($object, 'get_' . $field_name);
		// Try the getter
		if (is_callable($getter)) {
			$order_value = call_user_func($getter);
		} // Try accessing directly
		elseif (isset($object->$field_name)) {
			$order_value = $object->$field_name;
		} // If all else fails, return null
		else {
			$order_value = null;
		}

		return $order_value;
	}

	private static function is_valid_single_key($key)
	{
		return is_string($key) || is_int($key);
	}

	private static function is_valid_multi_key($keys)
	{
		if (!is_array($keys)) {
			return false;
		}

		$keys_valid = true;
		foreach ($keys as $key) {
			$keys_valid = $keys_valid && Array_Utils::is_valid_single_key($key);
		}

		return $keys_valid;
	}
}