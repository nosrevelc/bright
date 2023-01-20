<?php

namespace WPFEPP\Data_Mappers;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Email_Settings;
use WPFEPP\Constants\Form_Meta_Keys;
use WPFEPP\Constants\Frontend_Form_Messages;
use WPFEPP\Constants\General_Media_Types;
use WPFEPP\Constants\Option_Ids;
use WPFEPP\Constants\Post_Field_Settings;
use WPFEPP\Constants\Post_Fields;
use WPFEPP\Constants\Post_List_Settings;
use WPFEPP\Constants\Post_List_Tabs;
use WPFEPP\Constants\reCaptcha_Settings;
use WPFEPP\DB_Tables\Form_Meta;
use WPFEPP\DB_Tables\Forms;
use WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Constants\Sanitizers;
use WPGurus\Forms\Constants\Validators;

class Mapper_3 extends Mapper
{
	function map()
	{
		delete_option('wpfepp_db_table_version');
		delete_option('wpfepp_copyscape_settings');

		$old_media_settings = get_option(Option_Ids::OPTION_MEDIA_SETTINGS, array());
		update_option(
			Option_Ids::OPTION_MEDIA_SETTINGS,
			$this->map_media_settings($old_media_settings)
		);

		$old_post_list_settings = get_option(Option_Ids::OPTION_POST_LIST_SETTINGS, array());
		update_option(
			Option_Ids::OPTION_POST_LIST_SETTINGS,
			$this->map_post_list_settings($old_post_list_settings)
		);

		$old_data_settings = get_site_option(Option_Ids::OPTION_DATA_SETTINGS, array());
		update_site_option(
			Option_Ids::OPTION_DATA_SETTINGS,
			$this->map_data_settings($old_data_settings)
		);

		$old_messages = get_option('wpfepp_errors', array());
		update_option(
			Option_Ids::OPTION_MESSAGES,
			$this->map_messages($old_messages)
		);
		delete_option('wpfepp_errors');

		$old_email_settings = get_option(Option_Ids::OPTION_EMAIL_SETTINGS, array());
		update_option(
			Option_Ids::OPTION_EMAIL_SETTINGS,
			$this->map_email_settings($old_email_settings)
		);

		$old_recaptcha_settings = get_option(Option_Ids::OPTION_RECAPTCHA_SETTINGS, array());
		update_option(
			Option_Ids::OPTION_RECAPTCHA_SETTINGS,
			$this->map_recaptcha_settings($old_recaptcha_settings)
		);

		global $wpdb;

		$forms_table = new Forms();
		$form_meta_table = new Form_Meta();

		if ($wpdb->get_var("SHOW TABLES LIKE 'wpfepp_forms'") == 'wpfepp_forms') {
			$existing_rows = $wpdb->get_results('SELECT * FROM wpfepp_forms', ARRAY_A);

			foreach ($existing_rows as $row) {
				$forms_table->add(
					array(
						Forms::COLUMN_NAME        => $row['name'],
						Forms::COLUMN_POST_TYPE   => $row['post_type'],
						Forms::COLUMN_DESCRIPTION => $row['description']
					)
				);

				$insert_id = $forms_table->get_insert_id();

				$old_form_fields = $this->unserialize($row['fields']);
				$new_form_fields = $this->map_form_fields($old_form_fields);
				$form_meta_table->add_meta_value($insert_id, Form_Meta_Keys::FIELDS, $new_form_fields);

				$old_form_settings = $this->unserialize($row['settings']);
				$new_form_settings = $this->map_form_settings($old_form_settings);
				$form_meta_table->add_meta_value($insert_id, Form_Meta_Keys::SETTINGS, $new_form_settings);

				$old_form_emails = $this->unserialize($row['emails']);
				$new_form_emails = $this->map_form_email_settings($old_form_emails, $old_form_settings, $old_email_settings);
				$form_meta_table->add_meta_value($insert_id, Form_Meta_Keys::EMAILS, $new_form_emails);
			}

			$wpdb->query("DROP TABLE wpfepp_forms");
		}
	}

	function serialize($item)
	{
		return base64_encode(serialize($item));
	}

	function unserialize($item)
	{
		if (base64_decode($item, true) !== false)
			$item = base64_decode($item);

		return unserialize($item);
	}

	private function map_recaptcha_settings($old_values)
	{
		$key_mappings = array(
			'site_key' => reCaptcha_Settings::SETTING_SITE_KEY,
			'secret'   => reCaptcha_Settings::SETTING_SECRET,
			'theme'    => reCaptcha_Settings::SETTING_THEME
		);

		return $this->map_full($old_values, $key_mappings);
	}

	private function map_email_settings($old_values)
	{
		return $old_values;
	}

