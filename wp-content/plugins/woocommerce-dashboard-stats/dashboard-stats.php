<?php 
/*
Plugin Name: WooCommerce Dashboard Widgets Stats
Description: Dashboard widgets thats help the shop admin to keep an eye on shop stats.
Author: Lagudi Domenico
Version: 5.4
*/

//define('WCDS_PLUGIN_URL', WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ) );
define('WCDS_PLUGIN_URL', rtrim(plugin_dir_url(__FILE__), "/") ) ;
define('WCDS_PLUGIN_ABS_PATH', dirname( __FILE__ ) );

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ||
     (is_multisite() && array_key_exists( 'woocommerce/woocommerce.php', get_site_option('active_sitewide_plugins') ))	
	)
{

	$wcds_id = 13541759;
	$wcds_name = "WooCommerce Dashboard Widgets Stats";
	$wcds_activator_slug = "wcds-activator";
	
	//com
	include_once( "classes/com/WCDS_Globals.php");
	require_once('classes/admin/WCDS_ActivationPage.php');
	
	
	add_action('admin_init', 'wcds_register_settings');
	add_action('init', 'wcds_init');
	add_action('admin_notices', 'wcds_admin_notices' );
	add_action('admin_menu', 'wcds_init_act');
	if(defined('DOING_AJAX') && DOING_AJAX)
		wcds_init_act();
	//add_action('wp', 'wcds_init');
}
function wcds_init()
{
	//$wcds_html_helper = new WCDS_Html(); 
	/* if(is_admin())
		wcds_init_act(); */
}
function wcds_setup()
{
	global $wcps_option_model, $wcds_customer_model, $wcds_expert_model, $wcds_order_model, $wcds_product_model, $wcds_html_helper, $wcps_dashboard_widgets ;
	//com
	if(!class_exists('WCDS_Option')) 
		require_once('classes/com/WCDS_Option.php');
	$wcps_option_model = new WCDS_Option();
	
	if(!class_exists('WCDS_Wpml'))
		require_once('classes/com/WCDS_Wpml.php');
	
	if(!class_exists('WCDS_Customer'))
		require_once('classes/com/WCDS_Customer.php');
	$wcds_customer_model = new WCDS_Customer();

	
	if(!class_exists('WCDS_Expert'))
		require_once('classes/com/WCDS_Expert.php');
	$wcds_expert_model = new WCDS_Expert();
	
	if(!class_exists('WCDS_Order'))
		require_once('classes/com/WCDS_Order.php');
	$wcds_order_model = new WCSD_Order();
	
	if(!class_exists('WCDS_Product'))
		require_once('classes/com/WCDS_Product.php');
	$wcds_product_model = new WCDS_Product();
	
	if(!class_exists('WCDS_Html'))
		require_once('classes/com/WCDS_Html.php');
	$wcds_html_helper = new WCDS_Html(); //moved to init to be sure that the options model has been inited
	
	//admin 
	if(!class_exists('WCDS_Dashboard'))
		require_once('classes/admin/WCDS_Dashboard.php');
	$wcps_dashboard_widgets = new WCDS_Dashboard();
	
	add_action('admin_menu', 'wcds_init_admin_panel');	
}
function wcds_admin_notices()
{
	global $wcds_notice, $wcds_name, $wcds_activator_slug;
	if($wcds_notice && (!isset($_GET['page']) || $_GET['page'] != $wcds_activator_slug))
	{
		 ?>
		<div class="notice notice-success">
			<p><?php echo sprintf(__( 'To complete the <span style="color:#96588a; font-weight:bold;">%s</span> plugin activation, you must verify your purchase license. Click <a href="%s">here</a> to verify it.', 'woocommerce-dashboard-stats' ), $wcds_name, get_admin_url()."admin.php?page=".$wcds_activator_slug); ?></p>
		</div>
		<?php
	}
}
function wcds_register_settings()
{
	load_plugin_textdomain('woocommerce-dashboard-stats', false, basename( dirname( __FILE__ ) ) . '/languages' );
	register_setting('wcds_options_group', 'wcds_options');
}
function wcds_init_act()
{
	global $wcds_activator_slug, $wcds_name, $wcds_id;
	new WCDS_ActivationPage($wcds_activator_slug, $wcds_name, 'woocommerce-dashboard-stats', $wcds_id, WCDS_PLUGIN_URL);
}
function wcds_init_admin_panel()
{ 
	$place = wcds_get_free_menu_position(56 , .1);
	$cap = 'manage_woocommerce';
	//add_menu_page( __('Dashboard', 'woocommerce-dashboard-stats'), __('Dashboard', 'woocommerce-dashboard-stats'), 'manage_woocommerce', 'wcps-dashboard-stats', 'wcds_load_bulk_editor_page', 'dashicons-tag', $place);
	//add_submenu_page('wcps-dashboard-stats',  __('Orders/Coupons finder', 'woocommerce-dashboard-stats'), __('Orders finder', 'woocommerce-dashboard-stats'), 'manage_woocommerce', 'woocommerce-dashboard-stats-orders-finder', 'wcds_load_orders_finder_page');
	 add_submenu_page('woocommerce', __('Dashboard Widget Stats Settings', 'woocommerce-dashboard-stats'), __('Dashboard Widget Stats Settings', 'woocommerce-dashboard-stats'), $cap, 'woocommerce-dashboard-stats-settings', 'wcds_load_settings_view');
}
function wcds_load_settings_view()
{
	if(!class_exists('WCDS_SettingsPage'))
	require_once('classes/admin/WCDS_SettingsPage.php');
	$wcds_setting_page = new WCDS_SettingsPage();
	$wcds_setting_page->render_page();
}
function wcds_load_orders_finder_page()
{
	/* if(!class_exists('wcds_Finder'))
		require_once('classes/admin/wcds_Finder.php');
	$orders_finder = new wcds_Finder();
	$orders_finder->render_page(); */
}
function wcds_get_free_menu_position($start, $increment = 0.3)
{
	foreach ($GLOBALS['menu'] as $key => $menu) {
		$menus_positions[] = $key;
	}

	if (!in_array($start, $menus_positions)) return $start;

	/* the position is already reserved find the closet one */
	while (in_array($start, $menus_positions)) {
		$start += $increment;
	}
	return $start;
}
function wcds_var_dump($var)
{
	echo "<pre>";
	var_dump($var);
	echo "</pre>";
}
?>