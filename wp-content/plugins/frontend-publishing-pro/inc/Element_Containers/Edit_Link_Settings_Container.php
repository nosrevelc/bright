<?php
namespace WPFEPP\Element_Containers;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Edit_Link_Settings;
use WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Element;
use WPGurus\Forms\Elements\Select;
use WPGurus\Forms\Factories\Element_Factory;

class Edit_Link_Settings_Container extends Backend_Element_Container
{
	public function __construct($current_values = array())
	{
		parent::__construct($current_values);

		$this->build_form_elements();
	}

	private function build_form_elements()
	{
		$this->add_element(
			Element_Factory::make_element(
				Elements::SELECT,
				array(
					Element::KEY     => Edit_Link_Settings::SETTING_POST_TYPES,
					Element::LABEL   => __('Override Edit Links for', 'frontend-publishing-pro'),
					Element::POSTFIX => __('Select the post types for which you would like to override edit links.', 'frontend-publishing-pro'),
					Select::MULTIPLE => true,
					Select::CHOICES  => \WPFEPP\get_post_types(),
					Element::VALUE   => array()
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::SELECT,
				array(
					Element::KEY     => Edit_Link_Settings::SETTING_EDIT_PAGE,
					Element::LABEL   => __('Show Edit Form on', 'frontend-publishing-pro'),
					Element::POSTFIX => __('Select an empty page on which you would like to display the form for editing. This page should not have any content or shortcodes in it.', 'frontend-publishing-pro'),
					Select::CHOICES  => $this->get_page_choices(),
					Element::VALUE   => ''
				)
			)
		);
	}

	private function get_page_choices()
	{
		$pages = get_posts(
			array(
				'post_type'      => 'page',
				'posts_per_page' => -1,
				'post_status'    => 'publish'
			)
		);

		$choices = array(
			'' => ''
		);

		foreach ($pages as $page) {
			$choices[ $page->ID ] = $page->post_title;
		}

		return $choices;
	}
}