	private function map_messages($old_values)
	{
		$key_mappings = array(
			'form'          => Frontend_Form_Messages::SETTING_FORM_ERROR,
			'required'      => Frontend_Form_Messages::SETTING_REQUIRED_ERROR,
			'min_words'     => Frontend_Form_Messages::SETTING_MIN_WORDS_ERROR,
			'max_words'     => Frontend_Form_Messages::SETTING_MAX_WORDS_ERROR,
			'max_links'     => Frontend_Form_Messages::SETTING_MAX_LINKS_ERROR,
			'min_segments'  => Frontend_Form_Messages::SETTING_MIN_COUNT_ERROR,
			'max_segments'  => Frontend_Form_Messages::SETTING_MAX_COUNT_ERROR,
			'invalid_email' => Frontend_Form_Messages::SETTING_INVALID_EMAIL_ERROR,
			'invalid_url'   => Frontend_Form_Messages::SETTING_INVALID_URL_ERROR
		);

		return $this->map_full(
			$old_values,
			$key_mappings
		);
	}

	private function map_data_settings($old_values)
	{
		return $old_values;
	}

	private function map_post_list_settings($old_values)
	{
		$key_mappings = array(
			'post_list_page_len' => Post_List_Settings::PAGE_LENGTH,
			'post_list_tabs'     => Post_List_Settings::ACTIVE_TABS
		);

		$checkbox_groups = array(
			'post_list_tabs'
		);

		$mapped = $this->map_full($old_values, $key_mappings, null, $checkbox_groups);

		if (isset($mapped[ Post_List_Settings::ACTIVE_TABS ])) {
			foreach ($mapped[ Post_List_Settings::ACTIVE_TABS ] as $tab_key => $post_list_tab) {
				if ($post_list_tab == 'live') {
					$mapped[ Post_List_Settings::ACTIVE_TABS ][ $tab_key ] = Post_List_Tabs::PUBLISHED;
				}
			}
		}

		return $mapped;
	}

	private function map_media_settings($old_values)
	{
		$media_types_key = 'allowed_media_types';

		$new_values = $this->map_all_checkbox_groups(
			$old_values,
			array(
				$media_types_key,
				'exempt_roles'
			)
		);

		if (isset($new_values[ $media_types_key ])) {
			$new_values[ $media_types_key ] = $this->replace_value('image', General_Media_Types::TYPE_IMAGE, $new_values[ $media_types_key ]);
			$new_values[ $media_types_key ] = $this->replace_value('video', General_Media_Types::TYPE_VIDEO, $new_values[ $media_types_key ]);
			$new_values[ $media_types_key ] = $this->replace_value('text', General_Media_Types::TYPE_TEXT, $new_values[ $media_types_key ]);
			$new_values[ $media_types_key ] = $this->replace_value('audio', General_Media_Types::TYPE_AUDIO, $new_values[ $media_types_key ]);
			$new_values[ $media_types_key ] = $this->replace_value('office', General_Media_Types::TYPE_OFFICE, $new_values[ $media_types_key ]);
			$new_values[ $media_types_key ] = $this->replace_value('open_office', General_Media_Types::TYPE_OPEN_OFFICE, $new_values[ $media_types_key ]);
			$new_values[ $media_types_key ] = $this->replace_value('wordperfect', General_Media_Types::TYPE_WORDPERFECT, $new_values[ $media_types_key ]);
			$new_values[ $media_types_key ] = $this->replace_value('iwork', General_Media_Types::TYPE_IWORK, $new_values[ $media_types_key ]);
			$new_values[ $media_types_key ] = $this->replace_value('misc', General_Media_Types::TYPE_MISC, $new_values[ $media_types_key ]);
		}

		return $new_values;
	}

	private function map_form_email_settings($old_emails, $old_settings, $email_settings)
	{
		$old_emails[ Email_Settings::SETTING_USER_EMAILS ] = isset($old_settings[ Email_Settings::SETTING_USER_EMAILS ]) ? $old_settings[ Email_Settings::SETTING_USER_EMAILS ] : true;
		$old_emails[ Email_Settings::SETTING_ADMIN_EMAILS ] = isset($old_settings[ Email_Settings::SETTING_ADMIN_EMAILS ]) ? $old_settings[ Email_Settings::SETTING_ADMIN_EMAILS ] : true;
		$old_emails[ Email_Settings::SETTING_SENDER_ADDRESS ] = isset($email_settings[ Email_Settings::SETTING_SENDER_ADDRESS ]) ? $email_settings[ Email_Settings::SETTING_SENDER_ADDRESS ] : '';
		$old_emails[ Email_Settings::SETTING_SENDER_NAME ] = isset($email_settings[ Email_Settings::SETTING_SENDER_NAME ]) ? $email_settings[ Email_Settings::SETTING_SENDER_NAME ] : '';
		$old_emails[ Email_Settings::SETTING_EMAIL_FORMAT ] = isset($email_settings[ Email_Settings::SETTING_EMAIL_FORMAT ]) ? $email_settings[ Email_Settings::SETTING_EMAIL_FORMAT ] : '';

		return $old_emails;
	}

	private function map_form_settings($old_settings)
	{
		return $this->map_all_checkbox_groups(
			$old_settings,
			array(
				'no_restrictions',
				'instantly_publish'
			)
		);
	}

