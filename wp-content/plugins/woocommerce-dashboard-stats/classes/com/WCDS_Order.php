<?php 
class WCSD_Order
{
	public function __construct()
	{
		if(is_admin())
		{
			add_action('wp_ajax_wcds_earning_widget_get_earning_per_period', array(&$this, 'ajax_get_earning_per_period') );
			add_action('wp_ajax_wcds_listings_widget_get_listings_per_period', array(&$this, 'ajax_get_listings_per_period') );


			add_action('wp_ajax_wcds_geographic_widget_get_earning_per_area', array(&$this, 'ajax_get_earning_per_geograpic_area') );
			add_action('wp_ajax_wcds_geographic_widget_get_earning_per_area_network', array(&$this, 'ajax_get_earning_per_geograpic_area_network') );
			add_action('wp_ajax_wcds_av_and_est_widget', array(&$this, 'ajax_get_avarages_and_estimations') );
			add_action('wp_ajax_wcds_days_comp_widget', array(&$this, 'ajax_get_last_days_earnings') );
			add_action('wp_ajax_wcds_payment_methods_widget', array(&$this, 'ajax_get_payment_methods') );
			add_action('wp_ajax_wcds_refund_widget_get_refund_per_period', array(&$this, 'ajax_get_refund_widget_get_refund_per_period') );
		}
	}
	private function round($number, $precision = 2)
	{
		return number_format($number, $precision, '.', '');
	}
	public function ajax_get_last_days_earnings()
	{
		$max_days = 15;
		$avarage_time_day_interval =  31;
		$start_date = date('Y-m-d', strtotime('- '.$avarage_time_day_interval.' days'));
		$end_date =  date('Y-m-d', strtotime('- 7 days'));
		if(isset($_POST['start_date']) && $_POST['start_date'] != "" && isset($_POST['end_date']) && $_POST['end_date'] != "")
		{
			$dStart = new DateTime($_POST['start_date']);
			$dEnd  = new DateTime($_POST['end_date']);
			$dDiff = $dStart->diff($dEnd);
			$avarage_time_day_interval =  $dDiff->days;
			
			$start_date = $_POST['start_date'];
			$end_date = $_POST['end_date'];
		}
		
		$total_earning = $this->get_earnings_per_period('daily', $start_date, $end_date);
		$total_earning_last_two_weeks = $this->get_earnings_per_period('daily', date('Y-m-d', strtotime('- 16 days')), date('Y-m-d'));
		$earning_per_day = array();
		$earning_per_day_last_two_weeks = array();
		$final_result = array();		
		//Setup
		for($i=0; $i<$avarage_time_day_interval; $i++) //????????
		{
			$earning_per_day[date('j/n/Y', strtotime('-'.$i.' days'))] = array();
		}
		foreach($total_earning as $day_earning)
		{
			//wcds_var_dump($day_earning);
			$earning_per_day[$day_earning['date']] = $day_earning;
		}
		foreach($total_earning_last_two_weeks as $day_earning)
		{
			//wcds_var_dump($day_earning);
			$earning_per_day_last_two_weeks[$day_earning['date']] = $day_earning;
		}
		/*Format:
		
		["22/9/2015"]=>
		  array(4) {
			["order_total"]=>
			string(2) "11"
			["date"]=>
			string(9) "22/9/2015"
			["order_num"]=>
			string(1) "4"
			["orders_id"]=>
			string(11) "68,73,74,75"
		  }
		 */
		 //wcds_var_dump($earning_per_day);
		for($i = 0; $i<$max_days; $i++)
		{
			$current_day_date = date('j/n/Y', strtotime('-'.$i.' days'));
			
			//New
			$current_day = isset($earning_per_day_last_two_weeks[$current_day_date]) ? $earning_per_day_last_two_weeks[$current_day_date] : array('order_total' => 0, 'date' => $current_day_date, 'order_num' => 0, 'orders_id' => "");
			$weeks = array();
			for($j = 0; date('Y-m-d', strtotime($end_date.' - '.($i+$j).' days')) > $start_date ; $j+=7)
			{
				/* wcds_var_dump(date('Y-m-d', strtotime($end_date.' - '.($i+$j).' days')));
				wcds_var_dump($start_date);
				wcds_var_dump(date('Y-m-d', strtotime($end_date.' - '.($i+$j).' days')) > $start_date);
				wcds_var_dump($i+$j);
				wcds_var_dump(date('j/n/Y', strtotime($end_date.' - '.($i+$j).' days'))); */
				$weeks[date('j/n/Y', strtotime($end_date.' - '.($i+$j).' days'))] = !empty($earning_per_day[date('j/n/Y', strtotime($end_date.' - '.($i+$j).' days'))]) ? $earning_per_day[date('j/n/Y', strtotime($end_date.' - '.($i+$j).' days'))]["order_total"] : 0;
			}
			//Old
			/* 
			$current_day = $earning_per_day[$current_day_date];
			$week1 = !empty($earning_per_day[date('j/n/Y', strtotime('- '.($i+7).' days'))]) ? $earning_per_day[date('j/n/Y', strtotime('- '.($i+7).' days'))]["order_total"] : 0;
			$week2 = !empty($earning_per_day[date('j/n/Y', strtotime('- '.($i+14).' days'))]) ? $earning_per_day[date('j/n/Y', strtotime('- '.($i+14).' days'))]["order_total"] : 0;
			$week3 = !empty($earning_per_day[date('j/n/Y', strtotime('- '.($i+21).' days'))]) ? $earning_per_day[date('j/n/Y', strtotime('- '.($i+21).' days'))]["order_total"] : 0;;
			$week4 = !empty($earning_per_day[date('j/n/Y', strtotime('- '.($i+28).' days'))]) ? $earning_per_day[date('j/n/Y', strtotime('- '.($i+28).' days'))]["order_total"] : 0;
			*/
			
			if(empty($current_day))
			{
				$current_day['order_total'] = 0;
			}
			//Old
			//$current_day['avarage'] = $this->round(($week1 + $week2 + $week3 + $week4)/4, 2);
			//New
			$current_day['avarage'] = count($weeks) > 0 ? $this->round((array_sum($weeks))/count($weeks), 2) : 0;
			
			$current_day['order_total'] = $this->round($current_day['order_total'] , 2);
			$current_day['gain'] = $current_day['avarage'] > 0 ? $current_day['order_total']/$current_day['avarage'] : 0;
			$current_day['trend'] = 0;
			
			if($current_day['gain'] > 1.10 || $current_day['gain'] == 0)
				$current_day['trend'] = 1;
			elseif($current_day['gain'] < 0.9)
				$current_day['trend'] = -1;
				
			$final_result[$current_day_date] = $current_day;
			$final_result[$current_day_date]['label'] = date('D j', strtotime('-'.$i.' days'));
			$final_result[$current_day_date]['day'] = date('l', strtotime('-'.$i.' days'));
			
			unset($final_result[$current_day_date]['order_num']);
			unset($final_result[$current_day_date]['orders_id']);
		}
		/* Format:
		["2015/11/09"]=>
		  array(7) {
			["order_total"]=>
			string(3) "9.6"
			["date"]=>
			string(9) "9/11/2015"
			["order_num"]=>
			string(1) "5"
			["orders_id"]=>
			string(19) "652,658,661,667,670"
			["avarage"]=>
			float(2.7)
			["gain"]=>
			float(3.5555555555556)
			["trend"]=>
			int(1)
		  }
		  */
		/*wcds_var_dump($final_result); 
		 wcds_var_dump(date('Y-m-d', strtotime('-60 days')));*/
		$final_result = array_reverse($final_result);
		echo json_encode($final_result);
		wp_die();
	}
	public function ajax_get_avarages_and_estimations()
	{
		$first_order_date = $this->get_first_order_by_period();
		$avarage_per_month = $avarage_per_year = $avarage_per_day = $avarage_per_day_this_month = $total_today = $this_month_earning = $this_year_earning = 0;
		
		//Time
		$first_order_datetime = new DateTime( $first_order_date );
		$first_order_month = new DateTime( date('Y-m-01', strtotime($first_order_date) ));
		$first_order_year = new DateTime( date('Y-01-01', strtotime($first_order_date)));
		$now_dateime = new DateTime( "now" );
		//$tomorrow_dateime = new DateTime( "tomorrow" );
		$first_day_of_current_month_dateime = new DateTime(date('Y-m-01'));
		$day_intervel_from_biginning = $first_order_datetime->diff($now_dateime);
		$month_intervel_from_biginning = $first_order_month->diff($now_dateime);
		$year_intervel_from_biginning = $first_order_year->diff($now_dateime);
		$day_intervel_current_month = $first_day_of_current_month_dateime->diff($now_dateime);

		$number_of_days_from_first_order = $day_intervel_from_biginning->format('%d');
		$number_of_month_from_first_order = ($month_intervel_from_biginning->format('%y') * 12) + $month_intervel_from_biginning->format('%m');//$month_intervel_from_biginning->format('%m');
		$number_of_year_from_first_order = $year_intervel_from_biginning->format('%y');
		
		$number_of_hours_past_today = date('G');
		
		$number_of_days_from_month_beginning = $day_intervel_current_month->format('%d');
		$number_of_days_in_current_month = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
		
		$number_of_days_from_year_beginning = date('z');
		$first_year_day = new DateTime(date("Y-01-01"));
		$last_year_day = new DateTime(date("Y-12-31"));
		$number_of_days_in_current_year = $first_year_day->diff($last_year_day)->format("%a");
		//End time
		
		if($first_order_date != false)
		{
			$total_earning = $this->get_earnings_per_period('yearly', date('Y-m-d', strtotime($first_order_date)), date('Y-m-t'));
			$total_today = $this->get_earnings_per_period('daily', date('Y-m-d'), date('Y-m-d'));
			$this_month_earning = $this->get_earnings_per_period('monthly');
			$this_year_earning = $this->get_earnings_per_period('yearly',date('Y-01-01'), date('Y-m-d'));
			
			//$total_earning = !empty($total_earning) ? $total_earning[0]['order_total'] : 0;
			$total = 0;
			if(!empty($total_earning))
				foreach($total_earning as $year_earning)
						$total += $year_earning['order_total'];
			$total_earning = $total;
			$this_month_earning = !empty($this_month_earning) ? $this_month_earning[0]['order_total'] : 0;
			$total_today = !empty($total_today) ? $total_today[0]['order_total'] : 0;
			$this_year_earning = !empty($this_year_earning) ? $this_year_earning[0]['order_total'] : 0;
			
		/* 	wcds_var_dump($total_earning );
			wcds_var_dump($number_of_month_from_first_order ); */
			//wcds_var_dump($month_intervel_from_biginning['days']);
			
			$avarage_per_month = intval($number_of_month_from_first_order) > 0 ? $total_earning/$number_of_month_from_first_order : $total_earning;
			$avarage_per_year = intval($number_of_year_from_first_order) > 0 ? ($total_earning/$number_of_month_from_first_order)*12 /* $number_of_year_from_first_order */ : $total_earning;
			$avarage_per_day = intval($number_of_days_from_first_order) > 0 ? $total_earning/$number_of_days_from_first_order : $total_earning;
			$avarage_per_day_this_month = intval($number_of_days_from_month_beginning) > 0 ? $this_month_earning/$number_of_days_from_month_beginning : $this_month_earning;
		}
		
		echo json_encode(array('avarage_per_month' => $this->round($avarage_per_month,2), 
								'avarage_per_year' => $this->round($avarage_per_year,2), 
								'avarage_per_day' => $this->round($avarage_per_day,2), 
								'avarage_per_day_this_month' => $this->round($avarage_per_day_this_month,2),
								'total_today_earning' => $this->round($total_today,2),
								'total_month_earning' => $this->round($this_month_earning,2),
								'total_year_earning' => $this->round($this_year_earning,2),
								//'number_of_hours_to_tomorrow' =>$number_of_hours_to_tomorrow, 
								'number_of_hours_past_today' =>$number_of_hours_past_today, 
								'number_of_days_in_current_year' => $number_of_days_in_current_year,
								'number_of_days_in_current_month' =>$number_of_days_in_current_month,
								'number_of_days_from_month_beginning' =>$number_of_days_from_month_beginning,
								'number_of_days_from_year_beginning' =>$number_of_days_from_year_beginning
								/* 'number_of_days_left_to_year_end' =>$number_of_days_left_to_year_end */));
		wp_die();
	}
	public function ajax_get_earning_per_geograpic_area()
	{
		$range = isset($_POST['view_type']) ? $_POST['view_type'] : 'country'; //country, state, city
		$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
		$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
		$max_results_num = isset($_POST['max_results_num']) ? $_POST['max_results_num'] : null;
		$results = $this->get_earnings_per_geographic_area($range, $start_date,$end_date, $max_results_num);
		
		$countries_translator =  WC()->countries;
/*
		if ( function_exists( 'get_sites' ) && class_exists( 'WP_Site_Query' ) ) {
			$sites = get_sites();
			foreach ( $sites as $site ) {
				var_dump($site);
			}
			return;
		}*/

		foreach($results as $index => $stat)
		{
			$results[$index]['total_earning'] = $this->round($stat['total_earning'], 2);
			if($range == 'country')
			{
				$results[$index]['zone_name'] = isset($countries_translator->countries[ $stat['zone_name'] ]) ? $countries_translator->countries[ $stat['zone_name'] ] : "none";
				$results[$index]['zone_code'] =  !empty($stat['zone_name']) ? strtolower( $stat['zone_name']) : "none";
			}
		}
		
		
		usort($results, function($a, $b) {
				return $b['total_earning'] - $a['total_earning'];
			});
		
		if(count($results) >  $max_results_num)
			$results = array_slice($results, 0, $max_results_num);
		


		//	wcds_var_dump($results);
		
		
		echo json_encode($results);



		wp_die();
	}




