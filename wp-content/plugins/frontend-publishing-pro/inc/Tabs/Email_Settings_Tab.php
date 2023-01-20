<?php
namespace WPFEPP\Tabs;

if (!defined('WPINC')) die;

class Email_Settings_Tab extends Backend_Tab
{
	const TAB_SLUG = 'email';

	function __construct()
	{
		parent::__construct(
			self::TAB_SLUG,
			__('Email', 'frontend-publishing-pro')
		);
	}

	function render()
	{
		$form = new \WPFEPP\Forms\Email_Settings_Form();
		$form->render();
	}
}