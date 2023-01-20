<?php
namespace WidgiLabs\WP\Plugin\iDealBiz\GFFieldAddon;

use WidgiLabs\WP\Plugin\iDealBiz\GFFieldAddon\Upload;

class Bootstrap {

	public function register_hooks() {
		add_filter( 'query_vars',              array( $this, 'add_upload_files_var' ) );
		add_action( 'parse_request',           array( $this, 'upload_files' ) );
		add_action( 'gform_loaded',            array( $this, 'load' ), 5 );
		add_filter( 'gform_post_data',         array( $this, 'save_post_fields' ), 10, 3 );
		add_action( 'gform_after_create_post', array( $this, 'update_attachments' ), 10, 3 );
		add_filter( 'gform_field_validation',  array( $this, 'validate_number_fields' ), 10, 4 );
	}

	/**
	 * Add upload query var.
	 *
	 * @since  1.0.0
	 * @param  array $vars The array of whitelisted query variables.
	 * @return array $vars Modified array of whitelisted query variables.
	 */
	public function add_upload_files_var( $vars ) {
		$vars[] = 'idealbiz_gf_upload';
		return $vars;
	}

	public function upload_files( $query ) {

		if ( ! is_user_logged_in() ) {
			return;
		}

		if ( ! isset( $query->query_vars['idealbiz_gf_upload'] ) ) {
			return;
		}

		$media_id = Upload::media();

		if ( is_wp_error( $media_id ) ) {
			wp_send_json_error( $media_id );
		}

		wp_send_json_success( array(
			'id'           => $media_id,
			'image_markup' => wp_get_attachment_image( $media_id, 'thumbnail', false, array(
				'data-id' => $media_id,
			) ),
		) );
	}

    public static function load() {

        if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
            return;
        }

		\GFForms::include_addon_framework();

        \GFAddOn::register( '\WidgiLabs\WP\Plugin\iDealBiz\GFFieldAddon\FieldAddon' );
    }

	/**
	 * Save post fields from custom GravityForms field types.
	 *
	 * @since  1.0.0
	 * @param  array $post_data Current data to be inserted for the post.
	 * @param  array $form      GravityForms form structure and settings.
	 * @param  array $lead      GravityForms submitted form information.
	 * @return array $post_data Modified data to be inserted.
	 */
	public static function save_post_fields( $post_data, $form, $lead ) {

		// Save custom_taxonomy.
		$fields = static::get_fields( $form['fields'], 'custom_taxonomy' );

		if ( ! empty( $fields ) ) {
			if ( ! isset( $post_data['tax_input'] ) ) {
				$post_data['tax_input'] = array();
			}

			foreach ( $fields as $field ) {

				$taxonomy = $field['idealbizCustomTaxonomy'];

				$new_cats = array( $lead[ $field['id'] ] );

				if( isset( $post_data['tax_input'][$taxonomy] ) ) {
					$previous_cat_id = $post_data['tax_input'][$taxonomy];
					$new_cats        = array_merge( $previous_cat_id, array( $lead[ $field['id'] ] ));

				}
				$post_data['tax_input'][$taxonomy] = $new_cats;
			}

		}

		// Save post_attachments.
		$fields = static::get_fields( $form['fields'], 'post_attachments' );

		if ( ! empty( $fields ) ) {
			if ( ! isset( $post_data['meta_input'] ) ) {
				$post_data['meta_input'] = array();
			}

			foreach ( $fields as $field ) {
				if ( empty( $field['idealbizAttachmentMeta'] ) ) {
					continue;
				}

				$post_data['meta_input'] = array_merge( $post_data['meta_input'], array(
					$field['idealbizAttachmentMeta'] => explode( ',', $lead[ $field['id'] ] ),
				) );
			}
		}

		return $post_data;
	}

	/**
	 * Update attachment's parent post.
	 *
	 * @since 1.0.0
	 * @param int   $post_id The created post ID.
	 * @param array $lead GravityForms submitted form information.
	 * @param array $form GravityForms form structure and settings.
	 */
	public function update_attachments( $post_id, $lead, $form ) {

		// Get post_attachments fields.
		$fields = static::get_fields( $form['fields'], 'post_attachments' );

		if ( ! empty( $fields ) ) {
			return;
		}

		foreach ( $fields as $field ) {
			if ( ! empty( $field['idealbizAttachmentMeta'] ) ) {
				continue;
			}

			$attachment_ids = explode( ',', $lead[ $field['id'] ] );

			foreach ( $attachment_ids as $id ) {
				wp_insert_attachment( array(
					'ID'          => $id,
					'post_parent' => $post_id,
				) );
			}
		}
	}

	/**
	 * Get fields by type.
	 *
	 * @since  1.0.0
	 * @param  array  $lead           GravityForms form fields.
	 * @param  string $type           The field type.
	 * @return array  $fields_of_type Fields of the requested type.
	 */
	private static function get_fields( $fields, $type ) {
		$fields_of_type = array();

		foreach( $fields as $field ) {
			if ( $field['type'] === $type ) {
				$fields_of_type[] = $field;
			}
		}
		return $fields_of_type;
	}


	/**
	 * Hooks on number field type validation.
	 * And for the prices with specific values we force them to be numbers.
	 * @param $result
	 * @param $value
	 * @param $form
	 * @param $field
	 *
	 * @return mixed
	 * @since 1.0.0
	 * @author Ana Aires ( ana@widgilabs.com )
	 */
	public function validate_number_fields( $result, $value, $form, $field ) {
		if ( 'number' !== $field->inputType ) {
			return $result; //exit if this is not a number field which we would like to add custom message
		}

		//This is a number field.
		$specific_price_fields = array( 'price_manual', 'revenue_manual', 'cash_flow_manual' );
		if ( in_array( $field->inputName, $specific_price_fields, true ) && empty( $value ) ) {
				$result['is_valid'] = false;
				$result['message']  = __( 'Please insert only numbers (0 to 9)', 'idealbiz-gf-field-addon' );
		}

		return $result;
	}
}
