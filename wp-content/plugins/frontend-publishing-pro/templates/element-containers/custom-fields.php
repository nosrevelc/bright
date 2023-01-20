<?php if (!defined('WPINC')) die; ?>
<div>
	<?php
		foreach ($elements as $element) {
			$element->render();
		}
	?>
	<div class="ajax-button-container">
		<?php submit_button(__('Add', 'frontend-publishing-pro'), 'primary', null, false); ?>
	</div>
</div>