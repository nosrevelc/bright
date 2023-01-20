<?php
namespace WPFEPP\Element_Containers;

if (!defined('WPINC')) die;

use WPFEPP\Constants\reCaptcha_Settings;
use WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Element;
use WPGurus\Forms\Elements\Select;
use WPGurus\Forms\Factories\Element_Factory;

class reCaptcha_Settings_Container extends Backend_Element_Container
{
	public function __construct($current_values = array())
	{
		parent::__construct($current_values);

		$this->build_form_elements();
	}

	private function build_form_elements()
	{
		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY     => reCaptcha_Settings::SETTING_SITE_KEY,
					Element::LABEL   => __('Site Key', 'frontend-publishing-pro'),
					Element::POSTFIX => sprintf(
						__('Your ReCaptcha site key. You can get this key %s.', 'frontend-publishing-pro'),
						sprintf(
							'<a href="http://www.google.com/recaptcha/admin">%s</a>',
							__('here', 'frontend-publishing-pro')
						)
					)
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY     => reCaptcha_Settings::SETTING_SECRET,
					Element::LABEL   => __('Secret', 'frontend-publishing-pro'),
					Element::POSTFIX => sprintf(
						__('Your ReCaptcha secret. You can get it %s.', 'frontend-publishing-pro'),
						sprintf(
							'<a href="http://www.google.com/recaptcha/admin">%s</a>', 'here'
						)
					)
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::SELECT,
				array(
					Element::KEY     => reCaptcha_Settings::SETTING_THEME,
					Element::LABEL   => __('Theme', 'frontend-publishing-pro'),
					Element::POSTFIX => __('Color scheme of the ReCaptcha widget.', 'frontend-publishing-pro'),
					Element::VALUE   => 'light',
					Select::CHOICES  => array('light' => __('Light', 'frontend-publishing-pro'), 'dark' => __('Dark', 'frontend-publishing-pro'))
				)
			)
		);
	}
}