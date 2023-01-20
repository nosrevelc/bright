<?php

namespace WPFEPP\Constants;

if (!defined('WPINC')) die;

use WPGurus\Forms\Enum;

class Option_Ids extends Enum
{
	const OPTION_DATA_SETTINGS = 'wpfepp_data_settings';
	const OPTION_EMAIL_SETTINGS = 'wpfepp_email_settings';
	const OPTION_MEDIA_SETTINGS = 'wpfepp_media_settings';
	const OPTION_MESSAGES = 'wpfepp_messages';
	const OPTION_RECAPTCHA_SETTINGS = 'wpfepp_recaptcha_settings';
	const OPTION_GENERAL_FORM_SETTINGS = 'wpfepp_general_form_settings';
	const OPTION_POST_LIST_SETTINGS = 'wpfepp_post_list_settings';
	const OPTION_REWRITE_RULES_FLUSHED = 'wpfepp_rewrite_rules_flushed';
	const OPTION_NAG_DISMISSED = 'wpfepp_nag_dismissed';
	const OPTION_HAS_INCOMPATIBLE_CHANGES = 'wpfepp_has_incompatible_changes';
	const OPTION_EDIT_LINK_SETTINGS = 'wpfepp_edit_link_settings';
	const OPTION_UPDATE_SETTINGS = 'wpfepp_update_settings';
	const OPTION_PLUGIN_VERSION = 'wpfepp_version';
}