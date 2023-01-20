<?php

namespace WPFEPP\DB_Tables;

if (!defined('WPINC')) die;

/**
 * Makes interaction with the forms DB table easier.
 */
class Forms extends Table
{
	/**
	 * Column names.
	 */
	const COLUMN_NAME = 'name';
	const COLUMN_POST_TYPE = 'post_type';
	const COLUMN_DESCRIPTION = 'description';
	/**
	 * @var Form_Meta Used to interact with the form meta DB table.
	 */
	private $meta_table;

	/**
	 * Forms constructor.
	 */
	function __construct()
	{
		parent::__construct('frontend_post_forms', '2.0');

		$this->meta_table = new Form_Meta();
	}

	/**
	 * Returns column information required by the parent class.
	 * @return array
	 */
	public function get_columns()
	{
		return array(
			self::COLUMN_NAME        => array(
				self::COLUMN_DETAIL_ATTRS => array('TINYTEXT', 'NOT NULL')
			),
			self::COLUMN_POST_TYPE   => array(
				self::COLUMN_DETAIL_ATTRS => array('TINYTEXT', 'NOT NULL')
			),
			self::COLUMN_DESCRIPTION => array(
				self::COLUMN_DETAIL_ATTRS => array('TEXT', 'NULL')
			)
		);
	}

	/**
	 * An overridden function that deletes all the meta rows associated with a form before calling the original remove method.
	 * @param array|int $id The form ID.
	 * @return false|int False in case of an error otherwise number of rows removed.
	 */
	public function remove($id)
	{
		if (!is_array($id)) {
			$id = array($id);
		}

		foreach ($id as $form_id) {
			$this->meta_table->delete_meta_values($form_id);
		}

		return parent::remove($id);
	}

	public function get_by_post_type($post_type)
	{
		$post_type_column = self::COLUMN_POST_TYPE;
		$table_name = $this->get_table_name();

		$query = $this->db->prepare("SELECT * FROM $table_name WHERE $post_type_column = '%s'", $post_type);
		$rows = $this->db->get_results($query, ARRAY_A);

		return $rows;
	}
}