(function ($) {
	$.fn.conditionize = function (options) {

		var settings = $.extend({
			hideJS: true
		}, options);

		$.fn.eval = function (valueIs, valueShould, operator) {
			var valueShouldParts = [],
				isMatch = false;
			if(valueShould.indexOf(';') != -1){
				valueShouldParts = valueShould.split(';');
			}
			else {
				valueShouldParts.push(valueShould);
			}

			$.each(valueShouldParts, function(index, valueShouldPart){
				switch (operator) {
					case '==':
						isMatch = isMatch || valueIs == valueShouldPart;
						break;
					case '!=':
						isMatch = isMatch || valueIs != valueShouldPart;
						break;
					case '<=':
						isMatch = isMatch || valueIs <= valueShouldPart;
						break;
					case '<':
						isMatch = isMatch || valueIs < valueShouldPart;
						break;
					case '>=':
						isMatch = isMatch || valueIs >= valueShouldPart;
						break;
					case '>':
						isMatch = isMatch || valueIs > valueShouldPart;
						break;
				}
			});

			return isMatch;
		};

		$.fn.showOrHide = function (listenTo, listenFor, operator, $section) {
			if ($(listenTo).is('select, input[type=text]') && $.fn.eval($(listenTo).val(), listenFor, operator)) {
				$section.show();
			}
			else if ($(listenTo + ":checked").filter(function (idx, elem) {
					return $.fn.eval(elem.value, listenFor, operator);
				}).length > 0) {
				$section.show();
			}
			else {
				$section.hide();
			}
		};

		return this.each(function () {
			var listenTo = "[name=" + $(this).data('cond-option').replace(/(:|\.|\[|\]|,)/g, "\\$1") + "]";
			var listenFor = $(this).data('cond-value');
			var operator = $(this).data('cond-operator') ? $(this).data('cond-operator') : '==';
			var $section = $(this);

			//Set up event listener
			$(listenTo).on('change', function () {
				$.fn.showOrHide(listenTo, listenFor, operator, $section);
			});
			//If setting was chosen, hide everything first...
			if (settings.hideJS) {
				$(this).hide();
			}
			//Show based on current value on page load
			$.fn.showOrHide(listenTo, listenFor, operator, $section);
		});
	}
}(jQuery));
