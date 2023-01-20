<?php

namespace WPFEPP\Forms;

if (!defined('WPINC')) die;

use WPFEPP\Components\Media_Restrictions;
use WPFEPP\Components\Post_Previews;
use WPFEPP\Constants\Assets;
use WPFEPP\Constants\Form_Meta_Keys;
use WPFEPP\Constants\Form_Settings;
use WPFEPP\Constants\Frontend_Form_Messages;
use WPFEPP\Constants\Option_Ids;
use WPFEPP\Constants\Plugin_Actions;
use WPFEPP\Constants\Plugin_Filters;
use WPFEPP\Constants\Post_Field_Settings;
use WPFEPP\Constants\Post_Fields;
use WPFEPP\Constants\Post_Meta_Keys;
use WPFEPP\Constants\Redirection_Types;
use WPFEPP\DB_Tables\Form_Meta;
use WPFEPP\DB_Tables\Forms;
use WPFEPP\Element_Containers\Email_Settings_Container;
use WPFEPP\Element_Containers\Form_Fields_Container;
use WPFEPP\Element_Containers\Form_Settings_Container;
use WPFEPP\Element_Containers\Frontend_Element_Container;
use WPFEPP\Element_Containers\Frontend_Messages_Container;
use WPFEPP\Element_Containers\Post_List_Settings_Container;
use WPGurus\Forms\Element_Container;
use WPGurus\Forms\Elements\Media_File;
use WPGurus\Forms\Processors\File_Upload;
use WPGurus\Forms\Utils;

/**
 *
 */
class Frontend_Form extends \WPGurus\Forms\Form
{
	/**
	 * When a post is created using the form, the resulting post id is saved in this data member for access from outside the class.
	 * @var int
	 */
	private $new_post_id;

	/**
	 * When the form contains a file upload element and a post is inserted into the DB, the element changes. This data member holds the updated HTML.
	 * @var
	 */
	private $media_file_elements_html;

	/**
	 * The element container for this form.
	 * @var Frontend_Element_Container
	 */
	private $element_container;

	/**
	 * From fields information fetched from the meta table.
	 * @var array|null
	 */
	private $form_fields;

	/**
	 * Form settings fetched from the form meta table or the general plugin settings.
	 * @var array|null
	 */
	private $form_settings;

	/**
	 * Form emails fetched from the form meta table or the general plugin settings.
	 * @var array|null
	 */
	private $form_emails;

	/**
	 * The frontend messages.
	 * @var array|null
	 */
	private $frontend_messages;

	/**
	 * In case the form is being used to edit a post, this field contains the post to be edited.
	 * @var array
	 */
	private $post;

	/**
	 * When a post is inserted into the DB, the post status is stored in this field for access from outside the class.
	 * @var string
	 */
	private $new_post_status;

	/**
	 * The DB id of the form.
	 * @var int
	 */
	private $form_db_id;

	/**
	 * The forms DB table class.
	 * @var Forms
	 */
	private $form_table;

	/**
	 * The DB row from the forms table.
	 * @var array|null
	 */
	private $form_details;

	/**
	 * The form post type.
	 * @var null|string
	 */
	private $post_type = null;

	/**
	 * The form meta DB table class.
	 * @var Form_Meta
	 */
	private $form_meta_table;

	/**
	 * An array containing all the meta values for a particular form.
	 * @var array
	 */
	private $form_meta;

	/**
	 * Post list settings obtained from the meta table or the general plugins settings.
	 * @var mixed|null
	 */
	private $post_list_settings = null;

	/**
	 * The request coming from the form can either be a submission or a draft request. This field indicates which type the last request was.
	 * @var
	 */
	private $request_type;

	/**
	 * The URL where the user will be redirected to after a successful submission.
	 * @var string
	 */
	private $redirect_url;

	/**
	 * The possible request types.
	 */
	const REQUEST_TYPE_DRAFT = 'draft';
	const REQUEST_TYPE_SUBMISSION = 'submission';

