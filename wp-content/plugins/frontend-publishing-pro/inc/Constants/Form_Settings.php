<?php
namespace WPFEPP\Constants;

if (!defined('WPINC')) die;

abstract class Form_Settings
{
	const SETTING_NO_RESTRICTIONS = 'no_restrictions';
	const SETTING_INSTANTLY_PUBLISH = 'instantly_publish';
	const SETTING_WIDTH = 'width';
	const SETTING_REDIRECTION_TYPE = 'redirection_type';
	const SETTING_REDIRECT_URL = 'redirect_url';
	const SETTING_ENABLE_DRAFTS = 'enable_drafts';
	const SETTING_CAPTCHA_ENABLED = 'captcha_enabled';
	const SETTING_ADVANCED_VALIDATION = 'advanced_validation';
	const SETTING_TOOLTIPS = 'show_error_tooltips';
	const SETTING_AUTOSAVE_POSTS = 'autosave_posts';
	const SETTING_AUTOSAVE_INTERVAL = 'autosave_interval';
	const SETTING_REQUIRE_LOGIN = 'require_login';
	const SETTING_REDIRECT_TO_LOGIN = 'redirect_to_login';
	const SETTING_ANONYMOUS_POST_AUTHOR = 'anonymous_post_author';
}