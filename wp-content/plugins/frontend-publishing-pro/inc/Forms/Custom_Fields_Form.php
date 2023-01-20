<?php

namespace WPFEPP\Forms;

if (!defined('WPINC')) die;

use WPFEPP\Element_Containers\Custom_Fields_Container;

class Custom_Fields_Form extends \WPGurus\Forms\Form
{
	private $custom_field_html;
	private $post_type;

	function __construct($post_type)
	{
		parent::__construct(
			'custom-fields-form',
			null,
			true,
			true,
			array(
				'class' => 'form-manager-ajax-form custom-fields-form'
			)
		);

		$this->post_type = $post_type;

		$this->set_element_container(
			new Custom_Fields_Container(
				$this->post_type
			)
		);
	}

	function get_custom_field_html()
	{
		return $this->custom_field_html;
	}

	function process_data($cleaned_data)
	{
		$fields = new \WPFEPP\Element_Containers\Form_Fields_Container(
			$cleaned_data[ Custom_Fields_Container::ELEM_KEY_POST_TYPE ]
		);

		$fields->get_custom_field_elements(
			$cleaned_data[ Custom_Fields_Container::ELEM_KEY_META_KEY ],
			$cleaned_data[ Custom_Fields_Container::ELEM_KEY_LABEL ]
		);

		ob_start();
		$this->render_template(
			WPFEPP_ELEMENT_CONTAINER_TEMPLATES_DIR . 'form-field-single.php',
			$fields->get_field_template_args($cleaned_data[ Custom_Fields_Container::ELEM_KEY_META_KEY ])
		);
		$this->custom_field_html = ob_get_clean();

		return true;
	}
}