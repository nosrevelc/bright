<?php
/**
 * Android APK APP Generator
 *
 * @since 1.0
 *
 * @Class	SPWAPandroidapkapp()			Generate android app
 */

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}


/**
 * Generate android app
 */
class SPWAPandroidapkapp
{
    /**
     * get the unique instance of the class
     * @var SPWAPandroidapkapp
     */
    private static $instance;
    /**
     * get the unique instance of the class
     * @var settings
     */
    private $settings = array();
    /**
     * Constructor
     */
    public function __construct()
    {
        if (is_admin()) {
            add_action('admin_menu', array($this, 'superpwa_android_apk_app_sub_menu'));
            add_action('admin_init', array($this, 'superpwa_android_apk_app_register_settings'));
            add_action("admin_enqueue_scripts", array($this , 'script_enqueue'));

            //AJAX requests
            add_action("wp_ajax_superpwa_pro_apk_keystore_upload", array($this, "superpwa_pro_apk_keystore_upload"));
            add_action("wp_ajax_superpwa_pro_apk_plugin_caller", array($this, "superpwa_pro_apk_plugin_caller"));
            add_action("wp_ajax_superpwa_pro_apk_plugin_project_caller", array($this, "superpwa_pro_apk_plugin_project_caller"));
            add_action("wp_ajax_superpwa_apk_assets_removeold", array($this, "superpwa_apk_assets_removeold"));
        }
    }
    /**
     * Gets an instance of our SPWAPandroidapkapp class.
     *
     * @return SPWAPandroidapkapp Object
     */
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    /**
     * Get android apk app generator settings
     *
     * @since 1.0
     */
    public function superpwa_android_apk_app_get_settings()
    {
        $host = parse_url(get_site_url(), PHP_URL_HOST);
        if (strpos($host, 'www')!==false) {
            $host = str_replace("www.", "", $host);
        }
        $packageId = preg_replace("/[^a-z0-9]/", "", $host);
        $packageId = "com.".$packageId.".myapp";
        $basic_settings = superpwa_get_settings();//PWA settings
        $defaults = array(
                    'pta_package_id'			=> $packageId, //Message
                    'apk_type'					=> 'new', //Message
                    'apk_key_password'			=> '', //Message
                );
        $aaagen_settings = get_option('superpwa_android_apk_app_settings', $defaults);
        $settings = array_merge($basic_settings, $aaagen_settings);
        return $settings;
    }
    /**
     * Register Android apk app settings
     *
     * @since 	1.0
     */
    public function superpwa_android_apk_app_register_settings()
    {
        // Register Setting
        register_setting(
            'superpwa_android_apk_app_settings_group',		 // Group name
            'superpwa_android_apk_app_settings', 			// Setting name = html form <input> name on settings form
            'superpwa_android_apk_app_validater_sanitizer'	// Input validator and sanitizer
        );
        // android_apk_app
        add_settings_section(
            'superpwa_android_apk_app_section',				// ID
            __return_false(),								// Title
            array($this, 'superpwa_android_apk_app_section_cb'),				// Callback Function
            'superpwa_android_apk_app_section'					// Page slug
        );
        // Package Id
        add_settings_field(
            'superpwa_android_apk_app_packageId',						// ID
            __('Package Id', 'super-progressive-web-apps-pro'),	// Title
            array($this, 'superpwa_android_apk_app_packageId_cb'),					// CB
            'superpwa_android_apk_app_section',						// Page slug
            'superpwa_android_apk_app_section'							// Settings Section ID
        );
        // Generate APK type
        add_settings_field(
            'superpwa_android_apk_app_apktype',						// ID
            __('Generate APK type', 'super-progressive-web-apps-pro'),	// Title
            array($this, 'superpwa_android_apk_app_apktype_cb'),					// CB
            'superpwa_android_apk_app_section',						// Page slug
            'superpwa_android_apk_app_section'							// Settings Section ID
        );
        // Generated old APK
        add_settings_field(
            'superpwa_android_apk_app_oldapk',						// ID
            __('APK Downloads', 'super-progressive-web-apps-pro'),	// Title
            array($this, 'superpwa_android_apk_app_oldapk_cb'),					// CB
            'superpwa_android_apk_app_section',						// Page slug
            'superpwa_android_apk_app_section'							// Settings Section ID
        );
    }
    /**
     * Callbacks functions for Android APK APP Generator
     *
     * @since 1.0
     */
    public function superpwa_android_apk_app_section_cb()
    {
        // Get add-on info
        $addon_utm_tracking = superpwa_get_addons( 'android_apk_app_generator' );
        echo esc_html__("Create Android APK for your PWA and get listed on Google Play store with one click", "super-progressive-web-apps-pro");
        ?>
         <span><a class="docs-link" href="<?php echo esc_url($addon_utm_tracking['link']) . '?utm_source=superpwa-plugin&utm_medium=utm-tracking-settings'?>"><?php echo esc_html__( 'view tutorial', 'super-progressive-web-apps' ); ?></a></span> 
            <?php
    }
    public function superpwa_android_apk_app_packageId_cb()
    {
        $settings = $this->superpwa_android_apk_app_get_settings();
        $packageId = $settings['pta_package_id']; ?>
		<input type="text" name="superpwa_android_apk_app_settings[pta_package_id]" id="superpwa_android_apk_app_settings[pta_package_id]" class=""  value="<?php echo esc_attr($packageId); ?>">
		<?php
    }
    public function superpwa_android_apk_app_apktype_cb()
    {
        $settings = $this->superpwa_android_apk_app_get_settings();
        $newapktype = 'checked';
        $mineapktype = '';
        if (isset($settings['apk_type']) && $settings['apk_type']=='mine') {
            $newapktype = '';
            $mineapktype = 'checked';
        }
        // Get add-on info
        $addon_utm_tracking = superpwa_get_addons( 'android_apk_app_generator' );
        echo "<label class='radio-button first-time'> <input type='radio' name='superpwa_android_apk_app_settings[apk_type]' class='generate_apk_type' value='new' ".$newapktype.">".esc_html__('First time', 'super-progressive-web-apps-pro')."</label>
			<label class='radio-button'><input type='radio' name='superpwa_android_apk_app_settings[apk_type]' class='generate_apk_type' value='mine' ".$mineapktype.">".esc_html__('New version (Update) of APK', 'super-progressive-web-apps-pro')." </label>";
        echo "<p class='newversion-tooltip'>".esc_html__('If your app is live on Play store, then select "New version (Update) of APK".', 'super-progressive-web-apps-pro')."&nbsp;<a class='docs-link' target='_blank' href=".esc_url($addon_utm_tracking['link'])."?utm_source=superpwa-plugin&utm_medium=utm-tracking-settings#new_version>".esc_html__( 'Read more', 'super-progressive-web-apps' )."</a></p>";

        $showOldOption = false;
        if (isset($settings['apk_type']) && $settings['apk_type']=='mine') {
            $showOldOption = true;
        }
        $showUploadKeyStore = '';
        $uploadedStat = '';
        if (file_exists(SUPERPWA_PRO_PATH_ABS."/keystore/signing.keystore")) {
            $showUploadKeyStore = 'hide';
            $uploadedStat ="<span class='dashicons dashicons-yes' id='keystore-file'></span><em class='superpwaupload_new_keystore'> ".esc_html__('Key Applied', 'super-progressive-web-apps-pro')."<span class='superpwaupload_new_keystore change-keyfile'> ".esc_html__('(change)', 'super-progressive-web-apps-pro')."</span></em>";
        }
        echo "<div class='signedInfo-wrapper' ".($showOldOption? '': 'style="display:none"').">
				<div class='field'><label>".esc_html__('Last App version', 'super-progressive-web-apps-pro')." </label> <input style='margin-left: -3px;' type='text' name='superpwa_android_apk_app_settings[apk_app_version]' value='".@$settings['apk_app_version']."'></div> 
				<div class='field'><label>".esc_html__('Last App version code', 'super-progressive-web-apps-pro')." </label><input type='number' name='superpwa_android_apk_app_settings[apk_app_code]' value='".@$settings['apk_app_code']."'> </div>
				<div class='field' style='margin-bottom:6px;margin-top:6px;'><label>".esc_html__('Key file', 'super-progressive-web-apps-pro')."</label> 
				".$uploadedStat."
				<span class='keystore ".$showUploadKeyStore."'><input type='file' accept='.keystore'> <input type='button' value='Upload' class='superpwa_uploadkey_store'><span class='response_msg'></span></span>
				</div>

				<div class='field'><label>".esc_html__('Key password', 'super-progressive-web-apps-pro')." </label> <input type='text' name='superpwa_android_apk_app_settings[apk_key_password]' value='".@$settings['apk_key_password']."'> </div>
				<div class='field'><label>".esc_html__('Key store password', 'super-progressive-web-apps-pro')." </label> <input type='text' name='superpwa_android_apk_app_settings[apk_key_store_password]' value='".@$settings['apk_key_store_password']."'></div>
			</div>";
    }
    public function superpwa_android_apk_app_oldapk_cb()
    {
        $pwa_pwatapk = get_option("superpwa_apk", true);
        $olderDownload = '';
        $nonce = wp_create_nonce("superpwa_apk_nonce_assetlink");
        if (isset($pwa_pwatapk['old_files']) && $pwa_pwatapk['old_files']) {
            foreach (array_reverse($pwa_pwatapk['old_files']) as $key => $value) {
                $upload_path = wp_upload_dir();
                $file_name = isset($value['fname'])? $value['fname'] : '';
                if (!isset($value['fname'])) {
                    $nameArr = explode('/', $value['name']);
                    $file_name = end($nameArr);
                }
                $fileUrl = $upload_path['baseurl']."/superpwa_apk/".$file_name;
                $filecreatedtime = filemtime($upload_path['basedir']."/superpwa_apk/".$file_name);
                $olderDownload .= '<div class="apkzip-row" > 
							<span style="width:32%;display:inline-block;">'.$value['version'].'</span>
							<span style="width:32%;display:inline-block;">
								<a target="_blank" href="'.$fileUrl.'">'.esc_html__('DOWNLOAD', 'super-progressive-web-apps-pro').'</a>
							</span>
							<span style="width:32%;display:inline-block;"><em>'.date('Y-m-d H:i:s', $filecreatedtime).'</em></span>
							<span style="width:4%;display:none;">
								<em data-nonce="'.$nonce.'" data-version="'.$value['version'].'" class="delete_old_pwa" href="#" >'.esc_html__('Delete', 'super-progressive-web-apps-pro').'</em>
							</span>
						</div>';
            }
        } ?>
		<div class="download_wrapper">
			<button type="button" id="superpwa_pro_apk_plugin_sets" class="download_apk" data-nonce="<?php echo wp_create_nonce("superpwa_apk_nonce_gen"); ?>"><?php echo esc_html('Download APK', 'super-progressive-web-apps-pro'); ?></button>
			<span class="update_message"><p style="display:inline"></p></span>
		</div>
		<br/>
		<div class="download_wrapper">
			<button type="button" id="superpwa_pro_plugin_project" class="download_apk_project" data-nonce="<?php echo wp_create_nonce("superpwa_apk_nonce_gen"); ?>"><?php echo esc_html__("Download APK project (For development)", 'super-progressive-web-apps-pro')?></button>
			<span class="update_message"><p style="display:inline"></p></span>
		</div>
		<?php
        if ($olderDownload) {
            echo "<style>.spp-older-wrap{font-size: 17px;}.spp-tbl{boder:1px;padding:2px;}.spp-tbl-cnt{height:80px;max-height:80px;overflow-y:scroll}.apkzip-row{padding-top:8px;}.apkzip-row:nth-child(odd){background:#fff;}.apkzip-row span{padding-bottom: 5px;}.apkzip-row span:nth-child(1){padding-left:5px;}</style><br/>
				  <br/><hr/><strong class='spp-older-wrap'>Previous generated APK</strong>";
            echo "<div class='spp-tbl'>
					<div style='font-weight:600;padding-bottom:5px; padding-top:5px'><span style='width:32%;display:inline-block;'>".esc_html__("Version", 'super-progressive-web-apps-pro')."</span>
						<span style='width:32%;display:inline-block;'>".esc_html__("Action", 'super-progressive-web-apps-pro')."</span>
						<span style='width:32%;display:inline-block;'>".esc_html__("Created date", 'super-progressive-web-apps-pro')."</span>
					</div>
				 ";
            echo " <div class='spp-tbl-cnt'>".$olderDownload."</div> 
			    </div>";
        }
    }