	/**
	 * Frontend_Form constructor.
	 * @param string $form_db_id
	 * @param int $post_id
	 */
	function __construct($form_db_id, $post_id = 0)
	{
		$this->form_db_id = $form_db_id;
		$this->post = ($post_id) ? (array)get_post($post_id) : array();
		$this->form_table = new Forms();
		$this->form_details = $this->form_table->get($form_db_id);
		if (!$this->is_valid()) {
			return;
		}
		$this->post_type = $this->form_details[ Forms::COLUMN_POST_TYPE ];

		$this->form_meta_table = new Form_Meta();
		$this->form_meta = $this->form_meta_table->get_meta_values($form_db_id);
		$this->form_fields = $this->prepare_form_fields();
		$this->form_settings = $this->prepare_form_settings();
		$this->form_emails = $this->prepare_form_emails();
		$this->post_list_settings = $this->prepare_post_list_settings();
		$this->frontend_messages = $this->prepare_frontend_messages();

		$this->element_container = new Frontend_Element_Container(
			$form_db_id,
			$this->post_type,
			$this->form_fields,
			$this->form_settings,
			$this->post,
			$this->frontend_messages
		);

		parent::__construct('frontend-form-' . $form_db_id, $this->element_container);

		$this->set_form_attributes();

		$this->add_file_uploader();

		if ($this->draft_processing_required() || $this->current_user_exempt()) {
			$this->set_validation(false);
		}
	}

	/**
	 * Inserts a post in the WP database.
	 * @param array $cleaned_data The user submitted data.
	 * @return bool
	 */
	function process_data($cleaned_data)
	{
		$old_status = 'new';
		$post_data = $cleaned_data[ Frontend_Element_Container::COMMON_KEY_POST_DATA ];

		if (is_user_logged_in()) {
			$post_data[ Post_Fields::FIELD_AUTHOR ] = get_current_user_id();
		} else {
			$post_data[ Post_Fields::FIELD_AUTHOR ] = $this->form_settings[ Form_Settings::SETTING_ANONYMOUS_POST_AUTHOR ];
		}

		if (!empty($post_data[ Post_Fields::FIELD_POST_ID ])) {
			$current_post = get_post($post_data[ Post_Fields::FIELD_POST_ID ]);

			if (!\WPFEPP\current_user_can_edit($current_post)) {
				$this->add_form_errors(__("You don't have permission to edit this post.", 'frontend-publishing-pro'));
				return false;
			}
			$old_status = $current_post->post_status;

			// The post is being edited.
			// The following line will make sure that if an editor modifies the post, the author does not get changed.
			$post_data[ Post_Fields::FIELD_AUTHOR ] = $current_post->post_author;

			// The following line will make sure that when a post is edited it doesn't jump to the top of every post list.
			$post_data[ Post_Fields::FIELD_DATE ] = $current_post->post_date;
		}

		if ($this->draft_processing_required()) {
			$post_status = 'draft';
		} else {
			if ($this->current_user_can_publish())
				$post_status = 'publish';
			else
				$post_status = 'pending';
		}

		$post_data[ Post_Fields::FIELD_STATUS ] = $post_status;

		$post_data[ Post_Fields::FIELD_COMMENT_STATUS ] = get_option('default_comment_status');

		$post_data = apply_filters(Plugin_Filters::BEFORE_POST_INSERTION, $post_data, $this);

		$post_id = wp_insert_post($post_data, true);

		if (is_wp_error($post_id)) {
			/**
			 * @var $post_id \WP_Error
			 */
			$this->add_form_errors($post_id->get_error_messages());
			return false;
		} else {
			$this->add_post_id_to_element($post_id);

			$this->process_thumbnail($post_data, $post_id);

			$this->process_taxonomy_terms($post_data, $post_id);

			$this->add_form_db_id_to_post_data($post_data);

			$this->process_custom_fields($post_data, $post_id);

			$this->new_post_id = $post_id;

			$this->set_success_message(
				$this->prepare_success_message($post_id, $post_status)
			);

			$this->new_post_status = $post_status;

			$this->set_redirect_url($post_status, $post_id);

			$post_data[ Post_Fields::FIELD_POST_ID ] = $post_id;

			if (
				($old_status == 'new' || $old_status == 'draft')
				&& $post_status != 'draft'
			) {
				do_action(Plugin_Actions::POST_SUBMITTED, $post_data, $this);
			}

			do_action(Plugin_Actions::AFTER_POST_INSERTION, $post_data, $this);

			return true;
		}
	}

