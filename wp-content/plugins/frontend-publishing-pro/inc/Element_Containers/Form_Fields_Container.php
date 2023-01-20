<?php

namespace WPFEPP\Element_Containers;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Custom_Field_Locations;
use WPFEPP\Constants\Post_Field_Settings as Field_Settings;
use WPFEPP\Constants\Post_Fields;
use WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Constants\Sanitizers;
use WPGurus\Forms\Constants\Validators;
use WPGurus\Forms\Element;
use WPGurus\Forms\Element_Container;
use WPGurus\Forms\Elements\Select;
use WPGurus\Forms\Factories\Element_Factory;
use WPGurus\Forms\Utils;
use WPGurus\Utils\Array_Utils;

class Form_Fields_Container extends Element_Container
{
	const COMMON_TYPE = 'type';
	const COMMON_ARGS = 'args';

	/**
	 * The post type for which we need to create elements. It is needed because each post type has its own taxonomies.
	 * @var string
	 */
	private $post_type;

	private $template_args;

	private $current_values;

	private $element_template;
	private $conditional_element_template;
	private $hidden_element_template;
	private $container_template;

	const TEMPL_ARG_FIELDS = 'fields';
	const TEMPL_ARG_FIELD_ID = 'field_id';
	const TEMPL_ARG_FIELD_TITLE = 'field_title';
	const TEMPL_ARG_FIELD_SUPPORTED = 'is_supported';
	const TEMPL_ARG_FIELD_CUSTOM = 'is_custom_field';
	const TEMPL_ARG_FIELD_GEN_ELEMENTS = 'general_elements';
	const TEMPL_ARG_FIELD_VALIDATOR_ELEMENTS = 'validator_elements';
	const TEMPL_ARG_FIELD_SANITIZER_ELEMENTS = 'sanitizer_elements';

	const DEPENDENCY_ELEMENT = 'dependency_element';
	const DEPENDENCY_VALUE = 'dependency_value';

	/**
	 * Initializes the object.
	 *
	 * @param string $post_type The post type for which we need to generate elements.
	 * @param array $current_values The current values to display in the fields.
	 */
	function __construct($post_type, $current_values = array())
	{
		$this->current_values = $current_values;
		$this->post_type = $post_type;
		$this->element_template = WPFEPP_ELEMENT_TEMPLATES_DIR . 'table-element.php';
		$this->conditional_element_template = WPFEPP_ELEMENT_TEMPLATES_DIR . 'conditional-table-element.php';
		$this->hidden_element_template = WPFEPP_ELEMENT_TEMPLATES_DIR . 'hidden-table-element.php';
		$this->container_template = WPFEPP_ELEMENT_CONTAINER_TEMPLATES_DIR . 'form-fields.php';

		$this->build_form_elements();
	}

