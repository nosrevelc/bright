<?php

namespace WPFEPP\Admin_Pages;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Assets;
use WPFEPP\Constants\Tabbed_Interfaces;
use WPFEPP\Factories\Tabbed_Interface_Factory;
use WPGurus\Tabs\Tabbed_Interface;

class Plugin_Settings extends \WPGurus\Components\Component
{
	const PAGE_ID = 'wpfepp_settings';

	private $page_hook;

	function __construct()
	{
		parent::__construct();
		$this->register_action('admin_menu', array($this, 'add_menu_item'));
		$this->register_action('admin_enqueue_scripts', array($this, 'enqueue'));
	}

	public function enqueue($hook)
	{
		if ($this->page_hook != $hook)
			return;

		wp_enqueue_script(Assets::ADMIN_PAGE_JS);
		wp_enqueue_style(Assets::ADMIN_PAGE_CSS);
	}

	public function add_menu_item()
	{
		$this->page_hook = add_submenu_page(
			Form_Manager::PAGE_ID,
			__('Frontend Publishing Settings', 'frontend-publishing-pro'),
			__('Settings', 'frontend-publishing-pro'),
			'manage_options',
			self::PAGE_ID,
			array($this, 'render')
		);
	}

	public function render()
	{
		?>
		<div class="wrap">
			<h2><?php echo __('Frontend Form Settings', 'frontend-publishing-pro'); ?></h2>
			<?php
			$tabbed_interface = Tabbed_Interface_Factory::make_interface(
				Tabbed_Interfaces::PLUGIN_SETTINGS,
				array(
					array(
						Tabbed_Interface::WRAPPER_CLASS     => 'tabbed-interface plugin-settings-tabbed-interface',
						Tabbed_Interface::NAV_WRAPPER_CLASS => 'nav-tab-wrapper',
						Tabbed_Interface::NAV_CLASS         => 'nav-tab',
						Tabbed_Interface::ACTIVE_CLASS      => 'nav-tab-active'
					)
				)
			);

			$tabbed_interface->add_tab(new \WPFEPP\Tabs\Media_Settings_Tab());

			$tabbed_interface->add_tab(new \WPFEPP\Tabs\Frontend_Messages_Tab());

			$tabbed_interface->add_tab(new \WPFEPP\Tabs\reCaptcha_Settings_Tab());

			if(!is_multisite())
			{
				$tabbed_interface->add_tab(new \WPFEPP\Tabs\Data_Settings_Tab());
			}

			$tabbed_interface->add_tab(new \WPFEPP\Tabs\General_Form_Settings_Tab());

			$tabbed_interface->add_tab(new \WPFEPP\Tabs\Email_Settings_Tab());

			$tabbed_interface->add_tab(new \WPFEPP\Tabs\Post_List_Settings_Tab());

			$tabbed_interface->add_tab(new \WPFEPP\Tabs\Edit_Link_Settings_Tab());

			if(!is_multisite())
			{
				$tabbed_interface->add_tab(new \WPFEPP\Tabs\Update_Settings_Tab());
			}

			$tabbed_interface->render();

			?>
		</div>
		<?php
	}
}