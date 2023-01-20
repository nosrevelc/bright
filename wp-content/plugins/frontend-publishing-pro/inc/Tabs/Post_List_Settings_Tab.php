<?php
namespace WPFEPP\Tabs;

if (!defined('WPINC')) die;

use WPFEPP\Forms\Post_List_Settings_Form;

class Post_List_Settings_Tab extends Backend_Tab
{
	const TAB_SLUG = 'post-list';

	function __construct()
	{
		parent::__construct(
			self::TAB_SLUG,
			__('Post List', 'frontend-publishing-pro')
		);
	}

	function render()
	{
		$form = new Post_List_Settings_Form();
		$form->render();
	}
}