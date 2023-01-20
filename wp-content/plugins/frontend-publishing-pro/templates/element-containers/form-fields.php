<?php if (!defined('WPINC')) die; ?>
<div class="form-manager-widget-container sortable-boxes-container">
	<?php foreach ($fields as $field): ?>
		<?php
			$this->render_template(
				WPFEPP_ELEMENT_CONTAINER_TEMPLATES_DIR . 'form-field-single.php',
				$field
			);
		?>
	<?php endforeach; ?>
</div>
<?php submit_button(__('Save Changes', 'frontend-publishing-pro'), 'primary', null); ?>