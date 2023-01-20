jQuery(document).ready(function()
{
	var wcds_av_and_est_data;
	var wcds_av_and_est_chart_data;
	
	wcds_start_reloading_av_and_est_widget_data();
	
//Chart

function wcds_start_reloading_av_and_est_widget_data()
{
	jQuery('#wcds_av_and_est_wait_box').fadeIn(500); 
	jQuery('#wcds_av_and_est_stats_canvas_box').fadeOut(500, function(){ wcds_reset_av_and_est_widget_canvas(); wcds_load_new_av_and_est_widget_data()});
	//jQuery('#wcds_av_and_est_stats_table').delay(600).fadeOut(300, function(){ wcds_reset_av_and_est_widget_canvas(); wcds_load_new_av_and_est_widget_data()});
}
function wcds_load_new_av_and_est_widget_data()
{
	var formData = new FormData();
	formData.append('action', 'wcds_av_and_est_widget');
	
	jQuery.ajax({
		url: ajaxurl, 
		type: 'POST',
		data: formData,
		async: true,
		success: function (data) 
		{
			wcds_refresh_av_and_est_widget(data);
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
function wcds_refresh_av_and_est_widget(data)
{
	
	wcds_av_and_est_data = jQuery.parseJSON(data); 
	wcds_av_and_est_clear_table();
	jQuery('#wcds_av_and_est_wait_box').fadeOut(300);
	jQuery('#wcds_av_and_est_stats_canvas_box').delay(300).fadeIn(500, wcds_create_av_and_est_chart);
	//jQuery('#wcds_av_and_est_stats_table').delay(300).fadeIn(500, wcds_create_av_and_est_chart);
}
function wcds_create_av_and_est_chart()
{
	var labels_array  = new Array();
	var data_array = new Array();
	var amount_to_print;
	var month_estimation;
	var total_today_earning;
	var avarage_per_day_this_month;
	var total_month_earning;
	var avarage_per_month;
	var total_year_earning;
	var avarage_per_year;
	
	if(wcds_av_and_est_data != null && typeof wcds_av_and_est_data != "undefined")
	{
		month_estimation = parseFloat(wcds_av_and_est_data.avarage_per_day_this_month*wcds_av_and_est_data.number_of_days_in_current_month).toFixed(wcds_decimals);
		total_today_earning = parseFloat(wcds_av_and_est_data.total_today_earning).toFixed(wcds_decimals);
		avarage_per_day_this_month = parseFloat(wcds_av_and_est_data.avarage_per_day_this_month).toFixed(wcds_decimals);
		total_month_earning = parseFloat(wcds_av_and_est_data.total_month_earning).toFixed(wcds_decimals);
		avarage_per_month = parseFloat(wcds_av_and_est_data.avarage_per_month).toFixed(wcds_decimals);
		total_year_earning = parseFloat(wcds_av_and_est_data.total_year_earning).toFixed(wcds_decimals);
		avarage_per_year = parseFloat(wcds_av_and_est_data.avarage_per_year).toFixed(wcds_decimals);
		
		//console.log(wcds_decimals);
		//console.log(avarage_per_year);
		
		
		amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_av_and_est_currency+total_today_earning : total_today_earning+wcds_av_and_est_currency;
		jQuery('#wcds_av_and_est_stats_canvas_box').append('<div class="wcds_avg_est_circle_chart" data-width="2" data-dimension="250" data-text="'+amount_to_print+'" data-info="'+wcds_today_label+'" data-fontsize="38" data-fgcolor="#255a8c" data-part="'+wcds_av_and_est_data.number_of_hours_past_today+'" data-total="24" ></div>');
		
		amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_av_and_est_currency+avarage_per_day_this_month : avarage_per_day_this_month+wcds_av_and_est_currency;
		jQuery('#wcds_av_and_est_stats_canvas_box').append('<div class="wcds_avg_est_circle_chart_comment" ><span class="wcds_avg_est_comment_value">'+amount_to_print+'</span><span class="wcds_avg_est_comment_text">'+wcds_today_avarage_label+'</span></div>');
		
		amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_av_and_est_currency+total_month_earning : total_month_earning+wcds_av_and_est_currency;
		jQuery('#wcds_av_and_est_stats_canvas_box').append('<div class="wcds_avg_est_circle_chart" data-width="2" data-dimension="250" data-text="'+amount_to_print+'" data-info="'+wcds_month_label+'" data-fontsize="38" data-fgcolor="#71b02f" data-part="'+wcds_av_and_est_data.number_of_days_from_month_beginning+'" data-total="'+wcds_av_and_est_data.number_of_days_in_current_month+'" ></div>');
		
		amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_av_and_est_currency+avarage_per_month : avarage_per_month+wcds_av_and_est_currency;
		jQuery('#wcds_av_and_est_stats_canvas_box').append('<div class="wcds_avg_est_circle_chart_comment" ><span class="wcds_avg_est_comment_value">'+amount_to_print+'</span><span class="wcds_avg_est_comment_text">'+wcds_month_avarage_label+'</span></div>');
		
		amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_av_and_est_currency+total_year_earning : total_year_earning+wcds_av_and_est_currency;
		jQuery('#wcds_av_and_est_stats_canvas_box').append('<div class="wcds_avg_est_circle_chart" data-width="2" data-dimension="250" data-text="'+amount_to_print+'" data-info="'+wcds_year_label+'" data-fontsize="38" data-fgcolor="#a46497" data-part="'+wcds_av_and_est_data.number_of_days_from_year_beginning+'" data-total="'+wcds_av_and_est_data.number_of_days_in_current_year+'" ></div>');
		
		amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_av_and_est_currency+avarage_per_year : avarage_per_year+wcds_av_and_est_currency;
		jQuery('#wcds_av_and_est_stats_canvas_box').append('<div class="wcds_avg_est_circle_chart_comment" ><span class="wcds_avg_est_comment_value">'+amount_to_print+'</span><span class="wcds_avg_est_comment_text">'+wcds_year_avarage_label+'</span></div>');
		
		amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_av_and_est_currency+month_estimation : month_estimation+wcds_av_and_est_currency;
		jQuery('#wcds_av_and_est_stats_canvas_box').append('<div class="wcds_avg_est_estimation_box" ><span class="wcds_avg_est_estimation_value">'+amount_to_print+'</span><span class="wcds_avg_est_estimation_text">'+wcds_estimation_label+'</span></div>');
		 
		jQuery('.wcds_avg_est_circle_chart').circliful(); 
	}
	//wcds_render_av_and_est_table();
}
//End chart

//Table
function wcds_render_av_and_est_table()
{
	jQuery('#wcds_av_and_est_table_body').empty();
	var total_earnings = total_count = 0;
	var name = '';
	var value_to_print = '';
	jQuery.each(wcds_av_and_est_data,function(index, product)
	{
		if(product != null)
		{
			name = product.zone_name;
			if(jQuery('#wcds_av_and_est_view_type').val() === 'country')
				name = '<img src="'+wcds_flags_path+product.zone_code+'.png" /> '+name;
			
			value_to_print = (product.total_earning).toFixed(wcds_decimals);
			jQuery('#wcds_av_and_est_table_body').append("<tr>"+
															"<td>"+name+"</td>"+
															"<td>"+product.total_purchases+"</td>"+
															"<td>"+value_to_print+wcds_products_currency+"</td>"+
														"</tr>");
			if(product.total_earning != null)
				total_earnings += parseFloat(product.total_earning);
			if(product.total_purchases != null)
				total_count += parseInt(product.total_purchases);
		}
	});
	jQuery('#wcds_av_and_est_foot_total_count').html(parseFloat(total_count));
	jQuery('#wcds_av_and_est_foot_total_earnings').html(parseFloat(total_earnings).toFixed(wcds_decimals)+wcds_earnings_currency);
	
}

//Misc
function wcds_av_and_est_clear_table()
{
	jQuery('#wcds_av_and_est_table_body').empty();
}
function wcds_reset_av_and_est_widget_canvas()
{
	jQuery('#wcds_av_and_est_stats_canvas_box').empty();
	//jQuery('#wcds_av_and_est_stats_canvas_box').append('<canvas id="wcds_av_and_est_stats" ></canvas>');
}
function wcds_av_and_est_getRandomColor() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}
});