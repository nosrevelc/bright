<?php
namespace WPFEPP\Tabs;

use WPFEPP\Constants\Plugin_Forms;
use WPFEPP\Factories\Form_Factory;

if (!defined('WPINC')) die;

class Frontend_Messages_Tab extends Backend_Tab
{
	function __construct()
	{
		parent::__construct(
			'messages',
			__('Messages', 'frontend-publishing-pro')
		);
	}

	function render()
	{
		$form = Form_Factory::make_form(
			Plugin_Forms::FRONTEND_MESSAGES_FORM
		);
		$form->render();
	}
}