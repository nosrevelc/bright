jQuery(document).ready(function ($) {
	$('.sortable-boxes-container').sortable({
		handle: '.sortable-box-head',
		helper: 'clone',
		axis: 'y'
	});

	$('body').on('click', '.collapsable-box-head', function (e) {
		$(this).closest('.collapsable-box').toggleClass('collapsable-box-open');
	});

	$('.conditionized').conditionize();
});