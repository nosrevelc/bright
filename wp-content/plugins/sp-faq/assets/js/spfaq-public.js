(function($) {

	"use strict";

	// Accordian init 
	spfaq_accordion_init();
})(jQuery);

/* Function to Initialize FAQ Accordion */
function spfaq_accordion_init() {
	jQuery( '.faq-accordion' ).each(function( index ) {

		var faq_id		= jQuery(this).attr('id');
		var faq_conf	= jQuery.parseJSON( jQuery(this).attr('data-conf') );

		if( typeof(faq_id) != 'undefined' && faq_id != '' ) {

			jQuery('#'+faq_id+ ' [data-accordion]').accordionfaq({
				transitionEasing	: 'ease',
				transitionSpeed		: parseInt( faq_conf.transition_speed ),
				singleOpen			: ( faq_conf.single_open == 'true' ) ? true : false,
			});
		}
	});
}