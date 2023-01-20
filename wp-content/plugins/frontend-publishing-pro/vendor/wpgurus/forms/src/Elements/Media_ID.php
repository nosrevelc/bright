<?php

namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

use WPGurus\Forms\Sanitizers\Typecast;

/**
 * An extension of the Media class, this element is used when a single media id needs to be captured.
 *
 * Class Media_ID
 * @package WPGurus\Forms\Elements
 */
class Media_ID extends Media
{
	function __construct($args)
	{
		$args[ Media::FRAME_TITLE ] = __('Select an Item', 'frontend-publishing-pro');
		$args[ Media::FRAME_BUTTON_TEXT ] = __('Select', 'frontend-publishing-pro');

		parent::__construct($args);

		$this->add_sanitizer(new Typecast(Typecast::TYPE_INT));
	}

	/**
	 * Takes a media ID and returns the preview HTML for it.
	 *
	 * @param $id
	 * @return string
	 */
	protected function render_preview_html($id)
	{
		echo ($id) ? wp_get_attachment_image($id, 'full') : '';
	}
}