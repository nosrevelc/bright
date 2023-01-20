<?php if (!defined('WPINC')) die; ?>
<div class="form-wrap">
	<?php foreach ($elements as $element): ?>
		<?php $element->render(); ?>
	<?php endforeach; ?>
	<div class="ajax-button-container">
		<?php submit_button(__('Add', 'frontend-publishing-pro'), 'primary', null, false); ?>
	</div>
</div>