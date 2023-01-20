<?php

namespace WPGurus\DB;

if (!defined('WPINC')) die;

abstract class Table
{
	const COLUMN_ID = 'id';

	const COLUMN_DETAIL_TYPE = 'type';
	const COLUMN_DETAIL_ATTRS = 'attrs';
	const COLUMN_DETAIL_SERIALIZED = 'serialized';

	const COLUMN_TYPE_STRING = 'string';
	const COLUMN_TYPE_INT = 'int';
	const COLUMN_TYPE_FLOAT = 'float';
	/**
	 * The DB table name. It is supposed to be provided by the child class.
	 * @var string
	 */
	private $table_name;

	/**
	 * Class constructor.
	 * @param $name string The name of the DB table.
	 */
	function __construct($name)
	{
		global $wpdb;
		$this->db = $wpdb;
		$this->db->flush();
		$this->db->hide_errors();

		$this->table_name = $name;
		$this->columns = $this->process_columns($this->get_columns());
	}

	/**
	 * Returns an array containing information regarding the DB columns.
	 * @return array
	 */
	abstract public function get_columns();

	/**
	 * Getter for DB table name.
	 * @return string
	 */
	public function get_table_name()
	{
		return $this->table_name;
	}

	/**
	 * @param $columns
	 * @return array
	 */
	private function process_columns($columns)
	{
		return array_map(
			array(
				$this,
				'set_column_defaults'
			),
			$columns
		);
	}

	/**
	 * Fills in the missing values in the passed array containing the information regarding a column.
	 * @param $column array
	 * @return array The passed array with all the missing values filled in with defaults.
	 */
	private function set_column_defaults($column)
	{
		return wp_parse_args(
			$column,
			array(
				self::COLUMN_DETAIL_TYPE       => self::COLUMN_TYPE_STRING,
				self::COLUMN_DETAIL_ATTRS      => array(),
				self::COLUMN_DETAIL_SERIALIZED => false
			)
		);
	}

	/**
	 * Builds an SQL string that can be used in the table creation query.
	 * @return string
	 */
	private function build_columns_string()
	{
		$string = array();
		foreach ($this->columns as $column_key => $column) {
			$string[] = sprintf('%s %s', $column_key, implode(' ', $column[ self::COLUMN_DETAIL_ATTRS ]));
		}
		return implode(',' . PHP_EOL, $string);
	}

	/**
	 * Creates/Upgrades the DB table using the method given here: https://codex.wordpress.org/Creating_Tables_with_Plugins
	 */
	public function create_table()
	{
		$charset_collate = $this->db->get_charset_collate();
		$id_column = self::COLUMN_ID;

		$sql_format = "
			CREATE TABLE $this->table_name (
				$id_column mediumint(9) NOT NULL AUTO_INCREMENT,
				%s,
				UNIQUE KEY $id_column ($id_column)
			) $charset_collate;
		";

		$query = sprintf($sql_format, $this->build_columns_string());

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($query);
	}

	/**
	 * Drops the database table.
	 * @return false|int Number of rows affected/selected or false on error.
	 */
	public function remove_table()
	{
		$query = "DROP TABLE $this->table_name";
		return $this->db->query($query);
	}

	/**
	 * Returns the last error that occurred when talking to the DB table.
	 * @return string
	 */
	public function get_error()
	{
		return $this->db->last_error;
	}

	/**
	 * Fetches a row from the DB on the base of id. Passes the row through unserialize_row to handle any items that are in serialized form.
	 * @param $id string|int The ID of the row to be fetched from the table.
	 * @return array|null Returns null on error and when no results are found.
	 */
	public function get($id)
	{
		$where = $this->build_where(array(self::COLUMN_ID => $id));

		// $wpdb->get_row returns null on error and when no results are found.
		$query = "SELECT * FROM $this->table_name WHERE $where";
		$row = $this->db->get_row($query, ARRAY_A);

		if ($row !== null) {
			$row = $this->unserialize_row($row);
		}

		return $row;
	}

