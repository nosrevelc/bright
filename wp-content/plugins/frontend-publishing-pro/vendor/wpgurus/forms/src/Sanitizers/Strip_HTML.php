<?php

namespace WPGurus\Forms\Sanitizers;

if (!defined('WPINC')) die;

use WPGurus\Config\Config_Loader;

/**
 * Removes HTML and JS tags from a string. It can either remove everything or only the tags that it deems unsafe.
 *
 * Class Strip_HTML
 * @package WPGurus\Forms\Sanitizers
 */
class Strip_HTML extends \WPGurus\Forms\Sanitizer
{
	/**
	 * The following constants refer to the two modes in which this sanitizer can be used.
	 */
	const ALL = 'all';
	const UNSAFE = 'unsafe';

	/**
	 * The mode in which the sanitizer can run. Its two possible values are available as class constants.
	 *
	 * @var string
	 */
	private $mode;

	function __construct($mode = self::ALL)
	{
		$this->mode = $mode;
	}

	/**
	 * Removes HTML tags from the passed string.
	 *
	 * @param $text string The string that needs to be cleansed.
	 * @return string The cleansed string.
	 */
	function sanitize($text)
	{
		switch ($this->mode) {
			case self::ALL:
				$text = wp_strip_all_tags($text);
				break;

			case self::UNSAFE:
				$text = wp_kses($text, $this->get_whitelist());
				break;

			default:
				break;
		}
		return $text;
	}

	/**
	 * Returns an array of allowed tags and attributes. The array can be filtered by other plugins.
	 *
	 * @return array|mixed|void The allowed tags and attributes.
	 */
	function get_whitelist()
	{
		$allowed_attrs = array(
			'class' => array(),
			'id'    => array(),
			'style' => array(),
			'title' => array()
		);
		$allowed_html = array(
			'a'          => array_merge($allowed_attrs, array('href' => array())),
			'img'        => array_merge(
				$allowed_attrs,
				array(
					'src'    => array(),
					'alt'    => array(),
					'width'  => array(),
					'height' => array()
				)
			),
			'ins'        => array_merge($allowed_attrs, array('datetime' => array())),
			'del'        => array_merge($allowed_attrs, array('datetime' => array())),
			'p'          => $allowed_attrs,
			'br'         => $allowed_attrs,
			'em'         => $allowed_attrs,
			'b'          => $allowed_attrs,
			'ol'         => $allowed_attrs,
			'i'          => $allowed_attrs,
			'ul'         => $allowed_attrs,
			'li'         => $allowed_attrs,
			'table'      => $allowed_attrs,
			'tbody'      => $allowed_attrs,
			'tr'         => $allowed_attrs,
			'td'         => $allowed_attrs,
			'div'        => $allowed_attrs,
			'code'       => $allowed_attrs,
			'pre'        => $allowed_attrs,
			'sub'        => $allowed_attrs,
			'sup'        => $allowed_attrs,
			'span'       => $allowed_attrs,
			'q'          => $allowed_attrs,
			'h1'         => $allowed_attrs,
			'h2'         => $allowed_attrs,
			'h3'         => $allowed_attrs,
			'h4'         => $allowed_attrs,
			'h5'         => $allowed_attrs,
			'h6'         => $allowed_attrs,
			'abbr'       => $allowed_attrs,
			'strong'     => $allowed_attrs,
			'blockquote' => $allowed_attrs,
			'address'    => $allowed_attrs,
		);

		if($prefix = Config_Loader::get_config('prefix')){
			$allowed_html = apply_filters($prefix . '_form_safe_tags', $allowed_html);
		}

		return $allowed_html;
	}
}