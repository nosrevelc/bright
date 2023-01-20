<?php
namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

use WPGurus\Forms\Components\Asset_Manager;
use WPGurus\Forms\Constants\Assets;

/**
 * An enhanced select that allows users to search through the options thanks to a jQuery plugin.
 *
 * Class Select_With_Search
 * @package WPGurus\Forms\Elements
 */
class Select_With_Search extends Select
{
	const MAIN_SELECTOR = 'element-select-with-search';

	/**
	 * Additional arguments accepted by this element.
	 */
	const PLACEHOLDER_TEXT_SINGLE = 'sws_placeholder_text_single';
	const PLACEHOLDER_TEXT_MULTIPLE = 'sws_placeholder_text_multiple';
	const ALLOW_SINGLE_DESELECT = 'sws_allow_single_deselect';
	const DISABLE_SEARCH = 'sws_disable_search';
	const DISABLE_SEARCH_THRESHOLD = 'sws_disable_search_threshold';
	const MAX_SELECTED_OPTIONS = 'sws_max_selected_options';
	const LANGUAGE = 'sws_language';
	const ALLOW_ADDITION = 'sws_allow_addition';

	const EN = 'en';
	const VERY_LARGE_INT = 99999;

	/**
	 * The text to display as the single select placeholder.
	 * @var string
	 */
	private $placeholder_text_single;

	/**
	 * The text to display as the multi-select placeholder.
	 * @var string
	 */
	private $placeholder_text_multiple;

	/**
	 * Should the user be allowed to de-select after selecting an option.
	 * @var bool
	 */
	private $allow_single_deselect;

	/**
	 * A boolean that turns the search feature on and off.
	 * @var bool
	 */
	private $disable_search;

	/**
	 * If the number of options are less than this threshold, the search option is disabled.
	 * @var int
	 */
	private $disable_search_threshold;

	/**
	 * The maximum number of options the user should be allowed to select.
	 * @var int
	 */
	private $max_selected_options;

	/**
	 * The language to use for the element.
	 * @var string
	 */
	private $language;

	/**
	 * Allow addition of new items when no results are found.
	 * @var boolean
	 */
	private $allow_addition;

	function __construct($args)
	{
		parent::__construct($args);

		$args = wp_parse_args(
			$args,
			$this->get_default_args()
		);

		$this->placeholder_text_single = $args[ self::PLACEHOLDER_TEXT_SINGLE ];
		$this->placeholder_text_multiple = $args[ self::PLACEHOLDER_TEXT_MULTIPLE ];
		$this->allow_single_deselect = $args[ self::ALLOW_SINGLE_DESELECT ];
		$this->disable_search = $args[ self::DISABLE_SEARCH ];
		$this->disable_search_threshold = $args[ self::DISABLE_SEARCH_THRESHOLD ];
		$this->max_selected_options = $args[ self::MAX_SELECTED_OPTIONS ];
		$this->language = $args[ self::LANGUAGE ];
		$this->allow_addition = $args[ self::ALLOW_ADDITION ];
	}

	/**
	 * Renders the markup and makes sure the necessary assets are enqueued properly.
	 */
	function render_field_html()
	{
		if (!$this->is_multiple()) {
			// For single selects an empty value is added so that the advanced features of Select2 will work.
			// This value is only added if one doesn't already exist in the choices array.
			$this->set_choices(
				array('' => '') + $this->get_choices()
			);
		}

		$this->add_html_attributes();

		wp_enqueue_style(Assets::SELECT2_CSS);
		wp_enqueue_script(Assets::SELECT2_JS);
		$this->enqueue_language_file();

		parent::render_field_html();
	}

	/**
	 * Adds HTML attributes to the select.
	 */
	private function add_html_attributes()
	{
		$this->set_attribute(
			'data-placeholder',
			$this->is_multiple() ? $this->placeholder_text_multiple : $this->placeholder_text_single
		);
		$this->set_attribute(
			'data-allow-clear',
			$this->allow_single_deselect ? 'true' : 'false'
		);
		$this->set_attribute(
			'data-minimum-results-for-search',
			$this->disable_search ? self::VERY_LARGE_INT : $this->disable_search_threshold
		);
		$this->set_attribute('data-maximum-selection-length', $this->max_selected_options);
		$this->set_attribute('data-language', $this->language);
		$this->set_attribute(
			'data-tags',
			$this->allow_addition ? 'true' : 'false'
		);
		$this->set_attribute('data-width', '100%');
		$this->set_attribute('data-container-css-class', 'select-with-search-container');
		$this->set_attribute('data-dropdown-css-class', 'select-with-search-dropdown');

		$this->add_class(self::MAIN_SELECTOR);
	}

