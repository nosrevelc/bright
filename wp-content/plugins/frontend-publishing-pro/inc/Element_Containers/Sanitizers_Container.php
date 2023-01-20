<?php
namespace WPFEPP\Element_Containers;

if (!defined('WPINC')) die;

use WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Constants\Sanitizers;
use WPGurus\Forms\Element;
use WPGurus\Forms\Factories\Element_Factory;

class Sanitizers_Container extends Field_Addons_Container
{
	const TYPE = 'sanitizer';

	function __construct()
	{
		$this->build_elements();
	}

	private function build_elements()
	{
		$template_args = array('type' => self::TYPE);

		$this->add_element(
			Element_Factory::make_element(
				Elements::SELECT,
				array(
					Element::KEY                            => Sanitizers::STRIP_TAGS,
					Element::LABEL                          => __('Strip Tags', 'frontend-publishing-pro'),
					Element::TEMPLATE_ARGS                  => $template_args,
					\WPGurus\Forms\Elements\Select::CHOICES => array(
						'unsafe' => __('Unsafe', 'frontend-publishing-pro'),
						'all'    => __('All', 'frontend-publishing-pro')
					)
				)
			),
			Sanitizers::STRIP_TAGS
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::CHECKBOX,
				array(
					Element::KEY           => Sanitizers::NOFOLLOW,
					Element::LABEL         => __('Nofollow All Links', 'frontend-publishing-pro'),
					Element::TEMPLATE_ARGS => $template_args,
					Element::VALUE         => true
				)
			),
			Sanitizers::NOFOLLOW
		);
	}
}