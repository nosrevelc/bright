<?php

namespace WPGurus\Forms;

use WPGurus\Utils\Array_Utils;

if (!defined('WPINC')) die;

/**
 * A form element.
 *
 * Class Element
 * @package WPGurus\Forms
 */
abstract class Element extends HTML_Container
{
	/**
	 * The following constants contain the indices expected in the array passed to the constructor.
	 */
	const VALUE = 'element_value';
	const LABEL = 'element_label';
	const PREFIX = 'element_prefix_text';
	const POSTFIX = 'element_postfix_text';
	const SANITIZERS = 'element_sanitizers';
	const VALIDATORS = 'element_validators';
	const KEY = 'element_key';
	const ATTRIBUTES = 'element_attributes';
	const TEMPLATE = 'element_template';
	const TEMPLATE_ARGS = 'element_template_args';
	const VALID = 'element_valid';
	const ID_PREFIX = 'element_id_prefix';
	const ORDER = 'element_order';

	/**
	 * The following constants are HTML attributes.
	 */
	const HTML_ATTR_NAME = 'name';
	const HTML_ATTR_ID = 'id';
	const HTML_ATTR_CLASS = 'class';

	/**
	 * Contains objects of the Validator class. Used for validating user input and displaying error messages.
	 * @var Validator[]
	 */
	private $validators = array();

	/**
	 * Contains objects of the Sanitizer class. Used for silently cleaning up user input.
	 * @var Sanitizer[]
	 */
	private $sanitizers = array();

	/**
	 * The key used to identify this element. This key is also used to create the field name and id.
	 * @var array
	 */
	private $key;

	/**
	 * The current value of the element.
	 * @var mixed
	 */
	private $value;

	/**
	 * The text that is supposed to be used as the HTML label.
	 * @var string
	 */
	private $label;

	/**
	 * The text to be displayed before the label.
	 * @var string
	 */
	private $prefix_text;

	/**
	 * The text to be displayed after the field HTML.
	 * @var string
	 */
	private $postfix_text;

	/**
	 * The error strings for this element.
	 * @var string[]
	 */
	private $errors;

	/**
	 * Template for rendering this element.
	 * @var string
	 */
	private $template;

	/**
	 * Extra template args to be extracted to the template at the time of rendering.
	 * @var array
	 */
	private $template_args;

	/**
	 * Whether or not the element is in a valid state.
	 * @var boolean
	 */
	private $valid;

	/**
	 * A string to be prepended to the HTML ID of the element.
	 * @var string
	 */
	private $id_prefix;

	/**
	 * Used to sort the elements in element container.
	 * @var int
	 */
	private $order;

	/**
	 * Initializes the object. Since many of the items used by the constructor are optional, only an array is expected as an argument.
	 *
	 * @param array $args
	 */
	public function __construct($args)
	{
		if (!isset($args[ self::KEY ])) {
			// TODO: Log.
		}

		$args = wp_parse_args(
			$args,
			array(
				self::ATTRIBUTES    => array(),
				self::KEY           => '',
				self::VALUE         => '',
				self::LABEL         => '',
				self::PREFIX        => '',
				self::POSTFIX       => '',
				self::SANITIZERS    => array(),
				self::VALIDATORS    => array(),
				self::TEMPLATE      => dirname(dirname(__FILE__)) . '/templates/element.php',
				self::TEMPLATE_ARGS => array(),
				self::VALID         => true,
				self::ID_PREFIX     => '',
				self::ORDER         => 0
			)
		);

		parent::__construct($args[ self::ATTRIBUTES ]);

		$this->key = is_array($args[ self::KEY ]) ? $args[ self::KEY ] : array($args[ self::KEY ]);
		$this->value = $args[ self::VALUE ];
		$this->label = $args[ self::LABEL ];
		$this->prefix_text = $args[ self::PREFIX ];
		$this->postfix_text = $args[ self::POSTFIX ];
		$this->sanitizers = $args[ self::SANITIZERS ];
		$this->validators = $args[ self::VALIDATORS ];
		$this->errors = array();
		$this->template = $args[ self::TEMPLATE ];
		$this->template_args = $args[ self::TEMPLATE_ARGS ];
		$this->valid = $args[ self::VALID ];
		$this->id_prefix = $args[ self::ID_PREFIX ];
		$this->order = $args[ self::ORDER ];
	}

	/**
	 * Returns the key of the element as a flattened string.
	 * @return string Flattened key.
	 */
	public function get_flattened_key()
	{
		return $this->generate_flattened_key($this->get_key());
	}

