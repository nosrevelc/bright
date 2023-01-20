<?php

namespace WPFEPP\Shortcodes;

use WPFEPP\Constants\Plugin_Forms;
use WPFEPP\Factories\Form_Factory;

if (!defined('WPINC')) die;

/**
 *
 */
class Frontend_Form extends \WPGurus\Components\Component
{

	const SHORTCODE = 'wpfepp_submission_form';

	function __construct()
	{
		parent::__construct();
		$this->register_action('init', 'register_shortcode');
	}

	function register_shortcode()
	{
		add_shortcode(self::SHORTCODE, array($this, 'form_shortcode_callback'));
	}

	public function form_shortcode_callback($atts)
	{
		ob_start();
		if (!isset($atts['form'])) {
			echo __('Please provide a form id.', 'frontend-publishing-pro');
		} else {
			/**
			 * @var \WPFEPP\Forms\Frontend_Form $form
			 */
			$form = Form_Factory::make_form(
				Plugin_Forms::FRONTEND_FORM,
				$atts['form']
			);
			$form->render();
		}

		return ob_get_clean();
	}
}