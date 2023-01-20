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
use WPFEPP\Utils\String_Utils;
use WPGurus\Components\Component;
use WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Element;
use WPGurus\Forms\Elements\Select;
use WPGurus\Forms\Factories\Element_Factory;
use WPGurus\Utils\Array_Utils;

if (!defined('WPINC')) die;

/**
 * Displays a meta box containing custom fields on the post editing screen in the admin area.
 *
 * @package WPFEPP\Components
 */
class Custom_Fields_Meta_Box extends Component
{
	const BOX_ID = 'frontend-form-custom-fields-meta-box';
	const FIELD_NAME_PREFIX = 'frontend_form_custom_fields';
	const BOX_NONCE_NAME = 'frontend_form_custom_fields_meta_nonce';
	const BOX_NONCE_ACTION = 'frontend_form_custom_fields_meta_nonce_%s_action';

	function __construct()
	{
		parent::__construct();

		$this->register_action('add_meta_boxes', array($this, 'add_post_meta_box'));
		$this->register_action('save_post', array($this, 'save_post_meta'), 10, 1);
	}

	/**
	 * Calls WP add_meta_box
	 */
	public function add_post_meta_box()
	{
		global $post;
		if (!is_a($post, '\WP_Post')) {
			return;
		}

		$fields_to_display = $this->get_fields_to_display($post->ID);
		if ($fields_to_display) {
			add_meta_box(
				self::BOX_ID,
				__('Frontend Form Custom Fields', 'frontend-publishing-pro'),
				array($this, 'display_meta_box'),
				$post->post_type,
				'normal',
				'default',
				array(
					'form_fields' => $fields_to_display
				)
			);
		}
	}

	/**
	 * Displays the meta box.
	 *
	 * @param $post \WP_Post
	 * @param $meta_box array Meta box details.
	 */
	public function display_meta_box($post, $meta_box)
	{
		wp_enqueue_style(Assets::CUSTOM_FIELDS_META_BOX_CSS);

		$form_fields = $meta_box['args']['form_fields'];
		wp_nonce_field(
			sprintf(self::BOX_NONCE_ACTION, $post->ID),
			self::BOX_NONCE_NAME,
			false
		);

		?>
		<div class="meta-box-custom-fields">
			<?php $this->display_elements($post, $form_fields); ?>
		</div>
		<?php
	}

	/**
	 * @param $field_settings array
	 * @return bool
	 */
	private function is_field_element_multiple_select($field_settings)
	{
		return in_array($field_settings[ Post_Field_Settings::SETTING_ELEMENT ], array(Elements::SELECT, Elements::SELECT_WITH_SEARCH))
		&& $field_settings[ Post_Field_Settings::SETTING_MULTIPLE ];
	}

	/**
	 * @param $post \WP_Post
	 * @param $form_fields array
	 */
	private function display_elements($post, $form_fields)
	{
		foreach ($form_fields as $field_id => $field_settings) {
			?>
			<div class="meta-box-custom-field">
				<div class="meta-box-custom-field-inner">
					<?php $this->display_element($post, $field_id, $field_settings); ?>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * @param $post \WP_Post
	 * @param $field_id int
	 * @param $field_settings array
	 */
	private function display_element($post, $field_id, $field_settings)
	{
		$element_type = $field_settings[ Post_Field_Settings::SETTING_ELEMENT ];

		if ($element_type == Elements::MEDIA_FILE) {
			$element_type = Elements::MEDIA_ID;
		}

		$custom_field_element_key = array(self::FIELD_NAME_PREFIX, $field_id);
		if ($this->is_field_element_multiple_select($field_settings)) {
			$custom_field_element_key[] = '';
		}

		$element_args = array(
			Element::KEY    => $custom_field_element_key,
			Element::VALUE  => get_post_meta($post->ID, $field_id, true),
			Select::CHOICES => String_Utils::parse_choices_string($field_settings[ Post_Field_Settings::SETTING_CHOICES ])
		);

		$element_args = wp_parse_args(
			$element_args,
			$field_settings
		);

		$element = Element_Factory::make_element(
			$element_type,
			$element_args
		);

		$element->render();
	}

	/**
	 * Saves meta values.
	 *
	 * @param $post_id int The post ID.
	 */
	public function save_post_meta($post_id)
	{
		$meta_values = Array_Utils::get($_POST, array(self::FIELD_NAME_PREFIX));
		$nonce = Array_Utils::get($_POST, array(self::BOX_NONCE_NAME));
		$nonce_verified = wp_verify_nonce(
			$nonce,
			sprintf(self::BOX_NONCE_ACTION, $post_id)
		);
		$form_fields = $this->get_form_fields_for_post($post_id);

		if (!$nonce_verified || $meta_values === null || !$form_fields) {
			return;
		}

		if (is_array($meta_values)) {
			foreach ($meta_values as $key => $value) {
				$save_field = Array_Utils::get($form_fields, array($key, Post_Field_Settings::SETTING_SAVE_CUSTOM_VAL_TO_META));
				if ($save_field === false) {
					delete_post_meta($post_id, $key);
				} else {
					update_post_meta($post_id, $key, $value);
				}
			}
		}
	}

	/**
	 * @param $post_id
	 * @return mixed
	 */
	private function get_form_fields_for_post($post_id)
	{
		$post_form = get_post_meta($post_id, Post_Meta_Keys::FORM_ID, true);
		if (!$post_form) {
			return false;
		}

		$form_meta_table = new Form_Meta();
		$form_fields_container = new Form_Fields_Container(
			get_post_field($post_id, 'post_type'),
			$form_meta_table->get_meta_value($post_form, Form_Meta_Keys::FIELDS)
		);

		return $form_fields_container->get_values();
	}

	/**
	 * @param $field_settings array
	 * @return bool
	 */
	private function display_field_on_edit_page($field_settings)
	{
		return $field_settings[ Post_Field_Settings::SETTING_TYPE ] == Post_Fields::FIELD_CUSTOM
		&& in_array(
			Custom_Field_Locations::ADMIN_EDIT_PAGE,
			$field_settings[ Post_Field_Settings::SETTING_CUSTOM_FIELD_LOCATIONS ]
		);
	}

	/**
	 * @param $post_id int
	 * @return array
	 */
	private function get_fields_to_display($post_id)
	{
		$form_fields = $this->get_form_fields_for_post($post_id);
		$fields_to_display = array();

		if (!$form_fields) {
			return $fields_to_display;
		}

		foreach ($form_fields as $field_id => $field_settings) {
			if ($this->display_field_on_edit_page($field_settings)) {
				$fields_to_display[ $field_id ] = $field_settings;
			}
		}
		return $fields_to_display;
	}
}