<?php
namespace WidgiLabs\WP\Plugin\iDealBiz\GFFieldAddon\Fields;

class PostAttachments extends \GF_Field {

	/**
	 * @var string $type The field type.
	 */
	public $type = 'post_attachments';

	/**
	 * Return the field title, for use in the form editor.
	 *
	 * @return string
	 */
	public function get_form_editor_field_title() {
		return esc_attr__( 'Attachments', 'idealbiz-gf-field-addon' );
	}

	/**
	 * Assign the field button to the Advanced Fields group.
	 *
	 * @return array
	 */
	public function get_form_editor_button() {
		return array(
			'group' => 'post_fields',
			'text'  => $this->get_form_editor_field_title(),
		);
	}

	/**
	 * The settings which should be available on the field in the form editor.
	 *
	 * @return array
	 */
	function get_form_editor_field_settings() {
		return array(
			'admin_label_setting',
			'attachment_extensions_setting',
			'attachment_files_setting',
			'attachment_meta_setting',
			'attachment_size_setting',
			'conditional_logic_field_setting',
			'css_class_setting',
			'default_value_setting',
			'description_setting',
			'duplicate_setting',
			'error_message_setting',
			'idealbiz_meta_key_setting',
			'label_placement_setting',
			'label_setting',
			'prepopulate_field_setting',
			'rules_setting',
			'size_setting',
			'visibility_setting',
		);
	}

	/**
	 * Enable this field for use with conditional logic.
	 *
	 * @return bool
	 */
	public function is_conditional_logic_supported() {
		return true;
	}

	/**
	 * Define the fields inner markup.
	 *
	 * @param array $form The Form Object currently being processed.
	 * @param string|array $value The field value. From default/dynamic population, $_POST, or a resumed incomplete submission.
	 * @param null|array $entry Null or the Entry Object currently being edited.
	 *
	 * @return string
	 */
	public function get_field_input( $form, $value = '', $entry = null ) {
		$id              = absint( $this->id );
		$form_id         = absint( $form['id'] );
		$is_entry_detail = $this->is_entry_detail();
		$is_form_editor  = $this->is_form_editor();
		$attributes      = array();

		// Prepare the value of the input ID attribute.
		$field_id = $is_entry_detail || $is_form_editor || $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";

		// Prepare the input classes.
		$size         = $this->size;
		$class_suffix = $is_entry_detail ? '_admin' : '';
		$class        = $size . $class_suffix;
		$css_class    = trim( esc_attr( $class ) . ' gfield_dropzone' );

		// Prepare the other input attributes.
		$attributes['tabindex']            = $this->get_tabindex();
		$attributes['logic_event']         = $this->get_conditional_logic_event( 'change' );
		$attributes['required_attribute']  = $this->isRequired ? 'aria-required="true"' : '';
		$attributes['invalid_attribute']   = $this->failed_validation ? 'aria-invalid="true"' : 'aria-invalid="false"';
		$dropzone_attrs['data_url']        = sprintf( 'data-url="%s"', add_query_arg( 'idealbiz_gf_upload', 1, home_url( '/' ) ) );
		$dropzone_attrs['data_disabled']   = $is_form_editor ? 'data-disabled="disabled"' : '';
		$dropzone_attrs['data_input_id']   = sprintf( 'data-input-id="%s"', $field_id );
		$dropzone_attrs['data_extensions'] = empty( $this->idealbizAttachmentExtensions ) ? '' : sprintf( 'data-extensions="%s"', $this->idealbizAttachmentExtensions );
		$dropzone_attrs['data_filesize']   = sprintf( 'data-size="%s"', $this->idealbizAttachmentSize );
		$dropzone_attrs['data_files']      = sprintf( 'data-files="%s"', $this->idealbizAttachmentFiles );

		$gallery = $is_form_editor ? '' : $this->get_gallery( $value, $field_id );

		// Prepare the input tag for this field.
		$input = sprintf(
			'<input type="hidden" name="input_%1$s" id="%2$s" value="%3$s" %4$s>
			<div id="%2$s_dropzone" class="%5$s dropzone" %6$s></div>',
			$id,
			$field_id,
			esc_attr( $value ),
			implode( ' ', $attributes ),
			$css_class,
			implode( ' ', $dropzone_attrs )
		);

		return sprintf(
			'<div class="ginput_container ginput_container_%1$s">
				%2$s
				%3$s
			</div>',
			$this->type,
			$gallery,
			$input
		);
	}

	/**
	 * Get the current gallery.
	 *
	 * @since  1.0.0
	 * @param  string $value Current field value.
	 * @return string        Gallery markup.
	 */
	private function get_gallery( $value, $field_id ) {
		$attachment_ids = explode( ',', $value );
		$attachments    = '';

		foreach ( $attachment_ids as $key => $id ) {
			$image = wp_get_attachment_image( $id, 'thumbnail', false, array(
				'data-id' => $id,
			) );

			if ( empty( $image ) ) {
				unset( $attachment_ids[ $key ] );
				continue;
			}

			$attachments .= sprintf(
				'<div class="item">
					<span class="item-delete"></span>
					%s
				</div>',
				$image
			);
		}

		$ficheiros= 'files';
		$de= 'of';
		if (get_locale() == 'pt_PT') {
			$ficheiros= 'ficheiros';
			$de= 'de';
		}

		return sprintf(
			'<div class="attachment-count"><span class="current-files">%1$d</span> '.$ficheiros.'%2$s</div>
			<div class="attachment-gallery" data-field-id="%3$s">%4$s</div>',
			count( $attachment_ids ),
			empty( $this->idealbizAttachmentFiles ) ? '' : sprintf( ' '.$de.' <span class="total-files">%s</span>', $this->idealbizAttachmentFiles ),
			$field_id,
			$attachments
		);
	}
}
