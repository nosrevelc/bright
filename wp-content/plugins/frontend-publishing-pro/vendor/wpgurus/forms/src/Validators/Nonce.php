<?php

namespace WPGurus\Forms\Validators;

if (!defined('WPINC')) die;

/**
 * Ensures that the user-submitted value is a valid WordPress nonce.
 *
 * Class Nonce
 * @package WPGurus\Forms\Validators
 */
class Nonce extends \WPGurus\Forms\Validator
{
	/**
	 * The nonce action. Gives context to the nonce.
	 * @var string
	 */
	private $action;

	/**
	 * Nonce constructor.
	 * @param string $action The nonce action.
	 * @param string $message
	 */
	function __construct($action, $message = '')
	{
		$this->action = $action;
		parent::__construct($message);
	}

	/**
	 * Getter for the nonce action.
	 * @return string
	 */
	function get_action(){
		return $this->action;
	}

	/**
	 * Setter for the nonce action.
	 * @param $action
	 */
	function set_action( $action ){
		$this->action = $action;
	}

	/**
	 * @inheritdoc
	 */
	function is_valid( $nonce ) {
		if( wp_verify_nonce( $nonce, $this->action ) )
			return true;

		return false;
	}
}