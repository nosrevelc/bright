<?php
namespace WPFEPP\Admin_Pages;

use WPFEPP\Constants\Tabbed_Interfaces;
use WPFEPP\Factories\Tabbed_Interface_Factory;
use WPGurus\Components\Component;
use WPGurus\Tabs\Tabbed_Interface;

if (!defined('WPINC')) die;

/**
 * Contains settings that are applicable to the whole network.
 */
class Network_Settings extends Component
{
	private $page_hook;

	const PAGE_ID = 'wpfepp_network_settings';

	function __construct()
	{
		parent::__construct();

		$this->register_action('network_admin_menu', array($this, 'add_network_menu_item'));
	}

	function add_network_menu_item()
	{
		$this->page_hook = add_menu_page(
			__('Frontend Publishing Pro', 'frontend-publishing-pro'),
			__('Frontend Publishing Pro', 'frontend-publishing-pro'),
			'manage_options',
			self::PAGE_ID,
			array($this, 'render'),
			plugins_url("assets/img/icon.png", dirname(dirname(__FILE__)))
		);
	}

	function render()
	{
		?>
		<div class="wrap">
			<h2><?php echo __('Network-Wide Settings', 'frontend-publishing-pro'); ?></h2>

			<p><?php _e('Here you can change settings that will take effect throughout your network.', 'frontend-publishing-pro') ?></p>

			<?php
			$tabbed_interface = Tabbed_Interface_Factory::make_interface(
				Tabbed_Interfaces::PLUGIN_SETTINGS,
				array(
					array(
						Tabbed_Interface::WRAPPER_CLASS     => 'tabbed-interface network-settings-tabbed-interface',
						Tabbed_Interface::NAV_WRAPPER_CLASS => 'nav-tab-wrapper',
						Tabbed_Interface::NAV_CLASS         => 'nav-tab',
						Tabbed_Interface::ACTIVE_CLASS      => 'nav-tab-active'
					)
				)
			);

			$tabbed_interface->add_tab(new \WPFEPP\Tabs\Data_Settings_Tab());

			$tabbed_interface->add_tab(new \WPFEPP\Tabs\Update_Settings_Tab());

			$tabbed_interface->render();
			?>
		</div>
		<?php
	}
}