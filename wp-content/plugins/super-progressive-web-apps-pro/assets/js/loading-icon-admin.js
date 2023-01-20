jQuery(document).ready(function(){
	jQuery('.superpwaLoadingOptPreview').click(function(){
		var loadername = jQuery(this).attr('data-target');
		jQuery("#"+loadername).toggle();
	})
	jQuery('.loader-selection-data').click(function(){
		var loadername = jQuery(this).attr('data-loader-name');
		var loaderData = loaders_template[loadername];
		var css = loaderData['css'];
		var html = loaderData['content_html'];
		var content = '<style>'+ css +'</style>'+html;

		if(content){
			jQuery(".superpwa-loader-preview").html(content);//.find("div:first").css({"background-color":"#000","width":"max-content"});
			jQuery("#superpwa-icon-selector").val(loadername);
			 jQuery("#superpwaloadingicon").toggle();
		}

	});
});