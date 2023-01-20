<?php
namespace WPFEPP\Ajax;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Plugin_Forms;
use WPFEPP\Element_Containers\Frontend_Element_Container;
use WPFEPP\Factories\Form_Factory;
use WPFEPP\Forms\Frontend_Form;

class Frontend_Form_Ajax extends \WPFEPP\Ajax
{
	const ACTION = 'wpfepp_handle_submission';

	function __construct()
	{
		parent::__construct();

		$this->register_action(self::ACTION_PREFIX . self::ACTION, array($this, 'handle_post_submission'));
		$this->register_action(self::NOPRIV_ACTION_PREFIX . self::ACTION, array($this, 'handle_post_submission'));
	}

	function handle_post_submission()
	{
		$form_db_id = $_POST[ Frontend_Element_Container::ELEM_KEY_FORM_DB_ID ];
		/**
		 * @var Frontend_Form $form
		 */
		$form = Form_Factory::make_form(
			Plugin_Forms::FRONTEND_FORM,
			$form_db_id
		);

		$submission_successful = $form->handle_submission();
		$result = array(
			'success'                  => $submission_successful,
			'request_type'             => $form->get_request_type(),
			'media_file_elements_html' => $form->get_media_file_elements_html(),
			'redirect_url'             => $form->get_redirect_url()
		);

		if ($submission_successful) {
			$result['post_id'] = $form->get_new_post_id();
			$result['message'] = $form->get_success_message();
			$result['post_status'] = $form->get_new_post_status();
		} else {
			$result['errors'] = $form->get_printable_errors();
		}

		die(json_encode($result));
	}
}