<?php
/**
 * Pre Loading
 *
 * @since 1.11
 *
 * @Class SPWAP_PreLoading()			Add all features of Pre Loading
 *      @function
 */
// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
  * Features of Data Analytics
 */
class SPWAP_PreLoading
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
            add_action('admin_menu', array($this, 'pre_loader_sub_menu'));
            add_action( 'admin_init', array($this, 'superpwa_pre_loader_register_settings') );
            add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );
        }else{
        	add_action('wp_enqueue_scripts', array( $this, 'superpwa_pre_loading_frontend_enqueue_scripts' ));
        	add_action('wp_footer', array( $this, 'superpwa_pre_loading_icon_html'));
        	add_filter("superpwa_loading_contents" , array( $this, 'superpwa_loading_contents_lib_div' ) );
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
     * Add sub-menu page for app shortcut
     *
     * @since 1.5
     */
    public function pre_loader_sub_menu()
    {
        
        // Pre Loader sub-menu
        add_submenu_page(
            'superpwa',
            __('Super Progressive Web Apps Pro', 'super-progressive-web-apps-pro'),
            __('PreLoader', 'super-progressive-web-apps-pro'),
            'manage_options',
            'superpwa-pre-loader',
            array($this, 'superpwa_pre_loader_interface_render')
        );
    }

/**
 * Register Pre Loader settings
 *
 * @since 	2.1.7
 */
function superpwa_pre_loader_register_settings() {
    // Register Setting
	register_setting( 
		'superpwa_pre_loader_settings_group',		 // Group name
		'superpwa_pre_loader_settings', 			// Setting name = html form <input> name on settings form
		'superpwa_pre_loader_validater_sanitizer'	// Input validator and sanitizer
	);

	// Call to action
	    add_settings_section(
	        'superpwa_pre_loader_section',				// ID
	        __return_false(),								// Title
	      array($this, 'superpwa_pre_loader_section_cb'),				// Callback Function
	        'superpwa_pre_loader_section'					// Page slug
	    );
	// Show on Desktop CTA
		/*add_settings_field(
			'superpwa_pre_loader_icon',						// ID
			__('Loader', 'super-progressive-web-apps-pro'),	// Title
			array($this, 'superpwa_pre_loader_icon_cb'),					// CB
			'superpwa_pre_loader_section',						// Page slug
			'superpwa_pre_loader_section'							// Settings Section ID
		);*/

	// Loader Color
		add_settings_field(
			'superpwa_pre_loader_color',						// ID
			__('Loader Color', 'super-progressive-web-apps-pro'),	// Title
			array($this, 'superpwa_pre_loader_color_cb'),					// CB
			'superpwa_pre_loader_section',						// Page slug
			'superpwa_pre_loader_section'							// Settings Section ID
		);	
	// Loader Background Color
		add_settings_field(
			'superpwa_pre_loader_bg_color',						// ID
			__('Loader Background Color', 'super-progressive-web-apps-pro'),	// Title
			array($this, 'superpwa_pre_loader_bg_color_cb'),					// CB
			'superpwa_pre_loader_section',						// Page slug
			'superpwa_pre_loader_section'							// Settings Section ID
		);
	// Display only on PWA App
		add_settings_field(
			'superpwa_pre_loader_only_pwa',						// ID
			__('Show Only in PWA App', 'super-progressive-web-apps-pro'),	// Title
			array($this, 'superpwa_pre_loader_only_pwa_cb'),					// CB
			'superpwa_pre_loader_section',						// Page slug
			'superpwa_pre_loader_section'							// Settings Section ID
		);
	// Loader display_settings
		add_settings_field(
			'superpwa_pre_loader_display_settings',						// ID
			__('Loader Display on', 'super-progressive-web-apps-pro'),	// Title
			array($this, 'superpwa_pre_loader_display_settings_cb'),					// CB
			'superpwa_pre_loader_section',						// Page slug
			'superpwa_pre_loader_section'							// Settings Section ID
		);
	// Loading Icon Selector 
	add_settings_field(
			'superpwa_pre_loader_icon_selector',							// ID
			esc_html__('Loading icon selector', 'super-progressive-web-apps-pro'),	// Title
			array( $this, 'superpwa_pre_loading_icon_selector_callback' ),							// CB
			'superpwa_pre_loader_section',						// Page slug
			'superpwa_pre_loader_section'						// Settings Section ID
		);		
}



/**
 * Get Pre Loader settings
 *
 * @since 1.11
 */
function superpwa_pre_loader_get_settings() {
	
	$defaults = array(
                'loading_icon'  => '0',
				'loading_icon_color'=> '#3498db',
                'loading_icon_bg_color'  => '#ffffff',
                'loading_icon_display_pwa' => '0',
                'loading_icon_display_desktop' => '0',
                'loading_icon_display_mobile' => '0',
                'loading_icon_display_admin' => '0',
                'loading_icon_selector' => 'superpwa_loading_icon',
			);
	
	return get_option( 'superpwa_pre_loader_settings',$defaults);
}




/**
	 * Callbacks function for Pre Loader section
	 *
	 * @since 2.1.18
	 */
	function superpwa_pre_loader_section_cb(){
		echo esc_html__("This add-on makes easy for your users to Set the PreLoading Feature and provide an eye catchy loading functionality to the site.", "super-progressive-web-apps-pro");
	}


	function superpwa_pre_loader_icon_cb(){
		$settings = $this->superpwa_pre_loader_get_settings();
	

		echo '<fieldset><input data-id="loading_icon" class="superpwa-checkbox" id="loading_icon" name="superpwa_pre_loader_settings[loading_icon]" value="1" type="checkbox" '.((isset($settings['loading_icon']) && $settings['loading_icon'] == '1') ? 'checked=""' : "").'></fieldset>
			<p>'.esc_html__('This helps show loading icon on page or post load', 'super-progressive-web-apps-pro').'</p>';
	}

  	function superpwa_pre_loader_color_cb(){
		$settings = $this->superpwa_pre_loader_get_settings();
		echo '<fieldset><input id="pre_loader_color" name="superpwa_pre_loader_settings[loading_icon_color]" type="text" class="superpwa-colorpicker" value="'.(isset( $settings['loading_icon_color'] ) ? sanitize_hex_color( $settings['loading_icon_color']) : '#3498db').'" data-default-color="#3498db"></fieldset><p>'.esc_html__('Change the icon color of loader', 'super-progressive-web-apps-pro').'</p>';
	}

  	function superpwa_pre_loader_bg_color_cb(){
		$settings = $this->superpwa_pre_loader_get_settings();
		echo '<fieldset><input id="pre_loader_bg_color" name="superpwa_pre_loader_settings[loading_icon_bg_color]" type="text" class="superpwa-colorpicker" value="'.(isset( $settings['loading_icon_bg_color'] ) ? sanitize_hex_color( $settings['loading_icon_bg_color']) : '#ffffff').'" data-default-color="#ffffff"></fieldset><p>'.esc_html__('Change the background color of loader icon', 'super-progressive-web-apps-pro').'</p>';
	}

  	function superpwa_pre_loader_only_pwa_cb(){
		$settings = $this->superpwa_pre_loader_get_settings();
	

		echo '<fieldset><input data-id="loading_icon_display_pwa" class="superpwa-checkbox" id="loading_icon_display_pwa" name="superpwa_pre_loader_settings[loading_icon_display_pwa]" value="1" type="checkbox" '.((isset($settings['loading_icon_display_pwa']) && $settings['loading_icon_display_pwa'] == '1') ? 'checked=""' : "").'></fieldset>';
	}
	
	function superpwa_pre_loader_display_settings_cb(){	

    $settings = $this->superpwa_pre_loader_get_settings(); 

    if(!isset($settings['loading_icon_display_admin'])){
    	$settings['loading_icon_display_admin'] = 0;
    }

    echo '<fieldset>
    			<label><input data-id="loading_icon_display_desktop" class="superpwa-checkbox" id="loading_icon_display_desktop" name="superpwa_pre_loader_settings[loading_icon_display_desktop]" value="1" type="checkbox" '.((isset($settings['loading_icon_display_desktop']) && $settings['loading_icon_display_desktop'] == '1') ? 'checked=""' : "").'>&nbsp;'.esc_html__('Desktop', 'super-progressive-web-apps-pro').'</label></fieldset>
			<fieldset><label><input data-id="loading_icon_display_mobile" class="superpwa-checkbox" id="loading_icon_display_mobile" name="superpwa_pre_loader_settings[loading_icon_display_mobile]" value="1" type="checkbox" '.((isset($settings['loading_icon_display_mobile']) && $settings['loading_icon_display_mobile'] == '1') ? 'checked=""' : "").'>&nbsp;'.esc_html__('Mobile', 'super-progressive-web-apps-pro').'</label></fieldset>
			<fieldset><label><input data-id="loading_icon_display_admin" class="superpwa-checkbox" id="loading_icon_display_admin" name="superpwa_pre_loader_settings[loading_icon_display_admin]" value="1" type="checkbox" '.((isset($settings['loading_icon_display_admin']) && $settings['loading_icon_display_admin'] == '1') ? 'checked=""' : "").'>&nbsp;'.esc_html__('Admin', 'super-progressive-web-apps-pro').'</label>
		</fieldset>';
}
     // Loading Icon Selector Callback Function
	function superpwa_pre_loading_icon_selector_callback(){
         $allIcon = self::get_icon_library();
		$settings = $this->superpwa_pre_loader_get_settings();
		?>
		<div class="superpwa-loader-wrapper">
			<div class="superpwa-loader-preview">
				<?php
				if( (isset($settings['loading_icon_selector']) && $settings['loading_icon_selector']=='superpwa_loading_icon') || !isset($settings['loading_icon_selector']) ) {
					echo '<style>'.$this->default_loader_css().'</style><div id="superpwa_loading_icon"></div>';
				}else{
					if(isset($settings['loading_icon_selector']) && isset($allIcon[$settings['loading_icon_selector']])){
						echo $allIcon[$settings['loading_icon_selector']]['content_html'];
					}
				}
				?>
			</div>
			 <a href="javascript:void(0)" data-target="superpwaloadingicon" class="superpwaLoadingOptPreview" title="Select Loader icon"> Change loading icon</a>
			<input type="hidden" name="superpwa_pre_loader_settings[loading_icon_selector]" id="superpwa-icon-selector" value="<?php echo $settings['loading_icon_selector']; ?>">
		</div>
		<div id="superpwaloadingicon" style="display: none;">
			<div class="superpwa-icon-list-ul">
				<?php 
				foreach ($allIcon as $key => $value) {
					$loaderColorCss = $value['css'];
					if(isset($settings['loading_icon_color']) && !empty($settings['loading_icon_color'])){
						$loaderColorCss = str_replace("{{selected_color}}", $settings['loading_icon_color'], $loaderColorCss);
					}else{
						$loaderColorCss = str_replace("{{selected_color}}", '#3498db', $loaderColorCss);
					}
					echo '<div class="superpwa-loader loader-selection-data" data-loader-name="'.$key.'"><style>'.$loaderColorCss.'</style>'.$value['content_html'].'</div>';
				}
				?>
			</div>
		</div>
		<?php
	}

		function load_scripts($hooks){

			if (!in_array($hooks, array('superpwa_page_superpwa-pre-loader', 'super-pwa_page_superpwa-pre-loader')) && strpos($hooks, 'superpwa-pre-loader') == false) {
              return false;
            }

			add_thickbox();
			wp_enqueue_style( 'loading-icon-main-css', SUPERPWA_PRO_PATH_SRC . 'assets/css/loading-icon-admin.css',array(), SUPERPWA_PRO_VERSION,'all' );
			wp_enqueue_script( 'loading-icon-main-js', SUPERPWA_PRO_PATH_SRC . 'assets/js/loading-icon-admin.js',array('jquery'), SUPERPWA_PRO_VERSION,true );      
			$allIcon = self::get_icon_library();
			wp_localize_script('loading-icon-main-js', 'loaders_template', $allIcon);
		}

	public static function get_icon_library($icon_class=''){
		$iconlib = include  SUPERPWA_PRO_PATH_ABS . 'addons/icon-library.php';
		if($icon_class){
			if(isset($iconlib[$icon_class])){
				return $iconlib[$icon_class];
			}else{ return ''; }
		}
		return $iconlib;
	}
	public function default_loader_css(){
		return "#superpwa_loading_div {
				width: 100%;
				height: 200%;
				position: fixed;
				top: 0;
				left: 0;
				background-color: white;
				z-index: 500;
				}
				#superpwa_loading_icon {
				  border: 16px solid #f3f3f3;
				    border-radius: 50%;
				    border-top: 16px solid #3498db;
				    width: 25px;
				    height: 25px;
				    -webkit-animation: spin 2s linear infinite;
				    animation: spin 2s linear infinite;
				}";
	}
