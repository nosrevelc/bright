<?php
namespace WPFEPP\Constants;

if (!defined('WPINC')) die;

abstract class Query_Vars
{
	const PAGE = 'page';
	const POST_DELETER_NONCE = 'nonce';
	const POST_DELETER_ACTIVE_TAB = 'tab';
	const POST_DELETED = 'deleted';
	const POST_PREVIEW_NONCE = '_preview_nonce';
	const QUICK_EDIT_FORM_ID = 'frontend_form_id';
	const FORM_MANAGER_FORM_ID = 'form_id';
	const FORM_MANAGER_DELETION_NONCE = 'form_deletion_nonce';
	const FORM_MANAGER_POST_COUNT = 'post_count';
	const FORM_MANAGER_DELETED = 'deleted';
	const FORM_MANAGER_ACTION = 'action';
	const FORM_MANAGER_POST_TYPE = 'post_type';
}