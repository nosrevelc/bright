<?php

namespace WPFEPP\Admin_Pages;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Assets;
use WPFEPP\Constants\Plugin_Forms;
use WPFEPP\Constants\Post_Meta_Keys;
use WPFEPP\Constants\Query_Vars;
use WPFEPP\Constants\Tabbed_Interfaces;
use WPFEPP\DB_Tables\Forms;
use WPFEPP\DB_Tables\Post_Meta_Table;
use WPFEPP\Factories\Form_Factory;
use WPFEPP\Factories\Tabbed_Interface_Factory;
use WPFEPP\Forms\Form_Details_Form;
use WPFEPP\Shortcodes\Frontend_Form;
use WPFEPP\Shortcodes\Post_Table;
use WPGurus\Components\Component;
use WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Element;
use WPGurus\Forms\Factories\Element_Factory;
use WPGurus\Tabs\Tabbed_Interface;
use WPGurus\Utils\Array_Utils;

class Form_Manager extends Component
{
	private $page_hook;
	private $forms_table;
	private $post_meta_table;

	const PAGE_ID = 'wpfepp_form_manager';
	const DELETION_NONCE = 'post_table_form_deletion';
	const ACTION_DELETE = 'delete';
	const ACTION_EDIT = 'edit';

	function __construct()
	{
		$this->post_meta_table = new Post_Meta_Table();
		$this->forms_table = new Forms();
		$this->register_action('admin_menu', array($this, 'add_menu_item'));
		$this->register_action('admin_enqueue_scripts', array($this, 'enqueue'));
		$this->register_action('admin_init', array($this, 'delete_forms'));
		$this->register_action('admin_notices', array($this, 'admin_notices'));

		parent::__construct();
	}

	public function get_page_hook()
	{
		return $this->page_hook;
	}

	public function get_page_id()
	{
		return self::PAGE_ID;
	}

	public static function is_page()
	{
		global $pagenow;

		return $pagenow == 'admin.php' && isset($_GET['page']) && $_GET['page'] == self::PAGE_ID;
	}

	/**
	 * Displays a message on the option page whenever an item is deleted or updated.
	 **/
	public function admin_notices()
	{
		if (!$this->is_page())
			return;

		$deleted = Array_Utils::get($_GET, Query_Vars::FORM_MANAGER_DELETED);
		if ($deleted === null) {
			return;
		}

		if ($deleted == 1) {
			?>
			<div class="updated">
				<p><?php _e('Form deleted successfully!', 'frontend-publishing-pro'); ?></p>
			</div>
			<?php
		} else {
			$posts_using_form = Array_Utils::get($_GET, Query_Vars::FORM_MANAGER_POST_COUNT);
			if (is_numeric($posts_using_form) && $posts_using_form > 0) {
				$edit_link = $this->make_bulk_edit_link();
				?>
				<div class="error">
					<p><?php printf(__('The form could not be deleted because it is associated with %s post(s). You can change or remove these associations by bulk editing the posts %s.', 'frontend-publishing-pro'), $posts_using_form, $edit_link); ?></p>
				</div>
				<?php
			} else {
				?>
				<div class="error">
					<p><?php _e('The form could not be deleted.', 'frontend-publishing-pro'); ?></p>
				</div>
				<?php
			}
		}
	}

	/**
	 * Deals with the delete action of the list table.
	 **/
	public function delete_forms()
	{
		if (!$this->is_page())
			return;

		$action = Array_Utils::get($_GET, Query_Vars::FORM_MANAGER_ACTION);
		$form_id = Array_Utils::get($_GET, Query_Vars::FORM_MANAGER_FORM_ID);
		$nonce = Array_Utils::get($_GET, Query_Vars::FORM_MANAGER_DELETION_NONCE);

		if (
			$action == self::ACTION_DELETE
			&& is_numeric($form_id)
			&& wp_verify_nonce($nonce, self::DELETION_NONCE)
		) {
			$posts_using_form = $this->get_number_of_posts_using_form($form_id);
			$form = $this->forms_table->get($form_id);

			if ($posts_using_form == null) {
				// The form is not associated with any posts and can be deleted safely
				$deleted = intval($this->forms_table->remove($form_id));
			} else {
				$deleted = 0;
			}
			$sendback = esc_url_raw(
				add_query_arg(
					array(
						Query_Vars::PAGE                    => $_GET['page'],
						Query_Vars::FORM_MANAGER_DELETED    => $deleted,
						Query_Vars::FORM_MANAGER_POST_COUNT => $posts_using_form,
						Query_Vars::FORM_MANAGER_FORM_ID    => $form_id,
						Query_Vars::FORM_MANAGER_POST_TYPE  => $form[ Forms::COLUMN_POST_TYPE ]
					),
					''
				)
			);
			wp_redirect($sendback);
		}
	}

	/**
	 * Enqueues resources for the form manager page.
	 * @param $hook string Page hook.
	 */
	public function enqueue($hook)
	{
		if ($this->page_hook != $hook)
			return;

		wp_enqueue_script(Assets::FORM_MANAGER_JS);
		wp_enqueue_style(Assets::FORM_MANAGER_CSS);
	}

