<?php
namespace WPFEPP\Constants;

if (!defined('WPINC')) die;

abstract class Plugin_Actions
{
	const POST_SUBMITTED = 'wpfepp_post_submitted';
	const BEFORE_POST_INSERTION = 'wpfepp_before_post_insertion';
	const AFTER_POST_INSERTION = 'wpfepp_after_post_insertion';
	const AFTER_FORM_FIELDS = 'wpfepp_form_%s_fields';
	const AFTER_FORM_FIELDS_GENERAL = 'wpfepp_form_fields';
}