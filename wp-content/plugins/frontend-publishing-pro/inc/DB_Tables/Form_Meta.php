<?php
namespace WPFEPP\DB_Tables;

if (!defined('WPINC')) die;

/**
 * Makes interaction with the form meta DB table easier.
 */
class Form_Meta extends Table
{
	/**
	 * Table columns.
	 */
	const COLUMN_FORM_ID = 'form_id';
	const COLUMN_META_KEY = 'meta_key';
	const COLUMN_META_VALUE = 'meta_value';

	/**
	 * Form_Meta constructor.
	 */
	function __construct()
	{
		parent::__construct('frontend_post_form_meta', '1.0');
	}

	/**
	 * Returns column information required by the parent class.
	 * @return array
	 */
	public function get_columns()
	{
		return array(
			self::COLUMN_FORM_ID    => array(
				self::COLUMN_DETAIL_ATTRS => array('MEDIUMINT(9)', 'NOT NULL')
			),
			self::COLUMN_META_KEY   => array(
				self::COLUMN_DETAIL_ATTRS => array('TINYTEXT', 'NOT NULL')
			),
			self::COLUMN_META_VALUE => array(
				self::COLUMN_DETAIL_ATTRS      => array('LONGTEXT', 'NULL'),
				self::COLUMN_DETAIL_SERIALIZED => true
			)
		);
	}

	/**
	 * Returns a single meta value.
	 * @param $form_id int The ID of the form for which the meta value needs to be fetched.
	 * @param $meta_key string The meta key.
	 * @return mixed Meta value.
	 */
	public function get_meta_value($form_id, $meta_key)
	{
		$form_id_col = self::COLUMN_FORM_ID;
		$meta_key_col = self::COLUMN_META_KEY;
		$query = $this->db->prepare("SELECT * FROM {$this->get_table_name()} WHERE $form_id_col = %d AND $meta_key_col = %s", $form_id, $meta_key);
		$row = $this->db->get_row($query, ARRAY_A);
		$meta_value = null;

		if ($row != null) {
			$meta_value = $row[ self::COLUMN_META_VALUE ];
		}
		return $this->unserialize($meta_value);
	}

	/**
	 * Adds a meta value.
	 * @param $form_id int The ID of the form for which the meta value needs to be added.
	 * @param $meta_key string The meta key.
	 * @param $meta_value mixed The new meta value.
	 * @return false|int Number of rows added (1), or false in case of error.
	 */
	public function add_meta_value($form_id, $meta_key, $meta_value)
	{
		return $this->db->insert(
			$this->get_table_name(),
			array(
				Form_Meta::COLUMN_FORM_ID    => $form_id,
				Form_Meta::COLUMN_META_KEY   => $meta_key,
				Form_Meta::COLUMN_META_VALUE => $this->serialize($meta_value)
			)
		);
	}

	/**
	 * Updates a single meta value.
	 * @param $form_id int The ID of the form for which the meta value needs to be updated.
	 * @param $meta_key string The meta key.
	 * @param $meta_value mixed The new meta value.
	 * @return false|int Meta value.
	 */
	public function update_meta_value($form_id, $meta_key, $meta_value)
	{
		if (is_array($meta_value)) {
			$meta_value = $this->serialize($meta_value);
		}

		return $this->db->update(
			$this->get_table_name(),
			array(
				self::COLUMN_META_VALUE => $meta_value
			),
			array(
				self::COLUMN_FORM_ID  => $form_id,
				self::COLUMN_META_KEY => $meta_key
			)
		);
	}

	/**
	 * Deletes a single meta value.
	 * @param $form_id int The ID of the form for which the meta value needs to be deleted.
	 * @param $meta_key string The meta key.
	 * @return int|false The number of rows deleted, or false on error.
	 */
	public function delete_meta_value($form_id, $meta_key)
	{
		return $this->db->delete(
			$this->get_table_name(),
			array(
				self::COLUMN_FORM_ID  => $form_id,
				self::COLUMN_META_KEY => $meta_key
			)
		);
	}

	/**
	 * Deletes all the meta values for a form.
	 * @param $form_id int The ID of the form for which the meta value needs to be deleted.
	 * @return int|false The number of rows deleted, or false on error.
	 */
	public function delete_meta_values($form_id)
	{
		return $this->db->delete(
			$this->get_table_name(),
			array(
				self::COLUMN_FORM_ID => $form_id
			)
		);
	}

	/**
	 * Gets all the meta values for a form.
	 * @param $form_id int The ID of the form for which the meta values need to be fetched.
	 * @return array An associative array containing meta values as values and meta keys as keys.
	 */
	public function get_meta_values($form_id)
	{
		$form_id_col = self::COLUMN_FORM_ID;
		$query = $this->db->prepare("SELECT * FROM {$this->get_table_name()} WHERE $form_id_col = %d", $form_id);
		$rows = $this->db->get_results($query, ARRAY_A);
		$meta_values = array();

		if (is_array($rows) && count($rows)) {
			foreach ($rows as $row) {
				$meta_values[ $row[ self::COLUMN_META_KEY ] ] = $this->unserialize($row[ self::COLUMN_META_VALUE ]);
			}
		}
		return $meta_values;
	}
}