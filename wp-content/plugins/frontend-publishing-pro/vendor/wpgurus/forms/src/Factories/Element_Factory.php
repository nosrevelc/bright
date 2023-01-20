<?php

namespace WPGurus\Forms\Factories;

if (!defined('WPINC')) die;

use \WPGurus\Forms\Constants\Elements;
use WPGurus\Forms\Factory;

/**
 * Creates Element objects.
 *
 * Class Element_Factory
 * @package WPGurus\Forms\Factories
 */
class Element_Factory extends Factory
{
	/**
	 * Creates element object.
	 *
	 * @param $element_type string Element type.
	 * @param $args array Element args.
	 * @return null|\WPGurus\Forms\Element
	 */
	public static function make_element($element_type, $args)
	{
		$element = null;

		switch ($element_type) {
			case Elements::BUTTON:
				$element = new \WPGurus\Forms\Elements\Button($args);
				break;

			case Elements::CHECKBOX:
				$element = new \WPGurus\Forms\Elements\Checkbox($args);
				break;

			case Elements::COLOR_PICKER:
				$element = new \WPGurus\Forms\Elements\Color_Picker($args);
				break;

			case Elements::DATE_PICKER:
				$element = new \WPGurus\Forms\Elements\Date_Picker($args);
				break;

			case Elements::EMAIL:
				$element = new \WPGurus\Forms\Elements\Email($args);
				break;

			case Elements::FILE:
				$element = new \WPGurus\Forms\Elements\File($args);
				break;

			case Elements::GOOGLE_RECAPTCHA:
				$element = new \WPGurus\Forms\Elements\Google_reCaptcha($args);
				break;

			case Elements::HIDDEN:
				$element = new \WPGurus\Forms\Elements\Hidden($args);
				break;

			case Elements::MEDIA_FILE:
				$element = new \WPGurus\Forms\Elements\Media_File($args);
				break;

			case Elements::MEDIA_ID:
				$element = new \WPGurus\Forms\Elements\Media_ID($args);
				break;

			case Elements::MEDIA_IDS:
				$element = new \WPGurus\Forms\Elements\Media_IDs($args);
				break;

			case Elements::MEDIA_URL:
				$element = new \WPGurus\Forms\Elements\Media_URL($args);
				break;

			case Elements::NONCE:
				$element = new \WPGurus\Forms\Elements\Nonce($args);
				break;

			case Elements::NUMBER:
				$element = new \WPGurus\Forms\Elements\Number($args);
				break;

			case Elements::PASSWORD:
				$element = new \WPGurus\Forms\Elements\Password($args);
				break;

			case Elements::RICH_TEXT:
				$element = new \WPGurus\Forms\Elements\Rich_Text($args);
				break;

			case Elements::SANDBOXED_RICH_TEXT:
				$element = new \WPGurus\Forms\Elements\Sandboxed_Rich_Text($args);
				break;

			case Elements::SELECT:
				$element = new \WPGurus\Forms\Elements\Select($args);
				break;

			case Elements::SELECT_WITH_SEARCH:
				$element = new \WPGurus\Forms\Elements\Select_With_Search($args);
				break;

			case Elements::STAR_RATING:
				$element = new \WPGurus\Forms\Elements\Star_Rating($args);
				break;

			case Elements::SUBMIT_BUTTON:
				$element = new \WPGurus\Forms\Elements\Submit_Button($args);
				break;

			case Elements::TEXT:
				$element = new \WPGurus\Forms\Elements\Text($args);
				break;

			case Elements::TEXTAREA:
				$element = new \WPGurus\Forms\Elements\Textarea($args);
				break;

			default:
				break;
		}

		$element = self::filter_object('_manufactured_element', array($element, $element_type, $args), '\WPGurus\Forms\Element');

		return $element;
	}
}