<?php
namespace WPFEPP\Utils;

use WPFEPP\Constants\Option_Ids;
use WPFEPP\Constants\Update_Settings;
use WPFEPP\Element_Containers\Update_Settings_Container;
use WPGurus\Components\Component;

if (!defined('WPINC')) die;

class Update_Helper
{
	/**
	 * @return null|Component
	 */
	public static function initialize_updater($current_version, $remote_item_id, $plugin_basename)
	{
		$update_settings_container = new Update_Settings_Container();
		$update_settings = $update_settings_container->parse_values(get_site_option(Option_Ids::OPTION_UPDATE_SETTINGS));
		$purchase_code = $update_settings[ Update_Settings::SETTING_PURCHASE_CODE ];

		if ($purchase_code) {
			$info_url = sprintf('http://wpfrontendpublishing.com/item-info/%s/%s/', $remote_item_id, $purchase_code);
			$updater = new \WPGurus\Updates\Plugin_Updates(
				$current_version,
				$info_url,
				$plugin_basename
			);
		} else {
			$updater = null;
		}

		return $updater;
	}
}