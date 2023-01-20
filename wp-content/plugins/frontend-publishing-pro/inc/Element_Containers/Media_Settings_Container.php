<?php
namespace WPFEPP\Element_Containers;

if (!defined('WPINC')) die;

use WPFEPP\Constants\General_Media_Types;
use WPFEPP\Constants\Media_Settings;
use WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Element;
use WPGurus\Forms\Elements\Select;
use WPGurus\Forms\Factories\Element_Factory;

class Media_Settings_Container extends Backend_Element_Container
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
				Elements::CHECKBOX,
				array(
					Element::KEY     => Media_Settings::SETTING_MAKE_RESTRICTIONS_GLOBAL,
					Element::LABEL   => __('Make Restrictions Global', 'frontend-publishing-pro'),
					Element::POSTFIX => __('If this option is selected, the media restrictions will be applied everywhere including the admin area. Otherwise the restrictions will be applied only on pages containing the shortcodes.', 'frontend-publishing-pro'),
					Element::VALUE   => false
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::TEXT,
				array(
					Element::KEY     => Media_Settings::SETTING_MAX_UPLOAD_SIZE,
					Element::LABEL   => __('Max Upload Size', 'frontend-publishing-pro'),
					Element::POSTFIX => __('Maximum upload size in kilobytes.', 'frontend-publishing-pro'),
					Element::VALUE   => 500
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::CHECKBOX,
				array(
					Element::KEY     => Media_Settings::SETTING_OWN_MEDIA_ONLY,
					Element::LABEL   => __('Show users only their media items', 'frontend-publishing-pro'),
					Element::POSTFIX => __('All the other media items will be hidden.', 'frontend-publishing-pro'),
					Element::VALUE   => true
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::SELECT,
				array(
					Element::KEY     => array(Media_Settings::SETTING_ALLOWED_MEDIA_TYPES, ''),
					Element::LABEL   => __('Allowed Media Types', 'frontend-publishing-pro'),
					Element::POSTFIX => sprintf(
						__('By default WordPress supports a wide range of media formats. You can see the full list %s.', 'frontend-publishing-pro'),
						sprintf(
							'<a target="_blank" href="http://codex.wordpress.org/Function_Reference/get_allowed_mime_types#Default_allowed_mime_types">%s</a>',
							__('here', 'frontend-publishing-pro')
						)
					),
					Element::VALUE   => array(General_Media_Types::TYPE_IMAGE),
					Select::MULTIPLE => true,
					Select::CHOICES  => array(
						General_Media_Types::TYPE_IMAGE       => __('Image', 'frontend-publishing-pro'),
						General_Media_Types::TYPE_VIDEO       => __('Video', 'frontend-publishing-pro'),
						General_Media_Types::TYPE_TEXT        => __('Text', 'frontend-publishing-pro'),
						General_Media_Types::TYPE_AUDIO       => __('Audio', 'frontend-publishing-pro'),
						General_Media_Types::TYPE_OFFICE      => __('MS Office', 'frontend-publishing-pro'),
						General_Media_Types::TYPE_OPEN_OFFICE => __('OpenOffice', 'frontend-publishing-pro'),
						General_Media_Types::TYPE_WORDPERFECT => __('WordPerfect', 'frontend-publishing-pro'),
						General_Media_Types::TYPE_IWORK       => __('iWork', 'frontend-publishing-pro'),
						General_Media_Types::TYPE_MISC        => __('Misc', 'frontend-publishing-pro')
					)
				)
			)
		);

		$roles = \WPFEPP\get_roles();

		$this->add_element(
			Element_Factory::make_element(
				Elements::SELECT,
				array(
					Element::KEY     => array(Media_Settings::SETTING_EXEMPT_ROLES, ''),
					Element::LABEL   => __('Exempt Roles', 'frontend-publishing-pro'),
					Element::POSTFIX => __('These roles will not be affected by these upload restrictions.', 'frontend-publishing-pro'),
					Element::VALUE   => array('administrator'),
					Select::CHOICES  => $roles,
					Select::MULTIPLE => true
				)
			)
		);

		$this->add_element(
			Element_Factory::make_element(
				Elements::CHECKBOX,
				array(
					Element::KEY     => Media_Settings::SETTING_FORCE_ALLOW_UPLOADS,
					Element::LABEL   => __('Give upload capability to Subscribers & Contributors', 'frontend-publishing-pro'),
					Element::POSTFIX => __('By default WordPress does not allow contributors and subscribers to upload media items. Check this if you want to give upload capabilities to these roles.', 'frontend-publishing-pro'),
					Element::VALUE   => false
				)
			)
		);
	}
}