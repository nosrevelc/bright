jQuery(document).ready(function($){
	$("#superpwa_pro_apk_plugin_sets").on("click", function(){
		var self = $(this);
		self.attr('disabled', true);
		self.parent(".download_wrapper").find(".update_message").addClass("updating-message")
		self.parent(".download_wrapper").find(".update_message").html("<p style='display:inline'><small>Do not close it, its take some time...</small></p>");
		var data_nonce = superpwa_pro_apk.security_nonce;
		$.ajax({
			url: ajaxurl,
			method:'post',
			data:{"action": 'superpwa_pro_apk_plugin_caller', ref: data_nonce},
			dataType:'json',
			success:(function(response){
				self.parent(".download_wrapper").find(".update_message").removeClass("updating-message");
				self.attr('disabled', false);
				if(response.status==200){
					self.parent(".download_wrapper").find(".update_message").html("<p style='display:block; color:green;'>"+response.message+"</p>");
				}else{
					self.parent(".download_wrapper").find(".update_message").html("<p style='display:block;color:red'>"+response.message+"</p>");
				}
			})
		})
	});

	$("#superpwa_pro_plugin_project").on("click", function(){
		var self = $(this);
		self.attr('disabled', true);
		self.parent(".download_wrapper").find(".update_message").addClass("updating-message")
		self.parent(".download_wrapper").find(".update_message").html("<p style='display:inline'><small>Do not close it, its take some time...</small></p>");
		var data_nonce = superpwa_pro_apk.security_nonce;
		$.ajax({
			url: ajaxurl,
			method:'post',
			data:{"action": 'superpwa_pro_apk_plugin_project_caller', ref: data_nonce},
			dataType:'json',
			success:(function(response){
				self.parent(".download_wrapper").find(".update_message").removeClass("updating-message");
				self.attr('disabled', false);
				if(response.status==200){
					self.parent(".download_wrapper").find(".update_message").html("<p style='display:block; color:green;'>"+response.message+"</p>");
				}else{
					self.parent(".download_wrapper").find(".update_message").html("<p style='display:block;color:red'>"+response.message+"</p>");
				}
			})
		})
	});
	
	$(".delete_old_pwa").on("click", function(){
		var self = $(this);
		
		/*self.addClass("updating-message");
		self.find('p').css({'display':'inline'})*/
		var data_nonce = superpwa_pro_apk.security_nonce;
		var version = self.attr("data-version");
		$.ajax({
			url: ajaxurl,
			method:'post',
			data:{"action": 'superpwa_apk_assets_removeold', ref: data_nonce, "oldversion": version},
			dataType:'json',
			success:(function(response){
				if(response.status==200){
					self.parents('.apkzip-row').remove();
				}else{}
			})
		})
	});

	$(".generate_apk_type").change(function(){
		var self = $(this);
		if(self.is(":checked") && self.val()=='mine'){
			$('.signedInfo-wrapper').show();createAPKMsg();
		}else{$('.signedInfo-wrapper').hide();}

	});
	$(".superpwa_uploadkey_store").click(function(e){
		e.preventDefault();
		var self = $(this);
		file = self.parents('.field').find('input[type="file"]')[0].files[0];
		extension = file.name.substr( (file.name.lastIndexOf('.') +1) );
		if(extension!='keystore'){ alert('Select only .keystore file'); return false; }

		var request = new XMLHttpRequest();
		var formData = new FormData();
		formData.append("keystore_file", file);

		request.open("POST", ajaxurl+"?action=superpwa_pro_apk_keystore_upload&security_nonce="+superpwa_pro_apk.security_nonce);
        request.send(formData);
        request.onreadystatechange = function() {
            if (request.readyState === 4) {
                var reponse = JSON.parse(request.response);
              if(reponse.status==200){
              	console.log(self.parents('.field').find('.response_msg'));
                self.parents('.field').find('.response_msg').addClass("dashicons dashicons-yes").css({'color':'#46b450'});
              }
            }
        }
	})
	$('.superpwaupload_new_keystore').click(function(){
		var self = $(this);
		self.parents('.field').find(".keystore").removeClass('hide')
		self.addClass('hide')
	});
	function createAPKMsg(){
		var apktype = $(".generate_apk_type:checked").val()
		if(apktype=='mine'){ $('.update_message').html("Save above options before generate APK"); }
	}
	createAPKMsg();
});