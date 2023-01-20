<?php

namespace WPGurus\Forms;

if (!defined('WPINC')) die;

/**
 * Represents an HTML element that can have some attributes.
 *
 * Class HTML_Container
 * @package WPGurus\Forms
 */
abstract class HTML_Container extends \WPGurus\Templating\Renderable
{
	/**
	 * The attributes of the HTML element.
	 * @var array
	 */
	private $attributes;

	/**
	 * HTML_Container constructor.
	 * @param array $attributes HTML attributes with key value pairs.
	 */
	function __construct($attributes = array())
	{
		$this->attributes = $attributes;
	}

	/**
	 * Concatenates the attributes to create a string that can be used in HTML and prints it.
	 */
	function print_attributes()
	{
		$this->print_attributes_array($this->attributes);
	}

	function print_attributes_array($attributes_array)
	{
		$attr_str = array();
		foreach ($attributes_array as $name => $value) {
			$attr_str[] = sprintf('%s="%s"', $name, esc_attr(trim($value)));
		}
		echo implode(' ', $attr_str);
	}

	/**
	 * Tries to return the value of an attribute. Returns null if nothing can be found.
	 *
	 * @param $name
	 * @return null|string
	 */
	function get_attribute($name)
	{
		return isset($this->attributes[ $name ]) ? $this->attributes[ $name ] : NULL;
	}

	/**
	 * Sets the value of an attribute.
	 *
	 * @param $name string Attribute name e.g. id
	 * @param $value string Attribute value e.g. some-id
	 */
	function set_attribute($name, $value)
	{
		$this->attributes[ $name ] = $value;
	}

	/**
	 * Instead of replacing the old value of an attribute with a new one, it appends the new value to the old one.
	 *
	 * @param $name string The attribute name.
	 * @param $value string The value to append.
	 */
	function append_attribute($name, $value)
	{
		$this->attributes[ $name ] = isset($this->attributes[ $name ]) ? $this->attributes[ $name ] : '';
		$this->attributes[ $name ] .= $value;
	}

	/**
	 * Sets the value of an attribute only if it isn't already set.
	 *
	 * @param $name
	 * @param $value
	 */
	function set_attribute_if_not_exists($name, $value)
	{
		if (!isset($this->attributes[ $name ])) {
			$this->attributes[ $name ] = $value;
		}
	}

	/**
	 * Removes an attribute.
	 *
	 * @param $name
	 */
	function remove_attribute($name)
	{
		unset($this->attributes[ $name ]);
	}

	/**
	 * Getter for attributes.
	 * @return array
	 */
	function get_attributes()
	{
		return $this->attributes;
	}

	/**
	 * Setter for attributes.
	 * @param $attributes
	 */
	function set_attributes($attributes)
	{
		$this->attributes = $attributes;
	}
}