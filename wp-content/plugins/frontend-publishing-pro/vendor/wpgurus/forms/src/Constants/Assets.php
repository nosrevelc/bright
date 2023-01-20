<?php
namespace WPGurus\Forms\Constants;

if (!defined('WPINC')) die;

/**
 * Contains hooks of all the JS and CSS assets used by the package.
 *
 * Class Assets
 * @package WPGurus\Forms\Constants
 */
class Assets extends \WPGurus\Forms\Enum
{
	const JQUERY_UI_EFFECTS_CORE = 'jquery-effects-core';

	const FONT_AWESOME_CSS = 'wpgurus_font_awesome_css';

	const ELEMENTS_JS = 'wpgurus_form_elements_common_js';

	const RATY_CSS = 'wpgurus_raty_css';
	const RATY_JS = 'wpgurus_raty_js';

	const SELECT2_CSS = 'wpgurus_select2_css';
	const SELECT2_JS = 'wpgurus_select2_js';
	const SELECT2_LANGUAGE = 'wpgurus_select2_language_%s';

	const WP_MEDIA_LIB_ELEMENT_JS = 'wpgurus_wp_media_lib_element_js';
	const WP_MEDIA_LIB_ELEMENT_CSS = 'wpgurus_wp_media_lib_element_css';

	const MEDIA_FILE_ELEMENT_JS = 'wpgurus_media_file_element_js';
	const MEDIA_FILE_ELEMENT_CSS = 'wpgurus_media_file_element_css';

	const DATE_TIME_PICKER_JS = 'wpgurus_date_time_picker_js';
	const DATE_TIME_PICKER_CSS = 'wpgurus_date_time_picker_css';

	const COLOR_PICKER_JS = 'wpgurus_color_picker';

	const ADMIN_CSS = 'wpgurus_admin_css';
	const OPEN_SANS_CSS = 'wpgurus_open_sans_css';

	const IFRAME_RESIZER_JS = 'wpgurus_iframe_resizer_js';
	const IFRAME_RESIZER_CONTENT_WINDOW_JS = 'wpgurus_iframe_resizer_content_window_js';

	const RICHTEXT_IFRAME_CSS = 'wpgurus_richtext_iframe_css';
	const RICHTEXT_IFRAME_JS = 'wpgurus_richtext_iframe_js';

	const RICHTEXT_IFRAME_CONTENT_JS = 'wpgurus_richtext_iframe_content_js';
	const RICHTEXT_IFRAME_CONTENT_CSS = 'wpgurus_richtext_iframe_content_css';

	const GOOGLE_RECAPTCHA = 'wpgurus_google_recaptcha';
}