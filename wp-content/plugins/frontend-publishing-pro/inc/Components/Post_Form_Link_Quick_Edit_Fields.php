<?php
namespace WPFEPP\Components;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Assets;
use WPFEPP\Constants\Post_Meta_Keys;
use WPFEPP\Constants\Query_Vars;
use WPFEPP\DB_Tables\Forms;
use WPGurus\Components\Component;
use WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Element;
use WPGurus\Forms\Elements\Select;
use WPGurus\Forms\Factories\Element_Factory;
use WPGurus\Utils\Array_Utils;

class Post_Form_Link_Quick_Edit_Fields extends Component
{
	const FORM_LINK_COLUMN = 'post_frontend_form_link_col';
	const QUICK_EDIT_FIELD_NAME = 'frontend_form_id_quick_edit_field';
	const CURRENT_FORM_ID_VALUE_FIELD = 'frontend-form-id-value';
	const QUICK_EDIT_NONCE_NAME = 'frontend_form_id_quick_edit_nonce';
	const QUICK_EDIT_NONCE_ACTION = 'frontend_form_id_quick_edit_nonce_action';

	function __construct()
	{
		parent::__construct();

		$this->register_filter('manage_posts_columns', array($this, 'add_frontend_form_column'));
		$this->register_filter('manage_pages_columns', array($this, 'add_frontend_form_column'));

		$this->register_action('manage_posts_custom_column', array($this, 'add_frontend_form_column_contents'), 10, 2);
		$this->register_action('manage_pages_custom_column', array($this, 'add_frontend_form_column_contents'), 10, 2);

		$this->register_action('quick_edit_custom_box', array($this, 'add_quick_edit_field'), 10, 2);
		$this->register_action('bulk_edit_custom_box', array($this, 'add_bulk_edit_field'), 10, 2);

		$this->register_action('save_post', 'save_meta_value');

		$this->register_action('admin_enqueue_scripts', array($this, 'enqueue_resources'));

		$this->register_filter('pre_get_posts', array($this, 'show_posts_associated_with_form'));
	}

	/**
	 * Enqueues resources on the edit list page.
	 */
	public function enqueue_resources()
	{
		global $pagenow;

		if ($pagenow != 'edit.php') {
			return;
		}

		wp_enqueue_style(Assets::QUICK_EDIT_CSS);
		wp_enqueue_script(Assets::QUICK_EDIT_JS);
	}

	/**
	 * Adds a new column to the passed array of columns.
	 * @param $columns array Existing columns.
	 * @return array Passed array with a new column added to it.
	 */
	public function add_frontend_form_column($columns)
	{
		$columns[ self::FORM_LINK_COLUMN ] = __('Frontend Form', 'frontend-publishing-pro');

		return $columns;
	}

	/**
	 * Adds a link and a hidden field to the new column.
	 * @param $column_name string The current column name.
	 * @param $post_id int Post id of the current row.
	 */
	public function add_frontend_form_column_contents($column_name, $post_id)
	{
		if (!$this->is_link_column($column_name)) {
			return;
		}

		$frontend_form_id = get_post_meta($post_id, Post_Meta_Keys::FORM_ID, true);
		$this->print_visible_value($post_id, $frontend_form_id);

		$hidden_input = Element_Factory::make_element(
			Elements::HIDDEN,
			array(
				Element::KEY        => self::CURRENT_FORM_ID_VALUE_FIELD . '-' . $post_id,
				Element::VALUE      => $frontend_form_id,
				Element::ATTRIBUTES => array('class' => self::CURRENT_FORM_ID_VALUE_FIELD)
			)
		);
		$hidden_input->render();
	}

	/**
	 * Adds a field to the edit fields.
	 * @param $column_name string The name of the current column.
	 * @param $post_type string The post type of the page.
	 */
	public function add_quick_edit_field($column_name, $post_type)
	{
		$this->make_and_print_field($column_name, $post_type, null, '');
	}

	/**
	 * Adds a new field to the bulk edit section.
	 * @param $column_name string The name of the current column.
	 * @param $post_type string The post type of the page.
	 */
	public function add_bulk_edit_field($column_name, $post_type)
	{
		$initial_choices = array(
			'-1' => sprintf('— %s —', __('No Change', 'frontend-publishing-pro')),
			''   => sprintf('— %s —', __('No Form', 'frontend-publishing-pro'))
		);

		$this->make_and_print_field($column_name, $post_type, $initial_choices, '-1');
	}