	public function ajax_get_earning_per_geograpic_area_network()
	{
		$range = isset($_POST['view_type']) ? $_POST['view_type'] : 'country'; //country, state, city
		$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
		$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
		$max_results_num = isset($_POST['max_results_num']) ? $_POST['max_results_num'] : null;
		$results = $this->get_earnings_per_geographic_area_network($range, $start_date,$end_date, $max_results_num);
		
		$countries_translator =  WC()->countries;
/*
		if ( function_exists( 'get_sites' ) && class_exists( 'WP_Site_Query' ) ) {
			$sites = get_sites();
			foreach ( $sites as $site ) {
				var_dump($site);
			}
			return;
		}*/

		foreach($results as $index => $stat)
		{
			$results[$index]['total_earning'] = $this->round($stat['total_earning'], 2);
			if($range == 'country')
			{
				$results[$index]['zone_name'] = isset($countries_translator->countries[ $stat['zone_name'] ]) ? $countries_translator->countries[ $stat['zone_name'] ] : "none";
				$results[$index]['zone_code'] =  !empty($stat['zone_name']) ? strtolower( $stat['zone_name']) : "none";
			}
		}
		
		
		usort($results, function($a, $b) {
				return $b['total_earning'] - $a['total_earning'];
			});
		
		if(count($results) >  $max_results_num)
			$results = array_slice($results, 0, $max_results_num);
		


		//	wcds_var_dump($results);
		
		
		echo json_encode($results);



		wp_die();
	}



	
	public function ajax_get_refund_widget_get_refund_per_period()
	{
		$range = isset($_POST['view_type']) ? $_POST['view_type'] : 'daily';
		$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
		$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
		
		$stats = $this->get_refund_per_period($range, $start_date,$end_date);
		//wcds_var_dump($stats);
		echo json_encode($stats);
		wp_die();
	}
	public function ajax_get_earning_per_period()
	{
		$range = isset($_POST['view_type']) ? $_POST['view_type'] : 'daily';
		$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
		$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
		
		$stats = $this->get_earnings_per_period($range, $start_date,$end_date);
		$counter = 0;
		$totals = $order_num = $dates = $total_items ='';
		//wcds_var_dump($stats);
		foreach($stats as $stat_per_date)
		{
			if($counter > 0)
			{
				$dates .=",";
				$totals .=",";
				$order_num .=",";
				$total_items .=",";
			}
			if($range == 'daily')
			{
				 /*setlocale(LC_TIME, 'it_IT');
				$temp_date =  DateTime::createFromFormat('j/m/Y', $stat_per_date['date']);
				$dates .= strftime("%a - %d/%m", $temp_date->getTimestamp()); */
				
				$temp_date =  DateTime::createFromFormat('j/m/Y', $stat_per_date['date']);
				$dates .= $temp_date->format('D - d/m');
			}
			else
				$dates .= $stat_per_date['date']; //date($date_format ,strtotime($order_date))
			$totals .= $this->round($stat_per_date['order_total'], 2);
			$order_num .= $stat_per_date['order_num'];
			$total_items .= $this->get_quantities($stat_per_date['orders_id']); //$stat_per_date['total_items'];
			$counter++;
		}
		echo json_encode(array('dates'=>$dates, 'totals'=>$totals, 'order_num' => $order_num, 'total_items' => $total_items));
		wp_die();
	}


