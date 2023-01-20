<?php

namespace WPFEPP\Tabs;

if (!defined('WPINC')) die;

use WPFEPP\Admin_Pages\Plugin_Settings;
use WPFEPP\Constants\Form_Meta_Keys;
use WPFEPP\Element_Containers\Email_Settings_Container;
use WPFEPP\Forms\Form_Emails_Form;
use WPFEPP\Forms\Splitter_Form;

class Form_Emails_Tab extends Form_Tab
{
	function __construct($form_id)
	{
		parent::__construct(
			'emails',
			__('Emails', 'frontend-publishing-pro'),
			$form_id
		);
	}

	function render()
	{
		$current_emails = $this->form_meta_table->get_meta_value($this->form_id, Form_Meta_Keys::EMAILS);

		if ($current_emails) {
			$form = new Form_Emails_Form($this->form_id, $current_emails);
		} else {
			$container = new Email_Settings_Container();
			$url = admin_url(
				sprintf(
					'admin.php?page=%s&tab=%s',
					Plugin_Settings::PAGE_ID,
					Email_Settings_Tab::TAB_SLUG
				)
			);
			$form = new Splitter_Form(
				$this->form_id,
				Form_Meta_Keys::EMAILS,
				$container->get_values(),
				sprintf(
					__('This form currently inherits email settings from the general plugin settings %s.', 'frontend-publishing-pro'),
					sprintf(
						'<a href="%s">%s</a>',
						$url,
						__('here', 'frontend-publishing-pro')
					)
				),
				__('Create form-specific email settings', 'frontend-publishing-pro')
			);
		}

		$form->render();
	}
}