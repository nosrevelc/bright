<?php 
class WCDS_Html
{
	var $default_settings;
	var $default_time_periods;
	
	public function __construct()
	{
		global $wcps_option_model;
		if(isset($wcps_option_model))
		{
			$this->default_settings = $wcps_option_model->get_default_widget_values();
			$this->default_time_periods = $wcps_option_model->get_default_time_period_widget_values();
		}
	}
	private function common_css_and_js()
	{
		
		$currency_pos = get_option('woocommerce_currency_pos');
		$currency_pos = $currency_pos ? $currency_pos : 'left';
		?>
		<script>
		var wcds_currency_pos = "<? echo $currency_pos; ?>";
		var wcds_decimals = <?php echo $this->default_settings['number_of_decimals']; ?>;
		</script>
		<?php
		wp_enqueue_style('datepicker-classic', WCDS_PLUGIN_URL.'/css/datepicker/classic.css');   
		wp_enqueue_style('datepicker-date-classic', WCDS_PLUGIN_URL.'/css/datepicker/classic.date.css');   
		wp_enqueue_style('datepicker-time-classic', WCDS_PLUGIN_URL.'/css/datepicker/classic.time.css');   
		wp_enqueue_style('wcds-widget-general', WCDS_PLUGIN_URL.'/css/admin-widget-general.css');
		
		wp_enqueue_script('wcds-simple-pagination', WCDS_PLUGIN_URL.'/js/paging.js', array('jquery'));
		wp_enqueue_script('wcds-ui-chart', WCDS_PLUGIN_URL.'/js/Chart.min.js', array( 'jquery' ));
		//wp_enqueue_script('wcds-ui-chart-stackedbar', WCDS_PLUGIN_URL.'/js/Chart.StackedBar.js', array( 'jquery' ));
		wp_enqueue_script('wcds-ui-picker', WCDS_PLUGIN_URL.'/js/picker.js', array( 'jquery' ));
		wp_enqueue_script('wcds-ui-timepicker', WCDS_PLUGIN_URL.'/js/picker.date.js', array( 'jquery' ));
	}
	public function render_days_comparison_widget() 
	{
		$this->common_css_and_js();
		wp_enqueue_style('wcds-days-comp', WCDS_PLUGIN_URL.'/css/admin-widget-day-comp.css');
		
		//wp_enqueue_script('wcds-ui-chart-stacked', WCDS_PLUGIN_URL.'/js/Chart.StackedBar.js', array( 'jquery' ));
		wp_enqueue_script('wcds-days-comparision-widget', WCDS_PLUGIN_URL.'/js/admin-last-days-comparison.js', array( 'jquery' )); 
		?>
		<script>
		var wcds_days_comp_currency = "<?php echo get_woocommerce_currency_symbol(); ?>";
		var wcds_days_comp_widget_date_error = "<?php _e('Start date cannot be greater than End date.', 'woocommerce-dashboard-stats'); ?>";
		</script>
		
		<div style="display:none">
			<p>
			<?php _e('Last 15 days sales are compared by default with latest 30 days average sales per day. You can choose a different period to compare averages: ', 'woocommerce-dashboard-stats'); ?>
			</p>
			
			<input class="wcds_range_datepicker" type="text" id="wcds_days_comparison_picker_start_date" name="wcds_start_date" value="" placeholder="<?php _e('Start date', 'woocommerce-dashboard-stats' ); ?>" />
			<input class="wcds_range_datepicker" type="text" id="wcds_days_comparison_picker_end_date" name="wcds_end_date" value="" placeholder="<?php _e('End date', 'woocommerce-dashboard-stats' ); ?>" />
			<div class="wcds_spacer"></div>
			<input class="button-primary wcds_filter_button" id="wcds_days_comparison_chart_filter_button" type="submit" value="<?php _e('Filter', 'woocommerce-dashboard-stats' ); ?>" >  </input>
		</div>
		
		<div class="chart">
			<div id="wcds_days_comp_wait_box" class="wcds_wait_box">
					<?php _e('Computing data, please wait...', 'woocommerce-dashboard-stats' ); ?>
					<img class="wcds_preloader_image" src="<?php echo WCDS_PLUGIN_URL.'/img/preloader.gif' ?>" ></img>
			</div>
			<div id="wcds_days_comp_stats_canvas_box">
				<div id="wcds_days_comp_stats_canvas_subox">
					<canvas id="wcds_days_comp_stats_canvas" ></canvas>
				</div>
				<div id="wcds_days_comp_legend">
					<h2><?php _e('Legend', 'woocommerce-dashboard-stats' ); ?></h2>
				   <div id="wcds_days_comp_legend_earnings"></div> <span class="wcds_days_comp_legend_text"><?php _e('Day sales', 'woocommerce-dashboard-stats' ); ?></span>
				   <div id="wcds_days_comp_legend_avarages"></div>  <span class="wcds_days_comp_legend_text"> <?php _e('Average sales', 'woocommerce-dashboard-stats' ); ?></span>
				</div>
			</div>	
			
		</div>
		<?php 
	}
	public function render_avarage_and_estimation_widget()
	{
		global $wcds_order_model;
		
		$this->common_css_and_js();
		wp_enqueue_style('wcds-circular-chart', WCDS_PLUGIN_URL.'/css/jquery.circliful.css');
		wp_enqueue_script('wcds-circular-chart', WCDS_PLUGIN_URL.'/js/jquery.circliful.min.js', array( 'jquery' ));
		
		wp_enqueue_script('wcds-average-estimation-widget', WCDS_PLUGIN_URL.'/js/admin-average-estimation-widget.js', array( 'jquery' ));
		?>
		<script>
		var wcds_today_label = "<?php _e('Today','woocommerce-dashboard-stats'); ?>";
		var wcds_month_label = "<?php _e('This Month','woocommerce-dashboard-stats'); ?>";
		var wcds_year_label = "<?php _e('This Year','woocommerce-dashboard-stats'); ?>";
		var wcds_today_avarage_label = "<?php _e('Average per Day','woocommerce-dashboard-stats'); ?>";
		var wcds_month_avarage_label = "<?php _e('Average per Month','woocommerce-dashboard-stats'); ?>"; 
		var wcds_year_avarage_label = "<?php _e('Average per Year','woocommerce-dashboard-stats'); ?>"; 
		var wcds_estimation_label = "<?php _e('Estimated Sales This Month','woocommerce-dashboard-stats'); ?>"; 
		var wcds_av_and_est_currency = "<?php echo get_woocommerce_currency_symbol(); ?>";
		</script>
		
		<div class="chart">
			<div id="wcds_av_and_est_wait_box" class="wcds_wait_box">
					<?php _e('Computing data, please wait...', 'woocommerce-dashboard-stats' ); ?>
					<img class="wcds_preloader_image" src="<?php echo WCDS_PLUGIN_URL.'/img/preloader.gif' ?>" ></img>
			</div>
			<div id="wcds_av_and_est_stats_canvas_box">
				
			</div>			
		</div>
		<?php 
	}

	public function render_business_details_widget()
	{

		$this->common_css_and_js();
		wp_enqueue_script('wcds-business-details-widget', WCDS_PLUGIN_URL.'/js/admin-business-details-widget.js', array( 'jquery' ));
		?>
		<div class="chart">
			<div id="wcds_business_details_wait_box" class="wcds_wait_box">
					<?php _e('Computing data, please wait...', 'woocommerce-dashboard-stats' ); ?>
					<img class="wcds_preloader_image" src="<?php echo WCDS_PLUGIN_URL.'/img/preloader.gif' ?>" ></img>
			</div>
			<style>
				#wcds_business_details_box{
					display: flex;
    				flex-direction: column;
				}
				.be-number{
					font-size: 33px;
					line-height: 1.2em;
					font-weight: 400;
					display: block;
					color: #21759b;
					margin: 5px;
					margin-bottom: 0;
				}
				.be-desc{
					display: block;
					color: #aaa;
					padding: 9px 12px;
					-webkit-transition: all ease .5s;
					transition: all ease .5s;
					position: relative;
					font-size: 12px;
					margin-top: 0;
				}
				.be-row{
					width: 100%;
					float: left;
					padding: 0;
					box-sizing: border-box;
					margin: 0;
					border-top: 1px solid #ececec;
					color: #aaa;
					text-align: center;
				}
				.gauge {
				font-family: Arial, Helvetica, sans-serif;
				background: #e7e7e7;
				box-shadow: 0 0 0 6px rgba(255, 255, 255, 0.09), 0 0 35px 5px rgba(255, 255, 255, 0.29);
				width: 200px;
				height: 100px;
				border-radius: 100px 100px 0 0;
				position: relative;
				overflow: hidden;
				/* safari fix */
				-webkit-transform-style: flat;
				-webkit-transform: translateZ(0px);
				}
				.gauge.min-scaled {
				transform: scale(0.5);
				}

