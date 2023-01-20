jQuery(document).ready(function()
{
	var wcds_categories_widget_picker_start_date;
	var wcds_categories_widget_picker_end_date;
	var wcds_categories_widget_start_date;
	var wcds_categories_widget_end_date;
	var wcds_categories_chart_labels;
	var wcds_categories_total_spent;
	var wcds_categories_count_per_date;
	var wcds_categories_chart_data;
	var wcds_categories_data;
	var wcds_categories_colors;
	jQuery('#wcds_categories_chart_filter_button').click(wcds_start_reloading_categories_widget_data);
	wcds_categories_set_range_date_selectors();
	wcds_start_reloading_categories_widget_data(null)
	
	//Chart 
	function wcds_start_reloading_categories_widget_data(event)
	{
		if(event != null)
		{
			event.stopImmediatePropagation();
			event.preventDefault();
		}
		if(!wcds_categories_widget_are_date_good())
			alert(wcds_categories_widget_date_error);
		else
		{
			jQuery('#wcds_categories_stats_table').fadeOut(500);
			jQuery('#wcds_categories_stats').fadeOut(500);		
			jQuery('#wcds_categories_wait_box').delay(600).fadeIn(300, function(){ wcds_reset_categories_widget_canvas(); wcds_load_new_categories_widget_data()});
		}
	}
	function wcds_load_new_categories_widget_data()
	{
		console.log(wcds_categories_widget_start_date);
		console.log(wcds_categories_widget_end_date);

		var formData = new FormData();
		formData.append('action', 'wcds_categories_widget_get_categories_per_period');
		formData.append('start_date', wcds_categories_widget_start_date);
		formData.append('end_date', wcds_categories_widget_end_date);
		formData.append('categories_num', jQuery('#wcds_categories_num').val());
		//console.log(formData);
		jQuery.ajax({
			url: ajaxurl, 
			type: 'POST',
			data: formData,
			async: true,
			success: function (data) 
			{
				wcds_refresh_categories_widget(data);
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
	function wcds_refresh_categories_widget(data)
	{
		
		wcds_categories_data = jQuery.parseJSON(data); 
		//.total_spent ; .order_num ; .name; .last_name; .customer_email; .customer_id
		//console.log(wcds_categories_data);
		//console.log(data);
		
		wcds_categories_clear_table();
		jQuery('#wcds_categories_wait_box').fadeOut(300);
		jQuery('#wcds_categories_stats').delay(400).fadeIn(500);
		jQuery('#wcds_categories_stats_table').delay(400).fadeIn(500,wcds_create_categories_chart);
		//wcds_create_categories_chart(); 
	}
	function wcds_create_categories_chart()
	{
		wcds_categories_chart_data = {datasets:[{data:[], backgroundColor:[]}], labels:[]};
	jQuery.each(wcds_categories_data,function(index, category)
	{
		console.log(category.name);
		wcds_categories_chart_data.datasets[0].data.push(parseFloat(category.amount));
		wcds_categories_chart_data.datasets[0].backgroundColor.push(wcds_categories_getRandomColor());
		wcds_categories_chart_data.labels.push(category.name);
	});
	var char_options =  {
		legend: {
			display: false
		}
	};
		
	ctx = jQuery("#wcds_categories_stats").get(0).getContext("2d");
	var myPolarAreaChart = new Chart(ctx,{type:'pie', data: wcds_categories_chart_data, options: char_options});
	wcds_render_categories_table();
	}
	//Table
	function wcds_render_categories_table()
	{
		jQuery('#wcds_categories_table_body').empty();
		var total_spent = total_count = 0;
		var amount_to_print;
		var amount = '';

		var amount_to_print_per_sr;
		var amount_per_sr = '';

		var amount_to_print_idb_royalties;
		var amount_idb_royalties = '';

		var percentage_idb_royalties=0;

		jQuery.each(wcds_categories_data,function(index, category)
		{
			
			amount= parseFloat(category.amount).toFixed(wcds_decimals);
			amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_categories_currency+amount : amount+wcds_categories_currency;

			amount_per_sr= parseFloat(category.avg).toFixed(wcds_decimals);
			amount_to_print_per_sr = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_categories_currency+amount_per_sr : amount_per_sr+wcds_categories_currency;

			amount_idb_royalties= parseFloat(category.total_royalties_idealbiz).toFixed(wcds_decimals);
			amount_to_print_idb_royalties = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_categories_currency+amount_idb_royalties : amount_idb_royalties+wcds_categories_currency;

			if(category.percentage_idealbiz){
				percentage_idb_royalties= parseFloat(category.percentage_idealbiz).toFixed(wcds_decimals);
			}else{
				percentage_idb_royalties = 0;
			}
			jQuery('#wcds_categories_table_body').append("<tr>"+
															"<td>"+category.name+"</td>"+
															"<td>"+category.number_sr+"</td>"+
															"<td>"+amount_to_print+"</td>"+
															"<td>"+amount_to_print_per_sr+"</td>"+
															"<td>"+amount_to_print_idb_royalties+"</td>"+
															"<td>"+percentage_idb_royalties+"%</td>"+
														"</tr>");
			total_spent += parseFloat(category.amount);
			total_count += parseInt(category.number_sr);
		});
		amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ? wcds_categories_currency+parseFloat(total_spent).toFixed(wcds_decimals) : parseFloat(total_spent).toFixed(wcds_decimals)+wcds_categories_currency;

		jQuery('#wcds_categories_foot_number_sr').html(parseInt(total_count));
		jQuery('#wcds_categories_foot_total_sr').html(amount_to_print);
		
	}
	//Misc
	function wcds_categories_clear_table()
	{
		jQuery('#wcds_categories_table_body').empty();
	}
	function wcds_categories_set_range_date_selectors()
	{
		//if(selector_type == 'daily')
		{
			wcds_categories_widget_picker_start_date = jQuery( "#wcds_categories_picker_start_date" ).pickadate({selectMonths: true, selectYears: true, trueformatSubmit: 'yyyy-mm-dd', format: 'yyyy/mm/dd'});
			wcds_categories_widget_picker_end_date = jQuery( "#wcds_categories_picker_end_date" ).pickadate({selectMonths: true, selectYears: true, formatSubmit: 'yyyy-mm-dd', format: 'yyyy/mm/dd'});
		}
	}

	function wcds_categories_widget_are_date_good()
	{
		var picker_start_date = wcds_categories_widget_picker_start_date.pickadate('picker');
		var picker_end_date = wcds_categories_widget_picker_end_date.pickadate('picker'); 
		
		//if(wcds_categories_widget_date_range_type == 'daily')
		{
			wcds_categories_widget_start_date = picker_start_date.get('select', 'yyyy-mm-dd'); 
			wcds_categories_widget_end_date = picker_end_date.get('select', 'yyyy-mm-dd');
		}
		if( /* wcds_categories_widget_start_date=='' || wcds_categories_widget_start_date=='' || */ wcds_categories_widget_start_date > wcds_categories_widget_end_date)
			return false;
		
		return true;
	}
	function wcds_reset_categories_widget_canvas()
	{
		jQuery('#wcds_categories_stats_canvas_box').empty();
		jQuery('#wcds_categories_stats_canvas_box').append('<canvas id="wcds_categories_stats" ></canvas>');
	}
	function wcds_categories_getRandomColor() {
		var letters = '0123456789ABCDEF'.split('');
		var color = '#';
		for (var i = 0; i < 6; i++ ) {
			color += letters[Math.floor(Math.random() * 16)];
		}
		return color;
	}

});