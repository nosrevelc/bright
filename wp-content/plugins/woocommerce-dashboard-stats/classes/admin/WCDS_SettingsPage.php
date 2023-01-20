<?php 
class WCDS_SettingsPage
{
	public function __construct()
	{
	}
	public function render_page()
	{
		 global $wcps_option_model,$wp_roles;
		
		if(isset($_POST['wcds_general_options']))
			$wcps_option_model->save_general_options($_POST['wcds_general_options']); 
		
		//settings
		$order_statuses_to_consider_for_stats_computation = $wcps_option_model->get_order_statuses_to_consider_for_stats_computation_for_settings_page();
		$user_role_widget_visibility = $wcps_option_model->get_user_roles_to_which_widgets_are_visible();
		$default_settings = $wcps_option_model->get_default_widget_values();
		$default_time_period_settings = $wcps_option_model->get_default_time_period_widget_values();
		
		wp_enqueue_style( 'wcds-admin', WCDS_PLUGIN_URL.'/css/admin-settings.css');		
		$order_statuses = wc_get_order_statuses();
		//wcds_var_dump($wp_roles->roles);
		?>
		
		<div class="wrap white-box">
			
			<?php //screen_icon("options-general"); ?>
			<!--<h2><?php _e('General options', 'woocommerce-dashboard-stats');?></h2>-->
			<form action="" method="post" > <!--options.php -->
			<?php settings_fields('wcds_options_group'); ?> 
				
				<h3 class="wcds_title_small_margin"><?php _e('Order statuses', 'woocommerce-dashboard-stats');?></h3>
				<label><?php _e('Select which order status have to be considered for stats computation. Select at least one status', 'woocommerce-dashboard-stats'); ?></label>
				<?php if(!empty($_POST) && !isset($_POST['wcds_general_options']['order_statuses_to_consider_for_stats_computation'])): ?>
					<p class="wcds_error"><?php _e('Select at least one status!', 'woocommerce-dashboard-stats');?></p>
				<?php endif; ?>
				<?php foreach($order_statuses as $order_status => $order_status_name):
						//$order_status = str_replace("wc-", "", $order_status); ?>
						<input class="" type="checkbox" name="wcds_general_options[order_statuses_to_consider_for_stats_computation][<?php echo $order_status; ?>]" value="true" <?php  if(isset($order_statuses_to_consider_for_stats_computation[$order_status])) echo 'checked="checked"';?> ><?php echo $order_status_name; ?></input>
				<?php endforeach; ?>
				
				<h3 class=""><?php _e('Which role can see dashoboard widgets', 'woocommerce-dashboard-stats');?></h3>
				<?php if(!empty($_POST) && !isset($_POST['wcds_general_options']['user_roles_to_which_widgets_are_visible'])): ?>
					<p class="wcds_error"><?php _e('Select at least one role!', 'woocommerce-dashboard-stats');?></p>
				<?php endif; ?>
				<?php foreach($wp_roles->roles as $role_code => $role_data): ?>
						<input class="" type="checkbox" name="wcds_general_options[user_roles_to_which_widgets_are_visible][<?php echo $role_code; ?>]" value="true" <?php  if(isset($user_role_widget_visibility[$role_code])) echo 'checked="checked"';?> ><?php echo $role_data['name']; ?></input>
				<?php endforeach; ?>
				
				<h3 class=""><?php _e('Price format: number of decimal', 'woocommerce-dashboard-stats');?></h3>
				<label><?php _e('If left empyt, will be used the "Number of decimals" option setted in the WooCommerce -> Settings -> General tab. Values will be rounded (not truncated) so this may lead to some representation errors.', 'woocommerce-dashboard-stats'); ?></label>
				<input type="number" step="1" min="0" name="wcds_general_options[number_of_decimals]" value="<?php echo $default_settings['number_of_decimals']; ?>"></input>
				
				<div id="wcds_default_values_settings_container">
					<h3 class=""><?php _e('Default widget values', 'woocommerce-dashboard-stats');?></h3>
					<label><?php _e('Select default values used by widget to display results.', 'woocommerce-dashboard-stats'); ?></label>
					
					<div class="wcds_select_option_container">
						<strong class="wcds_description wcds_block"><?php _e('Geographical stats', 'woocommerce-dashboard-stats'); ?></strong>
						<div class="wcds_select_option_sub_container">
							<span class="wcds_option_label"><?php _e('Max results', 'woocommerce-dashboard-stats'); ?></span>
							<select id="wcds_max_results_num" name="wcds_general_options[widget_default_value_geo_max_results]">
								<option value="10" <?php if($default_settings['widget_default_value_geo_max_results'] === '10') echo 'selected="selected"' ?>>10</value>
								<option value="20" <?php if($default_settings['widget_default_value_geo_max_results'] === '20') echo 'selected="selected"' ?>>20</value>
								<option value="30" <?php if($default_settings['widget_default_value_geo_max_results'] === '30') echo 'selected="selected"' ?>>30</value>
								<option value="40" <?php if($default_settings['widget_default_value_geo_max_results'] === '40') echo 'selected="selected"' ?>>40</value>
								<option value="50" <?php if($default_settings['widget_default_value_geo_max_results'] === '50') echo 'selected="selected"' ?>>50</value>
							</select>
						</div>
						
						<div class="wcds_select_option_sub_container">
							<span class="wcds_option_label"><?php _e('View type', 'woocommerce-dashboard-stats'); ?></span>
							<select id="wcds_geographic_view_type" name="wcds_general_options[widget_default_value_geo_view_type]">
							  <option value="country" <?php if($default_settings['widget_default_value_geo_view_type'] === 'country') echo 'selected="selected"' ?>><?php _e('Country', 'woocommerce-dashboard-stats' ); ?></option>
							  <option value="state" <?php if($default_settings['widget_default_value_geo_view_type'] === 'state') echo 'selected="selected"' ?>><?php _e('State/Province', 'woocommerce-dashboard-stats' ); ?></option>
							</select>
						</div>
					</div>
					<div class="wcds_select_option_container">
						<strong class="wcds_description wcds_block"><?php _e('Products stats', 'woocommerce-dashboard-stats'); ?></strong>
						<div class="wcds_select_option_sub_container">
							<span class="wcds_option_label"><?php _e('Max results', 'woocommerce-dashboard-stats'); ?></span>
							<select name ="wcds_general_options[widget_default_value_products_stats]" class="wcds_select_option">
								<option value="10" <?php if($default_settings['widget_default_value_products_stats'] === '10') echo 'selected="selected"' ?>>10</value>
								<option value="20" <?php if($default_settings['widget_default_value_products_stats'] === '20') echo 'selected="selected"' ?>>20</value>
								<option value="30" <?php if($default_settings['widget_default_value_products_stats'] === '30') echo 'selected="selected"' ?>>30</value>
								<option value="40" <?php if($default_settings['widget_default_value_products_stats'] === '40') echo 'selected="selected"' ?>>40</value>
								<option value="50" <?php if($default_settings['widget_default_value_products_stats'] === '50') echo 'selected="selected"' ?>>50</value>
							</select>
						</div>
						
						<div class="wcds_select_option_sub_container">
							<span class="wcds_option_label"><?php _e('Show variations', 'woocommerce-dashboard-stats'); ?></span>
							<select id="wcds_geographic_view_type" name="wcds_general_options[widget_default_value_products_stats_show_variations]">
							  <option value="no" <?php if($default_settings['widget_default_value_products_stats_show_variations'] === 'no') echo 'selected="selected"' ?>><?php _e('No', 'woocommerce-dashboard-stats' ); ?></option>
							  <option value="yes" <?php if($default_settings['widget_default_value_products_stats_show_variations'] === 'yes') echo 'selected="selected"' ?>><?php _e('Yes', 'woocommerce-dashboard-stats' ); ?></option>
							</select>
						</div>
					</div>
					<div class="wcds_select_option_container">
						<strong class="wcds_description"><?php _e('Customers stats', 'woocommerce-dashboard-stats'); ?></strong>
						<span class="wcds_option_label"><?php _e('Max results', 'woocommerce-dashboard-stats'); ?></span>
						<select name ="wcds_general_options[widget_default_value_customers_stats]" class="wcds_select_option">
							<option value="10" <?php if($default_settings['widget_default_value_customers_stats'] === '10') echo 'selected="selected"' ?>>10</value>
							<option value="20" <?php if($default_settings['widget_default_value_customers_stats'] === '20') echo 'selected="selected"' ?>>20</value>
							<option value="30" <?php if($default_settings['widget_default_value_customers_stats'] === '30') echo 'selected="selected"' ?>>30</value>
							<option value="40" <?php if($default_settings['widget_default_value_customers_stats'] === '40') echo 'selected="selected"' ?>>40</value>
							<option value="50" <?php if($default_settings['widget_default_value_customers_stats'] === '50') echo 'selected="selected"' ?>>50</value>
						</select>
					</div>
					<div class="wcds_select_option_container">
						<strong class="wcds_description"><?php _e('Sales stats', 'woocommerce-dashboard-stats'); ?></strong>
						<span class="wcds_option_label"><?php _e('View type', 'woocommerce-dashboard-stats'); ?></span>
						<select name ="wcds_general_options[widget_default_value_sales_stats]" class="wcds_select_option">
							<option value="daily" <?php if($default_settings['widget_default_value_sales_stats'] === 'daily') echo 'selected="selected"' ?>><?php _e('Daily View', 'woocommerce-dashboard-stats' ); ?></option>
							<option value="monthly" <?php if($default_settings['widget_default_value_sales_stats'] === 'monthly') echo 'selected="selected"' ?>><?php _e('Monthly View', 'woocommerce-dashboard-stats' ); ?></option>
							<option value="yearly" <?php if($default_settings['widget_default_value_sales_stats'] === 'yearly') echo 'selected="selected"' ?>><?php _e('Yearly View', 'woocommerce-dashboard-stats' ); ?></option>
						</select>
					</div>
					<div class="wcds_select_option_container">
						<strong class="wcds_description"><?php _e('Payment stats', 'woocommerce-dashboard-stats'); ?></strong>
						<span class="wcds_option_label"><?php _e('Max results', 'woocommerce-dashboard-stats'); ?></span>
						<select name ="wcds_general_options[widget_default_value_payment_methods]" class="wcds_select_option">
							<option value="10" <?php if($default_settings['widget_default_value_payment_methods'] === '10') echo 'selected="selected"' ?>>10</value>
							<option value="20" <?php if($default_settings['widget_default_value_payment_methods'] === '20') echo 'selected="selected"' ?>>20</value>
							<option value="30" <?php if($default_settings['widget_default_value_payment_methods'] === '30') echo 'selected="selected"' ?>>30</value>
							<option value="40" <?php if($default_settings['widget_default_value_payment_methods'] === '40') echo 'selected="selected"' ?>>40</value>
							<option value="50" <?php if($default_settings['widget_default_value_payment_methods'] === '50') echo 'selected="selected"' ?>>50</value>
						</select>
					</div>
					
					<div class="wcds_select_option_container">
						<strong class="wcds_description"><?php _e('Refund stats', 'woocommerce-dashboard-stats'); ?></strong>
						<span class="wcds_option_label"><?php _e('View type', 'woocommerce-dashboard-stats'); ?></span>
						<select name ="wcds_general_options[widget_default_value_refund_stats]" class="wcds_select_option">
							<option value="daily" <?php if($default_settings['widget_default_value_refund_stats'] === 'daily') echo 'selected="selected"' ?>><?php _e('Daily View', 'woocommerce-dashboard-stats' ); ?></option>
							<option value="monthly" <?php if($default_settings['widget_default_value_refund_stats'] === 'monthly') echo 'selected="selected"' ?>><?php _e('Monthly View', 'woocommerce-dashboard-stats' ); ?></option>
							<option value="yearly" <?php if($default_settings['widget_default_value_refund_stats'] === 'yearly') echo 'selected="selected"' ?>><?php _e('Yearly View', 'woocommerce-dashboard-stats' ); ?></option>
						</select>
					</div>
					
					
				</div>
				
				<div class="wcds_generic_options_container">
					<h3 class=""><?php _e('Default widget data period', 'woocommerce-dashboard-stats');?></h3>
					<label><?php _e('By default, if any time period is selected, widgets will display stats for current month. Optionally you can change the default period.', 'woocommerce-dashboard-stats'); ?></label>
					<div class="wcds_select_option_container">
							<strong class="wcds_description wcds_block"><?php _e('Geographical stats', 'woocommerce-dashboard-stats'); ?></strong>
							<div class="wcds_select_option_sub_container">
								<span class="wcds_option_label"><?php _e('Time period', 'woocommerce-dashboard-stats'); ?></span>
								<select name ="wcds_general_options[widget_default_time_period_geo_stats]" class="wcds_select_option">
									<option value="day" <?php if($default_time_period_settings['widget_default_time_period_geo_stats'] === 'day') echo 'selected="selected"' ?>><?php _e('Day', 'woocommerce-dashboard-stats'); ?></value>
									<option value="month" <?php if($default_time_period_settings['widget_default_time_period_geo_stats'] === 'month') echo 'selected="selected"' ?>><?php _e('Month', 'woocommerce-dashboard-stats'); ?></value>
									<option value="year" <?php if($default_time_period_settings['widget_default_time_period_geo_stats'] === 'year') echo 'selected="selected"' ?>><?php _e('Year', 'woocommerce-dashboard-stats'); ?></value>
								</select>
							</div>
					</div>
					<div class="wcds_select_option_container">
							<strong class="wcds_description wcds_block"><?php _e('Products stats', 'woocommerce-dashboard-stats'); ?></strong>
							<div class="wcds_select_option_sub_container">
								<span class="wcds_option_label"><?php _e('Time period', 'woocommerce-dashboard-stats'); ?></span>
								<select name ="wcds_general_options[widget_default_time_period_products_stats]" class="wcds_select_option">
									<option value="day" <?php if($default_time_period_settings['widget_default_time_period_products_stats'] === 'day') echo 'selected="selected"' ?>><?php _e('Day', 'woocommerce-dashboard-stats'); ?></value>
									<option value="month" <?php if($default_time_period_settings['widget_default_time_period_products_stats'] === 'month') echo 'selected="selected"' ?>><?php _e('Month', 'woocommerce-dashboard-stats'); ?></value>
									<option value="year" <?php if($default_time_period_settings['widget_default_time_period_products_stats'] === 'year') echo 'selected="selected"' ?>><?php _e('Year', 'woocommerce-dashboard-stats'); ?></value>
								</select>
							</div>
					</div>
					<div class="wcds_select_option_container">
							<strong class="wcds_description wcds_block"><?php _e('Customers stats', 'woocommerce-dashboard-stats'); ?></strong>
							<div class="wcds_select_option_sub_container">
								<span class="wcds_option_label"><?php _e('Time period', 'woocommerce-dashboard-stats'); ?></span>
								<select name ="wcds_general_options[widget_default_time_period_customers_stats]" class="wcds_select_option">
									<option value="day" <?php if($default_time_period_settings['widget_default_time_period_customers_stats'] === 'day') echo 'selected="selected"' ?>><?php _e('Day', 'woocommerce-dashboard-stats'); ?></value>
									<option value="month" <?php if($default_time_period_settings['widget_default_time_period_customers_stats'] === 'month') echo 'selected="selected"' ?>><?php _e('Month', 'woocommerce-dashboard-stats'); ?></value>
									<option value="year" <?php if($default_time_period_settings['widget_default_time_period_customers_stats'] === 'year') echo 'selected="selected"' ?>><?php _e('Year', 'woocommerce-dashboard-stats'); ?></value>
								</select>
							</div>
					</div>
					<div class="wcds_select_option_container">
							<strong class="wcds_description wcds_block"><?php _e('Sales stats', 'woocommerce-dashboard-stats'); ?></strong>
							<div class="wcds_select_option_sub_container">
								<span class="wcds_option_label"><?php _e('Time period', 'woocommerce-dashboard-stats'); ?></span>
								<select name ="wcds_general_options[widget_default_time_period_sales_stats]" class="wcds_select_option">
									<option value="day" <?php if($default_time_period_settings['widget_default_time_period_sales_stats'] === 'day') echo 'selected="selected"' ?>><?php _e('Day', 'woocommerce-dashboard-stats'); ?></value>
									<option value="month" <?php if($default_time_period_settings['widget_default_time_period_sales_stats'] === 'month') echo 'selected="selected"' ?>><?php _e('Month', 'woocommerce-dashboard-stats'); ?></value>
									<option value="year" <?php if($default_time_period_settings['widget_default_time_period_sales_stats'] === 'year') echo 'selected="selected"' ?>><?php _e('Year', 'woocommerce-dashboard-stats'); ?></value>
								</select>
							</div>
					</div>
					<div class="wcds_select_option_container">
							<strong class="wcds_description wcds_block"><?php _e('Payment methods stats', 'woocommerce-dashboard-stats'); ?></strong>
							<div class="wcds_select_option_sub_container">
								<span class="wcds_option_label"><?php _e('Time period', 'woocommerce-dashboard-stats'); ?></span>
								<select name ="wcds_general_options[widget_default_time_period_payment_methods_stats]" class="wcds_select_option">
									<option value="day" <?php if($default_time_period_settings['widget_default_time_period_payment_methods_stats'] === 'day') echo 'selected="selected"' ?>><?php _e('Day', 'woocommerce-dashboard-stats'); ?></value>
									<option value="month" <?php if($default_time_period_settings['widget_default_time_period_payment_methods_stats'] === 'month') echo 'selected="selected"' ?>><?php _e('Month', 'woocommerce-dashboard-stats'); ?></value>
									<option value="year" <?php if($default_time_period_settings['widget_default_time_period_payment_methods_stats'] === 'year') echo 'selected="selected"' ?>><?php _e('Year', 'woocommerce-dashboard-stats'); ?></value>
								</select>
							</div>
					</div>
					<div class="wcds_select_option_container">
							<strong class="wcds_description wcds_block"><?php _e('Refund stats', 'woocommerce-dashboard-stats'); ?></strong>
							<div class="wcds_select_option_sub_container">
								<span class="wcds_option_label"><?php _e('Time period', 'woocommerce-dashboard-stats'); ?></span>
								<select name ="wcds_general_options[widget_default_time_period_refund_stats]" class="wcds_select_option">
									<option value="day" <?php if($default_time_period_settings['widget_default_time_period_refund_stats'] === 'day') echo 'selected="selected"' ?>><?php _e('Day', 'woocommerce-dashboard-stats'); ?></value>
									<option value="month" <?php if($default_time_period_settings['widget_default_time_period_refund_stats'] === 'month') echo 'selected="selected"' ?>><?php _e('Month', 'woocommerce-dashboard-stats'); ?></value>
									<option value="year" <?php if($default_time_period_settings['widget_default_time_period_refund_stats'] === 'year') echo 'selected="selected"' ?>><?php _e('Year', 'woocommerce-dashboard-stats'); ?></value>
								</select>
							</div>
					</div>
				</div>
				<p class="submit" id="wcds_subit">
					<input  name="Submit" type="submit" class="button-primary wcds_button" id="wcds_save_settings_button" value="<?php esc_attr_e('Save Changes', 'woocommerce-dashboard-stats'); ?>" />
				</p>
			</fom>
		</div>
		<?php
	}
}
?>