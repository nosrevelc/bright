<?php
namespace WPGurus\Forms\Constants;

if (!defined('WPINC')) die;

/**
 * Contains all the available element types.
 *
 * Class Elements
 * @package WPGurus\Forms\Constants
 */
class Elements extends \WPGurus\Forms\Enum
{
	const BUTTON = 'element_button';
	const CHECKBOX = 'element_checkbox';
	const COLOR_PICKER = 'element_color_picker';
	const DATE_PICKER = 'element_date_picker';
	const EMAIL = 'element_email';
	const FILE = 'element_ile';
	const HIDDEN = 'element_hidden';
	const MEDIA_FILE = 'element_media_file';
	const MEDIA_ID = 'element_media_id';
	const MEDIA_IDS = 'element_media_ids';
	const MEDIA_URL = 'element_media_url';
	const NONCE = 'element_nonce';
	const NUMBER = 'element_number';
	const PASSWORD = 'element_password';
	const RICH_TEXT = 'element_richtext';
	const SANDBOXED_RICH_TEXT = 'element_sandboxed_rich_text';
	const SELECT = 'element_select';
	const SELECT_WITH_SEARCH = 'element_select_with_search';
	const STAR_RATING = 'element_star_rating';
	const SUBMIT_BUTTON = 'element_submit_button';
	const TEXT = 'element_text';
	const TEXTAREA = 'element_textarea';
	const GOOGLE_RECAPTCHA = 'element_google_recaptcha';
}