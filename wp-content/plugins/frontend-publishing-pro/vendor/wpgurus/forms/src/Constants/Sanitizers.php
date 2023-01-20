<?php
namespace WPGurus\Forms\Constants;

if (!defined('WPINC')) die;

/**
 * Contains all the available sanitizer types.
 *
 * Class Sanitizers
 * @package WPGurus\Forms\Constants
 */
abstract class Sanitizers extends \WPGurus\Forms\Enum
{
	const STRIP_TAGS = 'sanitizer_strip_tags';
	const NOFOLLOW = 'sanitizer_nofollow';
	const STRIP_SLASHES = 'sanitizer_strip_slashes';
	const TYPECAST = 'sanitizer_typecast';
}