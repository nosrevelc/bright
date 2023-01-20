jQuery(document).ready(function()
{
	var wcds_geographic_widget_date_range_type = 'country';
	var wcds_geographic_widget_picker_start_date;
	var wcds_geographic_widget_picker_end_date;
	var wcds_geographic_widget_start_date;
	var wcds_geographic_widget_end_date;
	var wcds_geographic_data;
	var wcds_geographic_chart_data;
	
	jQuery('#wcds_geographic_chart_filter_button').click(wcds_start_reloading_geographic_widget_data);
	wcds_set_range_date_selectors(wcds_geographic_widget_date_range_type);
	wcds_start_reloading_geographic_widget_data(null);
	
//Chart

function wcds_start_reloading_geographic_widget_data(event)
{
	if(event != null)
	{
		event.stopImmediatePropagation();
		event.preventDefault();
	}
	
	if(!wcds_geographic_widget_are_date_good())
		alert(wcds_geographic_widget_date_error);
	else
	{
		jQuery('#wcds_geographic_stats').fadeOut(300);
		jQuery('#wcds_geographic_stats_table').fadeOut(300);
		jQuery('#wcds_geographic_wait_box').delay(400).fadeIn(500, function(){ wcds_reset_geographic_widget_canvas(); wcds_load_new_geographic_widget_data()});		
	}
}
function wcds_load_new_geographic_widget_data()
{
	/* console.log(wcds_geographic_widget_start_date);
	console.log(wcds_geographic_widget_end_date); */
	var formData = new FormData();
	wcds_geographic_widget_date_range_type = jQuery('#wcds_geographic_period_range').val();
	formData.append('action', 'wcds_geographic_widget_get_earning_per_area_network');
	formData.append('start_date', wcds_geographic_widget_start_date);
	formData.append('end_date', wcds_geographic_widget_end_date);
	formData.append('view_type', jQuery('#wcds_geographic_view_type').val());
	formData.append('max_results_num', jQuery('#wcds_max_results_num').val());
	
	jQuery.ajax({
		url: ajaxurl, 
		type: 'POST',
		data: formData,
		async: true,
		success: function (data) 
		{
			wcds_refresh_geographic_widget(data);
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
function wcds_refresh_geographic_widget(data)
{
	
	wcds_geographic_data = jQuery.parseJSON(data); //.zone_name ; .totals ; .order_num
	/* console.log(data);
	console.log(wcds_geographic_data);  */
	
	wcds_geographic_clear_table();
	jQuery('#wcds_geographic_wait_box').fadeOut(300);
	jQuery('#wcds_geographic_stats').delay(300).fadeIn(500);
	jQuery('#wcds_geographic_stats_table').delay(300).fadeIn(500, wcds_create_geographic_chart);
}
function wcds_create_geographic_chart()
{
	var labels_array  = new Array();
	var data_array = new Array();
	jQuery.each(wcds_geographic_data,function(index, product)
	{
		labels_array.push(product.zone_name);
		data_array.push(parseFloat(product.total_earning));
	});
	var wcds_geographic_chart_data = {
		labels:labels_array,
		datasets:[{
					label: "Sales",
					/* fillColor: "rgba(64,142,252, 0.2)",
					strokeColor: "rgba(64,142,252, 1)",
					pointColor: "rgba(64,142,252, 1)",
					pointStrokeColor: "#fff",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba(220,220,220,1)", */
					pointBackgroundColor : "rgba(20,48,123, 1)",
					borderColor : "rgba(20,48,123, 0.75)",
					backgroundColor: "rgba(20,48,123, 0.2)",
					data: data_array
				}]
	}
	var char_options =  {
		legend: {
			display: false
		}/* ,
		scale: {
			// Hides the scale
			display: false
		} */
	};
	
	
	ctx = jQuery("#wcds_geographic_stats").get(0).getContext("2d");
	//var myChart = new Chart(ctx).Radar(wcds_geographic_chart_data, {responsive : true, pointHitDetectionRadius:5}); //default 20
	var myChart = new Chart(ctx,{type:'radar', data: wcds_geographic_chart_data, options: char_options});
	
	wcds_render_geographic_table();
}
//End chart

//Table
function wcds_render_geographic_table()
{
	jQuery('#wcds_geographic_table_body').empty();
	var total_earnings = total_count = 0;
	var name = '';
	var amount_to_print
	var wcds_products_currency = ''
	jQuery.each(wcds_geographic_data,function(index, product)
	{
		if(product != null)
		{

            if(product.zone_coin!=''){
                wcds_products_currency = product.zone_coin+'';
            }else{
                wcds_products_currency = ''
            }

			amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ? wcds_products_currency+product.total_earning : product.total_earning+wcds_products_currency;
			name = product.zone_name;
			if(jQuery('#wcds_geographic_view_type').val() === 'country')
				name = '<img src="'+wcds_flags_path+product.zone_code+'.png" /> '+name;

			if(jQuery('#wcds_geographic_view_type').val() === 'site'){
				if(product.zone_code!=''){
					name = '<img src="'+wcds_flags_path+product.zone_code+'.png" />&nbsp;&nbsp;'+name;		
				}
			}
				
			
			jQuery('#wcds_geographic_table_body').append("<tr>"+
															"<td>"+name+"</td>"+
															"<td>"+product.total_purchases+"</td>"+
															"<td>"+amount_to_print+"</td>"+
														"</tr>");
			if(product.total_earning != null)
				total_earnings += parseFloat(product.total_earning);
			if(product.total_purchases != null)
				total_count += parseInt(product.total_purchases);
		}
	});

	
		amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_earnings_currency+parseFloat(total_earnings).toFixed(wcds_decimals) : parseFloat(total_earnings).toFixed(wcds_decimals)+wcds_earnings_currency;
	
		jQuery('#wcds_geographic_foot_total_count').html(parseFloat(total_count));
        jQuery('#wcds_geographic_foot_total_earnings').html(amount_to_print);

        //hide because diferent coins, maybe a currency conversion ?
        jQuery('#wcds_geographic_foot_total_earnings').html('');

	
}

//Misc
function wcds_geographic_clear_table()
{
	jQuery('#wcds_geographic_table_body').empty();
}
function wcds_set_range_date_selectors()
{
	//if(selector_type == 'daily')
	{
		wcds_geographic_widget_picker_start_date = jQuery( "#wcds_geographic_picker_start_date" ).pickadate({selectMonths: true, selectYears: true, trueformatSubmit: 'yyyy-mm-dd', format: 'yyyy/mm/dd'});
		wcds_geographic_widget_picker_end_date = jQuery( "#wcds_geographic_picker_end_date" ).pickadate({selectMonths: true, selectYears: true, formatSubmit: 'yyyy-mm-dd', format: 'yyyy/mm/dd'});
	}
}
function wcds_geographic_widget_are_date_good()
{
	var picker_start_date = wcds_geographic_widget_picker_start_date.pickadate('picker');
	var picker_end_date = wcds_geographic_widget_picker_end_date.pickadate('picker'); 
	
	wcds_geographic_widget_start_date = picker_start_date.get('select', 'yyyy-mm-dd'); 
	wcds_geographic_widget_end_date = picker_end_date.get('select', 'yyyy-mm-dd');
	
	if( wcds_geographic_widget_start_date > wcds_geographic_widget_end_date)
		return false;
	
	return true;
}
function wcds_reset_geographic_widget_canvas()
{
	/* var myCanvas = jQuery("#wcds_geographic_stats").get(0);
    var ctx = myCanvas.getContext('2d');
    ctx.clearRect(0, 0, myCanvas.width, myCanvas.height) */
	jQuery('#wcds_geographic_stats_canvas_box').empty();
	jQuery('#wcds_geographic_stats_canvas_box').append('<canvas id="wcds_geographic_stats" ></canvas>');
}
function wcds_geographic_getRandomColor() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}
});