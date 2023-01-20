<?php 
class WCDS_Product
{
	public function __construct()
	{
		if(is_admin())
		{
			add_action('wp_ajax_wcds_products_widget_get_products_per_period', array(&$this, 'ajax_get_products_per_period') );
		}
	}
	public function ajax_get_products_per_period()
	{

		$product_num = isset($_POST['product_num']) ? $_POST['product_num'] : 10;
		$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
		$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
		$show_variations = isset($_POST['show_variations']) ? $_POST['show_variations'] : "no";
		
		$results = [];
		$stats = $this->get_products_per_period(100 /* $product_num */, $start_date, $end_date, $show_variations);
		
		/*Format:
		array(4) {
		  [0]=>
		  array(4) {
			["total_earning"]=>
			string(1) "4"
			["total_purchases"]=>
			string(1) "4"
			["prod_id"]=>
			string(2) "12"
			["prod_variation_id"]=>
			string(1) "0"
		  }
		  */
		$counter = 0;
		$wpml_helper = new WCDS_Wpml();
		//wcds_var_dump($stats);
		foreach($stats as /* $prod_id */$index => $product)
		{
			$prod_id = $product['prod_id'];
			$prod_variation_id = $product['prod_variation_id'];
			$stats[$index]['total_earning'] = round($product['total_earning'], 2);
			$stats[$index]['permalink'] = get_permalink( $prod_id );
			
			if($wpml_helper->wpml_is_active())
			{
				//wcds_var_dump($prod_id." -> ".$original_id);
				if(!$show_variations || $prod_variation_id == 0)
				{
					$original_id = $wpml_helper->get_original_id($prod_id);
					$product_temp = new WC_Product($original_id);
					$stats[$index]['prod_title'] = $product_temp->get_title( ) ;
				}
				else
				{
					$original_id = $wpml_helper->get_original_id($prod_variation_id);
					$stats[$index]['prod_title'] = $this->get_variation_complete_name($prod_variation_id);
					$stats[$index]['permalink'] = get_permalink( $original_id );
				}
				
				$stats[$index]['permalink'] = get_permalink( $original_id );
				$stats[$index]['stock_left'] =  $show_variations ? $this->get_stock($prod_id, $prod_variation_id) : $this->get_stock($prod_id);
				
				if(!isset($results[$original_id]))
				{
					//wcds_var_dump("new");
					$results[$original_id] = $stats[$index];
				}
				else
				{
					//wcds_var_dump("update");
					$results[$original_id]["total_purchases"] += $product["total_purchases"];
					$results[$original_id]["total_earning"] += $product["total_earning"];
					$results[$original_id]["total_earning"] = round($results[$original_id]['total_earning'], 2);
				}
			}
			else
			{
				$results[$index] = $stats[$index];
				if($show_variations && $prod_variation_id != 0)
				{
					$results[$index]['prod_title'] = $this->get_variation_complete_name($prod_variation_id);
				}
				else
				{
					$product_temp = new WC_Product($prod_id); //???
					$results[$index]['prod_title'] = $product_temp->get_title( ) ;
				}
				$results[$index]['stock_left'] =  $show_variations ? $this->get_stock($prod_id, $prod_variation_id) : $this->get_stock($prod_id);
			}
			
			if(++$counter == $product_num )
				break;
		} 
		
		usort($results, function($a, $b) {
				return $b['total_earning'] - $a['total_earning'];
			});
		
		echo json_encode($results);
		wp_die();
	}
	public function get_products_per_period($product_num = 10, $start_date = null, $end_date = null, $show_variations = 'yes')
	{
		global $wpdb, $wcds_order_model, $wcps_option_model;
		$ordered_result = $ordered_result_with_variations = [];
		if((!isset($start_date) || $start_date == "") && (!isset($end_date) || $end_date == "")) 
		{ 
			$options = $wcps_option_model->get_default_time_period_widget_values();
			$wcds_default_time_period = $options['widget_default_time_period_products_stats'];
			switch($wcds_default_time_period)
			{
				case 'day' : $start_date = $end_date = date("Y-m-d");  break;
				case 'month' : $start_date = date('Y-m-01'); $end_date = date('Y-m-t'); ; break;
				case 'year' : $start_date = date('Y-01-01'); $end_date = date('Y-m-t'); ; break;
		
			}
		} 			
		
		$query_addons = $wcds_order_model->get_orders_query_conditions_to_exclude_bad_orders();
		$group_by = "product_id.meta_value";
		if($show_variations == 'yes') 
			$group_by = "product_id.meta_value,product_variation.meta_value";
		
		$wpdb->query('SET SQL_BIG_SELECTS=1');
		$query = "SELECT SUM(product_total.meta_value) AS total_earning, product.post_title AS prod_title, SUM(product_quantity.meta_value) AS total_purchases, product_id.meta_value AS prod_id, product_variation.meta_value AS prod_variation_id
				  FROM {$wpdb->posts} AS orders
				  INNER JOIN {$wpdb->postmeta} AS order_total ON order_total.post_id = orders.ID				
				  INNER JOIN {$wpdb->prefix}woocommerce_order_items AS order_items ON order_items.order_id = orders.ID
				  INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS product_id ON product_id.order_item_id = order_items.order_item_id 
				  INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS product_variation ON product_variation.order_item_id = order_items.order_item_id
				  INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS product_quantity ON product_quantity.order_item_id = order_items.order_item_id
				  INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS product_total ON product_total.order_item_id = order_items.order_item_id
				  INNER JOIN {$wpdb->posts} AS product ON product.ID = product_id.meta_value {$query_addons['join']}
				  WHERE orders.post_type = 'shop_order' 
				  AND order_total.meta_key = '_order_total' 
				  AND product_id.meta_key = '_product_id' 
				  AND product_variation.meta_key = '_variation_id' 
				  AND product_quantity.meta_key = '_qty' 
				  AND product_total.meta_key = '_line_total' 
				  AND orders.post_date >= '{$start_date} 00:00' 
				  AND orders.post_date <= '{$end_date} 23:59'  {$query_addons['where']}
				  GROUP BY {$group_by} ORDER BY SUM(product_total.meta_value) DESC LIMIT ".$product_num;
				  
		
		$result = $wpdb->get_results($query, ARRAY_A);
		$result = isset($result) && !empty($result) ? $result : array();
		
		foreach($result as $product) 
		{
			$ordered_result[$product['prod_id']."-".$product['prod_variation_id']] = $product;
		} 
		if($show_variations != 'yes') 
		{
			foreach($ordered_result as $key => $product) 
			{
				if($product['prod_variation_id'] != 0)
				{
					if(!isset($ordered_result_with_variations[$product['prod_id']."-0"]))
					{
						$ordered_result_with_variations[$product['prod_id']."-0"] = array();
						$ordered_result_with_variations[$product['prod_id']."-0"]["total_earning"] = 0;
						$ordered_result_with_variations[$product['prod_id']."-0"]["total_purchases"] = 0;
						$ordered_result_with_variations[$product['prod_id']."-0"]["prod_title"] = 0;
						$ordered_result_with_variations[$product['prod_id']."-0"]["prod_id"] = $product["prod_id"];
						$ordered_result_with_variations[$product['prod_id']."-0"]["prod_variation_id"] =0;
						
					}
					
					$ordered_result_with_variations[$product['prod_id']."-0"]["total_earning"] += $product["total_earning"];
					$ordered_result_with_variations[$product['prod_id']."-0"]["total_purchases"] += $product["total_purchases"];
				}
				else
					$ordered_result_with_variations[$product['prod_id']."-0"] = $product;
			} 
			$ordered_result = $ordered_result_with_variations;
		}
		//wcds_var_dump($ordered_result);
		return $ordered_result;
	}
	public function get_variation_complete_name($variation_id)
	{
		$error = false;
		$variation = null;
		try
		{
			$variation = new WC_Product_Variation($variation_id);
		}
		catch(Exception $e){return false; $error = true;}
		if($error) //no longer executed
    		try
    		{
    			$error = false;
    			$variation = new WC_Product($variation_id);
    			return $variation->get_title();
    		}catch(Exception $e){$error = true;}
		
		if($error)
			return "";
		
		$product_name = $variation->get_title()." - ";	
		if($product_name == " - ")
			return false;
		$attributes_counter = 0;
		
		foreach($variation->get_variation_attributes( ) as $attribute_name => $value)
		{
			
			if($attributes_counter > 0)
				$product_name .= ", ";
			$meta_key = urldecode( str_replace( 'attribute_', '', $attribute_name ) ); 
			
			$product_name .= " ".wc_attribute_label($meta_key).": ".$value;
			$attributes_counter++;
		}
		return $product_name;
	}
	public function get_stock($product_id, $variation_id = 0)
	{
		global $wpdb;
		$product_to_search = $product_id;
		if($variation_id != 0)
			$product_to_search = $variation_id;
		
		$query = "SELECT product_stock_left.meta_value AS stock_left 
				 FROM {$wpdb->postmeta} AS product_stock_left
				 INNER JOIN {$wpdb->postmeta} AS manage_stock ON manage_stock.post_id = product_stock_left.post_id
				 WHERE product_stock_left.post_id = {$product_to_search} 
				 AND manage_stock.meta_key = '_manage_stock' 
				 AND manage_stock.meta_value = 'yes' 
				 AND product_stock_left.meta_key = '_stock' ";
				 
		$result = $wpdb->get_results($query, ARRAY_A);
		//wcds_var_dump($product_id, $variation_id);
		//wcds_var_dump($result);
		if(isset($result) && empty($result[0]) && $variation_id != 0)
		{
			$product_to_search = $product_id;
			$query = "SELECT product_stock_left.meta_value AS stock_left 
				 FROM {$wpdb->postmeta} AS product_stock_left
				 INNER JOIN {$wpdb->postmeta} AS manage_stock ON manage_stock.post_id = product_stock_left.post_id
				 WHERE product_stock_left.post_id = {$product_to_search} 
				 AND manage_stock.meta_key = '_manage_stock' 
				 AND manage_stock.meta_value = 'yes' 
				 AND product_stock_left.meta_key = '_stock' ";
			$result = $wpdb->get_results($query, ARRAY_A);
			//wcds_var_dump($result);
		}		
		return !isset($result) || empty($result[0]) ?   __("N/A", 'woocommerce-dashboard-stats'): intval($result[0]['stock_left']);
	}
	
}
?>