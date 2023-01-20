( function($) {

	'use strict';

	jQuery(window).on('elementor/frontend/init', function() {

		/* Shortcode Element */
		elementorFrontend.hooks.addAction( 'frontend/element_ready/shortcode.default', function() {
			spfaq_accordion_init();
		});

		/* Text Editor Element */
		elementorFrontend.hooks.addAction( 'frontend/element_ready/text-editor.default', function() {
			spfaq_accordion_init();
		});

		/* Tabs Element */
		elementorFrontend.hooks.addAction( 'frontend/element_ready/tabs.default', function() {
			spfaq_accordion_init();
		});

		/* Accordion Element */
		elementorFrontend.hooks.addAction( 'frontend/element_ready/accordion.default', function() {
			spfaq_accordion_init();
		});

		/* Toggle Element */
		elementorFrontend.hooks.addAction( 'frontend/element_ready/toggle.default', function() {
			spfaq_accordion_init();
		});

	});
})(jQuery);