<?php
namespace WPGurus\Forms\Constants;

if (!defined('WPINC')) die;

/**
 * Contains all the available validator types.
 *
 * Class Validators
 * @package WPGurus\Forms\Constants
 */
abstract class Validators extends \WPGurus\Forms\Enum
{
	const EMAIL_FORMAT = 'validator_email_format';
	const KEY = 'validator_key';
	const MAX_CHARACTERS = 'validator_max_characters';
	const MAX_COUNT = 'validator_max_count';
	const MAX_LINKS = 'validator_max_links';
	const MAX_WORDS = 'validator_max_words';
	const MIN_CHARACTERS = 'validator_min_characters';
	const MIN_COUNT = 'validator_min_count';
	const MIN_WORDS = 'validator_min_words';
	const NONCE = 'validator_nonce';
	const REGEX = 'validator_regex';
	const REQUIRED = 'validator_required';
	const URL_FORMAT = 'validator_url_format';
	const VALUE = 'validator_value';
}