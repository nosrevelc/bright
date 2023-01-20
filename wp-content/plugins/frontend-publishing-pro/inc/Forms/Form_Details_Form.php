<?php

namespace WPFEPP\Forms;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Form_Meta_Keys;
use WPFEPP\DB_Tables\Form_Meta;
use WPFEPP\DB_Tables\Forms;
use WPFEPP\Element_Containers\Form_Details_Container;
use WPFEPP\Element_Containers\Form_Fields_Container;

class Form_Details_Form extends Backend_Form
{
	function __construct($page)
	{
		parent::__construct(
			'form-details',
			new Form_Details_Container($page),
			true,
			true,
			array(
				'class' => 'form-manager-ajax-form form-details'
			)
		);
	}

	public function get_container()
	{
		return $this->get_element_container();
	}

	function process_data($data)
	{
		$error = sprintf(
			__('The form could not be created. The following DB error occured: %s', 'frontend-publishing-pro'),
			'<br/><br/>%s<pre></pre>'
		);
		$forms_table = new Forms();
		$form_row = array(
			Forms::COLUMN_NAME        => $data[ Forms::COLUMN_NAME ],
			Forms::COLUMN_DESCRIPTION => $data[ Forms::COLUMN_DESCRIPTION ],
			Forms::COLUMN_POST_TYPE   => $data[ Forms::COLUMN_POST_TYPE ]
		);

		$result = $forms_table->add($form_row);

		if ($result === false) {
			$this->add_form_errors(
				sprintf(
					$error,
					$forms_table->get_error()
				)
			);
			return false;
		}

		$form_id = $forms_table->get_insert_id();

		$form_fields_container = new Form_Fields_Container(
			$data[ Forms::COLUMN_POST_TYPE ]
		);

		$form_meta_table = new Form_Meta();
		$result = $form_meta_table->add_meta_value($form_id, Form_Meta_Keys::FIELDS, $form_fields_container->get_values());

		if ($result === false) {
			$this->add_form_errors(
				sprintf(
					$error,
					$form_meta_table->get_error()
				)
			);
			$forms_table->remove($form_id);
			return false;
		}

		$this->set_success_message(__('The form was created successfully!', 'frontend-publishing-pro'));
		return true;
	}
}