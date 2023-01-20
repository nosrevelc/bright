<?php
namespace WPFEPP\Element_Containers;

use WPGurus\Forms\Utils;

if (!defined('WPINC')) die;

/**
 * Offers some utility functions to the element containers that are supposed to be used in the WordPress backend.
 *
 * Class Backend_Element_Container
 * @package WPFEPP\Element_Containers
 */
abstract class Backend_Element_Container extends \WPGurus\Forms\Element_Container
{
	const TEMP_ARG_ELEMENTS = 'elements';

	/**
	 * An array containing all the current values for the container. These values are used when creating elements.
	 * @var array
	 */
	protected $current_values;

	private $element_template;

	private $container_template;

	/**
	 * Initializes the object.
	 *
	 * @param array $current_values The current values for the elements.
	 */
	public function __construct($current_values = array())
	{
		$this->current_values = $current_values;
		// TODO: Here we can check if current values are not $current_values and if they are not we can use $this->parse_values to fill in all the empty gaps inside the current values.

		$this->element_template = WPFEPP_ELEMENT_TEMPLATES_DIR . 'table-element.php';
		$this->container_template = WPFEPP_ELEMENT_CONTAINER_TEMPLATES_DIR . 'form-table.php';
	}

	/**
	 * Does some processing on the passed Element object before calling the parent method.
	 * @param string $key Element key.
	 * @param \WPGurus\Forms\Element $element
	 * @param bool $table_element_template Whether or not to apply the table element template.
	 */
	public function add_element($element, $key = null, $table_element_template = true)
	{
		$element_value = Utils::get_from_array($this->current_values, $element->get_key());

		if ($element_value !== NULL) {
			$element->set_value($element_value);
		}

		if ($table_element_template) {
			$element->set_template(
				$this->element_template
			);
		}

		parent::add_element($element, $key);
	}

	public function render()
	{
		$template_args = array(
			self::TEMP_ARG_ELEMENTS => $this->get_elements()
		);

		$this->render_template($this->container_template, $template_args);
	}
}