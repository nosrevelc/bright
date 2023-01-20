<?php

namespace WPFEPP\Tabs;

if (!defined('WPINC')) die;

use WPFEPP\Admin_Pages\Plugin_Settings;
use WPFEPP\Constants\Form_Meta_Keys;
use WPFEPP\Constants\Plugin_Forms;
use WPFEPP\Element_Containers\Form_Settings_Container;
use WPFEPP\Factories\Form_Factory;
use WPFEPP\Forms\Splitter_Form;

class Form_Settings_Tab extends Form_Tab
{
	function __construct($form_id)
	{
		parent::__construct(
			'settings',
			__('Settings', 'frontend-publishing-pro'),
			$form_id
		);
	}

	function render()
	{
		$current_settings = $this->form_meta_table->get_meta_value($this->form_id, Form_Meta_Keys::SETTINGS);

		if ($current_settings) {
			$form = Form_Factory::make_form(
				Plugin_Forms::FORM_SETTINGS_FORM,
				array($this->form_id, $current_settings)
			);
		} else {
			$container = new Form_Settings_Container();
			$url = admin_url(
				sprintf(
					'admin.php?page=%s&tab=%s',
					Plugin_Settings::PAGE_ID,
					General_Form_Settings_Tab::TAB_SLUG
				)
			);
			$form = new Splitter_Form(
				$this->form_id,
				Form_Meta_Keys::SETTINGS,
				$container->get_values(),
				sprintf(
					__('This form currently inherits its settings from the general plugin settings %s.', 'frontend-publishing-pro'),
					sprintf(
						'<a href="%s">%s</a>',
						$url,
						__('here', 'frontend-publishing-pro')
					)
				),
				__('Create form-specific settings', 'frontend-publishing-pro')
			);
		}

		$form->render();
	}
}