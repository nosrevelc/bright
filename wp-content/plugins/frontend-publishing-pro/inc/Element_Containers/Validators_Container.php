<?php
namespace WPFEPP\Element_Containers;

if (!defined('WPINC')) die;

use WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Constants\Validators;
use WPGurus\Forms\Element;
use WPGurus\Forms\Factories\Element_Factory;

class Validators_Container extends Field_Addons_Container
{
	const TYPE = 'validator';

	function __construct()
	{
		$this->build_elements();
	}

	function build_elements()
	{
		$template_args = array('type' => self::TYPE);

		$this->add_element(
			Element_Factory::make_element(
				Elements::CHECKBOX,
				array(
					Element::KEY           => Validators::REQUIRED,
					Element::LABEL         => __('Required', 'frontend-publishing-pro'),
					Element::TEMPLATE_ARGS => $template_args,
					Element::VALUE         => true
				)
			),
			Validators::REQUIRED
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY           => Validators::MIN_WORDS,
					Element::LABEL         => __('Minimum Words', 'frontend-publishing-pro'),
					Element::TEMPLATE_ARGS => $template_args
				)
			),
			Validators::MIN_WORDS
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY           => Validators::MAX_WORDS,
					Element::LABEL         => __('Maximum Words', 'frontend-publishing-pro'),
					Element::TEMPLATE_ARGS => $template_args
				)
			),
			Validators::MAX_WORDS
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY           => Validators::MIN_CHARACTERS,
					Element::LABEL         => __('Minimum Characters', 'frontend-publishing-pro'),
					Element::TEMPLATE_ARGS => $template_args
				)
			),
			Validators::MIN_CHARACTERS
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY           => Validators::MAX_CHARACTERS,
					Element::LABEL         => __('Maximum Characters', 'frontend-publishing-pro'),
					Element::TEMPLATE_ARGS => $template_args
				)
			),
			Validators::MAX_CHARACTERS
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY           => Validators::MIN_COUNT,
					Element::LABEL         => __('Minimum Count', 'frontend-publishing-pro'),
					Element::TEMPLATE_ARGS => $template_args
				)
			),
			Validators::MIN_COUNT
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY           => Validators::MAX_COUNT,
					Element::LABEL         => __('Maximum Count', 'frontend-publishing-pro'),
					Element::TEMPLATE_ARGS => $template_args
				)
			),
			Validators::MAX_COUNT
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY           => Validators::MAX_LINKS,
					Element::LABEL         => __('Maximum Links', 'frontend-publishing-pro'),
					Element::TEMPLATE_ARGS => $template_args
				)
			),
			Validators::MAX_LINKS
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::CHECKBOX,
				array(
					Element::KEY           => Validators::EMAIL_FORMAT,
					Element::LABEL         => __('Email', 'frontend-publishing-pro'),
					Element::TEMPLATE_ARGS => $template_args,
					Element::VALUE         => true
				)
			),
			Validators::EMAIL_FORMAT
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::CHECKBOX,
				array(
					Element::KEY           => Validators::URL_FORMAT,
					Element::LABEL         => __('URL', 'frontend-publishing-pro'),
					Element::TEMPLATE_ARGS => $template_args,
					Element::VALUE         => true
				)
			),
			Validators::URL_FORMAT
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY           => Validators::REGEX,
					Element::LABEL         => __('Regex', 'frontend-publishing-pro'),
					Element::TEMPLATE_ARGS => $template_args
				)
			),
			Validators::REGEX
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY           => Validators::VALUE,
					Element::LABEL         => __('Value', 'frontend-publishing-pro'),
					Element::TEMPLATE_ARGS => $template_args
				)
			),
			Validators::VALUE
		);
	}
}