	public function ajax_get_listings_per_period()
	{
		$range = isset($_POST['view_type']) ? $_POST['view_type'] : 'daily';
		$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
		$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
		$isNetwork = isset($_POST['isNetwork']) ? $_POST['isNetwork'] : null;
	
		
		$stats = $this->get_listings_per_period($range, $start_date,$end_date, $isNetwork);
		$counter = 0;
		$order_num = $dates ='';
		//wcds_var_dump($stats);
		foreach($stats as $stat_per_date)
		{
			if($counter > 0)
			{
				$dates .=",";
				$order_num .=",";
			}
			if($range == 'daily')
			{
				 /*setlocale(LC_TIME, 'it_IT');
				$temp_date =  DateTime::createFromFormat('j/m/Y', $stat_per_date['date']);
				$dates .= strftime("%a - %d/%m", $temp_date->getTimestamp()); */
				
				$temp_date =  DateTime::createFromFormat('j/m/Y', $stat_per_date['date']);
				$dates .= $temp_date->format('D - d/m');
			}
			else
				$dates .= $stat_per_date['date']; //date($date_format ,strtotime($order_date))

			$order_num .= $stat_per_date['order_num'];
			$counter++;
		}
		echo json_encode(array('dates'=>$dates, 'order_num' => $order_num));
		wp_die();
	}


