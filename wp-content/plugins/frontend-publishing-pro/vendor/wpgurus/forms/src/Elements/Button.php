<?php
namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

use WPGurus\Forms\Element;

class Button extends Element
{
	const BUTTON_TEXT = 'button_text';

	private $button_text;

	function __construct( $args )
	{
		parent::__construct( $args );

		$args = wp_parse_args(
			$args,
			array(
				self::BUTTON_TEXT => ''
			)
		);

		$this->button_text = $args[ self::BUTTON_TEXT ];
	}

	function render_field_html()
	{
		$this->set_attribute('value', $this->get_value());
		?>
			<button <?php $this->print_attributes(); ?>><?php echo $this->button_text; ?></button>
		<?php
	}

	/**
	 * Gets the button text.
	 * @return string Button text
	 */
	public function get_button_text()
	{
		return $this->button_text;
	}

	/**
	 * Sets the button text.
	 * @param string $button_text Button text.
	 */
	public function set_button_text($button_text)
	{
		$this->button_text = $button_text;
	}
}