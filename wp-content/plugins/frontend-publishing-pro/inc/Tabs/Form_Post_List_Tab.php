<?php
namespace WPFEPP\Tabs;

if (!defined('WPINC')) die;

use WPFEPP\Admin_Pages\Plugin_Settings;
use WPFEPP\Constants\Form_Meta_Keys;
use WPFEPP\Element_Containers\Post_List_Settings_Container;
use WPFEPP\Forms\Form_Post_List_Form;
use WPFEPP\Forms\Splitter_Form;

class Form_Post_List_Tab extends Form_Tab
{
	function __construct($form_id)
	{
		parent::__construct(
			'post-list',
			__('Post List', 'frontend-publishing-pro'),
			$form_id
		);
	}

	function render()
	{
		$current_post_list_settings = $this->form_meta_table->get_meta_value($this->form_id, Form_Meta_Keys::POST_LIST);

		if ($current_post_list_settings) {
			$form = new Form_Post_List_Form($this->form_id, $current_post_list_settings);
		} else {
			$container = new Post_List_Settings_Container();
			$url = admin_url(
				sprintf(
					'admin.php?page=%s&tab=%s',
					Plugin_Settings::PAGE_ID,
					Post_List_Settings_Tab::TAB_SLUG
				)
			);
			$form = new Splitter_Form(
				$this->form_id,
				Form_Meta_Keys::POST_LIST,
				$container->get_values(),
				sprintf(
					__('This form currently inherits its post list settings from the general plugin post list settings %s.', 'frontend-publishing-pro'),
					sprintf(
						'<a href="%s">%s</a>',
						$url,
						__('here', 'frontend-publishing-pro')
					)
				),
				__('Create form-specific post list settings', 'frontend-publishing-pro')
			);
		}

		$form->render();
	}
}