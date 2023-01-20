jQuery(document).ready(function()
{
	var wcds_geographic_chart_labels =[];
	var wcds_geographic_total_earnings = [];
	var wcds_geographic_count_per_date = [];	
	var wcds_earning_chart_labels =[];
	var wcds_total_earnings = [];
	var wcds_count_per_date = [];
	var wcds_count_items_per_date = [];
	var wcds_earning_widget_date_range_type = 'daily';
	var wcds_earning_widget_picker_start_date;
	var wcds_earning_widget_picker_end_date;
	var wcds_earning_widget_start_date;
	var wcds_earning_widget_end_date;
	
	jQuery('#wcds_earnings_chart_filter_button').click(wcds_start_reloading_earning_widget_data);
	wcds_set_range_date_selectors(wcds_earning_widget_date_range_type);
	//wcds_create_earning_chart();
	//jQuery('#wcds_earnings_chart_filter_button').trigger('click');
	wcds_start_reloading_earning_widget_data(null);
	
//Chart
function wcds_create_earning_chart()
{
	var wcds_line_chart_data = {
	labels : wcds_earning_chart_labels,
	datasets : [
			{
				borderColor : "rgba(64,142,252, 0.75)",
				backgroundColor: "rgba(64,142,252, 0.2)",
				/* fillColor: "rgba(64,142,252, 0.2)",
				strokeColor: "rgba(64,142,252, 1)",
				highlightFill: "rgba(64,142,252, 0.75)",
				highlightStroke: "rgba(64,142,252, 1)", */
				data: wcds_total_earnings
			}
		],
	};
	
	var char_options =  { 
			legend: {
				display: false
			},
			/* tooltips: {
				callbacks: {
				   label: function(tooltipItem) {
						  return tooltipItem.yLabel;
				   }
				}
			} */
		};
						
	ctx = jQuery("#wcds_earning_stats").get(0).getContext("2d");
	//var myLineChart = new Chart(ctx).Line(wcds_line_chart_data, {responsive : true, pointHitDetectionRadius:5}); //default 20
	var myLineChart = new Chart(ctx,{type:'line', data: wcds_line_chart_data, options: char_options});
	
	wcds_render_table();
}
function wcds_start_reloading_earning_widget_data(event)
{
	if(event != null)
	{
		event.stopImmediatePropagation();
		event.preventDefault();
	}
	
	if(!wcds_earning_widget_are_date_good())
		alert(wcds_earning_widget_date_error);
	else
	{
		jQuery('#wcds_earning_stats').fadeOut(500);
		jQuery('#wcds_earning_stats_table').fadeOut(300);
		jQuery('#wcds_earning_wait_box').delay(600).fadeIn(300, function(){ wcds_reset_earning_widget_canvas(); wcds_load_new_earnings_widget_data()});	
	}
}
function wcds_load_new_earnings_widget_data()
{
	/* console.log(wcds_earning_widget_start_date);
	console.log(wcds_earning_widget_end_date); */
	var formData = new FormData();
	wcds_earning_widget_date_range_type = jQuery('#wcds_earning_period_range').val();
	formData.append('action', 'wcds_earning_widget_get_earning_per_period');
	formData.append('start_date', wcds_earning_widget_start_date);
	formData.append('end_date', wcds_earning_widget_end_date);
	formData.append('view_type', wcds_earning_widget_date_range_type);
	
	jQuery.ajax({
		url: ajaxurl, 
		type: 'POST',
		data: formData,
		async: true,
		success: function (data) 
		{
			wcds_refresh_earning_widget(data);
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
function wcds_refresh_earning_widget(data)
{
	
	var result = jQuery.parseJSON(data); //.dates ; .totals ; .order_num
	/* console.log(data);
	console.log(result); */
	wcds_earning_chart_labels = result.dates.split(",");
	wcds_total_earnings = result.totals.split(",");
	wcds_count_per_date = result.order_num.split(",");
	wcds_count_items_per_date = result.total_items.split(",");
	
	
	wcds_earning_clear_table();
	jQuery('#wcds_earning_wait_box').fadeOut(300);
	jQuery('#wcds_earning_stats').delay(300).fadeIn(500);
	jQuery('#wcds_earning_stats_table').delay(300).fadeIn(500, wcds_create_earning_chart);
}
//End chart

//Table
function wcds_render_table()
{
	jQuery('#wcds_table_body').empty();
	var total_earnings = total_count = total_items_count = 0;
	var amount_to_print;
	var value_to_print = '';
	if(wcds_total_earnings != "")
		for(var i=0; i<wcds_total_earnings.length; i++)
		{
			value_to_print= parseFloat(wcds_total_earnings[i]).toFixed(wcds_decimals);
			amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_earnings_currency+value_to_print : value_to_print+wcds_earnings_currency;
		
			jQuery('#wcds_table_body').append("<tr>"+
												"<td>"+wcds_earning_chart_labels[i]+"</td>"+
												"<td>"+wcds_count_items_per_date[i]+"</td>"+
												"<td>"+wcds_count_per_date[i]+"</td>"+
												"<td>"+amount_to_print+"</td>"+
											"</tr>");
			total_earnings += parseFloat(wcds_total_earnings[i]);
			total_count += parseInt(wcds_count_per_date[i]);
			total_items_count += parseInt(wcds_count_items_per_date[i]);
		}
		
	amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_earnings_currency+parseFloat(total_earnings).toFixed(wcds_decimals) : parseFloat(total_earnings).toFixed(wcds_decimals)+wcds_earnings_currency;
	jQuery('#wcds_foot_total_count').html(parseFloat(total_count));
	jQuery('#wcds_products_foot_total_item_count').html(total_items_count);
	jQuery('#wcds_foot_total_earnings').html(amount_to_print);	
}

//Misc
function wcds_earning_clear_table()
{
	jQuery('#wcds_table_body').empty();
}
function wcds_set_range_date_selectors()
{
	//if(selector_type == 'daily')
	{
		wcds_earning_widget_picker_start_date = jQuery( "#wcds_earning_picker_start_date" ).pickadate({selectMonths: true, selectYears: true, trueformatSubmit: 'yyyy-mm-dd', format: 'yyyy/mm/dd'});
		wcds_earning_widget_picker_end_date = jQuery( "#wcds_earning_picker_end_date" ).pickadate({selectMonths: true, selectYears: true, formatSubmit: 'yyyy-mm-dd', format: 'yyyy/mm/dd'});
	}
}
function wcds_earning_widget_are_date_good()
{
	var picker_start_date = wcds_earning_widget_picker_start_date.pickadate('picker');
	var picker_end_date = wcds_earning_widget_picker_end_date.pickadate('picker'); 
	
	//if(wcds_earning_widget_date_range_type == 'daily')
	{
		wcds_earning_widget_start_date = picker_start_date.get('select', 'yyyy-mm-dd'); 
		wcds_earning_widget_end_date = picker_end_date.get('select', 'yyyy-mm-dd');
	}
	if( /* wcds_earning_widget_start_date=='' || wcds_earning_widget_start_date=='' || */ wcds_earning_widget_start_date > wcds_earning_widget_end_date)
		return false;
	
	return true;
}
function wcds_reset_earning_widget_canvas()
{
	/* var myCanvas = jQuery("#wcds_earning_stats").get(0);
    var ctx = myCanvas.getContext('2d');
    ctx.clearRect(0, 0, myCanvas.width, myCanvas.height) */
	jQuery('#wcds_earning_stats_canvas_box').empty();
	jQuery('#wcds_earning_stats_canvas_box').append('<canvas id="wcds_earning_stats" ></canvas>');
}
});