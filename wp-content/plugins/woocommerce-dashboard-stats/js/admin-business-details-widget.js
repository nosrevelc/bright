jQuery(document).ready(function()
{
	
	wcds_start_reloading_business_detail_widget_data();
		
	//Chart

	function wcds_start_reloading_business_detail_widget_data()
	{
		jQuery('#wcds_business_details_wait_box').fadeIn(500); 
		wcds_load_business_detail_widget_data();
	}
	function wcds_load_business_detail_widget_data()
	{
		var closed_all_time, royalties_all_time, closed_this_year, royalties_this_year, in_progress, rejected_all_time, rejected_this_year, accepted_all_time, aceptance =0;
		var wcds_bd_data;
		var formData = new FormData();
		formData.append('action', 'wcds_business_detail_widget_data');
		
		jQuery.ajax({
			url: ajaxurl, 
			type: 'POST',
			data: formData,
			async: true,
			success: function (data) 
			{
				jQuery('#wcds_business_details_wait_box').fadeOut(300); 
				wcds_bd_data = jQuery.parseJSON(data); 

				closed_all_time =wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_av_and_est_currency+wcds_bd_data.closed_all_time : wcds_bd_data.closed_all_time+wcds_av_and_est_currency;
				jQuery('#closed_all_time').html(wcds_bd_data.closed_all_time);

				closed_this_year =wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_av_and_est_currency+wcds_bd_data.closed_this_year : wcds_bd_data.closed_this_year+wcds_av_and_est_currency;
				jQuery('#closed_this_year').html(wcds_bd_data.closed_this_year);

				in_progress =wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_av_and_est_currency+wcds_bd_data.in_progress : wcds_bd_data.in_progress+wcds_av_and_est_currency;
				jQuery('#in_progress').html(wcds_bd_data.in_progress);

				rejected_all_time =wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_av_and_est_currency+wcds_bd_data.rejected_all_time : wcds_bd_data.rejected_all_time+wcds_av_and_est_currency;
				jQuery('#rejected_all_time').html(wcds_bd_data.rejected_all_time);

				rejected_this_year =wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_av_and_est_currency+wcds_bd_data.rejected_this_year : wcds_bd_data.rejected_this_year+wcds_av_and_est_currency;
				jQuery('#rejected_this_year').html(wcds_bd_data.rejected_this_year);

				accepted_all_time =wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_av_and_est_currency+wcds_bd_data.accepted_all_time : wcds_bd_data.accepted_all_time+wcds_av_and_est_currency;
				jQuery('#accepted_all_time').html(wcds_bd_data.accepted_all_time);

				jQuery('#aceptance').html(wcds_bd_data.aceptance+'%');

				var rot = (accepted_all_time*180)/100;
				jQuery('.needle').css('transform','rotate('+rot+'deg)');

				royalties_all_time =wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_av_and_est_currency+wcds_bd_data.royalties_all_time : wcds_bd_data.royalties_all_time+wcds_av_and_est_currency;
				jQuery('#royalties_all_time').html(royalties_all_time);

				royalties_this_year =wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_av_and_est_currency+wcds_bd_data.royalties_this_year : wcds_bd_data.royalties_this_year+wcds_av_and_est_currency;
				jQuery('#royalties_this_year').html(royalties_this_year);

				jQuery('#wcds_business_details_box').fadeIn(); 
	
			},
			error: function (data,error) 
			{
				//alert("Could not contact the server, Error message: "+error);
			},
			cache: false,
			contentType: false, 
			processData: false
		});
	}

});