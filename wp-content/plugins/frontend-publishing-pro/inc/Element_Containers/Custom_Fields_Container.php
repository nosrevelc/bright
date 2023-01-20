<?php
namespace WPFEPP\Element_Containers;

if (!defined('WPINC')) die;

use WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Constants\Sanitizers;
use WPGurus\Forms\Constants\Validators;
use WPGurus\Forms\Element;
use WPGurus\Forms\Element_Container;
use WPGurus\Forms\Factories\Element_Factory;
use WPGurus\Forms\Factories\Sanitizer_Factory;
use WPGurus\Forms\Factories\Validator_Factory;

class Custom_Fields_Container extends Element_Container
{
	const ELEM_KEY_LABEL = 'label';
	const ELEM_KEY_META_KEY = 'meta_key';
	const ELEM_KEY_POST_TYPE = 'post_type';
	const ELEM_KEY_ACTION = 'action';

	const AJAX_ACTION = 'wpfepp_create_custom_field';

	const TEMPL_ARG_ELEMENTS = 'elements';

	private $container_template;
	private $element_template;
	private $post_type;

	public function __construct($post_type)
	{
		$this->post_type = $post_type;
		$this->container_template = WPFEPP_ELEMENT_CONTAINER_TEMPLATES_DIR . 'custom-fields.php';
		$this->element_template = WPFEPP_ELEMENT_TEMPLATES_DIR . 'custom-fields-form-element.php';

		$this->build_form_elements();
	}

	private function build_form_elements()
	{
		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY        => self::ELEM_KEY_LABEL,
					Element::LABEL      => __('Label', 'frontend-publishing-pro'),
					Element::TEMPLATE   => $this->element_template,
					Element::VALIDATORS => array(
						Validator_Factory::make_validator(
							Validators::REQUIRED,
							array(
								__('This field is required.', 'frontend-publishing-pro')
							)
						)
					),
					Element::SANITIZERS => array(
						Sanitizer_Factory::make_sanitizer(
							Sanitizers::STRIP_TAGS,
							array()
						)
					)
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY        => self::ELEM_KEY_META_KEY,
					Element::LABEL      => __('Meta Key', 'frontend-publishing-pro'),
					Element::TEMPLATE   => $this->element_template,
					Element::VALIDATORS => array(
						Validator_Factory::make_validator(
							Validators::REQUIRED,
							array(
								__('This field is required.', 'frontend-publishing-pro')
							)
						),
						Validator_Factory::make_validator(
							Validators::KEY,
							array(
								__('Please enter a valid key.', 'frontend-publishing-pro')
							)
						)
					),
					Element::SANITIZERS => array(
						Sanitizer_Factory::make_sanitizer(
							Sanitizers::STRIP_TAGS,
							array()
						)
					)
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::HIDDEN,
				array(
					Element::KEY   => self::ELEM_KEY_POST_TYPE,
					Element::VALUE => $this->post_type
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::HIDDEN,
				array(
					Element::KEY   => self::ELEM_KEY_ACTION,
					Element::VALUE => self::AJAX_ACTION
				)
			)
		);
	}

	public function render()
	{
		$this->render_template(
			$this->container_template,
			array(
				self::TEMPL_ARG_ELEMENTS => $this->get_elements()
			)
		);
	}
}