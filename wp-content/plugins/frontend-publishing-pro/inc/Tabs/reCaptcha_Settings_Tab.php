<?php
namespace WPFEPP\Tabs;

if (!defined('WPINC')) die;

class reCaptcha_Settings_Tab extends Backend_Tab
{
	function __construct()
	{
		parent::__construct(
			'recaptcha',
			'reCaptcha'
		);
	}

	function render()
	{
		$form = new \WPFEPP\Forms\reCaptcha_Settings_Form();
		$form->render();
	}
}