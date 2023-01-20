<?php
/**
 * Data Analytics
 *
 * @since 1.4
 *
 * @Class SPWAP_DataAnalytics()			Add all features of Data Analytics
 *      @function
 */
// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
  * Features of Data Analytics
 */
class SPWAP_DataAnalytics
{
	 /**
     * Get the unique instance of the class
     *
     * @var SPWAP_AppShortcut
     */
    private static $_instance;
    /**
     * Get the unique instance of the class
     *
     * @var settings
     */
    /**
     * Constructor
     */
    public function __construct()
    {
        if (is_admin()) {
            add_action('admin_menu', array($this, 'data_analytics_sub_menu'));
            add_action( 'admin_enqueue_scripts', array( $this, 'data_analytics_admin_enqueue' ) );
            add_action( 'superpwa_addon_activated_data_analytics', array( $this, 'activate_data_analytics' ) );
        }else{
        	add_action('wp_enqueue_scripts', array( $this, 'data_analytics_load_frontend_script' ));
        }
    }
    /**
     * Gets an instance of our SPWAP_DataAnalytics class.
     *
     * @return SPWAP_DataAnalytics Object
     */
    public static function get_instance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    /**
     * Initiate  table creation in db
     *
     * @since 1.5
     */

    public static function activate_data_analytics(){
		$obj = new self();
		$obj->data_analytics_create_tables();
	}
	/**
     * Creates spwap_data_analytics_overall table
     *
     * @since 1.5
     */

