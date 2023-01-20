<?php
namespace WPFEPP\Constants;

if (!defined('WPINC')) die;

use WPGurus\Forms\Constants\Validators;

abstract class Frontend_Form_Messages
{
	const SETTING_FORM_ERROR = 'form_error';

	// Validator errors.
	const SETTING_INVALID_EMAIL_ERROR = Validators::EMAIL_FORMAT;
	const SETTING_MAX_CHARS_ERROR = Validators::MAX_CHARACTERS;
	const SETTING_MAX_COUNT_ERROR = Validators::MAX_COUNT;
	const SETTING_MAX_LINKS_ERROR = Validators::MAX_LINKS;
	const SETTING_MAX_WORDS_ERROR = Validators::MAX_WORDS;
	const SETTING_MIN_CHARS_ERROR = Validators::MIN_CHARACTERS;
	const SETTING_MIN_COUNT_ERROR = Validators::MIN_COUNT;
	const SETTING_MIN_WORDS_ERROR = Validators::MIN_WORDS;
	const SETTING_REQUIRED_ERROR = Validators::REQUIRED;
	const SETTING_INVALID_URL_ERROR = Validators::URL_FORMAT;
	const SETTING_REGEX_ERROR = Validators::REGEX;
	const SETTING_VALUE_ERROR = Validators::VALUE;
	const SETTING_PUBLISHED_MESSAGE = 'frontend_form_post_published_message';
	const SETTING_SUBMITTED_MESSAGE = 'frontend_form_post_submitted_message';
	const SETTING_RECAPTCHA_ERROR = 'google_recaptcha_error';
	const SETTING_FILE_TOO_LARGE = 'file_too_large_error';
	const SETTING_LOGIN_REQUIRED_ERROR = 'login_required_error';
}