	public function ajax_get_payment_methods()
	{
		
		//paymnet -> post meta keys: _payment_method , _payment_method_title
		$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
		$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
		$max_results_num = isset($_POST['max_results_num']) ? $_POST['max_results_num'] : null;
		/* $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;
		$variation_id = isset($_POST['variation_id']) ? $_POST['variation_id'] : 'all';  */
		
		$stats = $this->get_earnings_per_payment_method(/* $product_id, $variation_id , */ $start_date,$end_date, $max_results_num );
		
		/* Format:
		array(1) {
			  [0]=>
			  array(4) {
				["total_earning"]=>
				string(4) "64.8"
				["total_purchases"]=>
				string(1) "8"
				["payment_method"]=>
				string(16) "Cash on Delivery"
				["payment_method_title"]=>
				string(16) "Cash on Delivery"
			  }
			}
			*/
		echo json_encode($stats);
		wp_die();
	}
	public function get_earnings_per_payment_method (/*$product_id, $variation_id ='all',  */$start_date = null, $end_date = null, $max_results_num = 'all')
	{
		global $wpdb, $wcps_option_model;
		$variation_join = "";
		$variation_where = "";
		$max_results_num = $max_results_num == 'all' ? "": " LIMIT ".$max_results_num;
		
		
		if((!isset($start_date) || $start_date == "") && (!isset($end_date) || $end_date == "")) 
		{ 
			$options = $wcps_option_model->get_default_time_period_widget_values();
			$wcds_default_time_period = $options['widget_default_time_period_payment_methods_stats'];
			switch($wcds_default_time_period)
			{
				case 'day' : $start_date = $end_date = date("Y-m-d");  break;
				case 'month' : $start_date = date('Y-m-01'); $end_date = date('Y-m-t'); ; break;
				case 'year' : $start_date = date('Y-01-01'); $end_date = date('Y-m-t'); ; break;
		
			}
		} 
		/* if($variation_id != 'all')
		{
			$variation_join = " INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS order_itemmeta_variation ON order_itemmeta_variation.order_item_id = order_items.order_item_id  ";
			$variation_where = "AND order_itemmeta_variation.meta_key = '_variation_id' AND order_itemmeta_variation.meta_value = {$variation_id} ";
		} */
		
		$query_addons = $this->get_orders_query_conditions_to_exclude_bad_orders();
		
		$wpdb->query('SET SQL_BIG_SELECTS=1');
		$query = "SELECT SUM(order_total.meta_value) AS total_earning, COUNT(orders.id) AS total_purchases, payment_method.meta_value AS payment_method, payment_method_title.meta_value AS payment_method_title
				  FROM {$wpdb->posts} AS orders ".
				  /* INNER JOIN {$wpdb->prefix}woocommerce_order_items AS order_items ON order_items.order_id = orders.ID 
				  INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS order_itemmeta ON order_itemmeta.order_item_id = order_items.order_item_id  */
				  "INNER JOIN {$wpdb->postmeta} AS payment_method ON payment_method.post_id = orders.ID
				  INNER JOIN {$wpdb->postmeta} AS payment_method_title ON payment_method_title.post_id = orders.ID
				  INNER JOIN {$wpdb->postmeta} AS order_total ON order_total.post_id = orders.ID {$variation_join} {$query_addons['join']} 
				  WHERE orders.post_type = 'shop_order' 
				  AND order_total.meta_key = '_order_total' 
				  AND payment_method.meta_key = '_payment_method' 
				  AND payment_method_title.meta_key = '_payment_method_title' ".
				  /* AND order_itemmeta.meta_key = '_product_id'
				  AND order_itemmeta.meta_value = {$product_id}  */ 
				  "AND orders.post_date >= '{$start_date} 00:00' 
				  AND orders.post_date <= '{$end_date} 23:59' {$variation_where} {$query_addons['where']}
				  GROUP BY  payment_method.meta_value  ORDER BY order_total.meta_value ASC {$max_results_num}";
			  
		return $wpdb->get_results($query, ARRAY_A);
	}
	public function get_refund_per_period($range_type = 'daily', $start_date = null, $end_date = null)
	{
		global $wpdb, $wcps_option_model;
		$group_by_string = "";
		$select_date_string = "";
		
		switch($range_type)
		{
			case 'yearly':$group_by_string = "YEAR(refund.post_date)"; 
						   $select_date_string = "YEAR(refund.post_date)";
						   break;
			case 'monthly': $group_by_string = "YEAR(refund.post_date), MONTH(refund.post_date)";   //YEAR(record_date), MONTH(record_date)
							 $select_date_string = "concat_ws('/', MONTH(refund.post_date), YEAR(refund.post_date))";
							break;
			case 'daily' :  $group_by_string = "MONTH(refund.post_date), DAY(refund.post_date)"; 
						    $select_date_string = "concat_ws('/', DAY(refund.post_date), MONTH(refund.post_date), YEAR(refund.post_date))";
							break;
		}
		if((!isset($start_date) || $start_date == "") && (!isset($end_date) || $end_date == ""))
		{ 
			$options = $wcps_option_model->get_default_time_period_widget_values();
			$wcds_default_time_period = $options['widget_default_time_period_sales_stats'];
			switch($wcds_default_time_period)
			{
				case 'day' : $start_date = $end_date = date("Y-m-d");  break;
				case 'month' : $start_date = date('Y-m-01'); $end_date = date('Y-m-t'); ; break;
				case 'year' : $start_date = date('Y-01-01'); $end_date = date('Y-m-t'); ; break;
		
			}
		} 
		
		$wpdb->query('SET SQL_BIG_SELECTS=1');
		$query = "SELECT refund.post_parent as order_id, refunded_amount.meta_value AS refunded_amount, refund_reason.meta_value AS refund_reason, {$select_date_string} AS refund_date".
				 " FROM {$wpdb->posts} AS refund
				  INNER JOIN {$wpdb->postmeta} AS refunded_amount ON refunded_amount.post_id = refund.ID 
				  INNER JOIN {$wpdb->postmeta} AS refund_reason ON refund_reason.post_id = refund.ID 
				  WHERE refund.post_type = 'shop_order_refund' 
				  AND refund_reason.meta_key = '_refund_reason' 
				  AND refunded_amount.meta_key = '_refund_amount' ".
				 // AND order_items.order_item_type = 'line_item' 
				 // AND order_itemmeta.meta_key = '_qty' 
				 " AND refund.post_date >= '{$start_date} 00:00' 
				  AND refund.post_date <= '{$end_date} 23:59'   
				  ORDER BY  refund.post_date";
				  

		$result = $wpdb->get_results($query, ARRAY_A);
		if(isset($result))
			foreach($result as $key => $refund)
			{
				$result[$key]['permalink'] = get_edit_post_link( $refund['order_id']);
			}	  
		return $result;
	}
	public function get_earnings_per_period($range_type = 'daily', $start_date = null, $end_date = null)
	{
		global $wpdb, $wcps_option_model;
		
		$group_by_string = "";
		$select_date_string = "";
		
		switch($range_type)
		{
			case 'yearly':$group_by_string = "YEAR(orders.post_date)"; 
						   $select_date_string = "YEAR(orders.post_date)";
						   break;
			case 'monthly': $group_by_string = "YEAR(orders.post_date), MONTH(orders.post_date)";   //YEAR(record_date), MONTH(record_date)
							 $select_date_string = "concat_ws('/', MONTH(orders.post_date), YEAR(orders.post_date))";
							break;
			case 'daily' :  $group_by_string = "MONTH(orders.post_date), DAY(orders.post_date)"; 
						    $select_date_string = "concat_ws('/', DAY(orders.post_date), MONTH(orders.post_date), YEAR(orders.post_date))";
							break;
		}
		if((!isset($start_date) || $start_date == "") && (!isset($end_date) || $end_date == ""))
		{ 
			$options = $wcps_option_model->get_default_time_period_widget_values();
			$wcds_default_time_period = $options['widget_default_time_period_sales_stats'];
			switch($wcds_default_time_period)
			{
				case 'day' : $start_date = $end_date = date("Y-m-d");  break;
				case 'month' : $start_date = date('Y-m-01'); $end_date = date('Y-m-t'); ; break;
				case 'year' : $start_date = date('Y-01-01'); $end_date = date('Y-m-t'); ; break;
		
			}
		} 
		$query_addons = $this->get_orders_query_conditions_to_exclude_bad_orders();
		
		$wpdb->query('SET SQL_BIG_SELECTS=1');
		$query = "SELECT SUM(order_total.meta_value) AS order_total, {$select_date_string} AS date, COUNT(orders.ID) AS order_num, GROUP_CONCAT(orders.ID) AS orders_id ".//, SUM(order_itemmeta.meta_value) AS total_items
				 " FROM {$wpdb->posts} AS orders
				  INNER JOIN {$wpdb->postmeta} AS order_total ON order_total.post_id = orders.ID ".
				 // INNER JOIN {$wpdb->prefix}woocommerce_order_items AS order_items ON order_items.order_id = orders.ID 
				 // INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS order_itemmeta ON order_itemmeta.order_item_id = order_items.order_item_id 
				 " {$query_addons['join']} 
				  WHERE orders.post_type = 'shop_order' 
				  AND order_total.meta_key = '_order_total' ".
				 // AND order_items.order_item_type = 'line_item' 
				 // AND order_itemmeta.meta_key = '_qty' 
				 " AND orders.post_date >= '{$start_date} 00:00' 
				  AND orders.post_date <= '{$end_date} 23:59'  {$query_addons['where']}
				  GROUP BY  ".$group_by_string;
		//	var_dump($query);

		//echo $query;


		return $wpdb->get_results($query, ARRAY_A);
	}




