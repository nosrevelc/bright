<?php
namespace WPFEPP\Constants;

if (!defined('WPINC')) die;

abstract class Post_List_Settings
{
	const PAGE_LENGTH = 'page_length';
	const ACTIVE_TABS = 'active_tabs';
	const DELETE_COLUMN_TABS = 'delete_column_tabs';
	const EDIT_COLUMN_TABS = 'edit_column_tabs';
	const LINK_COLUMN_TABS = 'view_column_tabs';
	const ONLY_FRONTEND_POSTS = 'only_frontend_posts';
	const EDITOR_ROLES = 'editor_roles';
	const ALLOW_EDITING_STATUSES = 'allow_editing_statuses';
	const ALLOW_DELETING_STATUSES = 'allow_deleting_statuses';
	const REQUIRE_LOGIN = 'require_login';
	const REDIRECT_TO_LOGIN = 'redirect_to_login';
	const HIDE_TABS = 'hide_tabs';
}