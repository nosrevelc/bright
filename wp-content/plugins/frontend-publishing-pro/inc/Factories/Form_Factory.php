<?php

namespace WPFEPP\Factories;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Plugin_Forms;
use WPGurus\Forms\Form;

class Form_Factory extends \WPGurus\Forms\Factory
{
	/**
	 * Makes a form element.
	 *
	 * @param $form_type string The form to create
	 * @param array $args Arguments to pass to the constructor
	 * @return Form|null
	 */
	public static function make_form($form_type, $args = array())
	{
		$form = null;
		$form_class = '';

		switch($form_type)
		{
			case Plugin_Forms::CUSTOM_FIELDS_FORM:
				$form_class = '\WPFEPP\Forms\Custom_Fields_Form';
				break;

			case Plugin_Forms::DATA_SETTINGS_FORM:
				$form_class = '\WPFEPP\Forms\Data_Settings_Form';
				break;

			case Plugin_Forms::EMAIL_SETTINGS_FORM:
				$form_class = '\WPFEPP\Forms\Email_Settings_Form';
				break;

			case Plugin_Forms::FORM_DETAILS_FORM:
				$form_class = '\WPFEPP\Forms\Form_Details_Form';
				break;

			case Plugin_Forms::FORM_EMAILS_FORM:
				$form_class = '\WPFEPP\Forms\Form_Emails_Form';
				break;

			case Plugin_Forms::FORM_FIELDS_FORM:
				$form_class = '\WPFEPP\Forms\Form_Fields_Form';
				break;

			case Plugin_Forms::FORM_POST_LIST_FORM:
				$form_class = '\WPFEPP\Forms\Form_Post_List_Form';
				break;

			case Plugin_Forms::FORM_SETTINGS_FORM:
				$form_class = '\WPFEPP\Forms\Form_Settings_Form';
				break;

			case Plugin_Forms::FRONTEND_FORM:
				$form_class = '\WPFEPP\Forms\Frontend_Form';
				break;

			case Plugin_Forms::FRONTEND_MESSAGES_FORM:
				$form_class = '\WPFEPP\Forms\Frontend_Messages_Form';
				break;

			case Plugin_Forms::GENERAL_FORM_SETTINGS_FORM:
				$form_class = '\WPFEPP\Forms\General_Form_Settings_Form';
				break;

			case Plugin_Forms::MEDIA_SETTINGS_FORM:
				$form_class = '\WPFEPP\Forms\Media_Settings_Form';
				break;

			case Plugin_Forms::POST_LIST_SETTINGS_FORM:
				$form_class = '\WPFEPP\Forms\Post_List_Settings_Form';
				break;

			case Plugin_Forms::RECAPTCHA_SETTINGS_FORM:
				$form_class = '\WPFEPP\Forms\reCaptcha_Settings_Form';
				break;
		}

		if ($form_class) {
			$form = self::create_instance($form_class, $args);
		}

		$form = self::filter_object('_manufactured_form', array($form, $form_type, $args), '\WPGurus\Forms\Form');

		return $form;
	}

}