				.gauge-center {
				content: '';
				color: #fff;
				width: 60%;
				height: 60%;
				background: #15222E;
				border-radius: 100px 100px 0 0;
				position: absolute;
				box-shadow: 0 -13px 15px -10px rgba(0, 0, 0, 0.28);
				right: 21%;
				bottom: 0;
				color: #fff;
				padding: 5px 0 0 0;
				-webkit-font-smoothing: antialiased;
				}

				.needle {
				width: 78px;
				height: 7px;
				background: #15222E;
				border-bottom-left-radius: 100%;
				border-bottom-right-radius: 5px;
				border-top-left-radius: 100%;
				border-top-right-radius: 5px;
				position: absolute;
				bottom: 4px;
				left: 20px;
				transform-origin: 100% 4px;
				transform: rotate(150deg);
				box-shadow: 0 2px 2px 1px rgba(0, 0, 0, 0.38);
				}

				.slice-colors .st {
				position: absolute;
				bottom: 0;
				width: 60px;
				height: 0;
				border-bottom: 80px solid #000;
				border-left: 12px solid transparent;
				border-right: 12px solid transparent;
				}
				.slice-colors .st.slice-item:nth-child(1) {
				border-bottom-color: #E84C3D;
				left: -2px;
				}
				.slice-colors .st.slice-item:nth-child(2) {
				
				border-bottom-color: #e67e22;
				transform: rotate(135deg);
				transform-origin: right;
				top: 12px;
				left: -65px;
				}
				.slice-colors .st.slice-item:nth-child(3) {
				border-bottom-color: #f1c40f;
				transform: rotate(179deg);
				transform-origin: right;
				top: -20px;
				left: -62px;
				border-bottom-width: 90px;
				border-left-width: 45px;
				border-right-width: 45px;
				width: 18px;
				}
				.slice-colors .st.slice-item:nth-child(4) {
					border-bottom-color: #9baa1e;
				transform: rotate(219deg);
				transform-origin: right;
				top: -23px;
				left: 43px;
				}
				.slice-colors .st.slice-item:nth-child(5) {
					border-bottom-color: #1eaa59;
				transform: rotate(240deg);
				transform-origin: right;
				right: 52px;
				}

