<?php
namespace WPFEPP\Components;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Data_Settings;
use WPFEPP\Constants\Option_Ids;
use WPFEPP\DB_Tables\Form_Meta;
use WPFEPP\DB_Tables\Forms;
use WPFEPP\Element_Containers\Data_Settings_Container;
use WPGurus\Components\Component;

class Data_Deleter extends Component
{
	function __construct()
	{
		parent::__construct();

		$this->register_action('wpfepp_uninstall', array($this, 'maybe_delete_data'));
	}

	/**
	 * When the plugin is installed, this method removes all data if the user has selected to do so.
	 */
	function maybe_delete_data()
	{
		if (!$this->deletion_required())
			return;

		$this->delete_tables();
		$this->delete_options();
		$this->delete_global_options();
	}

	/**
	 * Deletes plugin tables.
	 */
	public function delete_tables()
	{
		$form_table = new Forms();
		$form_table->delete_table();
		delete_option($form_table->get_version_option_name());

		$form_meta_table = new Form_Meta();
		$form_meta_table->delete_table();
		delete_option($form_meta_table->get_version_option_name());
	}

	/**
	 * Deletes options for the current site only.
	 */
	public function delete_options()
	{
		delete_option(Option_Ids::OPTION_EMAIL_SETTINGS);
		delete_option(Option_Ids::OPTION_GENERAL_FORM_SETTINGS);
		delete_option(Option_Ids::OPTION_MEDIA_SETTINGS);
		delete_option(Option_Ids::OPTION_MESSAGES);
		delete_option(Option_Ids::OPTION_POST_LIST_SETTINGS);
		delete_option(Option_Ids::OPTION_RECAPTCHA_SETTINGS);
		delete_option(Option_Ids::OPTION_NAG_DISMISSED);
		delete_option(Option_Ids::OPTION_HAS_INCOMPATIBLE_CHANGES);
	}

	/**
	 * Deletes network-wide options on multi-site and regular options on a regular site.
	 */
	public function delete_global_options()
	{
		delete_site_option(Option_Ids::OPTION_REWRITE_RULES_FLUSHED);
		delete_site_option(Option_Ids::OPTION_DATA_SETTINGS);
		delete_site_option(Option_Ids::OPTION_UPDATE_SETTINGS);
		delete_site_option(Option_Ids::OPTION_PLUGIN_VERSION);
	}

	/**
	 * Returns boolean indicating whether or not the user wants all data to be deleted on plugin removal.
	 * @return mixed
	 */
	public function deletion_required()
	{
		$data_settings_container = new Data_Settings_Container();
		$data_settings = $data_settings_container->parse_values(get_site_option(Option_Ids::OPTION_DATA_SETTINGS));
		$data_deletion_required = $data_settings[ Data_Settings::SETTINGS_DELETE_DATA ];
		return $data_deletion_required;
	}
}