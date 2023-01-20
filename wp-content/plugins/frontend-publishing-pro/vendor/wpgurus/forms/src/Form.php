<?php

namespace WPGurus\Forms;

if (!defined('WPINC')) die;

use WPGurus\Forms\Elements\Hidden;
use WPGurus\Forms\Elements\Nonce;
use WPGurus\Forms\Elements\Suppressed;
use WPGurus\Utils\Array_Utils;

class Form extends \WPGurus\Forms\HTML_Container
{
	const INDEX_FORM_ERRORS = 'form';
	const ELEMENT_KEY_FORM_ID = 'form_id';
	const INDEX_NONCE_ELEMENT = 'nonce_hidden_form_element';
	const INDEX_FORM_ID_ELEMENT = 'form_id_hidden_form_element';

	/**
	 * Contains the elements for this form.
	 * @var Element_Container array
	 */
	private $element_container;

	/**
	 * Contains error strings for the whole form as well as all the elements.
	 * @var array
	 */
	private $errors = array(self::INDEX_FORM_ERRORS => array());

	/**
	 * Contains all the form errors and element errors in printable form. HTML ids are used as keys for this array so that the errors can be inserted directly into the DOM.
	 * @var array
	 */
	private $printable_errors = array();

	/**
	 * The message shown to the user on successful submission handling.
	 * @var string
	 */
	private $success_message = '';

	/**
	 * The form ID. It is used as the HTML id attribute of the form. With the help of a hidden form field, it is also used to check if the form was being submitted.
	 * @var string
	 */
	private $form_id;

	private $method;

	/**
	 * Turns validation on or off.
	 * @var boolean
	 */
	private $validation = true;

	/**
	 * Turns sanitization on or off.
	 * @var boolean
	 */
	private $sanitization = true;

	/**
	 * @var Processor[]
	 */
	private $pre_processors = array();

	/**
	 * @var Processor[]
	 */
	private $post_processors = array();

	/**
	 * @param string $form_id
	 * @param Element_Container|null $element_container
	 * @param bool|true $validation
	 * @param bool|true $sanitization
	 * @param array $attributes
	 */
	function __construct($form_id, $element_container = null, $validation = true, $sanitization = true, $attributes = array())
	{
		parent::__construct($attributes);

		$this->form_id = $form_id;
		$this->set_attribute_if_not_exists('method', 'POST');
		$this->set_attribute('id', $this->form_id);
		$this->method = $this->get_attribute('method');

		$this->element_container = $element_container;
		$this->validation = $validation;
		$this->sanitization = $sanitization;
	}

	/**
	 * @param Processor $processor
	 */
	public function add_pre_processor($processor)
	{
		if (is_a($processor, '\WPGurus\Forms\Processor')) {
			$this->pre_processors[] = $processor;
		}
	}

	/**
	 * @return Processor[]
	 */
	public function get_pre_processors()
	{
		return $this->pre_processors;
	}

	/**
	 * @param Processor $processor
	 */
	public function add_post_processor($processor)
	{
		if (is_a($processor, '\WPGurus\Forms\Processor')) {
			$this->post_processors[] = $processor;
		}
	}

	/**
	 * @return Processor[]
	 */
	public function get_post_processors()
	{
		return $this->post_processors;
	}

	/**
	 * Sets the element container object.
	 * @param $element_container
	 */
	public function set_element_container($element_container)
	{
		$this->element_container = $element_container;
	}

	/**
	 * Getter for the element container object.
	 * @return Element_Container
	 */
	public function get_element_container()
	{
		return $this->element_container;
	}

	/**
	 * Returns hidden elements required by the form to function properly.
	 * @return Element[]
	 */
	private function get_hidden_elements()
	{
		/**
		 * For security, the nonce element is added by default.
		 */
		$elements[ self::INDEX_NONCE_ELEMENT ] = new Nonce(
			array(
				Element::KEY  => $this->form_id . '_nonce',
				Nonce::ACTION => $this->form_id . '_action'
			)
		);

		/**
		 * The form id is placed in a hidden field so that we can reliably check if the form was submitted. This is done in the is_submission() function.
		 */
		$elements[ self::INDEX_FORM_ID_ELEMENT ] = new Hidden(
			array(
				Element::KEY       => self::ELEMENT_KEY_FORM_ID,
				Element::VALUE     => $this->form_id,
				Element::ID_PREFIX => $this->form_id . '-'
			)
		);

		return $elements;
	}

