<?php
namespace WPFEPP\Tabs;

if (!defined('WPINC')) die;

use WPFEPP\Forms\Edit_Link_Settings_Form;

class Edit_Link_Settings_Tab extends Backend_Tab
{
	function __construct()
	{
		parent::__construct(
			'edit-links',
			__('Edit Links', 'frontend-publishing-pro')
		);
	}

	public function render()
	{
		$form = new Edit_Link_Settings_Form();
		$form->render();
	}
}