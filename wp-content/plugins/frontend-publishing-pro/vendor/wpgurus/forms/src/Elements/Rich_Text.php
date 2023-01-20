<?php
namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

/**
 * An element that uses the wp_editor function to create a rich text editor.
 *
 * Class Rich_Text
 * @package WPGurus\Forms\Elements
 */
class Rich_Text extends \WPGurus\Forms\Element
{
	/**
	 * Indexes of the addition arguments that this element takes.
	 */
	const MEDIA_BUTTONS = 'rich_text_media_buttons';
	const EDITOR_HEIGHT = 'rich_text_editor_height';
	const EDITOR_CLASS = 'rich_text_editor_class';
	const TINYMCE_SETTINGS = 'rich_text_tinymce';

	/**
	 * Whether or not should the media elements be displayed.
	 *
	 * @var boolean
	 */
	private $media_buttons;

	/**
	 * Height of the editor.
	 *
	 * @var string
	 */
	private $editor_height;

	/**
	 * Editor's class attribute.
	 *
	 * @var string
	 */
	private $editor_class;

	/**
	 * Settings to be passed to the Tinymce.init function.
	 *
	 * @var array()
	 */
	private $tinymce_settings;

	function __construct($args = array())
	{
		parent::__construct($args);

		$args = wp_parse_args(
			$args,
			array(
				self::MEDIA_BUTTONS    => true,
				self::EDITOR_HEIGHT    => '300',
				self::EDITOR_CLASS     => '',
				self::TINYMCE_SETTINGS => true
			)
		);

		$this->media_buttons = $args[ self::MEDIA_BUTTONS ];
		$this->editor_height = $args[ self::EDITOR_HEIGHT ];
		$this->editor_class = $args[ self::EDITOR_CLASS ];
		$this->tinymce_settings = $args[ self::TINYMCE_SETTINGS ];
	}

	/**
	 * Renders the HTML of the element using wp_editor function.
	 */
	function render_field_html()
	{
		wp_enqueue_media();

		wp_editor(
			$this->get_value(),
			$this->get_id(),
			array(
				'media_buttons' => $this->media_buttons_active(),
				'textarea_name' => $this->get_field_name(),
				'editor_height' => $this->get_editor_height(),
				'editor_class'  => $this->prepare_editor_class(),
				'tinymce'       => $this->get_tinymce_settings()
			)
		);
	}

	/**
	 * Prepares the class attribute for the editor textarea
	 * @return string Editor class
	 */
	function prepare_editor_class()
	{
		return $this->get_editor_class() . ' ' . $this->get_attribute('class');
	}

	/**
	 * Getter for the tinymce settings.
	 *
	 * @return array
	 */
	public function get_tinymce_settings()
	{
		return $this->tinymce_settings;
	}

	/**
	 * Setter for the tinymce settings.
	 *
	 * @param $tinymce_settings
	 */
	public function set_tinymce_settings($tinymce_settings)
	{
		$this->tinymce_settings = $tinymce_settings;
	}

	/**
	 * Tells whether media buttons are enabled on the editor.
	 *
	 * @return bool
	 */
	public function media_buttons_active()
	{
		return $this->media_buttons;
	}

	/**
	 * Setter for media buttons.
	 *
	 * @param $media_buttons
	 */
	public function set_media_buttons($media_buttons)
	{
		$this->media_buttons = $media_buttons;
	}

	/**
	 * Getter for editor height.
	 *
	 * @return string
	 */
	public function get_editor_height()
	{
		return $this->editor_height;
	}

	/**
	 * Setter for editor height.
	 *
	 * @param $editor_height
	 */
	public function set_editor_height($editor_height)
	{
		$this->editor_height = $editor_height;
	}

	/**
	 * Getter for editor class.
	 *
	 * @return string
	 */
	public function get_editor_class()
	{
		return $this->editor_class;
	}

	/**
	 * Setter for the editor class.
	 *
	 * @param $editor_class
	 */
	public function set_editor_class($editor_class)
	{
		$this->editor_class = $editor_class;
	}
}