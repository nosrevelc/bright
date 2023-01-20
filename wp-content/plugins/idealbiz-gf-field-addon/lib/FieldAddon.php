<?php
namespace WidgiLabs\WP\Plugin\iDealBiz\GFFieldAddon;

class FieldAddon extends \GFAddOn {

	protected $_version = '1.0.0';
	protected $_min_gravityforms_version = '1.9';
	protected $_slug = 'idealbiz-gf-field-addon';
	protected $_path = 'idealbiz-gf-field-addon/idealbiz-gf-field-addon.php';
	protected $_full_path = __FILE__;
	protected $_title = 'Gravity Forms iDealBiz Field Add-On';
	protected $_short_title = 'iDealBiz Field Add-On';

	/**
	 * @var object $_instance If available, contains an instance of this class.
	 */
	private static $_instance = null;

	/**
	 * Returns an instance of this class, and stores it in the $_instance property.
	 *
	 * @return object $_instance An instance of this class.
	 */
	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Include the field early so it is available when entry exports are being performed.
	 */
	public function pre_init() {
		parent::pre_init();

		if ( ! $this->is_gravityforms_supported() || ! class_exists( 'GF_Field' ) ) {
			return;
		}

		$fields = array(
			'custom_taxonomy'  => new Fields\PostCustomTaxonomy(),
			'post_attachments' => new Fields\PostAttachments(),
		);


		foreach ( $fields as $field ) {
			\GF_Fields::register( $field );
		}
	}

	public function init_admin() {
		parent::init_admin();

		add_action( 'gform_editor_js', array( $this, 'editor_script' ) );

		// Tooltip informations.
		add_filter( 'gform_tooltips', array( $this, 'tooltips' ) );

		// Custom field settings.
		add_action( 'gform_field_standard_settings', array( $this, 'general_setting' ), 10, 2 );

	}

	/**
	 * Bind initial value event.
	 *
	 * @since 1.0.0
	 */
	public static function editor_script() {
	    echo '<script type="text/javascript">
	        //adding setting to fields of type "text"
	        fieldSettings.custom_taxonomy += ", .custom_taxonomy";
			fieldSettings.attachment_meta += ", .attachment_meta";
			fieldSettings.attachment_extensions += ", .attachment_extensions";
			fieldSettings.attachment_size += ", .attachment_size";
			fieldSettings.attachment_files += ", .attachment_files";

	        //binding to the load field settings event to initialize the checkbox
	        jQuery(document).bind("gform_load_field_settings", function(event, field, form){
	            jQuery("#custom_taxonomy_setting").val(field.idealbizCustomTaxonomy);
				jQuery("#attachment_meta_setting").val(field.idealbizAttachmentMeta);
				jQuery("#attachment_extensions_setting").val(field.idealbizAttachmentExtensions);
				jQuery("#attachment_size_setting").val(field.idealbizAttachmentSize);
				jQuery("#attachment_files_setting").val(field.idealbizAttachmentFiles);
	        });
	    </script>';
	}

	/**
	 * Include my_script.js when the form contains a 'simple' type field.
	 *
	 * @return array
	 */
	public function scripts() {
		$scripts = array(
			array(
				'handle'    => 'idealbiz_addon_dropzone',
				'src'       => plugin_dir_url( __FILE__ ) . '../js/dropzone.js',
				'version'   => '5.1.1',
				'deps'      => array( 'jquery' ),
				'in_footer' => true,
				'enqueue'   => array(
					array( 'field_types' => array( 'post_attachments' ) ),
				),
			),
			array(
				'handle'    => 'idealbiz_addon_sortable',
				'src'       => plugin_dir_url( __FILE__ ) . '../js/Sortable.js',
				'version'   => '1.6.0',
				'deps'      => array( 'jquery' ),
				'in_footer' => true,
				'enqueue'   => array(
					array( 'field_types' => array( 'post_attachments' ) ),
				),
			),
			array(
				'handle'    => 'idealbiz_post_attachments',
				'src'       => plugin_dir_url( __FILE__ ) . '../js/post_attachments.js',
				'version'   => '1.0.0',
				'deps'      => array( 'jquery', 'idealbiz_addon_dropzone' ),
				'in_footer' => true,
				'enqueue'   => array(
					array( 'field_types' => array( 'post_attachments' ) ),
				),
			),
		);

		return array_merge( parent::scripts(), $scripts );
	}

