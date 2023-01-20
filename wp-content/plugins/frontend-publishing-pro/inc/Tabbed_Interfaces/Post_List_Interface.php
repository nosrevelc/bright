<?php
namespace WPFEPP\Tabbed_Interfaces;

if (!defined('WPINC')) die;

use WPFEPP\Components\Rewrites;
use WPFEPP\Constants\Assets;
use WPFEPP\Constants\Frontend_Form_Messages;
use WPFEPP\Constants\Plugin_Forms;
use WPFEPP\Constants\Post_List_Settings;
use WPFEPP\Constants\Post_List_Tabs;
use WPFEPP\Constants\Query_Vars;
use WPFEPP\Factories\Form_Factory;
use WPFEPP\Forms\Frontend_Form;
use WPFEPP\Tabs\Frontend_Tab;
use WPGurus\Tabs\Tabbed_Interface;
use WPGurus\Utils\Array_Utils;

/**
 * Displays user's post's on the front-end in the form of tabs. Each tab represents a post status.
 * @package WPFEPP\Tabbed_Interfaces
 */
class Post_List_Interface extends Tabbed_Interface
{
	/**
	 * The form that will be used for editing posts in this post list. It also contains the post type which will be used by the list.
	 * @var Frontend_Form
	 */
	private $frontend_form;

	/**
	 * Post list settings. This is obtained from the included frontend form.
	 * @var array|null
	 */
	private $settings;

	/**
	 * The post list can either display a list of posts or it can display a form for editing a particular post. The mode data member indicates which state is currently active.
	 * @var string
	 */
	private $mode;

	/**
	 * The ID of the post that is to be edited.
	 * @var int
	 */
	private $post_id;

	/**
	 * The slug of the tab that is currently active.
	 * @var mixed
	 */
	private $tab_slug;

	/**
	 * The post type against which this list will lookup posts.
	 * @var string
	 */
	private $post_type;

	/**
	 * The database ID of the form.
	 * @var int
	 */
	private $form_db_id;

	/**
	 * The possible states that the interface can be in.
	 */
	const MODE_EDIT = 'edit';
	const MODE_VIEW = 'view';

	/**
	 * Post_List_Interface constructor.
	 * @param $form_db_id int The DB ID of the form that will be used for editing posts.
	 * @param $current_post \WP_Post This is the post containing the list interface. It is not the post being edited.
	 */
	function __construct($form_db_id, $current_post)
	{
		$this->tab_slug = $this->get_tab_slug();
		$this->post_id = $this->get_post_id();
		$this->form_db_id = $form_db_id;
		$this->frontend_form = Form_Factory::make_form(
			Plugin_Forms::FRONTEND_FORM,
			array(
				$form_db_id,
				($this->post_id) ? $this->post_id : 0
			)
		);

		if ($this->frontend_form->is_valid()) {
			$this->settings = $this->frontend_form->get_post_list_settings();
			$this->post_type = $this->frontend_form->get_post_type();
		}

		// If the tab slug is not available but the post ID is then we are editing a post. Otherwise we are viewing posts.
		if (!$this->tab_slug && $this->post_id)
			$this->mode = self::MODE_EDIT;
		else
			$this->mode = self::MODE_VIEW;

		parent::__construct(
			array(
				Tabbed_Interface::WRAPPER_CLASS     => 'post-list-container',
				Tabbed_Interface::NAV_WRAPPER_CLASS => $this->get_nav_container_class(),
				Tabbed_Interface::NAV_CLASS         => 'post-list-tab-nav-item',
				Tabbed_Interface::ACTIVE_CLASS      => 'post-list-tab-nav-item-active'
			)
		);

		$this->create_tabs($current_post->ID);
	}

	/**
	 * Uses the post list settings to check if a tab is active and should be displayed.
	 * @param $tab string The tab slug.
	 * @return bool Whether or not the tab should be displayed.
	 */
	private function is_tab_active($tab)
	{
		return $this->settings ? in_array($tab, $this->settings[ Post_List_Settings::ACTIVE_TABS ]) : false;
	}

