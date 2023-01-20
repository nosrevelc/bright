<?php
namespace WPFEPP\Tabs;

if (!defined('WPINC')) die;

use WPFEPP\Components\Post_Previews;
use WPFEPP\Components\Rewrites;
use WPFEPP\Constants\Plugin_Filters;
use WPFEPP\Constants\Post_List_Settings;
use WPFEPP\Constants\Post_List_Tabs;
use WPFEPP\Constants\Post_Meta_Keys;
use WPFEPP\Constants\Query_Vars;
use WPFEPP\Tabbed_Interfaces\Post_List_Interface;
use WPGurus\Tabs\Tab;
use WPGurus\Utils\Array_Utils;

class Frontend_Tab extends Tab
{
	const TEMPLATE_ARG_FORM_DB_ID = 'form_db_id';
	/**
	 * Permalink of the page which contains this tab.
	 * @var string
	 */
	private $permalink;
	/**
	 * The tab endpoint registered with WP rewrite api.
	 * @var string
	 */
	private $tab_endpoint;

	/**
	 * The edit endpoint registered with WP rewrite api.
	 * @var string
	 */
	private $edit_endpoint;

	/**
	 * The delete endpoint registered with WP rewrite api.
	 * @var string
	 */
	private $delete_endpoint;

	/**
	 * The post list settings.
	 * @var array
	 */
	private $settings;

	/**
	 * The post type for which posts will be fetched.
	 * @var string
	 */
	private $post_type;

	/**
	 * The DB id of the form that will be used for editing.
	 * @var int
	 */
	private $form_db_id;

	/**
	 * The interface that this tab belongs to.
	 * @var Post_List_Interface
	 */
	private $post_list_interface;

	/**
	 * Template arguments.
	 */
	const TEMPLATE_ARG_POSTS = 'posts';
	const TEMPLATE_ARG_TAB = 'tab';
	const TEMPLATE_ARG_POST_PREVIEW = 'post_preview';
	const TEMPLATE_ARG_EDIT_URL_FORMAT = 'edit_url_format';
	const TEMPLATE_ARG_PAGE_URL_FORMAT = 'page_url_format';
	const TEMPLATE_ARG_DELETE_URL_FORMAT = 'delete_url_format';
	const TEMPLATE_ARG_DELETION_NONCE_ACTION_FORMAT = 'deletion_nonce_action_format';
	const TEMPLATE_ARG_PAGE = 'page';
	const TEMPLATE_ARG_NEWER_EXIST = 'newer_exist';
	const TEMPLATE_ARG_OLDER_EXIST = 'older_exist';
	const TEMPLATE_ARG_SHOW_LINK_COL = 'show_link_col';
	const TEMPLATE_ARG_SHOW_EDIT_COL = 'show_edit_col';
	const TEMPLATE_ARG_SHOW_DELETE_COL = 'show_delete_col';
	const TEMPLATE_ARG_PREVIEW_NONCE_ACTION_FORMAT = 'preview_nonce_action_format';
	const TEMPLATE_ARG_PREVIEW_URL_FORMAT = 'preview_url_format';
	const TEMPLATE_ARG_POSTS_NOT_LIVE = 'posts_not_live';

	const DELETION_NONCE_ACTION = 'tab-%s-post-%s-deletion-nonce';

	/**
	 * Frontend_Tab constructor.
	 * @param $name string Tab name.
	 * @param $label string Tab label.
	 * @param $post_type string The post type to use when fetching posts.
	 * @param $permalink string The URL of the page containing this tab.
	 * @param $post_list_interface Post_List_Interface Post list settings.
	 */
	function __construct($name, $label, $permalink, $post_list_interface)
	{
		parent::__construct($name, $label);

		$this->permalink = trailingslashit($permalink);
		$this->post_type = $post_list_interface->get_post_type();
		$this->settings = $post_list_interface->get_settings();
		$this->form_db_id = $post_list_interface->get_form_db_id();
		$this->post_list_interface = $post_list_interface;
		$this->tab_endpoint = Rewrites::TAB_ENDPOINT;
		$this->edit_endpoint = Rewrites::EDIT_ENDPOINT;
		$this->delete_endpoint = Rewrites::DELETE_ENDPOINT;
	}

