<?php
namespace WPFEPP\Forms;

if (!defined('WPINC')) die;

abstract class Backend_Options_Form extends Backend_Form
{
	private $option_id;
	private $global;

	/**
	 * @param string $form_id
	 * @param string $option_id
	 */
	function __construct($form_id, $option_id, $global = false)
	{
		$this->option_id = $option_id;
		$this->global = $global;

		parent::__construct($form_id);
	}

	function get_current_values()
	{
		if ($this->global) {
			return get_site_option(
				$this->option_id
			);
		}

		return get_option(
			$this->option_id
		);
	}

	function process_data($cleaned_data)
	{
		if ($this->global) {
			update_site_option(
				$this->option_id,
				$cleaned_data
			);
		} else {
			update_option(
				$this->option_id,
				$cleaned_data
			);
		}

		$this->set_success_message(__('Your changes have been saved.', 'frontend-publishing-pro'));
		return true;
	}
}