	/**
	 * Include my_styles.css when the form contains a 'simple' type field.
	 *
	 * @return array
	 */
	public function styles() {
		$styles = array(
			array(
				'handle'  => 'idealbiz_dropzone_style',
				'src'     => plugin_dir_url( __FILE__ ) . '../css/dropzone.css',
				'version' => '5.1.1',
				'enqueue' => array(
					array( 'field_types' => array( 'post_attachments' ) )
				)
			),
			array(
				'handle'  => 'idealbiz_dropzone_base_style',
				'src'     => plugin_dir_url( __FILE__ ) . '../css/dropzone-base.css',
				'version' => '5.1.1',
				'enqueue' => array(
					array( 'field_types' => array( 'post_attachments' ) ),
				),
			),
			array(
				'handle'  => 'idealbiz_post_attachments_style',
				'src'     => plugin_dir_url( __FILE__ ) . '../css/post_attachments.css',
				'version' => '1.0.0',
				'enqueue' => array(
					array( 'field_types' => array( 'post_attachments' ) ),
				),
			),
		);

		return array_merge( parent::styles(), $styles );
	}

	/**
	 * Add the tooltips for the field.
	 *
	 * @since  1.0.0
	 * @param  array $tooltips An associative array of tooltips where the key is
	 *                         the tooltip name and the value is the tooltip.
	 * @return array
	 */
	public function tooltips( $tooltips ) {
		$tooltips['custom_taxonomy_setting'] = sprintf(
			'<h6>%1$s</h6>
			%2$s',
			esc_html__( 'Field Taxonomy', 'idealbiz-gf-field-addon' ),
			esc_html__( 'Choose a taxonomy from the list. The taxonomies can be repeated, because they are listed by post_type and can be related to more than one.', 'idealbiz-gf-field-addon' )
		);
		$tooltips['attachment_meta_setting'] = sprintf(
			'<h6>%1$s</h6>
			%2$s',
			esc_html__( 'Post Attachments', 'idealbiz-gf-field-addon' ),
			esc_html__( 'This value will be used as the meta key to store an array of IDs for all the attachments added through this field. An empty value will still ahve the attachments uploaded, but will only set them to have the created post as their parent.', 'idealbiz-gf-field-addon' )
		);
		$tooltips['attachment_extensions_setting'] = sprintf(
			'<h6>%1$s</h6>
			%2$s
			%3$s',
			esc_html__( 'Attachment extensions', 'idealbiz-gf-field-addon' ),
			esc_html__( 'Provide a comma separated list with all the file extensions you want to allow. If empty, all extensions are allowed.', 'idealbiz-gf-field-addon' ),
			'<p><code>.jpg,.jpeg,.png</code></p>'
		);
		$tooltips['attachment_size_setting'] = sprintf(
			'<h6>%1$s</h6>
			%2$s',
			esc_html__( 'Attachment size (MB)', 'idealbiz-gf-field-addon' ),
			esc_html__( 'Maximum file size allowed for uploaded files.', 'idealbiz-gf-field-addon' )
		);
		$tooltips['attachment_files_setting'] = sprintf(
			'<h6>%1$s</h6>
			%2$s',
			esc_html__( 'Number of files', 'idealbiz-gf-field-addon' ),
			esc_html__( 'Maximum number of files that can be uploaded.', 'idealbiz-gf-field-addon' )
		);

		return $tooltips;
	}

	/**
	 * Add the custom setting for the General tab.
	 *
	 * @param int $position The position the settings should be located at.
	 * @param int $form_id  The ID of the form currently being edited.
	 */
	public static function general_setting( $position, $form_id ) {

		if ( $position !== 20 ) {
			return;
		}

		// Custom taxonomy settings.
		static::render_custom_taxonomy_setting();

		// Post attachments settings.
		static::render_attachment_meta_setting();
		static::render_attachment_extensions_setting();
		static::render_attachment_size_setting();
		static::render_attachment_files_setting();
	}

