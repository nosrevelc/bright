jQuery(document).ready(function ($) {
	var WP_Frontend_Post_Form = function ($context, options) {
		var classes = {
				form: 'frontend-post-form',
				form_container: 'frontend-form-container',
				item_container: 'frontend-item-container',
				form_field_container: 'frontend-form-field-container',
				media_file_element: 'element-media-file',
				item_messages: 'frontend-item-messages',
				item_error: 'frontend-item-errors',
				item_success: 'frontend-item-success',
				validated_field: 'js-validated-field',
				element_select_with_search: 'element-select-with-search',
				status_icon: 'frontend-form-icon',
				icon_loading: 'frontend-icon-loading',
				icon_success: 'frontend-icon-success',
				icon_error: 'frontend-icon-error',
				continue_editing_link: 'frontend-form-continue-editing',
				submit_button: 'submit-button',
				title_field: 'post-data-post-title',
				content_field: 'post-data-post-content',
				excerpt_field: 'post-data-post-excerpt'
			},
			selectors = {},
			validation_action = 'validate_single_element',
			form,
			post_id_element,
			form_container,
			submit_button,
			draft_button,
			add_error_for_element,
			reset_error_for_element,
			secs_since_save = 0,
			qtip_options = {
				content: ' ',
				prerender: true,
				overwrite: true,
				position: {my: 'left center', at: 'right center'},
				show: {event: false, ready: false, effect: false},
				hide: {event: false, effect: false},
				style: {classes: 'frontend-form-qtip'}
			};

		function populate_selectors() {
			$.each(classes, function (index, value) {
				selectors[index] = '.' + value
			});
		}

		populate_selectors();

		function init_components() {
			form = $context.closest(selectors.form);
			form_container = $context.closest(selectors.form_container);
			post_id_element = form.find('.post-data-ID');
			submit_button = form.find('[id$=main-submit-button]');
			draft_button = form.find('[id$=secondary-submit-button]');
		}

		init_components();

		function setup_options() {
			options = $.extend(
				true,
				{
					ajax_url: '',
					validation_plugin: true,
					show_tooltips: true,
					auto_save: false,
					auto_save_interval: 10
				},
				options
			);
		}

		setup_options();

		function hook_ajax_form_plugin() {
			$(selectors.submit_button, form).click(function(event){
				var $button = $(this),
					extra_data = {};

				extra_data[$button.attr('name')] = $button.attr('value');

				form.ajaxSubmit({
					url: options.ajax_url,
					data: extra_data,
					dataType: 'json',
					success: ajax_success_callback,
					error: ajax_error_callback,
					beforeSubmit: ajax_before_submit_callback
				});
				event.preventDefault();
			});
		}

		function hook_validation_plugin() {
			if (!options.validation_plugin)
				return;

			form.validate({
				ignore: '.js-validation-disabled',
				rules: create_form_validation_rules(),
				focusInvalid: false,
				success: field_valid_callback,
				errorPlacement: field_invalid_callback,
				onsubmit: false
			});
		}

		function hook_qtips_plugins()
		{
			if(!options.show_tooltips)
				return;

			$(selectors.form_field_container, form).qtip(qtip_options);
		}

		function hook_select_with_search_validation() {
			form.on('change', selectors.element_select_with_search, function () {
				if (!options.validation_plugin)
					return;

				$(this).valid();
			});
		}

		function hook_continue_editing_link()
		{
			form_container.on('click', selectors.continue_editing_link, function(event){
				event.preventDefault();
				$(this).closest(selectors.form_container).find(selectors.form).slideDown('slow');
				$(this).closest(selectors.item_messages).html('').removeClass(classes.item_error).removeClass(classes.item_success);
			});
		}

		function post_status_suitable_for_auto_save() {
			return (form.data('post_status') == 'draft' || form.data('post_status') == 'new');
		}

		function inc_secs_since_save() {
			secs_since_save++;
		}

		function reset_secs_since_save() {
			secs_since_save = 0;
		}

		function is_time_to_save(secs)
		{
			return secs_since_save >= secs;
		}

		function hook_auto_save()
		{
			if(!options.auto_save)
				return;

			setInterval(function(){
				inc_secs_since_save();

				if(!is_time_to_save(options.auto_save_interval) || !post_status_suitable_for_auto_save() || nothing_to_save())
					return;

				if(draft_button.is(':enabled')){
					draft_button.click();
				}

				reset_secs_since_save();
			}, 1000);
		}

		function remove_tags(text){
			var plain_text = "" + text;
			plain_text = plain_text.replace(/<[^>]+>/g, "");
			return $.trim(plain_text);
		}

		function nothing_to_save()
		{
			return (!$(selectors.title_field).length || $.trim($(selectors.title_field).val()) == '')
				&& (!$(selectors.content_field).length || remove_tags($(selectors.content_field).val()) == '')
				&& (!$(selectors.excerpt_field).length || $.trim($(selectors.excerpt_field).val()) == '')
		}

		function initialize() {
			hook_ajax_form_plugin();
			hook_validation_plugin();
			hook_select_with_search_validation();
			hook_qtips_plugins();
			hook_continue_editing_link();
			hook_auto_save();
		}

		function set_post_id(post_id) {
			post_id_element.val(post_id);
		}

		function set_media_file_elements_html(response) {
			if(typeof response.media_file_elements_html != 'undefined')
			{
				$.each(response.media_file_elements_html, function(field_id, html){
					var id_selector = '#' + field_id,
						$field = $(id_selector);
					$field.closest(selectors.item_container).html(html);
				});
			}
		}

		function reinitialize_file_elements() {
			$(selectors.media_file_element, form).each(function () {
				$(this).media_file_element();
			});
		}

		function reinitialize_file_element_tooltips()
		{
			if(!options.show_tooltips)
				return;

			$(selectors.media_file_element, form).each(function () {
				$(this).closest(selectors.form_field_container).qtip(qtip_options);
			});
		}

		function setup_error_handlers() {
			if (options.show_tooltips) {
				add_error_for_element = add_element_error_to_tooltip;
				reset_error_for_element = reset_element_error_tooltip;
			}
			else {
				add_error_for_element = add_element_error_to_div;
				reset_error_for_element = reset_element_error_div;
			}
		}

		setup_error_handlers();

		function add_success_message(message)
		{
			form_container.children(selectors.item_messages).html(message).addClass(classes.item_success);
		}

		function reset_form_errors()
		{
			form_container.children(selectors.item_messages).html('').removeClass(classes.item_error);
		}

		function reset_errors() {
			reset_form_errors();
			form_container.find(selectors.form_field_container).each(function () {
				reset_error_for_element($(this));
			});
		}

		function add_form_errors(errors)
		{
			var form_id = form.attr('id');
			if(form_id && form_id in errors)
			{
				form_container.children(selectors.item_messages).html(errors[form_id]).addClass(classes.item_error);
			}
		}

		function reposition_qtips() {
			if(!options.show_tooltips)
				return;

			$(selectors.form_field_container, form).qtip('reposition');
		}

		function add_errors(errors) {
			// For each field that has an error we need to call form.validate().element() so that the jquery plugin will know that the field is invalid and validate it on every keyup
			var form_validate;
			if (options.validation_plugin) {
				form_validate = form.validate();
			}

			$.each(errors, function (index, error) {
				var item_selector = '#' + index,
					$item_container = $(item_selector).closest(selectors.item_container),
					is_form = $(item_selector).is('form');

				if (is_form) {
					$item_container.children(selectors.item_messages).html(error).addClass(classes.item_error);
					reposition_qtips();
				}
				else {
					add_error_for_element(error, $(item_selector));
					if (options.validation_plugin && $(item_selector).is(classes.validated_field)) {
						form_validate.element(item_selector);
					}
				}
			});
		}

		function update_post_status_form_data(response) {
			form.data('post_status', response.post_status);
		}

		function ajax_success_callback(response, status_text, xhr, $form) {
			init_components($form);
			enable_buttons();
			set_media_file_elements_html(response);
			reinitialize_file_elements();
			reinitialize_file_element_tooltips();
			reset_secs_since_save();

			if (response.success) {

				set_post_id(response.post_id);

				show_success_icon();
				update_post_status_form_data(response);

			}
			else {
				show_error_icon();
			}

			// If the request made was to save a draft then the errors should stay untouched
			if(response.request_type == 'draft')
			{
				handle_draft_response(response);
			}
			else
			{
				handle_submission_response(response);
			}
		}

		function handle_draft_response(response)
		{
			if (response.success) {
				reset_form_errors();
			}
			else
			{
				reset_form_errors();
				add_form_errors(response.errors);
				scroll_to(form_container.children(selectors.item_messages).first());
			}
			reposition_qtips();
		}

		function scroll_to($item, callback){
			if( $item.offset().top < jQuery(window).scrollTop() ){
				jQuery('html, body').animate({ scrollTop: $item.offset().top-40 }, 'slow', callback);
			}
		}

		function redirect(response) {
			setTimeout(
				function () {
					if (response.redirect_url) {
						window.location.href = response.redirect_url;
					}
				},
				2000
			)
		}

		function handle_submission_response(response)
		{
			if(typeof grecaptcha != 'undefined')
			{
				try {
					grecaptcha.reset();
				}
				catch (e) {
					console.log(e);
				}
			}

			if (response.success) {
				reset_errors();
				add_success_message(response.message);
				form.hide();
				scroll_to(form_container.children(selectors.item_messages).first());
				redirect(response);
			}
			else {
				reset_errors();
				add_errors(response.errors);
				scroll_to(form_container.children(selectors.item_messages).first());
			}
		}

		function ajax_error_callback(error) {
			alert(options.form_error);
			console.log(error);
		}

		function ajax_before_submit_callback(arr, $form, options)
		{
			init_components($form);
			show_loading_icon();
			disable_buttons();
		}

		function enable_buttons()
		{
			submit_button.removeAttr('disabled');
			draft_button.removeAttr('disabled');
		}

		function disable_buttons()
		{
			submit_button.attr('disabled', 'disabled');
			draft_button.attr('disabled', 'disabled');
		}

		function add_element_error_to_tooltip(error_html, $context) {
			if(!options.show_tooltips)
				return;

			var $field_container = $context.closest(selectors.form_field_container);
			$field_container.qtip('api').set('content.text', error_html);
			$field_container.qtip('api').show();
		}

		function add_element_error_to_div(error_html, $context) {
			$context.closest(selectors.item_container).find(selectors.item_messages).html(error_html).addClass(classes.item_error);
		}

		function reset_element_error_tooltip($context) {
			if(!options.show_tooltips)
				return;

			var $field_container = $context.closest(selectors.form_field_container);
			$field_container.qtip('api').hide();
			$field_container.qtip('api').set('content.text', ' ');
		}

		function reset_element_error_div($context, animation) {
			animation = (typeof animation == 'undefined') ? false : animation;

			var $item_messages = $context.closest(selectors.item_container).find(selectors.item_messages);

			if (animation) {
				$item_messages.fadeOut(400, function () {
					$(this).html('').removeClass(classes.item_error).show();
				});
			}
			else {
				$item_messages.html('').removeClass(classes.item_error).show();
			}
		}

		function hide_icons()
		{
			form.find(selectors.status_icon).hide();
		}

		function show_loading_icon()
		{
			hide_icons();
			form.find(selectors.icon_loading).show();
		}

		function show_success_icon()
		{
			hide_icons();
			form.find(selectors.icon_success).show();
			setTimeout(hide_icons, 1000);
		}

		function show_error_icon()
		{
			hide_icons();
			form.find(selectors.icon_error).show();
			setTimeout(hide_icons, 1000);
		}

		function create_form_validation_rules() {
			var validation_rules = {},
				ajax_url = options.ajax_url;

			$(selectors.item_container, form).each(function () {
				var $item_container = $(this),
					$field = $item_container.find(selectors.validated_field),
					form_db_id = form.data('formDbId');

				validation_rules[$field.attr('name')] = {
					remote: {
						url: ajax_url,
						type: 'post',
						data: {
							action: validation_action,
							form_db_id: form_db_id,
							element_key: $item_container.data('elementKey')
						}
					}
				};
			});

			return validation_rules;
		}

		function field_valid_callback($label, field) {
			reset_error_for_element($(field), false);
		}

		function field_invalid_callback($label, $field) {
			add_error_for_element($label.html(), $field);
		}

		return {
			classes: classes,
			selectors: selectors,
			init: initialize,
			add_error_for_element: add_error_for_element,
			reset_error_for_element: reset_error_for_element,
			add_errors: add_errors,
			reset_errors: reset_errors
		};
	};

	$('.frontend-post-form').each(function () {
		var options = $.extend({
			ajax_url: frontend_post_form_ajax,
			form_error: frontend_post_form_error
		}, $(this).data());

		var frontend_form = new WP_Frontend_Post_Form($(this), options);
		frontend_form.init();
	});
});