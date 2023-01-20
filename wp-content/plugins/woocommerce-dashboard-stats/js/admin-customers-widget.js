jQuery(document).ready(function()
{
	var wcds_customers_widget_picker_start_date;
	var wcds_customers_widget_picker_end_date;
	var wcds_customers_widget_start_date;
	var wcds_customers_widget_end_date;
	var wcds_customers_chart_labels;
	var wcds_customers_total_spent;
	var wcds_customers_count_per_date;
	var wcds_customers_chart_data;
	var wcds_customers_data;
	var wcds_customers_colors;
	jQuery('#wcds_customers_chart_filter_button').click(wcds_start_reloading_customers_widget_data);
	wcds_customers_set_range_date_selectors();
	wcds_start_reloading_customers_widget_data(null)
	
	//Chart 
	function wcds_start_reloading_customers_widget_data(event)
	{
		if(event != null)
		{
			event.stopImmediatePropagation();
			event.preventDefault(); 
		}
		if(!wcds_customers_widget_are_date_good())
			alert(wcds_customers_widget_date_error);
		else
		{
			jQuery('#wcds_customers_stats_table').fadeOut(500);
			jQuery('#wcds_customers_stats').fadeOut(500);		
			jQuery('#wcds_customers_wait_box').delay(600).fadeIn(300, function(){ wcds_reset_customers_widget_canvas(); wcds_load_new_customers_widget_data()});
		}
	}
	function wcds_load_new_customers_widget_data()
	{
		/* console.log(wcds_customers_widget_start_date);
		console.log(wcds_customers_widget_end_date); */
		var formData = new FormData();
		formData.append('action', 'wcds_customers_widget_get_customers_per_period');
		formData.append('start_date', wcds_customers_widget_start_date);
		formData.append('end_date', wcds_customers_widget_end_date);
		formData.append('product_num', jQuery('#wcds_customers_num').val());
		
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
		
		wcds_customers_data = jQuery.parseJSON(data); 
		//.total_spent ; .order_num ; .name; .last_name; .customer_email; .customer_id
		/* console.log(wcds_customers_data);
		console.log(result); */
		
		wcds_customers_clear_table();
		jQuery('#wcds_customers_wait_box').fadeOut(300);
		jQuery('#wcds_customers_stats').delay(400).fadeIn(500);
		jQuery('#wcds_customers_stats_table').delay(400).fadeIn(500,wcds_create_customers_chart);
		//wcds_create_customers_chart(); 
	}
	function wcds_create_customers_chart()
	{
		/* wcds_customers_chart_data = new Array();
		jQuery.each(wcds_customers_data,function(index, customer)
		{
			//console.log(customer);
			wcds_customers_chart_data.push(
			{
				value: parseFloat(customer.total_spent),
				color: wcds_customers_getRandomColor(),
				highlight: "#14307b",
				label: customer.name+" "+customer.last_name
			});
		}); */
		
		//new
		var char_options =  {
			legend: {
				display: false
			}
		};
		wcds_customers_chart_data = {datasets:[{data:[], backgroundColor:[]}], labels:[]};
		jQuery.each(wcds_customers_data,function(index, customer)
		{
			//console.log(customer);
			wcds_customers_chart_data.datasets[0].data.push( parseFloat(customer.total_spent));
			wcds_customers_chart_data.datasets[0].backgroundColor.push(wcds_customers_getRandomColor());
			wcds_customers_chart_data.labels.push(customer.name+" "+customer.last_name);
		});
		//console.log(wcds_customers_chart_data);
		
		ctx = jQuery("#wcds_customers_stats").get(0).getContext("2d");
		//var myLineChart = new Chart(ctx).Line(wcds_line_chart_data, {responsive : true, pointHitDetectionRadius:5}); //default 20
		//var myPolarAreaChart = new Chart(ctx).Pie(wcds_customers_chart_data, {responsive : true}); 
		//var myPolarAreaChart = new Chart(ctx,{type:'polarArea', data: wcds_customers_chart_data,/*  responsive:true, */ options: char_options});
		var myPolarAreaChart = new Chart(ctx,{type:'doughnut', data: wcds_customers_chart_data,/*  responsive:true, */ options: char_options});
		wcds_render_customers_table();
	}
	//Table
	function wcds_render_customers_table()
	{
		jQuery('#wcds_customers_table_body').empty();
		var total_spent = total_count = 0;
		var is_guest, user_link;
		var amount_to_print;
		var value_to_print = '';
		jQuery.each(wcds_customers_data,function(index, customer)
		{
			is_guest = customer.customer_id > 0 ? wcds_no : wcds_yes;
			user_link = customer.name+" "+customer.last_name;
			if(customer.permalink != 'none')
				user_link = '<a target="_blank" href="'+customer.permalink+'">'+user_link+'</a>';
			
			value_to_print= parseFloat(customer.total_spent).toFixed(wcds_decimals);
			amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_customers_currency+value_to_print : value_to_print+wcds_customers_currency;

			jQuery('#wcds_customers_table_body').append("<tr>"+
															"<td>"+user_link+"</td>"+
															"<td>"+is_guest+"</td>"+
															"<td>"+customer.order_num+"</td>"+
															"<td>"+amount_to_print+"</td>"+
														"</tr>");
			total_spent += parseFloat(customer.total_spent);
			total_count += parseInt(customer.order_num);
		});
		amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ? wcds_customers_currency+parseFloat(total_spent).toFixed(wcds_decimals) : parseFloat(total_spent).toFixed(wcds_decimals)+wcds_customers_currency;

		jQuery('#wcds_customers_foot_total_count').html(parseInt(total_count));
		jQuery('#wcds_customers_foot_total_spent').html(amount_to_print);
		
	}
	//Misc
	function wcds_customers_clear_table()
	{
		jQuery('#wcds_customers_table_body').empty();
	}
	function wcds_customers_set_range_date_selectors()
	{
		//if(selector_type == 'daily')
		{
			wcds_customers_widget_picker_start_date = jQuery( "#wcds_customers_picker_start_date" ).pickadate({selectMonths: true, selectYears: true, trueformatSubmit: 'yyyy-mm-dd', format: 'yyyy/mm/dd'});
			wcds_customers_widget_picker_end_date = jQuery( "#wcds_customers_picker_end_date" ).pickadate({selectMonths: true, selectYears: true, formatSubmit: 'yyyy-mm-dd', format: 'yyyy/mm/dd'});
		}
	}

	function wcds_customers_widget_are_date_good()
	{
		var picker_start_date = wcds_customers_widget_picker_start_date.pickadate('picker');
		var picker_end_date = wcds_customers_widget_picker_end_date.pickadate('picker'); 
		
		//if(wcds_customers_widget_date_range_type == 'daily')
		{
			wcds_customers_widget_start_date = picker_start_date.get('select', 'yyyy-mm-dd'); 
			wcds_customers_widget_end_date = picker_end_date.get('select', 'yyyy-mm-dd');
		}
		if( /* wcds_customers_widget_start_date=='' || wcds_customers_widget_start_date=='' || */ wcds_customers_widget_start_date > wcds_customers_widget_end_date)
			return false;
		
		return true;
	}
	function wcds_reset_customers_widget_canvas()
	{
		jQuery('#wcds_customers_stats_canvas_box').empty();
		jQuery('#wcds_customers_stats_canvas_box').append('<canvas id="wcds_customers_stats" ></canvas>');
	}
	function wcds_customers_getRandomColor() {
		var letters = '0123456789ABCDEF'.split('');
		var color = '#';
		for (var i = 0; i < 6; i++ ) {
			color += letters[Math.floor(Math.random() * 16)];
		}
		return color;
	}

});