	public function get_listings_per_period($range_type = 'daily', $start_date = null, $end_date = null, $isNetwork = null)
	{
		global $wpdb, $wcps_option_model;
		$group_by_string = "";
		$select_date_string = "";

		
		switch($range_type)
		{
			case 'yearly':$group_by_string = "YEAR(listings.post_date)"; 
						   $select_date_string = "YEAR(listings.post_date)";
						   break;
			case 'monthly': $group_by_string = "YEAR(listings.post_date), MONTH(listings.post_date)";   //YEAR(record_date), MONTH(record_date)
							 $select_date_string = "concat_ws('/', MONTH(listings.post_date), YEAR(listings.post_date))";
							break;
			case 'daily' :  $group_by_string = "MONTH(listings.post_date), DAY(listings.post_date)"; 
						    $select_date_string = "concat_ws('/', DAY(listings.post_date), MONTH(listings.post_date), YEAR(listings.post_date))";
							break;
		}
		if((!isset($start_date) || $start_date == "") && (!isset($end_date) || $end_date == ""))
		{ 
			$options = $wcps_option_model->get_default_time_period_widget_values();
			$wcds_default_time_period = $options['widget_default_time_period_sales_stats'];
			switch($wcds_default_time_period)
			{
				case 'day' : $start_date = $end_date = date("Y-m-d");  break;
				case 'month' : $start_date = date('Y-m-01'); $end_date = date('Y-m-t'); ; break;
				case 'year' : $start_date = date('Y-01-01'); $end_date = date('Y-m-t'); ; break;
		
			}
		} 
		//$query_addons = $this->get_listings_query_conditions_to_exclude_bad_orders(); {$query_addons['where']}


			$wpdb->query('SET SQL_BIG_SELECTS=1');

			$query = "
			SELECT {$select_date_string} AS date, 
			COUNT(listings.ID) AS order_num, 
			GROUP_CONCAT(listings.ID) AS listings_id 

			FROM {$wpdb->posts} AS listings 

			WHERE listings.post_type = 'listing' 
			AND listings.post_date >= '{$start_date} 00:00' 
			AND listings.post_date <= '{$end_date} 23:59' 

			GROUP BY {$group_by_string}";

			
			$q = array ();
			
			/* query all network */
			if ($isNetwork){
				$sites = wp_get_sites();
				$table='';
				foreach ( $sites as $site ) {
					if($site['blog_id']=='1'){
						$table='';
					}else{
						$table=$site['blog_id'].'_';
					}
					$query = "
					SELECT {$select_date_string} AS date, 
					COUNT(listings.ID) AS order_num, 
					GROUP_CONCAT(listings.ID) AS listings_id 
					FROM ib_".$table."posts AS listings 
					WHERE listings.post_type = 'listing' 
					AND listings.post_date >= '{$start_date} 00:00' 
					AND listings.post_date <= '{$end_date} 23:59' 
					GROUP BY {$group_by_string}";
					//echo $query;

					$rows = $wpdb->get_results($query, ARRAY_A);
					foreach($rows as $row){ 
						$q[$row['date']]= $q[$row['date']]+$row['order_num'];
					}
				}
				$res= array();
				$i=0;
				foreach($q as $k => $val){
					$res[$i] = array('date' => $k, 'order_num' => $val);
					$i++;
				}
			//	var_dump($res);
			//	die();

				usort($res, function ($item1, $item2) {
					return $item1['date'] <=> $item2['date'];
				});
				
				return $res;
			}

			return $wpdb->get_results($query, ARRAY_A);
	}



