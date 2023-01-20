<?php if (!defined('WPINC')) die; ?>
<div class="frontend-item-container" style="<?php echo isset($element_container_style) ? $element_container_style : ''; ?>" data-element-key="<?php echo $element_key; ?>">
	<?php
		$this->render_template(
			WPFEPP_ELEMENT_TEMPLATES_DIR . 'frontend-form-element-inner.php',
			$args
		);
	?>
</div>