<?php
namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

use WPGurus\Forms\Constants\Assets;
use WPGurus\Forms\Element;

class Google_reCaptcha extends Element
{
	/**
	 * Argument constants
	 */
	const SITE_KEY = 'recaptcha_site_key';
	const SITE_SECRET = 'recaptcha_site_secret';
	const THEME = 'recaptcha_theme';
	const ERROR_MESSAGE = 'recaptcha_error_message';

	/**
	 * Site key obtained from Google captcha website
	 * @var string
	 */
	private $site_key;

	/**
	 * Site secret obtained from Google captcha website
	 * @var string
	 */
	private $site_secret;

	/**
	 * Captcha theme.
	 * @var string
	 */
	private $theme;

	/**
	 * The error message to pass to the validator.
	 * @var string
	 */
	private $error_message;

	function __construct($args)
	{
		$args[ self::KEY ] = 'g-recaptcha-response';

		parent::__construct($args);

		$args = wp_parse_args(
			$args,
			array(
				self::SITE_KEY      => '',
				self::SITE_SECRET   => '',
				self::THEME         => '',
				self::ERROR_MESSAGE => ''
			)
		);

		$this->site_key = $args[ self::SITE_KEY ];
		$this->site_secret = $args[ self::SITE_SECRET ];
		$this->theme = $args[ self::THEME ];
		$this->error_message = $args[ self::ERROR_MESSAGE ];

		if ($this->keys_available()) {
			$this->add_validator(
				new \WPGurus\Forms\Validators\Google_reCaptcha(
					$this->site_secret,
					$this->error_message
				)
			);
		}
	}

	function render_field_html()
	{
		if (!$this->keys_available()) {
			echo __('reCaptcha keys missing.', 'frontend-publishing-pro');
			return;
		}

		$this->add_html_attributes();

		?>
		<div <?php $this->print_attributes(); ?>></div>
		<?php
		wp_enqueue_script(Assets::GOOGLE_RECAPTCHA);
	}

	/**
	 * @return bool
	 */
	private function keys_available()
	{
		return $this->site_key && $this->site_secret;
	}

	private function add_html_attributes()
	{
		$this->set_attribute('class', 'g-recaptcha');
		$this->set_attribute('data-sitekey', $this->site_key);
		$this->set_attribute('data-theme', $this->theme);

		$this->remove_attribute('name');
	}
}