				#aceptance{
					font-size: 20px;
					top: 22px;
					position: relative;
				}
			</style>
			<div id="wcds_business_details_box" style="display:none;">
				<div class="be-row" style="border-top: 0;">
					<p class="be-number" id="closed_all_time" style="color:#94790B"></p>
					<p class="be-desc"><?php _e('Projects Closed Overall','woocommerce-dashboard-stats'); ?></p>
				</div>
				<div class="be-row" style="">
					<p class="be-number" id="closed_this_year" style="color:#0B8694;"></p>
					<p class="be-desc"><?php _e('Projects Closed This Year','woocommerce-dashboard-stats'); ?></p>
				</div>
				<div class="be-row" style="">
					<p class="be-number" id="in_progress" style="color:#087C1A;"></p>
					<p class="be-desc"><?php _e('Projects In Progress','woocommerce-dashboard-stats'); ?></p>
				</div>
				<div class="be-row" style="">
					<p class="be-number" id="royalties_all_time" style="color:#94790B;"></p>
					<p class="be-desc"><?php _e('Royalties Earned Overall','woocommerce-dashboard-stats'); ?></p>
				</div>
				<div class="be-row" style="">
					<p class="be-number" id="royalties_this_year" style="color:#0B8694;"></p>
					<p class="be-desc"><?php _e('Royalties Earned This Year','woocommerce-dashboard-stats'); ?></p>
				</div>
				<div class="be-row" style="">
					<p class="be-number" id="rejected_all_time" style="color:#950F25;"></p>
					<p class="be-desc"><?php _e('Opportunities Rejected Overall','woocommerce-dashboard-stats'); ?></p>
				</div>
				<div class="be-row" style="">
					<p class="be-number" id="rejected_this_year" style="color:#CD0B2B;"></p>
					<p class="be-desc"><?php _e('Opportunities Rejected This Year','woocommerce-dashboard-stats'); ?></p>
				</div>
				<div class="be-row" style="">
				<div class="wrapper" style="text-align: center;margin: 5px 0;">
				<div class="gauge" style="margin: 0 auto;">
					<div class="slice-colors">
					<div class="st slice-item"></div>
					<div class="st slice-item"></div>
					<div class="st slice-item"></div>
					<div class="st slice-item"></div>
					<div class="st slice-item"></div>
					</div>
					<div class="needle"></div>
					<div class="gauge-center"><span id="aceptance"></span></div>
				</div>
				<p class="be-desc"><?php _e('Service Request Acceptance','woocommerce-dashboard-stats'); ?></p>
				</div>
				</div>
								
				
			</div>			
		</div>
		<?php 
	}




	public function render_geographic_widget()
	{
		global $wcds_order_model;

			$wcds_geographic_view_type = $this->default_settings['widget_default_value_geo_view_type'];

		$wcds_default_time_period = $this->default_time_periods['widget_default_time_period_geo_stats'];
		switch($wcds_default_time_period)
		{
			case 'day' : $current_period_string = __('Day', 'woocommerce-dashboard-stats'); break; //date('F')
			case 'month' : $current_period_string = __('Month', 'woocommerce-dashboard-stats'); break;
			case 'year' : $current_period_string = __('Year', 'woocommerce-dashboard-stats'); break;
	
		}
		$this->common_css_and_js();
		//wp_enqueue_style('wcds-widget-geographic', WCDS_PLUGIN_URL.'/css/admin-widget-geographic.css');  
		wp_enqueue_script('wcds-geographic-widget', WCDS_PLUGIN_URL.'/js/admin-geographic-widget.js', array( 'jquery' ));
		?>
		<script>
		var wcds_flags_path = "<?php echo WCDS_PLUGIN_URL.'/img/flags/'; ?>";
		var wcds_earnings_currency = "<?php echo get_woocommerce_currency_symbol(); ?>";
		var wcds_geographic_currency = "<?php echo get_woocommerce_currency_symbol(); ?>";
		var wcds_geographic_widget_date_error = "<?php _e('Start date cannot be greater than End date.', 'woocommerce-dashboard-stats'); ?>";
		</script>
		<p>
		<?php _e('If a data range is not selected, the displayed stats are relative to the current: ', 'woocommerce-dashboard-stats'); echo '<strong>'.$current_period_string.'</strong>'; ?>
		</p>
		<!-- conf -->
		
		<select id="wcds_max_results_num">
			<option value="10"><?php _e('Max results', 'woocommerce-dashboard-stats' ); ?></value>
			<option value="10" <?php if($this->default_settings['widget_default_value_geo_max_results'] === '10') echo 'selected="selected"' ?>>10</value>
			<option value="20" <?php if($this->default_settings['widget_default_value_geo_max_results'] === '20') echo 'selected="selected"' ?>>20</value>
			<option value="30" <?php if($this->default_settings['widget_default_value_geo_max_results'] === '30') echo 'selected="selected"' ?>>30</value>
			<option value="40" <?php if($this->default_settings['widget_default_value_geo_max_results'] === '40') echo 'selected="selected"' ?>>40</value>
			<option value="50" <?php if($this->default_settings['widget_default_value_geo_max_results'] === '50') echo 'selected="selected"' ?>>50</value>
		</select>
		
		<select id="wcds_geographic_view_type" name='wcds_geographic_view_type'>
		  <option value="country" <?php if($wcds_geographic_view_type === 'country') echo 'selected="selected"' ?>><?php _e('Country', 'woocommerce-dashboard-stats' ); ?></option>
		  <option value="state" <?php if($wcds_geographic_view_type === 'state') echo 'selected="selected"' ?>><?php _e('State/Province', 'woocommerce-dashboard-stats' ); ?></option>
		</select>
		
		
		<!-- range data selection (changes according to earing_period_range type) -->
		<input class="wcds_range_datepicker" type="text" id="wcds_geographic_picker_start_date" name="wcds_start_date" value="" placeholder="<?php _e('Start date', 'woocommerce-dashboard-stats' ); ?>" />
		<input class="wcds_range_datepicker" type="text" id="wcds_geographic_picker_end_date" name="wcds_end_date" value="" placeholder="<?php _e('End date', 'woocommerce-dashboard-stats' ); ?>" />
		<div class="wcds_spacer"></div>
		<input class="button-primary wcds_filter_button" id="wcds_geographic_chart_filter_button" type="submit" value="<?php _e('Filter', 'woocommerce-dashboard-stats' ); ?>" >  </input>
	
		
		<div class="chart">
			<!-- <h2 class="stat-title"><?php _e('Sales per area', 'woocommerce-dashboard-stats' ); ?></h2> -->
			<div id="wcds_geographic_wait_box" class="wcds_wait_box">
					<?php _e('Computing data, please wait...', 'woocommerce-dashboard-stats' ); ?>
					<img class="wcds_preloader_image" src="<?php echo WCDS_PLUGIN_URL.'/img/preloader.gif' ?>" ></img>
			</div>
			<div id="wcds_geographic_stats_canvas_box">
				<canvas id="wcds_geographic_stats" ></canvas>
			</div>			
		</div>
		<div class="wcds_spacer2"></div>
		<div id="wcds_geographic_stats_table" class="wcds_table">
				<table class="wp-list-table widefat striped">
					<thead>
						<tr>
							<th style="" class="manage-column column-date" scope="col"><?php _e('Area', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-count" scope="col"><?php _e('Orders', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-earnings num" scope="col"><?php _e('Sales', 'woocommerce-dashboard-stats' ); ?></th>
						</tr>
					</thead>
					 
						<tbody id="wcds_geographic_table_body">
							
						</tbody>
					<tfoot>
						<tr>
							<th style="" class="manage-column column-date" id="wcds_geographic_foot_total" scope="col"><?php _e('Total', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-count" id="wcds_geographic_foot_total_count" scope="col"></th>
							<th style="" class="manage-column column-earnings num" id="wcds_geographic_foot_total_earnings" scope="col"></th>
						</tr>
					</tfoot>
				</table>
			</div>
		<?php 
	}






	public function render_geographic_widget_network()
	{
		global $wcds_order_model;

			$wcds_geographic_view_type = 'site';
		
		$wcds_default_time_period = $this->default_time_periods['widget_default_time_period_geo_stats'];
		switch($wcds_default_time_period)
		{
			case 'day' : $current_period_string = __('Day', 'woocommerce-dashboard-stats'); break; //date('F')
			case 'month' : $current_period_string = __('Month', 'woocommerce-dashboard-stats'); break;
			case 'year' : $current_period_string = __('Year', 'woocommerce-dashboard-stats'); break;
	
		}
		$this->common_css_and_js();
		//wp_enqueue_style('wcds-widget-geographic', WCDS_PLUGIN_URL.'/css/admin-widget-geographic.css');  
		wp_enqueue_script('wcds-geographic-widget', WCDS_PLUGIN_URL.'/js/admin-geographic-widget_network.js', array( 'jquery' ));
		?>
		<script>
		var wcds_flags_path = "<?php echo WCDS_PLUGIN_URL.'/img/flags/'; ?>";
		var wcds_earnings_currency = "<?php echo get_woocommerce_currency_symbol(); ?>";
		var wcds_geographic_currency = "<?php echo get_woocommerce_currency_symbol(); ?>";
		var wcds_geographic_widget_date_error = "<?php _e('Start date cannot be greater than End date.', 'woocommerce-dashboard-stats'); ?>";
		</script>
		<p>
		<?php _e('If a data range is not selected, the displayed stats are relative to the current: ', 'woocommerce-dashboard-stats'); echo '<strong>'.$current_period_string.'</strong>'; ?>
		</p>
		<!-- conf -->
		
		<select id="wcds_max_results_num">
			<option value="10"><?php _e('Max results', 'woocommerce-dashboard-stats' ); ?></value>
			<option value="10" <?php if($this->default_settings['widget_default_value_geo_max_results'] === '10') echo 'selected="selected"' ?>>10</value>
			<option value="20" <?php if($this->default_settings['widget_default_value_geo_max_results'] === '20') echo 'selected="selected"' ?>>20</value>
			<option value="30" <?php if($this->default_settings['widget_default_value_geo_max_results'] === '30') echo 'selected="selected"' ?>>30</value>
			<option value="40" <?php if($this->default_settings['widget_default_value_geo_max_results'] === '40') echo 'selected="selected"' ?>>40</value>
			<option value="50" <?php if($this->default_settings['widget_default_value_geo_max_results'] === '50') echo 'selected="selected"' ?>>50</value>
		</select>
		
		<select id="wcds_geographic_view_type" name='wcds_geographic_view_type'>
			<option value="site" <?php if($wcds_geographic_view_type === 'site') echo 'selected="selected"' ?>><?php _e('Sub-Site', 'woocommerce-dashboard-stats' ); ?></option>
		  <option value="country" <?php if($wcds_geographic_view_type === 'country') echo 'selected="selected"' ?>><?php _e('Country', 'woocommerce-dashboard-stats' ); ?></option>
		  <option value="state" <?php if($wcds_geographic_view_type === 'state') echo 'selected="selected"' ?>><?php _e('State/Province', 'woocommerce-dashboard-stats' ); ?></option>
		</select>
		
		
		<!-- range data selection (changes according to earing_period_range type) -->
		<input class="wcds_range_datepicker" type="text" id="wcds_geographic_picker_start_date" name="wcds_start_date" value="" placeholder="<?php _e('Start date', 'woocommerce-dashboard-stats' ); ?>" />
		<input class="wcds_range_datepicker" type="text" id="wcds_geographic_picker_end_date" name="wcds_end_date" value="" placeholder="<?php _e('End date', 'woocommerce-dashboard-stats' ); ?>" />
		<div class="wcds_spacer"></div>
		<input class="button-primary wcds_filter_button" id="wcds_geographic_chart_filter_button" type="submit" value="<?php _e('Filter', 'woocommerce-dashboard-stats' ); ?>" >  </input>
	
		
		<div class="chart">
			<!-- <h2 class="stat-title"><?php _e('Sales per area', 'woocommerce-dashboard-stats' ); ?></h2> -->
			<div id="wcds_geographic_wait_box" class="wcds_wait_box">
					<?php _e('Computing data, please wait...', 'woocommerce-dashboard-stats' ); ?>
					<img class="wcds_preloader_image" src="<?php echo WCDS_PLUGIN_URL.'/img/preloader.gif' ?>" ></img>
			</div>
			<div id="wcds_geographic_stats_canvas_box">
				<canvas id="wcds_geographic_stats" ></canvas>
			</div>			
		</div>
		<div class="wcds_spacer2"></div>
		<div id="wcds_geographic_stats_table" class="wcds_table">
				<table class="wp-list-table widefat striped">
					<thead>
						<tr>
							<th style="" class="manage-column column-date" scope="col"><?php _e('Area', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-count" scope="col"><?php _e('Orders', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-earnings num" scope="col"><?php _e('Sales', 'woocommerce-dashboard-stats' ); ?></th>
						</tr>
					</thead>
					 
						<tbody id="wcds_geographic_table_body">
							
						</tbody>
					<tfoot>
						<tr>
							<th style="" class="manage-column column-date" id="wcds_geographic_foot_total" scope="col"><?php _e('Total', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-count" id="wcds_geographic_foot_total_count" scope="col"></th>
							<th style="" class="manage-column column-earnings num" id="wcds_geographic_foot_total_earnings" scope="col"></th>
						</tr>
					</tfoot>
				</table>
			</div>
		<?php 
	}



	public function render_customers_widget()
	{
		$wcds_default_time_period = $this->default_time_periods['widget_default_time_period_customers_stats'];
		switch($wcds_default_time_period)
		{
			case 'day' : $current_period_string = __('Day', 'woocommerce-dashboard-stats'); break; //date('F')
			case 'month' : $current_period_string = __('Month', 'woocommerce-dashboard-stats'); break;
			case 'year' : $current_period_string = __('Year', 'woocommerce-dashboard-stats'); break;
	
		}
		$this->common_css_and_js();
		wp_enqueue_script('wcds-customers-widget', WCDS_PLUGIN_URL.'/js/admin-customers-widget.js', array( 'jquery' ));
		?>
		<script>
		var wcds_customers_widget_date_error = "<?php _e('Start date cannot be greater than End date.', 'woocommerce-dashboard-stats'); ?>";
		var wcds_customers_currency = "<?php echo get_woocommerce_currency_symbol(); ?>"; 
		</script>
		<!-- range data selection (changes according to earing_period_range type) -->
		<p>
		<?php _e('If a data range is not selected, the displayed stats are relative to the current: ', 'woocommerce-dashboard-stats'); echo '<strong>'.$current_period_string.'</strong>'; ?>
		</p>
		<select id="wcds_customers_num">
			<option value="10"><?php _e('Max customers', 'woocommerce-dashboard-stats' ); ?></value>
			<option value="10" <?php if($this->default_settings['widget_default_value_customers_stats'] === '10') echo 'selected="selected"' ?>>10</value>
			<option value="20" <?php if($this->default_settings['widget_default_value_customers_stats'] === '20') echo 'selected="selected"' ?>>20</value>
			<option value="30" <?php if($this->default_settings['widget_default_value_customers_stats'] === '30') echo 'selected="selected"' ?>>30</value>
			<option value="40" <?php if($this->default_settings['widget_default_value_customers_stats'] === '40') echo 'selected="selected"' ?>>40</value>
			<option value="50" <?php if($this->default_settings['widget_default_value_customers_stats'] === '50') echo 'selected="selected"' ?>>50</value>
		</select>
		
		<input class="wcds_range_datepicker" type="text" id="wcds_customers_picker_start_date" value="" placeholder="<?php _e('Start date', 'woocommerce-dashboard-stats' ); ?>" />
		<input class="wcds_range_datepicker" type="text" id="wcds_customers_picker_end_date"  value="" placeholder="<?php _e('End date', 'woocommerce-dashboard-stats' ); ?>" />
		<div class="wcds_spacer"></div>
		<input class="button-primary wcds_filter_button" id="wcds_customers_chart_filter_button" type="submit" value="<?php _e('Filter', 'woocommerce-dashboard-stats' ); ?>" >  </input>
		
		<div class="chart">
			<!-- <h2 class="stat-title"><?php _e('Customers', 'woocommerce-dashboard-stats' ); ?></h2> -->
			<div id="wcds_customers_wait_box" class="wcds_wait_box">
				<?php _e('Computing data, please wait...', 'woocommerce-dashboard-stats' ); ?>
				<img class="wcds_preloader_image" src="<?php echo WCDS_PLUGIN_URL.'/img/preloader.gif' ?>" ></img>
			</div>
			<div id="wcds_customers_stats_canvas_box">
				<canvas id="wcds_customers_stats" ></canvas>
			</div>			
		</div>
		<div class="wcds_spacer2"></div>
		<div id="wcds_customers_stats_table" class="wcds_table">
				<table class="wp-list-table widefat striped">
					<thead>
						<tr>
							<th style="" class="manage-column column-namr" scope="col"><?php _e('Name', 'woocommerce-dashboard-stats' ); ?></th>							
							<th style="" class="manage-column column-guest" scope="col"><?php _e('Guest', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-count" scope="col"><?php _e('Orders', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-spent num" scope="col"><?php _e('Spent', 'woocommerce-dashboard-stats' ); ?></th>
						</tr>
					</thead>
					 
						<tbody id="wcds_customers_table_body">
							
						</tbody>
					<tfoot>
						<tr>
							<th style="" class="manage-column column-name" id="wcds_customers_foot_total" scope="col"><?php _e('Total', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column scope="col"></th>
							<th style="" class="manage-column column-count" id="wcds_customers_foot_total_count" scope="col"></th>
							<th style="" class="manage-column column-spent num" id="wcds_customers_foot_total_spent" scope="col"></th>
						</tr>
					</tfoot>
				</table>
			</div>
		<?php 
	}



	public function render_experts_widget()
	{
		$wcds_default_time_period = 'year';
		switch($wcds_default_time_period)
		{
			case 'day' : $current_period_string = __('Day', 'woocommerce-dashboard-stats'); break; //date('F')
			case 'month' : $current_period_string = __('Month', 'woocommerce-dashboard-stats'); break;
			case 'year' : $current_period_string = __('Year', 'woocommerce-dashboard-stats'); break;
	
		}
		$this->common_css_and_js();
		wp_enqueue_script('wcds-experts-widget', WCDS_PLUGIN_URL.'/js/admin-experts-widget.js', array( 'jquery' ));
		?>
		<script>
		var wcds_experts_widget_date_error = "<?php _e('Start date cannot be greater than End date.', 'woocommerce-dashboard-stats'); ?>";
		var wcds_experts_currency = "<?php echo get_woocommerce_currency_symbol(); ?>"; 
		</script>
		<!-- range data selection (changes according to earing_period_range type) -->
		<p>
		<?php _e('If a data range is not selected, the displayed stats are relative to the current: ', 'woocommerce-dashboard-stats'); echo '<strong>'.$current_period_string.'</strong>'; ?>
		</p>
		<select id="wcds_experts_num">
		<option value="10"><?php _e('Max Experts', 'woocommerce-dashboard-stats' ); ?></value>
			<option value="10" selected="selected">10</value>
			<option value="20">20</value>
			<option value="30">30</value>
			<option value="40">40</value>
			<option value="50">50</value>
		</select>
		
		<input class="wcds_range_datepicker" type="text" id="wcds_experts_picker_start_date" value="" placeholder="<?php _e('Start date', 'woocommerce-dashboard-stats' ); ?>" />
		<input class="wcds_range_datepicker" type="text" id="wcds_experts_picker_end_date"  value="" placeholder="<?php _e('End date', 'woocommerce-dashboard-stats' ); ?>" />
		<div class="wcds_spacer"></div>
		<input class="button-primary wcds_filter_button" id="wcds_experts_chart_filter_button" type="submit" value="<?php _e('Filter', 'woocommerce-dashboard-stats' ); ?>" >  </input>
		
		<div class="chart">
			<!-- <h2 class="stat-title"><?php _e('Customers', 'woocommerce-dashboard-stats' ); ?></h2> -->
			<div id="wcds_experts_wait_box" class="wcds_wait_box">
				<?php _e('Computing data, please wait...', 'woocommerce-dashboard-stats' ); ?>
				<img class="wcds_preloader_image" src="<?php echo WCDS_PLUGIN_URL.'/img/preloader.gif' ?>" ></img>
			</div>
			<div id="wcds_experts_stats_canvas_box">
				<canvas id="wcds_experts_stats" ></canvas>
			</div>			
			<div id="wcds_experts_totals_legend">
				<h2><?php _e('Legend', 'woocommerce-dashboard-stats' ); ?></h2>
				<div id="wcds_experts_legend_total_service_requests" 
				style="display: inline-block;
				background: rgba(133, 0, 0, 1);
				width: 18px;
				height: 18px">
				</div> 
				<span class="wcds_days_comp_legend_text"><?php _e('Total Invoice SR Contacts (€)', 'woocommerce-dashboard-stats' ); ?></span>
				<div id="wcds_experts_legend_total_number_sr" style="display:none;"></div>  <span class="wcds_days_comp_legend_text" style="display:none;"> <?php _e('Average sales', 'woocommerce-dashboard-stats' ); ?></span>
			</div>
		</div>		
		<div class="wcds_spacer2"></div>
		<div id="wcds_experts_stats_table" class="wcds_table">
				<table class="wp-list-table widefat striped">
					<thead>
						<tr>
							<th style="" class="manage-column column-namr" scope="col"><?php _e('Name', 'woocommerce-dashboard-stats' ); ?></th>	
							<th style="" class="manage-column column-count" scope="col"><?php _e('Number SR', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-spent num" scope="col"><?php _e('Total SR', 'woocommerce-dashboard-stats' ); ?></th>
						</tr>
					</thead>
					 
						<tbody id="wcds_experts_table_body">
							
						</tbody>
					<tfoot>
						<tr>
							<th style="" class="manage-column column-name" id="wcds_experts_foot_total" scope="col"><?php _e('Total', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-count" id="wcds_experts_foot_number_sr" scope="col"></th>
							<th style="" class="manage-column column-spent num" id="wcds_experts_foot_total_sr" scope="col"></th>
						</tr>
					</tfoot>
				</table>
			</div>
		<?php 
	}



	public function render_referrals_widget()
	{
		$wcds_default_time_period = 'year';
		switch($wcds_default_time_period)
		{
			case 'day' : $current_period_string = __('Day', 'woocommerce-dashboard-stats'); break; //date('F')
			case 'month' : $current_period_string = __('Month', 'woocommerce-dashboard-stats'); break;
			case 'year' : $current_period_string = __('Year', 'woocommerce-dashboard-stats'); break;
	
		}
		$this->common_css_and_js();
		wp_enqueue_script('wcds-experts-widget', WCDS_PLUGIN_URL.'/js/admin-experts-widget.js', array( 'jquery' ));
		?>
		<script>
		var wcds_referrals_widget_date_error = "<?php _e('Start date cannot be greater than End date.', 'woocommerce-dashboard-stats'); ?>";
		var wcds_referrals_currency = "<?php echo get_woocommerce_currency_symbol(); ?>"; 
		</script>
		<!-- range data selection (changes according to earing_period_range type) -->
		<p>
		<?php _e('If a data range is not selected, the displayed stats are relative to the current: ', 'woocommerce-dashboard-stats'); echo '<strong>'.$current_period_string.'</strong>'; ?>
		</p>
		<select id="wcds_referrals_num">
		<option value="10"><?php _e('Max Referrals', 'woocommerce-dashboard-stats' ); ?></value>
			<option value="10" selected="selected">10</value>
			<option value="20">20</value>
			<option value="30">30</value>
			<option value="40">40</value>
			<option value="50">50</value>
		</select>
		
		<input class="wcds_range_datepicker" type="text" id="wcds_referrals_picker_start_date" value="" placeholder="<?php _e('Start date', 'woocommerce-dashboard-stats' ); ?>" />
		<input class="wcds_range_datepicker" type="text" id="wcds_referrals_picker_end_date"  value="" placeholder="<?php _e('End date', 'woocommerce-dashboard-stats' ); ?>" />
		<div class="wcds_spacer"></div>
		<input class="button-primary wcds_filter_button" id="wcds_referrals_chart_filter_button" type="submit" value="<?php _e('Filter', 'woocommerce-dashboard-stats' ); ?>" >  </input>
		
		<div class="chart">
			<!-- <h2 class="stat-title"><?php _e('Customers', 'woocommerce-dashboard-stats' ); ?></h2> -->
			<div id="wcds_referrals_wait_box" class="wcds_wait_box">
				<?php _e('Computing data, please wait...', 'woocommerce-dashboard-stats' ); ?>
				<img class="wcds_preloader_image" src="<?php echo WCDS_PLUGIN_URL.'/img/preloader.gif' ?>" ></img>
			</div>
			<div id="wcds_referrals_stats_canvas_box">
				<canvas id="wcds_referrals_stats" ></canvas>
			</div>			
			<div id="wcds_referrals_totals_legend">
				<div id="wcds_referrals_legend_total_service_requests" 
				style="display: inline-block;
				background: rgba(218,165,32);
				width: 18px;
				height: 18px">
				</div> 
				<span class="wcds_days_comp_legend_text"><?php _e('Total Expert Earnings (€)', 'woocommerce-dashboard-stats' ); ?></span>
				<div id="wcds_referrals_legend_total_number_sr" style="display:none;"></div>  <span class="wcds_days_comp_legend_text" style="display:none;"> <?php _e('Average sales', 'woocommerce-dashboard-stats' ); ?></span>
			</div>
		</div>		
		<div class="wcds_spacer2"></div>
		<div id="wcds_referrals_stats_table" class="wcds_table">
				<table class="wp-list-table widefat striped"> 
					<thead>
						<tr>
							<th style="" class="manage-column column-namr" scope="col"><?php _e('Name', 'woocommerce-dashboard-stats' ); ?></th>	
							<th style="" class="manage-column column-count" scope="col"><?php _e('Nº Referrals', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-spent num" scope="col"><?php _e('Earnings', 'woocommerce-dashboard-stats' ); ?></th>
						</tr>
					</thead>
					 
						<tbody id="wcds_referrals_table_body">
							
						</tbody>
					<tfoot>
						<tr>
							<th style="" class="manage-column column-name" id="wcds_referrals_foot_total" scope="col"><?php _e('Total', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-count" id="wcds_referrals_foot_number_sr" scope="col"></th>
							<th style="" class="manage-column column-spent num" id="wcds_referrals_foot_total_sr" scope="col"></th>
						</tr>
					</tfoot>
				</table>
			</div>
		<?php 
	}



	public function render_categories_widget()
	{
		$wcds_default_time_period = 'year';
		switch($wcds_default_time_period)
		{
			case 'day' : $current_period_string = __('Day', 'woocommerce-dashboard-stats'); break; //date('F')
			case 'month' : $current_period_string = __('Month', 'woocommerce-dashboard-stats'); break;
			case 'year' : $current_period_string = __('Year', 'woocommerce-dashboard-stats'); break;
	
		}
		$this->common_css_and_js();
		wp_enqueue_script('wcds-categories-widget', WCDS_PLUGIN_URL.'/js/admin-categories-widget.js', array( 'jquery' ));
		?>
		<style>
			.lowfont td, .lowfont th{
				font-size: 11px;
			}
		</style>
		<script>
		var wcds_categories_widget_date_error = "<?php _e('Start date cannot be greater than End date.', 'woocommerce-dashboard-stats'); ?>";
		var wcds_categories_currency = "<?php echo get_woocommerce_currency_symbol(); ?>"; 
		</script>
		<!-- range data selection (changes according to earing_period_range type) -->
		<p>
		<?php _e('If a data range is not selected, the displayed stats are relative to the current: ', 'woocommerce-dashboard-stats'); echo '<strong>'.$current_period_string.'</strong>'; ?>
		</p>
		<select id="wcds_categories_num">
			<option value="10"><?php _e('Max Categories', 'woocommerce-dashboard-stats' ); ?></value>
			<option value="10" selected="selected">10</value>
			<option value="20">20</value>
			<option value="30">30</value>
			<option value="40">40</value>
			<option value="50">50</value>
		</select>
		
		<input class="wcds_range_datepicker" type="text" id="wcds_categories_picker_start_date" value="" placeholder="<?php _e('Start date', 'woocommerce-dashboard-stats' ); ?>" />
		<input class="wcds_range_datepicker" type="text" id="wcds_categories_picker_end_date"  value="" placeholder="<?php _e('End date', 'woocommerce-dashboard-stats' ); ?>" />
		<div class="wcds_spacer"></div>
		<input class="button-primary wcds_filter_button" id="wcds_categories_chart_filter_button" type="submit" value="<?php _e('Filter', 'woocommerce-dashboard-stats' ); ?>" >  </input>
		<div class="chart">
			<!-- <h2 class="stat-title"><?php _e('Customers', 'woocommerce-dashboard-stats' ); ?></h2> -->
			<div id="wcds_categories_wait_box" class="wcds_wait_box">
				<?php _e('Computing data, please wait...', 'woocommerce-dashboard-stats' ); ?>
				<img class="wcds_preloader_image" src="<?php echo WCDS_PLUGIN_URL.'/img/preloader.gif' ?>" ></img>
			</div>
			<div id="wcds_categories_stats_canvas_box">
				<canvas id="wcds_categories_stats" ></canvas>
			</div>			
		</div>		
		<div class="wcds_spacer2"></div>

		<div id="wcds_categories_stats_table" class="wcds_table">
				<table class="wp-list-table widefat lowfont striped">
					<thead>
						<tr>
							<th style="" class="manage-column column-namr" scope="col"><?php _e('Category', 'woocommerce-dashboard-stats' ); ?></th>	
							<th style="" class="manage-column column-count" scope="col"><?php _e('#SR Number', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-spent num" scope="col"><?php _e('Amount', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-spent num" scope="col"><?php _e('AVG per SR', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-spent num" scope="col"><?php _e('Royalties', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-spent num" scope="col"><?php _e('%', 'woocommerce-dashboard-stats' ); ?></th>
						</tr>
					</thead>
					 
						<tbody id="wcds_categories_table_body">
						</tbody>
					<tfoot>
						<tr>
							<th style="" class="manage-column column-name" id="wcds_categories_foot_total" scope="col"><?php _e('Total', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-count" id="wcds_categories_foot_number_sr" scope="col"></th>
							<th style="" class="manage-column column-spent num" id="wcds_categories_foot_total_sr" scope="col"></th>
							<th style="" class="manage-column column-spent num" id="wcds_categories_foot_total_avg" scope="col"></th>
							<th style="" class="manage-column column-spent num" id="wcds_categories_foot_total_royal" scope="col"></th>
							<th style="" class="manage-column column-spent num" id="wcds_categories_foot_total_percentage" scope="col"></th>
						</tr>
					</tfoot>
				</table>
			</div>
		<?php 
	}





	public function render_products_widget()
	{
		$this->common_css_and_js();
		wp_enqueue_script('wcds-products-widget', WCDS_PLUGIN_URL.'/js/admin-products-widget.js', array( 'jquery' ));
		$wcds_default_time_period = $this->default_time_periods['widget_default_time_period_products_stats'];
		switch($wcds_default_time_period)
		{
			case 'day' : $current_period_string = __('Day', 'woocommerce-dashboard-stats'); break; //date('F')
			case 'month' : $current_period_string = __('Month', 'woocommerce-dashboard-stats'); break;
			case 'year' : $current_period_string = __('Year', 'woocommerce-dashboard-stats'); break;
	
		}
		?>
		<script>
		var wcds_products_widget_date_error = "<?php _e('Start date cannot be greater than End date.', 'woocommerce-dashboard-stats'); ?>";
		var wcds_products_currency = "<?php echo get_woocommerce_currency_symbol(); ?>"; 
		var wcds_yes = "<?php _e('Yes', 'woocommerce-dashboard-stats'); ?>"; 
		var wcds_no = "<?php _e('No', 'woocommerce-dashboard-stats'); ?>"; 
		</script>
		<!-- range data selection (changes according to earing_period_range type) -->
		<p>
		<?php _e('If a data range is not selected, the displayed stats are relative to the current: ', 'woocommerce-dashboard-stats'); echo '<strong>'.$current_period_string.'</strong>'; ?>
		</p>
		<select class="wcds_half_width" id="wcds_products_num">
			<option value="10"><?php _e('Max products', 'woocommerce-dashboard-stats' ); ?></value>
			<option value="10" <?php if($this->default_settings['widget_default_value_products_stats'] === '10') echo 'selected="selected"' ?>>10</value>
			<option value="20" <?php if($this->default_settings['widget_default_value_products_stats'] === '20') echo 'selected="selected"' ?>>20</value>
			<option value="30" <?php if($this->default_settings['widget_default_value_products_stats'] === '30') echo 'selected="selected"' ?>>30</value>
			<option value="40" <?php if($this->default_settings['widget_default_value_products_stats'] === '40') echo 'selected="selected"' ?>>40</value>
			<option value="50" <?php if($this->default_settings['widget_default_value_products_stats'] === '50') echo 'selected="selected"' ?>>50</value>
		</select>
		<select class="wcds_half_width" id="wcds_show_variations">
			<option value="no"><?php _e('Show variations', 'woocommerce-dashboard-stats' ); ?></value>
			<option value="no" <?php if($this->default_settings['widget_default_value_products_stats_show_variations'] === 'no') echo 'selected="selected"' ?>><?php _e('No', 'woocommerce-dashboard-stats' ); ?></value>
			<option value="yes" <?php if($this->default_settings['widget_default_value_products_stats_show_variations'] === 'yes') echo 'selected="selected"' ?>><?php _e('Yes', 'woocommerce-dashboard-stats' ); ?></value>
		</select>
		
		<input class="wcds_half_width wcds_range_datepicker" type="text" id="wcds_products_picker_start_date" value="" placeholder="<?php _e('Start date', 'woocommerce-dashboard-stats' ); ?>" />
		<input class="wcds_half_width wcds_range_datepicker" type="text" id="wcds_products_picker_end_date"  value="" placeholder="<?php _e('End date', 'woocommerce-dashboard-stats' ); ?>" />
		<div class="wcds_spacer"></div>
		<input class="button-primary wcds_filter_button" id="wcds_products_chart_filter_button" type="submit" value="<?php _e('Filter', 'woocommerce-dashboard-stats' ); ?>" >  </input>
		
		<div class="chart">
			<!--<h2 class="stat-title"><?php _e('Products', 'woocommerce-dashboard-stats' ); ?></h2> -->
			<div id="wcds_products_wait_box" class="wcds_wait_box">
				<?php _e('Computing data, please wait...', 'woocommerce-dashboard-stats' ); ?>
				<img class="wcds_preloader_image" src="<?php echo WCDS_PLUGIN_URL.'/img/preloader.gif' ?>" ></img>
			</div>
			<div id="wcds_products_stats_canvas_box">
				<canvas id="wcds_products_stats" ></canvas>
			</div>			
		</div>
		<div class="wcds_spacer2"></div>
		<div id="wcds_products_stats_table" class="wcds_table">
				<table class="wp-list-table widefat striped">
					<thead>
						<tr>
							<th style="" class="manage-column column-namr" scope="col"><?php _e('Name', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-count" scope="col"><?php _e('Items', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-count" scope="col"><?php _e('Stock Left', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-earnings num" scope="col"><?php _e('Sales', 'woocommerce-dashboard-stats' ); ?></th>
						</tr>
					</thead>
					 
						<tbody id="wcds_products_table_body">
							
						</tbody>
					<tfoot>
						<tr>
							<th style="" class="manage-column column-name" id="wcds_products_foot_total" scope="col"><?php _e('Total', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-count" id="wcds_products_foot_total_count" scope="col"></th>
							<th style="" class="manage-column column-count" id="wcds_products_foot_stock_left" scope="col"></th>
							<th style="" class="manage-column column-earnings num" id="wcds_products_foot_total_earnings" scope="col"></th>
						</tr>
					</tfoot>
				</table>
			</div>
		<?php 
	}
	public function render_earnings_widget()
	{
		global $wcds_order_model;
		$wcds_earning_period_range = $this->default_settings['widget_default_value_sales_stats'];
		$wcds_default_time_period = $this->default_time_periods['widget_default_time_period_sales_stats'];
		switch($wcds_default_time_period)
		{
			case 'day' : $current_period_string = __('Day', 'woocommerce-dashboard-stats'); break; //date('F')
			case 'month' : $current_period_string = __('Month', 'woocommerce-dashboard-stats'); break;
			case 'year' : $current_period_string = __('Year', 'woocommerce-dashboard-stats'); break;
	
		}
		$this->common_css_and_js();
		//wp_enqueue_style('wcds-widget-earnings', WCDS_PLUGIN_URL.'/css/admin-widget-earnings.css');  
		wp_enqueue_script('wcds-earnings-widget', WCDS_PLUGIN_URL.'/js/admin-earnings-widget.js', array( 'jquery' ));
		
		//$stats = $wcds_order_model->get_earnings_per_period();
		//wcds_var_dump($stats);
		?>
		<script>
		var wcds_earnings_currency = "<?php echo get_woocommerce_currency_symbol(); ?>";
		var wcds_earning_widget_date_error = "<?php _e('Start date cannot be greater than End date.', 'woocommerce-dashboard-stats'); ?>";
		</script>
		<p>
		<?php _e('If a data range is not selected, the displayed stats are relative to the current: ', 'woocommerce-dashboard-stats'); echo '<strong>'.$current_period_string.'</strong>'; ?>
		</p>
		<!-- conf -->
		<!--<form method="post"> -->
			<select id="wcds_earning_period_range" name='wcds_earning_period_range'>
			  <option value="daily" <?php if($wcds_earning_period_range === 'daily') echo 'selected="selected"' ?>><?php _e('Daily View', 'woocommerce-dashboard-stats' ); ?></option>
			  <option value="monthly" <?php if($wcds_earning_period_range === 'monthly') echo 'selected="selected"' ?>><?php _e('Monthly View', 'woocommerce-dashboard-stats' ); ?></option>
			  <option value="yearly" <?php if($wcds_earning_period_range === 'yearly') echo 'selected="selected"' ?>><?php _e('Yearly View', 'woocommerce-dashboard-stats' ); ?></option>
			</select>
			
			<!-- range data selection (changes according to earing_period_range type) -->
			<input class="wcds_range_datepicker" type="text" id="wcds_earning_picker_start_date" name="wcds_start_date" value="" placeholder="<?php _e('Start date', 'woocommerce-dashboard-stats' ); ?>" />
			<input class="wcds_range_datepicker" type="text" id="wcds_earning_picker_end_date" name="wcds_end_date" value="" placeholder="<?php _e('End date', 'woocommerce-dashboard-stats' ); ?>" />
			<div class="wcds_spacer"></div>
			<input class="button-primary wcds_filter_button" id="wcds_earnings_chart_filter_button" type="submit" value="<?php _e('Filter', 'woocommerce-dashboard-stats' ); ?>" >  </input>
		<!-- </form> -->
		<!-- end conf -->
		
		<div class="chart">
			<!--<h2 class="stat-title"><?php _e('Sales', 'woocommerce-dashboard-stats' ); ?></h2>-->
			<div id="wcds_earning_wait_box" class="wcds_wait_box">
					<?php _e('Computing data, please wait...', 'woocommerce-dashboard-stats' ); ?>
					<img class="wcds_preloader_image" src="<?php echo WCDS_PLUGIN_URL.'/img/preloader.gif' ?>" ></img>
			</div>
			<div id="wcds_earning_stats_canvas_box">
				<canvas id="wcds_earning_stats" ></canvas>
			</div>			
		</div>
		<div class="wcds_spacer2"></div>
		<div id="wcds_earning_stats_table" class="wcds_table">
				<table class="wp-list-table widefat striped">
					<thead>
						<tr>
							<th style="" class="manage-column column-date column-date-earnings" scope="col"><?php _e('Date', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-count" scope="col"><?php _e('Items', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-count" scope="col"><?php _e('Orders', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-earnings num" scope="col"><?php _e('Sales', 'woocommerce-dashboard-stats' ); ?></th>
						</tr>
					</thead>
					 
						<tbody id="wcds_table_body">
							
						</tbody>
					<tfoot>
						<tr>
							<th style="" class="manage-column column-date" id="wcds_foot_total" scope="col"><?php _e('Total', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-count" id="wcds_products_foot_total_item_count" scope="col"></th>
							<th style="" class="manage-column column-count" id="wcds_foot_total_count" scope="col"></th>
							<th style="" class="manage-column column-earnings num" id="wcds_foot_total_earnings" scope="col"></th>
						</tr>
					</tfoot>
				</table>
			</div>
		<?php 
	}
	public function render_refund_widget()
	{
		global $wcds_order_model;
		$wcds_refund_period_range = $this->default_settings['widget_default_value_refund_stats'];
		$wcds_default_time_period = $this->default_time_periods['widget_default_time_period_refund_stats'];
		switch($wcds_default_time_period)
		{
			case 'day' : $current_period_string = __('Day', 'woocommerce-dashboard-stats'); break; //date('F')
			case 'month' : $current_period_string = __('Month', 'woocommerce-dashboard-stats'); break;
			case 'year' : $current_period_string = __('Year', 'woocommerce-dashboard-stats'); break;
	
		}
		$this->common_css_and_js();
		wp_enqueue_script('wcds-refund-widget', WCDS_PLUGIN_URL.'/js/admin-refund-widget.js', array( 'jquery' ));
		
		?>
		<script>
		var wcds_refund_currency = "<?php echo get_woocommerce_currency_symbol(); ?>";
		var wcds_refund_widget_date_error = "<?php _e('Start date cannot be greater than End date.', 'woocommerce-dashboard-stats'); ?>";
		</script>
		<p>
		<?php _e('If a data range is not selected, the displayed stats are relative to the current: ', 'woocommerce-dashboard-stats'); echo '<strong>'.$current_period_string.'</strong>'; ?>
		</p>
		<!-- conf -->
		<!--<form method="post"> -->
			<select id="wcds_refund_period_range" name='wcds_refund_period_range'>
			  <option value="daily" <?php if($wcds_refund_period_range === 'daily') echo 'selected="selected"' ?>><?php _e('Daily View', 'woocommerce-dashboard-stats' ); ?></option>
			  <option value="monthly" <?php if($wcds_refund_period_range === 'monthly') echo 'selected="selected"' ?>><?php _e('Monthly View', 'woocommerce-dashboard-stats' ); ?></option>
			  <option value="yearly" <?php if($wcds_refund_period_range === 'yearly') echo 'selected="selected"' ?>><?php _e('Yearly View', 'woocommerce-dashboard-stats' ); ?></option>
			</select>
			
			<!-- range data selection (changes according to earing_period_range type) -->
			<input class="wcds_range_datepicker" type="text" id="wcds_refund_picker_start_date" name="wcds_start_date" value="" placeholder="<?php _e('Start date', 'woocommerce-dashboard-stats' ); ?>" />
			<input class="wcds_range_datepicker" type="text" id="wcds_refund_picker_end_date" name="wcds_end_date" value="" placeholder="<?php _e('End date', 'woocommerce-dashboard-stats' ); ?>" />
			<div class="wcds_spacer"></div>
			<input class="button-primary wcds_filter_button" id="wcds_refund_chart_filter_button" type="submit" value="<?php _e('Filter', 'woocommerce-dashboard-stats' ); ?>" >  </input>
		<!-- </form> -->
		<!-- end conf -->
		
		<div class="chart">
			<!--<h2 class="stat-title"><?php _e('Sales', 'woocommerce-dashboard-stats' ); ?></h2>-->
			<div id="wcds_refund_wait_box" class="wcds_wait_box">
					<?php _e('Computing data, please wait...', 'woocommerce-dashboard-stats' ); ?>
					<img class="wcds_preloader_image" src="<?php echo WCDS_PLUGIN_URL.'/img/preloader.gif' ?>" ></img>
			</div>
			<div id="wcds_refund_stats_canvas_box">
				<canvas id="wcds_refund_stats" ></canvas>
			</div>			
		</div>
		<div class="wcds_spacer2"></div>
		<div id="wcds_refund_stats_table" class="wcds_table">
				<table class="wp-list-table widefat striped">
					<thead>
						<tr>
							<th style="" class="manage-column column-count" scope="col"><?php _e('Order', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-date column-date-refund" scope="col"><?php _e('Date', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-refund  scope="col"><?php _e('Reason', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-refund num" scope="col"><?php _e('Refunded', 'woocommerce-dashboard-stats' ); ?></th>
						</tr>
					</thead>
					 
						<tbody id="wcds_refund_table_body">
							
						</tbody>
					<tfoot>
						<tr>
							<th style="" class="manage-column column-date" id="" scope="col"><?php _e('Total', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-count" id="" scope="col"></th>
							<th style="" class="manage-column column-count" id="" scope="col"></th>
							<th style="" class="manage-column column-refund num" id="wcds_refund_foot_total" scope="col"></th>
						</tr>
					</tfoot>
				</table>
				<div id="wcds-refund-list-paging" class="wcds-pager"></div>
			</div>
		<?php 
	}
	public function render_payment_methods_metabox()
	{
		global $wcds_order_model;
		
		$this->common_css_and_js();
		//wp_enqueue_style('wcds-widget-earnings', WCDS_PLUGIN_URL.'/css/admin-widget-earnings.css');  
		wp_enqueue_script('wcds-payment-methods-widget', WCDS_PLUGIN_URL.'/js/admin-payment-methods-widget.js', array( 'jquery' ));
		$wcds_default_time_period = $this->default_time_periods['widget_default_time_period_payment_methods_stats'];
		switch($wcds_default_time_period)
		{
			case 'day' : $current_period_string = __('Day', 'woocommerce-dashboard-stats'); break; //date('F')
			case 'month' : $current_period_string = __('Month', 'woocommerce-dashboard-stats'); break;
			case 'year' : $current_period_string = __('Year', 'woocommerce-dashboard-stats'); break;
	
		}
		?>
		<script>
		var wcds_payment_methods_currency = "<?php echo get_woocommerce_currency_symbol(); ?>";
		var wcds_payment_methods_widget_date_error = "<?php _e('Start date cannot be greater than End date.', 'woocommerce-dashboard-stats'); ?>";
		</script>
		<p>
		<?php _e('If a data range is not selected, the displayed stats are relative to the current: ', 'woocommerce-dashboard-stats'); echo '<strong>'.$current_period_string.'</strong>'; ?>
		</p>
		<select class="" id="wcds_payment_methods_num">
			<option value="10"><?php _e('Max results', 'woocommerce-dashboard-stats' ); ?></value>
			<option value="10" <?php if($this->default_settings['widget_default_value_payment_methods'] === '10') echo 'selected="selected"' ?>>10</value>
			<option value="20" <?php if($this->default_settings['widget_default_value_payment_methods'] === '20') echo 'selected="selected"' ?>>20</value>
			<option value="30" <?php if($this->default_settings['widget_default_value_payment_methods'] === '30') echo 'selected="selected"' ?>>30</value>
			<option value="40" <?php if($this->default_settings['widget_default_value_payment_methods'] === '40') echo 'selected="selected"' ?>>40</value>
			<option value="50" <?php if($this->default_settings['widget_default_value_payment_methods'] === '50') echo 'selected="selected"' ?>>50</value>
		</select>
		
		<!-- conf -->
		<!--<form method="post"> -->
			<!-- range data selection (changes according to earing_period_range type) -->
			<input class="wcds_range_datepicker " type="text" id="wcds_payment_methods_picker_start_date" name="wcds_start_date" value="" placeholder="<?php _e('Start date', 'woocommerce-dashboard-stats' ); ?>" />
			<input class="wcds_range_datepicker " type="text" id="wcds_payment_methods_picker_end_date" name="wcds_end_date" value="" placeholder="<?php _e('End date', 'woocommerce-dashboard-stats' ); ?>" />
			<div class="wcds_spacer"></div>
			<input class="button-primary wcds_filter_button" id="wcds_payment_methods_chart_filter_button" type="submit" value="<?php _e('Filter', 'woocommerce-dashboard-stats' ); ?>" >  </input>
		<!-- </form> -->
		<!-- end conf -->
		
		<div class="chart">
			<div id="wcds_payment_methods_wait_box" class="wcds_wait_box">
					<?php _e('Computing data, please wait...', 'woocommerce-dashboard-stats' ); ?>
					<img class="wcds_preloader_image" src="<?php echo WCDS_PLUGIN_URL.'/img/preloader.gif' ?>" ></img>
			</div>
			<div id="wcds_payment_methods_stats_canvas_box">
				<canvas id="wcds_payment_methods_stats" ></canvas>
			</div>			
		</div>
		<div class="wcds_spacer2"></div>
		<div id="wcds_payment_methods_stats_table" class="wcds_table">
				<table class="wp-list-table widefat striped">
					<thead>
						<tr>
							<th style="" class="manage-column column-date column-date-earnings" scope="col"><?php _e('Title', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-count" scope="col"><?php _e('Code', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-count" scope="col"><?php _e('Orders', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-earnings num" scope="col"><?php _e('Sales', 'woocommerce-dashboard-stats' ); ?></th>
						</tr>
					</thead>
					 
						<tbody id="wcds_payment_methods_table_body">
							
						</tbody>
					<tfoot>
						<tr>
							<th style="" class="manage-column column-date" id="wcds_payment_method_foot_total" scope="col"><?php _e('Total', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-count" id="wcds_payment_method_foot_total_item_count" scope="col"></th>
							<th style="" class="manage-column column-count" id="wcds_payment_method_foot_total_count" scope="col"></th>
							<th style="" class="manage-column column-earnings num" id="wcds_payment_method_foot_total_earnings" scope="col"></th>
						</tr>
					</tfoot>
				</table>
			</div>
		<?php 
	}



	public function render_listing_stats_widget( $network = null )
	{
		global $wcds_order_model;
		$wcds_listing_period_range = $this->default_settings['widget_default_value_sales_stats'];
		$wcds_default_time_period = $this->default_time_periods['widget_default_time_period_sales_stats'];
		switch($wcds_default_time_period)
		{
			case 'day' : $current_period_string = __('Day', 'woocommerce-dashboard-stats'); break; //date('F')
			case 'month' : $current_period_string = __('Month', 'woocommerce-dashboard-stats'); break;
			case 'year' : $current_period_string = __('Year', 'woocommerce-dashboard-stats'); break;
		}
		$this->common_css_and_js();
		//wp_enqueue_style('wcds-widget-earnings', WCDS_PLUGIN_URL.'/css/admin-widget-earnings.css');  
		//wp_enqueue_script('wcds-earnings-widget', WCDS_PLUGIN_URL.'/js/admin-earnings-widget.js', array( 'jquery' ));
		wp_enqueue_script('wcds-listing-widget', WCDS_PLUGIN_URL.'/js/admin-listing-widget.js', array( 'jquery' ));
		
		//$stats = $wcds_order_model->get_earnings_per_period();
		//wcds_var_dump($stats);
		?>
		<script>
		var wcds_listing_currency = "<?php echo get_woocommerce_currency_symbol(); ?>";
		var wcds_listing_widget_date_error = "<?php _e('Start date cannot be greater than End date.', 'woocommerce-dashboard-stats'); ?>";
		</script>
		<p>
		<?php _e('If a data range is not selected, the displayed stats are relative to the current: ', 'woocommerce-dashboard-stats'); echo '<strong>'.$current_period_string.'</strong>'; ?>
		</p>

		<?php
			if($network){
				echo '<input type="hidden" value="isNetwork" name="isnetwork" id="isnetwork" /> ';
			}else{
				echo '<input type="hidden" value="" name="isnetwork" id="isnetwork" />';
			}
		?>
		
		<!-- conf -->
		<!--<form method="post"> -->
			<select id="wcds_listing_period_range" name='wcds_listing_period_range'>
			  <option value="daily" <?php if($wcds_listing_period_range === 'daily') echo 'selected="selected"' ?>><?php _e('Daily View', 'woocommerce-dashboard-stats' ); ?></option>
			  <option value="monthly" <?php if($wcds_listing_period_range === 'monthly') echo 'selected="selected"' ?>><?php _e('Monthly View', 'woocommerce-dashboard-stats' ); ?></option>
			  <option value="yearly" <?php if($wcds_listing_period_range === 'yearly') echo 'selected="selected"' ?>><?php _e('Yearly View', 'woocommerce-dashboard-stats' ); ?></option>
			</select>
			
			<!-- range data selection (changes according to earing_period_range type) -->
			<input class="wcds_range_datepicker" type="text" id="wcds_listing_picker_start_date" name="wcds_start_date" value="" placeholder="<?php _e('Start date', 'woocommerce-dashboard-stats' ); ?>" />
			<input class="wcds_range_datepicker" type="text" id="wcds_listing_picker_end_date" name="wcds_end_date" value="" placeholder="<?php _e('End date', 'woocommerce-dashboard-stats' ); ?>" />
			<div class="wcds_spacer"></div>
			<input class="button-primary wcds_filter_button" id="wcds_listings_chart_filter_button" type="submit" value="<?php _e('Filter', 'woocommerce-dashboard-stats' ); ?>" >  </input>
		<!-- </form> -->
		<!-- end conf -->
		
		<div class="chart">
			<!--<h2 class="stat-title"><?php _e('Listings', 'woocommerce-dashboard-stats' ); ?></h2>-->
			<div id="wcds_listing_wait_box" class="wcds_wait_box">
					<?php _e('Computing data, please wait...', 'woocommerce-dashboard-stats' ); ?>
					<img class="wcds_preloader_image" src="<?php echo WCDS_PLUGIN_URL.'/img/preloader.gif' ?>" ></img>
			</div>
			<div id="wcds_listing_stats_canvas_box">
				<canvas id="wcds_listing_stats" ></canvas>
			</div>			
		</div>
		<div class="wcds_spacer2"></div>
		<div id="wcds_listing_stats_table" class="wcds_table">
				<table class="wp-list-table widefat striped">
					<thead>
						<tr>
							<th style="" class="manage-column column-date column-date-listings" scope="col"><?php _e('Date', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-count" scope="col"><?php _e('Items', 'woocommerce-dashboard-stats' ); ?></th>
						</tr>
					</thead>
					 
						<tbody id="wcds_table_listing_body">
							
						</tbody>
					<tfoot>
						<tr>
							<th style="" class="manage-column column-date" id="wcds_listings_foot_total" scope="col"><?php _e('Total', 'woocommerce-dashboard-stats' ); ?></th>
							<th style="" class="manage-column column-count" id="wcds_listings_foot_total_item_count" scope="col"></th>
						</tr>
					</tfoot>
				</table>
			</div>
		<?php 
	}

	
	
}
?>