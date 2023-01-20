<?php
namespace WPFEPP\Components;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Edit_Link_Settings;
use WPFEPP\Constants\Option_Ids;
use WPFEPP\Constants\Post_Meta_Keys;
use WPFEPP\Element_Containers\Edit_Link_Settings_Container;
use WPFEPP\Shortcodes\Post_Table;
use WPGurus\Components\Component;

class Edit_Post_Link extends Component
{
	function __construct()
	{
		parent::__construct();

		$this->register_filter('get_edit_post_link', array($this, 'override_edit_link'), 10, 2);
		$this->register_filter('the_content', array($this, 'replace_content_with_edit_shortcode'));
	}

	/**
	 * Replaces the default edit link our link.
	 */
	public function override_edit_link($link, $post_id)
	{
		if (is_admin()) {
			return $link;
		}

		$permalinks_enabled = (boolean)get_option('permalink_structure');
		$form_id = get_post_meta($post_id, Post_Meta_Keys::FORM_ID, true);
		$options = $this->get_options();
		$post_types = $options[ Edit_Link_Settings::SETTING_POST_TYPES ];
		$edit_page = $options[ Edit_Link_Settings::SETTING_EDIT_PAGE ];

		if (!$permalinks_enabled || !$form_id || !$edit_page) {
			return $link;
		}

		$current_post = get_post($post_id);

		if (!in_array($current_post->post_type, $post_types)) {
			return $link;
		}

		$post_permalink = get_permalink($edit_page);
		$overridden_link = trailingslashit($post_permalink) . Rewrites::EDIT_ENDPOINT . '/' . $post_id . '/';

		return $overridden_link;
	}

	/**
	 * When the user clicks on our link and starts to edit a post, this function replaces the content with a shortcode. This shortcode displays the form for editing.
	 * @param $content string The post content to replace.
	 * @return string Modified content.
	 */
	public function replace_content_with_edit_shortcode($content)
	{
		global $post;

		$options = $this->get_options();
		$edit_page = $options[ Edit_Link_Settings::SETTING_EDIT_PAGE ];

		if ($post && $post->ID == $edit_page && $edit_post_id = get_query_var(Rewrites::EDIT_ENDPOINT)) {
			$form_id = get_post_meta($edit_post_id, Post_Meta_Keys::FORM_ID, true);
			return sprintf('[%s form="%s"]', Post_Table::SHORTCODE, $form_id);
		}

		return $content;
	}

	private function get_options()
	{
		$element_container = new Edit_Link_Settings_Container();
		$options = get_option(Option_Ids::OPTION_EDIT_LINK_SETTINGS);
		return $element_container->parse_values($options);
	}
}