	/**
	 * Fetches a set of rows from the DB table.
	 * @param int $limit The maximum number of items to fetch.
	 * @param int $offset The initial offset.
	 * @param string $orderby Name of the column that we want to order the results by.
	 * @param string $order Order of the returned results DESC or ASC.
	 * @return array|null
	 */
	public function get_set($limit = PHP_INT_MAX, $offset = 0, $orderby = self::COLUMN_ID, $order = 'DESC')
	{
		$query = $this->db->prepare("SELECT * FROM $this->table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $limit, $offset);
		return $this->db->get_results($query);
	}

	/**
	 * Returns the total number of rows in the DB.
	 * @return int
	 */
	public function get_count()
	{
		$count = $this->db->get_var("SELECT COUNT(*) FROM $this->table_name");
		return isset($count) ? intval($count) : 0;
	}

	/**
	 * Inserts values in the DB table. Serializes the items that need to be serialized.
	 * @param $values array An associative array in which indices are column names and values are column values.
	 * @return false|int Returns false on error and 1 on success.
	 */
	public function add($values)
	{
		$values = $this->serialize_row($values);

		// $wpdb->insert returns false on error and 1 on success.
		$result = $this->db->insert($this->table_name, $values, $this->build_formats_array($values));
		return $result;
	}

	/**
	 * Returns the automatically generated ID of the last row successfully inserted into the table.
	 * @return int
	 */
	public function get_insert_id()
	{
		return $this->db->insert_id;
	}

	/**
	 * Updates the values of a particular row.
	 * @param $id int Row id.
	 * @param $values array Values that need to be updated.
	 * @return false|int Returns false on error and number of affected rows on success. The number of affected rows could be 0.
	 */
	public function update($id, $values)
	{
		$values = $this->serialize_row($values);
		// $wpdb->update returns false on error and number of affected rows on success. The number of affected rows could be 0.
		$result = $this->db->update($this->table_name, $values, array(self::COLUMN_ID => $id), $this->build_formats_array($values));
		return $result;
	}

	/**
	 * Removes a row from the DB table.
	 * @param $id int Row ID.
	 * @return false|int Returns FALSE if an error occurred and number of affected rows on success. The number of affected rows could be 0.
	 */
	public function remove($id)
	{
		$where = $this->build_where(array(self::COLUMN_ID => $id));
		$query = "DELETE FROM $this->table_name WHERE $where";

		// $wpdb->query returns FALSE if an error occurred and number of affected rows on success. The number of affected rows could be 0.
		$result = $this->db->query($query);
		return $result;
	}

	/**
	 * Several methods of WordPress' wpdb() class like insert and update require an array of formats to be passed along with the data. The elements from this array are used as placeholders to make sure only the expected data types make it to the database. This method uses the information present in the columns array to build such an array.
	 * @param $data array An array containing column names as indices and column values as values.
	 * @return array An array of formats.
	 */
	private function build_formats_array($data)
	{
		$formats = array();
		foreach ($data as $column => $value) {
			switch ($this->columns[ $column ][ self::COLUMN_DETAIL_TYPE ]) {
				case self::COLUMN_TYPE_STRING:
					$formats[] = '%s';
					break;

				case self::COLUMN_TYPE_INT:
					$formats[] = '%d';
					break;

				case self::COLUMN_TYPE_FLOAT:
					$formats[] = '%f';
					break;

				default:
					$formats[] = '%s';
					break;
			}
		}
		return $formats;
	}

	/**
	 * Builds the where clause for various SQL statements.
	 * @param $clauses int[] Components to be used to build the whole where clause. Is of the form array(COLUMN_NAME => COLUMN_VALUE|array).
	 * @return string
	 */
	private function build_where($clauses)
	{
		$where = array();
		foreach ($clauses as $column => $value) {
			if (is_array($value)) {
				$value = array_map('intval', $value);
				$list = implode(',', $value);
				$where[] = "$column IN ($list)";
			} else {
				$value = intval($value);
				$where[] = "$column = $value";
			}
		}

		return implode(' AND ', $where);
	}

	/**
	 * Goes through all the items in a row and serializes the ones that need to be serialized.
	 * @param $row array A array of values for the DB table.
	 * @return mixed The passed array with the necessary values serialized.
	 */
	protected function serialize_row($row)
	{
		foreach ($this->columns as $column_key => $column) {
			if ($column[ self::COLUMN_DETAIL_SERIALIZED ] && isset($row[ $column_key ])) {
				$row[ $column_key ] = $this->serialize($row[ $column_key ]);
			}
		}

		return $row;
	}

	/**
	 * Goes through all the items in a row and unserializes the ones that need to be unserialized.
	 * @param $row array A row from the DB table.
	 * @return mixed The passed array with the necessary values unserialized.
	 */
	protected function unserialize_row($row)
	{
		foreach ($this->columns as $column_key => $column) {
			if ($column[ self::COLUMN_DETAIL_SERIALIZED ] && isset($row[ $column_key ])) {
				$row[ $column_key ] = $this->unserialize($row[ $column_key ]);
			}
		}

		return $row;
	}

	/**
	 * Serializes a single item.
	 * @param $item
	 * @return string The serialized value.
	 */
	protected function serialize($item)
	{
		return base64_encode(serialize($item));
	}

	/**
	 * Unserializes a string.
	 * @param $item string
	 * @return mixed The recovered value.
	 */
	protected function unserialize($item)
	{
		if (base64_decode($item, true) !== false)
			$item = base64_decode($item);

		return unserialize($item);
	}
}