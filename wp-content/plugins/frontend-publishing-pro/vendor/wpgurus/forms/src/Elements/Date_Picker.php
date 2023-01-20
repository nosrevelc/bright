<?php
namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

use \WPGurus\Forms\Constants\Assets;

/**
 * This is basically a text field with a jQuery date time picker attached to it. The options passed to this class are rendered on the page as data attributes and are then passed to the jQuery plugin. This way each instance can be configured.
 *
 * Class Date_Picker
 * @package WPGurus\Forms\Elements
 */
class Date_Picker extends Text
{
	const MAIN_SELECTOR = 'element-date-picker';
	/**
	 * The various options available for this element.
	 */
	const FORMAT = 'date_picker_format';
	const LANGUAGE = 'date_picker_lang';
	const DATE_ENABLED = 'date_picker_date_enabled';
	const TIME_ENABLED = 'date_picker_time_enabled';
	const THEME = 'date_picker_theme';

	/**
	 * PHP date time format.
	 * @var string
	 */
	private $format;

	/**
	 * Language code.
	 * @var string
	 */
	private $language;

	/**
	 * Whether or not the date part should be enabled.
	 * @var bool
	 */
	private $date_enabled;

	/**
	 * Whether or not the time part should be enabled.
	 * @var bool
	 */
	private $time_enabled;

	/**
	 * Picker theme.
	 * @var string
	 */
	private $theme;

	/**
	 * Getter for date time format.
	 * @return string
	 */
	public function get_format()
	{
		return $this->format;
	}

	/**
	 * Setter for date time format.
	 * @param $format string New format
	 */
	public function set_format($format)
	{
		$this->format = $format;
	}

	/**
	 * Getter for language code.
	 * @return string
	 */
	public function get_language()
	{
		return $this->language;
	}

	/**
	 * Setter for language code.
	 * @param $language string
	 */
	public function set_language($language)
	{
		$this->language = $language;
	}

	/**
	 * Getter that checks if date is enabled.
	 * @return bool
	 */
	public function is_date_enabled()
	{
		return $this->date_enabled;
	}

	/**
	 * Setter for enabling or disabling the date part.
	 * @param $date_enabled bool
	 */
	public function set_date_enabled($date_enabled)
	{
		$this->date_enabled = $date_enabled;
	}

	/**
	 * Getter that checks if time is enabled.
	 * @return bool
	 */
	public function is_time_enabled()
	{
		return $this->time_enabled;
	}

	/**
	 * Setter for enabling or disabling the time part.
	 * @param $time_enabled bool
	 */
	public function set_time_enabled($time_enabled)
	{
		$this->time_enabled = $time_enabled;
	}

	/**
	 * Getter for picker theme.
	 * @return string
	 */
	public function get_theme()
	{
		return $this->theme;
	}

	/**
	 * Setter for picker theme.
	 * @param $theme string
	 */
	public function set_theme($theme)
	{
		$this->theme = $theme;
	}

	function __construct($args)
	{
		parent::__construct($args);

		$args = wp_parse_args(
			$args,
			array(
				self::FORMAT       => 'Y-m-d H:i:s',
				self::LANGUAGE     => 'en',
				self::DATE_ENABLED => true,
				self::TIME_ENABLED => true,
				self::THEME        => 'default',
			)
		);

		$this->format = $args[ self::FORMAT ];
		$this->language = $args[ self::LANGUAGE ];
		$this->date_enabled = $args[ self::DATE_ENABLED ];
		$this->time_enabled = $args[ self::TIME_ENABLED ];
		$this->theme = $args[ self::THEME ];
	}

	/**
	 * Enqueues the necessary assets and renders the HTML.
	 */
	function render_field_html()
	{
		$this->add_html_attributes();

		wp_enqueue_style(Assets::DATE_TIME_PICKER_CSS);
		wp_enqueue_script(Assets::DATE_TIME_PICKER_JS);
		parent::render_field_html();
	}

	private function add_html_attributes()
	{
		$this->set_attribute('data-format', $this->format);
		$this->set_attribute('data-lang', $this->language);
		$this->set_attribute(
			'data-datepicker',
			($this->date_enabled) ? 'true' : 'false'
		);
		$this->set_attribute(
			'data-timepicker',
			($this->time_enabled) ? 'true' : 'false'
		);
		$this->set_attribute('data-theme', $this->theme);

		$this->add_class(self::MAIN_SELECTOR);
	}
}