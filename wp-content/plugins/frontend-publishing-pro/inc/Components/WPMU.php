<?php
namespace WPFEPP\Components;

use WP_Site;
use WPGurus\Components\Component;

if (!defined('WPINC')) die;

class WPMU extends Component
{
	/**
	 * We can reuse the Data Deleter component here whose job is to remove data when the plugin is uninstalled.
	 * @var Data_Deleter
	 */
	private $deleter;

	public function __construct()
	{
		parent::__construct();

		$this->deleter = new Data_Deleter();

		$this->register_action('admin_init', array($this, 'create_tables_for_current_sub_site'));
		$this->register_action('wpmu_new_blog', array($this, 'create_tables_for_new_site'));
		$this->register_action('wpfepp_uninstall', array($this, 'delete_data'), 9);
		$this->register_action('delete_blog', array($this, 'delete_data_on_single_site_deletion'), 10, 2);
	}

	/**
	 * Deletes all data from the sub-sites when the plugin is uninstalled and the user has set the data deletion flag to true.
	 * Does not touch the main site data.
	 */
	public function delete_data()
	{
		if (!$this->deleter->deletion_required()) {
			return;
		}

		$sites = $this->get_sites();
		foreach ($sites as $site) {
			$this->delete_data_for_sub_site($site->blog_id);
		}
	}

	/**
	 * Before a single site is deleted from the network, this function removes data from its DB.
	 *
	 * @param $blog_id int The blog that is about to be removed.
	 * @param $drop boolean Whether the user is dropping the tables. Only delete if tables are being dropped.
	 */
	public function delete_data_on_single_site_deletion($blog_id, $drop)
	{
		if ($drop) {
			$this->delete_data_for_sub_site($blog_id);
		}
	}

	/**
	 * When the user visits the admin area of a site that existed before the plugin was installed, this method creates the necessary DB tables on it.
	 * This is done only when the plugin is active for the whole network.
	 */
	public function create_tables_for_current_sub_site()
	{
		if (!$this->plugin_active_for_network()) {
			return;
		}

		$this->create_tables_for_sub_site(get_current_blog_id());
	}

	/**
	 * When a new site is created on the network, this function creates the DB tables required by the plugin. This is done only when the plugin is active for the whole network.
	 * @param $blog_id int The ID of the new blog
	 */
	public function create_tables_for_new_site($blog_id)
	{
		if (!$this->plugin_active_for_network()) {
			return;
		}

		$this->create_tables_for_sub_site($blog_id);
	}

	/**
	 * Create required tables for a network sub-site.
	 * @param $blog_id int ID of the site.
	 */
	private function create_tables_for_sub_site($blog_id)
	{
		if (is_main_site($blog_id)) {
			return;
		}

		switch_to_blog($blog_id);

		$db_setup = new DB_Setup();
		$db_setup->create_tables();

		restore_current_blog();
	}

	/**
	 * Removes data for a network sub-site.
	 * @param $blog_id int ID of the site.
	 */
	private function delete_data_for_sub_site($blog_id)
	{
		if (is_main_site($blog_id)) {
			return;
		}

		switch_to_blog($blog_id);

		$this->deleter->delete_tables();
		$this->deleter->delete_options();

		restore_current_blog();
	}

	/**
	 * @return bool Whether or not is the plugin active for the whole network.
	 */
	private function plugin_active_for_network()
	{
		if (!function_exists('is_plugin_active_for_network')) {
			require_once(ABSPATH . '/wp-admin/includes/plugin.php');
		}

		return is_plugin_active_for_network('frontend-publishing-pro/frontend-publishing-pro.php');
	}

	/**
	 * Returns a list of site objects.
	 * @return WP_Site[] A list of site objects.
	 */
	private function get_sites()
	{
		return get_sites(
			array(
				'number' => false
			)
		);
	}
}