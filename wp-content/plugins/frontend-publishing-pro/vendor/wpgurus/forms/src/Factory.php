<?php
namespace WPGurus\Forms;

if (!defined('WPINC')) die;

use WPGurus\Config\Config_Loader;

/**
 * Uses the PHP reflection API to facilitate the instantiation of objects.
 *
 * Class Factory
 * @package WPGurus\Forms
 */
class Factory
{
	/**
	 * We want the object constructors to receive a variable number of arguments.
	 * At the same time users should able to create objects using the factories. The following method allows us to achieve
	 * both targets. Users can pass a class name and an array of arguments that will be passed to the constructor using the Reflection API.
	 *
	 * @param $class string Class name.
	 * @param $args array|mixed Arguments to be passed to the constructor.
	 * @return object
	 */
	public static function create_instance($class, $args = array())
	{
		if (!is_array($args)) {
			$args = array($args);
		}

		$reflect = new \ReflectionClass($class);
		if (method_exists($class, '__construct')) {
			if(count($args))
			{
				return $reflect->newInstanceArgs($args);
			}
			else
			{
				return $reflect->newInstance();
			}
		} else {
			return $reflect->newInstanceWithoutConstructor();
		}
	}

	/**
	 * Takes a newly created object and applies filters to it.
	 *
	 * @param $filter string The filter to use. The plugin prefix will be appended to this before use.
	 * @param array $args All the args for the filter. The first element MUST be the object that is to be filtered.
	 * @param string $class If the new object obtained after filtering does not belong to this class then the original object will be returned instead.
	 * @return mixed|null The filtered object.
	 */
	public static function filter_object($filter, $args = array(), $class = '')
	{
		$object = null;
		do {
			if(!count($args))
				break;

			$object = $args[0];

			$plugin_prefix = Config_Loader::get_config('prefix');
			if(!$plugin_prefix)
				break;

			$new_object = apply_filters_ref_array($plugin_prefix . $filter, $args);
			if($class && !is_a($new_object, $class)){
				break;
			}

			$object = $new_object;
		} while (0);

		return $object;
	}
}