<?php if (!defined('WPINC')) die; ?>
<div class="form-manager-tab">
	<div class="form-fields-main">
		<?php $form->render(); ?>
	</div>
	<div class="form-fields-side sortable-boxes-container">

		<div class="form-fields-side-item custom-field-builder collapsable-box collapsable-box-open sortable-box">
			<div class="collapsable-box-head sortable-box-head">
				<strong><?php echo __('Custom Fields', 'frontend-publishing-pro'); ?></strong>
			</div>
			<div class="collapsable-box-body sortable-box-body">
				<?php $custom_fields_form->render(); ?>
			</div>
		</div>

		<div class="form-fields-side-item field-addons-side collapsable-box sortable-box">
			<div class="collapsable-box-head sortable-box-head">
				<strong><?php echo __('Validators', 'frontend-publishing-pro'); ?></strong>
			</div>
			<div class="collapsable-box-body sortable-box-body">
				<?php $validators_container->render(); ?>
			</div>
		</div>

		<div class="form-fields-side-item field-addons-side collapsable-box sortable-box">
			<div class="collapsable-box-head sortable-box-head">
				<strong><?php echo __('Sanitizers', 'frontend-publishing-pro'); ?></strong>
			</div>
			<div class="collapsable-box-body sortable-box-body">
				<?php $sanitizers_container->render(); ?>
			</div>
		</div>
	</div>
</div>