	public function get_quantities($order_ids)
	{
		global $wpdb;
		$query = "SELECT SUM(order_itemmeta.meta_value) AS total_items 
				  FROM {$wpdb->prefix}woocommerce_order_itemmeta AS order_itemmeta
				  INNER JOIN {$wpdb->prefix}woocommerce_order_items AS order_items ON order_items.order_item_id = order_itemmeta.order_item_id
				  WHERE order_items.order_item_type = 'line_item'
				  AND order_itemmeta.meta_key = '_qty'	
				  AND order_items.order_id IN({$order_ids})";
		$result = $wpdb->get_col($query);
		return isset($result) ? $result[0]:0;
	}
	public function get_earnings_per_geographic_area($area_type = 'site', $start_date = null, $end_date = null, $max_results_num = 100)
	{
		global $wpdb, $wcps_option_model;

		
		$group_by_string = "";
		$select_zone_string = "";
		$geographic_join = "";
		$geographic_condition = "";

		$table_posts="{$wpdb->posts}";
		$table_post_meta="{$wpdb->postmeta}";
		
		switch($area_type)
		{
			case 'country':$group_by_string = " billing_country.meta_value "; 
						   $select_zone_string = " billing_country.meta_value ";
						   $geographic_join = " INNER JOIN #table_post_meta# AS billing_country ON billing_country.post_id = orders.ID  ";
						   $geographic_condition = " AND billing_country.meta_key = '_billing_country' ";
						   break;
			case 'state': $group_by_string = " billing_state.meta_value "; 
						  $select_zone_string = " billing_state.meta_value ";
						  $geographic_join = " INNER JOIN #table_post_meta# AS billing_state ON billing_state.post_id = orders.ID  ";
						  $geographic_condition = " AND billing_state.meta_key = '_billing_state' ";
						  break;
			case 'city': $group_by_string = " billing_city.meta_value  ";   
						 $select_zone_string = " billing_city.meta ";
						 $geographic_join = " INNER JOIN #table_post_meta# AS billing_city ON billing_city.post_id = orders.ID  ";
						 $geographic_condition = " AND billing_city.meta_key = '_billing_city' ";
					     break;
		}
		if((!isset($start_date) || $start_date == "") && (!isset($end_date) || $end_date == ""))
		{ 
			$options = $wcps_option_model->get_default_time_period_widget_values();
			$wcds_default_time_period = $options['widget_default_time_period_geo_stats'];
			switch($wcds_default_time_period)
			{
				case 'day' : $start_date = $end_date = date("Y-m-d");  break;
				case 'month' : $start_date = date('Y-m-01'); $end_date = date('Y-m-t'); ; break;
				case 'year' : $start_date = date('Y-01-01'); $end_date = date('Y-m-t'); ; break;
		
			}
		} 
		$query_addons = $this->get_orders_query_conditions_to_exclude_bad_orders();

		$wpdb->query('SET SQL_BIG_SELECTS=1');
		$select_zone_code = '';

		$zones = array();

	
			$query = "SELECT SUM(order_total.meta_value) AS total_earning, {$select_zone_string} as zone_name, COUNT(orders.id) AS total_purchases
			FROM ".$table_posts." AS orders
			INNER JOIN ".$table_post_meta." AS order_total ON order_total.post_id = orders.ID {$query_addons['join']} {$geographic_join}
			WHERE orders.post_type = 'shop_order' {$geographic_condition}
			AND order_total.meta_key = '_order_total' 
			AND orders.post_date >= '{$start_date} 00:00' 
			AND orders.post_date <= '{$end_date} 23:59'  {$query_addons['where']}
			GROUP BY  ".$group_by_string. "  ";//ORDER BY order_total.meta_value ASC LIMIT {$max_results_num}

			$query = str_replace('#table_post_meta#', $table_post_meta, $query);
			$zones = $wpdb->get_results($query, ARRAY_A);

			  
		return $zones;
	}






