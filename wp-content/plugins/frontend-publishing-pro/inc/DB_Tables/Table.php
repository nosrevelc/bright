<?php
namespace WPFEPP\DB_Tables;

if (!defined('WPINC')) die;

/**
 * Represents plugin's DB tables. Its main responsibility is to make sure that the forms get rebuilt properly when there is a version change.
 * @package WPFEPP\DB_Tables
 */
abstract class Table extends \WPGurus\DB\Table
{
	/**
	 * Table version.
	 * @var string
	 */
	protected $version;

	/**
	 * Table constructor.
	 * @param string $name Table name. The DB prefix of the site is appended to this name before usage.
	 * @param string $version Table version.
	 */
	function __construct($name, $version)
	{
		/**
		 * @var $wpdb \wpdb
		 */
		global $wpdb;
		parent::__construct($wpdb->get_blog_prefix() . $name);

		$this->version = $version;
	}

	/**
	 * An overridden method that ensures that the table gets rebuilt if the version in the options table and $version property do not match or if the table does not exist for some reason.
	 */
	public function create_table()
	{
		$last_version = $this->get_last_version();
		$table_name = $this->get_table_name();
		if ($last_version && $last_version == $this->version && $this->db->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
			return;
		}

		parent::create_table();

		$this->update_current_version($this->version);
	}

	/**
	 * Returns the last table version stored in the options table.
	 * @return string Table version.
	 */
	public function get_last_version()
	{
		return get_option(
			$this->get_version_option_name()
		);
	}

	/**
	 * Builds and returns the option name for table version.
	 * @return string
	 */
	public function get_version_option_name()
	{
		return sprintf(
			'wpfepp_%s_table_version',
			$this->get_table_name()
		);
	}

	/**
	 * Returns the latest table version.
	 * @return string Version number.
	 */
	public function get_version()
	{
		return $this->version;
	}

	/**
	 * Changes the version stored in the options table to the passed argument.
	 * @param $to string The version that should be set in the options table.
	 */
	private function update_current_version($to)
	{
		update_option(
			$this->get_version_option_name(),
			$to
		);
	}

	/**
	 * Deletes the table.
	 * @return mixed
	 */
	public function delete_table()
	{
		$drop_query = "DROP TABLE " . $this->get_table_name();
		return $this->db->query($drop_query);
	}
}