<?php
namespace WPFEPP\Constants;

if (!defined('WPINC')) die;

abstract class Post_List_Tabs
{
	const PUBLISHED = 'publish';
	const PENDING = 'pending';
	const DRAFT = 'draft';
}