    /**
     * Add sub-menu page for Android APK APP
     *
     * @since 1.0
     */
    public function superpwa_android_apk_app_sub_menu()
    {
        
        // Android APK APP sub-menu
        add_submenu_page('superpwa', __('Super Progressive Web Apps Pro', 'super-progressive-web-apps-pro'), __('Android APK APP', 'super-progressive-web-apps-pro'), 'manage_options', 'superpwa-android-apk-app', array($this, 'superpwa_android_apk_app_interface_render'));
    }
    /**
     * Call To ACtion UI renderer
     *
     * @since 1.0
     */
    public function superpwa_android_apk_app_interface_render()
    {
        // Authentication
        if (! current_user_can('manage_options')) {
            return;
        }
        // Handing save settings
        if (isset($_GET['settings-updated'])) {
            // Add settings saved message with the class of "updated"
            add_settings_error('superpwa_settings_group', 'superpwa_android_apk_app_settings_saved_message', __('Settings saved.', 'super-progressive-web-apps-pro'), 'updated');

            // Show Settings Saved Message
            settings_errors('superpwa_settings_group');
        } 
		// Get add-on info
	    $addon_utm_tracking = superpwa_get_addons( 'android_apk_app_generator' );
         // Menu Bar Styles
        if(function_exists('superpwa_setting_tabs_styles')){
            superpwa_setting_tabs_styles();
         }

		?>
		<div class="wrap">	
			<h1><?php _e('Android APK APP Generator', 'super-progressive-web-apps-pro'); ?></h1>
			<?php 
                 // Menu Bar Html
                 if(function_exists('superpwa_setting_tabs_html')){
                    superpwa_setting_tabs_html(); 
                 }
            ?>
			<form action="options.php" method="post" enctype="multipart/form-data">		
				<?php
                // Output nonce, action, and option_page fields for a settings page.
                settings_fields('superpwa_android_apk_app_settings_group');
                
        // Status
                do_settings_sections('superpwa_android_apk_app_section');	// Page slug
                
                // Output save settings button
        submit_button(__('Save Settings', 'super-progressive-web-apps-pro')); ?>
			</form>
		</div>
        <?php 
           // Newsletter Form HTML
            if(function_exists('superpwa_newsletter_form')){
                    superpwa_newsletter_form(); 
            }
        ?>
		<?php
    }
    /**
     * Enqueue the javascript for admin section
     * @param  string $hook 	name of the page
     * @return null       		load th script on page
     */
    public function script_enqueue($hook)
    {
        if ($hook=='superpwa_page_superpwa-android-apk-app' || $hook=='super-pwa_page_superpwa-android-apk-app' || strpos($hook, 'superpwa-android-apk-app') !== false) {
            wp_enqueue_script(
                'superpwa-pro-apk-app',
                trailingslashit(SUPERPWA_PRO_PATH_SRC) . '/assets/js/admin-android-apk-app-addon.js',
                array( 'jquery' ),
                SUPERPWA_PRO_VERSION,
                true
            );
            $array = array('security_nonce'=>wp_create_nonce('superpwa_pro_post_nonce'));
            wp_localize_script('superpwa-pro-apk-app', 'superpwa_pro_apk', $array);

          wp_enqueue_style('android-apk-admin-style', SUPERPWA_PRO_PATH_SRC . '/assets/css/admin-android-apk-generator.css', array(), SUPERPWA_PRO_VERSION, 'all');
        }
    }

