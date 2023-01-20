<?php
namespace WPFEPP\Constants;

if (!defined('WPINC')) die;

use WPGurus\Forms\Elements\Google_reCaptcha;

abstract class reCaptcha_Settings
{
	const SETTING_SITE_KEY = Google_reCaptcha::SITE_KEY;
	const SETTING_SECRET = Google_reCaptcha::SITE_SECRET;
	const SETTING_THEME = Google_reCaptcha::THEME;
}