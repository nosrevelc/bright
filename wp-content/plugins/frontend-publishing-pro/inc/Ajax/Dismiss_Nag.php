<?php
namespace WPFEPP\Ajax;

if (!defined('WPINC')) die;

use WPFEPP\Ajax;
use WPFEPP\Constants\Option_Ids;

class Dismiss_Nag extends Ajax
{
	const ACTION = 'wpfepp_dismiss_nag';

	function __construct()
	{
		parent::__construct();

		$this->register_action(self::ACTION_PREFIX . self::ACTION, array($this, 'dismiss_nag'));
	}

	public function dismiss_nag()
	{
		update_option(Option_Ids::OPTION_NAG_DISMISSED, $this->get_version());
	}
}