	/**
	 * Prints the main form errors.
	 */
	public function print_errors()
	{
		?>
		<div class="frontend-item-messages"><?php parent::print_errors(); ?></div>
		<?php
	}

	/**
	 * This overriden method just inserts the general error message to the form.
	 * @return bool
	 */
	public function handle_submission()
	{
		$submission_successful = parent::handle_submission();
		// TODO: The following would be easier if there was a get_form_errors method in the Form class
		$errors = $this->get_errors();
		$form_errors = $errors[ self::INDEX_FORM_ERRORS ];
		if (!$submission_successful && !count($form_errors)) {
			$this->add_form_errors(
				$this->frontend_messages[ Frontend_Form_Messages::SETTING_FORM_ERROR ]
			);
		}
		$this->update_request_type();
		$this->set_media_file_elements_html();
		return $submission_successful;
	}

	private function print_pre_req_error($error)
	{
		?>
		<div class="frontend-item-messages frontend-item-errors">
			<?php echo $error; ?>
		</div>
		<?php
	}

	/**
	 * Renders the frontend form.
	 */
	function render()
	{
		$this->enqueue_assets();

		?>
		<div class="frontend-form-container frontend-item-container"
			 style="<?php echo $this->form_container_style(); ?>">
			<?php

			if (!$this->is_valid()) {
				$this->print_pre_req_error(__('No form with the given ID was found in the database.', 'frontend-publishing-pro'));
				return;
			}

			if ($this->post && !\WPFEPP\current_user_can_edit((object)$this->post)) {
				$this->print_pre_req_error(__("You don't have permission to edit this post.", 'frontend-publishing-pro'));
				return;
			}

			if ($this->form_settings[ Form_Settings::SETTING_REQUIRE_LOGIN ] && !is_user_logged_in()) {
				if ($this->form_settings[ Form_Settings::SETTING_REDIRECT_TO_LOGIN ]) {
					\WPFEPP\login_redirect();
				} else {
					$this->print_pre_req_error(
						$this->frontend_messages[ Frontend_Form_Messages::SETTING_LOGIN_REQUIRED_ERROR ]
					);
				}
				return;
			}

			parent::render();
			?>
		</div>
		<?php
	}

	/**
	 * Returns a boolean which indicates if the current is a draft request or not.
	 * @return bool
	 */
	public function is_draft_request()
	{
		$data = $this->get_data();
		return $this->is_submission() && $data[ Frontend_Element_Container::ELEM_KEY_SUBMIT_BUTTON ] == Frontend_Element_Container::SUBMIT_BUTTON_VAL_DRAFT;
	}

	/**
	 * Returns a boolean indicating whether a draft should be saved while handling the submission.
	 * @return bool
	 */
	private function draft_processing_required()
	{
		return $this->form_settings[ Form_Settings::SETTING_ENABLE_DRAFTS ] && $this->is_draft_request();
	}

	/**
	 * Tells whether the current user can publish immediately or should the post be held in a pending queue.
	 * @return bool
	 */
	private function current_user_can_publish()
	{
		return \WPFEPP\current_user_can($this->form_settings[ Form_Settings::SETTING_INSTANTLY_PUBLISH ]);
	}

	/**
	 * Tells whether advanced validation should be used.
	 * @return bool
	 */
	private function use_advanced_validation()
	{
		return ($this->current_user_exempt()) ? false : $this->form_settings[ Form_Settings::SETTING_ADVANCED_VALIDATION ];
	}

	/**
	 * Tells whether the current user is exempt from restrictions or not.
	 * @return bool
	 */
	private function current_user_exempt()
	{
		return \WPFEPP\current_user_can($this->form_settings[ Form_Settings::SETTING_NO_RESTRICTIONS ]);
	}