/**
 * Pre Loader UI renderer
 *
 * @since 2.1.18
 */ 
function superpwa_pre_loader_interface_render() {
	
	// Authentication
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	
	// Handing save settings
	if ( isset( $_GET['settings-updated'] ) ) {
		
		// Add settings saved message with the class of "updated"
		add_settings_error( 'superpwa_settings_group', 'superpwa_pre_loader_settings_saved_message', __( 'Settings Saved.', 'super-progressive-web-apps' ), 'updated' );
		
		// Show Settings Saved Message
		settings_errors( 'superpwa_settings_group' );
	}
	// Get add-on info
	$addon_utm_tracking = superpwa_get_addons( 'pre_loader' );

	superpwa_setting_tabs_styles();
	?>
	<div class="wrap">
		<h1><?php _e( 'PreLoader', 'super-progressive-web-apps' ); ?> <small>(<a href="<?php echo esc_url($addon_utm_tracking['link']) . '?utm_source=superpwa-plugin&utm_medium=utm-tracking-settings'?>"><?php echo esc_html__( 'Docs', 'super-progressive-web-apps-pro' ); ?></a>)</small></h1>

		  <?php superpwa_setting_tabs_html(); ?>
          
		<form action="options.php" method="post" enctype="multipart/form-data">		
			<?php
			// Output nonce, action, and option_page fields for a settings page.
			settings_fields( 'superpwa_pre_loader_settings_group' );
			
			// Status
			do_settings_sections( 'superpwa_pre_loader_section' );	// Page slug
			
			submit_button( __('Save Settings', 'super-progressive-web-apps-pro') );
			?>
		</form>
	</div>
    <?php superpwa_newsletter_form(); ?>
	<?php
}

