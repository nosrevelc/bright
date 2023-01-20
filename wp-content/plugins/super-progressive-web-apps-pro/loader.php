<?php
/**
 * Loads the plugin files
 *
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

if( ! defined('SUPERPWA_PATH_ABS')){
	add_action( 'admin_notices', 'superpwa_pro_parent_notice' );
}else{

	// Load admin
	if(is_admin()){
		add_action('admin_upgrade_license_page', 'superpwa_pro_upgrade_license_page');
		add_action('admin_enqueue_scripts', 'superpwa_pro_enqueue_scripts');
		add_action("wp_ajax_superpwa_pro_activate_license",'superpwa_pro_activate_license');
		add_action("wp_ajax_superpwa_pro_autocheck",'superpwa_pro_autocheck');
	}

	// Load bundled add-ons pro
	$active_addons = get_option( 'superpwa_active_addons', array() );
	if ( in_array('call_to_action', $active_addons) ) require_once( SUPERPWA_PRO_PATH_ABS . 'addons/call-to-action.php' );
	if ( in_array('android_apk_app_generator', $active_addons) ) require_once( SUPERPWA_PRO_PATH_ABS . 'addons/android-apk-app-generator.php' );
	if ( in_array('app_shortcut', $active_addons) ) require_once( SUPERPWA_PRO_PATH_ABS . 'addons/app_shortcut.php' );
	if ( in_array('data_analytics', $active_addons) ) require_once( SUPERPWA_PRO_PATH_ABS . 'addons/data-analytics.php' );
	if ( in_array('pre_loader', $active_addons) ) require_once( SUPERPWA_PRO_PATH_ABS . 'addons/pre-loader.php' );
	if ( in_array('qr_code_generator', $active_addons) ) require_once( SUPERPWA_PRO_PATH_ABS . 'addons/qr-code-generator.php' );


}

function superpwa_pro_parent_notice(){
	$class = 'notice notice-error';
	$message = esc_html__( 'Parent plugin Super Progressive Web Apps not activated.', 'super-progressive-web-apps-pro' );

	printf( '<div class="%1$s"><p>%2$s <a href="%3$s" class="button button-primary" target="__blank">%4$s</a></p></div>', esc_attr( $class ),  $message , esc_url('https://wordpress.org/plugins/super-progressive-web-apps/'), esc_html__('Click here', 'super-progressive-web-apps-pro') ); 
}

/**
 * Get page of license and show the status of license
 * @return null
 */