	/**
	 * When the form is submitted and AJAX is disabled the value of the post id element needs to be updated so that the subsequent submissions update the same post instead of inserting new ones.
	 * @param $post_id int The post ID.
	 */
	private function add_post_id_to_element($post_id)
	{
		$post_id_element = $this->element_container->get_element(Post_Fields::FIELD_POST_ID);
		$post_id_element->set_value($post_id);
	}

	/**
	 * Sets the value of the thumbnail element HTML field.
	 */
	private function set_media_file_elements_html()
	{
		$this->media_file_elements_html = array();
		/**
		 * @var Frontend_Element_Container $element_container
		 */
		$element_container = $this->get_element_container();
		foreach ($element_container->get_elements() as $element) {
			if (is_a($element, '\WPGurus\Forms\Elements\Media_File')) {
				$element->set_template(
					WPFEPP_ELEMENT_TEMPLATES_DIR . 'frontend-form-element-inner.php'
				);
				ob_start();
				$element->render();
				$this->media_file_elements_html[ $element->get_id() ] = ob_get_clean();
			}
		}
	}

	/**
	 * Assigns taxonomy terms to a post.
	 * @param $post_data array User submitted post data.
	 * @param $post_id int The ID of the post to which we want to assign terms.
	 */
	private function process_taxonomy_terms($post_data, $post_id)
	{
		$taxonomy_terms = Utils::get_from_array($post_data, Frontend_Element_Container::COMMON_KEY_TAX_TERMS);
		if (is_array($taxonomy_terms)) {
			foreach ($taxonomy_terms as $taxonomy => $terms) {
				if (is_array($terms)) {
					$terms = array_filter($terms);
				}

				$terms = $this->create_hierarchical_taxonomy_terms($taxonomy, $terms);

				wp_set_post_terms($post_id, $terms, $taxonomy);
			}
		}
	}

	/**
	 * Adds (or removes) a thumbnail to a post.
	 * @param $post_data array User submitted post data.
	 * @param $post_id int The ID of the post to which we want to add a thumbnail.
	 */
	private function process_thumbnail($post_data, $post_id)
	{
		$thumbnail_id = Utils::get_from_array($post_data, Post_Fields::FIELD_THUMBNAIL);
		if ($thumbnail_id && is_numeric($thumbnail_id)) {
			set_post_thumbnail($post_id, $thumbnail_id);
		} else {
			delete_post_thumbnail($post_id);
		}
	}

	public function add_custom_field_to_data($data, $key, $value)
	{
		$data[ Frontend_Element_Container::COMMON_KEY_POST_DATA ][ Frontend_Element_Container::COMMON_KEY_CUSTOM_FIELD ][ $key ] = $value;
		return $data;
	}

	/**
	 * Saves custom field values as post meta.
	 * @param $post_data array An array containing user-submitted values.
	 * @param $post_id int The ID of the post that was last inserted into the DB.
	 */
	private function process_custom_fields($post_data, $post_id)
	{
		$custom_field_values = Utils::get_from_array($post_data, Frontend_Element_Container::COMMON_KEY_CUSTOM_FIELD);
		if (is_array($custom_field_values)) {
			foreach ($custom_field_values as $key => $value) {
				$save_field = Utils::get_from_array($this->form_fields, array($key, Post_Field_Settings::SETTING_SAVE_CUSTOM_VAL_TO_META));
				if ($save_field === false) {
					delete_post_meta($post_id, $key);
				} else {
					update_post_meta($post_id, $key, $value);
				}
			}
		}
	}

