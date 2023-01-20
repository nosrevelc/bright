<?php
namespace WPGurus\Forms\Factories;

if (!defined('WPINC')) die;

use WPGurus\Forms\Constants\Sanitizers;
use WPGurus\Forms\Factory;

/**
 * Creates Sanitizer objects.
 *
 * Class Sanitizer_Factory
 * @package WPGurus\Forms\Factories
 */
class Sanitizer_Factory extends Factory
{
	/**
	 * Creates Sanitizer objects.
	 *
	 * @param $sanitizer_type string
	 * @param $args array
	 * @return null|\WPGurus\Forms\Sanitizer
	 */
	static function make_sanitizer($sanitizer_type, $args = array())
	{
		$sanitizer_class = '';
		$sanitizer = null;

		switch ($sanitizer_type) {
			case Sanitizers::NOFOLLOW:
				$sanitizer_class = '\WPGurus\Forms\Sanitizers\Nofollow';
				break;

			case Sanitizers::STRIP_TAGS:
				$sanitizer_class = '\WPGurus\Forms\Sanitizers\Strip_HTML';
				break;

			case Sanitizers::STRIP_SLASHES:
				$sanitizer_class = '\WPGurus\Forms\Sanitizers\Strip_Slashes';
				break;

			case Sanitizers::TYPECAST:
				$sanitizer_class = '\WPGurus\Forms\Sanitizers\Typecast';
				break;
		}

		if ($sanitizer_class) {
			$sanitizer = self::create_instance($sanitizer_class, $args);
		}

		$sanitizer = self::filter_object('_manufactured_sanitizer', array($sanitizer, $sanitizer_type, $args), '\WPGurus\Forms\Sanitizer');

		return $sanitizer;
	}
}