<?php

namespace WPFEPP\Forms;

if (!defined('WPINC')) die;

abstract class Backend_Form extends \WPGurus\Forms\Form
{
	function print_success_message()
	{
		$has_success_message = (bool)$this->get_success_message();

		?>
		<div class="<?php echo ($has_success_message) ? 'updated notice' : ''; ?>">
			<?php parent::print_success_message(); ?>
		</div>
		<?php
	}

	function print_errors()
	{
		$errors = $this->get_errors();
		$has_errors = (bool)count($errors['form']);

		?>
		<div class="form-manager-error-container <?php echo $has_errors ? 'error notice' : ''; ?>">
			<?php parent::print_errors(); ?>
		</div>
		<?php
	}
}