function superpwa_pro_upgrade_license_page(){
	$license = get_option("superpwa_pro_upgrade_license");
	$license_key_status = $license_key = '';
	if(isset($license['pro']['license_key'])){
		$license_key	= $license['pro']['license_key'];
		$replace = ''; for ($i=0; $i < strlen($license_key)-4; $i++) { $replace .= '*'; }
		$license_key = substr_replace($license_key, $replace, 0, strlen($license_key)-4);
		$license_key_status = $license['pro']['license_key_status'];
	}
  // $license_key  = $license['pro']['license_key'];
  // $license_key_status = $license['pro']['license_key_status'];

  if( $_GET['page'] == 'superpwa-upgrade' ) { ?>
    <?php
        if ( defined('SUPERPWA_PRO_VERSION') ) {
          wp_enqueue_script('superpwa-pro-admin', trailingslashit(SUPERPWA_PRO_PATH_SRC).'assets/js/admin.js', array('jquery'), SUPERPWA_PRO_VERSION, true);
          $array = array('security_nonce'=>wp_create_nonce('superpwa_pro_post_nonce'));
    wp_localize_script('superpwa-pro-admin', 'superpwa_pro_var', $array);
            $license_info = get_option("superpwa_pro_upgrade_license");
            if ( defined('SUPERPWA_PRO_PLUGIN_DIR_NAME') && !empty($license_info) ){
            $superpwa_pro_manager = SUPERPWA_PRO_PLUGIN_DIR_NAME.'/assets/inc/superpwa-pro-license-data.php';                
                if( file_exists($superpwa_pro_manager) ){
                    require_once $superpwa_pro_manager;
                    if( $_GET['page'] == 'superpwa' ) {
                wp_enqueue_style( 'superpwa-license-panel-css', SUPERPWA_PRO_PATH_SRC . '/assets/inc/css/superpwa-pro-license-data.css', array() , SUPERPWA_PRO_VERSION );
            }
        }
    }
} ?>
<h1>Super Progressive Web Apps <sup><?php echo SUPERPWA_VERSION; ?></sup></h1>
<?php
if( function_exists('superpwa_setting_tabs_styles') ){
  superpwa_setting_tabs_styles();
}
if( function_exists('superpwa_setting_tabs_html') ){
  superpwa_setting_tabs_html();
}
}
?>
	<style type="text/css">.superpwa_pwa-field label{width:20%}.superpwa_pwa-field .superpwa_pwa-right{width:100%}.super_pwa_-extension-block{margin:0 40px 20px 20px;background:#fff;box-shadow:0 0 1px #607d8b;padding:10px 40px 40px 40px}.super_pwa_-act-b{background: white;box-shadow: 0px 0px 1px #607d8b;height: auto;padding: 1px;display: flex;margin-top: 10px;margin-bottom: 20px;}.super_pwa_-ext-block{margin:0 40px 20px 20px;background:#fff;box-shadow:0 0 1px #607d8b}.expired{color:red;font-size:15px;right:6px;position:relative}span.lmsg.refresh-lib:hover{color:#ca4a1f}.super_pwa_-link{text-decoration:none}#refresh_license{cursor:pointer}.super_pwa_-act-b .lkact{margin:36px}.super_pwa__inner{float:left;height:auto;padding:5px 39px}.super_pwa__inner .lmsg{font-size:15px}.linfo-rmsg{margin-top:14px}.linfo-rmsg_top{cursor:pointer}.super_pwa__inner .lmsg .dashicons{margin-right:8px}.super_pwa__inner .a-ext-renew-btn{padding:0 15px 1px 15px;line-height:20px;min-height:20px}.super_pwa__inner a{color:#444;text-decoration:none}.super_pwa_icon{color:green}.deact-text{width:240px}.act-msg{margin-top:5px}.super_pwa__inner .super_pwa__title{font-weight:700;margin-bottom:15px;font-size:15px}.bor-right{border-right:1px solid #dadee0}.dashicons.spin{animation:dashicons-spin 2s infinite;animation-timing-function:linear}.hide{display:none}@keyframes dashicons-spin{0%{transform:rotate(0)}100%{transform:rotate(-360deg)}}@media screen and (max-width:800px){.super_pwa__inner{float:none}.super_pwa_-act-b{height:auto}}@media only screen and (min-width:838px) and (max-width:1246px){.super_pwa__inner .super_pwa__title{font-weight:700;margin-bottom:10px;font-size:12px;margin-top:1px}}@media (max-width:1254px){button#revoke_license{margin:6px}.super_pwa__inner .a-ext-renew-btn{margin:6px}}@media (max-width:768px){#wpbody-content{width:auto}}a#exp{border:1px solid #7e8993;color:#272a2d;background:#f3f5f6;padding:0 15px 1px 15px;line-height:20px;cursor:pointer;border-width:1px;border-style:solid;border-radius:3px}p#error_msg{font-size:14px}span.lmsg{cursor:pointer}.hider{visibility:hidden;border:1px solid #a9a9a9;position:relative;bottom:20px;right:9em;font-size:13px}span#lil_id{display:none}.lmsg2:hover+.hider{visibility:visible}.lmsg2{font-size:15px;float:left;cursor:default;}#superpwa_refresh_active,#superpwa_refresh_expired{position:relative;top:13px}button#exp{right:32px;bottom:21px}i.dashicons.dashicons-calendar-alt{float:left}a#exp{background:#4cb122;color:#fff;border-color:#4cb122;padding:0 15px 1px 15px;line-height:20px;cursor:pointer;border-width:1px;border-style:solid;border-radius:3px}a#extend{color:#72aee6;font-size:14px}span#ex_text{font-size:13px}span.view_doc:hover{color:#ca4a1f}span.atq:hover{color:#ca4a1f}#attnl{visibility:hidden}span.lmsg_2.refresh-lib:hover{color:#ca4a1f}div#super_pwa__innerborright_sec{padding:5px 0 15px 25px;margin-left:4px;position:relative}p.enter_license_key{color:#000;font-size:14px}.activating_license{width:25em;height:36px}a.renew_license_key{border:1px solid #7e8993;color:#272a2d;background:#f3f5f6;padding:1px 7px 2px 8px;line-height:20px;cursor:pointer;border-width:1px;border-style:solid;border-radius:3px;margin:0 -5em 0 10px}span.expired_main_inner{font-size:15px;float:left;cursor:default;right:4px;position:relative;color:red}#lifetime{margin:0 10px 0 10px}.activate-l-but{padding:3px 20px!important}#superpwa_pro_license {padding: 6px 0px;}

	</style>

          			<div class="super_pwa_-act-b">
          				<?php  if($license_key_status == 'active'){
          					$license_info = get_option("superpwa_pro_upgrade_license");
          					$license_key_status  = $license_status = $license_exp = $lmsg_top_id = $lil_id = $license_info_lifetime = $license_exp_inwords = '';

          					$license_exp = date('Y-m-d', strtotime($license_info['pro']['license_key_expires']));
										$license_exp_inwords = date('d-F-Y', strtotime($license_info['pro']['license_key_expires']));			
										$license_status = $license_info['pro']['license_key_message'];
							      if (isset($license_info['pro']['license_key'])) {
										$original_license_key = $license_info['pro']['license_key'];
							      }
										$license_info_lifetime = $license_info['pro']['license_key_expires'];
							      $license_info_paymentid = isset($license_info['pro']['license_key_payment_id']) ? $license_info['pro']['license_key_payment_id'] :'' ;

							      $exp_class_2 = 'renew_license_key';
										$today = date('Y-m-d');
										$exp_date = $license_exp;
										$date1 = date_create($today);
										$date2 = date_create($exp_date);
										$diff = date_diff($date1,$date2);
										$days = $diff->format("%a");
										if( $license_info_lifetime == 'lifetime' ){
											$days = 'Lifetime';
											if ($days == 'Lifetime') {
												$expire_msg = " Your License is Valid for Lifetime ";
											}
										}
										else if($today > $exp_date){
											$days = -$days;
										}
										if ($days<0) {
											$lmsg_top_id = 'id="lmsg_top_id"';
										}

										$renew = "yes";
										$lisense_k = $original_license_key;
										if( $license_info_lifetime == 'lifetime' ){
											$days = 'Lifetime';
											if ($days == 'Lifetime') {
												$expire_msg = " Your License is Valid for Lifetime ";
											}
										}
										else if($days>=0){
											$expire_msg = " Expires in ".intval($days)." days";
										}
										$exp_class = 'lmsg2';
							      $exp_class_in = 'lmsg2';
							      $superpwa_refresh = 'superpwa_refresh_active';
										$exp_id = '';
										if($days<0){
											$expire_msg = " Expired ";
											$exp_class = 'expired_main';
							        $exp_class_in = 'expired_main_inner';
											$exp_id = 'exp';
											$exp_class_2 = 'renew_expired_license';
							        $superpwa_refresh = 'superpwa_refresh_expired';
										}
										 
									if ($license_info_lifetime == 'lifetime' ) {
											$lil_id = 'lil_id';
											$exp_text = 'Your License is Valid for ';
											$license_exp_inwords = $license_info_lifetime;
							        $exp_id = 'lifetime';
									}
									else {
										$exp_text = 'Expire on '; 
									}
									?>
		<div class="super_pwa__inner bor-right" id="super_pwa__inner_main">
			<p class="super_pwa__title">License Key</p>
			<input type="text" class="regular-text deact-text" id="superpwa-license" name="superpwa_pro_data[license]" value="<?php echo $license_key; ?>">
			<input type="hidden" id="original_superpwa_license" name="superpwa_pro_data[license]" value="<?php echo $original_license_key; ?>">
			<input type="submit" value="Deactivate" data-status="inactive"   class="button button-normal" id="superpea_pro_activate_deactivate_plugin" >

			<?php if($days<0){
				$span_class = "dashicons dashicons-no super_pwa__pro_no";
				$color = 'color:red';
			}
			else{
				$span_class = "dashicons dashicons-yes super_pwa_pro_dashicon_yes";
				$color = 'color:green';
			} ?>

			<p class="act-msg">
				<span style="<?php echo $color;?>" class="<?php echo $span_class;?>"></span>
				<?php
				if($days<0){ ?>
					<span id="ex_text" style="color:red">Expired.</span>
					<a id="extend" href='https://superpwa.com/checkout/?edd_license_key=<?php echo $original_license_key;?>&download_id=<?php echo $license_info_paymentid; ?>' target="_blank" >Extend</a>
					License to receive further updates & support
					<?php }
					else { ?> License is active. You are receiving updates & support.<?php }?>
				</p>
		</div>

		<div class="super_pwa__inner bor-right" id="super_pwa__innerborright_sec">
			<p class="super_pwa__title">License Information</p>
			<div class="<?php echo esc_attr($exp_class);?>">
				<a href='https://superpwa.com/checkout/?edd_license_key=<?php echo $original_license_key;?>&download_id=<?php echo $license_info_paymentid; ?>' target="_blank" class="<?php echo $exp_class_2; ?>" id="<?php echo $exp_id; ?>">Renew</a>
				<i class="dashicons dashicons-calendar-alt"></i>
				 
				<span class="<?php echo $exp_class_in; ?>"><span id="attnl">__</span>  <?php echo esc_attr($expire_msg);?></span>
				 
				<span class="hider" id="<?php echo $lil_id;?>" ><?php echo $exp_text.$license_exp_inwords; ?></span>			 
				<input class="l_key" type="hidden" value="<?php echo $lisense_k;?>" name="l_key">
			</div>

			<div class="linfo-rmsg_refresh" id="<?php echo $superpwa_refresh; ?>">
				<input type="hidden" value="<?php echo $renew ;?>" id="renew_status">
				<span class="lmsg refresh-lib"><i class="dashicons dashicons-update-alt" id="superpwa_refresh_icon"></i> Refresh License Data</span>
			</div>

        <?php
        $trans_check = get_transient( 'superpwa_pro_autocheck' );
        if ( $days<=7 && $trans_check !== 'superpwa_pro_autocheck_value' ) {
        ?>
        <div class="linfo-refresh" id="superpwa_autorefresh"></div>
        <input type="hidden" value="<?php echo $days ;?>" id="remaining_days">
      <?php } ?>

		</div>

		<div class="super_pwa__inner">
			<p class="super_pwa__title">At Your Service</p>
			<div>
				<a href="https://superpwa.com/docs/" target="_blank"><span class="lmsg"><i class="dashicons dashicons-media-text"></i> <span class="view_doc">View Documentation</span></span></a>
			</div>
			<div class="linfo-rmsg">
				<a href="https://superpwa.com/contact/" target="_blank"><span class="lmsg"><i class="dashicons dashicons-businessman"></i> <span class="atq">Ask Technical Question</span></span></a>
			</div>
		</div>

		<?php }  else{ ?>
							<div class="lkact">
							<p class="enter_license_key">Enter Your License Key</p>
						<input type="text" class="activating_license" id="superpwa-license" name="superpwa_pro_data[license]" value="<?php $license_key; ?>">
							<input type="submit" value="Activate License" data-status="active"   class="button button-primary activate-l-but" id="superpea_pro_activate_deactivate_plugin" >
							<div class="error-message" style="display: none; color:red;font-size: 14px;margin-top: 6px;"></div>
							<div class="activate-message" style="display: none;color:green;font-size: 14px;margin-top: 6px;"></div>
							<div id="superpwa_refresh_license" style="margin-top: 15px;display: none;"><div><span class="lmsg refresh-lib"><i class="dashicons dashicons-update-alt spin" id="refresh_license_icon"></i> Please wait while we upgrade library</span></div></div>
						<?php } ?>
						<div id="refresh_license" style="margin-top: 15px;"></div>
			<div id="err_message" style="margin-top: 15px;"></div>
	 
					</div>
					<?php
				}

/**
 * Load javascript for upgrede page
 * @param  string $hook_suffix 	gives the page name
 * @return string 				add the script
 */
function superpwa_pro_enqueue_scripts( $hook_suffix ){
	if($hook_suffix=='superpwa_page_superpwa-upgrade' || $hook_suffix=='super-pwa_page_superpwa-upgrade'){
		wp_enqueue_script('superpwa-pro-admin', trailingslashit(SUPERPWA_PRO_PATH_SRC).'assets/js/admin.js', array('jquery'), SUPERPWA_PRO_VERSION, true);
    wp_enqueue_style( 'superpwa-license-panel-css', SUPERPWA_PRO_PATH_SRC . '/assets/inc/css/superpwa-pro-license-data.css', array() , SUPERPWA_PRO_VERSION );
		$array = array('security_nonce'=>wp_create_nonce('superpwa_pro_post_nonce'));
		wp_localize_script('superpwa-pro-admin', 'superpwa_pro_var', $array);
	}
}

/**
 * For Check license activate/deactivate the license
 * @return string 				return json of license status
 */
function superpwa_pro_activate_license(){
	if(!wp_verify_nonce($_POST['security_nonce'], 'superpwa_pro_post_nonce')){
		echo json_encode(array('status'=>500, 'message'=>'security nonce not verify'));
		die;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
         echo json_encode(array('status'=>500, 'message'=>'not authorized'));
         die;
    }
    $license_key      = sanitize_text_field($_POST['license_key']);
    $license_status   = sanitize_text_field($_POST['license_status']);
    $edd_action = 'activate_license';
    if($license_status =='active'){
       $edd_action = 'activate_license'; 
    }
    
    if($license_status =='inactive'){
       $edd_action = 'deactivate_license'; 
		$license = get_option("superpwa_pro_upgrade_license");
		$license_key	= $license['pro']['license_key'];
    }
    $api_params = array(
					'edd_action' => $edd_action,
					'license'    => $license_key,
			        'item_name'  => SUPERPWA_PRO_DATA_ITEM_NAME,
			        'author'     => 'SuperPWA Team',			
					'url'        => home_url(),
					'beta'       => false,
					);
    $message        = '';
    $current_status = '';
    $response       = @wp_remote_post( SUPERPWA_PRO_DATA_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
    if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		$message =  ( is_wp_error( $response ) && ! empty( $response->get_error_message() ) ) ? $response->get_error_message() : __( 'An error occurred, please try again.' );
	} else {
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( false === $license_data->success ) {
			$current_status = $license_data->error;
			switch( $license_data->error ) {
				case 'expired' :
        $license['pro']['license_key_status']  = 'active';        
          $license['pro']['license_key']         = $license_key;
          $license['pro']['license_key_message'] = 'active';                    
          $license['pro']['license_key_expires'] = $license_data->expires;
          $license_username = $license_data->customer_name;
          $license['pro']['license_key_username'] = $license_username;
          $license_paymentid = $license_data->payment_id;
          $license['pro']['license_key_payment_id'] = $license_paymentid;

          $license_expiry = date('Y-m-d', strtotime($license_data->expires));
          $license_expiry_lifetime = $license_data->expires;
          
          $today = date('Y-m-d');
          $exp_date = $license_expiry;
          $date1 = date_create($today);
          $date2 = date_create($exp_date);
          $diff = date_diff($date1,$date2);
          $days = $diff->format("%a");
          if( $license_expiry_lifetime == 'lifetime' ){
            $days = 'Lifetime';
            if ($days == 'Lifetime') {
            $expire_msg = " Your License is Valid for Lifetime ";
            }
          }
          else if($today > $exp_date){
            $days = -$days;
          }

          $current_status = 'active';
          $cust_name      = $license_username;
          $days_remaining = $days;
          $lic_paymentid = $license_paymentid;
					$message = sprintf(
						__( 'Your license key expired on %s.' ),
						date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
					);
					break;
				case 'revoked' :
					$message = __( 'Your license key has been disabled.' );
					break;
				case 'missing' :
					$message = __( 'Your license key is Invalid.' );
					break;
				case 'invalid' :
				case 'site_inactive' :
					$message = __( 'Your license is not active for this URL.' );
					break;
				case 'item_name_mismatch' :
					$message = __( 'This appears to be an invalid license key.' );
					break;
				case 'no_activations_left':
					$message = __( 'Your license key has reached its activation limit.' );
					break;
				default :
        $license['pro']['license_key_status']  = 'active';        
          $license['pro']['license_key']         = $license_key;
          $license['pro']['license_key_message'] = 'active';                    
          $license['pro']['license_key_expires'] = $license_data->expires;
          $license_username = $license_data->customer_name;
          $license['pro']['license_key_username'] = $license_username;
          $license_paymentid = $license_data->payment_id;
          $license['pro']['license_key_payment_id'] = $license_paymentid;

          $license_expiry = date('Y-m-d', strtotime($license_data->expires));
          $license_expiry_lifetime = $license_data->expires;
          
          $today = date('Y-m-d');
          $exp_date = $license_expiry;
          $date1 = date_create($today);
          $date2 = date_create($exp_date);
          $diff = date_diff($date1,$date2);
          $days = $diff->format("%a");
          if( $license_expiry_lifetime == 'lifetime' ){
            $days = 'Lifetime';
            if ($days == 'Lifetime') {
            $expire_msg = " Your License is Valid for Lifetime ";
            }
          }
          else if($today > $exp_date){
            $days = -$days;
          }

          $current_status = 'expired';
          $cust_name      = $license_username;
          $days_remaining = $days;
          $lic_paymentid = $license_paymentid;
					$message = __( 'An error occurred, please try again.' );
					break;
			}
		}
	}
	if($message){
        $license['pro']['license_key_status'] = $current_status;
        $license['pro']['license_key']        = $license_key;
        $license['pro']['license_key_message']= $message;
    }
    else if($license_status == 'expired'){


      $license['pro']['license_key_status']  = 'active';
          $license['pro']['license_key']         = $license_key;
          $license['pro']['license_key_message'] = 'active';                    
          $license['pro']['license_key_expires'] = $license_data->expires;                    
          $license_username = $license_data->customer_name;
          $license['pro']['license_key_username'] = $license_username;
          $license_paymentid = $license_data->payment_id;
          $license['pro']['license_key_payment_id'] = $license_paymentid;                    
          $current_status = 'active';
          $message        = 'Activated';
          $cust_name        = $license_username;
          $lic_paymentid = $license_paymentid;

    }
    else{
    	if($license_status == 'active'){
	        $license['pro']['license_key_status']  = 'active';
	        $license['pro']['license_key']         = $license_key;
	        $license['pro']['license_key_message'] = 'active';                    
	        $license['pro']['license_key_expires'] = $license_data->expires;
	        $license_username = $license_data->customer_name;
          $license['pro']['license_key_username'] = $license_username;
          $license_expiry = date('Y-m-d', strtotime($license_data->expires));
          $license_expiry_lifetime = $license_data->expires;

          $license_paymentid = $license_data->payment_id;
          $license['pro']['license_key_payment_id'] = $license_paymentid;
          
          $today = date('Y-m-d');
          $exp_date = $license_expiry;
          $date1 = date_create($today);
          $date2 = date_create($exp_date);
          $diff = date_diff($date1,$date2);
          $days = $diff->format("%a");
          if( $license_expiry_lifetime == 'lifetime' ){
            $days = 'Lifetime';
            if ($days == 'Lifetime') {
            $expire_msg = " Your License is Valid for Lifetime ";
            }
          }
          else if($today > $exp_date){
            $days = -$days;
          }

	        $current_status = 'active';
	        $message        = 'Activated';
	        $cust_name        = $license_username;
          $days_remaining = $days;
          $lic_paymentid = $license_paymentid;
	    }
	    if($license_status == 'inactive'){
            $license['pro']['license_key_status']  = 'deactivated';
            $license['pro']['license_key']         = '';
            $license['pro']['license_key_message'] = 'Deactivated';
            $license['pro']['customer_name'] = $license_data->cust_name;
            $license_expiry = date('Y-m-d', strtotime($license_data->expires));
            $license_expiry_lifetime = $license_data->expires;
            
            $today = date('Y-m-d');
            $exp_date = $license_expiry;
            $date1 = date_create($today);
            $date2 = date_create($exp_date);
            $diff = date_diff($date1,$date2);
            $days = $diff->format("%a");
            if( $license_expiry_lifetime == 'lifetime' ){
              $days = 'Lifetime';
              if ($days == 'Lifetime') {
              $expire_msg = " Your License is Valid for Lifetime ";
              }
            }
            else if($today > $exp_date){
              $days = -$days;
            }

            $current_status = 'deactivated';
            $message        = 'Deactivated';
            $cust_name        = $license_username;
            $days_remaining = $days;
            
        }

    }
    $get_options   = (array) get_option('superpwa_pro_upgrade_license');
    $merge_options = array_merge($get_options, $license);
    $merge_options = array_filter($merge_options);
    update_option('superpwa_pro_upgrade_license', $merge_options);
    
    echo json_encode(array('status'=> $current_status, 'message'=> $message, 'cust_name'=> $cust_name, 'days_remaining'=> $days_remaining));die;
}

// For Auto check between 0-7 Days and when expired once in 24 Hours when user opens this page
function superpwa_pro_autocheck(){
            $transient_load =  'superpwa_pro_autocheck';
            $value_load =  'superpwa_pro_autocheck_value';
            $expiration_load =  86400 ;
            set_transient( $transient_load, $value_load, $expiration_load );
}