<?php
namespace WPGurus\Forms;

if (!defined('WPINC')) die;

/**
 * Processors are called when a form submission is being handled. They can change the value of the different variables and take actions based on the form's state.
 *
 * Interface Processor
 * @package WPGurus\Forms
 */
interface Processor
{
	/**
	 * Executes the main logic of the processor.
	 *
	 * @param $is_valid boolean Indicates whether the submission should be completed successfully or errors should be displayed to the user.
	 * @param $data array An array containing user submitted values.
	 * @param $cleaned_data array An array containing user submitted values cleaned using sanitizers.
 	 * @param $form Form The form on which the processor is being used.
	 * @return void
	 */
	public function do_process(&$is_valid, &$data, &$cleaned_data, $form);
}