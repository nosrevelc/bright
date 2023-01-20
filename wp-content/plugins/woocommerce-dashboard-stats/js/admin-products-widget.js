jQuery(document).ready(function()
{
	var wcds_products_widget_picker_start_date;
	var wcds_products_widget_picker_end_date;
	var wcds_products_widget_start_date;
	var wcds_products_widget_end_date;
	var wcds_products_chart_labels;
	var wcds_products_total_earnings;
	var wcds_products_count_per_date;
	var wcds_products_chart_data;
	var wcds_products_data;
	jQuery('#wcds_products_chart_filter_button').click(wcds_start_reloading_products_widget_data);
	wcds_products_set_range_date_selectors();
	jQuery('#wcds_products_chart_filter_button').trigger('click');
	
//Chart 
function wcds_start_reloading_products_widget_data(event)
{
	event.stopImmediatePropagation();
	event.preventDefault();
	
	if(!wcds_products_widget_are_date_good())
		alert(wcds_products_widget_date_error);
	else
	{
		jQuery('#wcds_products_stats_table').fadeOut(500);
		jQuery('#wcds_products_stats').fadeOut(500);		
		jQuery('#wcds_products_wait_box').delay(600).fadeIn(300, function(){ wcds_reset_products_widget_canvas(); wcds_load_new_products_widget_data()});
	}
}
function wcds_load_new_products_widget_data()
{
	/* console.log(wcds_products_widget_start_date);
	console.log(wcds_products_widget_end_date); */
	var formData = new FormData();
	formData.append('action', 'wcds_products_widget_get_products_per_period');
	formData.append('start_date', wcds_products_widget_start_date);
	formData.append('end_date', wcds_products_widget_end_date);
	formData.append('product_num', jQuery('#wcds_products_num').val());
	formData.append('show_variations', jQuery('#wcds_show_variations').val());
	
	jQuery.ajax({
		url: ajaxurl, 
		type: 'POST',
		data: formData,
		async: true,
		success: function (data) 
		{
			wcds_refresh_products_widget(data);
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
function wcds_refresh_products_widget(data)
{
	
	wcds_products_data = jQuery.parseJSON(data); //.dates ; .totals ; .order_num
	/* console.log(wcds_products_data);
	console.log(result); */
	/* wcds_products_chart_labels = result.dates.split(",");
	wcds_products_total_earnings = result.totals.split(",");
	wcds_products_count_per_date = result.order_num.split(","); */
	
	wcds_products_clear_table();
	jQuery('#wcds_products_wait_box').fadeOut(300);
	jQuery('#wcds_products_stats').delay(400).fadeIn(500);
	jQuery('#wcds_products_stats_table').delay(400).fadeIn(500,wcds_create_products_chart);
	//wcds_create_products_chart(); 
}
function wcds_create_products_chart()
{
	/* wcds_products_chart_data = new Array();
	jQuery.each(wcds_products_data,function(index, product)
	{
		//console.log(product);
		if(product != null)
		{
			product.total_earning = product.total_earning != null ? product.total_earning : 0;
			wcds_products_chart_data.push(
			{
				value: parseFloat(product.total_earning),
				color: wcds_products_getRandomColor(),
				highlight: "#14307b",
				label: product.prod_title
			});
		}
	}); */
	
	//new
	wcds_products_chart_data = {datasets:[{data:[], backgroundColor:[]}], labels:[]};
	jQuery.each(wcds_products_data,function(index, product)
	{
		wcds_products_chart_data.datasets[0].data.push(parseFloat(product.total_earning));
		wcds_products_chart_data.datasets[0].backgroundColor.push(wcds_products_getRandomColor());
		wcds_products_chart_data.labels.push(product.prod_title);
	});
	var char_options =  {
		legend: {
			display: false
		}
	};
		
	ctx = jQuery("#wcds_products_stats").get(0).getContext("2d");
	//var myLineChart = new Chart(ctx).Line(wcds_line_chart_data, {responsive : true, pointHitDetectionRadius:5}); //default 20
	//var myPolarAreaChart = new Chart(ctx).PolarArea(wcds_products_chart_data, {responsive : true}); 
	var myPolarAreaChart = new Chart(ctx,{type:'pie', data: wcds_products_chart_data, options: char_options});
	wcds_render_products_table();
}
//Table
function wcds_render_products_table()
{
	jQuery('#wcds_products_table_body').empty();
	var total_earnings = total_count = stock_left = 0;
	var amount_to_print; 
	var value_to_print; 
	jQuery.each(wcds_products_data,function(index, product)
	{
		if(product != null)
		{
			//console.log(isNaN(product.stock_left));
			value_to_print = parseFloat(product.total_earning).toFixed(wcds_decimals);
			product.stock_left =  Number.isNaN(product.stock_left) ? parseInt(product.stock_left) : product.stock_left;
			amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_products_currency+value_to_print : value_to_print+wcds_products_currency;
	
			jQuery('#wcds_products_table_body').append("<tr>"+
															"<td><a target='_blank' href='"+product.permalink+"'>"+product.prod_title+"</a></td>"+
															"<td>"+product.total_purchases+"</td>"+
															"<td>"+product.stock_left+"</td>"+
															"<td>"+amount_to_print+"</td>"+
														"</tr>");
			if(product.total_earning != null)
				total_earnings += parseFloat(product.total_earning);
			if(product.total_purchases != null)
				total_count += parseInt(product.total_purchases);
			if(product.stock_left != null && Number.isInteger(product.stock_left) )
				stock_left += product.stock_left;
		}
	});
	amount_to_print = wcds_currency_pos == 'left' || wcds_currency_pos == 'left_space' ?  wcds_products_currency+parseFloat(total_earnings).toFixed(wcds_decimals) : parseFloat(total_earnings).toFixed(wcds_decimals)+wcds_products_currency;

	jQuery('#wcds_products_foot_total_count').html(parseInt(total_count));
	jQuery('#wcds_products_foot_stock_left').html(parseInt(stock_left));
	jQuery('#wcds_products_foot_total_earnings').html(amount_to_print);
	
}
//Misc
function wcds_products_clear_table()
{
	jQuery('#wcds_products_table_body').empty();
}
function wcds_products_set_range_date_selectors()
{
	//if(selector_type == 'daily')
	{
		wcds_products_widget_picker_start_date = jQuery( "#wcds_products_picker_start_date" ).pickadate({selectMonths: true, selectYears: true, trueformatSubmit: 'yyyy-mm-dd', format: 'yyyy/mm/dd'});
		wcds_products_widget_picker_end_date = jQuery( "#wcds_products_picker_end_date" ).pickadate({selectMonths: true, selectYears: true, formatSubmit: 'yyyy-mm-dd', format: 'yyyy/mm/dd'});
	}
}

function wcds_products_widget_are_date_good()
{
	var picker_start_date = wcds_products_widget_picker_start_date.pickadate('picker');
	var picker_end_date = wcds_products_widget_picker_end_date.pickadate('picker'); 
	
	//if(wcds_products_widget_date_range_type == 'daily')
	{
		wcds_products_widget_start_date = picker_start_date.get('select', 'yyyy-mm-dd'); 
		wcds_products_widget_end_date = picker_end_date.get('select', 'yyyy-mm-dd');
	}
	if( /* wcds_products_widget_start_date=='' || wcds_products_widget_start_date=='' || */ wcds_products_widget_start_date > wcds_products_widget_end_date)
		return false;
	
	return true;
}
function wcds_reset_products_widget_canvas()
{
	jQuery('#wcds_products_stats_canvas_box').empty();
	jQuery('#wcds_products_stats_canvas_box').append('<canvas id="wcds_products_stats" ></canvas>');
}
function wcds_products_getRandomColor() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}
});