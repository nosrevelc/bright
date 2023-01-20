<?php
namespace WPGurus\Forms\Validators;

if (!defined('WPINC')) die;

/**
 * Makes sure that the value submitted by the user does not have less than the minimum allowed number of items.
 *
 * Class Min_Count
 * @package WPGurus\Forms\Validators
 */
class Min_Count extends Count
{
	/**
	 * The minimum allowed number of items.
	 * @var int
	 */
	private $min_count;

	/**
	 * Min_Count constructor.
	 *
	 * @param int $min_count The minimum allowed number of items.
	 * @param string $message The error message.
	 * @param string $delimiter The delimiter used to break down strings into arrays.
	 */
	public function __construct($min_count, $message = '', $delimiter = ',')
	{
		$this->min_count = $min_count;

		parent::__construct($message);
	}

	/**
	 * @inheritdoc
	 */
	public function is_valid($value)
	{
		if(is_null($value))
			return true;

		$count = $this->calculate_count($value);
		return $count == 0 || $count >= $this->min_count;
	}
}