	/**
	 * Adds elements to the container.
	 */
	private function build_form_elements()
	{
		/**
		 * @var array $fields Each item in this array contains all the elements for a single widget. This includes the renderables as well as the data elements.
		 */
		$fields = array();

		$fields[ Post_Fields::FIELD_TITLE ] = $this->get_required_elements(
			Post_Fields::FIELD_TITLE,
			__('Title', 'frontend-publishing-pro'),
			array(
				Field_Settings::SETTING_ENABLED     => true,
				Field_Settings::SETTING_WIDTH       => '',
				Field_Settings::SETTING_PREFIX_TEXT => '',
				Field_Settings::SETTING_FALLBACK    => ''
			)
		);

		$fields[ Post_Fields::FIELD_CONTENT ] = $this->get_required_elements(
			Post_Fields::FIELD_CONTENT,
			__('Content', 'frontend-publishing-pro'),
			array(
				Field_Settings::SETTING_ENABLED            => true,
				Field_Settings::SETTING_WIDTH              => '',
				Field_Settings::SETTING_ELEMENT            => array(
					Element::VALUE  => Elements::RICH_TEXT,
					Select::CHOICES => array(
						Elements::RICH_TEXT           => __('Rich Text', 'frontend-publishing-pro'),
						Elements::SANDBOXED_RICH_TEXT => __('Rich Text (Sandboxed)', 'frontend-publishing-pro'),
						Elements::TEXTAREA            => __('Textarea', 'frontend-publishing-pro')
					)
				),
				Field_Settings::SETTING_RICH_MEDIA_BUTTON  => true,
				Field_Settings::SETTING_RICH_EDITOR_HEIGHT => 300,
				Field_Settings::SETTING_PREFIX_TEXT        => '',
				Field_Settings::SETTING_FALLBACK           => ''
			)
		);

		$fields[ Post_Fields::FIELD_EXCERPT ] = $this->get_required_elements(
			Post_Fields::FIELD_EXCERPT,
			__('Excerpt', 'frontend-publishing-pro'),
			array(
				Field_Settings::SETTING_ENABLED     => true,
				Field_Settings::SETTING_WIDTH       => '',
				Field_Settings::SETTING_PREFIX_TEXT => '',
				Field_Settings::SETTING_FALLBACK    => ''
			)
		);

		$fields[ Post_Fields::FIELD_THUMBNAIL ] = $this->get_required_elements(
			Post_Fields::FIELD_THUMBNAIL,
			__('Thumbnail', 'frontend-publishing-pro'),
			array(
				Field_Settings::SETTING_ENABLED     => true,
				Field_Settings::SETTING_WIDTH       => '',
				Field_Settings::SETTING_ELEMENT     => array(
					Element::VALUE  => Elements::MEDIA_ID,
					Select::CHOICES => array(
						Elements::MEDIA_ID   => __('Media ID', 'frontend-publishing-pro'),
						Elements::MEDIA_FILE => __('Media File', 'frontend-publishing-pro')
					)
				),
				Field_Settings::SETTING_PREFIX_TEXT => '',
				Field_Settings::SETTING_FALLBACK    => ''
			)
		);

		$fields[ Post_Fields::FIELD_POST_FORMAT ] = $this->get_required_elements(
			Post_Fields::FIELD_POST_FORMAT,
			__('Formats', 'frontend-publishing-pro'),
			array(
				Field_Settings::SETTING_ENABLED                       => true,
				Field_Settings::SETTING_WIDTH                         => '',
				Field_Settings::SETTING_ELEMENT                       => array(
					Element::VALUE  => Elements::SELECT,
					Select::CHOICES => array(
						Elements::SELECT             => __('Select', 'frontend-publishing-pro'),
						Elements::SELECT_WITH_SEARCH => __('Advanced Select', 'frontend-publishing-pro')
					)
				),
				Field_Settings::SETTING_SWS_LANGUAGE                  => __('en', 'frontend-publishing-pro'),
				Field_Settings::SETTING_SWS_PLACEHOLDER_TEXT_SINGLE   => __('Select an Option', 'frontend-publishing-pro'),
				Field_Settings::SETTING_SWS_PLACEHOLDER_TEXT_MULTIPLE => __('Select Some Options', 'frontend-publishing-pro'),
				Field_Settings::SETTING_SWS_ALLOW_SINGLE_DESELECT     => false,
				Field_Settings::SETTING_SWS_DISABLE_SEARCH            => false,
				Field_Settings::SETTING_SWS_DISABLE_SEARCH_THRESHOLD  => 0,
				Field_Settings::SETTING_SWS_MAX_SELECTED_OPTIONS      => '',
				Field_Settings::SETTING_PREFIX_TEXT                   => '',
				Field_Settings::SETTING_FALLBACK                      => ''
			)
		);

		if ($this->post_type) {
			$taxonomies = get_object_taxonomies($this->post_type, 'objects');

			foreach ($taxonomies as $taxonomy) {
				if ($taxonomy->name != 'post_format') {

					$tax_field_id = $taxonomy->name;
					$tax_field_title = $taxonomy->label;

					if ($taxonomy->hierarchical) {

						$fields[ $tax_field_id ] = $this->get_required_elements(
							$tax_field_id,
							$tax_field_title,
							array(
								Field_Settings::SETTING_ENABLED                       => true,
								Field_Settings::SETTING_WIDTH                         => '',
								Field_Settings::SETTING_ELEMENT                       => array(
									Element::VALUE  => Elements::SELECT,
									Select::CHOICES => array(
										Elements::SELECT             => __('Select', 'frontend-publishing-pro'),
										Elements::SELECT_WITH_SEARCH => __('Advanced Select', 'frontend-publishing-pro')
									)
								),
								Field_Settings::SETTING_SWS_LANGUAGE                  => __('en', 'frontend-publishing-pro'),
								Field_Settings::SETTING_SWS_ALLOW_ADDITION            => false,
								Field_Settings::SETTING_SWS_PLACEHOLDER_TEXT_SINGLE   => __('Select an Option', 'frontend-publishing-pro'),
								Field_Settings::SETTING_SWS_PLACEHOLDER_TEXT_MULTIPLE => __('Select Some Options', 'frontend-publishing-pro'),
								Field_Settings::SETTING_SWS_ALLOW_SINGLE_DESELECT     => false,
								Field_Settings::SETTING_SWS_DISABLE_SEARCH            => false,
								Field_Settings::SETTING_SWS_DISABLE_SEARCH_THRESHOLD  => 0,
								Field_Settings::SETTING_SWS_MAX_SELECTED_OPTIONS      => '',
								Field_Settings::SETTING_MULTIPLE                      => true,
								Field_Settings::SETTING_HIDE_EMPTY_TAXONOMIES         => false,
								Field_Settings::SETTING_EXCLUDE_TAXONOMIES            => '',
								Field_Settings::SETTING_INCLUDE_TAXONOMIES            => '',
								Field_Settings::SETTING_DISPLAY_HIERARCHICALLY        => false,
								Field_Settings::SETTING_PREFIX_TEXT                   => '',
								Field_Settings::SETTING_FALLBACK                      => '',
								Field_Settings::SETTING_TYPE                          => Post_Fields::FIELD_HIERARCHICAL_TAX,
							)
						);
					} else {
						$fields[ $tax_field_id ] = $this->get_required_elements(
							$tax_field_id,
							$tax_field_title,
							array(
								Field_Settings::SETTING_ENABLED                       => true,
								Field_Settings::SETTING_WIDTH                         => '',
								Field_Settings::SETTING_ELEMENT                       => array(
									Element::VALUE  => Elements::TEXT,
									Select::CHOICES => array(
										Elements::TEXT               => __('Text', 'frontend-publishing-pro'),
										Elements::SELECT             => __('Select', 'frontend-publishing-pro'),
										Elements::SELECT_WITH_SEARCH => __('Advanced Select', 'frontend-publishing-pro')
									)
								),
								Field_Settings::SETTING_SWS_LANGUAGE                  => __('en', 'frontend-publishing-pro'),
								Field_Settings::SETTING_SWS_ALLOW_ADDITION            => false,
								Field_Settings::SETTING_SWS_PLACEHOLDER_TEXT_SINGLE   => __('Select an Option', 'frontend-publishing-pro'),
								Field_Settings::SETTING_SWS_PLACEHOLDER_TEXT_MULTIPLE => __('Select Some Options', 'frontend-publishing-pro'),
								Field_Settings::SETTING_SWS_ALLOW_SINGLE_DESELECT     => false,
								Field_Settings::SETTING_SWS_DISABLE_SEARCH            => false,
								Field_Settings::SETTING_SWS_DISABLE_SEARCH_THRESHOLD  => 0,
								Field_Settings::SETTING_SWS_MAX_SELECTED_OPTIONS      => '',
								Field_Settings::SETTING_MULTIPLE                      => true,
								Field_Settings::SETTING_HIDE_EMPTY_TAXONOMIES         => false,
								Field_Settings::SETTING_EXCLUDE_TAXONOMIES            => '',
								Field_Settings::SETTING_INCLUDE_TAXONOMIES            => '',
								Field_Settings::SETTING_PREFIX_TEXT                   => '',
								Field_Settings::SETTING_FALLBACK                      => '',
								Field_Settings::SETTING_TYPE                          => Post_Fields::FIELD_NON_HIERARCHICAL_TAX
							)
						);
					}
				}
			}
		}

		$ordered_field_keys = array();

		// Traverse the current values and add the elements for each field.
		foreach ($this->current_values as $current_field_key => $current_field_value) {
			$ordered_field_keys[] = $current_field_key;

			if (isset($fields[ $current_field_key ])) {
				$this->add_elements($fields[ $current_field_key ]);
				// Unset the value since it has already been used and we don't want the second foreach loop to use it again.
				unset($fields[ $current_field_key ]);
			} else {
				$this->add_elements(
					$this->get_custom_field_elements(
						$current_field_key,
						Array_Utils::get($current_field_value, Field_Settings::SETTING_LABEL)
					)
				);
			}
		}

		// Add the elements for the leftover fields (if any).
		foreach ($fields as $field_key => $field) {
			$ordered_field_keys[] = $field_key;

			$this->add_elements($field);
		}

		$this->reorder_template_arg_fields($ordered_field_keys);
	}

