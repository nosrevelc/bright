<?php
namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

use \WPGurus\Forms\Constants\Assets;

/**
 * This is basically a text field with a jQuery color picked attached to it. The plugin being used is Iris since it is simple and is shipped with the WordPress core.
 *
 * Class Color_Picker
 * @package WPGurus\Forms\Elements
 */
class Color_Picker extends Text
{
	const MAIN_SELECTOR = 'element-color-picker';

	function __construct($args)
	{
		parent::__construct($args);
	}

	/**
	 * Renders HTML for the field and enqueues the necessary scripts.
	 */
	function render_field_html()
	{
		$this->add_html_attributes();

		wp_enqueue_script(Assets::COLOR_PICKER_JS);
		parent::render_field_html();
	}

	public function add_html_attributes()
	{
		// This is the class that the color picker is attached to.
		$this->add_class(self::MAIN_SELECTOR);
	}
}