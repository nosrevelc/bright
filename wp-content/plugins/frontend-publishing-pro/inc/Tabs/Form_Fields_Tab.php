<?php

namespace WPFEPP\Tabs;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Form_Meta_Keys;
use WPFEPP\Element_Containers\Sanitizers_Container;
use WPFEPP\Element_Containers\Validators_Container;
use WPFEPP\Forms\Custom_Fields_Form;
use WPFEPP\Forms\Form_Fields_Form;

class Form_Fields_Tab extends Form_Tab
{
	protected $post_type;

	const TEMPL_ARG_FORM = 'form';
	const TEMPL_ARG_CUSTOM_FIELDS_FORM = 'custom_fields_form';
	const TEMPL_ARG_VALIDATORS_CONTAINER = 'validators_container';
	const TEMPL_ARG_SANITIZERS_CONTAINER = 'sanitizers_container';

	function __construct($form_id, $post_type)
	{
		parent::__construct(
			'fields',
			__('Fields', 'frontend-publishing-pro'),
			$form_id
		);

		$this->post_type = $post_type;
	}

	function render()
	{
		$current_fields = $this->form_meta_table->get_meta_value($this->form_id, Form_Meta_Keys::FIELDS);

		$form = new Form_Fields_Form(
			$this->form_id,
			$this->post_type,
			$current_fields
		);

		$custom_fields_form = new Custom_Fields_Form($this->post_type);

		$template_args = array(
			self::TEMPL_ARG_FORM                 => $form,
			self::TEMPL_ARG_CUSTOM_FIELDS_FORM   => $custom_fields_form,
			self::TEMPL_ARG_VALIDATORS_CONTAINER => new Validators_Container(),
			self::TEMPL_ARG_SANITIZERS_CONTAINER => new Sanitizers_Container()
		);

		$this->render_template(
			WPFEPP_TAB_TEMPLATES_DIR . 'form-fields.php',
			$template_args
		);
	}
}