<?php

namespace WPFEPP\Components;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Post_Meta_Keys;
use WPGurus\Components\Component;
use WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Element;
use WPGurus\Forms\Elements\Select;
use WPGurus\Forms\Factories\Element_Factory;
use WPGurus\Utils\Array_Utils;

class Post_Form_Link_Meta_Box extends Component
{
	const BOX_ID = 'post-frontend-form-link-meta-box';
	const FORM_META_FIELD_NAME = 'frontend_form_id_meta_field';
	const FORM_META_NONCE_NAME = 'frontend_form_id_meta_nonce';
	const FORM_META_NONCE_ACTION = 'frontend_form_id_meta_nonce_%s_action';

	function __construct()
	{
		parent::__construct();

		$this->register_action('add_meta_boxes', array($this, 'add_post_meta_box'));
		$this->register_action('save_post', 'save_post_meta', 10, 1);
	}

	/**
	 * Calls WP add_meta_box
	 */
	function add_post_meta_box()
	{
		add_meta_box(
			self::BOX_ID,
			__('Frontend Form', 'frontend-publishing-pro'),
			array($this, 'display_meta_box'),
			\WPFEPP\get_post_types(),
			'side',
			'default',
			null
		);
	}

	/**
	 * Displays the meta box.
	 * @param $post \WP_Post
	 */
	function display_meta_box($post)
	{
		wp_nonce_field(
			sprintf(self::FORM_META_NONCE_ACTION, $post->ID),
			self::FORM_META_NONCE_NAME,
			false
		);

		$choices = \WPFEPP\get_forms_for_post_type($post->post_type);

		$select = Element_Factory::make_element(
			Elements::SELECT,
			array(
				Select::KEY     => self::FORM_META_FIELD_NAME,
				Select::LABEL   => __('Frontend Form: ', 'frontend-publishing-pro'),
				Select::CHOICES => $choices,
				Select::VALUE   => get_post_meta($post->ID, Post_Meta_Keys::FORM_ID, true)
			)
		);

		$select->render();
	}

	/**
	 * Saves post meta value.
	 * @param $post_id int
	 */
	function save_post_meta($post_id)
	{
		$meta_value = Array_Utils::get($_POST, array(self::FORM_META_FIELD_NAME));
		$nonce = Array_Utils::get($_POST, array(self::FORM_META_NONCE_NAME));
		$nonce_verified = wp_verify_nonce(
			$nonce,
			sprintf(self::FORM_META_NONCE_ACTION, $post_id)
		);

		// If the value is null then it means that the field isn't present in the form that this call came from.
		// In other words the user might have submitted a form other than the one we want.
		if (!$nonce_verified || $meta_value === null) {
			return;
		}

		if ($meta_value === '') {
			delete_post_meta($post_id, Post_Meta_Keys::FORM_ID);
		} else {
			update_post_meta($post_id, Post_Meta_Keys::FORM_ID, $meta_value);
		}
	}
}