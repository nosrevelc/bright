<?php if (!defined('WPINC')) die; ?>
<div class="form-field">
	<?php echo $label; ?>
	<div class="form-manager-error-container"><?php echo $errors; ?></div>
	<?php echo $field_html; ?>
	<p class="description"><?php echo $postfix_text; ?></p>
</div>