(function ($) {
	$.fn.media_file_element = function () {
		var main_class = 'element-media-file',
			main_selector = '.' + main_class,
			class_preview_state = 'element-media-file-preview-state',
			class_upload_state = 'element-media-file-upload-state';

		if (!this.hasClass(main_class)) {
			this.addClass(main_class);
		}

		var Components = function ($context) {
			this.main_element = $context.closest(main_selector);
			this.clear_link = this.main_element.find('.element-media-file-clear');
			this.reload_link = this.main_element.find('.element-media-file-reload');
			this.file_input = this.main_element.find('input[type="file"]');
			this.hidden_input = this.main_element.find('input[type="hidden"]');
		};

		var $components = new Components(this);

		function clear($context) {
			var $components = new Components($context);
			var existing_value = $components.hidden_input.val();
			if (existing_value) {
				$components.main_element.data(
					'removed-id',
					existing_value
				);
			}
			$components.hidden_input.val('');
			$components.main_element.removeClass(class_preview_state).addClass(class_upload_state);
		}

		function reload($context) {
			var $components = new Components($context);
			$components.hidden_input.val($components.main_element.data('removed-id'));
			$components.main_element.removeData('removed-id');
			$components.file_input.get(0).value = '';
			$components.main_element.removeClass(class_upload_state).addClass(class_preview_state);
		}

		$components.clear_link.click(function (e) {
			e.preventDefault();
			clear($(this));
		});

		$components.reload_link.click(function (e) {
			e.preventDefault();
			reload($(this));
		});
	};
}(jQuery));