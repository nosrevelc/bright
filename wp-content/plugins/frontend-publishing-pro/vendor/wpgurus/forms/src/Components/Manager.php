<?php
namespace WPGurus\Forms\Components;

if (!defined('WPINC')) die;

use WPGurus\Components\Component;

/**
 * A component that includes all the other components of this package. WordPress plugins using this package need only include this one component to make everything work.
 *
 * Class Manager
 * @package WPGurus\Forms\Components
 */
class Manager extends Component
{
	function __construct()
	{
		parent::__construct();

		$this->register_sub_component(
			new \WPGurus\Forms\Components\Asset_Manager()
		);

		$this->register_sub_component(
			new \WPGurus\Forms\Components\Element_Sandbox()
		);

		$this->register_sub_component(
			new \WPGurus\Forms\Components\Sandboxed_RichText_Buttons()
		);
	}
}