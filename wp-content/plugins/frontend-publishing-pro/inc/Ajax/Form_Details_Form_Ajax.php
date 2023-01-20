<?php
namespace WPFEPP\Ajax;

use WPFEPP\Constants\Plugin_Forms;
use WPFEPP\Factories\Form_Factory;
use WPFEPP\Forms\Form_Details_Form;

if (!defined('WPINC')) die;

class Form_Details_Form_Ajax extends \WPFEPP\Ajax
{
	const ACTION = 'wpfepp_create_form';

	function __construct()
	{
		parent::__construct();
		$this->register_action(self::ACTION_PREFIX . self::ACTION, array($this, 'create_form'));
	}

	function create_form()
	{
		$page = $_POST[ \WPFEPP\Element_Containers\Form_Details_Container::ELEM_KEY_PAGE ];
		/**
		 * @var Form_Details_Form $details_form
		 */
		$details_form = Form_Factory::make_form(
			Plugin_Forms::FORM_DETAILS_FORM,
			$page
		);

		$success = $details_form->handle_submission();

		$result = array(
			'success' => $success
		);

		if ($success) {
			ob_start();
			$table = new \WPFEPP\List_Tables\Forms($page);
			$table->render();
			$result['table_html'] = ob_get_clean();
		} else {
			$result['errors'] = $details_form->get_printable_errors();
		}

		die(json_encode($result));
	}
}