// Frontend Loading Icon Html
function superpwa_pre_loading_icon_html() {
    
    if( is_preview() || (function_exists('is_preview_mode') && is_preview_mode()) ){return false;}
    $settings = $this->superpwa_pre_loader_get_settings();
   
        $color = (isset($settings['loading_icon_color']) && !empty($settings['loading_icon_color']))? $settings['loading_icon_color'] : '';
        $bgcolor = (isset($settings['loading_icon_bg_color']) && !empty($settings['loading_icon_bg_color']))? $settings['loading_icon_bg_color'] : '#ffffff';
        $color_style = $bg_color_style = '';
        if($color){
            $color_style = 'style="border-top-color: '.$color.'"';
        }
        if($bgcolor!=='#ffffff'){ $bg_color_style = 'style="background-color: '.$bgcolor.'"'; }
        echo '<div id="superpwa_loading_div" '.$bg_color_style.'></div>';
        echo apply_filters('superpwa_loading_contents', '<div class="superpwa-loading-wrapper"><div id="superpwa_loading_icon"  '.$color_style.'></div></div>');   
}

function superpwa_loading_contents_lib_div($defaultContent){
		$allIcon = self::get_icon_library();
		 $settings = $this->superpwa_pre_loader_get_settings();
		if(isset($allIcon[$settings['loading_icon_selector']])){
			$loaderColorCss = $allIcon[$settings['loading_icon_selector']]['css'];
			if(isset($settings['loading_icon_color']) && !empty($settings['loading_icon_color'])){
				$loaderColorCss = str_replace("{{selected_color}}", $settings['loading_icon_color'], $loaderColorCss);
			}else{
				$loaderColorCss = str_replace("{{selected_color}}", '#3498db', $loaderColorCss);
			}
			$defaultContent = "<style>.superpwa-loading-wrapper{position: fixed; left: auto;top: 45%;z-index: 99999;margin: 0 auto;color:#3498db;display: flex;align-items: center;width: 100%;}.superpwa-loading-wrapper>div{margin: 0 auto;}".$loaderColorCss."</style>";
			$defaultContent .= "<div class='superpwa-loading-wrapper'>".$allIcon[$settings['loading_icon_selector']]['content_html']."</div>";
		}
		return $defaultContent;
	}
