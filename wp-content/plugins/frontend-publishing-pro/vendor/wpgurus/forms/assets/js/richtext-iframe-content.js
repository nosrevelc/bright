window.iFrameResizer = {
	messageCallback: function (message) {
		var selector_editor_container = '[data-iframe-height]',
			message_type_fullscreen_response = 'fullscreen_response';

		if (message.type == message_type_fullscreen_response) {
			if (message.fullscreen) {
				// The parent page is telling us that the iframe has been made fullscreen so now we can make the html element visible.
				// However the editor will be kept hidden.
				jQuery('html').css('visibility', 'visible');
			}
			else {
				// The parent page is telling us that the iframe is normal again so we can make the html element as well as the editor visible.
				jQuery('html').css('visibility', 'visible');
				jQuery(selector_editor_container).css('visibility', 'visible');
			}
		}
	}
};

jQuery(document).ready(function ($) {
	var selector_view_switch = '.wp-switch-editor',
		selector_editor_container = '[data-iframe-height]',
		selector_textarea = 'textarea',
		selector_mce_modal = '.mce-panel.mce-floatpanel.mce-window',
		mce_container_selector = '.mce-tinymce.mce-container',
		message_type_fullscreen = 'fullscreen',
		message_type_resize = 'resize',
		message_type_content_sync = 'content_sync',
		mce_fullscreen_flag = false,
		sync_interval = 150,
		$last_matched_node;

	function at_least_one_node_is(selector, nodes) {
		var is = false;
		$.each(nodes, function (index, node) {
			is = is || $(node).is(selector);
			if ($(node).is(selector)) {
				$last_matched_node = $(node);
			}
		});
		return is;
	}

	function has_class(needle_class, class_attr) {
		return $.inArray(needle_class, class_attr.split(' ')) != -1;
	}

	function wp_modal_opened(mutation) {
		return mutation.type == 'attributes'
			&& mutation.attributeName == 'class'
			&& $(mutation.target).hasClass('modal-open')
			&& (mutation.oldValue == null || !has_class('modal-open', mutation.oldValue));
	}

	function wp_modal_closed(mutation) {
		return mutation.type == 'attributes'
			&& mutation.attributeName == 'class'
			&& !$(mutation.target).hasClass('modal-open')
			&& mutation.oldValue != null && has_class('modal-open', mutation.oldValue);
	}

	function mce_modal_closed(mutation) {
		return mutation.type == 'childList'
			&& mutation.removedNodes.length > 0
			&& at_least_one_node_is(selector_mce_modal, mutation.removedNodes)
	}

	function mce_modal_opened(mutation) {
		return mutation.type == 'childList'
			&& mutation.addedNodes.length > 0
			&& at_least_one_node_is(selector_mce_modal, mutation.addedNodes);
	}

	function mce_fullscreen() {
		return $(mce_container_selector).hasClass('mce-fullscreen');
	}

	var targetNodes = $("body");
	var MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
	var observer = new MutationObserver(function (mutation_records) {
		mutation_records.forEach(function (mutation) {
			var mce_modal_open;
			if (mce_fullscreen()) {
				mce_fullscreen_flag = true;
				parentIFrame.sendMessage(
					{
						type: message_type_fullscreen,
						fullscreen: true
					}
				);
			}
			else if (!mce_fullscreen() && mce_fullscreen_flag) {
				mce_fullscreen_flag = false;
				parentIFrame.sendMessage(
					{
						type: message_type_fullscreen,
						fullscreen: false
					}
				);
			}
			else if (wp_modal_opened(mutation) || (mce_modal_open = mce_modal_opened(mutation))) {
				$('html').css('visibility', 'hidden');
				$(selector_editor_container).css('visibility', 'hidden');

				if (mce_modal_open) {
					$last_matched_node.css({left: '0', right: '0', top: '50px', margin: '0 auto'});
				}

				parentIFrame.sendMessage(
					{
						type: message_type_fullscreen,
						fullscreen: true
					}
				);
			}
			else if (wp_modal_closed(mutation) || mce_modal_closed(mutation)) {
				parentIFrame.sendMessage(
					{
						type: message_type_fullscreen,
						fullscreen: false
					}
				);
			}
		});
	});
	var observer_config = {
		childList: true,
		characterData: false,
		attributes: true,
		attributeOldValue: true,
		subtree: false
	};

	targetNodes.each(function () {
		observer.observe(this, observer_config);
	});

	// Problems occur when we switch between the visual and HTML editor. The following bit sends a message to the parent page after a second of
	// the switch telling it to resize the iframe.
	$(selector_view_switch).click(function () {
		window.scrollTo(0, 0);
		setTimeout(function () {
			window.scrollTo(0, 0);
			window.parentIFrame.sendMessage(
				{
					type: message_type_resize,
					height: $(selector_editor_container).outerHeight(),
					width: $(selector_editor_container).outerWidth()
				}
			);
		}, 1000);
	});

	// After every sync_interval ms, sync the textarea inside the iframe with the one outside it.
	var $textarea = $(selector_textarea);
	setInterval(function () {
		var textarea_id = $textarea.attr('id');
		if (typeof tinyMCE !== 'undefined') {
			var editor = tinyMCE.get(textarea_id);
			if (editor != null) {
				editor.save();
			}
		}

		if (typeof parentIFrame !== 'undefined') {
			parentIFrame.sendMessage(
				{
					type: message_type_content_sync,
					id: textarea_id,
					content: $textarea.val()
				}
			);
		}
	}, sync_interval);
});