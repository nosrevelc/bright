<?php
namespace WPGurus\Forms\Factories;

if (!defined('WPINC')) die;

use WPGurus\Forms\Constants\Validators;
use WPGurus\Forms\Factory;

/**
 * Creates Validator objects.
 *
 * Class Validator_Factory
 * @package WPGurus\Forms\Factories
 */
class Validator_Factory extends Factory
{
	/**
	 * Creates Validator objects.
	 *
	 * @param $validator_type string
	 * @param array $args array()
	 * @return null|\WPGurus\Forms\Validator
	 */
	static function make_validator($validator_type, $args = array())
	{
		$validator = null;
		$validator_class = '';

		switch ($validator_type) {
			case Validators::EMAIL_FORMAT:
				$validator_class = '\WPGurus\Forms\Validators\Email_Format';
				break;

			case Validators::KEY:
				$validator_class = '\WPGurus\Forms\Validators\Key';
				break;

			case Validators::MAX_CHARACTERS:
				$validator_class = '\WPGurus\Forms\Validators\Max_Characters';
				break;

			case Validators::MAX_COUNT:
				$validator_class = '\WPGurus\Forms\Validators\Max_Count';
				break;

			case Validators::MAX_LINKS:
				$validator_class = '\WPGurus\Forms\Validators\Max_Links';
				break;

			case Validators::MAX_WORDS:
				$validator_class = '\WPGurus\Forms\Validators\Max_Words';
				break;

			case Validators::MIN_CHARACTERS:
				$validator_class = '\WPGurus\Forms\Validators\Min_Characters';
				break;

			case Validators::MIN_COUNT:
				$validator_class = '\WPGurus\Forms\Validators\Min_Count';
				break;

			case Validators::MIN_WORDS:
				$validator_class = '\WPGurus\Forms\Validators\Min_Words';
				break;

			case Validators::NONCE:
				$validator_class = '\WPGurus\Forms\Validators\Nonce';
				break;

			case Validators::REGEX:
				$validator_class = '\WPGurus\Forms\Validators\Regex';
				break;

			case Validators::REQUIRED:
				$validator_class = '\WPGurus\Forms\Validators\Required';
				break;

			case Validators::URL_FORMAT:
				$validator_class = '\WPGurus\Forms\Validators\URL_Format';
				break;

			case Validators::VALUE:
				$validator_class = '\WPGurus\Forms\Validators\Value';
				break;
		}

		if ($validator_class) {
			$validator = self::create_instance($validator_class, $args);
		}

		$validator = self::filter_object('_manufactured_validator', array($validator, $validator_type, $args), '\WPGurus\Forms\Validator');

		return $validator;
	}
}