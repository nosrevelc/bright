<?php
namespace WPFEPP\Constants;

if (!defined('WPINC')) die;

abstract class Plugin_Components
{
	const ADMIN_MESSAGES = 'admin_messages';
	const ASSET_MANAGER = 'asset_manager';
	const DATA_DELETER = 'data_deleter';
	const DB_SETUP = 'db_setup';
	const EMAIL_MANAGER = 'email_manager';
	const MEDIA_RESTRICTIONS = 'media_restrictions';
	const PLUGIN_COMPONENTS = 'plugin_components';
	const POST_DELETER = 'post_deleter';
	const POST_PREVIEWS = 'post_previews';
	const REWRITES = 'rewrites';
	const TINYMCE_VALIDATION_FIX = 'tinymce_validation_fix';
}