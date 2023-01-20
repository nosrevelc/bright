<?php 
class WCDS_Customer
{
	public function __construct()
	{
		if(is_admin())
		{
			add_action('wp_ajax_wcds_customers_widget_get_customers_per_period', array(&$this, 'ajax_get_customers_per_period') );
		}
	}
	public function ajax_get_customers_per_period()
	{
		$customers_num = isset($_POST['product_num']) ? $_POST['product_num'] : 10;
		$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
		$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
		
		$stats = $this->get_customers_per_period($customers_num, $start_date,$end_date);
		//wcds_var_dump($stats);
		/* Format:
			array(2) {
			  [0]=>
			  array(4) {
				["order_total"]=>
				string(4) "15.6"
				["order_num"]=>
				string(1) "2"
				["name"]=>
				string(8) "Domenico"
				["last_name"]=>
				string(6) "Lagudi"
				["customer_id"] =>
				int 1234123
			  }
			  */
		
		foreach($stats as $index => $customer)
		{
			$stats[$index]['total_spent'] = round($customer['total_spent'], 2);
			$stats[$index]['permalink'] = $customer['customer_id'] >0 ? get_edit_user_link($customer['customer_id']) : 'none';
		}
		echo json_encode($stats);
		wp_die();
	}
	public function get_customers_per_period($customers_num = 10, $start_date = null, $end_date = null)
	{
		global $wpdb, $wcds_order_model, $wcps_option_model;
		
		if((!isset($start_date) || $start_date == "") && (!isset($end_date) || $end_date == "")) 
		{ 
			$options = $wcps_option_model->get_default_time_period_widget_values();
			$wcds_default_time_period = $options['widget_default_time_period_customers_stats'];
			switch($wcds_default_time_period)
			{
				case 'day' : $start_date = $end_date = date("Y-m-d");  break;
				case 'month' : $start_date = date('Y-m-01'); $end_date = date('Y-m-t'); ; break;
				case 'year' : $start_date = date('Y-01-01'); $end_date = date('Y-m-t'); ; break;
		
			}
		} 
		
		$query_addons = $wcds_order_model->get_orders_query_conditions_to_exclude_bad_orders();
		
		$wpdb->query('SET SQL_BIG_SELECTS=1');
		$query = "SELECT SUM(total_spent.meta_value) AS total_spent, COUNT(orders.id) AS order_num, customer_user_id.meta_value AS customer_id, 
							customer_email.meta_value AS customer_email, customer_user_name.meta_value AS name, customer_user_last_name.meta_value AS last_name
				  FROM {$wpdb->posts} AS orders
				  INNER JOIN {$wpdb->postmeta} AS customer_user_id ON customer_user_id.post_id = orders.ID 
				  INNER JOIN {$wpdb->postmeta} AS customer_user_name ON customer_user_name.post_id = orders.ID 
				  INNER JOIN {$wpdb->postmeta} AS customer_user_last_name ON customer_user_last_name.post_id = orders.ID 
				  INNER JOIN {$wpdb->postmeta} AS customer_email ON customer_email.post_id = orders.ID 
				  INNER JOIN {$wpdb->postmeta} AS total_spent ON total_spent.post_id = orders.ID 
				  {$query_addons['join']} 
				  WHERE orders.post_type = 'shop_order' 
				  AND total_spent.meta_key = '_order_total' 
				  AND customer_user_id.meta_key = '_customer_user' 
				  AND customer_email.meta_key = '_billing_email' 
				  AND customer_user_name.meta_key = '_billing_first_name' 
				  AND customer_user_last_name.meta_key  = '_billing_last_name' ".
				  //AND customer_user_id.meta_value > 0 
				 " AND orders.post_date >= '{$start_date} 00:00' 
				  AND orders.post_date <= '{$end_date} 23:59'  {$query_addons['where']} ".
				 // GROUP BY customer_user_id.meta_value  ORDER BY SUM(total_spent.meta_value) DESC LIMIT ".$customers_num;
				 " GROUP BY customer_email.meta_value  ORDER BY SUM(total_spent.meta_value) DESC LIMIT ".$customers_num;

		/* wcds_var_dump($start_date);
		wcds_var_dump($end_date); */
		$result = $wpdb->get_results($query, ARRAY_A);
		//wcds_var_dump($result);
		$result = isset($result) && !empty($result) ? $result : array();
		
		return $result;
	}
	
}
?>