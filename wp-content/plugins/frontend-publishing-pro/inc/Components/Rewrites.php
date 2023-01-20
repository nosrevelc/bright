<?php
namespace WPFEPP\Components;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Option_Ids;
use WPGurus\Components\Component;

/**
 * Creates rewrite rules for fancy URLs on the post list page.
 * @package WPFEPP\Components
 */
class Rewrites extends Component
{

	/**
	 * The various end-points.
	 */
	const EDIT_ENDPOINT = 'frontend-publishing/edit';
	const DELETE_ENDPOINT = 'frontend-publishing/delete';
	const TAB_ENDPOINT = 'frontend-publishing/status';

	/**
	 * Rewrites constructor.
	 */
	function __construct()
	{
		parent::__construct();
		$this->register_action('init', array($this, 'add_rewrite_rules'));
	}

	/**
	 * Adds rewrite endpoints and flushes the rules if required.
	 */
	function add_rewrite_rules()
	{
		add_rewrite_endpoint(self::EDIT_ENDPOINT, EP_PERMALINK | EP_PAGES);
		add_rewrite_endpoint(self::TAB_ENDPOINT, EP_PERMALINK | EP_PAGES);
		add_rewrite_endpoint(self::DELETE_ENDPOINT, EP_PERMALINK | EP_PAGES);

		if ($this->needs_flushing()) {
			$this->flush();
		}
	}

	/**
	 * Returns a boolean indicating whether rewrite rules need to be flushed.
	 * @return boolean
	 */
	private function needs_flushing()
	{
		return version_compare($this->get_version(), get_site_option(Option_Ids::OPTION_REWRITE_RULES_FLUSHED, '0'), '>') || $this->debug;
	}

	/**
	 * Updates the option containing the flag which indicates whether or not rules have been flushed.
	 */
	private function updated_flushed_flag()
	{
		update_site_option(Option_Ids::OPTION_REWRITE_RULES_FLUSHED, $this->plugin_version);
	}

	/**
	 * Uses WP' flush_rewrite_rules to flush rules.
	 * @see flush_rewrite_rules
	 */
	private function flush()
	{
		flush_rewrite_rules(true);
		$this->updated_flushed_flag();
	}
}