	/**
	 * Adds menu item for the form manager in admin menu.
	 */
	public function add_menu_item()
	{
		$this->page_hook = add_menu_page(
			__('Frontend Publishing Pro', 'frontend-publishing-pro'),
			__('Frontend Publishing Pro', 'frontend-publishing-pro'),
			'manage_options',
			self::PAGE_ID,
			array($this, 'render'),
			plugins_url("assets/img/icon.png", dirname(dirname(__FILE__)))
		);

		add_submenu_page(
			self::PAGE_ID,
			__('Frontend Publishing Forms', 'frontend-publishing-pro'),
			__('Forms', 'frontend-publishing-pro'),
			'manage_options',
			self::PAGE_ID
		);
	}

	/**
	 * Displays contents for the form manager page.
	 */
	public function render()
	{
		// TODO: Move this to a template.
		$form_id = Array_Utils::get($_GET, Query_Vars::FORM_MANAGER_FORM_ID);
		$action = Array_Utils::get($_GET, Query_Vars::FORM_MANAGER_ACTION);
		?>
		<div class="wrap">
			<?php if ($form_id != null && $action == self::ACTION_EDIT): ?>
				<?php $form = $this->forms_table->get($form_id); ?>
				<?php if ($form): ?>
					<h2>
						<?php echo $form[ Forms::COLUMN_NAME ] . ' '; ?><?php _e('Settings', 'frontend-publishing-pro'); ?>
					</h2>
					<div class="form-shortcodes-container">
						<p>
							<?php
							$form_shortcode = Element_Factory::make_element(
								Elements::TEXT,
								array(
									Element::KEY        => 'shortcode',
									Element::VALUE      => $this->form_shortcode_example($form_id),
									Element::ATTRIBUTES => array('readonly' => 'readonly')
								)
							);
							$form_shortcode->render();
							?>
						</p>

						<p>
							<?php
							$form_shortcode = Element_Factory::make_element(
								Elements::TEXT,
								array(
									Element::KEY        => 'shortcode',
									Element::VALUE      => $this->post_table_shortcode_example($form_id),
									Element::ATTRIBUTES => array('readonly' => 'readonly')
								)
							);
							$form_shortcode->render();
							?>
						</p>
					</div>
					<?php
					$tabbed_interface = Tabbed_Interface_Factory::make_interface(
						Tabbed_Interfaces::FORM_MANAGER,
						array(
							array(
								Tabbed_Interface::NAV_WRAPPER_CLASS => 'nav-tab-wrapper',
								Tabbed_Interface::NAV_CLASS         => 'nav-tab',
								Tabbed_Interface::ACTIVE_CLASS      => 'nav-tab-active'
							)
						)
					);

					$tabbed_interface->add_tab(
						new \WPFEPP\Tabs\Form_Fields_Tab(
							$form_id,
							$form[ Forms::COLUMN_POST_TYPE ]
						)
					);

					$tabbed_interface->add_tab(
						new \WPFEPP\Tabs\Form_Settings_Tab(
							$form_id
						)
					);

					$tabbed_interface->add_tab(
						new \WPFEPP\Tabs\Form_Emails_Tab(
							$form_id
						)
					);

					$tabbed_interface->add_tab(
						new \WPFEPP\Tabs\Form_Post_List_Tab(
							$form_id
						)
					);

					$tabbed_interface->render();
					?>
				<?php else: ?>
					<?php _e("No form with the given ID was found in the database.", 'frontend-publishing-pro'); ?>
				<?php endif; ?>
			<?php else: ?>
				<h2><?php _e('Frontend Publishing Forms', 'frontend-publishing-pro'); ?></h2>
				<div class="form-manager-main">
					<h3><?php _e('Create a New Form', 'frontend-publishing-pro') ?></h3>
					<?php
					/**
					 * @var Form_Details_Form $details
					 */
					$details = Form_Factory::make_form(
						Plugin_Forms::FORM_DETAILS_FORM,
						self::PAGE_ID
					);
					$details->render();
					?>
				</div>

				<div class="form-manager-side">
					<div class="form-manager-list-table">
						<?php
						$table = new \WPFEPP\List_Tables\Forms(self::PAGE_ID);
						$table->render();
						?>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * @return string
	 */
	private function make_bulk_edit_link()
	{
		$form_id = Array_Utils::get($_GET, Query_Vars::FORM_MANAGER_FORM_ID);
		$post_type = Array_Utils::get($_GET, Query_Vars::FORM_MANAGER_POST_TYPE);
		$edit_url = admin_url(sprintf('edit.php?post_type=%s&%s=%s', $post_type, Query_Vars::QUICK_EDIT_FORM_ID, $form_id));
		return sprintf('<a href="%s">%s</a>', $edit_url, 'here');
	}

	/**
	 * @param $form_id
	 * @return mixed
	 */
	private function get_number_of_posts_using_form($form_id)
	{
		$meta_values = $this->post_meta_table->get_all_values(Post_Meta_Keys::FORM_ID);
		$counts = array_count_values($meta_values);
		$posts_using_form = Array_Utils::get($counts, intval($form_id));
		return $posts_using_form;
	}

	/**
	 * @param $form_id
	 * @return string
	 */
	private function form_shortcode_example($form_id)
	{
		return sprintf('[%s form="%s"]', Frontend_Form::SHORTCODE, $form_id);
	}

	/**
	 * @param $form_id
	 * @return string
	 */
	private function post_table_shortcode_example($form_id)
	{
		return sprintf('[%s form="%s"]', Post_Table::SHORTCODE, $form_id);
	}
}