<?php
namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

use WPGurus\Forms\Constants\Assets;
use WPGurus\Forms\Element;

/**
 * This element does not use the media library directly. Instead, a traditional file input is displayed to the user but the file is ultimately uploaded to the media library using a form processor. The value that ultimately gets passed on is a media item id.
 * Most of the heavy-lifting is done by the jQuery plugin and the rest is done by the processor. This class exists solely for the HTML markup required by jQuery.
 *
 * Class Media_File
 * @package WPGurus\Forms\Elements
 */
class Media_File extends Input
{
	const MAIN_SELECTOR = 'element-media-file';

	/**
	 * Renders the markup required by the jQuery plugin and enqueues all the necessary assets.
	 */
	function render_field_html()
	{
		$this->set_attribute('type', 'hidden');

		wp_enqueue_style(Assets::MEDIA_FILE_ELEMENT_CSS);
		wp_enqueue_script(Assets::MEDIA_FILE_ELEMENT_JS);
		$file_input = new File(
			array(
				Element::KEY => $this->get_file_input_key()
			)
		);

		?>
		<div class="<?php echo self::MAIN_SELECTOR; ?> <?php echo $this->get_value() ? 'element-media-file-preview-state' : 'element-media-file-upload-state'; ?>">
			<?php if ($this->get_value()): ?>
				<div class="element-media-file-preview-container">
					<div class="element-media-file-preview">
						<?php echo wp_get_attachment_image($this->get_value(), 'full'); ?>
					</div>
					<div class="element-media-file-controls">
						<a href="#" class="element-media-file-reload"><i class="fa fa-arrow-left"></i></a>
						<a href="#" class="element-media-file-clear"><i class="fa fa-close"></i></a>
						<div style="clear:both;"></div>
					</div>
				</div>
			<?php endif; ?>
			<div class="element-media-file-id-input">
				<?php parent::render_field_html(); ?>
			</div>
			<div class="element-media-file-input">
				<?php $file_input->render(); ?>
			</div>
		</div>
		<?php
	}

	public function get_file_input_key()
	{
		$keys = $this->get_key();
		$keys[] = 'file_input';
		return implode('_', $keys);
	}
}