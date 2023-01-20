jQuery(document).ready(function ($) {
	$('.ectbe_dismiss_notice').on('click', function (event) {
		var thisE = $(this);
		var wrapper=thisE.parents('.ectbe-rating-notice-wrapper');
		var ajaxURL=wrapper.data('ajax-url');
		var ajaxCallback=wrapper.data('ajax-callback');
        var wp_nonce = wrapper.data('wp-nonce');
		$.post(ajaxURL, { 'action':ajaxCallback,'_nonce':wp_nonce }, function( data ) {
            wrapper.slideUp('fast');
		  }, 'json');
	});
});