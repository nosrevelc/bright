<?php

namespace WPFEPP\Element_Containers;

if (!defined('WPINC')) die;

use WPFEPP\Ajax\Frontend_Form_Ajax;
use WPFEPP\Constants\Form_Settings;
use WPFEPP\Constants\Frontend_Form_Messages;
use WPFEPP\Constants\Option_Ids;
use WPFEPP\Constants\Post_Field_Settings as Field_Settings;
use WPFEPP\Constants\Post_Fields;
use WPFEPP\Elements\Action_Element;
use WPFEPP\Utils\String_Utils;
use WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Element;
use WPGurus\Forms\Elements\Button;
use WPGurus\Forms\Elements\Google_reCaptcha;
use WPGurus\Forms\Elements\Rich_Text;
use WPGurus\Forms\Elements\Select;
use WPGurus\Forms\Factories\Element_Factory;

class Frontend_Element_Container extends \WPGurus\Forms\Element_Container
{
	private $form_db_id;
	private $post_type;
	private $fields;
	private $settings;
	private $current_values;

	const POST_FIELD_ID = 'ID';
	const PREFIX_POST_FORMAT = 'post-format-';
	const TEMPL_ARG_ELEMENT_CONTAINER_STYLE = 'element_container_style';
	const TEMPL_ARG_FIELD_CONTAINER_STYLE = 'field_container_style';
	const COMMON_KEY_POST_DATA = 'post_data';

	private $element_template;
	private $container_template;
	private $messages;

	const ELEM_KEY_FORM_DB_ID = 'form_db_id';

	const COMMON_KEY_CUSTOM_FIELD = 'custom_field';

	const JS_VALIDATION_CLASS = 'js-validated-field';

	const ELEM_KEY_SUBMIT_BUTTON = 'submit_button';

	const SUBMIT_BUTTON_VAL_DRAFT = 'save-draft';

	const SUBMIT_BUTTON_VAL_SUBMIT = 'submit-form';

	const TEMPLATE_ARG_ELEMENTS = 'elements';

	const COMMON_KEY_TAX_TERMS = 'taxonomy_terms';

	public function __construct($form_db_id, $post_type, $fields, $settings, $current_values = array(), $messages = array())
	{
		$this->form_db_id = $form_db_id;
		$this->post_type = $post_type;
		$this->fields = $fields;
		$this->settings = $settings;
		$this->current_values = $current_values;
		$this->messages = $messages;
		$this->element_template = WPFEPP_ELEMENT_TEMPLATES_DIR . 'frontend-form-element.php';
		$this->container_template = WPFEPP_ELEMENT_CONTAINER_TEMPLATES_DIR . 'frontend-element-container.php';

		$this->build_form_elements();
	}

	public function add_element($element, $key = null, $prepend_key = true, $id_prefix = '')
	{
		if ($prepend_key) {
			$element->prepend_key(self::COMMON_KEY_POST_DATA);
		}

		$element->set_id_prefix(
			$id_prefix ? $id_prefix : $this->id_prefix()
		);

		parent::add_element($element, $key);
	}

