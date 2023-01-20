<?php
namespace WPGurus\Forms\Validators;

if (!defined('WPINC')) die;

/**
 * Ensures that the user submitted value does not have more than the allowed number of links.
 *
 * Class Max_Links
 * @package WPGurus\Forms\Validators
 */
class Max_Links extends String_Validator
{
	/**
	 * The allowed number of links.
	 * @var int
	 */
	private $max_links;

	/**
	 * Max_Links constructor.
	 *
	 * @param int $max_links The allowed number of links.
	 * @param string $message
	 */
	public function __construct($max_links, $message = '')
	{
		$this->max_links = $max_links;
		parent::__construct($message);
	}

	/**
	 * @inheritdoc
	 */
	public function is_valid($value)
	{
		if(!$this->is_applicable($value))
			return true;

		$regex = '/(?i)<\s*a([^>]+)>(.+?)<\s*\/\s*a\s*>/';
		$link_count = preg_match_all($regex, $value);
		if($link_count === false){
			//TODO: Log
		}

		return $link_count <= $this->max_links;
	}
}