    public function superpwa_pro_apk_keystore_upload()
    {
        if (! isset($_GET['security_nonce'])) {
            echo json_encode(array("status"=>500, "message"=> "security nonce not found"));
            die;
        }
        if (!wp_verify_nonce($_GET['security_nonce'], 'superpwa_pro_post_nonce')) {
            echo json_encode(array("status"=>500, "message"=> "nonce not matched"));
            die;
        }
        $keystore_file = $_FILES['keystore_file'];
        if ($keystore_file['error']>0) {
            echo json_encode(array("status"=>400, "message"=> "file not found"));
            die;
        }
        if (!is_dir(SUPERPWA_PRO_PATH_ABS."/keystore/")) {
            wp_mkdir_p(SUPERPWA_PRO_PATH_ABS."/keystore/");
        }
        $movedFile = move_uploaded_file($keystore_file['tmp_name'], SUPERPWA_PRO_PATH_ABS."/keystore/signing.keystore");
        if ($movedFile) {
            echo json_encode(array("status"=>200, "message"=> "File upload successful"));
            die;
        } else {
            echo json_encode(array("status"=>201, "message"=> "File not uploaded try again"));
            die;
        }
    }

    public function superpwa_pro_apk_plugin_caller()
    {
        $nonce = $_POST['ref'];
        if (!wp_verify_nonce($nonce, 'superpwa_pro_post_nonce')) {
            echo json_encode(array("status"=>400, "message"=>"Request not verified"));
            die;
        }
        $license = get_option("superpwa_pro_upgrade_license");
        if (!$license || (isset($license['pro']['license_key_status']) && $license['pro']['license_key_status']!='active')) {
            echo json_encode(array("status"=>400, "message"=>"PRO license not activated"));
            die;
        }
        $defaults = $this->superpwa_android_apk_app_get_settings();
        $settings = superpwa_get_settings();
        $signingMode = 'new';
        $keyPassword = $keystorePassword = '';
        $keystorefile = null;
        $appVersionCode = 1;
        $version = "1.0.0";
        if (isset($defaults['apk_type']) && $defaults['apk_type']=='mine') {
            $signingMode = 'mine';
            $keyPassword = isset($defaults['apk_key_password'])? $defaults['apk_key_password']: '';
            $keystorePassword = isset($defaults['apk_key_store_password'])? $defaults['apk_key_store_password']: '';
            
            if (!file_exists(SUPERPWA_PRO_PATH_ABS."/keystore/signing.keystore")) {
                echo json_encode(array("status"=>400, "message"=>"Keystore File not found"));
                die;
            } else {
                $keystorefile = 'data:application/octet-stream;base64,'.base64_encode(file_get_contents(SUPERPWA_PRO_PATH_ABS."/keystore/signing.keystore"));
            }

            if (empty($keyPassword)) {
                echo json_encode(array("status"=>400, "message"=>"Key password is empty"));
                die;
            }
            if (empty($keystorePassword)) {
                echo json_encode(array("status"=>400, "message"=>"Key store password is empty"));
                die;
            }
            if (isset($defaults['apk_app_version']) && !empty($defaults['apk_app_version'])) {
                $version = $defaults['apk_app_version'];
            } else {
                echo json_encode(array("status"=>400, "message"=>"Last APK version is empty"));
                die;
            }
            if (isset($defaults['apk_app_code']) && !empty($defaults['apk_app_code'])) {
                $appVersionCode = $defaults['apk_app_code']+1;
            } else {
                echo json_encode(array("status"=>400, "message"=>"Last APK code is empty, ex: 1"));
                die;
            }
            if (version_compare($version, "1.0.0", '>=')) {
                //$version = $pwa_pwatapk['last_version'];
                $verArr = explode(".", $version);
                if ($verArr[count($verArr)-1]<=9) {
                    $verArr[count($verArr)-1] = $verArr[count($verArr)-1]+1;
                } elseif ($verArr[count($verArr)-2]<=9) {
                    $verArr[count($verArr)-1] = 0;
                    $verArr[count($verArr)-2] = $verArr[count($verArr)-2]+1;
                } elseif ($verArr[count($verArr)-3]<=9) {
                    $verArr[count($verArr)-1] = 0;
                    $verArr[count($verArr)-2] = 0;
                    $verArr[count($verArr)-3] = $verArr[count($verArr)-3]+1;
                }
                $version = implode(".", $verArr);
            }
        }//$defaults['apk_type'] ==mine if closed
        $host = home_url();
        $packageId = $defaults['pta_package_id'];
        $start_url = '/';

        if(function_exists('superpwa_get_start_url')){

            $start_url = strlen( superpwa_get_start_url( true ) )>2?user_trailingslashit(superpwa_get_start_url( true )) : '/'; 
         }

        $data = array(
                "packageId"=> $packageId,
                "host"=> $host,
                "name"=> esc_attr($settings['app_name']),
                "launcherName"=> esc_attr($settings['app_short_name']),
                "themeColor"=> sanitize_hex_color($settings['theme_color']),
                "navigationColor"=> sanitize_hex_color($settings['background_color']),
                "backgroundColor"=> sanitize_hex_color($settings['background_color']),
                "startUrl"=> esc_url($start_url),
                "webManifestUrl"=>esc_url(superpwa_manifest('src')),
                "iconUrl"=> esc_url($defaults['icon']),
                "maskableIconUrl"=> esc_url($defaults['splash_icon']),
                "appVersion"=> $version,
                "appVersionCode"=> $appVersionCode,
                "useBrowserOnChromeOS"=> true,
                "splashScreenFadeOutDuration"=> 300,
                "enableNotifications"=> true,
                "display"=> esc_attr($defaults['display']),
                "shortcuts"=> array(array(
                                "name"=> esc_attr($defaults['app_blog_name']),
                                "short_name"=> esc_attr($defaults['app_blog_short_name']),
                                "url"=> "/?shortcut",
                                "icons"=> array()
                            )),
                "signingMode"=> $signingMode,
                "signing"=> array(
                    "file"=> $keystorefile,
                    "alias"=> "my-key-alias",
                    "fullName"=> "PWA for wp",
                    "organization"=> "MAGAZINE3",
                    "organizationalUnit"=> "Development",
                    "countryCode"=> "IN",
                    "keyPassword"=> $keyPassword,
                    "storePassword"=> $keystorePassword
                ),
                "fallbackType"=> "customtabs",
            );
        $headers_data = array('request_url'=>esc_url(get_home_url()));
        set_time_limit(500);
        $response = $this->sender_request("generateApkZip", $data, $headers_data, true);
        if ($response) {
            if ($response['status'] == 200) {
                $host = parse_url(get_home_url(), PHP_URL_HOST);
                if (strpos($host, 'www')!==false) {
                    $host = str_replace("www.", "", $host);
                }
                
                $upload_path = wp_upload_dir();
                $file_path = $upload_path['basedir']."/superpwa_apk/";
                wp_mkdir_p($file_path);
                $file_name = $host."-".$version."-".$this->generate_random_string().'.zip';
                $file_name_path = $file_path.'/'.$file_name;
                $fp = file_put_contents($file_name_path, $response['body']);


                $apk = get_option("superpwa_apk");
                $apk["user_authentication"] = 1;
                $apk["last_version"] = $version;
                $apk["old_files"][] = array('name'=>$file_name_path, 'fname'=>$file_name, 'version'=>$version, 'time'=> time());
                update_option("superpwa_apk", $apk);
                $fileUrl = $upload_path['baseurl']."/superpwa_apk/".$file_name;
                echo json_encode(array("status"=>200, "message"=> esc_html__("PWA APK generated ", 'super-progressive-web-apps-pro')."<a href='".$fileUrl."'>".esc_html__('Download', 'super-progressive-web-apps-pro')."</a>"));
            } else {
                echo json_encode(array("status"=>300, "message"=> "APK not created try later. <br/>ErrorDetails: ".$response['body']));
            }
        } else {
            echo json_encode(array("status"=>400, "message"=> "Some error occurred in response, try again later."));
        }
        die;
    }

