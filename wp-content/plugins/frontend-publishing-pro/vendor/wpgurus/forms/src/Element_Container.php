<?php

namespace WPGurus\Forms;

if (!defined('WPINC')) die;

use \WPGurus\Templating\Renderable;
use WPGurus\Utils\Array_Utils;

/**
 * The responsibility of this class is to hold elements and perform various functions on their values. The element container gets inserted into a form.
 *
 * Class Element_Container
 * @package WPGurus\Forms
 */
class Element_Container extends Renderable
{
	/**
	 * Contains all the form elements.
	 * @var Element[]
	 */
	private $elements = array();

	/**
	 * Getter for the elements array.
	 * @return \WPGurus\Forms\Element[]
	 */
	public function get_elements()
	{
		return $this->elements;
	}

	/**
	 * Fetches a single element from the array of elements.
	 * @param $key string The key of the element to retrieve.
	 * @return \WPGurus\Forms\Element
	 */
	public function get_element($key)
	{
		return isset($this->elements[ $key ]) ? $this->elements[ $key ] : null;
	}

	/**
	 * Adds a form element to the array of elements.
	 * @param Element $element The form element to be added.
	 * @param string $key The key against which this element should be stored.
	 */
	public function add_element($element, $key = null)
	{
		// If this is a data element then it should be added to the array $data_elements so some processing can be done on its value.
		if (is_a($element, '\WPGurus\Forms\Element')) {
			$this->elements = $this->add_item_to_array($this->elements, $element, $key);
		}
	}

	/**
	 * Adds an item to the array. Uses a key if one is passed otherwise just adds the item to the end of the array.
	 *
	 * @param $array array The array to which the item is to be added.
	 * @param $item mixed The item to add to the array.
	 * @param string|null $key The key to use for storage.
	 * @return array The modified array.
	 */
	private function add_item_to_array($array, $item, $key = null)
	{
		if ($key == null) {
			$array[] = $item;
		} else {
			$array[ $key ] = $item;
		}

		return $array;
	}

	/**
	 * Adds multiple elements.
	 * @param array $elements An array of elements.
	 */
	public function add_elements($elements)
	{
		foreach ($elements as $element) {
			$this->add_element($element);
		}
	}

	/**
	 * Returns the current state of the form by collecting all the data element values.
	 *
	 * @return array
	 */
	public function get_values()
	{
		$values = array();
		foreach ($this->elements as $element) {
			Utils::add_to_array($element->get_value(), $values, $element->get_key());
		}

		return $values;
	}

	/**
	 * Fills in all the gaps in the passed array with default element values.
	 *
	 * @param $current_values array An array of values.
	 * @return mixed
	 */
	public function parse_values($current_values)
	{
		foreach ($this->elements as $element) {
			$current_value = Utils::get_from_array($current_values, $element->get_key());

			if($current_value === null){
				Utils::add_to_array($element->get_value(), $current_values, $element->get_key());
			}
		}

		return $current_values;
	}

	/**
	 * Returns an ordered array of elements.
	 * @return Element[]
	 */
	public function get_ordered_elements()
	{
		$elements = $this->elements;
		Array_Utils::sort_objects($elements, 'order');
		return $elements;
	}

	/**
	 * Renders all the elements.
	 */
	public function render()
	{
		foreach ($this->get_ordered_elements() as $element) {
			$element->render();
		}
	}
}