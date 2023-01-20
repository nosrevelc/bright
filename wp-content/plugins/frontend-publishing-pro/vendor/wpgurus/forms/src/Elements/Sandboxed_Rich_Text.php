<?php

namespace WPGurus\Forms\Elements;

if (!defined('WPINC')) die;

use WPGurus\Forms\Components\Element_Sandbox;
use WPGurus\Forms\Constants\Assets;
use WPGurus\Forms\Element;

/**
 * Element that renders the wp_editor rich text inside of a sandbox iframe so that the CSS and JS of the editor do not conflict with the theme.
 *
 * Class Sandboxed_Rich_Text
 * @package WPGurus\Forms\Elements
 */
class Sandboxed_Rich_Text extends Rich_Text implements Sandboxed_Element
{
	/**
	 * Renders the real rich text editor and enqueues all the assets required inside the iframe.
	 */
	public function render_original_element()
	{
		parent::render_field_html();
		wp_enqueue_style(Assets::RICHTEXT_IFRAME_CONTENT_CSS);
		wp_enqueue_script(Assets::RICHTEXT_IFRAME_CONTENT_JS);
	}

	/**
	 * Renders the iframe element and all the assets required on the main page.
	 */
	public function render_field_html()
	{
		$container_attrs = array(
			'class' => 'richtext-sandbox-iframe-container'
		);
		?>
		<div <?php $this->print_attributes_array($container_attrs); ?>>
			<?php
			Element_Sandbox::render_iframe($this, 'richtext-sandbox-iframe regular-element-sandbox-iframe');

			// The following textarea is where the content entered in the rich text inside the iFrame ultimately end up.
			$textarea = new Textarea(
				array(
					Element::KEY        => $this->get_key(),
					Element::ID_PREFIX  => $this->get_id_prefix(),
					Element::ATTRIBUTES => array('style' => 'display:none;')
				)
			);
			$textarea->render();
			?>
		</div>
		<?php
		wp_enqueue_style(Assets::RICHTEXT_IFRAME_CSS);
		wp_enqueue_script(Assets::RICHTEXT_IFRAME_JS);
	}
}