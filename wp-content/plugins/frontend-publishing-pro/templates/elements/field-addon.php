<?php if (!defined('WPINC')) die; ?>
<div class="field-addon" data-type="<?php echo $type; ?>">
	<div class="field-addon-head"><?php echo $label; ?></div>
	<div class="field-addon-body">
		<?php
			$this->render_template(
				WPFEPP_ELEMENT_TEMPLATES_DIR . 'field-addon-inner.php',
				$args
			);
		?>
	</div>
</div>