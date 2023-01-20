<?php
namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

use \WPGurus\Forms\Constants\Assets;

/**
 * Allows the use of the standard WordPress media library for uploading items. The value stored is a media ID, URL or multiple IDs. Most of the work is done through a jQuery plugin. This class just makes sure that all the markup required by this plugin is rendered properly.
 *
 * Class Media
 * @package WPGurus\Forms\Elements
 */
abstract class Media extends Input
{
	const MAIN_SELECTOR = 'element-media';
	const MULTIPLE_SELECTOR = 'element-media-multiple';
	/**
	 * Element specific argument keys.
	 */
	const FRAME_TITLE = 'frame_title';
	const FRAME_BUTTON_TEXT = 'frame_button_text';
	const MULTIPLE = 'multiple';
	const ATTRIBUTE = 'attribute';

	/**
	 * The title of the media library popup.
	 * @var string
	 */
	private $frame_title;

	/**
	 * The text to be used in the media library button.
	 * @var string
	 */
	private $frame_button_text;

	/**
	 * Should the user be allowed to select multiple items?
	 * @var boolean
	 */
	private $multiple;

	/**
	 * Which attribute should be extracted and filled into the hidden input from the javascript media object?
	 * @var string
	 */
	private $attribute;

	function __construct($args)
	{
		parent::__construct($args);

		$args = wp_parse_args(
			$args,
			array(
				self::FRAME_TITLE       => '',
				self::FRAME_BUTTON_TEXT => '',
				self::MULTIPLE          => false,
				self::ATTRIBUTE         => 'id'
			)
		);

		$this->frame_title = $args[ self::FRAME_TITLE ];
		$this->frame_button_text = $args[ self::FRAME_BUTTON_TEXT ];
		$this->multiple = $args[ self::MULTIPLE ];
		$this->attribute = $args[ self::ATTRIBUTE ];
	}

	/**
	 * Renders all the markup required by the jQuery plugin and enqueues all the necessary assets.
	 */
	function render_field_html()
	{
		$this->add_html_attributes();

		wp_enqueue_media();
		wp_enqueue_script(Assets::WP_MEDIA_LIB_ELEMENT_JS);
		wp_enqueue_style(Assets::WP_MEDIA_LIB_ELEMENT_CSS);
		?>
		<div <?php $this->print_attributes_array($this->get_container_attributes()); ?>>
			<?php parent::render_field_html(); ?>
			<div class="element-media-preview">
				<?php $this->render_preview_html($this->get_value()); ?>
			</div>
			<div class="element-media-controls">
				<a href="#" class="element-media-select"><i class="fa fa-plus"></i></a>
				<a href="#" class="element-media-clear"><i class="fa fa-remove"></i></a>
				<div style="clear: both;"></div>
			</div>
		</div>
		<?php
	}

	/**
	 * Prints the HTML for previewing the selected media items. It is the responsibility of the child classes to create this HTML.
	 * @param $value mixed The value of the current element.
	 * @return void
	 */
	abstract protected function render_preview_html($value);

	/**
	 * Returns an array of html attributes for the container div.
	 * @return array
	 */
	private function get_container_attributes()
	{
		return array(
			'class'            => self::MAIN_SELECTOR . ($this->multiple ? ' ' . self::MULTIPLE_SELECTOR : ''),
			'data-title'       => $this->frame_title,
			'data-button-text' => $this->frame_button_text,
			'data-multiple'    => ($this->multiple) ? 'true' : 'false',
			'data-attribute'   => $this->attribute
		);
	}

	/**
	 * Adds HTML attributes to the HTML container.
	 */
	private function add_html_attributes()
	{
		$this->set_attribute('type', 'hidden');
	}
}