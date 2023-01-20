<?php
namespace WPFEPP\Constants;

if (!defined('WPINC')) die;

use WPGurus\Forms\Element;
use WPGurus\Forms\Elements\Date_Picker;
use WPGurus\Forms\Elements\Rich_Text;
use WPGurus\Forms\Elements\Select;
use WPGurus\Forms\Elements\Select_With_Search;
use WPGurus\Forms\Elements\Star_Rating;

abstract class Post_Field_Settings
{
	const SETTING_ENABLED = 'enabled';
	const SETTING_WIDTH = 'width';
	const SETTING_FALLBACK = 'fallback_value';
	const SETTING_TYPE = 'type';
	const SETTING_ELEMENT = 'element';

	// Custom fields
	const SETTING_SAVE_CUSTOM_VAL_TO_META = 'save_custom_field_to_meta';
	const SETTING_CUSTOM_FIELD_LOCATIONS = 'custom_field_locations';
	const SETTING_ENABLE_CUSTOM_FIELD_EMBEDS = 'enable_embeds';

	// Taxonomy settings
	const SETTING_HIDE_EMPTY_TAXONOMIES = 'hide_empty_taxonomies';
	const SETTING_EXCLUDE_TAXONOMIES = 'exclude_taxonomies';
	const SETTING_INCLUDE_TAXONOMIES = 'include_taxonomies';
	const SETTING_DISPLAY_HIERARCHICALLY = 'display_terms_hierarchically';

	// Element settings
	const SETTING_LABEL = Element::LABEL;
	const SETTING_PREFIX_TEXT = Element::PREFIX;

	// Select settings
	const SETTING_MULTIPLE = Select::MULTIPLE;
	const SETTING_CHOICES = Select::CHOICES;

	// Rich text settings
	const SETTING_RICH_MEDIA_BUTTON = Rich_Text::MEDIA_BUTTONS;
	const SETTING_RICH_EDITOR_HEIGHT = Rich_Text::EDITOR_HEIGHT;

	// Date picker settings
	const SETTING_DATE_PICKER_FORMAT = Date_Picker::FORMAT;
	const SETTING_DATE_PICKER_LANGUAGE = Date_Picker::LANGUAGE;
	const SETTING_DATE_PICKER_DATE_ENABLED = Date_Picker::DATE_ENABLED;
	const SETTING_DATE_PICKER_TIME_ENABLED = Date_Picker::TIME_ENABLED;
	const SETTING_DATE_PICKER_THEME = Date_Picker::THEME;

	// Star rating
	const SETTING_RATING_NUMBER = Star_Rating::NUMBER;

	// Select with search
	const SETTING_SWS_PLACEHOLDER_TEXT_SINGLE = Select_With_Search::PLACEHOLDER_TEXT_SINGLE;
	const SETTING_SWS_PLACEHOLDER_TEXT_MULTIPLE = Select_With_Search::PLACEHOLDER_TEXT_MULTIPLE;
	const SETTING_SWS_ALLOW_SINGLE_DESELECT = Select_With_Search::ALLOW_SINGLE_DESELECT;
	const SETTING_SWS_DISABLE_SEARCH = Select_With_Search::DISABLE_SEARCH;
	const SETTING_SWS_DISABLE_SEARCH_THRESHOLD = Select_With_Search::DISABLE_SEARCH_THRESHOLD;
	const SETTING_SWS_MAX_SELECTED_OPTIONS = Select_With_Search::MAX_SELECTED_OPTIONS;
	const SETTING_SWS_LANGUAGE = Select_With_Search::LANGUAGE;
	const SETTING_SWS_ALLOW_ADDITION = Select_With_Search::ALLOW_ADDITION;

	// Media settings
	const SETTING_MEDIA_ITEMS_DISPLAY_COLUMNS = 'media_items_display_columns';
	const SETTING_MEDIA_ITEMS_DISPLAY_SIZE = 'media_items_display_size';
	const SETTING_MEDIA_ITEMS_DISPLAY_LINK = 'media_items_display_link';
	const SETTING_MEDIA_ITEM_DISPLAY_SIZE = 'media_item_display_size';
	const SETTING_MEDIA_URL_DISPLAY_WIDTH = 'media_url_display_size';
}