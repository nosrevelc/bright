<?php
namespace WPFEPP\Element_Containers;

if (!defined('WPINC')) die;

/**
 * Provides utility function to those element containers which hold elements for field addons.
 *
 * Class Field_Addons_Container
 * @package WPFEPP\Element_Containers
 */
abstract class Field_Addons_Container extends \WPGurus\Forms\Element_Container
{
	const NAME_ATTRIBUTE = 'data-name';
	const FIELD_PLACEHOLDER = '%FIELD%';
	const NAME_PLACEHOLDER = '%NAME%';

	public function add_element($element, $key = null)
	{
		$name_attribute = self::FIELD_PLACEHOLDER . '[' . self::NAME_PLACEHOLDER . ']';

		$element->set_template(
			WPFEPP_ELEMENT_TEMPLATES_DIR . 'field-addon.php'
		);
		$element->append_attribute('class', ' field-addon-input');
		$element->append_attribute(self::NAME_ATTRIBUTE, $name_attribute);

		parent::add_element($element, $key);
	}
}