	private function build_form_elements()
	{
		foreach ($this->fields as $field_id => $field_settings) {

			if (!\WPFEPP\is_field_supported($field_settings[ Field_Settings::SETTING_TYPE ], $this->post_type)) {
				continue;
			}

			$element_args = array();
			$element_type = '';

			switch ($field_settings[ Field_Settings::SETTING_TYPE ]) {
				case Post_Fields::FIELD_TITLE:
					$element_type = Elements::TEXT;
					$element_args = array(
						Element::KEY   => $field_id,
						Element::VALUE => $this->get_post_field($field_id)
					);
					break;

				case Post_Fields::FIELD_CONTENT:
					$element_type = $field_settings[ Field_Settings::SETTING_ELEMENT ];
					$element_args = array(
						Element::KEY   => $field_id,
						Element::VALUE => $this->get_post_field($field_id)
					);
					break;

				case Post_Fields::FIELD_EXCERPT:
					$element_type = Elements::TEXTAREA;
					$element_args = array(
						Element::KEY   => $field_id,
						Element::VALUE => $this->get_post_field($field_id)
					);
					break;

				case Post_Fields::FIELD_THUMBNAIL:
					$element_type = $field_settings[ Field_Settings::SETTING_ELEMENT ];
					$element_args = array(
						Element::KEY   => $field_id,
						Element::VALUE => $this->get_thumb_id()
					);
					break;

				case Post_Fields::FIELD_POST_FORMAT:
					$element_type = $field_settings[ Field_Settings::SETTING_ELEMENT ];
					$element_args = array(
						Element::KEY    => array(self::COMMON_KEY_TAX_TERMS, $field_id),
						Select::CHOICES => $this->get_supported_formats(),
						Element::VALUE  => $this->get_post_terms(
							$field_id,
							array(
								'fields' => 'slugs'
							)
						)
					);
					break;

				case Post_Fields::FIELD_HIERARCHICAL_TAX:
					$element_type = $field_settings[ Field_Settings::SETTING_ELEMENT ];
					$element_args = array(
						Element::KEY    => array(self::COMMON_KEY_TAX_TERMS, $field_id, ''),
						Select::CHOICES => $this->get_hierarchical_term_choices($field_id, $field_settings),
						Element::VALUE  => $this->get_post_terms(
							$field_id,
							array(
								'fields' => 'ids'
							)
						)
					);
					break;

				case Post_Fields::FIELD_NON_HIERARCHICAL_TAX:
					$element_type = $field_settings[ Field_Settings::SETTING_ELEMENT ];

					$non_hierarchical_tax_terms = $this->get_post_terms(
						$field_id,
						array(
							'fields' => 'names'
						),
						array()
					);

					if ($element_type == Elements::TEXT) {
						$non_hierarchical_tax_terms = implode(
							', ',
							$non_hierarchical_tax_terms
						);

						$tax_element_key = array(self::COMMON_KEY_TAX_TERMS, $field_id);
					} else {
						$tax_element_key = array(self::COMMON_KEY_TAX_TERMS, $field_id, '');
					}

					$element_args = array(
						// TODO: The key will be different for multi-selects.
						Element::KEY    => $tax_element_key,
						Select::CHOICES => $this->get_non_hierarchical_term_choices($field_id, $field_settings),
						Element::VALUE  => $non_hierarchical_tax_terms
					);
					break;

				case Post_Fields::FIELD_CUSTOM:
					$element_type = $field_settings[ Field_Settings::SETTING_ELEMENT ];
					$custom_field_element_key = array(self::COMMON_KEY_CUSTOM_FIELD, $field_id);
					if ($this->is_field_element_multiple_select($field_settings)) {
						$custom_field_element_key[] = '';
					}
					$element_args = array(
						Element::KEY    => $custom_field_element_key,
						Element::VALUE  => $this->get_post_meta($field_id),
						Select::CHOICES => String_Utils::parse_choices_string($field_settings[ Field_Settings::SETTING_CHOICES ])
					);
					break;

				default:
					break;
			}

			$element_container_style = '';
			if (!$field_settings[ Field_Settings::SETTING_ENABLED ]) {
				$element_container_style .= 'display:none;';

				if ($field_settings[ Field_Settings::SETTING_FALLBACK ]) {
					$element_args[ Element::VALUE ] = $this->parse_fallback_value($field_settings[ Field_Settings::SETTING_FALLBACK ], $element_type);
				}
			}

			$field_container_style = '';
			if ($field_settings[ Field_Settings::SETTING_WIDTH ]) {
				$field_container_style .= sprintf(
					'max-width:%s;',
					$field_settings[ Field_Settings::SETTING_WIDTH ]
				);
			}

			$element_args[ Element::TEMPLATE ] = $this->element_template;

			$element_args = wp_parse_args(
				$element_args,
				$field_settings
			);

			$element = Element_Factory::make_element(
				$element_type,
				$element_args
			);

			$element->set_template_arg(self::TEMPL_ARG_ELEMENT_CONTAINER_STYLE, $element_container_style);
			$element->set_template_arg(self::TEMPL_ARG_FIELD_CONTAINER_STYLE, $field_container_style);
			$element->set_template_arg('element_key', $field_id);

			if ($element_type == Elements::RICH_TEXT) {
				/**
				 * @var $element Rich_Text
				 */
				$element->set_editor_class(self::JS_VALIDATION_CLASS);
			} else if ($element_type != Elements::MEDIA_FILE) {
				$element->append_attribute('class', ' ' . self::JS_VALIDATION_CLASS);
			}

			$this->add_validators_to_element($element, $field_settings);

			$this->add_sanitizers_to_element($element, $field_settings);

			$this->add_element($element, $field_id);
		}

		$this->add_element(
			new Action_Element(
				array(
					Element::KEY                   => 'action_element_key',
					Action_Element::FORM_ID        => $this->form_db_id,
					Action_Element::CURRENT_VALUES => $this->current_values
				)
			)
		);

		$this->add_google_recaptcha_element();

		$this->add_element(
			Element_Factory::make_element(
				Elements::HIDDEN,
				array(
					Element::KEY   => 'ID',
					Element::VALUE => $this->get_post_field('ID')
				)
			),
			Post_Fields::FIELD_POST_ID
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::HIDDEN,
				array(
					Element::KEY   => Post_Fields::FIELD_POST_TYPE,
					Element::VALUE => $this->post_type
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::HIDDEN,
				array(
					Element::KEY   => self::ELEM_KEY_FORM_DB_ID,
					Element::VALUE => $this->form_db_id
				)
			),
			null,
			false
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::HIDDEN,
				array(
					Element::KEY   => 'action',
					Element::VALUE => Frontend_Form_Ajax::ACTION
				)
			),
			null,
			false
		);