	/**
	 * Render custom taxonomy selection.
	 *
	 * @since 1.0.0
	 */
	private static function render_custom_taxonomy_setting() {
		$option_groups = '';
		$post_types    = get_post_types( array(), 'objects' );

		foreach ( $post_types as $type ) {

			$taxonomies = get_taxonomies( array(
				'object_type' => array( $type->name ),
				'_builtin'    => false,
			), 'objects' );
			$options = '';

			foreach ( $taxonomies as $taxonomy ) {
				$options .= sprintf(
					'<option value="%1$s">%2$s</option>',
					esc_attr( $taxonomy->name ),
					esc_html( $taxonomy->label )
				);
			}

			$option_groups .= sprintf(
				'<optgroup label="%1$s">%2$s</optgroup>',
				esc_attr( $type->label ),
				$options
			);
		}

		printf(
			'<li class="custom_taxonomy_setting field_setting">
				<label for="custom_taxonomy" class="section_label">
					%1$s
					%2$s
				</label>
				<select id="custom_taxonomy_setting" onchange="SetFieldProperty(\'idealbizCustomTaxonomy\', jQuery(this).val());">
					<option value="">%3$s</option>
					%4$s
				</select>
			</li>',
			esc_html__( 'Custom taxonomy', 'idealbiz-gf-field-addon' ),
			gform_tooltip( 'custom_taxonomy_setting', '', true ),
			esc_html__( 'Select taxonomy', 'idealbiz-gf-field-addon' ),
			$option_groups
		);
	}

	/**
	 * Render attachment meta key setting.
	 *
	 * @since 1.0.0
	 */
	private static function render_attachment_meta_setting() {
		printf(
			'<li class="attachment_meta_setting field_setting">
				<label for="attachment_meta" class="section_label">
					%1$s
					%2$s
				</label>
				<input id="attachment_meta_setting" type="text" onchange="SetFieldProperty(\'idealbizAttachmentMeta\', jQuery(this).val());">
			</li>',
			esc_html__( 'Attachment meta key', 'idealbiz-gf-field-addon' ),
			gform_tooltip( 'attachment_meta_setting', '', true )
		);
	}

	/**
	 * Render attachment extensions setting.
	 *
	 * @since 1.0.0
	 */
	private static function render_attachment_extensions_setting() {
		printf(
			'<li class="attachment_extensions_setting field_setting">
				<label for="attachment_extensions" class="section_label">
					%1$s
					%2$s
				</label>
				<input id="attachment_extensions_setting" type="text" onchange="SetFieldProperty(\'idealbizAttachmentExtensions\', jQuery(this).val());">
			</li>',
			esc_html__( 'Attachment extensions', 'idealbiz-gf-field-addon' ),
			gform_tooltip( 'attachment_extensions_setting', '', true )
		);
	}

	/**
	 * Render attachment file size setting.
	 *
	 * @since 1.0.0
	 */
	private static function render_attachment_size_setting() {
		printf(
			'<li class="attachment_size_setting field_setting">
				<label for="attachment_size" class="section_label">
					%1$s
					%2$s
				</label>
				<input id="attachment_size_setting" type="number" min="0" step="1" onchange="SetFieldProperty(\'idealbizAttachmentSize\', jQuery(this).val());">
			</li>',
			esc_html__( 'Attachment size (MB)', 'idealbiz-gf-field-addon' ),
			gform_tooltip( 'attachment_size_setting', '', true )
		);
	}

	/**
	 * Render attachment maximum files to upload setting.
	 *
	 * @since 1.0.0
	 */
	private static function render_attachment_files_setting() {
		printf(
			'<li class="attachment_files_setting field_setting">
				<label for="attachment_files" class="section_label">
					%1$s
					%2$s
				</label>
				<input id="attachment_files_setting" type="number" min="0" step="1" onchange="SetFieldProperty(\'idealbizAttachmentFiles\', jQuery(this).val());">
			</li>',
			esc_html__( 'Number of files', 'idealbiz-gf-field-addon' ),
			gform_tooltip( 'attachment_files_setting', '', true )
		);
	}
}