// Enqueue Scripts and Styles in Frontend

function superpwa_pre_loading_frontend_enqueue_scripts(){
   
        $settings = $this->superpwa_pre_loader_get_settings();               
            
            wp_register_script('superpwa-preloader-js', SUPERPWA_PRO_PATH_SRC . '/assets/js/preloader.min.js',array(), SUPERPWA_VERSION, true); 
            
            $loader_desktop = $loader_mobile = $loader_only_pwa = 0;
            //For desktop
            if( isset($settings['loading_icon_display_pwa']) && !empty($settings['loading_icon_display_pwa']) ){
                $loader_only_pwa = $settings['loading_icon_display_pwa'];
            }
            //For desktop
            if(isset($settings['loading_icon_display_desktop'])){
                $loader_desktop = $settings['loading_icon_display_desktop'];
            }

            //For mobile
            if(isset($settings['loading_icon_display_mobile'])){
                $loader_mobile = $settings['loading_icon_display_mobile'];
            }

            $object_js_name = array(
              'ajax_url'       => admin_url( 'admin-ajax.php' ),
              'loader_desktop' => $loader_desktop,
              'loader_mobile'  => $loader_mobile,
              'loader_only_pwa'  => $loader_only_pwa,
              'reset_cookies'  => $reset_cookies,
            );
            
            wp_localize_script('superpwa-preloader-js', 'superpwa_preloader_obj', $object_js_name);
            
            wp_enqueue_script('superpwa-preloader-js'); 

            wp_enqueue_style( 'preloader-style', SUPERPWA_PRO_PATH_SRC . '/assets/css/preloader-main.min.css', false , SUPERPWA_VERSION ); 
            
}

//add_action( 'wp_enqueue_scripts', 'superpwa_pre_loading_frontend_enqueue_scripts', 35 );

}

function superpwapro_preloading(){
	return SPWAP_PreLoading::get_instance();
}
superpwapro_preloading();