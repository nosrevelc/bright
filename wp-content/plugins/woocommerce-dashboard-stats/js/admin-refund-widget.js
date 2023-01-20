var wcds_refund_pager;
	
jQuery(document).ready(function()
{
	var wcds_refund_chart_labels =[];
	var wcds_total_refund = [];
	var wcds_count_per_date = [];
	var wcds_count_items_per_date = [];
	var wcds_refund_widget_date_range_type = 'daily';
	var wcds_refund_widget_picker_start_date;
	var wcds_refund_widget_picker_end_date;
	var wcds_refund_widget_start_date;
	var wcds_refund_widget_end_date;
	var wcds_refund_data;

	jQuery('#wcds_refund_chart_filter_button').click(wcds_start_reloading_refund_widget_data);
	wcds_set_range_date_selectors(wcds_refund_widget_date_range_type);
	wcds_start_reloading_refund_widget_data(null);
	
//Chart
function wcds_create_refund_chart()
{
	var refund_dataset = [];
	wcds_refund_chart_labels = [];
	wcds_total_refund = {};
	jQuery.each(wcds_refund_data,function(date, refund)
	{
		if(typeof wcds_total_refund[refund.refund_date] == 'undefined')
			wcds_total_refund[refund.refund_date] = 0.0;
		wcds_total_refund[refund.refund_date] += parseFloat(refund.refunded_amount);
	});
	for(var value in wcds_total_refund)
	{
		refund_dataset.push(wcds_total_refund[value]);
		wcds_refund_chart_labels.push(value);
	}
	
	var wcds_line_chart_data = {
	labels : wcds_refund_chart_labels,
	datasets : [
			{
				borderColor : "rgba(64,142,252, 0.75)",
				backgroundColor: "rgba(64,142,252, 0.2)",
				data: refund_dataset
			}
		],
	};
	
	var char_options =  {
			legend: {
				display: false
			}
		};
						
	ctx = jQuery("#wcds_refund_stats").get(0).getContext("2d");
	var myLineChart = new Chart(ctx,{type:'line', data: wcds_line_chart_data, options: char_options});
	
	wcds_render_table();
}
function wcds_start_reloading_refund_widget_data(event)
{
	if(event != null)
	{
		event.stopImmediatePropagation();
		event.preventDefault();
	}
	
	if(!wcds_refund_widget_are_date_good())
		alert(wcds_refund_widget_date_error);
	else
	{
		jQuery('#wcds_refund_stats').fadeOut(500);
		jQuery('#wcds_refund_stats_table').fadeOut(300);
		jQuery('#wcds_refund_wait_box').delay(600).fadeIn(300, function(){ wcds_reset_refund_widget_canvas(); wcds_load_new_refund_widget_data()});	
	}
}
function wcds_load_new_refund_widget_data()
{
	/* console.log(wcds_refund_widget_start_date);
	console.log(wcds_refund_widget_end_date); */
	var formData = new FormData();
	wcds_refund_widget_date_range_type = jQuery('#wcds_refund_period_range').val();
	formData.append('action', 'wcds_refund_widget_get_refund_per_period');
	formData.append('start_date', wcds_refund_widget_start_date);
	formData.append('end_date', wcds_refund_widget_end_date);
	formData.append('view_type', wcds_refund_widget_date_range_type);
	
	jQuery.ajax({
		url: ajaxurl, 
		type: 'POST',
		data: formData,
		async: true,
		success: function (data) 
		{
			wcds_refresh_refund_widget(data);
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
function wcds_refresh_refund_widget(data)
{
	
	wcds_refund_data = jQuery.parseJSON(data); //.dates ; .totals ; .order_num
	wcds_refund_clear_table();
	jQuery('#wcds_refund_wait_box').fadeOut(300);
	jQuery('#wcds_refund_stats').delay(300).fadeIn(500);
	jQuery('#wcds_refund_stats_table').delay(300).fadeIn(500, wcds_create_refund_chart);
}
//End chart

//Table
function wcds_render_table()
{
	jQuery('#wcds_refund_table_body').empty();
	var total_refund = total_count = total_items_count = 0;
	var amount_to_print;
	var value_to_print;
	for(var value in wcds_refund_data)
		{
			value_to_print = parseFloat(wcds_refund_data[value].refunded_amount).toFixed(wcds_decimals);
			amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_refund_currency+value_to_print : value_to_print+wcds_refund_currency;
			jQuery('#wcds_refund_table_body').append("<tr>"+
												"<td><a target='_blank' href='"+wcds_refund_data[value].permalink+"'>"+wcds_refund_data[value].order_id+"</a></td>"+
												"<td>"+wcds_refund_data[value].refund_date+"</td>"+
												"<td class='wcds_refund_reason_column'><a class='wcds_tooltip'>"+wcds_refund_data[value].refund_reason+"<span>"+wcds_refund_data[value].refund_reason+"</span></a></td>"+
												"<td>"+amount_to_print+"</td>"+
											"</tr>");
			total_refund += parseFloat(wcds_refund_data[value].refunded_amount);
			
		}
		
	total_refund = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_refund_currency+parseFloat(total_refund).toFixed(wcds_decimals) : parseFloat(total_refund).toFixed(wcds_decimals)+wcds_refund_currency;
	
	jQuery('#wcds_refund_foot_total').html(total_refund);	
	
	//paging 
	if(document.getElementById('wcds_refund_table_body').children.length > 15)
	{
		wcds_refund_pager  = new Pager('wcds_refund_table_body', 15); 
		wcds_refund_pager.init(); 
		wcds_refund_pager.showPageNav('wcds_refund_pager', 'wcds-refund-list-paging'); 
		wcds_refund_pager.showPage(1);
	}
}

//Misc
function wcds_refund_clear_table()
{
	jQuery('#wcds_refund_table_body').empty();
}
function wcds_set_range_date_selectors()
{
	//if(selector_type == 'daily')
	{
		wcds_refund_widget_picker_start_date = jQuery( "#wcds_refund_picker_start_date" ).pickadate({selectMonths: true, selectYears: true, trueformatSubmit: 'yyyy-mm-dd', format: 'yyyy/mm/dd'});
		wcds_refund_widget_picker_end_date = jQuery( "#wcds_refund_picker_end_date" ).pickadate({selectMonths: true, selectYears: true, formatSubmit: 'yyyy-mm-dd', format: 'yyyy/mm/dd'});
	}
}
function wcds_refund_widget_are_date_good()
{
	var picker_start_date = wcds_refund_widget_picker_start_date.pickadate('picker');
	var picker_end_date = wcds_refund_widget_picker_end_date.pickadate('picker'); 
	
	//if(wcds_refund_widget_date_range_type == 'daily')
	{
		wcds_refund_widget_start_date = picker_start_date.get('select', 'yyyy-mm-dd'); 
		wcds_refund_widget_end_date = picker_end_date.get('select', 'yyyy-mm-dd');
	}
	if( /* wcds_refund_widget_start_date=='' || wcds_refund_widget_start_date=='' || */ wcds_refund_widget_start_date > wcds_refund_widget_end_date)
		return false;
	
	return true;
}
function wcds_reset_refund_widget_canvas()
{
	/* var myCanvas = jQuery("#wcds_refund_stats").get(0);
    var ctx = myCanvas.getContext('2d');
    ctx.clearRect(0, 0, myCanvas.width, myCanvas.height) */
	jQuery('#wcds_refund_stats_canvas_box').empty();
	jQuery('#wcds_refund_stats_canvas_box').append('<canvas id="wcds_refund_stats" ></canvas>');
}
});