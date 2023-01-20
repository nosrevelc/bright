<?php
namespace WPFEPP\Tabs;

if (!defined('WPINC')) die;

class Data_Settings_Tab extends Backend_Tab
{
	function __construct()
	{
		parent::__construct(
			'data',
			__('Data', 'frontend-publishing-pro')
		);
	}

	function render()
	{
		$form = new \WPFEPP\Forms\Data_Settings_Form();
		$form->render();
	}
}