<?php

namespace WPFEPP\Data_Mappers;

if (!defined('WPINC')) die;

use WPGurus\Components\Component;

class Mapping_Manager extends Component
{
	/**
	 * @var Mapper
	 */
	private $mapper_3;

	function __construct()
	{
		parent::__construct();

		$this->mapper_3 = new Mapper_3();

		$this->register_installation_action(array($this, 'map'), 11);
	}

	function map()
	{
		if ($this->debug) {
			$this->do_mapping();
		} else {
			@$this->do_mapping();
		}
	}

	function do_mapping()
	{
		$this_version = $this->get_version();
		$last_version = $this->get_last_version();

		if ($last_version == '0') {
			return;
		}

		switch (true) {
			case version_compare($this_version, '3.0.0', '>=') && version_compare($last_version, '3.0.0', '<'):
				$this->mapper_3->map();
		}
	}
}