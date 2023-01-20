<?php
namespace WidgiLabs\WP\Plugin\iDealBiz\GFFieldAddon\Fields;

class PostCustomTaxonomy extends \GF_Field {

	/**
	 * @var string $type The field type.
	 */
	public $type = 'custom_taxonomy';

	/**
	 * Return the field title, for use in the form editor.
	 *
	 * @return string
	 */
	public function get_form_editor_field_title() {
		return esc_attr__( 'Custom Taxonomy', 'idealbiz-gf-field-addon' );
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
			'conditional_logic_field_setting',
			'css_class_setting',
			'default_value_setting',
			'description_setting',
			'duplicate_setting',
			'enable_enhanced_ui_setting',
			'error_message_setting',
			'custom_taxonomy_setting',
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

		// Prepare the value of the input ID attribute.
		$field_id = $is_entry_detail || $is_form_editor || $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";

		// Prepare the input classes.
		$size         = $this->size;
		$class_suffix = $is_entry_detail ? '_admin' : '';
		$class        = $size . $class_suffix;
		$css_class    = trim( esc_attr( $class ) . ' gfield_select' );

		// Prepare the other input attributes.
		$tabindex           = $this->get_tabindex();
		$logic_event        = $this->get_conditional_logic_event( 'change' );
		$required_attribute = $this->isRequired ? 'aria-required="true"' : '';
		$invalid_attribute  = $this->failed_validation ? 'aria-invalid="true"' : 'aria-invalid="false"';
		$disabled_text      = $is_form_editor ? 'disabled="disabled"' : '';



		// Options.
		$options = static::get_taxonomy_options( $form['fields'], $id, $value );



		// Prepare the input tag for this field.
		$attributes = "{$tabindex} {$logic_event} {$required_attribute} {$invalid_attribute} {$disabled_text}";
		$input      = sprintf(
			'<select name="input_%1$s" id="%2$s" class="%3$s" %4$s>%5$s</select>',
			$id,
			$field_id,
			$css_class,
			$attributes,
			$options
		);

		return sprintf( "<div class='ginput_container ginput_container_%s'>%s</div>", $this->type, $input );
	}

	/**
	 * Get options for the select field.
	 *
	 * @since  1.0.0
	 * @param  array  $fields   Form fields.
	 * @param  int    $field_id Field ID for the current field.
	 * @param  string $value    Current selected value.
	 * @return string $options  The field options.
	 */
	private static function get_taxonomy_options( $fields, $field_id, $value ) {

		// Get taxonomy for field.
		$taxonomy = '';
		foreach ( $fields as $field ) {
			if ( $field['id'] !== $field_id ) {
				continue;
			}
			$taxonomy = $field['idealbizCustomTaxonomy'];
			$class    = $field['cssClass'];
		}

		if ( empty( $taxonomy ) ) {
			return '';
		}


		/*
		 * This will get values if needed. There is a problem with this custom
		 * post taxonomy field, where on edit listing value is not properly
		 * filled, this is a workaround that knows we do have only 2 custom
		 * taxonomy fields for the same taxonomy listing_cat
		 * */
		$parent = false;
		if ( empty( $value ) &&  isset($_REQUEST['listing_id'] ) ) {
			$listing_id = $_REQUEST['listing_id'];
			$terms      = wp_get_post_terms( $listing_id, $taxonomy );

			foreach( $terms as $term ) {
				if( $term->parent  )
				{
					$parent     = $term->parent;
					$sub_cat_id = $term->term_id;
				}
				else {
					$value = $term->term_id;
				}
			}
		}


		// Build options.
		$args = array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
			'parent'     => 0,
		);

		if ( 'listing_subcat' === $class ) {
			$args = array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			);

			if ( $parent ) {
				$args['parent'] = $parent;
				$value          = $sub_cat_id;
			}
		}

		$terms = get_terms( $args );

		if ( empty( $terms ) ) {
			return '';
		}

		$options = '<option value="">' . __( 'Select option', 'idealbiz' ) . '</option>';

		foreach ( $terms as $term ) {
			$options .= sprintf(
				'<option value="%1$s" %3$s>%2$s</option>',
				esc_attr( $term->term_id ),
				esc_html( $term->name ),
				selected( $term->term_id, $value, false )
			);
		}

		return $options;
	}
}