	/**
	 * @param $field_id string A string ID that will be included in the key of each element belonging to this widget.
	 * @param $field_title string The widget title.
	 * @param $required_elements array An array in which each index indicates which element to use from the common elements array and the respective value is used as a default fallback if nothing can be found in the current values field.
	 * @param bool|false $is_custom boolean Indicates whether this is a custom field.
	 * @return array
	 */
	function get_required_elements($field_id, $field_title, $required_elements, $is_custom = false)
	{
		$template = $this->element_template;
		$hidden_template = $this->hidden_element_template;
		$conditional_template = $this->conditional_element_template;

		// There are some elements that are required by almost every widget so we will add their indices to the $required_elements array here
		$required_elements = wp_parse_args(
			$required_elements,
			array(
				Field_Settings::SETTING_LABEL => $field_title,
				Field_Settings::SETTING_TYPE  => $field_id
			)
		);

		/*
		 * The following array contains sub-arrays. Each sub-array can be used to initialize an element.
		 * The key of each sub-array is a constant. required_elements is just an array of these constants.
		 */
		$common_elements = array(

			Field_Settings::SETTING_ENABLED => array(
				self::COMMON_TYPE => Elements::CHECKBOX,
				self::COMMON_ARGS => array(
					Element::LABEL    => __('Enabled', 'frontend-publishing-pro'),
					Element::TEMPLATE => $template
				)
			),

			Field_Settings::SETTING_LABEL => array(
				self::COMMON_TYPE => Elements::TEXT,
				self::COMMON_ARGS => array(
					Element::LABEL      => __('Label', 'frontend-publishing-pro'),
					Element::TEMPLATE   => $template,
					Element::SANITIZERS => array(
						new \WPGurus\Forms\Sanitizers\Strip_HTML()
					)
				)
			),

			Field_Settings::SETTING_WIDTH => array(
				self::COMMON_TYPE => Elements::TEXT,
				self::COMMON_ARGS => array(
					Element::LABEL      => __('Field Width', 'frontend-publishing-pro'),
					Element::POSTFIX    => __('Width in pixels or percentage. (e.g. 300px)', 'frontend-publishing-pro'),
					Element::TEMPLATE   => $template,
					Element::SANITIZERS => array(
						new \WPGurus\Forms\Sanitizers\Strip_HTML()
					)
				)
			),

			Field_Settings::SETTING_PREFIX_TEXT => array(
				self::COMMON_TYPE => Elements::TEXTAREA,
				self::COMMON_ARGS => array(
					Element::LABEL    => __('Prefix Text', 'frontend-publishing-pro'),
					Element::POSTFIX  => __('The text that you want to place above this field.', 'frontend-publishing-pro'),
					Element::TEMPLATE => $template
				)
			),

			Field_Settings::SETTING_FALLBACK => array(
				self::COMMON_TYPE => Elements::TEXTAREA,
				self::COMMON_ARGS => array(
					Element::LABEL    => __('Fallback Value', 'frontend-publishing-pro'),
					// TODO: These instructions should be based on the type of field. Something like this: For select add a comma seperated list of keys ..
					Element::POSTFIX  => __('The value to use when this field is disabled. You can leave this empty.', 'frontend-publishing-pro'),
					Element::TEMPLATE => $template
				)
			),

			Field_Settings::SETTING_TYPE              => array(
				self::COMMON_TYPE => Elements::HIDDEN,
				self::COMMON_ARGS => array(
					Element::TEMPLATE => $hidden_template
				)
			),

			// TODO: In addition to media buttons we also need other rich text options
			Field_Settings::SETTING_RICH_MEDIA_BUTTON => array(
				self::COMMON_TYPE => Elements::CHECKBOX,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Display Media Button', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('The media button will show up only if the user has permission to upload media.', 'frontend-publishing-pro'),
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::RICH_TEXT . ';' . Elements::SANDBOXED_RICH_TEXT,
					Element::TEMPLATE        => $conditional_template
				)
			),

			Field_Settings::SETTING_RICH_EDITOR_HEIGHT => array(
				self::COMMON_TYPE => Elements::TEXT,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Editor Height', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('Height of the editor e.g. 300 (unit not necessary)', 'frontend-publishing-pro'),
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::RICH_TEXT . ';' . Elements::SANDBOXED_RICH_TEXT,
					Element::TEMPLATE        => $conditional_template
				)
			),

