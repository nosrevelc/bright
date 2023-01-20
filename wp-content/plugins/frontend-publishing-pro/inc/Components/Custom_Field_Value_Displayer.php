<?php
namespace WPFEPP\Components;

use WPFEPP\Constants\Assets;
use WPFEPP\Constants\Custom_Field_Locations;
use WPFEPP\Constants\Form_Meta_Keys;
use WPFEPP\Constants\Post_Field_Settings;
use WPFEPP\Constants\Post_Fields;
use WPFEPP\Constants\Post_Meta_Keys;
use WPFEPP\DB_Tables\Form_Meta;
use WPFEPP\Element_Containers\Form_Fields_Container;
use WPGurus\Components\Component;
use WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Element;
use WPGurus\Forms\Elements\Star_Rating;
use WPGurus\Forms\Factories\Element_Factory;
use WPGurus\Utils\Array_Utils;

if (!defined('WPINC')) die;

/**
 * Automatically displays custom fields and custom field values in different places.
 *
 * @package WPFEPP\Components
 */
class Custom_Field_Value_Displayer extends Component
{
	function __construct()
	{
		parent::__construct();

		$this->register_filter('the_content', 'display_values_in_content');
	}

	/**
	 * @param $meta_value string
	 * @param $field_settings array
	 */
	private function display_field_value($meta_key, $meta_value, $field_settings)
	{
		$element = $field_settings[ Post_Field_Settings::SETTING_ELEMENT ];
		?>
		<div class="form-custom-field-container <?php echo $meta_key; ?> <?php echo $element; ?>">
			<div class="form-custom-field-label">
				<?php echo $field_settings[ Post_Field_Settings::SETTING_LABEL ]; ?><span>:</span>
			</div>
			<div class="form-custom-field-value">
				<?php
				switch ($element) {
					case Elements::SELECT:
					case Elements::SELECT_WITH_SEARCH:
						$this->display_select_value($meta_value, $field_settings);
						break;

					case Elements::CHECKBOX:
						$this->display_checkbox_value($meta_value, $field_settings);
						break;

					case Elements::MEDIA_ID:
					case Elements::MEDIA_FILE:
						$this->display_media_id_value($meta_value, $field_settings);
						break;

					case Elements::MEDIA_URL:
						$this->display_media_url_value($meta_value, $field_settings);
						break;

					case Elements::MEDIA_IDS:
						$this->display_media_ids_value($meta_value, $field_settings);
						break;

					case Elements::STAR_RATING:
						$this->display_star_rating_value($meta_value, $field_settings);
						break;

					case Elements::COLOR_PICKER:
						$this->display_color_value($meta_value, $field_settings);
						break;

					case Elements::TEXT:
					case Elements::TEXTAREA:
					case Elements::RICH_TEXT:
					case Elements::SANDBOXED_RICH_TEXT:
						$this->display_text_value_with_embeds($meta_value, $field_settings);
						break;

					case Elements::DATE_PICKER:
					default:
						$this->display_text_value($meta_value, $field_settings);
						break;
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Displays custom field values in post content.
	 * @param $content string Initial post content.
	 * @return string Filtered post content.
	 */
	public function display_values_in_content($content)
	{
		if(!is_singular())
		{
			return $content;
		}

		wp_enqueue_style(Assets::CUSTOM_FIELDS_CSS);

		global $post;

		$post_form_id = get_post_meta($post->ID, Post_Meta_Keys::FORM_ID, true);
		if (!$post_form_id) {
			return $content;
		}

		$form_meta_table = new Form_Meta();
		$db_form_fields = $form_meta_table->get_meta_value($post_form_id, Form_Meta_Keys::FIELDS);
		if (!$db_form_fields) {
			return $content;
		}

		$form_fields_container = new Form_Fields_Container($post->post_type, $db_form_fields);
		$form_fields = $form_fields_container->get_values();
		$section_format = '<div class="form-custom-fields-container %s">%s</div>';
		$above_content = '';
		$below_content = '';

		foreach ($form_fields as $field => $field_settings) {
			if ($this->is_field_custom($field_settings)) {
				ob_start();
				$meta_value = get_post_meta($post->ID, $field, true);
				$this->display_field_value($field, $meta_value, $field_settings);
				$custom_field = ob_get_clean();

				if ($this->display_field_above_content($field_settings)) {
					$above_content .= $custom_field;
				}

				if ($this->display_field_below_content($field_settings)) {
					$below_content .= $custom_field;
				}
			}
		}
		$above_content = sprintf($section_format, 'above-content', $above_content);
		$below_content = sprintf($section_format, 'below-content', $below_content);

		return $above_content . $content . $below_content;
	}

	/**
	 * @param $meta_value string
	 * @param $field_settings array
	 */
	private function display_text_value($meta_value, $field_settings)
	{
		echo $meta_value;
	}

	/**
	 * Displays text that may contain embeddable URLs in it.
	 * @param $meta_value string Text to display.
	 * @param $field_settings array Field settings.
	 */
	private function display_text_value_with_embeds($meta_value, $field_settings)
	{
		if ($field_settings[ Post_Field_Settings::SETTING_ENABLE_CUSTOM_FIELD_EMBEDS ]) {
			/**
			 * @var $wp_embed \WP_Embed
			 */
			global $wp_embed;
			$meta_value = $wp_embed->autoembed($meta_value);
		}
		echo $meta_value;
	}

	/**
	 * @param $meta_value string
	 * @param $field_settings array
	 */
	private function display_select_value($meta_value, $field_settings)
	{
		if (!is_array($meta_value)) {
			$this->display_text_value($meta_value, $field_settings);
			return;
		}

		?>
		<ul>
			<?php foreach ($meta_value as $list_item): ?>
				<li><?php echo $list_item; ?></li>
			<?php endforeach; ?>
		</ul>
		<?php
	}

	/**
	 * @param $meta_value string
	 * @param $field_settings array
	 */
	private function display_checkbox_value($meta_value, $field_settings)
	{
		$class = ((boolean)$meta_value) ? 'check-box-value-true' : 'check-box-value-false';
		echo "<span class='$class'></span>";
	}

	/**
	 * @param $meta_value string
	 * @param $field_settings array
	 */
	private function display_media_id_value($meta_value, $field_settings)
	{
		$attachment_markup = wp_get_attachment_image(
			$meta_value,
			$field_settings[ Post_Field_Settings::SETTING_MEDIA_ITEM_DISPLAY_SIZE ]
		);

		if (empty($attachment_markup)) {
			$attachment_markup = wp_get_attachment_link($meta_value);
		}

		echo $attachment_markup;
	}

	/**
	 * @param $meta_value string
	 * @param $field_settings array
	 */
	private function display_media_url_value($meta_value, $field_settings)
	{
		$max_width = $field_settings[ Post_Field_Settings::SETTING_MEDIA_URL_DISPLAY_WIDTH ];
		$style = $max_width ? sprintf('style="max-width: %s;"', $max_width) : '';

		echo sprintf('<img src="%s" %s />', $meta_value, $style);
	}

	/**
	 * @param $meta_value string
	 * @param $field_settings array
	 */
	private function display_media_ids_value($meta_value, $field_settings)
	{
		echo do_shortcode(
			sprintf(
				'[gallery ids="%s" columns="%s" size="%s" link="%s"]',
				$meta_value,
				$field_settings[ Post_Field_Settings::SETTING_MEDIA_ITEMS_DISPLAY_COLUMNS ],
				$field_settings[ Post_Field_Settings::SETTING_MEDIA_ITEMS_DISPLAY_SIZE ],
				$field_settings[ Post_Field_Settings::SETTING_MEDIA_ITEMS_DISPLAY_LINK ]
			)
		);
	}

	private function display_color_value($meta_value, $field_settings)
	{
		echo sprintf('<div class="picked-color-value" style="background: %s;"></div>', $meta_value);
	}

	/**
	 * @param $meta_value string
	 * @param $field_settings array
	 */
	private function display_star_rating_value($meta_value, $field_settings)
	{
		$star_rating_element = Element_Factory::make_element(
			Elements::STAR_RATING,
			$field_settings
		);
		$star_rating_element->set_template(WPFEPP_ELEMENT_TEMPLATES_DIR . 'display-element.php');
		$star_rating_element->set_value($meta_value);
		$star_rating_element->set_attribute('data-read-only', 'true');
		$star_rating_element->render();
	}

	/**
	 * @param $field_settings array
	 * @return bool
	 */
	private function is_field_custom($field_settings)
	{
		return $field_settings[ Post_Field_Settings::SETTING_TYPE ] == Post_Fields::FIELD_CUSTOM;
	}

	/**
	 * @param $field_settings array
	 * @return bool
	 */
	private function display_field_above_content($field_settings)
	{
		return in_array(
			Custom_Field_Locations::ABOVE_POST_CONTENT,
			$field_settings[ Post_Field_Settings::SETTING_CUSTOM_FIELD_LOCATIONS ]
		);
	}

	/**
	 * @param $field_settings array
	 * @return bool
	 */
	private function display_field_below_content($field_settings)
	{
		return in_array(
			Custom_Field_Locations::BELOW_POST_CONTENT,
			$field_settings[ Post_Field_Settings::SETTING_CUSTOM_FIELD_LOCATIONS ]
		);
	}
}