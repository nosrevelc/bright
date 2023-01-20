<?php 
class WCDS_Dashboard
{
	public function __construct()
	{

		 if(get_current_blog_id() != 1){
			add_action( 'wp_dashboard_setup', array( &$this, 'add_presale_metabox' ) );
		 }
		
		 add_action( 'wp_network_dashboard_setup', array( &$this, 'add_network_presale_metabox' ) ); /* edit here */

	}
	public function add_presale_metabox()
	{
		global $wcps_option_model, $current_user;
		
		$can_display = $wcps_option_model->can_user_display_widget_by_roles($current_user->roles);
		if($can_display/* current_user_can('manage_woocommerce') */)
		{
			wp_add_dashboard_widget( 'wcds-woocommerce-dashboard-days-comparison', __('Last 15 days comparison', 'woocommerce-dashboard-stats'), array( &$this, 'render_days_comparison_metabox' ));
			wp_add_dashboard_widget( 'wcds-woocommerce-dashboard-avarage-estimation', __('Averages & Estimations', 'woocommerce-dashboard-stats'), array( &$this, 'render_avarage_and_estimation_metabox' ));
			wp_add_dashboard_widget( 'wcds-woocommerce-dashboard-geographic', __('Geographical stats', 'woocommerce-dashboard-stats'), array( &$this, 'render_geographic_metabox' ));
			wp_add_dashboard_widget( 'wcds-woocommerce-dashboard-customers', __('Customers stats', 'woocommerce-dashboard-stats'), array( &$this, 'render_customers_metabox' ));
			
			wp_add_dashboard_widget( 'wcds-woocommerce-dashboard-experts', __('Experts stats', 'woocommerce-dashboard-stats'), array( &$this, 'render_experts_metabox' ));
			wp_add_dashboard_widget( 'wcds-woocommerce-dashboard-categories', __('Top Service Request Categories', 'woocommerce-dashboard-stats'), array( &$this, 'render_categories_metabox' ));
			wp_add_dashboard_widget( 'wcds-woocommerce-dashboard-business-details', __('Business Details', 'woocommerce-dashboard-stats'), array( &$this, 'render_business_details_metabox' ));
			
			wp_add_dashboard_widget( 'wcds-woocommerce-dashboard-referrals', __('Referral stats', 'woocommerce-dashboard-stats'), array( &$this, 'render_referrals_metabox' ));

			wp_add_dashboard_widget( 'wcds-woocommerce-dashboard-products', __('Products stats', 'woocommerce-dashboard-stats'), array( &$this, 'render_products_metabox' ));
			wp_add_dashboard_widget( 'wcds-woocommerce-dashboard-earnings', __('Sales stats', 'woocommerce-dashboard-stats'), array( &$this, 'render_earnings_metabox' ));
			wp_add_dashboard_widget( 'wcds-woocommerce-dashboard-refund', __('Refund stats', 'woocommerce-dashboard-stats'), array( &$this, 'render_refund_metabox' ));
			wp_add_dashboard_widget( 'wcds-woocommerce-dashboard-related', __('Payment methods stats', 'woocommerce-dashboard-stats'), array( &$this, 'render_payment_methods_metabox' ));
		
			wp_add_dashboard_widget( 'listings-dashboard-widget', __('Listing Stats', 'listing-dashboard-stats'), array( &$this, 'render_listing_stats_metabox' ));
			
		}
	}

	public function add_network_presale_metabox()
	{
		global $wcps_option_model, $current_user;
		
		$can_display = $wcps_option_model->can_user_display_widget_by_roles($current_user->roles);
		if($can_display/* current_user_can('manage_woocommerce') */)
		{
			wp_add_dashboard_widget( 'wcds-woocommerce-dashboard-geographic', __('Geographical Sub-Sites stats', 'woocommerce-dashboard-stats'), array( &$this, 'render_geographic_metabox_network' ));
			wp_add_dashboard_widget( 'listings-network-dashboard-widget', __('Listing Sub-Sites Stats', 'listing-dashboard-stats'), array( &$this, 'render_listing_stats_metabox_network' ));

		}
	}





	public function render_days_comparison_metabox()
	{
		global $wcds_html_helper;
		$wcds_html_helper->render_days_comparison_widget();
	
	}
	public function render_avarage_and_estimation_metabox()
	{
		global $wcds_html_helper;
		$wcds_html_helper->render_avarage_and_estimation_widget();
	
	}
	public function render_geographic_metabox()
	{
		global $wcds_html_helper;
		$wcds_html_helper->render_geographic_widget();
	}


	public function render_geographic_metabox_network()
	{
		global $wcds_html_helper;
		$wcds_html_helper->render_geographic_widget_network();
	}

	public function render_earnings_metabox()
	{
		global $wcds_html_helper;
		$wcds_html_helper->render_earnings_widget();
	}
	public function render_refund_metabox()
	{
		global $wcds_html_helper;
		$wcds_html_helper->render_refund_widget();
	}
	public function render_products_metabox()
	{
		global $wcds_html_helper;
		$wcds_html_helper->render_products_widget();
	}
	public function render_customers_metabox()
	{
		global $wcds_html_helper;
		$wcds_html_helper->render_customers_widget();
	}


	public function render_experts_metabox()
	{
		global $wcds_html_helper;
		$wcds_html_helper->render_experts_widget();
	}
	public function render_categories_metabox()
	{
		global $wcds_html_helper;
		$wcds_html_helper->render_categories_widget();
	}
	public function render_business_details_metabox()
	{
		global $wcds_html_helper;
		$wcds_html_helper->render_business_details_widget();
	}
	public function render_referrals_metabox()
	{
		global $wcds_html_helper;
		$wcds_html_helper->render_referrals_widget();
	}

	


	public function render_payment_methods_metabox()
	{
		global $wcds_html_helper;
		$wcds_html_helper->render_payment_methods_metabox();
	}

	public function render_listing_stats_metabox()
	{
		global $wcds_html_helper;
		$wcds_html_helper->render_listing_stats_widget();
	}
	public function render_listing_stats_metabox_network()
	{
		global $wcds_html_helper;
		$wcds_html_helper->render_listing_stats_widget('network');
	}

	
}
?>