	/**
	 * Returns all the form elements by combining the elements from the container and the hidden elements.
	 * @return Element[]
	 */
	public function get_elements()
	{
		return array_merge($this->element_container->get_elements(), $this->get_hidden_elements());
	}

	private function render_elements()
	{
		$this->element_container->render();
		foreach ($this->get_hidden_elements() as $element) {
			$element->render();
		}
	}

	/**
	 * This is where the actual data processing is done. It needs to be implemented by child classes.
	 * This function gets called after sanitization and only if the data is valid.
	 *
	 * @param  array $cleaned_data An array containing all the sanitized and validated values.
	 * @return boolean Indicates whther data processing was successful.
	 */
	function process_data($cleaned_data)
	{
	}

	/**
	 * Returns submitted data after passing it through filter_values(). All the unnecessary values like the nonce, form_id and submit buttons are removed.
	 *
	 * @return array An array containing user submitted data.
	 */
	public function get_data()
	{
		$data = $this->get_raw_data();

		$data = $this->remove_hidden_element_values($data);

		return $data;
	}

	/**
	 * Returns the raw data obtained from $_POST or $_GET magic variables. The returned values include everything including extra values like nonces and form_id.
	 * @return array The raw data.
	 */
	private function get_raw_data()
	{
		return ($this->method == 'POST') ? $_POST : $_GET;
	}

	/**
	 * Checks the user submitted values to see if the form_id field is set and is equal to this form's ID.
	 * @return boolean Indicates whether or not the form was submitted.
	 */
	public function is_submission()
	{
		$data = $this->get_raw_data();
		return isset($data[ self::ELEMENT_KEY_FORM_ID ]) && $data[ self::ELEMENT_KEY_FORM_ID ] == $this->form_id;
	}

	/**
	 * Sanitizes and validates data, sets the value of each element to what the user just entered and calls process_data() for final processing.
	 *
	 * @see process_data()
	 * @return boolean If all the user entered values are valid and process_data returned true then returns true, otherwise false.
	 */
	public function handle_submission()
	{
		$data = $this->get_raw_data();
		$cleaned_data = array();
		$is_valid = true;

		if (!$this->check_nonce($data)) {
			return false;
		}

		foreach ($this->get_pre_processors() as $pre_processor) {
			// Arguments are passed by reference to do_process
			$pre_processor->do_process($is_valid, $data, $cleaned_data, $this);
		}

		foreach ($this->element_container->get_elements() as $element_id => $element) {
			$keys = $element->get_key();

			$value = Utils::get_from_array($data, $keys);

			$is_element_valid = true;

			if ($this->validation) {
				$element->validate($value);
				$is_element_valid = $element->is_valid();
			}

			if ($this->sanitization) {
				$value = $element->sanitize($value);
			}

			$element->set_value($value);

			// If the element is valid it's value must be added to the cleaned data array.
			if ($is_element_valid) {
				Utils::add_to_array($value, $cleaned_data, $keys);
			} else {
				// Otherwise we need to notify the user that a problem exists in the element value.

				// If the element is suppressed i.e. is not visible on the page then its errors should go in the form errors.
				if (is_a($element, '\WPGurus\Forms\Elements\Suppressed')) {
					$this->add_form_errors(
						$element->get_errors()
					);
				} else {
					// Otherwise we can put them separately.
					$this->add_element_errors($element);
				}
			}

			$is_valid = $is_element_valid && $is_valid;
		}

		foreach ($this->get_post_processors() as $post_processor) {
			// Arguments are passed by reference to do_process
			$post_processor->do_process($is_valid, $data, $cleaned_data, $this);
		}

		return $is_valid && (boolean)$this->process_data($cleaned_data);
	}

