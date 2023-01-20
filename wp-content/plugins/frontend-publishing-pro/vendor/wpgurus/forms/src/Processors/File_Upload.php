<?php

namespace WPGurus\Forms\Processors;

if (!defined('WPINC')) die;

use WPGurus\Forms\Elements\Media_File;
use WPGurus\Forms\Utils;

/**
 * The job of this form processor is to look for media file elements, upload their files into the media library and add the IDs of the uploaded items to the data array to be processed.
 *
 * Class File_Upload
 * @package WPGurus\Forms\Processors
 */
class File_Upload implements \WPGurus\Forms\Processor
{
	private $max_upload_size;
	private $allowed_types;
	private $file_too_large_error;
	private $invalid_type_error;

	const MAX_SIZE = 'file_upload_max_size';
	const ALLOWED_TYPES = 'file_upload_allowed_types';
	const FILE_TOO_LARGE_ERROR = 'file_upload_file_too_large_error_message';
	const INVALID_TYPE_ERROR = 'file_upload_invalid_type_error_message';

	function __construct($args = array())
	{
		$args = wp_parse_args(
			$args,
			array(
				self::MAX_SIZE             => 32768,
				self::ALLOWED_TYPES        => array('image/png', 'image/jpeg', 'image/gif'),
				self::FILE_TOO_LARGE_ERROR => '',
				self::INVALID_TYPE_ERROR   => ''
			)
		);

		$this->max_upload_size = $args[ self::MAX_SIZE ] * 1024;
		$this->allowed_types = $args[ self::ALLOWED_TYPES ];
		$this->file_too_large_error = $args[ self::FILE_TOO_LARGE_ERROR ];
		$this->invalid_type_error = $args[ self::INVALID_TYPE_ERROR ];
	}

	function do_process(&$is_valid, &$data, &$cleaned_data, $form)
	{
		if (!function_exists('wp_handle_upload')) {
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			require_once(ABSPATH . 'wp-admin/includes/media.php');
		}

		foreach ($form->get_elements() as $element) {
			if (is_a($element, '\WPGurus\Forms\Elements\Media_File')) {
				/**
				 * @var Media_File $element
				 */
				$key = $element->get_file_input_key();

				if (!isset($_FILES[ $key ]) || (isset($_FILES[ $key ]['error']) && $_FILES[ $key ]['error'])) {
					continue;
				}

				$error_exists = false;

				if (!in_array($_FILES[ $key ]['type'], $this->allowed_types)) {
					$element->add_errors($this->invalid_type_error);
					$error_exists = true;
				}

				if ($_FILES[ $key ]['size'] > $this->max_upload_size) {
					$element->add_errors($this->file_too_large_error);
					$error_exists = true;
				}

				if ($error_exists) {
					$element->set_valid(false);
					continue;
				}

				$media_id = media_handle_upload($key, 0);

				if (is_wp_error($media_id)) {
					$element->add_errors($media_id->get_error_messages());
					$element->set_valid(false);
				} else {
					Utils::add_to_array($media_id, $data, $element->get_key());
				}
			}
		}
	}
}