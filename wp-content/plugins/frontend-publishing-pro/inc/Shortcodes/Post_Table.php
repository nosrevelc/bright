<?php

namespace WPFEPP\Shortcodes;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Tabbed_Interfaces;
use WPFEPP\Factories\Tabbed_Interface_Factory;

/**
 * The post list shortcode.
 */
class Post_Table extends \WPGurus\Components\Component
{
	const SHORTCODE = 'wpfepp_post_table';

	const ATTRIBUTE_FORM = 'form';

	function __construct()
	{
		parent::__construct();
		$this->register_action('init', 'register_shortcode');
	}

	function register_shortcode()
	{
		add_shortcode(self::SHORTCODE, array($this, 'post_list_shortcode_callback'));
	}

	function post_list_shortcode_callback($attributes)
	{
		ob_start();
		if (!isset($attributes[ self::ATTRIBUTE_FORM ])) {
			echo __('Please specify a form ID.', 'frontend-publishing-pro');
		}
		else {
			global $post;
			$post_list_interface = Tabbed_Interface_Factory::make_interface(
				Tabbed_Interfaces::POST_LIST_INTERFACE,
				array(
					$attributes[ self::ATTRIBUTE_FORM ],
					$post
				)
			);
			$post_list_interface->render();
		}
		return ob_get_clean();
	}
}