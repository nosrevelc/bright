jQuery(document).ready(function ($) {
	$('.post-list-delete-cell a').click(function(event){
		var confirmation = confirm(post_list_deletion_confirmation);
		if(!confirmation)
		{
			event.preventDefault();
		}
	});

	$('.post-list-row-expand-icon').click(function(){
		$(this).closest('.post-list-table-row').toggleClass('post-list-row-expanded');
	});
});