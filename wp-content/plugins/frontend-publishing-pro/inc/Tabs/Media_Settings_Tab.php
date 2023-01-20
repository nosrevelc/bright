<?php
namespace WPFEPP\Tabs;

if (!defined('WPINC')) die;

class Media_Settings_Tab extends Backend_Tab
{
	function __construct()
	{
		parent::__construct(
			'media',
			__('Media', 'frontend-publishing-pro')
		);
	}

	function render()
	{
		$form = new \WPFEPP\Forms\Media_Settings_Form();
		$form->render();
	}
}