	public function get_earnings_per_geographic_area_network($area_type = 'site', $start_date = null, $end_date = null, $max_results_num = 100)
	{
		global $wpdb, $wcps_option_model;

		
		$group_by_string = "";
		$select_zone_string = "";
		$geographic_join = "";
		$geographic_condition = "";

		$table_posts="{$wpdb->posts}";
		$table_post_meta="{$wpdb->postmeta}";
		
		switch($area_type)
		{
			case 'country':$group_by_string = " billing_country.meta_value "; 
						   $select_zone_string = " billing_country.meta_value ";
						   $geographic_join = " INNER JOIN #table_post_meta# AS billing_country ON billing_country.post_id = orders.ID  ";
						   $geographic_condition = " AND billing_country.meta_key = '_billing_country' ";
						   break;
			case 'state': $group_by_string = " billing_state.meta_value "; 
						  $select_zone_string = " billing_state.meta_value ";
						  $geographic_join = " INNER JOIN #table_post_meta# AS billing_state ON billing_state.post_id = orders.ID  ";
						  $geographic_condition = " AND billing_state.meta_key = '_billing_state' ";
						  break;
			case 'city': $group_by_string = " billing_city.meta_value  ";   
						 $select_zone_string = " billing_city.meta ";
						 $geographic_join = " INNER JOIN #table_post_meta# AS billing_city ON billing_city.post_id = orders.ID  ";
						 $geographic_condition = " AND billing_city.meta_key = '_billing_city' ";
					     break;
		}
		if((!isset($start_date) || $start_date == "") && (!isset($end_date) || $end_date == ""))
		{ 
			$options = $wcps_option_model->get_default_time_period_widget_values();
			$wcds_default_time_period = $options['widget_default_time_period_geo_stats'];
			switch($wcds_default_time_period)
			{
				case 'day' : $start_date = $end_date = date("Y-m-d");  break;
				case 'month' : $start_date = date('Y-m-01'); $end_date = date('Y-m-t'); ; break;
				case 'year' : $start_date = date('Y-01-01'); $end_date = date('Y-m-t'); ; break;
		
			}
		}  
		$query_addons = $this->get_orders_query_conditions_to_exclude_bad_orders();

		$wpdb->query('SET SQL_BIG_SELECTS=1');
		$select_zone_code = '';
		$zone_coin = '';

		$zones = array();


			
			if ( function_exists( 'get_sites' ) && class_exists( 'WP_Site_Query' ) ) {
				$sites = get_sites();
				foreach ( $sites as $site ) {

					if($site->blog_id){

						$table_posts = "ib_".$site->blog_id."_posts";
						$table_post_meta = "ib_".$site->blog_id."_postmeta";
						
						

						if($area_type=='site'){
							$select_zone_string = "'".$site->blogname."'";
							$geographic_condition= '';
							$group_by_string= 'zone_name';
							$geographic_join='';
							$select_zone_code = "'".strtolower(get_blog_option($site->blog_id,'country_market'))."' AS zone_code, ";

							
						}

						switch_to_blog( $site->blog_id );
						$zone_coin = "'".get_woocommerce_currency_symbol()."' AS zone_coin, ";
						restore_current_blog();


						$query = "SELECT 
						SUM(order_total.meta_value) AS total_earning, 
						{$select_zone_string} as zone_name, 
						{$select_zone_code}
						{$zone_coin}
						COUNT(orders.id) AS total_purchases
						FROM ".$table_posts." AS orders
						INNER JOIN ".$table_post_meta." AS order_total ON order_total.post_id = orders.ID {$query_addons['join']} {$geographic_join}
						WHERE orders.post_type = 'shop_order' {$geographic_condition}
						AND order_total.meta_key = '_order_total' 
						AND orders.post_date >= '{$start_date} 00:00' 
						AND orders.post_date <= '{$end_date} 23:59'  {$query_addons['where']}
						GROUP BY  ".$group_by_string. "  ";//ORDER BY order_total.meta_value ASC LIMIT {$max_results_num}

						$query = str_replace('#table_post_meta#', $table_post_meta, $query);

						//echo $query;

						$aux = $wpdb->get_results($query, ARRAY_A);
						
				
						if(!$aux){
							$aux[] = array(
								'total_earning' => 0,
								'zone_name' => $site->blogname, 
								'zone_code' => strtolower(get_blog_option($site->blog_id,'country_market')), 
								'total_purchases' => 0,
								'zone_coin' => ''
							);
						}

						$zones = array_merge( $zones, $aux );

					}
				}
			}
	

		//print_r($zones);
			  
		return $zones;
	}