	/**
	 * Returns the HTML field name for the element. If key is array('foo', 'bar') it will return foo[bar]
	 * @return string
	 */
	public function get_field_name()
	{
		return $this->generate_field_name($this->get_key());
	}

	/**
	 * Returns the HTML ID for the element. If key is array('foo', 'bar'), it will return foo-bar.
	 * @return string
	 */
	public function get_id()
	{
		return $this->generate_id($this->get_key());
	}

	/**
	 * Returns the HTML class name for the element. If key is array('foo', 'bar'), it will return foo-bar.
	 * @return string
	 */
	public function get_class()
	{
		return $this->generate_class($this->get_key());
	}

	/**
	 * Getter function for the element key.
	 * @return array Element key.
	 */
	public function get_key()
	{
		return $this->key;
	}

	/**
	 * Setter function for the element key.
	 * @param $key string|array
	 */
	public function set_key($key)
	{
		$this->key = is_array($key) ? $key : array($key);
	}

	/**
	 * Takes a string key and prepends it to the current key array.
	 * For example if the current key is bar and this function is called with argument foo the element key will become array('foo', 'bar')
	 *
	 * @param $key string The value to prepend to the key array.
	 */
	public function prepend_key($key)
	{
		array_unshift($this->key, $key);
	}

	/**
	 * Getter function for the element value.
	 * @return mixed
	 */
	public function get_value()
	{
		return $this->value;
	}

	/**
	 * Setter function for the element value.
	 * @param $value mixed
	 */
	public function set_value($value)
	{
		$this->value = $value;
	}

	/**
	 * Adds errors to the array of element errors.
	 * @param $errors string[]|string
	 */
	public function add_errors($errors)
	{
		if (is_array($errors)) {
			$this->errors = array_merge($this->errors, $errors);
		} elseif (is_string($errors)) {
			$this->errors[] = $errors;
		}
	}

	/**
	 * Returns an array containing all the errors for this element.
	 * @return string[]
	 */
	public function get_errors()
	{
		return $this->errors;
	}

	/**
	 * Returns a single string containing the HTML for all the element errors.
	 * @return string
	 */
	public function get_printable_errors()
	{
		return Utils::make_string(array($this, 'print_errors'));
	}

	/**
	 * Getter for the template path.
	 * @return string The full path to the template file.
	 */
	public function get_template()
	{
		return $this->template;
	}

	/**
	 * Sets the template path for the element.
	 * @param $template string The full path to the template file.
	 */
	public function set_template($template)
	{
		$this->template = $template;
	}

	/**
	 * Getter for the template args.
	 * @return array
	 */
	public function get_template_args()
	{
		return $this->template_args;
	}

	/**
	 * Sets the arguments that will be sent to the template.
	 * @param $template_args array
	 */
	public function set_template_args($template_args)
	{
		$this->template_args = $template_args;
	}

	/**
	 * Getter that returns the value of a single template argument.
	 * @param $key string The key of the required template argument.
	 * @return null|mixed
	 */
	public function get_template_arg($key)
	{
		return isset($this->template_args[ $key ]) ? $this->template_args[ $key ] : null;
	}

	/**
	 * Sets the value of a single template argument.
	 * @param $key string The key against which the value must be set.
	 * @param $value mixed The value.
	 */
	public function set_template_arg($key, $value)
	{
		$this->template_args[ $key ] = $value;
	}

	/**
	 * Adds a validator object to the element.
	 * @param $validator \WPGurus\Forms\Validator
	 */
	public function add_validator($validator)
	{
		if (is_a($validator, '\WPGurus\Forms\Validator')) {
			$this->validators[] = $validator;
		}
	}

	/**
	 * Adds a sanitizer object to the element.
	 * @param $sanitizer '\WPGurus\Forms\Sanitizer'
	 */
	public function add_sanitizer($sanitizer)
	{
		if (is_a($sanitizer, '\WPGurus\Forms\Sanitizer')) {
			$this->sanitizers[] = $sanitizer;
		}
	}

	/**
	 * Validates a value by passing it through each validator object that has been added to the element.
	 * @param $value mixed The value to be validated.
	 * @return void
	 */
	public function validate($value)
	{
		foreach ($this->validators as $validator) {
			if (!$validator->is_valid($value)) {
				$this->errors[] = $validator->get_message();
				$this->set_valid(false);
			}
		}
	}

