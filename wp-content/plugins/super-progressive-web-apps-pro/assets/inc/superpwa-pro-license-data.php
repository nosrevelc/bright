<?php
	
		$settings_url = esc_url(admin_url('admin.php?page=superpwa-upgrade'));
        $license_info = get_option("superpwa_pro_upgrade_license");
        // print_r($license_info);die;        
        $license_exp = $original_license_key = $license_info_paymentid = "";
        if ( $license_info['pro']['license_key_status'] == 'active' || $license_info['pro']['license_key_status'] == 'expired' ) {
			$license_status = $license_info['pro']['license_key_message'];
			$original_license_key = $license_info['pro']['license_key'];

            $license_exp = date('Y-m-d', strtotime($license_info['pro']['license_key_expires']));
            $license_exp_d = date('d-F-Y', strtotime($license_info['pro']['license_key_expires']));
			$license_info_lifetime = $license_info['pro']['license_key_expires'];
            $license_msg = $license_info['pro']['license_key_message'];
            $license_info_paymentid = isset($license_info['pro']['license_key_payment_id']) ? $license_info['pro']['license_key_payment_id'] :'' ;
        }
        $today = date('Y-m-d');
		$exp_date = $license_exp;
		$date1 = date_create($today);
			$date2 = date_create($exp_date);
			$diff = date_diff($date1,$date2);
			$days = $diff->format("%a");
			if( isset($license_info_lifetime) && $license_info_lifetime == 'lifetime' ){
				$days = 'Lifetime';
				if ($days == 'Lifetime') {
				$expire_msg = " Your License is Valid for Lifetime ";
				}
			}
			else if($today > $exp_date){
				$days = -$days;
			}
            $exp_id = '';
            $expire_msg = '';
            $renew_mesg = '';
            $span_class = '';
            $alert_icon = '';
            $ext_settings_url = 'ext_url';
            if( $days == 'Lifetime' ){
                $renew_url = "https://superpwa.com/checkout/?edd_license_key=".$original_license_key."&download_id=".$license_info_paymentid."";
                $expire_msg_before = '<span class="before_msg_Pro">Your <span class="lifetime">License is</span></span> <span class="superpwa-alert">Valid for '.$days.'</span><a target="blank" class="renewal-license" href="'.$renew_url.'"><span class="renew-lic">'.esc_html__('Renew', 'accelerated-mobile-pages').'</span></a>';
                // $span_class = "aeaicon dashicons dashicons-alert pro_icon";
                $color = 'color:green';
                $span_class = "aeaicon dashicons dashicons-yes pro_icon";
            }
            else if( $days>=0 && $days<=30){
                $renew_url = "https://superpwa.com/checkout/?edd_license_key=".$original_license_key."&download_id=".$license_info_paymentid."";
                $expire_msg_before = '<span class="before_msg_Pro">Your <span class="zto30">License is</span></span> <span class="amppro-alert">expiring in '.$days.' days</span><a target="blank" class="renewal-license" href="'.$renew_url.'"><span class="renew-lic">'.esc_html__('Renew', 'accelerated-mobile-pages').'</span></a>';
                // $span_class = "aeaicon dashicons dashicons-alert pro_icon";
                $color = 'color:green';
                $alert_icon = '<span class="spwawp_addon_icon dashicons dashicons-warning pro_warning"></span>';
            }else if($days<0){
                $ext_settings_url = 'ext_settings_url';
                $renew_url = "https://superpwa.com/checkout/?edd_license_key=".$original_license_key."&download_id=".$license_info_paymentid."";
                $expire_msg = " Expired ";
                $expire_msg_before = 'Your<span class="superpwa_Pro_inactive">License has been</span>';
                $exp_class = 'expired';
                $exp_id = 'exp';
                $exp_class_2 = 'renew_license_key_';
                $span_class = "aeaicon dashicons dashicons-no spwa_no";
                $renew_mesg = '<a target="blank" class="renewal-license" href="'.$renew_url.'"><span class="renew-lic">'.esc_html__('Renew', 'accelerated-mobile-pages').'</span></a>';
                $color = 'color:red';
            }else{
                $expire_msg = " Active ";
                $expire_msg_before = '<span class="before_msg_Pro_active">Your License is</span>';
                $span_class = "aeaicon dashicons dashicons-yes pro_icon";
                $color = 'color:green';
            }
            if( defined('SUPERPWA_PRO_PLUGIN_DIR_NAME') ) {
            $license_info = get_option( 'superpwa_pro_upgrade_license');
            $fname = '';
            if ( $license_info['pro']['license_key_status'] == 'active' || $license_info['pro']['license_key_status'] == 'expired' ) {
                if (isset($license_info['pro']['license_key_username'])) {
                    $fname = $license_info['pro']['license_key_username'];
                }
            $fname = substr($fname, 0, strpos($fname, ' '));
            $check_for_Caps = ctype_upper($fname);
            if ( $check_for_Caps == 1 ) {
            $fname =  strtolower($fname);
            $fname =  ucwords($fname);
            }
            else{
                $fname =  ucwords($fname);   
            }
            $proDetailsProvide = "<div class='superpwa-extension-mgr-main'>
            <span class='superpwa-extension-mgr-info'>
            ".$alert_icon."<span class='activated-plugins'>Hi <span class='superpwa_pro_key_user_name'>".esc_html($fname)."</span>".','."
            <span class='activated-plugins'> ".$expire_msg_before." <span class='inner_span' id=".$exp_id.">".$expire_msg."</span></span>
            <span class='".$span_class."'></span>".$renew_mesg ;    
        $proDetailsProvide .= "</span></div>";
        if ( !empty($fname) && $license_msg !== 'An error occurred, please try again.' ) {
            echo $proDetailsProvide;
        }
        }
    }