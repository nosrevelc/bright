<?php
namespace WPFEPP\Element_Containers;

if (!defined('WPINC')) die;

use WPFEPP\Constants\Post_List_Settings;
use WPFEPP\Constants\Post_List_Tabs;
use WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Element;
use WPGurus\Forms\Elements\Select;
use WPGurus\Forms\Factories\Element_Factory;

class Post_List_Settings_Container extends Backend_Element_Container
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
				Elements::TEXT,
				array(
					Element::KEY     => Post_List_Settings::PAGE_LENGTH,
					Element::LABEL   => __('Page Length', 'frontend-publishing-pro'),
					Element::POSTFIX => __('Number of items to display on a single page of the post list table', 'frontend-publishing-pro'),
					Element::VALUE   => 10
				)
			)
		);

		$tab_choices = array(
			Post_List_Tabs::PUBLISHED => __('Published', 'frontend-publishing-pro'),
			Post_List_Tabs::PENDING   => __('Pending', 'frontend-publishing-pro'),
			Post_List_Tabs::DRAFT     => __('Draft', 'frontend-publishing-pro')
		);

		$tab_defaults = array(
			Post_List_Tabs::PUBLISHED,
			Post_List_Tabs::PENDING,
			Post_List_Tabs::DRAFT
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::SELECT,
				array(
					Element::KEY     => array(Post_List_Settings::ACTIVE_TABS, ''),
					Element::LABEL   => __('Active Tabs', 'frontend-publishing-pro'),
					Element::POSTFIX => __('Which tabs would you like to display in the post list table', 'frontend-publishing-pro'),
					Select::MULTIPLE => true,
					Select::CHOICES  => $tab_choices,
					Element::VALUE   => $tab_defaults
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::SELECT,
				array(
					Element::KEY     => array(Post_List_Settings::LINK_COLUMN_TABS, ''),
					Element::LABEL   => __('Show permalink column on', 'frontend-publishing-pro'),
					Element::POSTFIX => __('In which tabs should the view link be shown', 'frontend-publishing-pro'),
					Select::MULTIPLE => true,
					Select::CHOICES  => $tab_choices,
					Element::VALUE   => $tab_defaults
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::SELECT,
				array(
					Element::KEY     => array(Post_List_Settings::EDIT_COLUMN_TABS, ''),
					Element::LABEL   => __('Show edit column on', 'frontend-publishing-pro'),
					Element::POSTFIX => __('In which tabs should the edit link be shown', 'frontend-publishing-pro'),
					Select::MULTIPLE => true,
					Select::CHOICES  => $tab_choices,
					Element::VALUE   => $tab_defaults
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::SELECT,
				array(
					Element::KEY     => array(Post_List_Settings::DELETE_COLUMN_TABS, ''),
					Element::LABEL   => __('Show delete column on', 'frontend-publishing-pro'),
					Element::POSTFIX => __('In which tabs should the delete link be shown', 'frontend-publishing-pro'),
					Select::MULTIPLE => true,
					Select::CHOICES  => $tab_choices,
					Element::VALUE   => $tab_defaults
				)
			)
		);

		$roles = \WPFEPP\get_roles();
		$this->add_element(
			Element_Factory::make_element(
				Elements::SELECT,
				array(
					Element::KEY     => array(Post_List_Settings::EDITOR_ROLES, ''),
					Element::LABEL   => __('Show All Posts to', 'frontend-publishing-pro'),
					Element::POSTFIX => __('Select the user roles that should see all the posts, not just their own', 'frontend-publishing-pro'),
					Select::MULTIPLE => true,
					Select::CHOICES  => $roles,
					Element::VALUE   => array('administrator')
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::CHECKBOX,
				array(
					Element::KEY     => Post_List_Settings::REQUIRE_LOGIN,
					Element::LABEL   => __('Require Login', 'frontend-publishing-pro'),
					Element::POSTFIX => __('If this option is enabled, the post list will only be shown to logged-in users.', 'frontend-publishing-pro'),
					Element::VALUE   => true
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::CHECKBOX,
				array(
					Element::KEY     => Post_List_Settings::REDIRECT_TO_LOGIN,
					Element::LABEL   => __('Redirect to Login Page', 'frontend-publishing-pro'),
					Element::POSTFIX => __('If this option is enabled, the user will be redirected to the login page automatically. Otherwise, an error will be shown.', 'frontend-publishing-pro'),
					Element::VALUE   => false
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::CHECKBOX,
				array(
					Element::KEY     => Post_List_Settings::HIDE_TABS,
					Element::LABEL   => __('Hide Tabs', 'frontend-publishing-pro'),
					Element::POSTFIX => __('If you want to display the posts of each status on a separate page, you can use this option to hide the tabs.', 'frontend-publishing-pro'),
					Element::VALUE   => false
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::CHECKBOX,
				array(
					Element::KEY     => Post_List_Settings::ONLY_FRONTEND_POSTS,
					Element::LABEL   => __( 'Only include posts created with this form', 'frontend-publishing-pro' ),
					Element::POSTFIX => __( 'If this option is checked, only the posts created with the current form will be displayed in the post list. Otherwise all posts belonging to the post type will be shown.', 'frontend-publishing-pro' ),
					Element::VALUE   => false,
				)
			)
		);
	}
}