<?php if (!defined('WPINC')) die; ?>
<div class="form-field-widget form-field-widget-white collapsable-box sortable-box" data-field="<?php echo $field_id; ?>" style="<?php echo ($is_supported) ? '' : 'display:none;'; ?>">
	<div class="form-field-widget-title collapsable-box-head sortable-box-head">
		<strong><?php echo $field_title; ?></strong>
		<?php if($is_custom_field): ?>
			<span class="form-field-widget-remove"></span>
		<?php endif; ?>
	</div>
	<div class="form-field-widget-body collapsable-box-body sortable-box-body">
		<div class="form-field-widget-body-inner">
			<div class="field-general-options">
				<table class="form-table">
					<?php
					foreach($general_elements as $element):
						/**
						 * @var $element \WPGurus\Forms\Element
						 */
						?>
						<?php $element->render(); ?>
					<?php endforeach; ?>
				</table>
			</div>
			<div class="field-addons">
				<div class="field-validators">
					<?php foreach($validator_elements as $element): ?>
						<?php $element->render(); ?>
					<?php endforeach; ?>
				</div>
				<div class="field-sanitizers">
					<?php foreach($sanitizer_elements as $element): ?>
						<?php $element->render(); ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>