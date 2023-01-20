jQuery(document).ready(function ($) {
	var selector_iframe = '.richtext-sandbox-iframe',
		selector_iframe_container = '.richtext-sandbox-iframe-container',
		fullscreen_iframe_class = 'fullscreen-element-sandbox-iframe',
		regular_iframe_class = 'regular-element-sandbox-iframe',
		fullscreen_container_class = 'richtext-sandbox-fullscreen-iframe-container',
		message_type_fullscreens = 'fullscreen',
		message_type_fullscreen_response = 'fullscreen_response',
		message_type_resize = 'resize',
		message_type_content_sync = 'content_sync';

	$(selector_iframe).each(function () {
		$(this).iFrameResize({
			heightCalculationMethod: 'taggedElement',
			scrolling: false,
			resizedCallback: function (args) {
				// When the iframe is resized, its container should be resized as well so that when the iframe is made invisible and fullscreen,
				// the container can act as a placeholder.
				$(args.iframe).parents(selector_iframe_container).css('height', args.height);
			},
			messageCallback: function (args) {
				if (args.message.type == message_type_fullscreens) {
					if (args.message.fullscreen == true) {
						// The iframe has sent a message telling the parent page to make the iframe fullscreen
						$(args.iframe).removeClass(regular_iframe_class);
						$(args.iframe).addClass(fullscreen_iframe_class);

						// Now that the iframe has gone full-screen we can apply a special class to its container to make it look like a
						// proper placeholder.
						$(args.iframe).parents(selector_iframe_container).addClass(fullscreen_container_class);

						// Tell the iframe that the full-screen process is complete.
						args.iframe.iFrameResizer.sendMessage(
							{
								type: message_type_fullscreen_response,
								fullscreen: true
							}
						);
					}
					else {
						// The iframe has sent a message telling the parent page to make the iframe normal again.
						$(args.iframe).removeClass(fullscreen_iframe_class);
						$(args.iframe).addClass(regular_iframe_class);
						$(args.iframe).parents(selector_iframe_container).removeClass(fullscreen_container_class);

						args.iframe.iFrameResizer.sendMessage(
							{
								type: message_type_fullscreen_response,
								fullscreen: false
							}
						);
					}
				}
				else if (args.message.type == message_type_resize) {
					// The iframe has sent a message telling the parent page to resize the iframe manually.
					args.iframe.iFrameResizer.resize();
				}
				else if (args.message.type == message_type_content_sync) {
					// The iframe has sent a content sync request.
					$('#' + args.message.id).val(
						args.message.content
					);
				}
			}
		});
	});
});