<?php
namespace WPGurus\Forms\Validators;

if (!defined('WPINC')) die;

use WPGurus\Forms\Validator;

class Google_reCaptcha extends Validator
{
	private $site_secret;

	function __construct($site_secret, $message = '')
	{
		parent::__construct($message);

		$this->site_secret = $site_secret;
	}

	function is_valid($value)
	{
		$url 		= 'https://www.google.com/recaptcha/api/siteverify?secret=' . $this->site_secret . '&response=' . $value . '&remoteip=' . $this->get_user_ip();
		$response 	= wp_remote_get( $url, array('timeout' => 10) );
		$body 		= wp_remote_retrieve_body($response);
		$body 		= json_decode($body);
		return $body->success;
	}

	private function get_user_ip(){
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			//check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}