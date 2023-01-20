<?php
namespace WPFEPP\Element_Containers;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Email_Placeholders;
use WPFEPP\Constants\Email_Settings;
use WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Element;
use WPGurus\Forms\Elements\Select;
use WPGurus\Forms\Factories\Element_Factory;

class Email_Settings_Container extends Backend_Element_Container
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
				Elements::CHECKBOX,
				array(
					Element::KEY     => Email_Settings::SETTING_USER_EMAILS,
					Element::LABEL   => __('User Emails', 'frontend-publishing-pro'),
					Element::POSTFIX => __('Send thank you email to user on post submission?', 'frontend-publishing-pro'),
					Element::VALUE   => true
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::CHECKBOX,
				array(
					Element::KEY     => Email_Settings::SETTING_ADMIN_EMAILS,
					Element::LABEL   => __('Admin Emails', 'frontend-publishing-pro'),
					Element::POSTFIX => __('Send email to admin on post submission?', 'frontend-publishing-pro'),
					Element::VALUE   => true
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY        => Email_Settings::SETTING_SENDER_NAME,
					Element::LABEL      => __('Sender Name', 'frontend-publishing-pro'),
					Element::POSTFIX    => __('This name will be used as the sender name in notification emails.', 'frontend-publishing-pro'),
					Element::SANITIZERS => array(
						new \WPGurus\Forms\Sanitizers\Strip_HTML()
					)
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY        => Email_Settings::SETTING_SENDER_ADDRESS,
					Element::LABEL      => __('Sender Address', 'frontend-publishing-pro'),
					Element::POSTFIX    => __('Emails will be sent from this address.', 'frontend-publishing-pro'),
					Element::SANITIZERS => array(
						new \WPGurus\Forms\Sanitizers\Strip_HTML()
					)
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::SELECT,
				array(
					Element::KEY     => Email_Settings::SETTING_EMAIL_FORMAT,
					Element::LABEL   => __('Email Format', 'frontend-publishing-pro'),
					Element::POSTFIX => __('In which format would you like to send the emails.', 'frontend-publishing-pro'),
					Element::VALUE   => 'plain',
					Select::CHOICES  => array(
						'plain' => __('Plain Text', 'frontend-publishing-pro'),
						'html'  => __('HTML', 'frontend-publishing-pro')
					)
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY   => Email_Settings::SETTING_USER_EMAIL_SUBJECT,
					Element::LABEL => __('User Email Subject', 'frontend-publishing-pro'),
					Element::VALUE => __('Thank you for your contribution', 'frontend-publishing-pro')
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXTAREA,
				array(
					Element::KEY        => Email_Settings::SETTING_USER_EMAIL_CONTENT,
					Element::LABEL      => __('User Email Content', 'frontend-publishing-pro'),
					Element::VALUE      => sprintf(__("Hi %s,\n\nThank you for submitting the article '%s' at our website %s. It has been added to the queue and will be published shortly.\n\nRegards,\n%s\n%s", 'frontend-publishing-pro'), "%%AUTHOR_NAME%%", "%%POST_TITLE%%", "%%SITE_NAME%%", "%%ADMIN_NAME%%", "%%SITE_URL%%"),
					Element::ATTRIBUTES => array(
						Element::HTML_ATTR_CLASS => 'large-textarea'
					)
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY   => Email_Settings::SETTING_ADMIN_EMAIL_SUBJECT,
					Element::LABEL => __('Admin Email Subject', 'frontend-publishing-pro'),
					Element::VALUE => sprintf(__("A new article has been submitted on your website %s", 'frontend-publishing-pro'), "%%SITE_NAME%%")
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXTAREA,
				array(
					Element::KEY        => Email_Settings::SETTING_ADMIN_EMAIL_CONTENT,
					Element::LABEL      => __('Admin Email Content', 'frontend-publishing-pro'),
					Element::VALUE      => sprintf(__("Hi %s,\n\nA new article has been added to your website. You can view and edit all your articles here:\n\n%s\n\nRegards,\nYour web server", 'frontend-publishing-pro'), "%%ADMIN_NAME%%", "%%EDIT_LINK%%"),
					Element::ATTRIBUTES => array(
						Element::HTML_ATTR_CLASS => 'large-textarea'
					)
				)
			)
		);
	}

	function render()
	{
		$placeholder_string = $this->make_placeholder_string();
		?>
		<p class="tab-description"><?php echo __('You can use the following placeholders in email subject and content: ', 'frontend-publishing-pro') . $placeholder_string; ?></p>
		<?php

		parent::render();
	}

	/**
	 * @return string
	 */
	private function make_placeholder_string()
	{
		$placeholders = array();
		foreach (Email_Placeholders::values() as $placeholder) {
			$placeholders[] = sprintf('<span class="highlighted-text">%s</span>', $placeholder);
		}
		$placeholder_string = implode(' ', $placeholders);
		return $placeholder_string;
	}
}