<?php
namespace WPFEPP\Components;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Query_Vars;
use WPGurus\Components\Component;

class Post_Previews extends Component
{
	const POST_PREVIEW_NONCE_ACTION = 'frontend-post-%s-preview';

	const PREVIEW_LINK_FORMAT = '%s?p=%s&preview=true&%s=%s';

	function __construct()
	{
		parent::__construct();

		$this->register_filter('query_vars', array($this, 'add_query_var'));
		$this->register_filter('pre_get_posts', array($this, 'show_public_preview'));
		$this->register_filter('wpseo_whitelist_permalink_vars', array($this, 'add_query_var'));
	}

	public function add_query_var($qv)
	{
		$qv[] = Query_Vars::POST_PREVIEW_NONCE;

		return $qv;
	}

	public static function make_preview_link($post_id)
	{
		$nonce = wp_create_nonce(
			sprintf(self::POST_PREVIEW_NONCE_ACTION, $post_id)
		);
		return sprintf(self::PREVIEW_LINK_FORMAT, home_url(), $post_id, Query_Vars::POST_PREVIEW_NONCE, $nonce);
	}

	/**
	 * @param $query \WP_Query
	 * @return mixed
	 */
	public function show_public_preview($query)
	{
		if (
			$query->is_main_query() &&
			$query->is_preview() &&
			$query->is_singular() &&
			$query->get(Query_Vars::POST_PREVIEW_NONCE)
		) {
			add_filter('posts_results', array($this, 'set_post_to_publish'));
		}

		return $query;
	}

	public function set_post_to_publish($posts)
	{
		// Remove the filter again, otherwise it will be applied to other queries too.
		remove_filter('posts_results', array($this, 'set_post_to_publish'));

		if (empty($posts))
			return array();

		$post_id = $posts[0]->ID;

		// If the post has gone live, redirect to it's proper permalink
		if ('publish' == get_post_status($post_id)) {
			wp_redirect(get_permalink($post_id), 301);
			exit;
		}

		$nonce_action = sprintf(self::POST_PREVIEW_NONCE_ACTION, $post_id);
		if (wp_verify_nonce(get_query_var(Query_Vars::POST_PREVIEW_NONCE), $nonce_action))
			$posts[0]->post_status = 'publish';

		return $posts;
	}
}