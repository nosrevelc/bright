<?php
namespace WPFEPP\Components;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Query_Vars;
use WPFEPP\Tabs\Frontend_Tab;
use WPGurus\Components\Component;
use WPGurus\Utils\Array_Utils;

/**
 * Deletes posts.
 * @package WPFEPP\Components
 */
class Post_Deleter extends Component
{
	/**
	 * Post_Deleter constructor.
	 */
	function __construct()
	{
		parent::__construct();

		$this->register_action('wp', array($this, 'handle_deletion_request'));
	}

	/**
	 * Deletes posts and sends the user back to the page from where the request came.
	 */
	public function handle_deletion_request()
	{
		$post_id = \WPFEPP\get_query_var_part(Rewrites::DELETE_ENDPOINT, 0);
		$nonce = Array_Utils::get($_GET, Query_Vars::POST_DELETER_NONCE);
		$tab = Array_Utils::get($_GET, Query_Vars::POST_DELETER_ACTIVE_TAB);

		if (
			$post_id
			&& is_numeric($post_id)
			&& wp_verify_nonce($nonce, sprintf(Frontend_Tab::DELETION_NONCE_ACTION, $tab, $post_id))
			&& \WPFEPP\current_user_can_delete($post_id)
		) {
			$request_uri = esc_url_raw( add_query_arg( array() ) );

			$page_url = substr(
				$request_uri,
				0,
				strpos($request_uri, Rewrites::DELETE_ENDPOINT)
			);
			$page_url .= Rewrites::TAB_ENDPOINT . '/' . $tab . '/';
			$deleted = wp_delete_post($post_id, true);

			$redirect_url = esc_url_raw(
				add_query_arg(
					array(
						Query_Vars::POST_DELETED => $deleted ? 1 : 0
					),
					$page_url
				)
			);

			wp_safe_redirect( $redirect_url );
			die();
		}
	}
}