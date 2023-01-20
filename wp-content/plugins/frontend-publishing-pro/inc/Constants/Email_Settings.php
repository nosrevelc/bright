<?php
namespace WPFEPP\Constants;

if (!defined('WPINC')) die;

abstract class Email_Settings
{
	const SETTING_USER_EMAILS = 'user_emails';
	const SETTING_ADMIN_EMAILS = 'admin_emails';
	const SETTING_SENDER_NAME = 'sender_name';
	const SETTING_SENDER_ADDRESS = 'sender_address';
	const SETTING_EMAIL_FORMAT = 'email_format';
	const SETTING_USER_EMAIL_SUBJECT = 'user_email_subject';
	const SETTING_USER_EMAIL_CONTENT = 'user_email_content';
	const SETTING_ADMIN_EMAIL_SUBJECT = 'admin_email_subject';
	const SETTING_ADMIN_EMAIL_CONTENT = 'admin_email_content';
}