	private function map_form_fields($old_fields)
	{
		$field_key_mappings = array(
			'title'      => Post_Fields::FIELD_TITLE,
			'content'    => Post_Fields::FIELD_CONTENT,
			'excerpt'    => Post_Fields::FIELD_EXCERPT,
			'thumbnail'  => Post_Fields::FIELD_THUMBNAIL,
			'formatting' => Post_Fields::FIELD_POST_FORMAT,
		);

		$field_setting_key_mappings = array(
			'label'        => Post_Field_Settings::SETTING_LABEL,
			'width'        => Post_Field_Settings::SETTING_WIDTH,
			'media_button' => Post_Field_Settings::SETTING_RICH_MEDIA_BUTTON,
			'multiple'     => Post_Field_Settings::SETTING_MULTIPLE,
			'hide_empty'   => Post_Field_Settings::SETTING_HIDE_EMPTY_TAXONOMIES,
			'exclude'      => Post_Field_Settings::SETTING_EXCLUDE_TAXONOMIES,
			'include'      => Post_Field_Settings::SETTING_INCLUDE_TAXONOMIES,
			'choices'      => Post_Field_Settings::SETTING_CHOICES,
			'prefix_text'  => Post_Field_Settings::SETTING_PREFIX_TEXT,
			'required'     => Validators::REQUIRED,
			'min_words'    => Validators::MIN_WORDS,
			'max_words'    => Validators::MAX_WORDS,
			'min_count'    => Validators::MIN_COUNT,
			'max_count'    => Validators::MAX_COUNT,
			'max_links'    => Validators::MAX_LINKS,
			'strip_tags'   => Sanitizers::STRIP_TAGS,
			'nofollow'     => Sanitizers::NOFOLLOW
		);

		$value_mappings = array(
			'element' => array(
				'input'     => Elements::TEXT,
				'textarea'  => Elements::TEXTAREA,
				'checkbox'  => Elements::CHECKBOX,
				'select'    => Elements::SELECT,
				'radio'     => Elements::SELECT,
				'email'     => Elements::TEXT,
				'url'       => Elements::TEXT,
				'image_url' => Elements::MEDIA_URL,
				'richtext'  => Elements::RICH_TEXT,
				'plaintext' => Elements::TEXTAREA
			),
			'type'    => array(
				'title'                     => Post_Fields::FIELD_TITLE,
				'content'                   => Post_Fields::FIELD_CONTENT,
				'excerpt'                   => Post_Fields::FIELD_EXCERPT,
				'thumbnail'                 => Post_Fields::FIELD_THUMBNAIL,
				'post_format'               => Post_Fields::FIELD_POST_FORMAT,
				'hierarchical_taxonomy'     => Post_Fields::FIELD_HIERARCHICAL_TAX,
				'non_hierarchical_taxonomy' => Post_Fields::FIELD_NON_HIERARCHICAL_TAX,
				'custom_field'              => Post_Fields::FIELD_CUSTOM
			)
		);

		foreach ($old_fields as $field_key => $old_field_settings) {
			$old_fields[ $field_key ] = $this->map_full($old_field_settings, $field_setting_key_mappings, $value_mappings);
		}

		return $this->map_full($old_fields, $field_key_mappings);
	}

	private function map_full($old_values, $key_mappings, $value_mappings = null, $checkbox_groups = null)
	{
		$value_mappings = $value_mappings ? $value_mappings : array();
		$checkbox_groups = $checkbox_groups ? $checkbox_groups : array();
		$old_values = $old_values ? $old_values : array();

		$new_values = array();
		foreach ($old_values as $key => $value) {
			$new_value = isset($value_mappings[ $key ][ $value ]) ? $value_mappings[ $key ][ $value ] : $value;

			if (in_array($key, $checkbox_groups)) {
				$new_value = $this->map_checkbox_group($new_value);
			}

			if (array_key_exists($key, $key_mappings)) {
				$new_values[ $key_mappings[ $key ] ] = $new_value;
			} else {
				$new_values[ $key ] = $new_value;
			}
		}

		return $new_values;
	}

	private function map_all_checkbox_groups($old_settings, $checkbox_group_keys)
	{
		foreach ($old_settings as $setting_key => $setting_value) {
			if (in_array($setting_key, $checkbox_group_keys)) {
				$old_settings[ $setting_key ] = $this->map_checkbox_group($setting_value);
			}
		}

		return $old_settings;
	}

	private function map_checkbox_group($values)
	{
		$new_values = array();

		if (!is_array($values))
			return $values;

		foreach ($values as $key => $value) {
			if ($value) {
				$new_values[] = $key;
			}
		}

		return $new_values;
	}

	private function replace_value($old_value, $new_value, $array)
	{
		foreach ($array as $key => $value) {
			if ($value == $old_value) {
				$array[ $key ] = $new_value;
			}
		}

		return $array;
	}
}