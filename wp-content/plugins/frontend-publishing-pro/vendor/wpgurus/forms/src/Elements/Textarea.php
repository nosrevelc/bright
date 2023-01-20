<?php

namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

/**
 * A simple textarea input.
 *
 * Class Textarea
 * @package WPGurus\Forms\Elements
 */
class Textarea extends \WPGurus\Forms\Element
{

	function __construct( $args )
	{
		parent::__construct( $args );

		$this->add_sanitizer( new \WPGurus\Forms\Sanitizers\Strip_Slashes() );
	}

	function render_field_html() {
		?>
			<textarea <?php $this->print_attributes(); ?>><?php echo esc_textarea( $this->get_value() ); ?></textarea>
		<?php
	}
}