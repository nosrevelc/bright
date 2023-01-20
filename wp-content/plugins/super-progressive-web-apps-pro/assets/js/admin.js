(function($){
	// Start Activate/Deactivate License Key
	$('#superpea_pro_activate_deactivate_plugin').click(function(e){		
		e.preventDefault();
		var self = $(this)
		var licenseField = $('#superpwa-license').val();
		var licenseCurrentStatus = self.attr('data-status');
		if(licenseCurrentStatus=='inactive'){
			if(!confirm("Are you sure to deactivate license? ")){
				self.attr("disabled",false);
				return false;
			}
		}
		if(!licenseField){
			alert("Please enter the License Key")
		}
		if(licenseField){
			var license_key = licenseField;
			var start;
			var licenseCurrentStatus = self.attr('data-status');
			if (licenseCurrentStatus== 'active' ) {
						var dots = '.';
						$("#superpea_pro_activate_deactivate_plugin").val("Validating"+dots);
						var interval = 0;
						start = setTimeout(function(){
							dots +='.';
							interval++;
							if(interval==3){
								interval=0;
								dots = '.';
							}
							$("#superpea_pro_activate_deactivate_plugin").val("Validating"+dots);
						}, 0);
					}

					if (licenseCurrentStatus == 'inactive' ) {
	            		var start;
	            		var dots = '.';
						$("#superpea_pro_activate_deactivate_plugin").val("Deactivating"+dots);
						var interval = 0;
						start = setTimeout(function(){
							dots +='.';
							interval++;
							if(interval==3){
								interval=0;
								dots = '.';
							}
						}, 0);
	            	}

			jQuery.ajax({
	            type: "POST",    
	            url: ajaxurl,                    
	            dataType: "json",
	            data:{action:"superpwa_pro_activate_license",license_key:license_key, security_nonce:superpwa_pro_var.security_nonce, 'license_status': licenseCurrentStatus},
	            success:function(response){
	            	console.log(response);
	            	if (response.status == 'missing') {
	            	$('.error-message').html(response.message)
	            	$('.error-message').css("display","block")
	            	$("#superpea_pro_activate_deactivate_plugin").val("Activate License");
	            	}
	            	if (response.status == 'invalid') {
	            	$('.error-message').html(response.message)
	            	$('.error-message').css("display","block")
	            	$("#superpea_pro_activate_deactivate_plugin").val("Activate License");
	            	}
	            	if (response.status == 'expired') {
	            	$('.error-message').html(response.message)
	            	$('.error-message').css("display","block")
	            	}

	            	if (response.status == 'expired') {
	            		window.location.reload();
	            	}
	            	
	            	if( response.status=='active' ){
	            		var licenseCurrentStatus = self.attr('data-status');
	            		console.log(licenseCurrentStatus);
	            			 
	            		
	            	}
	            	if(response.status=='active' || response.status=='deactivated'){
	            	$("#superpwa_refresh_license").css("display","block")
	            	window.location.reload();
	            	}else{
	            		self.attr("disabled",false);
	            	}
	            }
	        });
		}
	})		

	// End Activate/Deactivate License Key

// Start Refresh superpwa License
	$('#superpwa_refresh_active').click(function(e){		
		e.preventDefault();
		var self = $(this)
		var licenseField = $('#original_superpwa_license').val();
		var licenseCurrentStatus = 'active';

		if(licenseField){
			var license_key = licenseField;
			jQuery("#superpwa_refresh_icon").addClass( 'spin' );

			jQuery.ajax({
	            type: "POST",    
	            url: ajaxurl,                    
	            dataType: "json",
	            data:{action:"superpwa_pro_activate_license",license_key:license_key, security_nonce:superpwa_pro_var.security_nonce, 'license_status': licenseCurrentStatus},
	            success:function(response){
	            	if( response.status =='active' ){
	            	window.location.reload();
	            }
	            
	            	
	            }
	        });
		}
	})

	// End Refresh superpwa License

	// Start Refresh_expired superpwa License
	$('#superpwa_refresh_expired').click(function(e){		
		e.preventDefault();
		var self = $(this)
		var licenseField = $('#original_superpwa_license').val();
		var licenseCurrentStatus = 'active';

		if(licenseField){
			var license_key = licenseField;
			jQuery("#superpwa_refresh_icon").addClass( 'spin' );

			jQuery.ajax({
	            type: "POST",    
	            url: ajaxurl,                    
	            dataType: "json",
	            data:{action:"superpwa_pro_activate_license",license_key:license_key, security_nonce:superpwa_pro_var.security_nonce, 'license_status': licenseCurrentStatus},
	            success:function(response){
	            	if( response.status =='active' ){
	            	window.location.reload();
	            }
	            
	            	
	            }
	        });
		}
	})

	// End Refresh_expired superpwa License

	 // Start
	 // Run when expired & between 0-7 Days to get Updated Data if he has done the Renewal   
	 setTimeout(function(e){ 
   		var rem_days = jQuery("#remaining_days").val();
   		if ( rem_days <= 7 ) {
   		jQuery("#superpwa_autorefresh").click();
		var self = $(this)
		var licenseField = $('#original_superpwa_license').val();
		var licenseCurrentStatus = 'active';
   		
	    if(licenseField){
			var license_key = licenseField;

			jQuery.ajax({
	            type: "POST",    
	            url: ajaxurl,                    
	            dataType: "json",
	            data:{action:"superpwa_pro_activate_license",license_key:license_key, security_nonce:superpwa_pro_var.security_nonce, 'license_status': licenseCurrentStatus},
	            success:function(response){
	            	var days_remain = response.days_remaining
	            	if (days_remain >7 || days_remain =='Lifetime' ) {
	            	window.location.reload();
	            }
	        }
	    }
	    );

	        jQuery.ajax({
	        url: ajaxurl,
	        method: 'post',
	        data: {
	        		action: 'superpwa_pro_autocheck',
	        		security_nonce:superpwa_pro_var.security_nonce
	              },
	        success: function(response){
	        	var resp = JSON.parse(response);
	        }
	    });
		}
   	}
   },1000);

	 
	// End 
	// To run when expired to get data if he has done the Renewal   

})(jQuery);

