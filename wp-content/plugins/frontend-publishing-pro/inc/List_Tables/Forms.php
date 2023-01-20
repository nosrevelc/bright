<?php

namespace WPFEPP\List_Tables;

if (!defined('WPINC')) die;

use WPFEPP\Admin_Pages\Form_Manager;
use WPFEPP\Constants\Query_Vars;
use WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Element;
use WPGurus\Forms\Factories\Element_Factory;

/**
 * Creates a list table the WordPress backend. Extends WP_List_Table class provided by WordPress.
 *
 * @since 1.0.0
 * @package WPFEPP
 **/
class Forms extends \WPFEPP\WP_List_Table
{
	/**
	 * @var string The slug/id of the page on which this table is meant to be used.
	 */
	private $page;

	/**
	 * Initializes class attributes and invokes the constructor of parent class WP_List_Table
	 * @param string $screen The slug/id of the page on which this table is meant to be used.
	 */
	public function __construct($screen)
	{
		$this->page = $screen;

		parent::__construct(array(
			'singular' => 'form',
			'plural'   => 'forms',
			'ajax'     => true,
			'screen'   => $screen
		));
	}

	/**
	 * The default function used for printing column values. This function is used whenever a more specific function isn't available.
	 *
	 * @param array $item An associative array representing a row from the database table.
	 * @param string $column_name Name of the column currently being displayed.
	 * @return string Column value.
	 **/
	function column_default($item, $column_name)
	{
		return stripslashes($item->$column_name);
	}

	private function build_action_link($action, $item_id, $name)
	{
		$query_args = array(
			Query_Vars::PAGE                 => $_REQUEST['page'],
			Query_Vars::FORM_MANAGER_ACTION  => $action,
			Query_Vars::FORM_MANAGER_FORM_ID => $item_id
		);

		if ($action == Form_Manager::ACTION_DELETE) {
			$query_args[ Query_Vars::FORM_MANAGER_DELETION_NONCE ] = wp_create_nonce(Form_Manager::DELETION_NONCE);
		}

		$url = esc_url_raw(
			add_query_arg(
				$query_args,
				admin_url('admin.php')
			)
		);

		return sprintf(
			'<a href="%s">%s</a>',
			$url,
			$name
		);
	}

	/**
	 * Prints out the form name and action links in each row.
	 *
	 * @param Object $item An associative array representing a row from the database table.
	 * @return string Form name and action links formatted into a single string.
	 **/
	function column_name($item)
	{
		//Build row actions
		$actions = array(
			'edit'   => $this->build_action_link(Form_Manager::ACTION_EDIT, $item->id, __('Edit', 'frontend-publishing-pro')),
			'delete' => $this->build_action_link(Form_Manager::ACTION_DELETE, $item->id, __('Delete', 'frontend-publishing-pro'))
		);

		//Return the title contents
		return sprintf('%1$s %2$s',
			/*$1%s*/
			stripslashes($item->name),
			/*$2%s*/
			$this->row_actions($actions)
		);
	}

	/**
	 * Dictates the table's columns and their titles.
	 *
	 * @return array
	 **/
	function get_columns()
	{
		$columns = array(
			'name'        => __('Name', 'frontend-publishing-pro'),
			'post_type'   => __('Post Type', 'frontend-publishing-pro'),
			'description' => __('Description', 'frontend-publishing-pro'),
			'id'          => __('ID', 'frontend-publishing-pro'),
		);
		return $columns;
	}

	function get_default_primary_column_name() {
		return 'name';
	}

	/**
	 * Queries the database, sorts and filters the data, and gets it ready to be displayed.
	 **/
	public function prepare_items()
	{
		$per_page = 25;
		$hidden = array();
		$columns = $this->get_columns();
		$sortable = array();
		$curr_page = $this->get_pagenum();

		$db = new \WPFEPP\DB_Tables\Forms();
		$total_items = $db->get_count();
		$data = $db->get_set($per_page, ($curr_page - 1) * $per_page);

		$this->items = $data;
		$this->_column_headers = array($columns, $hidden, $sortable);

		$this->set_pagination_args(array(
			'total_items' => $total_items,                  //WE have to calculate the total number of items
			'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
			'total_pages' => ceil($total_items / $per_page)   //WE have to calculate the total number of pages
		));
	}

	public function render()
	{
		?>
		<form method="GET">
			<?php
			$this->prepare_items();
			$page_element = Element_Factory::make_element(
				Elements::HIDDEN,
				array(
					Element::KEY   => 'page',
					Element::VALUE => $this->page
				)
			);
			$page_element->render();
			$this->display();
			?>
		</form>
		<?php
	}

}