	/**
	 * Saves meta value originating from the quick edit or bulk edit sections.
	 * @param $post_id int The post ID for which to save meta value.
	 */
	public function save_meta_value($post_id)
	{
		$meta_value = $this->get_meta_value();
		$nonce = $this->get_nonce_value();
		$nonce_verified = wp_verify_nonce($nonce, self::QUICK_EDIT_NONCE_ACTION);

		if (!$nonce_verified || $meta_value === null || $meta_value == '-1') {
			return;
		}

		if ($meta_value === '') {
			delete_post_meta($post_id, Post_Meta_Keys::FORM_ID);
		} else {
			update_post_meta($post_id, Post_Meta_Keys::FORM_ID, $meta_value);
		}
	}

	/**
	 * @param $column_name
	 * @return bool
	 */
	private function is_link_column($column_name)
	{
		return $column_name == self::FORM_LINK_COLUMN;
	}

	/**
	 * @param $column_name
	 * @param $post_type
	 * @param $initial_choices
	 * @param $select_value
	 */
	private function make_and_print_field($column_name, $post_type, $initial_choices, $select_value)
	{
		if (!$this->is_link_column($column_name)) {
			return;
		}

		$select = Element_Factory::make_element(
			Elements::SELECT,
			array(
				Select::KEY      => self::QUICK_EDIT_FIELD_NAME,
				Select::LABEL    => __('FEP Form: ', 'frontend-publishing-pro'),
				Select::CHOICES  => \WPFEPP\get_forms_for_post_type($post_type, $initial_choices),
				Select::VALUE    => $select_value,
				Select::TEMPLATE => WPFEPP_ELEMENT_TEMPLATES_DIR . 'quick-edit-element-template.php'
			)
		);

		?>
		<fieldset class="inline-edit-col-left">&nbsp;</fieldset>
		<fieldset class="inline-edit-col-right" style="margin-top: 0;">
			<div class="inline-edit-col">
				<?php wp_nonce_field(self::QUICK_EDIT_NONCE_ACTION, self::QUICK_EDIT_NONCE_NAME, false); ?>
				<?php $select->render(); ?>
			</div>
		</fieldset>
		<?php
	}

	/**
	 * @return mixed
	 */
	private function get_meta_value()
	{
		$meta_value = Array_Utils::get($_POST, array(self::QUICK_EDIT_FIELD_NAME));

		if ($meta_value === null) {
			$meta_value = Array_Utils::get($_GET, array(self::QUICK_EDIT_FIELD_NAME));
		}

		return $meta_value;
	}

	private function get_nonce_value()
	{
		$nonce_value = Array_Utils::get($_POST, array(self::QUICK_EDIT_NONCE_NAME));

		if ($nonce_value === null) {
			$nonce_value = Array_Utils::get($_GET, array(self::QUICK_EDIT_NONCE_NAME));
		}

		return $nonce_value;
	}

	/**
	 * @param $frontend_form_id
	 */
	private function print_visible_value($post_id, $frontend_form_id)
	{
		$forms_table = new Forms();
		$form = $forms_table->get($frontend_form_id);
		$post = get_post($post_id);
		if ($form != null) {
			$form_name = $form[ Forms::COLUMN_NAME ];
			$edit_url = admin_url(
				sprintf('edit.php?post_type=%s&%s=%s', $post->post_type, Query_Vars::QUICK_EDIT_FORM_ID, $frontend_form_id)
			);
			?>
			<a href="<?php echo $edit_url; ?>"><?php echo $form_name; ?></a>
			<?php
		}
	}

	/**
	 * Filters the query and adds a new condition for displaying posts associated with a particular form.
	 * @param $query \WP_Query Unfiltered query.
	 * @return \WP_Query Filtered query.
	 */
	public function show_posts_associated_with_form($query)
	{
		if (!$this->should_show_posts_associated_with_form($query)) {
			return $query;
		}

		$meta_query = $query->get('meta_query');
		$form_condition = array(
			'key'     => Post_Meta_Keys::FORM_ID,
			'value'   => Array_Utils::get($_GET, Query_Vars::QUICK_EDIT_FORM_ID),
			'compare' => '='
		);

		if (!$meta_query) {
			$meta_query = array();
		}

		$meta_query[] = $form_condition;
		$query->set(
			'meta_query',
			$meta_query
		);

		return $query;
	}

	/**
	 * @param $query \WP_Query
	 * @return bool
	 */
	private function should_show_posts_associated_with_form($query)
	{
		global $pagenow;
		$form_id = Array_Utils::get($_GET, Query_Vars::QUICK_EDIT_FORM_ID);

		return $query->is_main_query() && is_admin() && $pagenow == 'edit.php' && $form_id != null;
	}
}