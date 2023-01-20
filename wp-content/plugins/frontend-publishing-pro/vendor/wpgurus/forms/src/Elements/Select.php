<?php

namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

use WPGurus\Forms\Sanitizers\Typecast;

/**
 * Creates a simple select input.
 *
 * Class Select
 * @package WPGurus\Forms\Elements
 */
class Select extends \WPGurus\Forms\Element
{
	const CHOICES = 'select_choices';
	const MULTIPLE = 'select_multiple';

	/**
	 * The choices to display as options in the dropdown. The keys of this array will be used in value attributes whereas the values will be used as the visible text.
	 * @var array
	 */
	private $choices;

	/**
	 * Whether or not multiple selections are allowed. This field can't be set from outside the class because we need to make decisions based on its value in the constructor.
	 * @var boolean
	 */
	private $multiple;

	function __construct($args)
	{
		parent::__construct($args);

		$args = wp_parse_args(
			$args,
			array(
				self::CHOICES  => array(),
				self::MULTIPLE => false
			)
		);

		$this->choices = $args[ self::CHOICES ];
		$this->multiple = $args[ self::MULTIPLE ];

		if ($this->multiple) {
			$this->add_sanitizer(
				new Typecast(Typecast::TYPE_ARRAY)
			);
		}
	}

	/**
	 * Getter for the $multiple attribute.
	 * @return boolean
	 */
	public function is_multiple()
	{
		return $this->multiple;
	}

	/**
	 * Getter for the choices array.
	 * @return array
	 */
	public function get_choices()
	{
		return $this->choices;
	}

	/**
	 * Setter for the choices array.
	 * @param array $choices
	 */
	public function set_choices($choices)
	{
		$this->choices = $choices;
	}

	/**
	 * Renders the select HTML.
	 */
	function render_field_html()
	{
		$this->add_html_attributes();
		?>
		<select <?php $this->print_attributes(); ?>>
			<?php foreach ($this->choices as $value => $name): ?>
				<?php
				if (is_array($this->get_value()))
					$selected = (in_array($value, $this->get_value())) ? 'selected="selected"' : '';
				else
					$selected = selected($value, $this->get_value(), false);
				?>
				<option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $name; ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	private function add_html_attributes()
	{
		if ($this->multiple) {
			$this->set_attribute('multiple', 'multiple');
		}
	}
}