<?php
namespace WPFEPP\Tabs;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Plugin_Forms;
use WPFEPP\Factories\Form_Factory;

class General_Form_Settings_Tab extends Backend_Tab
{
	const TAB_SLUG = 'form-settings';

	function __construct()
	{
		parent::__construct(
			self::TAB_SLUG,
			__('Form Settings', 'frontend-publishing-pro')
		);
	}

	function render()
	{
		$form = Form_Factory::make_form(
			Plugin_Forms::GENERAL_FORM_SETTINGS_FORM
		);
		$form->render();
	}
}