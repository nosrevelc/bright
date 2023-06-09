<?php
/**
 * The Google core class.
 *
 * @link    http://premium.wpmudev.org
 * @since   3.2.0
 *
 * @author  Joel James <joel@incsub.com>
 * @package Beehive\Core\Modules\Google_Analytics
 */

namespace Beehive\Core\Modules\Google_Analytics;

// If this file is called directly, abort.
defined( 'WPINC' ) || die;

use Google_Service_Analytics;
use Beehive\Core\Helpers\Permission;
use Beehive\Core\Utils\Abstracts\Base;
use Beehive\Core\Modules\Google_Analytics\Views\Stats;
use Beehive\Core\Modules\Google_Analytics\Views\Tracking;

/**
 * Class Analytics
 *
 * @package Beehive\Core\Modules\Google_Analytics
 */
class Analytics extends Base {

	/**
	 * Register all the hooks related to module.
	 *
	 * @since 3.2.0
	 *
	 * @return void
	 */
	public function init() {
		// Add Google Analytics auth scopes.
		add_filter( 'beehive_google_auth_scopes', array( $this, 'auth_scopes' ) );

		// Stats menu is required only when logged in.
		if ( Helper::instance()->can_get_stats( $this->is_network() ) ) {
			// Setup widgets.
			add_action( 'widgets_init', array( $this, 'widgets' ) );
		}

		// Initialize sub classes.
		Admin::instance()->init();
		Stats::instance()->init();
		Tracking::instance()->init();

		// Rest API.
		Endpoints\Stats::instance();
		Endpoints\Data::instance();
	}

	/**
	 * Add Google Analytics scope for authentication.
	 *
	 * @param array $scopes Auth scopes.
	 *
	 * @since 3.2.0
	 *
	 * @return array $scopes
	 */
	public function auth_scopes( $scopes = array() ) {
		// Add Google Analytics auth scope.
		$scopes[] = Google_Service_Analytics::ANALYTICS_READONLY;

		return $scopes;
	}

	/**
	 * Setup widgets for Google Analytics.
	 *
	 * Register all widgets with WordPress.
	 *
	 * @since 3.2.0
	 *
	 * @return void
	 */
	public function widgets() {
		// Make sure the user has capability.
		if ( Permission::user_can( 'analytics' ) || ! is_admin() ) {
			// Most popular contents.
			register_widget( Widgets\Popular::instance() );
		}
	}
}