	/**
	 * Displays the frontend tab.
	 */
	function render()
	{
		$posts = new \WP_Query(
			$this->prepare_query_args()
		);

		$page_num = $this->get_page_num();

		$this->render_template(
			WPFEPP_TAB_TEMPLATES_DIR . 'frontend-tab/tab.php',
			array(
				self::TEMPLATE_ARG_POSTS                        => $posts,
				self::TEMPLATE_ARG_TAB                          => $this->get_name(),
				self::TEMPLATE_ARG_FORM_DB_ID                   => $this->form_db_id,
				self::TEMPLATE_ARG_EDIT_URL_FORMAT              => $this->make_edit_url_format(),
				self::TEMPLATE_ARG_PAGE_URL_FORMAT              => $this->make_page_url_format(),
				self::TEMPLATE_ARG_DELETE_URL_FORMAT            => $this->make_delete_url_format(),
				self::TEMPLATE_ARG_DELETION_NONCE_ACTION_FORMAT => sprintf(self::DELETION_NONCE_ACTION, $this->get_name(), '%s'),
				self::TEMPLATE_ARG_PREVIEW_NONCE_ACTION_FORMAT  => Post_Previews::POST_PREVIEW_NONCE_ACTION,
				self::TEMPLATE_ARG_PREVIEW_URL_FORMAT           => sprintf(Post_Previews::PREVIEW_LINK_FORMAT, home_url(), '%s', Query_Vars::POST_PREVIEW_NONCE, '%s'),
				self::TEMPLATE_ARG_POSTS_NOT_LIVE               => in_array($this->get_name(), array(Post_List_Tabs::PENDING, Post_List_Tabs::DRAFT)),
				self::TEMPLATE_ARG_PAGE                         => $page_num,
				self::TEMPLATE_ARG_NEWER_EXIST                  => $page_num > 1,
				self::TEMPLATE_ARG_OLDER_EXIST                  => ($page_num * $this->page_length()) < $posts->found_posts,
				self::TEMPLATE_ARG_SHOW_LINK_COL                => in_array($this->get_name(), $this->settings[ Post_List_Settings::LINK_COLUMN_TABS ]),
				self::TEMPLATE_ARG_SHOW_EDIT_COL                => in_array($this->get_name(), $this->settings[ Post_List_Settings::EDIT_COLUMN_TABS ]),
				self::TEMPLATE_ARG_SHOW_DELETE_COL              => in_array($this->get_name(), $this->settings[ Post_List_Settings::DELETE_COLUMN_TABS ])
			)
		);

		wp_reset_query();
		wp_reset_postdata();
	}

	/**
	 * Creates a URL format for post deletion in which post ID, deletion nonce and the active tab are placeholders.
	 * @return string URL format for post deletion.
	 */
	function make_delete_url_format()
	{
		return $this->permalink . $this->delete_endpoint . '/%d/?' . Query_Vars::POST_DELETER_NONCE . '=%s&' . Query_Vars::POST_DELETER_ACTIVE_TAB . '=' . $this->get_name();
	}

	/**
	 * Creates a URL format for post editing in which the post ID is a placeholder.
	 * @return string
	 */
	function make_edit_url_format()
	{
		return $this->permalink . $this->edit_endpoint . '/%d/';
	}

	/**
	 * Creates a URL format for pagination in which page number is a placeholder.
	 * @return string
	 */
	function make_page_url_format()
	{
		return $this->permalink . $this->tab_endpoint . '/' . $this->get_active_tab() . '/%d/';
	}

	function is_active($is_first)
	{
		$active_tab = $this->get_active_tab();

		if (($is_first && !$active_tab) || $active_tab == $this->get_name())
			return true;

		return false;
	}

	function make_url()
	{
		return $this->permalink . $this->tab_endpoint . '/' . $this->get_name() . '/';
	}

	/**
	 * Prepares arguments for fetching posts with WP_Query.
	 * @return array Query arguments.
	 */
	public function prepare_query_args()
	{
		$args = array();
		if (!\WPFEPP\current_user_can($this->settings[ Post_List_Settings::EDITOR_ROLES ])) {
			$args['author'] = get_current_user_id();
		}

		// The name of the tab is always a post status.
		$args['post_status'] = $this->get_name();

		$args['posts_per_page'] = $this->page_length();

		$args['post_type'] = $this->post_type;

		$args['paged'] = $this->get_page_num();

		if ( $this->frontend_posts_only() ) {
			$args['meta_query'] = array(
				array(
					'key'     => Post_Meta_Keys::FORM_ID,
					'value'   => $this->form_db_id,
					'compare' => '=',
				),
			);
		}

		$args = apply_filters(Plugin_Filters::FRONTEND_TAB_QUERY_ARGS, $args, $this);

		return $args;
	}

	/**
	 * Gets the slug of the currently active tab from the URL.
	 * @return string Tab slug.
	 */
	private function get_active_tab()
	{
		return \WPFEPP\get_query_var_part( $this->tab_endpoint, 0, Post_List_Tabs::PUBLISHED );
	}

	/**
	 * Gets the current page number from the URL (for pagination).
	 * @return int Page number.
	 */
	private function get_page_num()
	{
		return \WPFEPP\get_query_var_part($this->tab_endpoint, 1, 1);
	}

	/**
	 * Gets the page length from the post list settings.
	 * @return int
	 */
	private function page_length()
	{
		return $this->settings[ Post_List_Settings::PAGE_LENGTH ];
	}

	private function frontend_posts_only() {
		return (boolean) Array_Utils::get( $this->settings, Post_List_Settings::ONLY_FRONTEND_POSTS );
	}

	/**
	 * Returns the post list interface that this tab belongs in.
	 * @return Post_List_Interface
	 */
	public function get_post_list_interface()
	{
		return $this->post_list_interface;
	}
}