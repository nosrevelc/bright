<?php
namespace WPFEPP\Components;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Email_Placeholders;
use WPFEPP\Constants\Email_Settings;
use WPFEPP\Constants\Plugin_Actions;
use WPFEPP\Constants\Post_Fields;
use WPFEPP\Forms\Frontend_Form;
use WPGurus\Components\Component;

/**
 * Sends emails to the user and the site administrator on form submissions.
 * @package WPFEPP\Components
 */
class Email_Manager extends Component
{
	private $email_settings;

	/**
	 * Email_Manager constructor.
	 */
	function __construct()
	{
		parent::__construct();

		$this->register_action(Plugin_Actions::POST_SUBMITTED, array($this, 'send_emails'), 10, 2);
	}

	/**
	 * Sends emails.
	 * @param $post_data array The post data submitted by the user.
	 * @param $form Frontend_Form The form that handled the user's request.
	 */
	function send_emails($post_data, $form)
	{
		$this->email_settings = $form->get_form_emails();

		add_filter('wp_mail_from', array($this, 'from_email'), 9999);
		add_filter('wp_mail_from_name', array($this, 'from_name'), 9999);
		add_filter('wp_mail_content_type', array($this, 'set_content_type'), 9999);

		$author_id = get_post_field('post_author', $post_data['ID']);
		$author_email = get_the_author_meta('user_email', $author_id);
		$admin_email = get_bloginfo('admin_email');

		$author_email_sent = false;
		if ($this->email_settings[ Email_Settings::SETTING_USER_EMAILS ]) {
			$author_email_subject = $this->prepare($this->email_settings[ Email_Settings::SETTING_USER_EMAIL_SUBJECT ], $post_data);
			$author_email_content = $this->prepare($this->email_settings[ Email_Settings::SETTING_USER_EMAIL_CONTENT ], $post_data);
			wp_mail(
				$author_email,
				$author_email_subject,
				$author_email_content
			);
			$author_email_sent = true;
		}

		if ($this->email_settings[ Email_Settings::SETTING_ADMIN_EMAILS ] && !($author_email == $admin_email && $author_email_sent)) {
			$admin_email_subject = $this->prepare($this->email_settings[ Email_Settings::SETTING_ADMIN_EMAIL_SUBJECT ], $post_data);
			$admin_email_content = $this->prepare($this->email_settings[ Email_Settings::SETTING_ADMIN_EMAIL_CONTENT ], $post_data);
			wp_mail(
				$admin_email,
				$admin_email_subject,
				$admin_email_content
			);
		}

		remove_filter('wp_mail_from', array($this, 'from_email'), 9999);
		remove_filter('wp_mail_from_name', array($this, 'from_name'), 9999);
		remove_filter('wp_mail_content_type', array($this, 'set_content_type'), 9999);
	}

	/**
	 * Prepares a string by replacing all the placeholders with real values.
	 * @param $value string The value that needs to be prepared.
	 * @param $post_data array The user submitted data.
	 * @return string A string with all the placeholders replaced with real values.
	 */
	private function prepare($value, $post_data)
	{
		$admin_info = get_userdata(1);
		$author_id = get_post_field('post_author', $post_data[ Post_Fields::FIELD_POST_ID ]);
		$place_holders[ Email_Placeholders::PLACEHOLDER_POST_TITLE ] = wp_strip_all_tags($post_data[ Post_Fields::FIELD_TITLE ]);
		$place_holders[ Email_Placeholders::PLACEHOLDER_POST_PERMALINK ] = get_post_permalink($post_data[ Post_Fields::FIELD_POST_ID ]);
		$place_holders[ Email_Placeholders::PLACEHOLDER_AUTHOR_NAME ] = get_the_author_meta('display_name', $author_id);
		$place_holders[ Email_Placeholders::PLACEHOLDER_SITE_NAME ] = html_entity_decode(get_bloginfo('name'), ENT_QUOTES);
		$place_holders[ Email_Placeholders::PLACEHOLDER_SITE_URL ] = get_bloginfo('url');
		$place_holders[ Email_Placeholders::PLACEHOLDER_ADMIN_NAME ] = $admin_info->display_name;
		$place_holders[ Email_Placeholders::PLACEHOLDER_EDIT_LINK ] = sprintf(admin_url('edit.php?post_type=%s'), $post_data[ Post_Fields::FIELD_POST_TYPE ]);

		foreach ($place_holders as $placeholder => $val) {
			$value = str_replace($placeholder, $val, $value);
		}
		return $value;
	}

	/**
	 * Filters the sender email address.
	 * @param $email string The actual email address.
	 * @return string The email address from the email settings.
	 */
	public function from_email($email)
	{

		if ($this->email_settings[ Email_Settings::SETTING_SENDER_ADDRESS ] && is_email($this->email_settings[ Email_Settings::SETTING_SENDER_ADDRESS ]))
			return $this->email_settings[ Email_Settings::SETTING_SENDER_ADDRESS ];

		return $email;
	}

	/**
	 * Filters the sender name.
	 * @param $name string The actual sender name.
	 * @return string The sender name from the email settings.
	 */
	public function from_name($name)
	{

		if ($this->email_settings[ Email_Settings::SETTING_SENDER_NAME ])
			return $this->email_settings[ Email_Settings::SETTING_SENDER_NAME ];

		return $name;
	}

	/**
	 * Filters the email content type.
	 * @return string The email content type from the email settings.
	 */
	public function set_content_type()
	{
		return 'text/' . $this->email_settings[ Email_Settings::SETTING_EMAIL_FORMAT ];
	}
}