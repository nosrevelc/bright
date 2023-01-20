<?php

namespace WPFEPP\Forms;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Form_Meta_Keys;
use WPFEPP\Element_Containers\Email_Settings_Container;

class Form_Emails_Form extends Backend_DB_Form
{
	function __construct($form_id, $current_emails)
	{
		parent::__construct($form_id, Form_Meta_Keys::EMAILS, 'form-emails');

		$element_container = new Email_Settings_Container(
			$current_emails
		);

		$this->set_element_container($element_container);
	}
}