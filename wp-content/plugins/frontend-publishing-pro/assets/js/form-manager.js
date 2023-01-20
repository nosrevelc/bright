jQuery(document).ready(function ($) {
	var $body = $('body');
	$body.on('click', '.form-manager-list-table thead input[type="checkbox"], .form-manager-list-table tfoot input[type="checkbox"]', function () {
		$('.form-manager-list-table input[type="checkbox"]').prop('checked', $(this).prop('checked'));
	});

	$body.on('focus', '.form-manager-error-field', function () {
		$(this).removeClass('form-manager-error-field');
		$(this).siblings('.form-manager-error-container').html('');
	});

	function ajax_form_submission($form, submission_event, success_callback) {
		submission_event.preventDefault();
		var $button_container = $('.ajax-button-container', $form);
		$button_container.addClass('ajax-button-container-working');
		var $button = $('input', $button_container);
		$button.prop('disabled', true);
		$.post(
			ajaxurl,
			$form.serialize(),
			function (data) {
				$button_container.removeClass('ajax-button-container-working');
				$form.siblings('.form-manager-error-container').html('').removeClass('error');
				$button.prop('disabled', false);
				$('.form-manager-error-container', $form).html('');
				$('input[type="text"]', $form).removeClass('form-manager-error-field');

				if (data.success) {
					$('input[type="text"], textarea', $form).val('');
					$button_container.addClass('ajax-button-container-done');
					setTimeout(
						function () {
							$button_container.removeClass('ajax-button-container-done');
						},
						1000
					);
					success_callback(data);
				}
				else {
					for (var key in data.errors) {
						if (data.errors.hasOwnProperty(key)) {
							var $elem = $('#' + key),
								$error_container = $elem.siblings('.form-manager-error-container').first();

							$error_container.html(data.errors[key]);

							if ($elem.is('input')) {
								$elem.addClass('form-manager-error-field');
							}
							else if ($elem.is('form')) {
								$error_container.addClass('error');
							}
						}
					}
				}
			},
			'json'
		);
	}

	var droppable_args_obj = {
		accept: '.field-addons-side .field-addon',
		hoverClass: 'ui-droppable-hover',
		drop: function (event, ui) {
			var area_selector;

			if (ui.helper.data('type') == 'validator') {
				area_selector = '.field-validators';
			}
			else {
				area_selector = '.field-sanitizers';
			}

			handle_addon_drop($(this), area_selector, event, ui);
		}
	};

	$('.form-field-widget').droppable(droppable_args_obj);

	$('.form-manager-ajax-form.custom-fields-form').submit(function (e) {
		var $form = $(this);
		ajax_form_submission($form, e, function (data) {
			$('.form-manager-widget-container').append(data.field_html);

			$('.form-field-widget').droppable(droppable_args_obj);
			$('.conditionized').conditionize();
			hook_widget_removal();
		});
	});

	$('.form-manager-ajax-form.form-details').submit(function (e) {
		ajax_form_submission($(this), e, function (data) {
			$('.form-manager-list-table').html(data.table_html);
			location.reload();
		});
	});

	$(".form-shortcodes-container input[type='text']").click(function () {
		$(this).select();
	});

	$('.field-addons-side .field-addon').draggable({
		revert: 'invalid',
		helper: 'clone'
	});

	function handle_addon_drop($droppable, area_selector, event, ui) {
		var $form_table = ui.helper.find('.form-table').first(),
			$form_table_clone = $($form_table).clone(false),
			field = $droppable.data('field'),
			input = $form_table_clone.find('.field-addon-input'),
			label = $form_table_clone.find('label'),
			name = input.data('name').replace('%FIELD%', field).replace('%NAME%', input.attr('name')),
			$area = $droppable.find(area_selector);

		input.attr('name', name);
		input.attr('id', name);
		label.attr('for', name);

		if(input.attr('type') == 'checkbox'){
			var hidden_input = input.siblings('input[type="hidden"]');
			hidden_input.attr('name', name);
		}

		if (!$area.find('[name="' + escape_selector(name) + '"]').length) {
			$area.append($form_table_clone);
			$droppable.switchClass('form-field-widget-white', 'form-field-widget-grey', 0);
			$droppable.switchClass('form-field-widget-grey', 'form-field-widget-white', 700, 'easeInOutQuad');
		}
		else {
			$droppable.effect('shake');
		}
	}

	function escape_selector(selector) {
		return selector.replace(/(:|\.|\[|\]|,)/g, "\\$1");
	}

	function hook_widget_removal() {
		$('.form-field-widget-remove').click(function (event) {
			var confirm = window.confirm(form_manager_confirmation);
			event.stopPropagation();
			if (confirm) {
				$(this).parents('.form-field-widget').first().remove();
			}
		});
	}

	hook_widget_removal();

	$body.on('click', '.field-addon-remove', function () {
		$(this).parents('table').first().remove();
	});

	$("#meta-key").autocomplete({
		source: ajaxurl + '?action=wpfepp_find_meta_key',
		minLength: 2,
		delay: 800
	});
});