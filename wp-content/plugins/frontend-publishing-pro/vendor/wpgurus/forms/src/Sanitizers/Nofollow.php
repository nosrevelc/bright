<?php

namespace WPGurus\Forms\Sanitizers;

if (!defined('WPINC')) die;

/**
 * Nofollows links in a string.
 *
 * Class Nofollow
 * @package WPGurus\Forms\Sanitizers
 */
class Nofollow extends \WPGurus\Forms\Sanitizer
{
	/**
	 * Adds the nofollow attributes to all the <a href> links in the passed string.
	 *
	 * @see wp_rel_nofollow
	 * @param string $text The text in which links need to be nofollowed.
	 * @return string The resulting text.
	 */
	function sanitize( $text ) {
		return stripslashes(wp_rel_nofollow( $text ));
	}
}