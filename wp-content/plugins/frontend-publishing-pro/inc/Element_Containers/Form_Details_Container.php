<?php
namespace WPFEPP\Element_Containers;

if (!defined('WPINC')) die;

use WPFEPP\DB_Tables\Forms;
use WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Constants\Sanitizers;
use WPGurus\Forms\Constants\Validators;
use WPGurus\Forms\Element;
use WPGurus\Forms\Elements\Select;
use WPGurus\Forms\Factories\Element_Factory;
use WPGurus\Forms\Factories\Sanitizer_Factory;
use WPGurus\Forms\Factories\Validator_Factory;

/**
 * Holds elements for the form that can be used to create new database items.
 *
 * Class Form_Details_Container
 * @package WPFEPP\Element_Containers
 */
class Form_Details_Container extends \WPGurus\Forms\Element_Container
{
	const ELEM_KEY_ACTION = 'action';
	const ELEM_KEY_PAGE = 'page';
	const AJAX_ACTION = 'wpfepp_create_form';
	const TEMPL_ARG_ELEMENTS = 'elements';

	private $container_template;
	private $element_template;
	private $page;

	public function __construct($page)
	{
		$this->page = $page;
		$this->container_template = WPFEPP_ELEMENT_CONTAINER_TEMPLATES_DIR . 'form-details.php';
		$this->element_template = WPFEPP_ELEMENT_TEMPLATES_DIR . 'form-details-element.php';

		$this->build_form_elements();
	}

	private function build_form_elements()
	{
		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY        => Forms::COLUMN_NAME,
					Element::LABEL      => __('Form Name', 'frontend-publishing-pro'),
					Element::POSTFIX    => __('The name of this form', 'frontend-publishing-pro'),
					Element::TEMPLATE   => $this->element_template,
					Element::VALIDATORS => array(
						Validator_Factory::make_validator(
							Validators::REQUIRED,
							array(
								__('This field is required.', 'frontend-publishing-pro')
							)
						)
					)
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXTAREA,
				array(
					Element::KEY        => Forms::COLUMN_DESCRIPTION,
					Element::LABEL      => __('Description', 'frontend-publishing-pro'),
					Element::POSTFIX    => __('A tiny description explaining what this form does', 'frontend-publishing-pro'),
					Element::TEMPLATE   => $this->element_template,
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
				Elements::SELECT,
				array(
					Element::KEY      => Forms::COLUMN_POST_TYPE,
					Element::LABEL    => __('Post Type', 'frontend-publishing-pro'),
					Element::POSTFIX  => __('The post type of this form', 'frontend-publishing-pro'),
					Select::CHOICES   => \WPFEPP\get_post_types(),
					Element::TEMPLATE => $this->element_template
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::HIDDEN,
				array(
					Element::KEY   => self::ELEM_KEY_PAGE,
					Element::VALUE => $this->page
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