	/**
	 * Prepares the success message.
	 * @param $post_id int Post ID.
	 * @param $post_status string Post status.
	 * @return string The success message string.
	 */
	private function prepare_success_message($post_id, $post_status)
	{
		$success_message = '';

		switch ($post_status) {
			case self::REQUEST_TYPE_DRAFT:
				return $success_message;

			case 'pending':
				$success_message = $this->frontend_messages[ Frontend_Form_Messages::SETTING_SUBMITTED_MESSAGE ];
				break;

			case 'publish':
				$success_message = $this->frontend_messages[ Frontend_Form_Messages::SETTING_PUBLISHED_MESSAGE ];
				break;
		}

		$links = array();
		if (\WPFEPP\current_user_can_edit(get_post($post_id))) {
			$links[] = sprintf('<a class="frontend-form-continue-editing" href="#">%s</a>', __('Continue Editing', 'frontend-publishing-pro'));
		}

		$links[] = sprintf(
			'<a class="frontend-form-post-link" href="%s">%s</a>',
			$this->get_post_url($post_id, $post_status),
			$post_status == 'publish' ? __('View Post', 'frontend-publishing-pro') : __('Preview Post', 'frontend-publishing-pro')
		);

		$links = apply_filters(Plugin_Filters::FRONTEND_FORM_SUCCESS_LINKS, $links, $post_id, $this);

		$success_message .= sprintf(
			'<div class="frontend-form-success-links">%s</div>',
			implode(' | ', $links)
		);

		return $success_message;
	}

	/**
	 * Returns the value of a post field or the default value if one is not found.
	 * @param $field string The array index of the field.
	 * @param $default string The default value to be returned if the field is not available.
	 * @return string
	 */
	private function get_post_field($field, $default = '')
	{
		return isset($this->post[ $field ]) ? $this->post[ $field ] : $default;
	}

	/**
	 * Add the necessary attributes to the form element. Most of these are data attributes to be used by jQuery.
	 */
	private function set_form_attributes()
	{
		$this->set_attribute('class', ' frontend-post-form wp-ref');
		$this->set_attribute('data-form-db-id', $this->form_db_id);
		$this->set_attribute(
			'data-validation_plugin',
			($this->use_advanced_validation()) ? 'true' : 'false'
		);

		$this->set_attribute(
			'data-show_tooltips',
			($this->form_settings[ Form_Settings::SETTING_TOOLTIPS ]) ? 'true' : 'false'
		);

		$this->set_attribute(
			'data-auto_save',
			($this->form_settings[ Form_Settings::SETTING_AUTOSAVE_POSTS ]) ? 'true' : 'false'
		);

		$this->set_attribute('data-auto_save_interval', $this->form_settings[ Form_Settings::SETTING_AUTOSAVE_INTERVAL ]);

		$this->set_attribute(
			'data-post_status',
			$this->get_post_field('post_status', 'new')
		);
	}

	/**
	 * Fetches form field information from the meta table.
	 * @return array Form field information.
	 */
	private function prepare_form_fields()
	{
		$form_fields = isset($this->form_meta[ Form_Meta_Keys::FIELDS ]) ? $this->form_meta[ Form_Meta_Keys::FIELDS ] : array();
		$form_fields_container = new Form_Fields_Container($this->post_type, $form_fields);
		$form_fields = $form_fields_container->parse_values($form_fields);
		return $form_fields;
	}

	/**
	 * Fetches form settings from either the meta table or the plugin settings.
	 * @return array Form settings
	 */
	private function prepare_form_settings()
	{
		return $this->fetch_optional_meta_value(
			Form_Meta_Keys::SETTINGS,
			Option_Ids::OPTION_GENERAL_FORM_SETTINGS,
			new Form_Settings_Container()
		);
	}

	/**
	 * Fetches form settings from either the meta table or the plugin settings.
	 * @return array Form emails
	 */
	private function prepare_form_emails()
	{
		return $this->fetch_optional_meta_value(
			Form_Meta_Keys::EMAILS,
			Option_Ids::OPTION_EMAIL_SETTINGS,
			new Email_Settings_Container()
		);
	}

	/**
	 * Fetches post list settings from either the meta table or the plugin settings.
	 * @return array Form post list settings
	 */
	private function prepare_post_list_settings()
	{
		return $this->fetch_optional_meta_value(
			Form_Meta_Keys::POST_LIST,
			Option_Ids::OPTION_POST_LIST_SETTINGS,
			new Post_List_Settings_Container()
		);
	}

	/**
	 * Prepares frontend messages by filling in the missing values.
	 * @return mixed
	 */
	private function prepare_frontend_messages()
	{
		$messages_container = new Frontend_Messages_Container();
		$messages = $messages_container->parse_values(get_option(Option_Ids::OPTION_MESSAGES));
		return $messages;
	}