		$this->add_buttons();
	}

	private function parse_fallback_value($fallback_value, $element_type)
	{
		// TODO: What if the field only allows single selection but the fallback value has multiple items in it.
		if ($element_type == Elements::SELECT) {
			$fallback_value = explode(
				',',
				$fallback_value
			);
		}
		return $fallback_value;
	}

	/**
	 * Adds validator objects to an element.
	 *
	 * The validators used by this plugin are of two types:
	 * 1) Some take only a single argument which is the error message. In the field settings these validators are represented by a checkbox so the value is boolean.
	 * 2) Others take two arguments. The first is specific to the validator e.g. word count (not a boolean). The second is error message.
	 *
	 * This function works under these two assumptions.
	 *
	 * @param $element Element
	 * @param $field_settings
	 */
	private function add_validators_to_element($element, $field_settings)
	{
		foreach (\WPGurus\Forms\Constants\Validators::values() as $validator_id) {
			if (isset($field_settings[ $validator_id ])) {
				$validator_arg = $field_settings[ $validator_id ];
				$validator_disabled = !$validator_arg;
				if ($validator_disabled) {
					continue;
				}
				$constructor_args = array();

				$validator_message = isset($this->messages[ $validator_id ]) ? $this->messages[ $validator_id ] : '';

				if (!is_bool($validator_arg)) {
					$constructor_args[] = $field_settings[ $validator_id ];
					$validator_message = sprintf($validator_message, $validator_arg);
				}

				$constructor_args[] = $validator_message;

				$validator = \WPGurus\Forms\Factories\Validator_Factory::make_validator($validator_id, $constructor_args);
				$element->add_validator(
					$validator
				);
			}
		}
	}

	/**
	 * @param $element Element
	 * @param $field_settings
	 */
	private function add_sanitizers_to_element($element, $field_settings)
	{
		foreach (\WPGurus\Forms\Constants\Sanitizers::values() as $sanitizer) {
			if (isset($field_settings[ $sanitizer ])) {
				$element->add_sanitizer(
					\WPGurus\Forms\Factories\Sanitizer_Factory::make_sanitizer($sanitizer, $field_settings[ $sanitizer ])
				);
			}
		}
	}

	private function get_post_field($field, $default = '')
	{
		return isset($this->current_values[ $field ]) ? $this->current_values[ $field ] : $default;
	}

	private function get_thumb_id($default = '')
	{
		return isset($this->current_values[ self::POST_FIELD_ID ]) ? get_post_thumbnail_id($this->current_values[ self::POST_FIELD_ID ]) : $default;
	}

	private function get_supported_formats()
	{
		// This doesn't work in all themes but since we are calling the is_field_supported function before this, its okay.
		$supported_formats = get_theme_support('post-formats');
		$supported_formats = $supported_formats[0];
		$formats = array();
		foreach ($supported_formats as $format) {
			$formats[ self::PREFIX_POST_FORMAT . $format ] = get_post_format_string($format);
		}
		return array_merge(
			array('' => ''),
			$formats
		);
	}

	private function get_post_terms($taxonomy, $args, $default = '')
	{
		return isset($this->current_values[ self::POST_FIELD_ID ]) ? wp_get_post_terms($this->current_values[ self::POST_FIELD_ID ], $taxonomy, $args) : $default;
	}

	function get_post_meta($field, $default = '')
	{
		return isset($this->current_values[ self::POST_FIELD_ID ]) ? get_post_meta($this->current_values[ self::POST_FIELD_ID ], $field, true) : $default;
	}

	private function get_term_args($field_settings)
	{
		return array(
			'fields'     => 'id=>name',
			'hide_empty' => $field_settings[ Field_Settings::SETTING_HIDE_EMPTY_TAXONOMIES ],
			'include'    => $field_settings[ Field_Settings::SETTING_INCLUDE_TAXONOMIES ],
			'exclude'    => $field_settings[ Field_Settings::SETTING_EXCLUDE_TAXONOMIES ]
		);
	}

	private function get_non_hierarchical_term_choices($taxonomy, $field_settings)
	{
		$terms = get_terms(
			$taxonomy,
			$this->get_term_args($field_settings)
		);
		$term_choices = array();

		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term_id => $term_name ) {
				$term_choices[ $term_name ] = $term_name;
			}
		}

		if (!$this->is_field_element_multiple_select($field_settings)) {
			$term_choices = array('' => '') + $term_choices;
		}

		return $term_choices;
	}

	private function get_spaces($times)
	{
		$spaces = '';
		for ($i = 0; $i < $times; $i++) {
			$spaces .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}

		return $spaces;
	}

	private function get_hierarchical_terms($taxonomy, $args, $level = -1)
	{
		$level++;
		$args['parent'] = isset($args['parent']) ? $args['parent'] : 0;
		$terms = get_terms($taxonomy, $args);
		$hierarchical_terms = array();

		foreach ($terms as $term_id => $term) {
			$hierarchical_terms[ $term_id ] = $this->get_spaces($level) . $term;
			$args['parent'] = $term_id;
			$hierarchical_terms = $hierarchical_terms + $this->get_hierarchical_terms($taxonomy, $args, $level);
		}

		return $hierarchical_terms;
	}

	private function get_hierarchical_term_choices($taxonomy, $field_settings)
	{
		$args = $this->get_term_args($field_settings);
		if ($field_settings[ Field_Settings::SETTING_DISPLAY_HIERARCHICALLY ]) {
			$term_choices = $this->get_hierarchical_terms(
				$taxonomy,
				$args
			);
		} else {
			$term_choices = get_terms(
				$taxonomy,
				$args
			);
		}

		if (!$this->is_field_element_multiple_select($field_settings)) {
			$term_choices = array('' => '') + $term_choices;
		}

		return $term_choices;
	}

	private function add_google_recaptcha_element()
	{
		if (
			!$this->settings[ Form_Settings::SETTING_CAPTCHA_ENABLED ]
			|| \WPFEPP\current_user_can($this->settings[ Form_Settings::SETTING_NO_RESTRICTIONS ])
		) {
			return;
		}

		$recaptcha_settings = get_option(Option_Ids::OPTION_RECAPTCHA_SETTINGS);
		$settings_container = new reCaptcha_Settings_Container();
		$recaptcha_settings = array_merge(
			array(
				Element::TEMPLATE               => $this->element_template,
				Google_reCaptcha::ERROR_MESSAGE => $this->messages[ Frontend_Form_Messages::SETTING_RECAPTCHA_ERROR ]
			),
			$settings_container->parse_values($recaptcha_settings)
		);

		// TODO: Use element factory here.
		$element = new \WPGurus\Forms\Elements\Google_reCaptcha(
			$recaptcha_settings
		);
		$element->set_template_arg('element_container_style', 'width: 320px;');
		$element->set_template_arg('element_key', '');
		$this->add_element(
			$element,
			null,
			false
		);
	}

	/**
	 * @return string
	 */
	private function id_prefix()
	{
		return 'frontend-form-' . $this->form_db_id . '-';
	}

	public function render()
	{
		$this->render_template(
			$this->container_template,
			array(
				self::TEMPLATE_ARG_ELEMENTS => $this->get_elements()
			)
		);

		if (!wp_style_is(\WPGurus\Forms\Constants\Assets::FONT_AWESOME_CSS)) {
			wp_enqueue_style(\WPFEPP\Constants\Assets::FONT_AWESOME_CSS);
		}
	}

	private function add_buttons()
	{
		$this->add_element(
			Element_Factory::make_element(
				Elements::BUTTON,
				array(
					Element::KEY        => self::ELEM_KEY_SUBMIT_BUTTON,
					Element::VALUE      => self::SUBMIT_BUTTON_VAL_SUBMIT,
					Button::BUTTON_TEXT => __('Submit', 'frontend-publishing-pro')
				)
			),
			null,
			false,
			$this->id_prefix() . 'main-'
		);

		if ($this->settings[ Form_Settings::SETTING_ENABLE_DRAFTS ]) {
			$this->add_element(
				Element_Factory::make_element(
					Elements::BUTTON,
					array(
						Element::KEY        => self::ELEM_KEY_SUBMIT_BUTTON,
						Element::VALUE      => self::SUBMIT_BUTTON_VAL_DRAFT,
						Element::ATTRIBUTES => array(
							'formnovalidate' => 'formnovalidate'
						),
						Button::BUTTON_TEXT => __('Save Draft', 'frontend-publishing-pro')
					)
				),
				null,
				false,
				$this->id_prefix() . 'secondary-'
			);
		}
	}

	private function is_field_element_multiple_select($field_settings)
	{
		return in_array($field_settings[ Field_Settings::SETTING_ELEMENT ], array(Elements::SELECT, Elements::SELECT_WITH_SEARCH))
		&& $field_settings[ Field_Settings::SETTING_MULTIPLE ];
	}
}