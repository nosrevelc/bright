jQuery(document).ready(function()
{
	var wcds_experts_widget_picker_start_date;
	var wcds_experts_widget_picker_end_date;
	var wcds_experts_widget_start_date;
	var wcds_experts_widget_end_date;
	var wcds_experts_chart_labels;
	var wcds_experts_total_spent;
	var wcds_experts_count_per_date;
	var wcds_experts_chart_data;
	var wcds_experts_data;
	var wcds_experts_colors;
	jQuery('#wcds_experts_chart_filter_button').click(wcds_start_reloading_experts_widget_data);
	wcds_experts_set_range_date_selectors();
	wcds_start_reloading_experts_widget_data(null)
	
	//Chart 
	function wcds_start_reloading_experts_widget_data(event)
	{
		if(event != null)
		{
			event.stopImmediatePropagation();
			event.preventDefault();
		}
		if(!wcds_experts_widget_are_date_good())
			alert(wcds_experts_widget_date_error);
		else
		{
			jQuery('#wcds_experts_stats_table').fadeOut(500);
			jQuery('#wcds_experts_stats').fadeOut(500);		
			jQuery('#wcds_experts_wait_box').delay(600).fadeIn(300, function(){ wcds_reset_experts_widget_canvas(); wcds_load_new_experts_widget_data()});
		}
	}
	function wcds_load_new_experts_widget_data()
	{
		console.log(wcds_experts_widget_start_date);
		console.log(wcds_experts_widget_end_date);

		var formData = new FormData();
		formData.append('action', 'wcds_experts_widget_get_experts_per_period');
		formData.append('start_date', wcds_experts_widget_start_date);
		formData.append('end_date', wcds_experts_widget_end_date);
		formData.append('expert_num', jQuery('#wcds_experts_num').val());
		//console.log(formData);
		jQuery.ajax({
			url: ajaxurl, 
			type: 'POST',
			data: formData,
			async: true,
			success: function (data) 
			{
				wcds_refresh_experts_widget(data);
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
	function wcds_refresh_experts_widget(data)
	{
		
		wcds_experts_data = jQuery.parseJSON(data); 
		//.total_spent ; .order_num ; .name; .last_name; .customer_email; .customer_id
		//console.log(wcds_experts_data);
		
		wcds_experts_clear_table();
		jQuery('#wcds_experts_wait_box').fadeOut(300);
		jQuery('#wcds_experts_stats').delay(400).fadeIn(500);
		jQuery('#wcds_experts_stats_table').delay(400).fadeIn(500,wcds_create_experts_chart);
		//wcds_create_experts_chart(); 
	}
	function wcds_create_experts_chart()
	{
		var labels = [];
		var total_service_requests = [];
		var number_sr = [];
		var counter = 0;
		jQuery.each(wcds_experts_data, function(index, expert)
		{
			var fillColor= "rgba(113,76,47,1)";
			var strokeColor= "rgba(113,76,47,1)";
			var highlightFill= "rgba(113,76,47,0.7)";
			var highlightStroke= "rgba(113,76,47,7)";
			
			labels.push(expert.name);
			total_service_requests.push(expert.total_service_requests);
			number_sr.push(expert.number_sr);
			counter++;
		});
		
		
		/*console.log(labels);
		 console.log(last_days); 
		console.log(avarages);  */
			
		var data = {
				labels: labels,
				datasets: [
					{
						label: "Total SR (€)", 
						borderColor : "rgba(133, 0, 0, 1)",
						backgroundColor: "rgba(133, 0, 0, 1)",
						data: total_service_requests
					}/*,
					{
						label: "Number SR",
						borderColor : "rgba(37,90,140,1)",
						backgroundColor: "rgba(37,90,140,1)",
						data: number_sr
					}*/
				]
			}; 

			/*
		wcds_experts_chart_data = {datasets:[{data:[], backgroundColor:[]}], labels:[]};
		jQuery.each(wcds_experts_data,function(index, expert)
		{
			//console.log(customer);
			wcds_experts_chart_data.datasets[0].data.push( parseFloat(expert.total_service_requests));
			wcds_experts_chart_data.datasets[0].backgroundColor.push(wcds_experts_getRandomColor());
			wcds_experts_chart_data.labels.push(expert.name);
		});
		//console.log(wcds_experts_chart_data);
		*/
		ctx = jQuery("#wcds_experts_stats").get(0).getContext("2d");
		//var myLineChart = new Chart(ctx).Line(wcds_line_chart_data, {responsive : true, pointHitDetectionRadius:5}); //default 20
		//var myPolarAreaChart = new Chart(ctx).Pie(wcds_experts_chart_data, {responsive : true}); 
		//var myPolarAreaChart = new Chart(ctx,{type:'polarArea', data: wcds_experts_chart_data,/*  responsive:true, */ options: char_options});
		//var myPolarAreaChart = new Chart(ctx,{type:'doughnut', data: wcds_experts_chart_data,/*  responsive:true, */ options: char_options});
		var myLineChart = new Chart(ctx,{type:'horizontalBar', data: data});
		wcds_render_experts_table();
	}
	//Table
	function wcds_render_experts_table()
	{
		jQuery('#wcds_experts_table_body').empty();
		var total_spent = total_count = 0;
		var user_link;
		var amount_to_print;
		var value_to_print = '';
		jQuery.each(wcds_experts_data,function(index, expert)
		{
			if(expert.link != 'none')
				user_link = '<a target="_blank" href="'+expert.link+'">'+expert.name+'</a>';
			
			value_to_print= parseFloat(expert.total_service_requests).toFixed(wcds_decimals);
			amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_experts_currency+value_to_print : value_to_print+wcds_experts_currency;

			jQuery('#wcds_experts_table_body').append("<tr>"+
															"<td>"+user_link+"</td>"+
															"<td>"+expert.number_sr+"</td>"+
															"<td>"+amount_to_print+"</td>"+
														"</tr>");
			total_spent += parseFloat(expert.total_service_requests);
			total_count += parseInt(expert.number_sr);
		});
		amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ? wcds_experts_currency+parseFloat(total_spent).toFixed(wcds_decimals) : parseFloat(total_spent).toFixed(wcds_decimals)+wcds_experts_currency;

		jQuery('#wcds_experts_foot_number_sr').html(parseInt(total_count));
		jQuery('#wcds_experts_foot_total_sr').html(amount_to_print);
		
	}
	//Misc
	function wcds_experts_clear_table()
	{
		jQuery('#wcds_experts_table_body').empty();
	}
	function wcds_experts_set_range_date_selectors()
	{
		//if(selector_type == 'daily')
		{
			wcds_experts_widget_picker_start_date = jQuery( "#wcds_experts_picker_start_date" ).pickadate({selectMonths: true, selectYears: true, trueformatSubmit: 'yyyy-mm-dd', format: 'yyyy/mm/dd'});
			wcds_experts_widget_picker_end_date = jQuery( "#wcds_experts_picker_end_date" ).pickadate({selectMonths: true, selectYears: true, formatSubmit: 'yyyy-mm-dd', format: 'yyyy/mm/dd'});
		}
	}

	function wcds_experts_widget_are_date_good()
	{
		var picker_start_date = wcds_experts_widget_picker_start_date.pickadate('picker');
		var picker_end_date = wcds_experts_widget_picker_end_date.pickadate('picker'); 
		
		//if(wcds_experts_widget_date_range_type == 'daily')
		{
			wcds_experts_widget_start_date = picker_start_date.get('select', 'yyyy-mm-dd'); 
			wcds_experts_widget_end_date = picker_end_date.get('select', 'yyyy-mm-dd');
		}
		if( /* wcds_experts_widget_start_date=='' || wcds_experts_widget_start_date=='' || */ wcds_experts_widget_start_date > wcds_experts_widget_end_date)
			return false;
		
		return true;
	}
	function wcds_reset_experts_widget_canvas()
	{
		jQuery('#wcds_experts_stats_canvas_box').empty();
		jQuery('#wcds_experts_stats_canvas_box').append('<canvas id="wcds_experts_stats" ></canvas>');
	}
	function wcds_experts_getRandomColor() {
		var letters = '0123456789ABCDEF'.split('');
		var color = '#';
		for (var i = 0; i < 6; i++ ) {
			color += letters[Math.floor(Math.random() * 16)];
		}
		return color;
	}

});




jQuery(document).ready(function()
{
	var wcds_referrals_widget_picker_start_date;
	var wcds_referrals_widget_picker_end_date;
	var wcds_referrals_widget_start_date;
	var wcds_referrals_widget_end_date;
	var wcds_referrals_chart_labels;
	var wcds_referrals_total_spent;
	var wcds_referrals_count_per_date;
	var wcds_referrals_chart_data;
	var wcds_referrals_data;
	var wcds_referrals_colors;
	jQuery('#wcds_referrals_chart_filter_button').click(wcds_start_reloading_referrals_widget_data);
	wcds_referrals_set_range_date_selectors();
	wcds_start_reloading_referrals_widget_data(null)
	
	//Chart 
	function wcds_start_reloading_referrals_widget_data(event)
	{
		if(event != null)
		{
			event.stopImmediatePropagation();
			event.preventDefault();
		}
		if(!wcds_referrals_widget_are_date_good())
			alert(wcds_referrals_widget_date_error);
		else
		{
			jQuery('#wcds_referrals_stats_table').fadeOut(500);
			jQuery('#wcds_referrals_stats').fadeOut(500);		
			jQuery('#wcds_referrals_wait_box').delay(600).fadeIn(300, function(){ wcds_reset_referrals_widget_canvas(); wcds_load_new_referrals_widget_data()});
		}
	}
	function wcds_load_new_referrals_widget_data()
	{
		console.log(wcds_referrals_widget_start_date);
		console.log(wcds_referrals_widget_end_date);

		var formData = new FormData();
		formData.append('action', 'wcds_referrals_widget_get_referrals_per_period');
		formData.append('start_date', wcds_referrals_widget_start_date);
		formData.append('end_date', wcds_referrals_widget_end_date);
		formData.append('expert_num', jQuery('#wcds_referrals_num').val());
		//console.log(formData);
		jQuery.ajax({
			url: ajaxurl, 
			type: 'POST',
			data: formData,
			async: true,
			success: function (data) 
			{
				wcds_refresh_referrals_widget(data);
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
	function wcds_refresh_referrals_widget(data)
	{
		
		wcds_referrals_data = jQuery.parseJSON(data); 
		//.total_spent ; .order_num ; .name; .last_name; .customer_email; .customer_id
		//console.log(wcds_referrals_data);
		
		wcds_referrals_clear_table();
		jQuery('#wcds_referrals_wait_box').fadeOut(300);
		jQuery('#wcds_referrals_stats').delay(400).fadeIn(500);
		jQuery('#wcds_referrals_stats_table').delay(400).fadeIn(500,wcds_create_referrals_chart);
		//wcds_create_referrals_chart(); 
	}
	function wcds_create_referrals_chart()
	{
		var labels = [];
		var total_service_requests = [];
		var number_sr = [];
		var counter = 0;
		jQuery.each(wcds_referrals_data, function(index, expert)
		{
			
			labels.push(expert.name);
			total_service_requests.push(expert.total_service_requests);
			number_sr.push(expert.number_sr);
			counter++;
		});
			
		var data = {
				labels: labels,
				datasets: [
					{
						label: "Earnings (€)", 
						borderColor : "rgb(218,165,32)",
						backgroundColor: "rgb(215,160,30)",
						data: total_service_requests
					}
				]
			}; 

		ctx = jQuery("#wcds_referrals_stats").get(0).getContext("2d");
	var myLineChart = new Chart(ctx,{type:'bar', data: data});
		wcds_render_referrals_table();
	}
	//Table
	function wcds_render_referrals_table()
	{
		jQuery('#wcds_referrals_table_body').empty();
		var total_spent = total_count = 0;
		var user_link;
		var amount_to_print;
		var value_to_print = '';
		jQuery.each(wcds_referrals_data,function(index, expert)
		{
			if(expert.link != 'none')
				user_link = '<a target="_blank" href="'+expert.link+'">'+expert.name+'</a>';
			
			value_to_print= parseFloat(expert.total_service_requests).toFixed(wcds_decimals);
			amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_referrals_currency+value_to_print : value_to_print+wcds_referrals_currency;

			jQuery('#wcds_referrals_table_body').append("<tr>"+
															"<td>"+user_link+"</td>"+
															"<td>"+expert.number_sr+"</td>"+
															"<td>"+amount_to_print+"</td>"+
														"</tr>");
			total_spent += parseFloat(expert.total_service_requests);
			total_count += parseInt(expert.number_sr);
		});
		amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ? wcds_referrals_currency+parseFloat(total_spent).toFixed(wcds_decimals) : parseFloat(total_spent).toFixed(wcds_decimals)+wcds_referrals_currency;

		jQuery('#wcds_referrals_foot_number_sr').html(parseInt(total_count));
		jQuery('#wcds_referrals_foot_total_sr').html(amount_to_print);
		
	}
	//Misc
	function wcds_referrals_clear_table()
	{
		jQuery('#wcds_referrals_table_body').empty();
	}
	function wcds_referrals_set_range_date_selectors()
	{
		//if(selector_type == 'daily')
		{
			wcds_referrals_widget_picker_start_date = jQuery( "#wcds_referrals_picker_start_date" ).pickadate({selectMonths: true, selectYears: true, trueformatSubmit: 'yyyy-mm-dd', format: 'yyyy/mm/dd'});
			wcds_referrals_widget_picker_end_date = jQuery( "#wcds_referrals_picker_end_date" ).pickadate({selectMonths: true, selectYears: true, formatSubmit: 'yyyy-mm-dd', format: 'yyyy/mm/dd'});
		}
	}

	function wcds_referrals_widget_are_date_good()
	{
		var picker_start_date = wcds_referrals_widget_picker_start_date.pickadate('picker');
		var picker_end_date = wcds_referrals_widget_picker_end_date.pickadate('picker'); 
		
		//if(wcds_referrals_widget_date_range_type == 'daily')
		{
			wcds_referrals_widget_start_date = picker_start_date.get('select', 'yyyy-mm-dd'); 
			wcds_referrals_widget_end_date = picker_end_date.get('select', 'yyyy-mm-dd');
		}
		if( /* wcds_referrals_widget_start_date=='' || wcds_referrals_widget_start_date=='' || */ wcds_referrals_widget_start_date > wcds_referrals_widget_end_date)
			return false;
		
		return true;
	}
	function wcds_reset_referrals_widget_canvas()
	{
		jQuery('#wcds_referrals_stats_canvas_box').empty();
		jQuery('#wcds_referrals_stats_canvas_box').append('<canvas id="wcds_referrals_stats" ></canvas>');
	}

});