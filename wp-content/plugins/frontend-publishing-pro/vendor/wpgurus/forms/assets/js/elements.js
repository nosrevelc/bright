jQuery(document).ready(function ($) {
	$('.element-media').each(function () {
		$(this).wp_media_lib_element(
			$(this).data()
		);
	});

	$('.element-star-rating').each(function () {
		$(this).raty(
			$(this).data()
		);
	});

	$('.element-select-with-search').each(function () {
		$(this).select2(
			$(this).data()
		);
	});

	$('.element-date-picker').each(function () {
		$(this).datetimepicker(
			$(this).data()
		);
	});

	$('.element-color-picker').each(function () {
		$(this).iris();
	});

	$('.element-media-file').each(function () {
		$(this).media_file_element();
	});
});