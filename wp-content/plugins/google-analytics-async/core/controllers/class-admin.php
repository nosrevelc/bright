<?php
/**
 * The admin class of the plugin.
 *
 * Defines admin-specific functionality of the plugin.
 *
 * @link    http://premium.wpmudev.org
 * @since   3.2.0
 * @author  Joel James <joel@incsub.com>
 * @package Beehive\Core\Controllers
 */

namespace Beehive\Core\Controllers;

// If this file is called directly, abort.
defined( 'WPINC' ) || die;

use Beehive\Core\Helpers\General;
use Beehive\Core\Helpers\Template;
use Beehive\Core\Utils\Abstracts\Base;
use Beehive\Core\Views\Admin as Admin_View;

/**
 * Class Admin
 *
 * @package Beehive\Core\Controllers
 */
class Admin extends Base {

	/**
	 * Initialize the class by registering hooks.
	 *
	 * @since 3.2.0
	 *
	 * @return void
	 */
	public function init() {
		// Setup plugin menu.
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		// Setup network admin menu.
		add_action( 'network_admin_menu', array( $this, 'admin_menu' ) );

		// Add body class to admin pages.
		add_filter( 'admin_body_class', array( $this, 'admin_body_classes' ) );

		// Add plugin action links.
		add_filter(
			'plugin_action_links_' . plugin_basename( BEEHIVE_PLUGIN_FILE ),
			array(
				$this,
				'action_links',
			)
		);

		// Only if network wide.
		if ( General::is_networkwide() ) {
			// Network admin plugin action links.
			add_filter(
				'network_admin_plugin_action_links_' . plugin_basename( BEEHIVE_PLUGIN_FILE ),
				array(
					$this,
					'action_links',
				)
			);
		}

		// Add links next to network admin plugin details.
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );

		// Process plugin actions from url.
		add_filter( 'admin_init', array( $this, 'process_actions_from_url' ) );
	}

	/**
	 * Add custom admin body class for SUI.
	 *
	 * @param string $classes Admin body class.
	 *
	 * @since 3.2.0
	 *
	 * @return string
	 */
	public function admin_body_classes( $classes ) {
		// Do not continue if not active network wide.
		if ( $this->is_network() && ! General::is_networkwide() ) {
			return $classes;
		}

		// Set our custom body class.
		$classes .= ' sui-beehive-admin ';

		// Check if plugin admin.
		$is_plugin_admin = General::is_plugin_settings() || General::is_plugin_dashboard();

		/**
		 * Filter to include current page for Beehive admin class.
		 *
		 * @param bool $is_plugin_admin Should include.
		 *
		 * @since 3.2.4
		 */
		$is_plugin_admin = apply_filters( 'beehive_admin_body_classes_is_plugin_admin', $is_plugin_admin );

		// Only within our admin page.
		if ( $is_plugin_admin ) {
			// Shared UI.
			$classes .= ' sui-' . str_replace( '.', '-', BEEHIVE_SUI_VERSION ) . ' ';
		} else {
			// Shared UI.
			$classes .= ' sui-beehive-' . str_replace( '.', '-', BEEHIVE_SUI_VERSION ) . ' ';
		}

		return $classes;
	}

	/**
	 * Register admin menu for the settings.
	 *
	 * We will register a dummy menu and then overwrite it with
	 * another sub menu items.
	 *
	 * @since 3.2.0
	 * @since 3.2.4 Modified to add Dashboard menu.
	 *
	 * @return void
	 */
	public function admin_menu() {
		// Do not add network menu if not active network wide.
		if ( $this->is_network() && ! General::is_networkwide() ) {
			return;
		}

		// Add the dashboard page.
		$settings_hook = add_menu_page(
			__( 'Beehive Dashboard', 'ga_trans' ),
			__( 'Dashboard', 'ga_trans' ),
			Capability::SETTINGS_CAP,
			'beehive',
			array( Admin_View::instance(), 'dashboard_page' ),
			Admin_View::instance()->get_settings_icon()
		);

		// Add settings page.
		add_submenu_page(
			'beehive',
			__( 'Beehive Settings', 'ga_trans' ),
			__( 'Settings', 'ga_trans' ),
			Capability::SETTINGS_CAP,
			'beehive-settings',
			array( Admin_View::instance(), 'settings_page' ),
			999 // Settings should be the last one.
		);

		global $menu;

		foreach ( $menu as $position => $data ) {
			// Only when it's Beehive menu.
			if ( isset( $data[2] ) && 'beehive' === $data[2] ) {
				// Rename the plugin main menu title to Beehive.
				// phpcs:ignore
				$menu[ $position ][0] = General::plugin_name();
			}
		}

		// Statistics menu.
		$this->statistics_menu();

		/**
		 * Register additional menus for Beehive.
		 *
		 * @param string $settings_hook Hook suffix for settings menu.
		 *
		 * @since 3.2.4
		 */
		do_action( 'beehive_admin_menu', $settings_hook );
	}

	/**
	 * Register admin menu for the statistics reports.
	 *
	 * Modules can use `beehive_statistics_menu_items` filter to add
	 * new items to the menu. Menu will appear only if at least one
	 * submenu item is added to the hook.
	 *
	 * @since 3.2.4
	 *
	 * @return void
	 */
	private function statistics_menu() {
		// Get the list of registered sub menus.
		$submenus = apply_filters( 'beehive_statistics_menu_items', array() );

		// No need to continue if empty.
		if ( empty( $submenus ) ) {
			return;
		}

		// The main menu slug.
		$main_slug = 'beehive-statistics';

		// Add the statistics main menu.
		add_menu_page(
			__( 'Statistics', 'ga_trans' ),
			__( 'Statistics', 'ga_trans' ),
			Capability::ANALYTICS_CAP,
			$main_slug,
			null,
			Admin_View::instance()->get_statistics_icon(),
			3
		);

		// Add a fake page and we will remove it later.
		add_submenu_page(
			$main_slug,
			'',
			'',
			Capability::ANALYTICS_CAP,
			$main_slug,
			null
		);

		// Remove the fake submenu.
		remove_submenu_page( 'beehive-statistics', 'beehive-statistics' );

		// Add settings page.
		foreach ( $submenus as $slug => $submenu ) {
			add_submenu_page(
				$main_slug,
				$submenu['page_title'],
				$submenu['menu_title'],
				$submenu['cap'],
				$slug,
				$submenu['callback']
			);
		}

		/**
		 * Action hook to run after statistics menu setup.
		 *
		 * @since 3.2.4
		 */
		do_action( 'beehive_statistics_menu' );
	}

	/**
	 * Action links for plugins listing page.
	 *
	 * Add quick links to plugin settings page, docs page, upgrade page
	 * from the plugins listing page.
	 *
	 * @param array $links Links array.
	 *
	 * @since 3.2.0
	 *
	 * @return array
	 */
	public function action_links( $links ) {
		// Added a fix for weird warning in multisite, "array_unshift() expects parameter 1 to be array, null given".
		$links = empty( $links ) ? array() : $links;

		// Common links.
		$custom = array(
			'settings' => '<a href="' . Template::settings_page( 'tracking', $this->is_network() ) . '" aria-label="' . esc_attr( __( 'Settings', 'ga_trans' ) ) . '">' . __( 'Settings', 'ga_trans' ) . '</a>',
			'docs'     => '<a href="https://premium.wpmudev.org/docs/wpmu-dev-plugins/beehive/?utm_source=beehive&utm_medium=plugin&utm_campaign=beehive_pluginlist_docs" aria-label="' . esc_attr( __( 'Documentation', 'ga_trans' ) ) . '" target="_blank">' . __( 'Docs', 'ga_trans' ) . '</a>',
		);

		// WPMUDEV membership status.
		$membership = General::membership_status();

		// Expired membership.
		if ( ! beehive_analytics()->is_pro() ) {
			$custom['upgrade'] = '<a href="https://premium.wpmudev.org/?utm_source=beehive&utm_medium=plugin&utm_campaign=beehive_pluginlist_upgrade" aria-label="' . esc_attr( __( 'Upgrade to Beehive Pro', 'ga_trans' ) ) . '" target="_blank" style="color: #8D00B1;">' . esc_html__( 'Upgrade', 'ga_trans' ) . '</a>';
		} elseif ( 'expired' === $membership || 'free' === $membership ) {
			$custom['renew'] = '<a href="https://premium.wpmudev.org/?utm_source=beehive&utm_medium=plugin&utm_campaign=beehive_pluginlist_renew" aria-label="' . esc_attr( __( 'Renew Your Membership', 'ga_trans' ) ) . '" target="_blank" style="color: #8D00B1;">' . esc_html__( 'Renew Membership', 'ga_trans' ) . '</a>';
		}

		// Merge custom links to first.
		$links = array_merge( $custom, $links );

		return $links;
	}

	/**
	 * Add custom links to support and roadmap next to plugin meta.
	 *
	 * @param array  $links Current links.
	 * @param string $file  Plugin base name.
	 *
	 * @since 3.2.0
	 *
	 * @return array
	 */
	public function plugin_row_meta( $links, $file ) {
		// Show network meta links only when activated network wide.
		if ( $this->is_network() && ! General::is_networkwide() ) {
			return $links;
		}

		// Make sure the links are array.
		$links = empty( $links ) ? array() : $links;

		// Only for our plugin.
		if ( plugin_basename( BEEHIVE_PLUGIN_FILE ) === $file ) {
			if ( beehive_analytics()->is_pro() ) {
				$custom['support'] = '<a href="https://premium.wpmudev.org/get-support/" aria-label="' . esc_html__( 'Get Premium Support', 'ga_trans' ) . '" target="_blank">' . esc_html__( 'Premium Support', 'ga_trans' ) . '</a>';
			} else {
				$custom['rate']    = '<a href="https://wordpress.org/support/plugin/beehive-analytics/reviews/?rate=5#new-post" aria-label="' . esc_html__( 'Rate Beehive', 'ga_trans' ) . '" target="_blank">' . esc_html__( 'Rate Beehive', 'ga_trans' ) . '</a>';
				$custom['support'] = '<a href="https://wordpress.org/support/plugin/404-to-301/" aria-label="' . esc_html__( 'Get Support', 'ga_trans' ) . '" target="_blank">' . esc_html__( 'Support', 'ga_trans' ) . '</a>';
			}

			$custom['roadmap'] = '<a href="https://premium.wpmudev.org/roadmap/" aria-label="' . esc_html__( 'View our Public Roadmap', 'ga_trans' ) . '" target="_blank">' . esc_html__( 'Roadmap', 'ga_trans' ) . '</a>';

			// Add our custom links.
			$links = array_merge( $links, $custom );
		}

		return $links;
	}

	/**
	 * Process custom admin actions for the plugin.
	 *
	 * All actions added in `beehive-admin-action` param will be
	 * processed here.
	 *
	 * @since 3.2.5
	 *
	 * @return void
	 */
	public function process_actions_from_url() {
		// If action not found.
		if ( ! isset( $_GET['beehive-admin-action'], $_GET['beehive-admin-action-nonce'] ) ) {
			return;
		}

		// If nonce verification failed. Bail.
		// phpcs:ignore
		if ( ! wp_verify_nonce( $_GET['beehive-admin-action-nonce'], 'beehive_admin_action' ) ) {
			return;
		}

		switch ( $_GET['beehive-admin-action'] ) {
			case 'dismiss-welcome':
				// Dismiss the welcome modal.
				beehive_analytics()->settings->update( 'show_welcome', 0, 'misc', $this->is_network() );
				break;

			default:
				return;
		}
	}
}