	/**
	 * Instantiates all the tabs and adds them to the post list.
	 * @param $post_id int ID of the post which contains this post list interface.
	 */
	private function create_tabs($post_id)
	{
		$statuses = array();

		if ($this->is_tab_active(Post_List_Tabs::PUBLISHED))
			$statuses[ Post_List_Tabs::PUBLISHED ] = __('Published', 'frontend-publishing-pro');

		if ($this->is_tab_active(Post_List_Tabs::PENDING))
			$statuses[ Post_List_Tabs::PENDING ] = __('Pending', 'frontend-publishing-pro');

		if ($this->is_tab_active(Post_List_Tabs::DRAFT))
			$statuses[ Post_List_Tabs::DRAFT ] = __('Draft', 'frontend-publishing-pro');

		foreach ($statuses as $name => $label) {
			$tab = new Frontend_Tab(
				$name,
				$label,
				get_permalink($post_id),
				$this
			);
			$this->add_tab($tab);
		}
	}

	/**
	 * Prints an error message on the page.
	 * @param $error string The error message.
	 */
	private function print_error_message($error)
	{
		?>
		<div class="post-list-message post-list-message-error">
			<?php echo $error; ?>
		</div>
		<?php
	}

	/**
	 * Prints a success message on the page.
	 * @param $message string The success message.
	 */
	private function print_success_message($message)
	{
		?>
		<div class="post-list-message post-list-message-success">
			<?php echo $message; ?>
		</div>
		<?php
	}

	/**
	 * Renders the post list UI.
	 */
	public function render()
	{
		wp_enqueue_script(Assets::POST_LIST_SCRIPT);
		wp_enqueue_style(Assets::POST_LIST_STYLE);
		if (!wp_style_is(\WPGurus\Forms\Constants\Assets::FONT_AWESOME_CSS)) {
			wp_enqueue_style(\WPFEPP\Constants\Assets::FONT_AWESOME_CSS);
		}

		?>
		<div class="post-list-interface">
			<?php

			if ($this->settings[ Post_List_Settings::REQUIRE_LOGIN ] && !is_user_logged_in()) {
				if ($this->settings[ Post_List_Settings::REDIRECT_TO_LOGIN ]) {
					\WPFEPP\login_redirect();
				} else {
					$frontend_messages = $this->frontend_form->get_frontend_messages();
					$this->print_error_message($frontend_messages[ Frontend_Form_Messages::SETTING_LOGIN_REQUIRED_ERROR ]);
				}
				return;
			}

			if (!$this->frontend_form->is_valid()) {
				$this->print_error_message(__("The form ID that you have added is not valid.", 'frontend-publishing-pro'));
				return;
			}

						$post_status = get_post_field('post_status', $this->post_id);
			if ($this->mode == self::MODE_EDIT && !in_array($post_status, $this->settings[ Post_List_Settings::EDIT_COLUMN_TABS ])) {
				$this->print_error_message(__("This post can not be edited.", 'frontend-publishing-pro'));
				$this->mode = self::MODE_VIEW;
			}

			$deleted = Array_Utils::get($_GET, Query_Vars::POST_DELETED);
			if ($deleted) {
				$this->print_success_message(__("The post has been deleted successfully.", 'frontend-publishing-pro'));
			}

			switch ($this->mode) {
				case self::MODE_VIEW:
					parent::render();
					break;

				case self::MODE_EDIT:
					$this->frontend_form->render();
					break;
			}

			?>
		</div>
		<?php
	}

	/**
	 * Extracts the slug of the current tab from the URL.
	 * @return string Tab slug.
	 */
	private function get_tab_slug()
	{
		return \WPFEPP\get_query_var_part(Rewrites::TAB_ENDPOINT, 0);
	}

	/**
	 * Extracts the ID of the post that is to be edited.
	 * @return int
	 */
	private function get_post_id()
	{
		return \WPFEPP\get_query_var_part(Rewrites::EDIT_ENDPOINT, 0);
	}

	private function get_nav_container_class()
	{
		$nav_container_class = 'post-list-tab-nav';
		return $this->settings[Post_List_Settings::HIDE_TABS] ? $nav_container_class . ' post-list-tab-nav-hidden' : $nav_container_class;
	}

	/**
	 * Returns the post type of the form and post list interface.
	 * @return string
	 */
	public function get_post_type()
	{
		return $this->post_type;
	}

	/**
	 * Returns an array containing the settings of the list interface.
	 * @return array|null
	 */
	public function get_settings()
	{
		return $this->settings;
	}

	/**
	 * Returns the database ID of the edit form.
	 * @return int
	 */
	public function get_form_db_id()
	{
		return $this->form_db_id;
	}

	/**
	 * Returns the edit form object.
	 * @return Frontend_Form
	 */
	public function get_frontend_form()
	{
		return $this->frontend_form;
	}
}