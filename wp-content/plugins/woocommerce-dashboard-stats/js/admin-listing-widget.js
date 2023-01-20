jQuery(document).ready(function()
{
	var wcds_geographic_chart_labels =[];
	var wcds_geographic_total_listings = [];
	var wcds_geographic_count_per_date = [];	
	var wcds_listing_chart_labels =[];
	var wcds_total_listings = [];
	var wcds_count_per_date = [];
	var wcds_count_items_per_date = [];
	var wcds_listing_widget_date_range_type = 'daily';
	var wcds_listing_widget_picker_start_date;
	var wcds_listing_widget_picker_end_date;
	var wcds_listing_widget_start_date;
	var wcds_listing_widget_end_date;
	
	jQuery('#wcds_listings_chart_filter_button').click(wcds_start_reloading_listing_widget_data);
	wcds_set_range_date_selectors(wcds_listing_widget_date_range_type);
	//wcds_create_listing_chart();
	//jQuery('#wcds_listings_chart_filter_button').trigger('click');
	wcds_start_reloading_listing_widget_data(null);
	
//Chart
function wcds_create_listing_chart()
{




	var wcds_line_chart_data = {
	labels : wcds_listing_chart_labels,
	datasets : [
			{
				borderColor : "rgba(64,142,252, 0.75)",
				backgroundColor: "rgba(64,142,252, 0.2)",
				/* fillColor: "rgba(64,142,252, 0.2)",
				strokeColor: "rgba(64,142,252, 1)",
				highlightFill: "rgba(64,142,252, 0.75)",
				highlightStroke: "rgba(64,142,252, 1)", */
				data: wcds_count_per_date
			}
		],
	};
	
	var char_options =  {
			legend: {
				display: false
			},
			acixY: { minimum: 0 }
			/* tooltips: {
				callbacks: {
				   label: function(tooltipItem) {
						  return tooltipItem.yLabel;
				   }
				}
			} */
		};
						


	ctx = jQuery("#wcds_listing_stats").get(0).getContext("2d");
	//var myLineChart = new Chart(ctx).Line(wcds_line_chart_data, {responsive : true, pointHitDetectionRadius:5}); //default 20
	var myLineChart = new Chart(ctx,{type:'line', data: wcds_line_chart_data, options: char_options});
	
	wcds_render_listing_table();
}
function wcds_start_reloading_listing_widget_data(event)
{
	if(event != null)
	{
		event.stopImmediatePropagation();
		event.preventDefault();
	}
	
	if(!wcds_listing_widget_are_date_good())
		alert(wcds_listing_widget_date_error);
	else
	{
		jQuery('#wcds_listing_stats').fadeOut(500);
		jQuery('#wcds_listing_stats_table').fadeOut(300);
		jQuery('#wcds_listing_wait_box').delay(600).fadeIn(300, function(){ wcds_reset_listing_widget_canvas(); wcds_load_new_listings_widget_data()});	
	}
}
function wcds_load_new_listings_widget_data()
{
	/* console.log(wcds_listing_widget_start_date);
	console.log(wcds_listing_widget_end_date); */
	var formData = new FormData();
	wcds_listing_widget_date_range_type = jQuery('#wcds_listing_period_range').val();
	formData.append('action', 'wcds_listings_widget_get_listings_per_period');
	formData.append('start_date', wcds_listing_widget_start_date);
	formData.append('end_date', wcds_listing_widget_end_date);
	formData.append('view_type', wcds_listing_widget_date_range_type);
	var isNetwork = jQuery('#isnetwork').val();
	formData.append('isNetwork', isNetwork);
	jQuery.ajax({
		url: ajaxurl, 
		type: 'POST',
		data: formData,
		async: true,
		success: function (data) 
		{
			wcds_refresh_listing_widget(data);
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
function wcds_refresh_listing_widget(data)
{
	
	var result = jQuery.parseJSON(data); //.dates ; .totals ; .order_num
	/* console.log(data);
	console.log(result); */
	wcds_listing_chart_labels = result.dates.split(",");
	wcds_count_per_date = result.order_num.split(",");
	


	wcds_listing_clear_table();
	jQuery('#wcds_listing_wait_box').fadeOut(300);
	jQuery('#wcds_listing_stats').delay(300).fadeIn(500);
	jQuery('#wcds_listing_stats_table').delay(300).fadeIn(500, wcds_create_listing_chart);
}
//End chart

//Table
function wcds_render_listing_table()
{
/*
	console.log(wcds_listing_chart_labels);
	console.log(wcds_count_per_date); 
*/

	jQuery('#wcds_table_listing_body').empty();
	var total_count = 0;
	if(wcds_listing_chart_labels != "")
		for(var i=0; i<wcds_listing_chart_labels.length; i++)
		{
			jQuery('#wcds_table_listing_body').append("<tr>"+
												"<td>"+wcds_listing_chart_labels[i]+"</td>"+
												"<td>"+wcds_count_per_date[i]+"</td>"+
											"</tr>");

	
						
			total_count = total_count + parseInt(wcds_count_per_date[i]);					
			//console.log(total_count);
		}
	jQuery('#wcds_listings_foot_total_item_count').html(total_count);
}

//Misc
function wcds_listing_clear_table()
{
	jQuery('#wcds_table_listing_body').empty();
}
function wcds_set_range_date_selectors()
{
	//if(selector_type == 'daily')
	{
		wcds_listing_widget_picker_start_date = jQuery( "#wcds_listing_picker_start_date" ).pickadate({selectMonths: true, selectYears: true, trueformatSubmit: 'yyyy-mm-dd', format: 'yyyy/mm/dd'});
		wcds_listing_widget_picker_end_date = jQuery( "#wcds_listing_picker_end_date" ).pickadate({selectMonths: true, selectYears: true, formatSubmit: 'yyyy-mm-dd', format: 'yyyy/mm/dd'});
	}
}
function wcds_listing_widget_are_date_good()
{
	var picker_start_date = wcds_listing_widget_picker_start_date.pickadate('picker');
	var picker_end_date = wcds_listing_widget_picker_end_date.pickadate('picker'); 
	
	//if(wcds_listing_widget_date_range_type == 'daily')
	{
		wcds_listing_widget_start_date = picker_start_date.get('select', 'yyyy-mm-dd'); 
		wcds_listing_widget_end_date = picker_end_date.get('select', 'yyyy-mm-dd');
	}
	if( /* wcds_listing_widget_start_date=='' || wcds_listing_widget_start_date=='' || */ wcds_listing_widget_start_date > wcds_listing_widget_end_date)
		return false;
	
	return true;
}
function wcds_reset_listing_widget_canvas()
{
	/* var myCanvas = jQuery("#wcds_listing_stats").get(0);
    var ctx = myCanvas.getContext('2d');
    ctx.clearRect(0, 0, myCanvas.width, myCanvas.height) */
	jQuery('#wcds_listing_stats_canvas_box').empty();
	jQuery('#wcds_listing_stats_canvas_box').append('<canvas id="wcds_listing_stats" ></canvas>');
}
});