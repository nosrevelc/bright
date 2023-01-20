<?php if (!defined('WPINC')) die; ?>
<table class="form-table">
	<?php
		/*
		 * @var $elements \WPFEPP\Element[]
		 */
		foreach ($elements as $element){
			$element->render();
		}
	?>
</table>
<?php submit_button(); ?>