			Field_Settings::SETTING_ELEMENT => array(
				self::COMMON_TYPE => Elements::SELECT,
				self::COMMON_ARGS => array(
					Element::LABEL    => __('Element', 'frontend-publishing-pro'),
					Element::TEMPLATE => $template
				)
			),

			Field_Settings::SETTING_MULTIPLE => array(
				self::COMMON_TYPE => Elements::CHECKBOX,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Allow Multiple Selections', 'frontend-publishing-pro'),
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::SELECT . ';' . Elements::SELECT_WITH_SEARCH,
					Element::TEMPLATE        => $conditional_template
				)
			),

			Field_Settings::SETTING_HIDE_EMPTY_TAXONOMIES => array(
				self::COMMON_TYPE => Elements::CHECKBOX,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Hide Empty', 'frontend-publishing-pro'),
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::SELECT . ';' . Elements::SELECT_WITH_SEARCH,
					Element::TEMPLATE        => $conditional_template
				)
			),

			Field_Settings::SETTING_EXCLUDE_TAXONOMIES => array(
				self::COMMON_TYPE => Elements::TEXT,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Exclude', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('A comma-seperated list of term IDs that you want to exclude.', 'frontend-publishing-pro'),
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::SELECT . ';' . Elements::SELECT_WITH_SEARCH,
					Element::TEMPLATE        => $conditional_template,
					Element::SANITIZERS      => array(
						new \WPGurus\Forms\Sanitizers\Strip_HTML()
					)
				)
			),

			Field_Settings::SETTING_INCLUDE_TAXONOMIES => array(
				self::COMMON_TYPE => Elements::TEXT,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Include', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('A comma-seperated list of term IDs that you want to include.', 'frontend-publishing-pro'),
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::SELECT . ';' . Elements::SELECT_WITH_SEARCH,
					Element::TEMPLATE        => $conditional_template,
					Element::SANITIZERS      => array(
						new \WPGurus\Forms\Sanitizers\Strip_HTML()
					)
				)
			),

			Field_Settings::SETTING_DISPLAY_HIERARCHICALLY => array(
				self::COMMON_TYPE => Elements::CHECKBOX,
				self::COMMON_ARGS => array(
					Element::LABEL    => __('Display Terms as Hierarchy', 'frontend-publishing-pro'),
					Element::POSTFIX  => __('In a hierarchy structure, terms (e.g. categories) are followed by their children.', 'frontend-publishing-pro'),
					Element::TEMPLATE => $template
				)
			),

			Field_Settings::SETTING_CHOICES => array(
				self::COMMON_TYPE => Elements::TEXTAREA,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Choices', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('The choices for select and radio elements. One per line. Key value pairs can be added like this: key|Value', 'frontend-publishing-pro'),
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::SELECT . ';' . Elements::SELECT_WITH_SEARCH,
					Element::TEMPLATE        => $conditional_template,
					Element::SANITIZERS      => array(
						new \WPGurus\Forms\Sanitizers\Strip_HTML()
					)
				)
			),

			Field_Settings::SETTING_SAVE_CUSTOM_VAL_TO_META => array(
				self::COMMON_TYPE => Elements::CHECKBOX,
				self::COMMON_ARGS => array(
					Element::LABEL    => __('Save to Post Meta', 'frontend-publishing-pro'),
					Element::POSTFIX  => __('Whether or not the value entered by the user should be saved in the DB as post meta', 'frontend-publishing-pro'),
					Element::TEMPLATE => $template
				)
			),

			Field_Settings::SETTING_CUSTOM_FIELD_LOCATIONS => array(
				self::COMMON_TYPE => Elements::SELECT,
				self::COMMON_ARGS => array(
					Element::LABEL    => __('Automatically Display', 'frontend-publishing-pro'),
					Element::POSTFIX  => __('Select the places where you would like the custom field to be displayed automatically.', 'frontend-publishing-pro'),
					Select::MULTIPLE  => true,
					Select::CHOICES   => array(
						Custom_Field_Locations::ABOVE_POST_CONTENT => __('Above post content', 'frontend-publishing-pro'),
						Custom_Field_Locations::BELOW_POST_CONTENT => __('Below post content', 'frontend-publishing-pro'),
						Custom_Field_Locations::ADMIN_EDIT_PAGE    => __('Admin area edit page', 'frontend-publishing-pro')
					),
					Element::TEMPLATE => $template
				)
			),

			Field_Settings::SETTING_ENABLE_CUSTOM_FIELD_EMBEDS => array(
				self::COMMON_TYPE => Elements::CHECKBOX,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Enable Embeds', 'frontend-publishing-pro'),
					Element::POSTFIX         => sprintf(
						__('When the text from this field is shown in a post, should %s be enabled?', 'frontend-publishing-pro'),
						sprintf('<a target="_blank" href="%s">%s</a>', 'https://codex.wordpress.org/Embeds', __('WP embeds', 'frontend-publishing-pro'))
					),
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::TEXT . ';' . Elements::TEXTAREA . ';' . Elements::RICH_TEXT . ';' . Elements::SANDBOXED_RICH_TEXT,
					Element::TEMPLATE        => $conditional_template
				)
			),

			// Date picker elements
			Field_Settings::SETTING_DATE_PICKER_FORMAT => array(
				self::COMMON_TYPE => Elements::TEXT,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('DateTime Format', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('The format of the datetime stamp', 'frontend-publishing-pro'),
					Element::TEMPLATE        => $conditional_template,
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::DATE_PICKER,
					Element::SANITIZERS      => array(
						new \WPGurus\Forms\Sanitizers\Strip_HTML()
					)
				)
			),

			Field_Settings::SETTING_DATE_PICKER_LANGUAGE => array(
				self::COMMON_TYPE => Elements::TEXT,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Date Picker Language', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('Language code for the date picker jQuery plugin', 'frontend-publishing-pro'),
					Element::TEMPLATE        => $conditional_template,
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::DATE_PICKER,
					Element::SANITIZERS      => array(
						new \WPGurus\Forms\Sanitizers\Strip_HTML()
					)
				)
			),

			Field_Settings::SETTING_DATE_PICKER_DATE_ENABLED => array(
				self::COMMON_TYPE => Elements::CHECKBOX,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Date Enabled?', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('Enable date in the date picker plugin', 'frontend-publishing-pro'),
					Element::TEMPLATE        => $conditional_template,
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::DATE_PICKER,
					Element::SANITIZERS      => array(
						new \WPGurus\Forms\Sanitizers\Strip_HTML()
					)
				)
			),

			Field_Settings::SETTING_DATE_PICKER_TIME_ENABLED => array(
				self::COMMON_TYPE => Elements::CHECKBOX,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Time Enabled?', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('Enable time in the date picker plugin', 'frontend-publishing-pro'),
					Element::TEMPLATE        => $conditional_template,
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::DATE_PICKER,
					Element::SANITIZERS      => array(
						new \WPGurus\Forms\Sanitizers\Strip_HTML()
					)
				)
			),

			Field_Settings::SETTING_DATE_PICKER_THEME => array(
				self::COMMON_TYPE => Elements::SELECT,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Theme', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('Date picker theme', 'frontend-publishing-pro'),
					Element::TEMPLATE        => $conditional_template,
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::DATE_PICKER,
					Select::CHOICES          => array(
						'dark'  => __('Dark', 'frontend-publishing-pro'),
						'light' => __('Light', 'frontend-publishing-pro')
					)
				)
			),

			// Star rating element
			Field_Settings::SETTING_RATING_NUMBER     => array(
				self::COMMON_TYPE => Elements::TEXT,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Star Count', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('Number of stars to display', 'frontend-publishing-pro'),
					Element::TEMPLATE        => $conditional_template,
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::STAR_RATING
				)
			),

			// Select with search
			Field_Settings::SETTING_SWS_LANGUAGE      => array(
				self::COMMON_TYPE => Elements::SELECT,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Language', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('Choose a language for the jQuery select library used by this element', 'frontend-publishing-pro'),
					Select::CHOICES          => array(
						'ar'      => 'ar',
						'az'      => 'az',
						'bg'      => 'bg',
						'ca'      => 'ca',
						'cs'      => 'cs',
						'da'      => 'da',
						'de'      => 'de',
						'el'      => 'el',
						'en'      => 'en',
						'es'      => 'es',
						'et'      => 'et',
						'eu'      => 'eu',
						'fa'      => 'fa',
						'fi'      => 'fi',
						'fr'      => 'fr',
						'gl'      => 'gl',
						'he'      => 'he',
						'hi'      => 'hi',
						'hr'      => 'hr',
						'hu'      => 'hu',
						'id'      => 'id',
						'is'      => 'is',
						'it'      => 'it',
						'ja'      => 'ja',
						'km'      => 'km',
						'ko'      => 'ko',
						'lt'      => 'lt',
						'lv'      => 'lv',
						'mk'      => 'mk',
						'ms'      => 'ms',
						'nb'      => 'nb',
						'nl'      => 'nl',
						'pl'      => 'pl',
						'pt-BR'   => 'pt-BR',
						'pt'      => 'pt',
						'ro'      => 'ro',
						'ru'      => 'ru',
						'sk'      => 'sk',
						'sl'      => 'sl',
						'sr-Cyrl' => 'sr-Cyrl',
						'sr'      => 'sr',
						'sv'      => 'sv',
						'th'      => 'th',
						'tr'      => 'tr',
						'uk'      => 'uk',
						'vi'      => 'vi',
						'zh-CN'   => 'zh-CN',
						'zh-TW'   => 'zh-TW'
					),
					Element::TEMPLATE        => $conditional_template,
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::SELECT_WITH_SEARCH
				)
			),

			Field_Settings::SETTING_SWS_ALLOW_ADDITION => array(
				self::COMMON_TYPE => Elements::CHECKBOX,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Allow Addition of New Items', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('Should the user be able to add items that are not already available in the dropdown?', 'frontend-publishing-pro'),
					Element::TEMPLATE        => $conditional_template,
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::SELECT_WITH_SEARCH
				)
			),

			Field_Settings::SETTING_SWS_PLACEHOLDER_TEXT_SINGLE => array(
				self::COMMON_TYPE => Elements::TEXT,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Placeholder Text for Single Select', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('The text to display when nothing is selected and the multi-select is disabled', 'frontend-publishing-pro'),
					Element::TEMPLATE        => $conditional_template,
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::SELECT_WITH_SEARCH
				)
			),

			Field_Settings::SETTING_SWS_PLACEHOLDER_TEXT_MULTIPLE => array(
				self::COMMON_TYPE => Elements::TEXT,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Placeholder Text for Multi-Select', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('The text to display when nothing is selected and the multi-select is enabled', 'frontend-publishing-pro'),
					Element::TEMPLATE        => $conditional_template,
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::SELECT_WITH_SEARCH
				)
			),

			Field_Settings::SETTING_SWS_ALLOW_SINGLE_DESELECT => array(
				self::COMMON_TYPE => Elements::CHECKBOX,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Allow Single Deselect', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('When multi-select is disabled, should the user have the ability to deselect the currently selected value?', 'frontend-publishing-pro'),
					Element::TEMPLATE        => $conditional_template,
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::SELECT_WITH_SEARCH
				)
			),

			Field_Settings::SETTING_SWS_DISABLE_SEARCH => array(
				self::COMMON_TYPE => Elements::CHECKBOX,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Disable search', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('Whether or not to disable the search feature', 'frontend-publishing-pro'),
					Element::TEMPLATE        => $conditional_template,
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::SELECT_WITH_SEARCH
				)
			),

			Field_Settings::SETTING_SWS_DISABLE_SEARCH_THRESHOLD => array(
				self::COMMON_TYPE => Elements::TEXT,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Disable Search Threshold', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('If the number of choices is less than this value then the search feature will be disabled', 'frontend-publishing-pro'),
					Element::TEMPLATE        => $conditional_template,
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::SELECT_WITH_SEARCH
				)
			),

			Field_Settings::SETTING_SWS_MAX_SELECTED_OPTIONS => array(
				self::COMMON_TYPE => Elements::TEXT,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Maximum Selected Options', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('Maximum number of options that the user is allowed to select in case of multi-select', 'frontend-publishing-pro'),
					Element::TEMPLATE        => $conditional_template,
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::SELECT_WITH_SEARCH
				)
			),

			Field_Settings::SETTING_MEDIA_ITEMS_DISPLAY_COLUMNS => array(
				self::COMMON_TYPE => Elements::SELECT,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Columns', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('When the gallery is shown in a post, how many columns should it have?', 'frontend-publishing-pro'),
					Element::TEMPLATE        => $conditional_template,
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::MEDIA_IDS,
					Select::CHOICES          => array(
						'1' => __('1', 'frontend-publishing-pro'),
						'2' => __('2', 'frontend-publishing-pro'),
						'3' => __('3', 'frontend-publishing-pro'),
						'4' => __('4', 'frontend-publishing-pro'),
						'5' => __('5', 'frontend-publishing-pro')
					)
				)
			),

			Field_Settings::SETTING_MEDIA_ITEMS_DISPLAY_SIZE => array(
				self::COMMON_TYPE => Elements::SELECT,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Size', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('When the gallery is shown in a post, what should be the size of each image?', 'frontend-publishing-pro'),
					Element::TEMPLATE        => $conditional_template,
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::MEDIA_IDS,
					Select::CHOICES          => array(
						'thumbnail' => __('Thumbnail', 'frontend-publishing-pro'),
						'medium'    => __('Medium', 'frontend-publishing-pro'),
						'large'     => __('Large', 'frontend-publishing-pro'),
						'full'      => __('Full', 'frontend-publishing-pro')
					)
				)
			),

			Field_Settings::SETTING_MEDIA_ITEMS_DISPLAY_LINK => array(
				self::COMMON_TYPE => Elements::SELECT,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Link', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('When the gallery is shown in a post, what should the images link to?', 'frontend-publishing-pro'),
					Element::TEMPLATE        => $conditional_template,
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::MEDIA_IDS,
					Select::CHOICES          => array(
						''     => __('Attachment Page', 'frontend-publishing-pro'),
						'file' => __('Image File', 'frontend-publishing-pro'),
						'none' => __('None', 'frontend-publishing-pro')
					)
				)
			),

			Field_Settings::SETTING_MEDIA_ITEM_DISPLAY_SIZE => array(
				self::COMMON_TYPE => Elements::SELECT,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Size', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('When the image is shown in a post, what should be its size?', 'frontend-publishing-pro'),
					Element::TEMPLATE        => $conditional_template,
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::MEDIA_ID . ';' . Elements::MEDIA_FILE,
					Select::CHOICES          => array(
						'thumbnail' => __('Thumbnail', 'frontend-publishing-pro'),
						'medium'    => __('Medium', 'frontend-publishing-pro'),
						'large'     => __('Large', 'frontend-publishing-pro'),
						'full'      => __('Full', 'frontend-publishing-pro')
					)
				)
			),

			Field_Settings::SETTING_MEDIA_URL_DISPLAY_WIDTH => array(
				self::COMMON_TYPE => Elements::TEXT,
				self::COMMON_ARGS => array(
					Element::LABEL           => __('Maximum Image Width', 'frontend-publishing-pro'),
					Element::POSTFIX         => __('When the image is shown in a post, what should be its maximum width in px?', 'frontend-publishing-pro'),
					Element::TEMPLATE        => $conditional_template,
					self::DEPENDENCY_ELEMENT => Field_Settings::SETTING_ELEMENT,
					self::DEPENDENCY_VALUE   => Elements::MEDIA_URL
				)
			)
		);

		/**
		 * @var $general_elements Element[]
		 */
		$general_elements = array();

		foreach ($required_elements as $req_element_key => $req_element_value) {
			if (isset($common_elements[ $req_element_key ])) {
				$element = $this->prepare_element(
					$req_element_key,
					$common_elements[ $req_element_key ],
					$field_id,
					$req_element_value
				);

				$general_elements[ $field_id . $req_element_key ] = $element;
			}
		}

		$this->add_conditional_logic($field_id, $required_elements, $common_elements, $general_elements);

		$validator_elements = $this->get_validators($field_id);

		$sanitizer_elements = $this->get_sanitizers($field_id);

		$this->template_args[ self::TEMPL_ARG_FIELDS ][ $field_id ] = array(
			self::TEMPL_ARG_FIELD_ID                 => $field_id,
			self::TEMPL_ARG_FIELD_TITLE              => $field_title,
			self::TEMPL_ARG_FIELD_CUSTOM             => $is_custom,
			self::TEMPL_ARG_FIELD_SUPPORTED          => \WPFEPP\is_field_supported($field_id, $this->post_type),
			self::TEMPL_ARG_FIELD_GEN_ELEMENTS       => $general_elements,
			self::TEMPL_ARG_FIELD_VALIDATOR_ELEMENTS => $validator_elements,
			self::TEMPL_ARG_FIELD_SANITIZER_ELEMENTS => $sanitizer_elements
		);

		return array_merge($general_elements, $validator_elements, $sanitizer_elements);
	}

	/**
	 * @param $field_id
	 * @param $required_elements
	 * @param $common_element_args
	 * @param $element_objects Element[]
	 */
	private function add_conditional_logic($field_id, $required_elements, $common_element_args, $element_objects)
	{
		foreach ($required_elements as $req_element_key => $req_element_value) {
			do {
				if (!isset($common_element_args[ $req_element_key ][ self::COMMON_ARGS ][ self::DEPENDENCY_ELEMENT ]))
					break;

				$dependency_key = $field_id . $common_element_args[ $req_element_key ][ self::COMMON_ARGS ][ self::DEPENDENCY_ELEMENT ];

				if (!isset($common_element_args[ $req_element_key ][ self::COMMON_ARGS ][ self::DEPENDENCY_VALUE ]))
					break;

				$dependency_value = $common_element_args[ $req_element_key ][ self::COMMON_ARGS ][ self::DEPENDENCY_VALUE ];

				if (!isset($element_objects[ $dependency_key ]))
					break;

				$dependency_element = $element_objects[ $dependency_key ];
				$element = $element_objects[ $field_id . $req_element_key ];
				$element->set_template_arg('cond_option', $dependency_element->get_field_name());
				$element->set_template_arg('cond_value', $dependency_value);
			} while (0);
		}
	}

	function get_field_template_args($field_id)
	{
		return $this->template_args[ self::TEMPL_ARG_FIELDS ][ $field_id ];
	}

	function get_validators($field_id)
	{
		return $this->get_addons(
			$field_id,
			new \WPFEPP\Element_Containers\Validators_Container(),
			Validators::values()
		);
	}

	function get_sanitizers($field_id)
	{
		return $this->get_addons(
			$field_id,
			new \WPFEPP\Element_Containers\Sanitizers_Container(),
			Sanitizers::values()
		);
	}

	/**
	 * Takes an array of indices and their default values ($required_addons), checks if any of those indices refer to elements in the $element_container and if they do, prepares and returns them for inclusion in the widget.
	 *
	 * @param $field_id string A string to be included in the key of the new element.
	 * @param $element_container Validators_Container|Sanitizers_Container
	 * @param $addons string[] A list containing all the addon keys to be used in this function.
	 * @return array
	 */
	function get_addons($field_id, $element_container, $addons)
	{
		$return_elements = array();

		// TODO this function relies on the internal details of another object $element_container. It assumes that the keys of the elements are a certain way. This is not very good.
		foreach ($addons as $addon_id) {

			if ($addon_element = $element_container->get_element($addon_id)) {
				$key = array($field_id, $addon_id);
				$addon_element->set_key(
					$key
				);

				$addon_element->set_template(
					WPFEPP_ELEMENT_TEMPLATES_DIR . 'field-addon-inner.php'
				);

				$current_value = Utils::get_from_array($this->current_values, $key);
				$current_value_available = $current_value !== null;

				if ($current_value_available) {
					$addon_element->set_value($current_value);
					$return_elements[] = $addon_element;
				}
			}
		}

		return $return_elements;
	}

	/**
	 * Takes an item from the common elements array, clones it and prepares it for inclusion in a widget.
	 * Before returning the prepared item it tries to set its value from the current_values array. If it can't do that, it sets the default value passed as argument.
	 *
	 * @param string $element_id
	 * @param array $element_args An array containing element arguments.
	 * @param string $field_id A string to be included in the key of the new element.
	 * @param string $default_element_args The value that will be set as the new generated element's value if nothing can be found in the current_values field.
	 * @return \WPGurus\Forms\Element
	 */
	function prepare_element($element_id, $element_args, $field_id, $default_element_args = '')
	{
		$type = $element_args[ self::COMMON_TYPE ];
		$args = wp_parse_args(
			$element_args[ self::COMMON_ARGS ],
			is_array($default_element_args) ? $default_element_args : array(Element::VALUE => $default_element_args)
		);

		$args[ Element::KEY ] = array($field_id, $element_id);

		if ($type === Elements::SELECT && Array_Utils::get($args, Select::MULTIPLE) === true) {
			$args[ Element::KEY ][] = '';
		}

		$element = Element_Factory::make_element(
			$type,
			$args
		);

		$value = Utils::get_from_array(
			$this->current_values,
			array($field_id, $element_id)
		);

		// If it exists then set it, otherwise leave it to default.
		if ($value !== NULL) {
			$element->set_value($value);
		}

		return $element;
	}

	/**
	 * Returns all the elements that make up a custom field widget. This includes data elements as well as the necessary renderables.
	 *
	 * @param $id string The meta key for the custom field.
	 * @param $title string The title/label for the custom field.
	 * @return \WPGurus\Forms\Element[]
	 */
	function get_custom_field_elements($id, $title)
	{
		return $this->get_required_elements(
			$id,
			stripslashes($title),
			array(
				Field_Settings::SETTING_ENABLED                       => true,
				Field_Settings::SETTING_WIDTH                         => '',
				Field_Settings::SETTING_SAVE_CUSTOM_VAL_TO_META       => true,
				Field_Settings::SETTING_CUSTOM_FIELD_LOCATIONS        => array(
					Element::VALUE => array()
				),
				Field_Settings::SETTING_ELEMENT                    => array(
					Element::VALUE  => Elements::TEXT,
					Select::CHOICES => array(
						Elements::TEXT                => __('Text', 'frontend-publishing-pro'),
						Elements::TEXTAREA            => __('Textarea', 'frontend-publishing-pro'),
						Elements::RICH_TEXT           => __('Rich Text', 'frontend-publishing-pro'),
						Elements::SANDBOXED_RICH_TEXT => __('Rich Text (Sandboxed)', 'frontend-publishing-pro'),
						Elements::CHECKBOX            => __('Checkbox', 'frontend-publishing-pro'),
						Elements::SELECT              => __('Select', 'frontend-publishing-pro'),
						Elements::MEDIA_ID            => __('Media Id', 'frontend-publishing-pro'),
						Elements::MEDIA_URL           => __('Media URL', 'frontend-publishing-pro'),
						Elements::MEDIA_FILE          => __('Media File', 'frontend-publishing-pro'),
						Elements::MEDIA_IDS           => __('Gallery', 'frontend-publishing-pro'),
						Elements::SELECT_WITH_SEARCH  => __('Advanced Select', 'frontend-publishing-pro'),
						Elements::COLOR_PICKER        => __('Color Picker', 'frontend-publishing-pro'),
						Elements::DATE_PICKER         => __('Date Picker', 'frontend-publishing-pro'),
						Elements::STAR_RATING         => __('Star Rating', 'frontend-publishing-pro')
					)
				),
				Field_Settings::SETTING_ENABLE_CUSTOM_FIELD_EMBEDS => false,
				Field_Settings::SETTING_DATE_PICKER_LANGUAGE       => 'en',
				Field_Settings::SETTING_DATE_PICKER_THEME          => 'light',
				Field_Settings::SETTING_DATE_PICKER_DATE_ENABLED   => true,
				Field_Settings::SETTING_DATE_PICKER_TIME_ENABLED   => true,
				Field_Settings::SETTING_DATE_PICKER_FORMAT         => 'Y-m-d H:i:s',
				Field_Settings::SETTING_RATING_NUMBER              => 5,
				Field_Settings::SETTING_SWS_LANGUAGE               => __('en', 'frontend-publishing-pro'),
				Field_Settings::SETTING_SWS_ALLOW_ADDITION         => false,
				Field_Settings::SETTING_SWS_PLACEHOLDER_TEXT_SINGLE   => __('Select an Option', 'frontend-publishing-pro'),
				Field_Settings::SETTING_SWS_PLACEHOLDER_TEXT_MULTIPLE => __('Select Some Options', 'frontend-publishing-pro'),
				Field_Settings::SETTING_SWS_ALLOW_SINGLE_DESELECT     => false,
				Field_Settings::SETTING_SWS_DISABLE_SEARCH            => false,
				Field_Settings::SETTING_SWS_DISABLE_SEARCH_THRESHOLD  => 0,
				Field_Settings::SETTING_SWS_MAX_SELECTED_OPTIONS      => '',
				Field_Settings::SETTING_MULTIPLE                      => false,
				Field_Settings::SETTING_RICH_MEDIA_BUTTON             => true,
				Field_Settings::SETTING_RICH_EDITOR_HEIGHT            => 300,
				Field_Settings::SETTING_CHOICES                       => '',
				Field_Settings::SETTING_MEDIA_ITEMS_DISPLAY_COLUMNS   => 3,
				Field_Settings::SETTING_MEDIA_ITEMS_DISPLAY_SIZE      => 'thumbnail',
				Field_Settings::SETTING_MEDIA_ITEMS_DISPLAY_LINK      => '',
				Field_Settings::SETTING_MEDIA_ITEM_DISPLAY_SIZE       => 'full',
				Field_Settings::SETTING_MEDIA_URL_DISPLAY_WIDTH       => '',
				Field_Settings::SETTING_PREFIX_TEXT                   => '',
				Field_Settings::SETTING_FALLBACK                      => '',
				Field_Settings::SETTING_TYPE                          => Post_Fields::FIELD_CUSTOM
			),
			true
		);
	}

	function render()
	{
		$this->render_template($this->container_template, $this->template_args);
	}

	/**
	 * Reorders the fields array stored in the template arguments object to match the order specified by the user.
	 * @param $ordered_field_keys array An array containing field ids in a certain order.
	 */
	private function reorder_template_arg_fields($ordered_field_keys)
	{
		$template_arg_fields = array();
		foreach ($ordered_field_keys as $field_key) {
			$template_arg_fields[ $field_key ] = $this->template_args[ self::TEMPL_ARG_FIELDS ][ $field_key ];
		}
		$this->template_args[ self::TEMPL_ARG_FIELDS ] = $template_arg_fields;
	}
}