	public function data_analytics_create_tables(){

		global $wpdb;

		$charset = 'CHARSET=utf8mb4';
        $collate = 'COLLATE=utf8mb4_unicode_ci';

        if ( defined('DB_COLLATE') && DB_COLLATE )  {
            $charset = DB_CHARSET;
            $collate = DB_COLLATE;
        }

        $charset_collate = $charset . ' ' . $collate;

        $table_schema = [

            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}spwap_data_analytics_overall` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `record_date` date NOT NULL,
                `total_installed` int(255) DEFAULT NULL,
                `other_data` text,
                `created_at` datetime NOT NULL,
                PRIMARY KEY (`id`)
            ) $charset_collate;",

            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}spwap_analytics_data` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `data` text,
                `client` varchar(200) NOT NULL DEFAULT '',
                `os` varchar(200) NOT NULL DEFAULT '',
                `created_at` datetime NOT NULL,
                PRIMARY KEY (`id`)
            ) $charset_collate;",

        ];


        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        foreach ( $table_schema as $table ) {
            dbDelta( $table );
            //var_dump( $table);die; 
        }




	}

    /**
     * Add sub-menu page for app shortcut
     *
     * @since 1.5
     */
    public function data_analytics_sub_menu()
    {
        
        // data analytics sub-menu
        add_submenu_page(
            'superpwa',
            __('Super Progressive Web Apps Pro', 'super-progressive-web-apps-pro'),
            __('Data Analytics', 'super-progressive-web-apps-pro'),
            'manage_options',
            'superpwa-data-analytics',
            array($this, 'superpwa_data_analytics_interface_render')
        );
    }

    /**
	 * Data Analytics UI renderer
	 *
	 * @since 1.5
	 */ 
	public function superpwa_data_analytics_interface_render()
	{
		// Authentication
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$dataValue = $this->get_current_data(); 
			$todayData = isset($dataValue[0])? $dataValue[0] : array();
			$yesterdayData = isset($dataValue[1])? $dataValue[1] : array();
			$all7daysdownloads = 0;
			if(isset($dataValue) && $dataValue){
				foreach ($dataValue as $key => $value) {
					$all7daysdownloads += $value['total_installed'];
				}
			}

			global $wpdb;
			$overAllselectQuery = $wpdb->get_results(
					"SELECT sum(total_installed) as overall FROM `{$wpdb->prefix}spwap_data_analytics_overall` order by `record_date` desc"
					, ARRAY_A) ;
			$overallCount = 0;
			if(isset($overAllselectQuery[0]['overall'])){
				$overallCount = $overAllselectQuery[0]['overall'];
			}

			//Analytics
			$analyticsSelect = array(
							'7_days'=> 'Last 7 days',
							'month' =>'Last Month',
							'3_month'=> 'Last 3 Month',
							'1_year'=> 'Last 1 Year',
							'all_time'=> 'All Time'
								);
			$analyticsSetData = isset($_GET['spwap-data-analytic']) && !empty($_GET['spwap-data-analytic'])? $_GET['spwap-data-analytic'] : '7_days';

			$graphData = $this->super_pwa_analyticsData($analyticsSetData);

			$addon_utm_tracking = superpwa_get_addons( 'data_analytics' );

           // Menu Bar Styles
        if(function_exists('superpwa_setting_tabs_styles')){
            superpwa_setting_tabs_styles();
         }

		?>
			<div class='spwap-da-wrap wrap'>
				<h1><?php _e('User Data Analytics', 'super-progressive-web-apps-pro'); ?> <small>(<a href="<?php echo esc_url($addon_utm_tracking['link']) . '?utm_source=superpwa-plugin&utm_medium=utm-tracking-settings'?>"><?php echo esc_html__( 'Docs', 'super-progressive-web-apps' ); ?></a>)</small></h1>
				<hr/>
				<?php 
                 // Menu Bar Html
                 if(function_exists('superpwa_setting_tabs_html')){
                    superpwa_setting_tabs_html(); 
                 }
            	?>
					<h4>DOWNLOADS HISTORY</h4>
				<div class="spwap-history-wrap">
					<table id="spwap-download-history" class="spwap-download-history">
					   <tbody>
					   	<tr>
					   		<th colspan="2" style="text-align: center;font-weight: bold">Recent Installs</th>
					   	</tr>
					      <tr>
					         <th scope="row">Today</th>
					         <td><?php echo isset($todayData['total_installed'])? $todayData['total_installed']: 0; ?></td>
					      </tr>
					      <tr>
					         <th scope="row">Yesterday</th>
					         <td><?php echo isset($yesterdayData['total_installed'])? $yesterdayData['total_installed'] : 0;  ?></td>
					      </tr>
					      <tr>
					         <th scope="row">Last 7 Days</th>
					         <td><?php echo $all7daysdownloads; ?></td>
					      </tr>
					      <tr>
					         <th scope="row">All time</th>
					         <td><?php echo $overallCount; ?></td>
					      </tr>
					   </tbody>
					</table>
				</div>
				<hr/>
				<?php if(isset($graphData['y-axis']) && !empty($graphData['y-axis'])) { ?>
				<div class="spwap-data-analytics-chart-wrap">
					<h4>DOWNLOADS Graph</h4>
					<form method="get" action="<?php echo admin_url('admin.php?page=superpwa-data-analytics&'); ?>">
						<input type="hidden" name="page" value="superpwa-data-analytics">
						<label>Show data</label>
						<select name="spwap-data-analytic" onchange="this.form.submit()">
							<?php
							if($analyticsSelect){
								foreach ($analyticsSelect as $key => $value) {
									$sel = '';
									if($analyticsSetData==$key){
										$sel = "selected";
									}
									echo "<option value='".$key."' ".$sel.">".$value."</option>";
								}
							}
							?>
						</select>
					</form>

					<div style="width:80%;" class="spwap-graph">
						<canvas id="canvas"></canvas>
					</div>
				</div>
			<?php } ?>

			</div>
			<script type="text/javascript">
		var config = {
			type: 'line',
			data: {
				labels: [<?php echo $graphData['x-axis']; ?>],
				datasets: [{
					label: '',//'Total installed',
					backgroundColor: '#f1f1f1',//'#4dc9f6',
					borderColor: '#4dc9f6',//'#4dc9f6',
					data: [<?php echo $graphData['y-axis']; ?>],
					fill: false,
				}
				]
			},
			options: {
				responsive: true,
				title: {
					display: true,
					text: '<?php echo $graphData['install_count']; ?> SuperPWA Installation in - <?php echo $analyticsSelect[$analyticsSetData]?>'
				},
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Time line'
						}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Installs'
						}
					}]
				}
			}
		};

		window.onload = function() {
			var ctx = document.getElementById('canvas').getContext('2d');
			window.myLine = new Chart(ctx, config);

			var spwa_ul = document.getElementById('toplevel_page_superpwa');
		       var links = spwa_ul.getElementsByTagName('a');
		       for (var i = 0; i<links.length; i++){  
		            var all_links = links[i].href;
		            if(all_links.indexOf("page=superpwa-addons")>-1){
		            links[i].parentNode.classList.add("current");
		            } 
        		}

		};

		</script>

			<?php 
		   // Newsletter Form HTML
	        if(function_exists('superpwa_newsletter_form')){
					superpwa_newsletter_form(); 
			}
			
			?>

		<?php
	}

	public function get_current_data()
	{
			global $wpdb;
			$todaydate = date("Y-m-d");

			$dateObj = new DateTime();
			$dateObj->sub(new DateInterval('P7D'));
			$sevendaysdate = $dateObj->format('Y-m-d');

			$selectQuery = $wpdb->get_results($wpdb->prepare( 
					"SELECT * FROM `{$wpdb->prefix}spwap_data_analytics_overall` where `record_date` between '%s' and '%s' order by `record_date` desc", 
						 $sevendaysdate, $todaydate
					), ARRAY_A) ;

			$sevendaysData = $selectQuery;
			return $sevendaysData;
	}

	public function super_pwa_analyticsData($getdatatype='7_days'){
			global $wpdb;
			$total_install_count = 0;
			switch ($getdatatype) {
				case '7_days':

					$col[] = date('l');
					$sevendaysdate = $todaydate = date('Y-m-d');
					$allDaysdate = array($todaydate=>'0');
					for($i=1;$i<=7;$i++) 
					{
					    $date = new DateTime(); 
					    $date->sub(new DateInterval('P'.$i.'D')); 
					    $col[] = $date->format('l');
					    $allDaysdate[$date->format('Y-m-d')] = 0;
					    if($i==7){
					    	$sevendaysdate = $date->format('Y-m-d');
					    }
					}
					$dataYaxis = array();

					$selectQuery = $wpdb->get_results($wpdb->prepare( 
					"SELECT * FROM `{$wpdb->prefix}spwap_data_analytics_overall` where `record_date` between '%s' and '%s' order by `record_date` asc", 
						 $sevendaysdate, $todaydate
					), ARRAY_A) ;
					foreach ($selectQuery as $key => $value) {
						/*$dataYaxis[] = $value['total_installed'];*/
						$total_install_count += $value['total_installed'];
						$allDaysdate[$value['record_date']] = $value['total_installed'];
					}
					$dataYaxis = $allDaysdate;

					break;
				case 'month':

					$col[] = date('l');
					$todaydate = date('Y-m-d');
					$allDaysdate = array($todaydate=>'0');
					for($i=1;$i<=31;$i++) 
					{
					    $date = new DateTime(); 
					    $date->sub(new DateInterval('P'.$i.'D')); 
					    $col[] = $date->format('l');
					    $allDaysdate[$date->format('Y-m-d')] = 0;
					    if($i==31){
					    	$monthdaysdate = $date->format('Y-m-d');
					    }
					}
					$dataYaxis = array();

					$selectQuery = $wpdb->get_results($wpdb->prepare( 
					"SELECT * FROM `{$wpdb->prefix}spwap_data_analytics_overall` where `record_date` between '%s' and '%s' order by `record_date` desc", 
						 $monthdaysdate, $todaydate
					), ARRAY_A) ;
					foreach ($selectQuery as $key => $value) {
						/*$dataYaxis[] = $value['total_installed'];*/
						$total_install_count +=  $value['total_installed'];
						$allDaysdate[$value['record_date']] = $value['total_installed'];
					}
					$dataYaxis = $allDaysdate;

					break;
				case '3_month':

					
					$todaydate = date('Y-m-d');
					for($i=1;$i<=90;$i++) 
					{
					    $date = new DateTime(); 
					    $date->sub(new DateInterval('P'.$i.'D')); 
					    $col['Week '.$date->format('W')] = 'Week '.$date->format('W');//$date->format('l');
					    $allDaysdate[$date->format('W')] = 0;
					    if($i==90){
					    	$sevendaysdate = $date->format('Y-m-d');
					    }
					}
					
					$dataYaxis = array();
					$selectQuery = $wpdb->get_results($wpdb->prepare( 
					"SELECT WEEK(`record_date`) weeknumber, sum(`total_installed`) as total_installed FROM `{$wpdb->prefix}spwap_data_analytics_overall` where `record_date` between '%s' and '%s' GROUP BY weeknumber", 
						 $sevendaysdate, $todaydate
					), ARRAY_A) ;
					foreach ($selectQuery as $key => $value) {
						/*$dataYaxis[] = $value['total_installed'];*/
						$total_install_count += $value['total_installed'];
						$allDaysdate[$value['weeknumber']] = $value['total_installed'];
					}
					$dataYaxis = $allDaysdate;

					break;
				case '1_year':

					
				$todaydate = date('Y-m-d');
					for($i=0;$i<=12;$i++) 
					{
					    $date = new DateTime(); 
					    //print_r($date->format('M'));die;	
					    $date->sub(new DateInterval('P'.$i.'M')); 
					    $col['MONTH '.$date->format('M').' '.$date->format('Y')] = $date->format('M').' '.$date->format('Y');//$date->format('l');
					    $allDaysdate[$date->format('M').' '.$date->format('Y')] = 0;
					    if($i==12){
					    	$yearsdate = $date->format('Y-m-d');
					    }
					}
	
					
					
					$dataYaxis = array();
					$selectQuery = $wpdb->get_results($wpdb->prepare( 
					"SELECT YEAR(`record_date`) yearnumber,  MONTH(`record_date`) monthnumber, sum(`total_installed`) as total_installed FROM `{$wpdb->prefix}spwap_data_analytics_overall` where `record_date` between '%s' and '%s' GROUP BY monthnumber", 
						 $yearsdate, $todaydate
					), ARRAY_A) ;
					
					foreach ($selectQuery as $key => $value) {
						/*$dataYaxis[] = $value['total_installed'];*/
						$total_install_count += $value['total_installed'];
                        $dateObj   = DateTime::createFromFormat('!m', $value['monthnumber']);
                        $monthName = $dateObj->format('M');
						$allDaysdate[$monthName.' '.$value['yearnumber']] = $value['total_installed'];
					}
					$dataYaxis = $allDaysdate;

					break;
				case 'all_time':
        
					$dataYaxis = $col = array();
					$selectQuery = $wpdb->get_results($wpdb->prepare( 
					"SELECT YEAR(`record_date`) yearnumber, sum(`total_installed`) as total_installed FROM `{$wpdb->prefix}spwap_data_analytics_overall` GROUP BY yearnumber"), ARRAY_A) ;
					
					foreach ($selectQuery as $key => $value) {
						$total_install_count += $value['total_installed'];
 
						$allDaysdate[$value['yearnumber']] = $value['total_installed'];

						$col[$value['yearnumber']] = $value['yearnumber'];//
					}

					if(count($col) == 1 || count($col) == 0){
					    $years	= empty($col) ? date('Y') : key($col);
					    $dateObj   = DateTime::createFromFormat('!Y', $years);
					    $dateObj->sub(new DateInterval('P1Y'));
					    
                        $prev_year = $dateObj->format('Y');
                        $allDaysdate[$prev_year] = 0;
						$col[$prev_year] = $prev_year;//$date->format('l');
						
						$dateObj->sub(new DateInterval('P1Y'));
						$prev_year = $dateObj->format('Y');
                        $allDaysdate[$prev_year] = 0;
						$col[$prev_year] = $prev_year;//$date->format('l');
                         
                       
					}
					$dataYaxis = $allDaysdate;

					break;
				default:
					# code...
					break;
			}

			return array(
					'x-axis'=> "'".implode("', '", array_reverse($col))."'",
					'y-axis'=>  implode(", ", array_reverse($dataYaxis)),
					'install_count' => $total_install_count,

				);
		}
		
  	//Backend Scripts
	public function data_analytics_admin_enqueue($hooks)
    {
        if (!in_array($hooks, array('superpwa_page_superpwa-data-analytics', 'super-pwa_page_superpwa-data-analytics')) && strpos($hooks, 'superpwa-data-analytics') == false ) {
            return false;
        }

        wp_enqueue_script('data-anaylytics-charts-script', SUPERPWA_PRO_PATH_SRC . '/assets/js/chart.bundle.min.js', array('superpwa-main-js'), SUPERPWA_PRO_VERSION, true);
		wp_enqueue_media();
        wp_enqueue_style('data-anaylytics-admin-style', SUPERPWA_PRO_PATH_SRC . '/assets/css/admin-data-analytics.css', array(), SUPERPWA_PRO_VERSION, 'all');
        wp_enqueue_style('data-anaylytics-charts-css', SUPERPWA_PRO_PATH_SRC . '/assets/css/chart.css', array(), SUPERPWA_PRO_VERSION, 'all');

    }

     //Frontend Scripts

    public function data_analytics_load_frontend_script()
    {
    	wp_enqueue_script( "superpwa_data_analytics_frontend_script", SUPERPWA_PRO_PATH_SRC . '/assets/js/data-analytics-frontend-script.js', array() , SUPERPWA_PRO_VERSION, true );
    	
		$scriptData = array(
						'ajax_url'=>admin_url('admin-ajax.php'),
						'nonce_csrf'=> wp_create_nonce('superpwa_data_analytics_nonce_handle'),
							);
		wp_localize_script('superpwa_data_analytics_frontend_script', 'SuperPwaAnalyticsData', $scriptData);
    }

}

