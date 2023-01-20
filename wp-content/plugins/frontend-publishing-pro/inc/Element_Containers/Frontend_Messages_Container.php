<?php
namespace WPFEPP\Element_Containers;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Frontend_Form_Messages as Messages;
use WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Element;
use WPGurus\Forms\Factories\Element_Factory;
use WPGurus\Forms\Sanitizers\Strip_HTML;

class Frontend_Messages_Container extends Backend_Element_Container
{
	public function __construct($current_values = array())
	{
		parent::__construct($current_values);

		$this->build_form_elements();
	}

	private function build_form_elements()
	{
		$commons = array(
			Element::SANITIZERS => array(
				new Strip_HTML(Strip_HTML::UNSAFE)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array_merge(
					array(
						Element::KEY     => Messages::SETTING_PUBLISHED_MESSAGE,
						Element::LABEL   => __('Post Published Message', 'frontend-publishing-pro'),
						Element::POSTFIX => __('The success message shown when a post is published successfully.', 'frontend-publishing-pro'),
						Element::VALUE   => __('The post has been published successfully.', 'frontend-publishing-pro')
					),
					$commons
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array_merge(
					array(
						Element::KEY     => Messages::SETTING_SUBMITTED_MESSAGE,
						Element::LABEL   => __('Post Submitted Message', 'frontend-publishing-pro'),
						Element::POSTFIX => __('The success message shown when a post is submitted successfully for review.', 'frontend-publishing-pro'),
						Element::VALUE   => __('The post has been submitted. It will be reviewed by an editor soon.', 'frontend-publishing-pro')
					),
					$commons
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array_merge(
					array(
						Element::KEY     => Messages::SETTING_FORM_ERROR,
						Element::LABEL   => __('Form Error', 'frontend-publishing-pro'),
						Element::POSTFIX => __('The main error shown when the user hits the submission button.', 'frontend-publishing-pro'),
						Element::VALUE   => __('There are errors in your submission. Please try again.', 'frontend-publishing-pro')
					),
					$commons
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array_merge(
					array(
						Element::KEY     => Messages::SETTING_REQUIRED_ERROR,
						Element::LABEL   => __('Required Field Error', 'frontend-publishing-pro'),
						Element::POSTFIX => __('The error shown when the user misses a required field.', 'frontend-publishing-pro'),
						Element::VALUE   => __('This field is required.', 'frontend-publishing-pro')
					),
					$commons
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array_merge(
					array(
						Element::KEY     => Messages::SETTING_MIN_WORDS_ERROR,
						Element::LABEL   => __('Minimum Words Error', 'frontend-publishing-pro'),
						Element::POSTFIX => __('The error shown when the submission does not meet the minimum words requirement.', 'frontend-publishing-pro'),
						Element::VALUE   => __('Please enter at least %s words.', 'frontend-publishing-pro')
					),
					$commons
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array_merge(
					array(
						Element::KEY     => Messages::SETTING_MAX_WORDS_ERROR,
						Element::LABEL   => __('Maximum Words Error', 'frontend-publishing-pro'),
						Element::POSTFIX => __('The error shown when the submission does not meet the maximum words requirement.', 'frontend-publishing-pro'),
						Element::VALUE   => __('You cannot enter more than %s words.', 'frontend-publishing-pro')
					),
					$commons
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array_merge(
					array(
						Element::KEY     => Messages::SETTING_MIN_CHARS_ERROR,
						Element::LABEL   => __('Minimum Characters Error', 'frontend-publishing-pro'),
						Element::POSTFIX => __('The error shown when the submission does not meet the minimum characters requirement.', 'frontend-publishing-pro'),
						Element::VALUE   => __('Please enter at least %s characters.', 'frontend-publishing-pro')
					),
					$commons
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array_merge(
					array(
						Element::KEY     => Messages::SETTING_MAX_CHARS_ERROR,
						Element::LABEL   => __('Maximum Characters Error', 'frontend-publishing-pro'),
						Element::POSTFIX => __('The error shown when the submission does not meet the maximum characters requirement.', 'frontend-publishing-pro'),
						Element::VALUE   => __('You cannot enter more than %s characters.', 'frontend-publishing-pro')
					),
					$commons
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array_merge(
					array(
						Element::KEY     => Messages::SETTING_MAX_LINKS_ERROR,
						Element::LABEL   => __('Maximum Links Error', 'frontend-publishing-pro'),
						Element::POSTFIX => __('The error shown when the submission does not meet the maximum links requirement (used for non-hierarichal taxonomies like tags).', 'frontend-publishing-pro'),
						Element::VALUE   => __('You cannot enter more than %s links.', 'frontend-publishing-pro')
					),
					$commons
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array_merge(
					array(
						Element::KEY     => Messages::SETTING_MIN_COUNT_ERROR,
						Element::LABEL   => __('Minimum Segments Error', 'frontend-publishing-pro'),
						Element::POSTFIX => __('The error shown when the submission does not meet the minimum segments requirement (used for non-hierarichal taxonomies like tags).', 'frontend-publishing-pro'),
						Element::VALUE   => __('Please enter at least %s.', 'frontend-publishing-pro')
					),
					$commons
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array_merge(
					array(
						Element::KEY     => Messages::SETTING_MAX_COUNT_ERROR,
						Element::LABEL   => __('Maximum Segments Error', 'frontend-publishing-pro'),
						Element::POSTFIX => __('The error shown when the submission does not meet the maximum segments requirement (used for non-hierarichal taxonomies like tags).', 'frontend-publishing-pro'),
						Element::VALUE   => __('You cannot enter more than %s.', 'frontend-publishing-pro')
					),
					$commons
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array_merge(
					array(
						Element::KEY     => Messages::SETTING_INVALID_EMAIL_ERROR,
						Element::LABEL   => __('Invalid Email Error', 'frontend-publishing-pro'),
						Element::POSTFIX => __('The error shown when the user enters an invalid email.', 'frontend-publishing-pro'),
						Element::VALUE   => __('Please enter a valid email address.', 'frontend-publishing-pro')
					),
					$commons
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array_merge(
					array(
						Element::KEY     => Messages::SETTING_INVALID_URL_ERROR,
						Element::LABEL   => __('Invalid URL Error', 'frontend-publishing-pro'),
						Element::POSTFIX => __('The error shown when the user enters an invalid URL.', 'frontend-publishing-pro'),
						Element::VALUE   => __('Please enter a valid URL.', 'frontend-publishing-pro')
					),
					$commons
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array_merge(
					array(
						Element::KEY     => Messages::SETTING_REGEX_ERROR,
						Element::LABEL   => __('Regex Error', 'frontend-publishing-pro'),
						Element::POSTFIX => __('The error shown when the value entered by the user does not match the regex pattern.', 'frontend-publishing-pro'),
						Element::VALUE   => __('The value you have entered is invalid.', 'frontend-publishing-pro')
					),
					$commons
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array_merge(
					array(
						Element::KEY     => Messages::SETTING_VALUE_ERROR,
						Element::LABEL   => __('Value Error', 'frontend-publishing-pro'),
						Element::POSTFIX => __('The error shown when the value entered by the user does not match the required string.', 'frontend-publishing-pro'),
						Element::VALUE   => __('This is not the required value.', 'frontend-publishing-pro')
					),
					$commons
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array_merge(
					array(
						Element::KEY     => Messages::SETTING_RECAPTCHA_ERROR,
						Element::LABEL   => __('reCaptcha Error', 'frontend-publishing-pro'),
						Element::POSTFIX => __('The error shown when the captcha response is incorrect.', 'frontend-publishing-pro'),
						Element::VALUE   => __('Captcha response incorrect.', 'frontend-publishing-pro')
					),
					$commons
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array_merge(
					array(
						Element::KEY     => Messages::SETTING_FILE_TOO_LARGE,
						Element::LABEL   => __('File Too Large Error', 'frontend-publishing-pro'),
						Element::POSTFIX => __('The error shown when the uploaded file is too large.', 'frontend-publishing-pro'),
						Element::VALUE   => __('The file you are attempting to upload is too large. The max file size is %s KBs.', 'frontend-publishing-pro')
					),
					$commons
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array_merge(
					array(
						Element::KEY     => Messages::SETTING_LOGIN_REQUIRED_ERROR,
						Element::LABEL   => __('Login Required Error', 'frontend-publishing-pro'),
						Element::POSTFIX => __('The error shown when the user is not logged in but tries to access something restricted.', 'frontend-publishing-pro'),
						Element::VALUE   => __('You need to login first.', 'frontend-publishing-pro')
					),
					$commons
				)
			)
		);
	}
}