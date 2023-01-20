<?php
namespace WPGurus\Forms\Validators;

if (!defined('WPINC')) die;

/**
 * Makes sure that the value submitted by the user does not have more than the maximum allowed number of items.
 *
 * Class Max_Count
 * @package WPGurus\Forms\Validators
 */
class Max_Count extends Count
{
	/**
	 * The maximum allowed number of items.
	 * @var int
	 */
	private $max_count;

	/**
	 * Max_Count constructor.
	 * @param int $max_count The maximum allowed number of items.
	 * @param string $message The error message.
	 * @param string $delimiter The delimiter used to break down strings into arrays.
	 */
	public function __construct($max_count, $message = '', $delimiter = ',')
	{
		$this->max_count = $max_count;

		parent::__construct($message, $delimiter);
	}

	/**
	 * @inheritdoc
	 */
	public function is_valid($value)
	{
		// Multi-selects return null when nothing is selected. So validator is not applicable on null.
		if(is_null($value))
			return true;

		return $this->calculate_count($value) <= $this->max_count;
	}
}