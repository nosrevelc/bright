<?php
namespace WPFEPP\Tabs;

if (!defined('WPINC')) die;

class Update_Settings_Tab extends Backend_Tab
{
	function __construct()
	{
		parent::__construct(
			'updates',
			'Updates'
		);
	}

	function render()
	{
		$form = new \WPFEPP\Forms\Update_Settings_Form();
		$form->render();
	}
}