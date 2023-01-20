<?php
namespace WPFEPP\DB_Tables;

class Post_Meta_Table
{
	const COLUMN_META_ID = 'meta_id';
	const COLUMN_POST_ID = 'post_id';
	const COLUMN_META_KEY = 'meta_key';
	const COLUMN_META_VALUE = 'meta_value';

	/**
	 * Gets all meta values for a key.
	 * @param $meta_key string Meta key
	 * @return array Meta values.
	 */
	function get_all_values($meta_key)
	{
		/**
		 * @var \wpdb
		 */
		global $wpdb;

		$column_meta_value = self::COLUMN_META_VALUE;
		$column_meta_key = self::COLUMN_META_KEY;
		$query = $wpdb->prepare("SELECT {$column_meta_value} FROM {$wpdb->postmeta} WHERE {$column_meta_key} = '%s'", $meta_key);
		$wpdb->query($query);
		$values = array();

		if ($wpdb->last_result) {
			foreach ($wpdb->last_result as $row_object) {
				$values[] = $row_object->$column_meta_value;
			}
		}

		return $values;
	}
}