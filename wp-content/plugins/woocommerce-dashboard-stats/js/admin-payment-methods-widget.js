jQuery(document).ready(function()
{
	var wcds_payment_methods_widget_picker_start_date;
	var wcds_payment_methods_widget_picker_end_date;
	var wcds_payment_methods_widget_start_date;
	var wcds_payment_methods_widget_end_date;
	var wcds_payment_methods_chart_labels;
	var wcds_payment_methods_total_spent;
	var wcds_payment_methods_count_per_date;
	var wcds_payment_methods_chart_data;
	var wcds_payment_methods_data;
	var wcds_payment_methods_colors;
	jQuery('#wcds_payment_methods_chart_filter_button').click(wcds_start_reloading_customers_widget_data);
	wcds_payment_methods_set_range_date_selectors();
	wcds_start_reloading_customers_widget_data(null)
	
	//Chart 
	function wcds_start_reloading_customers_widget_data(event)
	{
		if(event != null)
		{
			event.stopImmediatePropagation();
			event.preventDefault();
		}
		if(!wcds_payment_methods_widget_are_date_good())
			alert(wcds_payment_methods_widget_date_error);
		else
		{
			jQuery('#wcds_payment_methods_stats_table').fadeOut(500);
			jQuery('#wcds_payment_methods_stats').fadeOut(500);		
			jQuery('#wcds_payment_methods_wait_box').delay(600).fadeIn(300, function(){ wcds_reset_customers_widget_canvas(); wcds_load_new_customers_widget_data()});
		}
	}
	function wcds_load_new_customers_widget_data()
	{
		/* console.log(wcds_payment_methods_widget_start_date);
		console.log(wcds_payment_methods_widget_end_date); */
		var formData = new FormData();
		formData.append('action', 'wcds_payment_methods_widget');
		formData.append('start_date', wcds_payment_methods_widget_start_date);
		formData.append('end_date', wcds_payment_methods_widget_end_date);
		formData.append('max_results_num', jQuery('#wcds_payment_methods_num').val());
		
		jQuery.ajax({
			url: ajaxurl, 
			type: 'POST',
			data: formData,
			async: true,
			success: function (data) 
			{
				wcds_refresh_customers_widget(data);
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
	function wcds_refresh_customers_widget(data)
	{
		
		wcds_payment_methods_data = jQuery.parseJSON(data); 
		//.total_spent ; .order_num ; .name; .last_name; .customer_email; .customer_id
		/* console.log(wcds_payment_methods_data);
		console.log(result); */
		
		wcds_payment_methods_clear_table();
		jQuery('#wcds_payment_methods_wait_box').fadeOut(300);
		jQuery('#wcds_payment_methods_stats').delay(400).fadeIn(500);
		jQuery('#wcds_payment_methods_stats_table').delay(400).fadeIn(500,wcds_create_customers_chart);
		//wcds_create_customers_chart(); 
	}
	function wcds_create_customers_chart()
	{
		/* wcds_payment_methods_chart_data = new Array();
		jQuery.each(wcds_payment_methods_data,function(index, payment_method)
		{
			wcds_payment_methods_chart_data.push(
			{
				value: parseFloat(payment_method.total_earning).toFixed(wcds_decimals),
				color: wcds_payment_methods_getRandomColor(),
				highlight: "#14307b",
				label: payment_method.payment_method_title
			});
		}); */
		
		//new
		wcds_payment_methods_chart_data = {datasets:[{data:[], backgroundColor:[]}], labels:[]};
		jQuery.each(wcds_payment_methods_data,function(index, payment_method)
		{
			wcds_payment_methods_chart_data.datasets[0].data.push(parseFloat(payment_method.total_earning).toFixed(wcds_decimals));
			wcds_payment_methods_chart_data.datasets[0].backgroundColor.push(wcds_payment_methods_getRandomColor());
			wcds_payment_methods_chart_data.labels.push(payment_method.payment_method_title);
		});
		var char_options =  {
			legend: {
				display: false
			}
		};
		
		ctx = jQuery('#wcds_payment_methods_stats').get(0).getContext("2d");
		//var myPolarAreaChart = new Chart(ctx).Pie(wcds_payment_methods_chart_data, {responsive : true}); 
		var myPolarAreaChart = new Chart(ctx,{type:'pie', data: wcds_payment_methods_chart_data, options: char_options});
		wcds_render_customers_table();
	}
	//Table
	function wcds_render_customers_table()
	{
		jQuery('#wcds_payment_methods_table_body').empty();
		var total_spent = total_count = 0;
		var amount_to_print;
		jQuery.each(wcds_payment_methods_data,function(index, payment_method)
		{	
			amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_payment_methods_currency+parseFloat(payment_method.total_earning).toFixed(wcds_decimals) : parseFloat(payment_method.total_earning).toFixed(wcds_decimals)+wcds_payment_methods_currency;
	
			jQuery('#wcds_payment_methods_table_body').append("<tr>"+
															"<td>"+payment_method.payment_method_title+"</td>"+
															"<td>"+payment_method.payment_method+"</td>"+
															"<td>"+payment_method.total_purchases+"</td>"+
															"<td>"+amount_to_print+"</td>"+
														"</tr>");
			total_spent += parseFloat(payment_method.total_earning);
			total_count += parseInt(payment_method.total_purchases);
		});
		
		amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_payment_methods_currency+parseFloat(total_spent).toFixed(wcds_decimals) : parseFloat(total_spent).toFixed(wcds_decimals)+wcds_payment_methods_currency;
	
		jQuery('#wcds_payment_method_foot_total_count').html(parseInt(total_count));
		jQuery('#wcds_payment_method_foot_total_earnings').html(amount_to_print);
		
	}
	//Misc
	function wcds_payment_methods_clear_table()
	{
		jQuery('#wcds_payment_methods_table_body').empty();
	}
	function wcds_payment_methods_set_range_date_selectors()
	{
		//if(selector_type == 'daily')
		{
			wcds_payment_methods_widget_picker_start_date = jQuery( "#wcds_payment_methods_picker_start_date" ).pickadate({selectMonths: true, selectYears: true, trueformatSubmit: 'yyyy-mm-dd', format: 'yyyy/mm/dd'});
			wcds_payment_methods_widget_picker_end_date = jQuery( "#wcds_payment_methods_picker_end_date" ).pickadate({selectMonths: true, selectYears: true, formatSubmit: 'yyyy-mm-dd', format: 'yyyy/mm/dd'});
		}
	}

	function wcds_payment_methods_widget_are_date_good()
	{
		var picker_start_date = wcds_payment_methods_widget_picker_start_date.pickadate('picker');
		var picker_end_date = wcds_payment_methods_widget_picker_end_date.pickadate('picker'); 
		
		//if(wcds_payment_methods_widget_date_range_type == 'daily')
		{
			wcds_payment_methods_widget_start_date = picker_start_date.get('select', 'yyyy-mm-dd'); 
			wcds_payment_methods_widget_end_date = picker_end_date.get('select', 'yyyy-mm-dd');
		}
		if( /* wcds_payment_methods_widget_start_date=='' || wcds_payment_methods_widget_start_date=='' || */ wcds_payment_methods_widget_start_date > wcds_payment_methods_widget_end_date)
			return false;
		
		return true;
	}
	function wcds_reset_customers_widget_canvas()
	{
		jQuery('#wcds_payment_methods_stats_canvas_box').empty();
		jQuery('#wcds_payment_methods_stats_canvas_box').append('<canvas id="wcds_payment_methods_stats" ></canvas>');
	}
	function wcds_payment_methods_getRandomColor() {
		var letters = '0123456789ABCDEF'.split('');
		var color = '#';
		for (var i = 0; i < 6; i++ ) {
			color += letters[Math.floor(Math.random() * 16)];
		}
		return color;
	}

});