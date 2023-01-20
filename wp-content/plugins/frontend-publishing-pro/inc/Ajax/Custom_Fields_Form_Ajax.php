<?php
namespace WPFEPP\Ajax;

if (!defined('WPINC')) die;

class Custom_Fields_Form_Ajax extends \WPFEPP\Ajax
{
	const ACTION = 'wpfepp_create_custom_field';
	const META_KEY_SEARCH_ACTION = 'wpfepp_find_meta_key';

	function __construct()
	{
		parent::__construct();
		$this->register_action(self::ACTION_PREFIX . self::ACTION, array($this, 'create_custom_field'));
		$this->register_action( self::ACTION_PREFIX . self::META_KEY_SEARCH_ACTION, array(
			$this,
			'find_meta_key',
		) );
	}

	function find_meta_key() {
		$results = array();
		if ( ! empty( $_GET['term'] ) ) {
			global $wpdb;

			$query = "SELECT DISTINCT(meta_key) AS id, meta_key AS label, meta_key AS value FROM {$wpdb->postmeta} " .
			         "WHERE meta_key LIKE %s LIMIT 10";
			$term = $_GET['term'];
			$like = '%' . $wpdb->esc_like( $term ) . '%';
			$results = $wpdb->get_results( $wpdb->prepare( $query, $like ) );
		}
		die( wp_json_encode( $results ) );
	}

	function create_custom_field()
	{
		$post_type = $_POST[ \WPFEPP\Element_Containers\Custom_Fields_Container::ELEM_KEY_POST_TYPE ];
		$form = new \WPFEPP\Forms\Custom_Fields_Form($post_type);

		$success = $form->handle_submission();

		$result = array(
			'success' => $success
		);

		if ($success) {
			$result['field_html'] = $form->get_custom_field_html();
		} else {
			$result['errors'] = $form->get_printable_errors();
		}

		die(json_encode($result));
	}
}