	/**
	 * Fetches a meta value and if it is not available, fetches the corresponding option value instead.
	 * @param $meta_key string The key for looking up a value from the meta table.
	 * @param $option_id string The key for looking up a value from the options table.
	 * @param $container Element_Container The container used to fill in the gaps with defaults.
	 * @return array
	 */
	private function fetch_optional_meta_value($meta_key, $option_id, $container)
	{
		$meta_value = isset($this->form_meta[ $meta_key ]) ? $this->form_meta[ $meta_key ] : array();
		$meta_value = ($meta_value) ? $meta_value : get_option($option_id);
		return $container->parse_values($meta_value);
	}

	/**
	 * Adds file uploader to the form.
	 */
	private function add_file_uploader()
	{
		$media_restrictions_component = new Media_Restrictions();
		$this->add_pre_processor(
			new File_Upload(
				array(
					File_Upload::MAX_SIZE             => $media_restrictions_component->get_max_upload_size(),
					File_Upload::ALLOWED_TYPES        => $media_restrictions_component->allowed_mime_types(),
					File_Upload::FILE_TOO_LARGE_ERROR => $media_restrictions_component->get_file_too_large_error(),
					File_Upload::INVALID_TYPE_ERROR   => __('This file type is not allowed for security reasons.', 'frontend-publishing-pro')
				)
			)
		);

		$this->set_attribute('enctype', 'multipart/form-data');
	}

	/**
	 * Returns a boolean indicating whether the form's record was found in the DB or not.
	 * @return bool
	 */
	public function is_valid()
	{
		return $this->form_details !== null;
	}

	/**
	 * Returns the ID of the last processed post.
	 * @return int
	 */
	public function get_new_post_id()
	{
		return $this->new_post_id;
	}

	/**
	 * Returns the updated HTML of the media file elements.
	 * @return mixed
	 */
	public function get_media_file_elements_html()
	{
		return $this->media_file_elements_html;
	}

	/**
	 * Returns the last request type.
	 * @return mixed
	 */
	public function get_request_type()
	{
		return $this->request_type;
	}

	/**
	 * Returns the post status of the last processed post.
	 * @return string
	 */
	public function get_new_post_status()
	{
		return $this->new_post_status;
	}

	/**
	 * Getter for post type.
	 * @return string
	 */
	public function get_post_type()
	{
		return $this->post_type;
	}

	/**
	 * Getter for post list settings.
	 * @return array|null
	 */
	public function get_post_list_settings()
	{
		return $this->post_list_settings;
	}

	/**
	 * Returns array containing form settings. Null if the form does not exist.
	 * @return array|null
	 */
	public function get_form_settings()
	{
		return $this->form_settings;
	}

	/**
	 * Returns array containing form emails. Null if the form does not exist.
	 * @return array|null
	 */
	public function get_form_emails()
	{
		return $this->form_emails;
	}

	/**
	 * Returns an array containing form field details. Null if the form does not exist.
	 * @return array|null
	 */
	public function get_form_fields()
	{
		return $this->form_fields;
	}

	/**
	 * Returns the DB ID for the form.
	 * @return int
	 */
	public function get_form_db_id()
	{
		return $this->form_db_id;
	}

	/**
	 * Returns a DB row containing the current form's details.
	 * @return array|null
	 */
	public function get_form_details()
	{
		return $this->form_details;
	}

	/**
	 * Returns an array containing all the meta values for the form.
	 * @return array
	 */
	public function get_form_meta()
	{
		return $this->form_meta;
	}

	/**
	 * Returns frontend messages.
	 * @return array
	 */
	public function get_frontend_messages()
	{
		return $this->frontend_messages;
	}

	/**
	 * Getter.
	 * @return string
	 */
	public function get_redirect_url()
	{
		return $this->redirect_url;
	}

	/**
	 * Updates the request type.
	 */
	private function update_request_type()
	{
		if ($this->draft_processing_required()) {
			$this->request_type = self::REQUEST_TYPE_DRAFT;
		} else {
			$this->request_type = self::REQUEST_TYPE_SUBMISSION;
		}
	}