    public function generate_random_string($length = 5)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function sender_request($suffix, $postData, $headers=array(), $getHeader= false)
    {
        $api_url = 'http://app.pwa-for-wp.com/';
        $data = array( 'timeout' => 1000, 'sslverify' => false, 'body' => json_encode($postData));
        $headers['Content-Type'] = 'application/json';
        $header = array("headers"=>$headers);
        
        $data = array_merge($data, $header);
        $request = wp_remote_post($api_url.$suffix, $data);
        $response = false;
        if (! is_wp_error($request)) {
            $response = wp_remote_retrieve_body($request);
        }
        $code = wp_remote_retrieve_response_code($request);
        if ($code==200) {
            if ($getHeader) {
                return array('status'=> $code, 'body'=>$response, 'header'=> wp_remote_retrieve_headers($request) );
            }
        } else {
            if ($getHeader) {
                return array('status'=> $code, 'body'=>$response, 'header'=> wp_remote_retrieve_headers($request) );
            }
        }
        return $response;
    }

    public function superpwa_pro_apk_plugin_project_caller()
    {
        $nonce = $_POST['ref'];
        if (!wp_verify_nonce($nonce, 'superpwa_pro_post_nonce')) {
            echo json_encode(array("status"=>400, "message"=>"Request not verified"));
            die;
        }
            
        $license = get_option("superpwa_pro_upgrade_license");
        if (!$license || (isset($license['pro']['license_key_status']) && $license['pro']['license_key_status']!='active')) {
            echo json_encode(array("status"=>400, "message"=>"PRO license not activated"));
            die;
        }
        $defaults = $this->superpwa_android_apk_app_get_settings();
        $settings = superpwa_get_settings();

        $signingMode = 'new';
        $keyPassword = $keystorePassword = '';
        $keystorefile = null;
        $appVersionCode = 1;
        $version = "1.0.0";
        if (isset($defaults['apk_type']) && $defaults['apk_type']=='mine') {
            $signingMode = 'mine';
            $keyPassword = isset($defaults['apk_key_password'])? $defaults['apk_key_password']: '';
            $keystorePassword = isset($defaults['apk_key_store_password'])? $defaults['apk_key_store_password']: '';
                
            if (!file_exists(SUPERPWA_PRO_PATH_ABS."/keystore/signing.keystore")) {
                echo json_encode(array("status"=>400, "message"=>"Keystore File not found"));
                die;
            } else {
                $keystorefile = 'data:application/octet-stream;base64,'.base64_encode(file_get_contents(SUPERPWA_PRO_PATH_ABS."/keystore/signing.keystore"));
            }

            if (empty($keyPassword)) {
                echo json_encode(array("status"=>400, "message"=>"Key password is empty"));
                die;
            }
            if (empty($keystorePassword)) {
                echo json_encode(array("status"=>400, "message"=>"Key store password is empty"));
                die;
            }
            if (isset($defaults['apk_app_version']) && !empty($defaults['apk_app_version'])) {
                $version = $defaults['apk_app_version'];
            } else {
                echo json_encode(array("status"=>400, "message"=>"Last APK version is empty"));
                die;
            }
            if (isset($defaults['apk_app_code']) && !empty($defaults['apk_app_code'])) {
                $appVersionCode = $defaults['apk_app_code']+1;
            } else {
                echo json_encode(array("status"=>400, "message"=>"Last APK code is empty, ex: 1"));
                die;
            }
            if (version_compare($version, "1.0.0", '>=')) {
                //$version = $pwa_pwatapk['last_version'];
                $verArr = explode(".", $version);
                if ($verArr[count($verArr)-1]<=9) {
                    $verArr[count($verArr)-1] = $verArr[count($verArr)-1]+1;
                } elseif ($verArr[count($verArr)-2]<=9) {
                    $verArr[count($verArr)-1] = 0;
                    $verArr[count($verArr)-2] = $verArr[count($verArr)-2]+1;
                } elseif ($verArr[count($verArr)-3]<=9) {
                    $verArr[count($verArr)-1] = 0;
                    $verArr[count($verArr)-2] = 0;
                    $verArr[count($verArr)-3] = $verArr[count($verArr)-3]+1;
                }
                $version = implode(".", $verArr);
            }
        }
            
            
            
        $host = get_home_url();
        $packageId = $defaults['pta_package_id'];
        $start_url = '/';

        if(function_exists('superpwa_get_start_url')){

            $start_url = strlen( superpwa_get_start_url( true ) )>2?user_trailingslashit(superpwa_get_start_url( true )) : '/'; 
         }
            
        $data = array(
                "packageId"=> $packageId,
                "host"=> $host,
                "name"=> esc_attr($settings['app_name']),
                "launcherName"=> esc_attr($settings['app_short_name']),
                "themeColor"=> sanitize_hex_color($settings['theme_color']),
                "navigationColor"=> sanitize_hex_color($settings['background_color']),
                "backgroundColor"=> sanitize_hex_color($settings['background_color']),
                "startUrl"=> esc_url($start_url),
                "webManifestUrl"=>esc_url(superpwa_manifest('src')),
                "iconUrl"=> esc_url($defaults['icon']),
                "maskableIconUrl"=> esc_url($defaults['splash_icon']),
                "appVersion"=> $version,
                "appVersionCode"=> $appVersionCode,
                "useBrowserOnChromeOS"=> true,
                "splashScreenFadeOutDuration"=> 300,
                "enableNotifications"=> true,
                "display"=> esc_attr($defaults['display']),
                "shortcuts"=> array(array(
                                "name"=> esc_attr($defaults['app_blog_name']),
                                "short_name"=> esc_attr($defaults['app_blog_short_name']),
                                "url"=> "/?shortcut",
                                "icons"=> array()
                            )),
                "signingMode"=> $signingMode,
                "signing"=> array(
                    "file"=> $keystorefile,
                    "alias"=> "my-key-alias",
                    "fullName"=> "PWA for wp",
                    "organization"=> "MAGAZINE3",
                    "organizationalUnit"=> "Development",
                    "countryCode"=> "IN",
                    "keyPassword"=> $keyPassword,
                    "storePassword"=> $keystorePassword
                ),
                "fallbackType"=> "customtabs",
            );

        $headers_data = array('request_url'=>esc_url(get_home_url()));
        set_time_limit(500);
        $response = $this->sender_request("generateAppProject", $data, $headers_data, true);
        if ($response) {
            if ($response['status'] == 200) {
                $host = parse_url(get_home_url(), PHP_URL_HOST);
                if (strpos($host, 'www')!==false) {
                    $host = str_replace("www.", "", $host);
                }
                    
                $upload_path = wp_upload_dir();
                $file_path = $upload_path['basedir']."/superpwa_apk/";
                wp_mkdir_p($file_path);
                $file_name = $host."-project.zip";
                $file_name_path = $file_path.'/'.$file_name;
                if (file_exists($file_name_path)) {
                    @unlink($file_name_path);
                }
                $fp = file_put_contents($file_name_path, $response['body']);

                $fileUrl = $upload_path['baseurl']."/superpwa_apk/".$file_name;
                echo json_encode(array("status"=>200, "message"=> esc_html__("App project generated", 'super-progressive-web-apps-pro')." <a href='".$fileUrl."'>".esc_html__("Download", 'super-progressive-web-apps-pro')."</a>"));
            } else {
                echo json_encode(array("status"=>300, "message"=> esc_html__("App project not generated try later.", 'super-progressive-web-apps-pro')." <br/>ErrorDetails: ".$response['body']));
            }
        } else {
            echo json_encode(array("status"=>400, "message"=> "Some error occurred in response, try again later."));
        }
        die;
    }
    public function superpwa_apk_assets_removeold()
    {
        $nonce = $_POST['ref'];
        $version = $_POST['oldversion'];
        if (!wp_verify_nonce($nonce, 'superpwa_pro_post_nonce')) {
            echo json_encode(array("status"=>400, "message"=>"Request not verified"));
            die;
        }
        $superpwa_apk = get_option("superpwa_apk", true);
        if (isset($superpwa_apk['old_files']) && $superpwa_apk['old_files']) {
            $filedir = '';
            $upload_path = wp_upload_dir();
            foreach ($superpwa_apk['old_files'] as $key => $value) {
                if ($value['version']==$version) {
                    $file_name = isset($value['fname'])? $value['fname'] : '';
                    if (!isset($value['fname'])) {
                        $nameArr = explode('/', $value['name']);
                        $file_name = end($nameArr);
                    }
                    $fileUrl = $upload_path['basedir']."/superpwa_apk/".$file_name;
                    if (file_exists($fileUrl)) {
                        @unlink($fileUrl);
                    }
                    unset($superpwa_apk['old_files'][$key]);
                    break;
                }
            }
            update_option("superpwa_apk", $superpwa_apk);
        }
        echo json_encode(array("status"=>200, "message"=>"Version removed successfully"));
        die;
    }
}
function superpwapro_androidapkapp()
{
    return SPWAPandroidapkapp::get_instance();
}
superpwapro_androidapkapp();
