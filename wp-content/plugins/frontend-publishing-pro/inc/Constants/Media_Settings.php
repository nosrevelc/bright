<?php
namespace WPFEPP\Constants;

if (!defined('WPINC')) die;

abstract class Media_Settings
{
	const SETTING_MAX_UPLOAD_SIZE = 'max_upload_size';
	const SETTING_OWN_MEDIA_ONLY = 'own_media_only';
	const SETTING_ALLOWED_MEDIA_TYPES = 'allowed_media_types';
	const SETTING_EXEMPT_ROLES = 'exempt_roles';
	const SETTING_FORCE_ALLOW_UPLOADS = 'force_allow_uploads';
	const SETTING_MAKE_RESTRICTIONS_GLOBAL = 'make_restrictions_global';
}