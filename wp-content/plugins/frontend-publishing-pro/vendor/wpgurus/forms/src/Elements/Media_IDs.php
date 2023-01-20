<?php
namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

/**
 * An extension of the Media class, this element is used when a multiple media ids needs to be captured.
 *
 * Class Media_IDs
 * @package WPGurus\Forms\Elements
 */
class Media_IDs extends Media
{
	function __construct($args)
	{
		$args[ Media::FRAME_TITLE ] = __('Select Items', 'frontend-publishing-pro');
		$args[ Media::FRAME_BUTTON_TEXT ] = __('Select', 'frontend-publishing-pro');
		$args[ Media::MULTIPLE ] = true;

		parent::__construct($args);
	}

	/**
	 * Takes multiple media IDs and returns preview HTML for them.
	 *
	 * @param $ids
	 * @return string
	 */
	protected function render_preview_html($ids)
	{
		$html = '';

		if ($ids) {
			foreach (explode(',', $ids) as $id) {
				$html .= wp_get_attachment_image(trim($id), 'thumbnail');
			}
		}

		echo $html;
	}
}