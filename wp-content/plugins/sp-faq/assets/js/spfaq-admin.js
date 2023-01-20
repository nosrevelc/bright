(function($) {

	"use strict";

	/* Click to Copy the Text */
	$(document).on('click', '.wpos-copy-clipboard', function() {
		var copyText = $(this);
		copyText.select();
		document.execCommand("copy");
	});
	
	/* Drag widget event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.preview-rendered', spfaq_fl_render_preview );

	/* Save widget event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.layout-rendered', spfaq_fl_render_preview );

	/* Publish button event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.didSaveNodeSettings', spfaq_fl_render_preview );
})(jQuery);

/* Function to render shortcode preview for Beaver Builder */
function spfaq_fl_render_preview() {
	spfaq_accordion_init();
}