	/**
	 * Returns the default arguments.
	 * @return array
	 */
	private function get_default_args()
	{
		return array(
			self::PLACEHOLDER_TEXT_SINGLE   => __('Select an Option', 'frontend-publishing-pro'),
			self::PLACEHOLDER_TEXT_MULTIPLE => __('Select Some Options', 'frontend-publishing-pro'),
			self::ALLOW_SINGLE_DESELECT     => false,
			self::DISABLE_SEARCH            => false,
			self::DISABLE_SEARCH_THRESHOLD  => 0,
			self::MAX_SELECTED_OPTIONS      => self::VERY_LARGE_INT,
			self::LANGUAGE                  => self::EN,
			self::ALLOW_ADDITION            => false
		);
	}

	/**
	 * Getter for single-select placeholder.
	 * @return string
	 */
	public function get_placeholder_text_single()
	{
		return $this->placeholder_text_single;
	}

	/**
	 * Setter for single-select placeholder.
	 * @param string $placeholder_text_single
	 */
	public function set_placeholder_text_single($placeholder_text_single)
	{
		$this->placeholder_text_single = $placeholder_text_single;
	}

	/**
	 * Getter for multi-select placeholder.
	 * @return string
	 */
	public function get_placeholder_text_multiple()
	{
		return $this->placeholder_text_multiple;
	}

	/**
	 * Setter for multi-select placeholder.
	 * @param string $placeholder_text_multiple
	 */
	public function set_placeholder_text_multiple($placeholder_text_multiple)
	{
		$this->placeholder_text_multiple = $placeholder_text_multiple;
	}

	/**
	 * Returns boolean indicating if single deselect is allowed.
	 * @return boolean
	 */
	public function allow_single_deselect()
	{
		return $this->allow_single_deselect;
	}

	/**
	 * Set the boolean to allow single deselect.
	 * @param boolean $allow_single_deselect
	 */
	public function set_allow_single_deselect($allow_single_deselect)
	{
		$this->allow_single_deselect = $allow_single_deselect;
	}

	/**
	 * Returns boolean indicating if search is disabled.
	 * @return boolean
	 */
	public function disable_search()
	{
		return $this->disable_search;
	}

	/**
	 * Sets boolean to disable search.
	 * @param boolean $disable_search
	 */
	public function set_disable_search($disable_search)
	{
		$this->disable_search = $disable_search;
	}

	/**
	 * Gets the value of disable search threshold.
	 * @return int
	 */
	public function get_disable_search_threshold()
	{
		return $this->disable_search_threshold;
	}

	/**
	 * Sets the value of disable search threshold.
	 * @param int $disable_search_threshold
	 */
	public function set_disable_search_threshold($disable_search_threshold)
	{
		$this->disable_search_threshold = $disable_search_threshold;
	}

	/**
	 * Gets the maximum number of selections that user is allowed to make.
	 * @return int
	 */
	public function get_max_selected_options()
	{
		return $this->max_selected_options;
	}

	/**
	 * Sets the maximum number of selections that user is allowed to make
	 * @param int $max_selected_options
	 */
	public function set_max_selected_options($max_selected_options)
	{
		$this->max_selected_options = $max_selected_options;
	}

	/**
	 * Gets the language for the element.
	 * @return string
	 */
	public function get_language()
	{
		return $this->language;
	}

	/**
	 * Sets the language for the element.
	 * @param string $language
	 */
	public function set_language($language)
	{
		$this->language = $language;
	}

	/**
	 * Gets the boolean indicating whether new items can be added or not.
	 * @return boolean
	 */
	public function allow_addition()
	{
		return $this->allow_addition;
	}

	/**
	 * Sets the boolean indicating whether new items can be added or not.
	 * @param boolean $allow_addition
	 */
	public function set_allow_addition($allow_addition)
	{
		$this->allow_addition = $allow_addition;
	}

	private function enqueue_language_file()
	{
		if ($this->language != self::EN) {
			wp_enqueue_script(
				sprintf(
					Assets::SELECT2_LANGUAGE,
					$this->language
				),
				Asset_Manager::get_libs_url() . 'select2/dist/js/i18n/' . $this->language . '.js'
			);
		}
	}
}