	/**
	 * Sanitizes a value by passing it through all the sanitizer objects that have been added to the element.
	 * @param $value mixed The value to be sanitized.
	 * @return mixed The sanitized value.
	 */
	public function sanitize($value)
	{
		foreach ($this->sanitizers as $sanitizer) {
			$value = $sanitizer->sanitize($value);
		}
		return $value;
	}

	/**
	 * Getter for the valid data member.
	 * @return boolean
	 */
	public function is_valid()
	{
		return $this->valid;
	}

	/**
	 * Setter for the valid data member.
	 * @param boolean $valid
	 */
	public function set_valid($valid)
	{
		$this->valid = $valid;
	}

	/**
	 * Getter for the ID prefix.
	 * @return string
	 */
	public function get_id_prefix()
	{
		return $this->id_prefix;
	}

	/**
	 * Setter for the ID prefix.
	 * @param string $id_prefix
	 */
	public function set_id_prefix($id_prefix)
	{
		$this->id_prefix = $id_prefix;
	}

	/**
	 * Returns element order.
	 * @return int
	 */
	public function get_order()
	{
		return $this->order;
	}

	/**
	 * Sets the element order
	 * @param int $order New element order.
	 */
	public function set_order($order)
	{
		$this->order = $order;
	}

	/**
	 * Prints the element errors as an unordered list.
	 */
	public function print_errors()
	{
		Array_Utils::print_ul($this->errors);
	}

	/**
	 * Prints the label HTML element.
	 */
	public function print_label()
	{
		?>
		<?php if ($this->label): ?>
		<label for="<?php echo $this->get_id(); ?>"><?php echo $this->label; ?></label>
	<?php endif; ?>
		<?php
	}

	/**
	 * Prints full HTML for the element including label, prefix text, errors, field and postfix text.
	 */
	public function render()
	{
		$this->add_html_attributes();

		$this->render_template(
			$this->get_template(),
			array_merge(
				array(
					'label'        => Utils::make_string(array($this, 'print_label')),
					'prefix_text'  => $this->prefix_text,
					'errors'       => Utils::make_string(array($this, 'print_errors')),
					'field_html'   => Utils::make_string(array($this, 'render_field_html')),
					'postfix_text' => $this->postfix_text
				),
				$this->get_template_args()
			)
		);
	}

	/**
	 * Prints the actual field HTML. This function is meant to be implemented by child classes. For instance in case of text this function would print the <input /> HTML with all the necessary attributes.
	 */
	abstract function render_field_html();

	/**
	 * Flattens the element key.
	 * @param $key array An array containing the element key.
	 * @return string The key in flattened form.
	 */
	private function generate_flattened_key($key)
	{
		return str_replace('_', '-', implode('-', $key));
	}

	/**
	 * Generates the field name for the element using the key.
	 * @param $keys array An array containing the element key.
	 * @return string The field name.
	 */
	private function generate_field_name($keys)
	{
		$field_name = array_shift($keys);

		if (count($keys)) {
			foreach ($keys as $key) {
				$field_name .= sprintf('[%s]', $key);
			}
		}

		return $field_name;
	}

	/**
	 * Generates the HTML ID of the element using its key.
	 * @param $key array An array containing the element key.
	 * @return string The ID of the element.
	 */
	private function generate_id($key)
	{
		return $this->id_prefix . $this->generate_flattened_key($key);
	}

	/**
	 * Generates an HTML class for the element.
	 * @param $key array An array containing the element key.
	 * @return string An HTML string class.
	 */
	private function generate_class($key)
	{
		return $this->generate_flattened_key($key);
	}

	/**
	 * Adds the necessary attributes for the HTML field.
	 */
	private function add_html_attributes()
	{
		$key = $this->get_key();

		$this->set_attribute(self::HTML_ATTR_NAME, $this->generate_field_name($key));
		$this->set_attribute(self::HTML_ATTR_ID, $this->generate_id($key));

		$class = $this->generate_class($key);
		$this->add_class($class);
	}

	/**
	 * Adds a class if it does not already exist.
	 * @param $class string CSS class.
	 */
	protected function add_class($class)
	{
		$existing_class_attr = trim($this->get_attribute(self::HTML_ATTR_CLASS));
		if ($existing_class_attr && strpos($existing_class_attr, $class) === false) {
			$this->append_attribute(self::HTML_ATTR_CLASS, ' ' . $class);
		} else {
			$this->set_attribute(self::HTML_ATTR_CLASS, $class);
		}
	}
}