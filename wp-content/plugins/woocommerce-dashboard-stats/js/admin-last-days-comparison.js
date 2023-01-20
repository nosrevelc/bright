jQuery(document).ready(function()
{
	var wcds_days_comp;
	var wcds_days_comp_chart_data;
	var wcds_days_comp_widget_picker_start_date;
	var wcds_days_comp_widget_picker_end_date;
	var wcds_days_comp_widget_start_date;
	var wcds_days_comp_widget_end_date;
	
	wcds_days_comp_set_range_date_selectors();
	wcds_start_reloading_days_comp_widget_data(null);
	jQuery('#wcds_days_comparison_chart_filter_button').click(wcds_start_reloading_days_comp_widget_data);
//Chart
function wcds_days_comp_set_range_date_selectors()
	{
		//if(selector_type == 'daily')
		{
			wcds_days_comp_widget_picker_start_date = jQuery( "#wcds_days_comparison_picker_start_date" ).pickadate({selectMonths: true, selectYears: true, trueformatSubmit: 'yyyy-mm-dd', format: 'yyyy/mm/dd'});
			wcds_days_comp_widget_picker_end_date = jQuery( "#wcds_days_comparison_picker_end_date" ).pickadate({selectMonths: true, selectYears: true, formatSubmit: 'yyyy-mm-dd', format: 'yyyy/mm/dd'});
		}
	}
function wcds_days_comp_widget_are_date_good()
	{
		var picker_start_date = wcds_days_comp_widget_picker_start_date.pickadate('picker');
		var picker_end_date = wcds_days_comp_widget_picker_end_date.pickadate('picker'); 
		
		//if(wcds_days_comp_widget_date_range_type == 'daily')
		{
			wcds_days_comp_widget_start_date = picker_start_date.get('select', 'yyyy-mm-dd'); 
			wcds_days_comp_widget_end_date = picker_end_date.get('select', 'yyyy-mm-dd');
		}
		if( /* wcds_days_comp_widget_start_date=='' || wcds_days_comp_widget_start_date=='' || */ wcds_days_comp_widget_start_date > wcds_days_comp_widget_end_date)
			return false;
		
		return true;
	}
function wcds_start_reloading_days_comp_widget_data(event)
{
	if(!wcds_days_comp_widget_are_date_good())
	{
		alert(wcds_days_comp_widget_date_error);
		return;
	}
	
	jQuery('#wcds_days_comp_wait_box').fadeIn(500); 
	jQuery('#wcds_days_comp_stats_canvas_box').fadeOut(500, function(){ wcds_reset_days_comp_widget_canvas(); wcds_load_new_days_comp_widget_data()});
}
function wcds_load_new_days_comp_widget_data()
{
	var formData = new FormData();
	formData.append('action', 'wcds_days_comp_widget');
	formData.append('start_date', wcds_days_comp_widget_start_date);
	formData.append('end_date', wcds_days_comp_widget_end_date);
		
	jQuery.ajax({
		url: ajaxurl, 
		type: 'POST',
		data: formData,
		async: true,
		success: function (data) 
		{
			wcds_refresh_days_comp_widget(data);
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
function wcds_refresh_days_comp_widget(data)
{
	
	wcds_days_comp = jQuery.parseJSON(data); 
	//wcds_days_comp_clear_table();
	jQuery('#wcds_days_comp_wait_box').fadeOut(300);
	jQuery('#wcds_days_comp_stats_canvas_box').delay(300).fadeIn(500, wcds_create_days_comp_chart);
}
function wcds_create_days_comp_chart()
{
	var labels_array  = new Array();
	var data_array = new Array();
	/* 	console.log(wcds_days_comp);*/
	
	if(wcds_days_comp != null && typeof wcds_days_comp != "undefined")
	{
		var labels = [];
		var last_days = [];
		var avarages = [];
		var counter = 0;
		jQuery.each(wcds_days_comp, function(index, value)
		{
			var fillColor= "rgba(113,76,47,1)";
			var strokeColor= "rgba(113,76,47,1)";
			var highlightFill= "rgba(113,76,47,0.7)";
			var highlightStroke= "rgba(113,76,47,7)";
		
			
			
			/*Format:
				avarage	   0
				date       "14/11/2015"
				day	       "Saturday"
				gain        0
				label       "Sat 14"
				order_total	"2.4"
				trend       -1
				*/
			
			labels.push(value.label);
			last_days.push(value.order_total);
			avarages.push(value.avarage);
			counter++;
		});
		
		
		/*console.log(labels);
		 console.log(last_days); 
		console.log(avarages);  */
			
		var data = {
				labels: labels,
				datasets: [
					{
						label: "Last 30 days", 
						borderColor : "rgba(113,176,47,1)",
						backgroundColor: "rgba(113,176,47,1)",
						/* fillColor: "rgba(113,176,47,1)",
						strokeColor: "rgba(113,176,47,1)",
						highlightFill: "rgba(113,176,47,0.5)",
						highlightStroke: "rgba(113,176,47,0.5)", */
						data: last_days
					},
					{
						label: "Avarage values",
						borderColor : "rgba(37,90,140,1)",
						backgroundColor: "rgba(37,90,140,1)",
						/* fillColor: "rgba(37,90,140,1)",
						strokeColor: "rgba(37,90,140,1)",
						highlightFill: "rgba(37,90,140,0.5)",
						highlightStroke: "rgba(37,90,140,0.5)", */
						data: avarages
					}
				]
			}; 
		
		var ctx = jQuery("#wcds_days_comp_stats_canvas").get(0).getContext("2d");
		// customTooltips: function(tooltip) { ...} 
		
		//var myBarChart = new Chart(ctx).StackedBar(data, {responsive : true/*, tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>"	 , pointHitDetectionRadius:5 */});
		//var myBarChart = new Chart(ctx).Bar(data, {responsive : true/*, tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>"	 , pointHitDetectionRadius:5 */});
		var myLineChart = new Chart(ctx,{type:'bar', data: data});
	}
	//wcds_render_days_comp_table();
}
//End chart

//Table
function wcds_render_days_comp_table()
{
	
	
}

//Misc
function wcds_days_comp_clear_table()
{
	jQuery('#wcds_days_comp_table_body').empty();
}
function wcds_reset_days_comp_widget_canvas()
{
	jQuery('#wcds_days_comp_stats_canvas_subox').empty();
	jQuery('#wcds_days_comp_stats_canvas_subox').append('<canvas id="wcds_days_comp_stats_canvas" ></canvas>');
}
function wcds_days_comp_getRandomColor() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}
});