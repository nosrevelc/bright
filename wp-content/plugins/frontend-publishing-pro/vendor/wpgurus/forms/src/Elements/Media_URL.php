<?php
namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

/**
 * An extension of the Media class, this element is used when a media URL needs to be captured.
 *
 * Class Media_URL
 * @package WPGurus\Forms\Elements
 */
class Media_URL extends Media
{
	function __construct($args)
	{
		$args[ Media::FRAME_TITLE ] = __('Select an Item', 'frontend-publishing-pro');
		$args[ Media::FRAME_BUTTON_TEXT ] = __('Select', 'frontend-publishing-pro');
		$args[ Media::ATTRIBUTE ] = 'url';

		parent::__construct($args);
	}

	/**
	 * Takes an image URL and returns preview HTML for it.
	 *
	 * @param $image_url
	 * @return string
	 */
	protected function render_preview_html($image_url)
	{
		if ($image_url):
			?>
			<img src="<?php echo $image_url; ?>"/>
			<?php
		endif;
	}
}