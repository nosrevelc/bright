<?php 
class WCDS_Option
{
	public function __construct(){}
	
	public function get_order_statuses_to_consider_for_stats_computation_for_settings_page()
	{
		$result = get_option('wcds_general_options');
		if($result == false || !isset($result['order_statuses_to_consider_for_stats_computation']) || empty($result['order_statuses_to_consider_for_stats_computation']))
		{
			$result['order_statuses_to_consider_for_stats_computation'] = array();
			$result['order_statuses_to_consider_for_stats_computation']['wc-processing'] = true;
			$result['order_statuses_to_consider_for_stats_computation']['wc-on-hold'] = true;
			$result['order_statuses_to_consider_for_stats_computation']['wc-completed'] = true;
		}
		
		return $result["order_statuses_to_consider_for_stats_computation"]; 
	}
	public function get_default_widget_values()
	{
		$result = get_option('wcds_general_options');
		
		$result_to_return['number_of_decimals'] = isset($result['number_of_decimals']) && $result['number_of_decimals'] != "" ? $result['number_of_decimals'] : get_option( 'woocommerce_price_num_decimals', 2 ) /* wc_get_price_decimals() */;
		$result_to_return['widget_default_value_sales_stats'] = isset($result['widget_default_value_sales_stats']) ? $result['widget_default_value_sales_stats']: "daily";
		$result_to_return['widget_default_value_refund_stats'] = isset($result['widget_default_value_refund_stats']) ? $result['widget_default_value_refund_stats']: "daily";
		$result_to_return['widget_default_value_products_stats'] = isset($result['widget_default_value_products_stats']) ? $result['widget_default_value_products_stats']: "";
		$result_to_return['widget_default_value_products_stats_show_variations'] = isset($result['widget_default_value_products_stats_show_variations']) ? $result['widget_default_value_products_stats_show_variations']: "no";
		$result_to_return['widget_default_value_customers_stats'] = isset($result['widget_default_value_customers_stats']) ? $result['widget_default_value_customers_stats']: "";
		$result_to_return['widget_default_value_payment_methods'] = isset($result['widget_default_value_payment_methods']) ? $result['widget_default_value_payment_methods']: "";
		$result_to_return['widget_default_value_geo_max_results'] = isset($result['widget_default_value_geo_max_results']) ? $result['widget_default_value_geo_max_results']: "";
		$result_to_return['widget_default_value_geo_view_type'] = isset($result['widget_default_value_geo_view_type']) ? $result['widget_default_value_geo_view_type']: "country";
		
		return $result_to_return; 
	}
	
	public function get_default_time_period_widget_values()
	{
		$result = get_option('wcds_general_options');
		
		$result_to_return['widget_default_time_period_geo_stats'] = isset($result['widget_default_time_period_geo_stats']) ? $result['widget_default_time_period_geo_stats']: "month";
		$result_to_return['widget_default_time_period_products_stats'] = isset($result['widget_default_time_period_products_stats']) ? $result['widget_default_time_period_products_stats']: "month";
		$result_to_return['widget_default_time_period_customers_stats'] = isset($result['widget_default_time_period_customers_stats']) ? $result['widget_default_time_period_customers_stats']: "month";
		$result_to_return['widget_default_time_period_sales_stats'] = isset($result['widget_default_time_period_sales_stats']) ? $result['widget_default_time_period_sales_stats']: "month";
		$result_to_return['widget_default_time_period_refund_stats'] = isset($result['widget_default_time_period_refund_stats']) ? $result['widget_default_time_period_refund_stats']: "day";
		$result_to_return['widget_default_time_period_payment_methods_stats'] = isset($result['widget_default_time_period_payment_methods_stats']) ? $result['widget_default_time_period_payment_methods_stats']: "month";
		
		return $result_to_return; 
	}
	public function get_user_roles_to_which_widgets_are_visible()
	{
		$result = get_option('wcds_general_options');
		if($result == false || !isset($result['user_roles_to_which_widgets_are_visible']) || empty($result['user_roles_to_which_widgets_are_visible']))
		{
			$result['user_roles_to_which_widgets_are_visible'] = array();
			$result['user_roles_to_which_widgets_are_visible']['shop_manager'] = true;
			$result['user_roles_to_which_widgets_are_visible']['administrator'] = true;
		}
		
		return $result["user_roles_to_which_widgets_are_visible"]; 
	}
	public function can_user_display_widget_by_roles($roles)
	{
		$options = get_option('wcds_general_options');
		$result = false;
		if($options == false || !isset($options['user_roles_to_which_widgets_are_visible']) || empty($options['user_roles_to_which_widgets_are_visible']))
		{
			$options['user_roles_to_which_widgets_are_visible'] = array();
			$options['user_roles_to_which_widgets_are_visible']['shop_manager'] = true;
			$options['user_roles_to_which_widgets_are_visible']['administrator'] = true;
		}
		
		foreach($options['user_roles_to_which_widgets_are_visible'] as $role_code => $value)
			if(in_array($role_code, $roles))	
				$result = true;
		
		return $result; 
	}
	public function get_order_statuses_to_consider_for_stats_computation()
	{
		$result = $this->get_order_statuses_to_consider_for_stats_computation_for_settings_page();
		$statuses = array();
		foreach($result as $status => $value)
			$statuses[] = $status;
			
		return $statuses;
	}
	public function save_general_options($options)
	{
		update_option('wcds_general_options', $options);
	}
	 public function get_dashboard_widget_options( $widget_id='' )
    {
        //Fetch ALL dashboard widget options from the db...
        $opts = get_option( 'dashboard_widget_options' );

        //If no widget is specified, return everything
        if ( empty( $widget_id ) )
            return $opts;

        //If we request a widget and it exists, return it
        if ( isset( $opts[$widget_id] ) )
            return $opts[$widget_id];

        //Something went wrong...
        return false;
    }
	
	public function get_dashboard_widget_option( $widget_id, $option, $default=NULL ) 
	{
		$opts = $this->get_dashboard_widget_options($widget_id);

		//If widget opts dont exist, return false
		if ( ! $opts )
			return false;

		//Otherwise fetch the option or use default
		if ( isset( $opts[$option] ) && ! empty($opts[$option]) )
			return $opts[$option];
		else
			return ( isset($default) ) ? $default : false;

	}
	public function update_dashboard_widget_options( $widget_id , $args=array(), $add_only=false )
	{
		//Fetch ALL dashboard widget options from the db...
		$opts = get_option( 'dashboard_widget_options' );

		//Get just our widget's options, or set empty array
		$w_opts = ( isset( $opts[$widget_id] ) ) ? $opts[$widget_id] : array();

		if ( $add_only ) {
			//Flesh out any missing options (existing ones overwrite new ones)
			$opts[$widget_id] = array_merge($args,$w_opts);
		}
		else {
			//Merge new options with existing ones, and add it back to the widgets array
			$opts[$widget_id] = array_merge($w_opts,$args);
		}

		//Save the entire widgets array back to the db
		return update_option('dashboard_widget_options', $opts);
	}
}
?>