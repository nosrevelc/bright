<?php
namespace WPFEPP\Ajax;

if (!defined('WPINC')) die;

use WPFEPP\Ajax;
use WPFEPP\Constants\Form_Settings;
use WPFEPP\Constants\Plugin_Forms;
use WPFEPP\Element_Containers\Frontend_Element_Container;
use WPFEPP\Factories\Form_Factory;
use WPFEPP\Forms\Frontend_Form;
use WPGurus\Forms\Utils;

class Field_Validation_Ajax extends Ajax
{
	const ACTION = 'validate_single_element';

	public function __construct()
	{
		parent::__construct();
		$this->register_action(self::ACTION_PREFIX . self::ACTION, array($this, 'validate_field'));
		$this->register_action(self::NOPRIV_ACTION_PREFIX . self::ACTION, array($this, 'validate_field'));
	}

	public function validate_field()
	{
		$form_db_id = $_POST[ Frontend_Element_Container::ELEM_KEY_FORM_DB_ID ];
		$element_key = $_POST['element_key'];
		/**
		 * @var Frontend_Form $form
		 */
		$form = Form_Factory::make_form(
			Plugin_Forms::FRONTEND_FORM,
			$form_db_id
		);
		$form_settings = $form->get_form_settings();
		$element_container = $form->get_element_container();
		$element = $element_container->get_element($element_key);
		$is_valid = true;
		$current_user_exempt = \WPFEPP\current_user_can($form_settings[ Form_Settings::SETTING_NO_RESTRICTIONS ]);

		if (!$current_user_exempt) {
			$value = Utils::get_from_array($_POST, $element->get_key());
			$element->validate($value);
			$is_valid = $element->is_valid();
		}

		if ($is_valid) {
			$response = true;
		} else {
			$response = $element->get_printable_errors();
		}

		die(json_encode($response));
	}
}