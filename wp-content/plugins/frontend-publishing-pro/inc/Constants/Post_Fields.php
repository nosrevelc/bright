<?php
namespace WPFEPP\Constants;

if (!defined('WPINC')) die;

abstract class Post_Fields
{
	const FIELD_TITLE = 'post_title';
	const FIELD_CONTENT = 'post_content';
	const FIELD_EXCERPT = 'post_excerpt';
	const FIELD_POST_FORMAT = 'post_format';
	const FIELD_POST_ID = 'ID';
	const FIELD_POST_TYPE = 'post_type';
	const FIELD_AUTHOR = 'post_author';
	const FIELD_DATE = 'post_date';
	const FIELD_STATUS = 'post_status';
	const FIELD_THUMBNAIL = 'thumbnail';
	const FIELD_HIERARCHICAL_TAX = 'hierarchical_taxonomy';
	const FIELD_NON_HIERARCHICAL_TAX = 'non_hierarchical_taxonomy';
	const FIELD_CUSTOM = 'custom_field';
	const FIELD_COMMENT_STATUS = 'comment_status';
}