	/**
	 * Returns an array containing all the error strings.
	 * @return array
	 */
	public function get_errors()
	{
		return $this->errors;
	}

	/**
	 * Returns printable errors.
	 * @return array
	 */
	public function get_printable_errors()
	{
		return $this->printable_errors;
	}

	/**
	 * Adds form errors. Doesn't modify element errors.
	 * @param array|string $errors One or more than one errors.
	 */
	public function add_form_errors($errors)
	{
		// First, ADD the passed errors to the $errors array.
		if (is_array($errors)) {
			$this->errors[ self::INDEX_FORM_ERRORS ] = array_merge($this->errors[ self::INDEX_FORM_ERRORS ], $errors);
		} else {
			$this->errors[ self::INDEX_FORM_ERRORS ][] = $errors;
		}

		// Then pass the new value of $this->errors['form'] through a function to make them into an HTML list. This html list will then be stored in the $printable_errors array.
		$this->printable_errors[ $this->form_id ] = Utils::make_string('\WPGurus\Utils\Array_Utils::print_ul', array($this->errors[ self::INDEX_FORM_ERRORS ]));
	}

	/**
	 * Setter for success message
	 * @param string $message Success message.
	 */
	public function set_success_message($message)
	{
		$this->success_message = $message;
	}

	/**
	 * Getter for form success message.
	 * @return string Success message.
	 */
	public function get_success_message()
	{
		return $this->success_message;
	}

	/**
	 * Setter for the validation boolean.
	 * @param boolean $validation
	 */
	public function set_validation($validation)
	{
		$this->validation = $validation;
	}

	/**
	 * Setter for sanitization boolean.
	 * @param boolean $sanitization
	 */
	public function set_sanitization($sanitization)
	{
		$this->sanitization = $sanitization;
	}

	/**
	 * Prints the success message.
	 */
	public function print_success_message()
	{
		?>
		<?php if ($this->success_message): ?>
		<p>
			<?php echo $this->success_message; ?>
		</p>
	<?php endif; ?>
		<?php
	}

	/**
	 * Prints the form errors.
	 */
	public function print_errors()
	{
		if ($this->errors[ self::INDEX_FORM_ERRORS ])
			Array_Utils::print_ul($this->errors[ self::INDEX_FORM_ERRORS ]);
	}

	/**
	 * Handles submissions by calling the handle_submission method and prints the form.
	 */
	public function render()
	{
		if ($this->is_submission()) {
			$this->handle_submission();
		}

		$this->print_success_message();

		$this->print_errors();

		$this->render_form();
	}

	/**
	 * Prints the actual form element.
	 */
	public function render_form()
	{
		?>
		<form <?php $this->print_attributes(); ?>>
			<?php $this->render_elements(); ?>
		</form>
		<?php
	}

	/**
	 * @param $data
	 */
	private function remove_hidden_element_values($data)
	{
		foreach ($this->get_hidden_elements() as $element_key => $element) {
			$keys = $element->get_key();
			$key = $keys[0];
			unset($data[ $key ]);
		}

		return $data;
	}

	/**
	 * @param Element $element
	 */
	private function add_element_errors($element)
	{
		Utils::add_to_array($element->get_errors(), $this->errors, $element->get_key());

		$this->printable_errors[ $element->get_id() ] = $element->get_printable_errors();
	}

	/**
	 * Validates the nonce, adds an error if it is invalid and returns a boolean indicating nonce validity.
	 * @param $data array The user submitted data.
	 * @return bool Boolean indicating whether or not the nonce is valid.
	 */
	private function check_nonce($data)
	{
		$hidden_elements = $this->get_hidden_elements();
		$nonce_element = $hidden_elements[ self::INDEX_NONCE_ELEMENT ];
		$value = Utils::get_from_array($data, $nonce_element->get_key());
		$nonce_element->validate($value);
		$is_valid = $nonce_element->is_valid();
		if (!$is_valid) {
			$this->add_form_errors(
				$nonce_element->get_errors()
			);
		}
		return $is_valid;
	}
}