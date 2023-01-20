<?php
namespace WPFEPP\Constants;

use WPFEPP\Enum;

if (!defined('WPINC')) die;

abstract class Email_Placeholders extends Enum
{
	const PLACEHOLDER_POST_TITLE = '%%POST_TITLE%%';
	const PLACEHOLDER_POST_PERMALINK = '%%POST_PERMALINK%%';
	const PLACEHOLDER_AUTHOR_NAME = '%%AUTHOR_NAME%%';
	const PLACEHOLDER_SITE_NAME = '%%SITE_NAME%%';
	const PLACEHOLDER_SITE_URL = '%%SITE_URL%%';
	const PLACEHOLDER_ADMIN_NAME = '%%ADMIN_NAME%%';
	const PLACEHOLDER_EDIT_LINK = '%%EDIT_LINK%%';
}