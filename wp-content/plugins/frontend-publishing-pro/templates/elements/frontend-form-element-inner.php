<?php if (!defined('WPINC')) die; ?>
<?php echo $label; ?>
<?php echo $prefix_text; ?>
<div class="frontend-item-messages <?php echo ($errors) ? 'frontend-item-errors' : '' ?>">
	<?php echo $errors; ?>
</div>
<div class="frontend-form-field-container" style="<?php echo isset($field_container_style) ? $field_container_style : ''; ?>">
	<?php echo $field_html; ?>
</div>
<?php echo $postfix_text; ?>