<?php
namespace WPFEPP\Components;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Frontend_Form_Messages;
use WPFEPP\Constants\General_Media_Types;
use WPFEPP\Constants\Media_Settings;
use WPFEPP\Constants\Option_Ids;
use WPFEPP\Element_Containers\Frontend_Messages_Container;
use WPFEPP\Element_Containers\Media_Settings_Container;
use WPFEPP\Shortcodes\Frontend_Form;
use WPFEPP\Shortcodes\Post_Table;
use WPGurus\Components\Component;

class Media_Restrictions extends Component
{
	private $make_restrictions_global;
	private $exempt_roles;
	private $general_allowed_types;
	private $force_allow_uploads;
	private $max_upload_size;
	private $own_media_only;
	private $file_too_large_error;

	public function __construct()
	{
		parent::__construct();

		$this->register_filter('wp_handle_upload_prefilter', array($this, 'upload_size'));
		$this->register_filter('upload_mimes', array($this, 'change_upload_mimes'));
		$this->register_action('init', array($this, 'grant_upload_capabilities'));
		$this->register_action('pre_get_posts', array($this, 'show_users_own_attachments'));

		$this->populate_data_members();
	}

	private function is_referrer_shortcode_page()
	{
		// If get_allowed_mime_types() is called too early $wp_rewrite is not
		// available yet and url_to_postid fails fatally
		global $wp_rewrite;
		$referrer = wp_get_referer();

		if ( ! $referrer || empty( $wp_rewrite ) ) {
			return false;
		}

		$post_id = url_to_postid($referrer);

		if ($post_id === 0)
			return false;

		$post = get_post($post_id);
		return has_shortcode($post->post_content, Frontend_Form::SHORTCODE) || has_shortcode($post->post_content, Post_Table::SHORTCODE);
	}

	private function restrictions_not_applicable()
	{
		return $this->current_user_exempt() || (!$this->make_restrictions_global && !$this->is_referrer_shortcode_page());
	}

	public function show_users_own_attachments($wp_query_obj)
	{
		global $pagenow;

		if ('admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments' || $wp_query_obj->get('post_type') != 'attachment')
			return;

		if ($this->restrictions_not_applicable() || !$this->own_media_only) {
			return;
		}

		$current_user = wp_get_current_user();

		if (!is_a($current_user, 'WP_User'))
			return;

		$wp_query_obj->set('author', $current_user->ID);
	}

	public function grant_upload_capabilities()
	{
		$subscriber_role = get_role('subscriber');
		$contributor_role = get_role('contributor');

		if ($this->force_allow_uploads) {
			if ($subscriber_role) {
				$subscriber_role->add_cap('upload_files');
				$subscriber_role->add_cap('edit_posts');
			}

			if ($contributor_role) {
				$contributor_role->add_cap('upload_files');
			}
		} else {
			if ($subscriber_role) {
				$subscriber_role->remove_cap('upload_files');
				$subscriber_role->remove_cap('edit_posts');
			}

			if ($contributor_role) {
				$contributor_role->remove_cap('upload_files');
			}
		}
	}

	public function upload_size($file)
	{
		if ($this->restrictions_not_applicable()) {
			return $file;
		}

		if ($file['size'] > $this->max_upload_size * 1024) {
			$file['error'] = $this->file_too_large_error;
		}

		return $file;
	}

	public function change_upload_mimes($mimes)
	{
		if ($this->restrictions_not_applicable()) {
			return $mimes;
		}

		return $this->allowed_mime_types();
	}

	/**
	 * @return bool
	 */
	private function current_user_exempt()
	{
		return \WPFEPP\current_user_can($this->exempt_roles);
	}

	private function populate_data_members()
	{
		$media_settings_container = new Media_Settings_Container();
		$media_settings = $media_settings_container->parse_values(get_option(Option_Ids::OPTION_MEDIA_SETTINGS));
		$this->make_restrictions_global = $media_settings[ Media_Settings::SETTING_MAKE_RESTRICTIONS_GLOBAL ];
		$this->exempt_roles = $media_settings[ Media_Settings::SETTING_EXEMPT_ROLES ];
		$this->general_allowed_types = $media_settings[ Media_Settings::SETTING_ALLOWED_MEDIA_TYPES ];
		$this->force_allow_uploads = $media_settings[ Media_Settings::SETTING_FORCE_ALLOW_UPLOADS ];
		$this->max_upload_size = $media_settings[ Media_Settings::SETTING_MAX_UPLOAD_SIZE ];
		$this->own_media_only = $media_settings[ Media_Settings::SETTING_OWN_MEDIA_ONLY ];

		$messages_container = new Frontend_Messages_Container();
		$error_messages = $messages_container->parse_values(get_option(Option_Ids::OPTION_MESSAGES));
		$this->file_too_large_error = $error_messages[ Frontend_Form_Messages::SETTING_FILE_TOO_LARGE ];
		$this->file_too_large_error = sprintf($this->file_too_large_error, $this->max_upload_size);
	}