	/**
	 * Sets the redirect_url property after a post has been created.
	 * @param $post_status string The status of the newly created post.
	 * @param $post_id int The ID of the newly created post.
	 */
	private function set_redirect_url($post_status, $post_id)
	{
		switch ($this->form_settings[ Form_Settings::SETTING_REDIRECTION_TYPE ]) {
			case Redirection_Types::NONE:
				$this->redirect_url = '';
				break;

			case Redirection_Types::CUSTOM_URL:
				$this->redirect_url = $this->form_settings[ Form_Settings::SETTING_REDIRECT_URL ];
				break;

			case Redirection_Types::POST_URL:
				$this->redirect_url = $this->get_post_url($post_id, $post_status);
				break;
		}
	}

	/**
	 * Returns the post permalink if the post is live otherwise returns the preview link.
	 * @param $post_id int Post ID
	 * @param $post_status string Post status.
	 * @return string|\WP_Error
	 */
	private function get_post_url($post_id, $post_status)
	{
		if ($post_status == 'publish') {
			return get_post_permalink($post_id);
		} else {
			return Post_Previews::make_preview_link($post_id);
		}
	}

	/**
	 * Prints the contents of the style HTML attribute of the form.
	 * @return string Container style.
	 */
	private function form_container_style()
	{
		$form_container_style = '';
		if ($this->form_settings[ Form_Settings::SETTING_WIDTH ]) {
			$form_container_style = sprintf('max-width:%s;', $this->form_settings[ Form_Settings::SETTING_WIDTH ]);
			return $form_container_style;
		}
		return $form_container_style;
	}

	/**
	 * Adds the form db ID to the post data so it can be saved as custom field value.
	 * @param $post_data
	 */
	private function add_form_db_id_to_post_data(&$post_data)
	{
		$post_data[ Frontend_Element_Container::COMMON_KEY_CUSTOM_FIELD ][ Post_Meta_Keys::FORM_ID ] = $this->form_db_id;
	}

	private function enqueue_assets()
	{
		wp_enqueue_media();
		wp_enqueue_script(Assets::FRONTEND_FORM_JS);
		wp_enqueue_style(Assets::FRONTEND_FORM_CSS);
		wp_enqueue_style(Assets::RICH_EDITOR_FIX);

		if ($this->form_settings[ Form_Settings::SETTING_ADVANCED_VALIDATION ]) {
			wp_enqueue_script(Assets::JQUERY_VALIDATION_JS);
		}

		if ($this->form_settings[ Form_Settings::SETTING_TOOLTIPS ]) {
			wp_enqueue_script(Assets::QTIP_JS);
			wp_enqueue_style(Assets::QTIP_CSS);
		}
	}

	/**
	 * Takes an array of hierarchical taxonomy terms, checks if any of the items in the array are strings and inserts those strings as terms. Then it replaces the string it founds with the ID of the newly added term.
	 * @param $taxonomy string The hierarchical taxonomy.
	 * @param $terms array A mixed array containing numeric term IDs and new terms that need to be created.
	 * @return array An array containing only term IDs, ready to be passed to wp_set_post_terms.
	 */
	private function create_hierarchical_taxonomy_terms($taxonomy, $terms)
	{
		if (!is_taxonomy_hierarchical($taxonomy)) {
			return $terms;
		}

		foreach ($terms as $index => $term) {
			if (is_numeric($term)) {
				continue;
			}

			unset($terms[ $index ]);

			/**
			 * @var $existing_term \WP_Term
			 */
			$term_id = 0;
			$existing_term = get_term_by('name', $term, $taxonomy);
			if ($existing_term) {
				$term_id = $existing_term->term_id;
			} else {
				$inserted_term = wp_insert_term($term, $taxonomy);
				if (!is_wp_error($inserted_term)) {
					$term_id = $inserted_term['term_id'];
				}
			}

			if ($term_id) {
				$terms[ $index ] = $term_id;
			}
		}

		return $terms;
	}
}