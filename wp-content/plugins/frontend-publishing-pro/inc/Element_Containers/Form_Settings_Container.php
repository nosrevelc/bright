<?php
namespace WPFEPP\Element_Containers;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Form_Settings;
use WPFEPP\Constants\Redirection_Types;
use WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Element;
use WPGurus\Forms\Elements\Select;
use WPGurus\Forms\Factories\Element_Factory;

class Form_Settings_Container extends Backend_Element_Container
{
	private $conditional_element_template;

	public function __construct($current_values = array())
	{
		parent::__construct($current_values);

		$this->conditional_element_template = WPFEPP_ELEMENT_TEMPLATES_DIR . 'conditional-table-element.php';

		$this->build_form_elements();
	}

	private function build_form_elements()
	{
		$roles = \WPFEPP\get_roles();

		$this->add_element(
			Element_Factory::make_element(
				Elements::SELECT,
				array(
					Element::KEY     => array(Form_Settings::SETTING_NO_RESTRICTIONS, ''),
					Element::LABEL   => __('Disable restrictions for', 'frontend-publishing-pro'),
					Element::POSTFIX => __('These roles will have unrestricted access to the form.', 'frontend-publishing-pro'),
					Select::CHOICES  => $roles,
					Select::MULTIPLE => true,
					Element::VALUE   => array('administrator')
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::SELECT,
				array(
					Element::KEY     => array(Form_Settings::SETTING_INSTANTLY_PUBLISH, ''),
					Element::LABEL   => __('Instantly publish posts by', 'frontend-publishing-pro'),
					Element::POSTFIX => __('The post submitted by these roles will be published instantly.', 'frontend-publishing-pro'),
					Select::CHOICES  => $roles,
					Select::MULTIPLE => true,
					Element::VALUE   => array('administrator')
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY     => Form_Settings::SETTING_WIDTH,
					Element::LABEL   => __('Width', 'frontend-publishing-pro'),
					Element::POSTFIX => __('Maximum form width.', 'frontend-publishing-pro')
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::SELECT,
				array(
					Element::KEY     => Form_Settings::SETTING_REDIRECTION_TYPE,
					Element::LABEL   => __('Redirection Type', 'frontend-publishing-pro'),
					Element::POSTFIX => __('Where would you like the user to be redirected after submission.', 'frontend-publishing-pro'),
					Element::VALUE   => Redirection_Types::NONE,
					Select::CHOICES  => array(
						Redirection_Types::NONE       => __('Disabled', 'frontend-publishing-pro'),
						Redirection_Types::CUSTOM_URL => __('Custom URL', 'frontend-publishing-pro'),
						Redirection_Types::POST_URL   => __('Post URL', 'frontend-publishing-pro')
					)
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY           => Form_Settings::SETTING_REDIRECT_URL,
					Element::LABEL         => __('Custom Redirect URL', 'frontend-publishing-pro'),
					Element::POSTFIX       => __('The user will be redirected to this URL after successful submission. Leave this empty to disable redirection.', 'frontend-publishing-pro'),
					Element::TEMPLATE      => $this->conditional_element_template,
					Element::TEMPLATE_ARGS => array(
						'cond_option' => Form_Settings::SETTING_REDIRECTION_TYPE,
						'cond_value'  => Redirection_Types::CUSTOM_URL
					)
				)
			),
			null,
			false
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::CHECKBOX,
				array(
					Element::KEY   => Form_Settings::SETTING_ENABLE_DRAFTS,
					Element::LABEL => __('Allow users to save drafts', 'frontend-publishing-pro'),
					Element::VALUE => true
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::CHECKBOX,
				array(
					Element::KEY     => Form_Settings::SETTING_CAPTCHA_ENABLED,
					Element::LABEL   => __('Enable Captcha', 'frontend-publishing-pro'),
					Element::POSTFIX => __('Enable captcha for this form? In order to use this feature you need to add your ReCaptcha keys in the plugin settings.', 'frontend-publishing-pro'),
					Element::VALUE   => false
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::CHECKBOX,
				array(
					Element::KEY     => Form_Settings::SETTING_ADVANCED_VALIDATION,
					Element::LABEL   => __('Advanced Validation', 'frontend-publishing-pro'),
					Element::POSTFIX => __('If this option is enabled, the values entered by the user will be validated continuously while the form is being filled in.', 'frontend-publishing-pro'),
					Element::VALUE   => true
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::CHECKBOX,
				array(
					Element::KEY     => Form_Settings::SETTING_TOOLTIPS,
					Element::LABEL   => __('Show Error Tooltips', 'frontend-publishing-pro'),
					Element::POSTFIX => __('If this option is enabled, the form errors will be displayed as tooltips. Otherwise, a simple error message will be displayed above the field.', 'frontend-publishing-pro'),
					Element::VALUE   => true
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::CHECKBOX,
				array(
					Element::KEY     => Form_Settings::SETTING_REQUIRE_LOGIN,
					Element::LABEL   => __('Require Login', 'frontend-publishing-pro'),
					Element::POSTFIX => __('If this option is enabled, the form will only be accessible to logged-in users.', 'frontend-publishing-pro'),
					Element::VALUE   => true
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::CHECKBOX,
				array(
					Element::KEY     => Form_Settings::SETTING_REDIRECT_TO_LOGIN,
					Element::LABEL   => __('Redirect to Login Page', 'frontend-publishing-pro'),
					Element::POSTFIX => __('If this option is enabled, the user will be redirected to the login page automatically. Otherwise, an error will be shown.', 'frontend-publishing-pro'),
					Element::VALUE   => false
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY     => Form_Settings::SETTING_ANONYMOUS_POST_AUTHOR,
					Element::LABEL   => __('Assign Anonymously Submitted Posts to', 'frontend-publishing-pro'),
					Element::POSTFIX => __('Enter the ID of the user that you would like anonymously submitted posts assigned to.', 'frontend-publishing-pro'),
					Element::VALUE   => 1
				)
			)
		);
		
		$this->add_element(
			Element_Factory::make_element(
				Elements::CHECKBOX,
				array(
					Element::KEY     => Form_Settings::SETTING_AUTOSAVE_POSTS,
					Element::LABEL   => __('Auto Save Posts', 'frontend-publishing-pro'),
					Element::POSTFIX => __('If this option is enabled, the post will be autosaved. In order for this to work, the draft feature must be enabled.', 'frontend-publishing-pro'),
					Element::VALUE   => true
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY     => Form_Settings::SETTING_AUTOSAVE_INTERVAL,
					Element::LABEL   => __('Auto Save Interval', 'frontend-publishing-pro'),
					Element::POSTFIX => __('After how many seconds do you want the post to be auto-saved?', 'frontend-publishing-pro'),
					Element::VALUE   => 30
				)
			)
		);
	}
}