	public function allowed_mime_types()
	{
		$mime_types = array(
			// Image formats
			General_Media_Types::TYPE_IMAGE       => array(
				'jpg|jpeg|jpe' => 'image/jpeg',
				'gif'          => 'image/gif',
				'png'          => 'image/png',
				'bmp'          => 'image/bmp',
				'tif|tiff'     => 'image/tiff',
				'ico'          => 'image/x-icon'
			),

			// Video formats
			General_Media_Types::TYPE_VIDEO       => array(
				'asf|asx'      => 'video/x-ms-asf',
				'wmv'          => 'video/x-ms-wmv',
				'wmx'          => 'video/x-ms-wmx',
				'wm'           => 'video/x-ms-wm',
				'avi'          => 'video/avi',
				'divx'         => 'video/divx',
				'flv'          => 'video/x-flv',
				'mov|qt'       => 'video/quicktime',
				'mpeg|mpg|mpe' => 'video/mpeg',
				'mp4|m4v'      => 'video/mp4',
				'ogv'          => 'video/ogg',
				'webm'         => 'video/webm',
				'mkv'          => 'video/x-matroska'
			),

			// Text formats
			General_Media_Types::TYPE_TEXT        => array(
				'txt|asc|c|cc|h' => 'text/plain',
				'csv'            => 'text/csv',
				'tsv'            => 'text/tab-separated-values',
				'ics'            => 'text/calendar',
				'rtx'            => 'text/richtext',
				'css'            => 'text/css',
				'htm|html'       => 'text/html'
			),

			// Audio formats
			General_Media_Types::TYPE_AUDIO       => array(
				'mp3|m4a|m4b' => 'audio/mpeg',
				'ra|ram'      => 'audio/x-realaudio',
				'wav'         => 'audio/wav',
				'ogg|oga'     => 'audio/ogg',
				'mid|midi'    => 'audio/midi',
				'wma'         => 'audio/x-ms-wma',
				'wax'         => 'audio/x-ms-wax',
				'mka'         => 'audio/x-matroska'
			),

			// Misc application formats
			General_Media_Types::TYPE_MISC        => array(
				'rtf'     => 'application/rtf',
				'js'      => 'application/javascript',
				'pdf'     => 'application/pdf',
				'swf'     => 'application/x-shockwave-flash',
				'class'   => 'application/java',
				'tar'     => 'application/x-tar',
				'zip'     => 'application/zip',
				'gz|gzip' => 'application/x-gzip',
				'rar'     => 'application/rar',
				'7z'      => 'application/x-7z-compressed',
				'exe'     => 'application/x-msdownload'
			),

			// MS Office formats
			General_Media_Types::TYPE_OFFICE      => array(
				'doc'                          => 'application/msword',
				'pot|pps|ppt'                  => 'application/vnd.ms-powerpoint',
				'wri'                          => 'application/vnd.ms-write',
				'xla|xls|xlt|xlw'              => 'application/vnd.ms-excel',
				'mdb'                          => 'application/vnd.ms-access',
				'mpp'                          => 'application/vnd.ms-project',
				'docx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'docm'                         => 'application/vnd.ms-word.document.macroEnabled.12',
				'dotx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
				'dotm'                         => 'application/vnd.ms-word.template.macroEnabled.12',
				'xlsx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				'xlsm'                         => 'application/vnd.ms-excel.sheet.macroEnabled.12',
				'xlsb'                         => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
				'xltx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
				'xltm'                         => 'application/vnd.ms-excel.template.macroEnabled.12',
				'xlam'                         => 'application/vnd.ms-excel.addin.macroEnabled.12',
				'pptx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
				'pptm'                         => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
				'ppsx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
				'ppsm'                         => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
				'potx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.template',
				'potm'                         => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
				'ppam'                         => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
				'sldx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
				'sldm'                         => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
				'onetoc|onetoc2|onetmp|onepkg' => 'application/onenote'
			),

			// OpenOffice formats
			General_Media_Types::TYPE_OPEN_OFFICE => array(
				'odt' => 'application/vnd.oasis.opendocument.text',
				'odp' => 'application/vnd.oasis.opendocument.presentation',
				'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
				'odg' => 'application/vnd.oasis.opendocument.graphics',
				'odc' => 'application/vnd.oasis.opendocument.chart',
				'odb' => 'application/vnd.oasis.opendocument.database',
				'odf' => 'application/vnd.oasis.opendocument.formula'
			),

			// WordPerfect formats
			General_Media_Types::TYPE_WORDPERFECT => array(
				'wp|wpd' => 'application/wordperfect'
			),

			// iWork formats
			General_Media_Types::TYPE_IWORK       => array(
				'key'     => 'application/vnd.apple.keynote',
				'numbers' => 'application/vnd.apple.numbers',
				'pages'   => 'application/vnd.apple.pages'
			)
		);

		$required_types = $this->current_user_exempt() ? array_keys($mime_types) : $this->general_allowed_types;
		$return = array();
		foreach ($required_types as $required_type) {
			$return = $return + $mime_types[ $required_type ];
		}

		return $return;
	}

	/**
	 * @return mixed
	 */
	public function get_exempt_roles()
	{
		return $this->exempt_roles;
	}

	/**
	 * @return mixed
	 */
	public function get_general_allowed_types()
	{
		return $this->general_allowed_types;
	}

	/**
	 * @return mixed
	 */
	public function force_allow_uploads()
	{
		return $this->force_allow_uploads;
	}

	/**
	 * @return mixed
	 */
	public function get_max_upload_size()
	{
		return $this->max_upload_size;
	}

	/**
	 * @return mixed
	 */
	public function own_media_only()
	{
		return $this->own_media_only;
	}

	/**
	 * @return mixed
	 */
	public function get_file_too_large_error()
	{
		return $this->file_too_large_error;
	}
}