	public function get_first_order_by_period($period = 'year')
	{
		global $wpdb;
		$query = "SELECT orders.post_date 
				 FROM {$wpdb->posts} AS orders
				 WHERE orders.post_type = 'shop_order' 
				 ORDER BY orders.post_date ASC LIMIT 1";
		$result = $wpdb->get_results($query);
		return isset($result) && isset($result[0]) ? $result[0]->post_date : false;
	}
	public function get_orders_query_conditions_to_exclude_bad_orders($join_type = 'INNER')
	{
		global $wpdb, $wcps_option_model;
		$statuses = $this->get_order_statuses();
		$result = array();
		$result['join'] = "";
		$result['where'] = "";
		$result['version'] = $statuses['version'];
		if($statuses['version'] > 2.1)
		{
			//$result['statuses'] = $statuses['statuses'] = array_diff($statuses['statuses'], array('wc-cancelled', 'wc-refunded', 'wc-failed','wc-pending'));
			$result['statuses'] = $statuses['statuses'] = $wcps_option_model->get_order_statuses_to_consider_for_stats_computation();
			$result['where'] = " AND orders.post_status IN ('".implode( "','",$statuses['statuses'])."') ";
		}
		else 
		{
			$result['statuses'] = $statuses['statuses'] = array_diff($statuses['statuses'], array('cancelled', 'refunded', 'failed','pending'));
			$result['join'] = " {$join_type} JOIN {$wpdb->term_relationships} AS rel ON orders.ID=rel.object_id
							  {$join_type} JOIN {$wpdb->term_taxonomy} AS tax ON tax.term_taxonomy_id = rel.term_taxonomy_id
							  {$join_type} JOIN {$wpdb->terms} AS term ON term.term_id = tax.term_id ";
			$result['where'] .= " AND orders.post_status   = 'publish'
								 AND tax.taxonomy        = 'shop_order_status' 
								 AND term.slug           IN ( '" .implode( "','",$statuses['statuses']). "' )";
		}
		//wcds_var_dump($result['statuses']);
		return $result;
	}
	public function get_order_statuses()
	{
		
		$result = array();
		$result['statuses'] = array();
		if(function_exists( 'wc_get_order_statuses' ))
		{
			
			$result['version'] = 2.2;
			//[slug] => name
			$temp  = wc_get_order_statuses();
			foreach($temp as $slug => $title)
					array_push($result['statuses'], $slug);
		}
		else
		{
			$args = array(
				'hide_empty'   => false, 
				'fields'            => 'id=>slug', 
			);
			$result['version'] = 2.1;
			
			$temp = get_terms('shop_order_status', $args);
			foreach($temp as $id => $slug)
					array_push($result['statuses'], $slug);
		}
		return $result;
	}
}
?>