// Ajax Handle to increase the table count
add_action("wp_ajax_superpwa_data_analytics_action_handle", "superpwa_data_analytics_action_handle");
add_action("wp_ajax_nopriv_superpwa_data_analytics_action_handle", "superpwa_data_analytics_action_handle");

function superpwa_data_analytics_action_handle(){

	if( wp_verify_nonce( $_POST['csrf'], 'superpwa_data_analytics_nonce_handle')){
		$osdetails = strtolower(sanitize_text_field($_POST['os']));
		$networkclintdetails = strtolower(sanitize_text_field($_POST['networkclient']));

		global $wpdb;
		
		$date = date('Y-m-d');
		$selectQuery = $wpdb->get_results($wpdb->prepare( 
					"SELECT * FROM `{$wpdb->prefix}spwap_data_analytics_overall` WHERE `record_date` = %s", 
					$date
					), ARRAY_A) ;
		$runupdate = false;
		$total_installed = 1;
		if(count($selectQuery)){
			$updated_data = $selectQuery[0];
			$updated_data['total_installed'] += 1;
			$total_installed = $updated_data['total_installed'];

			$otherdata = json_decode( $updated_data['other_data'], true );
			$runupdate = true;
		}else{
			$otherdata = array('windows'=>0, 'mac os'=>0, 'ios'=>0, 'android'=>0, 'linux'=> 0);
		}
		switch( $osdetails ){
			case 'windows':
				$otherdata['windows'] 	+= 1;
			break;
			case 'mac os':
				$otherdata['mac os']  	+= 1;
			break;
			case 'ios':
				$otherdata['ios'] 	  	+= 1;
			break;
			case 'android':
				$otherdata['android'] 	+= 1;
			break;
			case 'linux':
				$otherdata['linux'] 	+= 1;
			break;
		}

		

		if($runupdate){
			$response = $wpdb->query($wpdb->prepare( 
							"UPDATE `{$wpdb->prefix}spwap_data_analytics_overall` SET `total_installed`='%s',`other_data`='%s' WHERE record_date='%s'",
								$total_installed,
								json_encode($otherdata),
						        $date
						));
		}else{
			$response = $wpdb->query($wpdb->prepare( 
							"INSERT INTO `{$wpdb->prefix}spwap_data_analytics_overall`(`record_date`, `total_installed`, `other_data`, `created_at`) VALUES ( '%s', '%s', '%s', %s)
							",
						        $date,
								1,
								json_encode($otherdata),
								date('Y-m-d H:i:s')
						));
		}

		//Recard data
		$data = array('os'=> $osdetails, 'client'=>$networkclintdetails);
		$response = $wpdb->query($wpdb->prepare( 
							"INSERT INTO `{$wpdb->prefix}spwap_analytics_data` (`data`, `client`, `os`, `created_at`) VALUES ( '%s', '%s', '%s', '%s')
							",
						        json_encode($data),
								$osdetails,
								$networkclintdetails,
								date('Y-m-d H:i:s')
						));
		echo json_encode(array("status"=>200, 'message'=>'analytics updated'));
	}else{
		echo json_encode(array("status"=>502, 'message'=>'Invalid token provided'));
	}
	die;
}


function superpwapro_dataanalytics()
{
    return SPWAP_DataAnalytics::get_instance();
}
superpwapro_dataanalytics();