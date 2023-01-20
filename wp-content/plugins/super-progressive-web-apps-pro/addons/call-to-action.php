<?php
/**
 * Call To Action
 *
 * @since 1.8
 * 
 * @Class	SPWAPcalltoaction()			Add all features of call to action
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Features of Call to action
 */
class SPWAPcalltoaction
{
	/**
	 * get the unique instance of the class
	 * @var SPWAPcalltoaction
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
	function __construct()
	{
		if(is_admin()){
			add_action( 'admin_menu', array($this, 'superpwa_call_to_action_sub_menu') );
			add_action( 'admin_init', array($this, 'superpwa_call_to_action_register_settings') );
			add_filter( 'wp_edit_nav_menu_walker', array($this, 'superpwa_filterwp_edit_navmenuwalker') , 99 );
			require_once SUPERPWA_PRO_PLUGIN_DIR_NAME.'/addons/custom-menu/menu-item-custom-fields.php';
		}else{
			add_action( 'wp_footer', array($this, 'superpwa_call_to_action_sticky_banner') );
			add_action( 'wp_enqueue_scripts', array($this, 'superpwa_cta_front_enque') );
			add_filter( 'superpwa_sw_localize_data', array($this, 'superpwa_cta_localize_update') );
			add_shortcode( 'superpwa-add-to-home-button', array($this, 'shortcode_button_add_to_home') );
			add_action( 'init', array($this, 'superpwa_add_error_template_query_var'), 10 );
			add_filter( 'parse_query', array($this, 'superpwa_install_page_template'), 10 );

			add_filter( 'nav_menu_link_attributes',array($this, 'superpwa_cta_nav_menu_link_atts'), 12, 4 );

			add_filter( 'nav_menu_css_class', array($this, 'superpwa_cta_special_nav_class'), 10, 2 );
			add_action( 'wp_footer', array($this, 'superpwa_menu_ctafp_dynamic_css'),99 );

		}
	}
	/**
     * Gets an instance of our SPWAPcalltoaction class.
     *
     * @return SPWAPcalltoaction Object
     */
	public static function get_instance(){
		if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
	}
	/**
	 * Get Call To Action settings
	 *
	 * @since 1.7
	 */
	function superpwa_call_to_action_get_settings() {
		
		$defaults = array(
					'add_to_home_msg'					=> 'Welcome to %sitename%', //Message
					'add_to_home_btn_text'				=> esc_html__('Install', 'super-progressive-web-apps-pro'),//Button Text
					'add_to_home_pos'					=> 'top',//Position
					'a2h_sticky_on_desktop'				=> '0',//Show on Desktop CTA
					'a2h_sticky_hideon_ios'			    => '0',//Hide on ios CTA
					'a2h_sticky_show_only_on_ios'		=> '0',//show onlyon on ios CTA
					'a2h_banner_without_scroll_cta'		=> '0',//Show CTA banner without scroll
					'a2h_banner_on_scroll'		=> '1',
					//Show CTA banner on scroll
					'a2h_banner_delay_cta'		=> '0',
					//Show CTA banner delay
					'a2h_banner_delay_sec_cta'		=> 5,
					//Show CTA banner delay secs

					'a2h_menu_button_cta'		=> '0',// Menu Button as CTA
					'bar_bg_color'						=> '#1f1f1f',//Bar Background Color
					'bar_container_bg_color'			=> '#ff416c',//Container Background Color
					'bar_text_color'					=> '#ffffff',//Bar Text Color
					'bar_btn_bg_color'					=> '#ffffff',//Button Background Color
					'bar_btn_text_color'				=> '#ff416a',//Button Text Color
					'bar_btn_font_size'					=> '13',//Button Font Size
					'ios_message'						=> esc_html__('Install this %sitename% on your iPhone %ICON_INSTALL% and then %bold% Add to Home Screen %/bold%','super-progressive-web-apps-pro'),
					'ios_chrome_msg'						=> esc_html__('Currently PWA is not supported in iOS Chrome So follow below steps:','super-progressive-web-apps-pro'),
					'ios_chrome_hscrn'						=> esc_html__('Add to Home Screen','super-progressive-web-apps-pro')
				);
		
		return get_option( 'superpwa_call_to_action_settings', $defaults );
	}

	/**
	 * Register Call To Action settings
	 *
	 * @since 	1.7
	 */
	function superpwa_call_to_action_register_settings(){
		// Register Setting
		register_setting( 
			'superpwa_call_to_action_settings_group',		 // Group name
			'superpwa_call_to_action_settings', 			// Setting name = html form <input> name on settings form
			'superpwa_call_to_action_validater_sanitizer'	// Input validator and sanitizer
		);
		// Call to action
	    add_settings_section(
	        'superpwa_call_to_action_section',				// ID
	        __return_false(),								// Title
	        array($this, 'superpwa_call_to_action_section_cb'),				// Callback Function
	        'superpwa_call_to_action_section'					// Page slug
	    );
	    // Message
		add_settings_field(
			'superpwa_call_to_action_button',						// ID
			__('Message', 'super-progressive-web-apps-pro'),	// Title
			array($this, 'superpwa_call_to_action_message_cb'),					// CB
			'superpwa_call_to_action_section',						// Page slug
			'superpwa_call_to_action_section'							// Settings Section ID
		);
		// Button Text
		add_settings_field(
			'superpwa_call_to_action_button',						// ID
			__('Button Text', 'super-progressive-web-apps-pro'),	// Title
			array($this, 'superpwa_call_to_action_button_cb'),					// CB
			'superpwa_call_to_action_section',						// Page slug
			'superpwa_call_to_action_section'							// Settings Section ID
		);
		// Position
		add_settings_field(
			'superpwa_call_to_action_position',						// ID
			__('Position', 'super-progressive-web-apps-pro'),	// Title
			array($this, 'superpwa_call_to_action_position_cb'),					// CB
			'superpwa_call_to_action_section',						// Page slug
			'superpwa_call_to_action_section'							// Settings Section ID
		);
		// CTA Banner Display
		add_settings_field(
			'superpwa_call_to_action_display',						// ID
			__('CTA Banner Display', 'super-progressive-web-apps-pro'),	// Title
			array($this, 'superpwa_call_to_action_display_cb'),					// CB
			'superpwa_call_to_action_section',						// Page slug
			'superpwa_call_to_action_section'							// Settings Section ID
		);
		// Show on Desktop CTA
		add_settings_field(
			'superpwa_call_to_action_ondesktop',						// ID
			__('Show on Desktop CTA', 'super-progressive-web-apps-pro'),	// Title
			array($this, 'superpwa_call_to_action_ondesktop_cb'),					// CB
			'superpwa_call_to_action_section',						// Page slug
			'superpwa_call_to_action_section'							// Settings Section ID
		);

		// Show Only on iOS Device
		add_settings_field(
			'superpwa_call_to_action_showonlyonios',						// ID
			__('Show CTA Only on iOS Device', 'super-progressive-web-apps-pro'),	// Title
			array($this, 'superpwa_call_to_action_showonlyon_ios_device_cb'),					// CB
			'superpwa_call_to_action_section',						// Page slug
			'superpwa_call_to_action_section'							// Settings Section ID
		);
		
		// Hide on iOS Device
		add_settings_field(
			'superpwa_call_to_action_hideonios',						// ID
			__('Hide CTA banner on iOS Device', 'super-progressive-web-apps-pro'),	// Title
			array($this, 'superpwa_call_to_action_hideonios_device_cb'),					// CB
			'superpwa_call_to_action_section',						// Page slug
			'superpwa_call_to_action_section'							// Settings Section ID
		);
			// Menu Button
		add_settings_field(
			'superpwa_call_to_action_menu_button',						// ID
			__('Menu Button', 'super-progressive-web-apps-pro'),	// Title
			array($this, 'superpwa_call_to_action_menu_button_cb'),					// CB
			'superpwa_call_to_action_section',						// Page slug
			'superpwa_call_to_action_section'							// Settings Section ID
		);
		// Bar Background Color
		add_settings_field(
			'superpwa_call_to_action_barbgcolor',						// ID
			__('Bar Background Color', 'super-progressive-web-apps-pro'),	// Title
			array($this, 'superpwa_call_to_action_barbgcolor_cb'),					// CB
			'superpwa_call_to_action_section',						// Page slug
			'superpwa_call_to_action_section'							// Settings Section ID
		);
		// Container Background Color
		add_settings_field(
			'superpwa_call_to_action_containerbgcolor',						// ID
			__('Container Background Color', 'super-progressive-web-apps-pro'),	// Title
			array($this, 'superpwa_call_to_action_containerbgcolor_cb'),					// CB
			'superpwa_call_to_action_section',						// Page slug
			'superpwa_call_to_action_section'							// Settings Section ID
		);
		// Bar Text Color
		add_settings_field(
			'superpwa_call_to_action_bartextcolor',						// ID
			__('Bar Text Color', 'super-progressive-web-apps-pro'),	// Title
			array($this, 'superpwa_call_to_action_bartextcolor_cb'),					// CB
			'superpwa_call_to_action_section',						// Page slug
			'superpwa_call_to_action_section'							// Settings Section ID
		);
		// Button Background Color
		add_settings_field(
			'superpwa_call_to_action_buttonbgcolor',						// ID
			__('Button Background Color', 'super-progressive-web-apps-pro'),	// Title
			array($this, 'superpwa_call_to_action_buttonbgcolor_cb'),					// CB
			'superpwa_call_to_action_section',						// Page slug
			'superpwa_call_to_action_section'							// Settings Section ID
		);
		// Button Text Color
		add_settings_field(
			'superpwa_call_to_action_buttontextcolor',						// ID
			__('Button Text Color', 'super-progressive-web-apps-pro'),	// Title
			array($this, 'superpwa_call_to_action_buttontextcolor_cb'),					// CB
			'superpwa_call_to_action_section',						// Page slug
			'superpwa_call_to_action_section'							// Settings Section ID
		);
		// Button Font Size
		add_settings_field(
			'superpwa_call_to_action_buttonfontsize',						// ID
			__('Button Font Size', 'super-progressive-web-apps-pro'),	// Title
			array($this, 'superpwa_call_to_action_buttonfontsize_cb'),					// CB
			'superpwa_call_to_action_section',						// Page slug
			'superpwa_call_to_action_section'							// Settings Section ID
		);
		// Install Button Text
		add_settings_field(
			'superpwa_call_to_action_installbuttontext',						// ID
			__('Install Button Text', 'super-progressive-web-apps-pro'),	// Title
			array($this, 'superpwa_call_to_action_installbuttontext_cb'),					// CB
			'superpwa_call_to_action_section',						// Page slug
			'superpwa_call_to_action_section'							// Settings Section ID
		);
		add_settings_section('superpwa_cta_ios_setting_section', esc_html__('iOS popup','super-progressive-web-apps-pro'), '__return_false', 'superpwa_call_to_action_section');
	    add_settings_field(
	        'superpwa_cta_ios_setting',                          // ID
	        esc_html__('Message', 'super-progressive-web-apps-pro'), // Title
	        array($this, 'superpwa_ios_translate_text_cb'),      // CB
	        'superpwa_call_to_action_section',                      // Page slug
	        'superpwa_cta_ios_setting_section'                       // Settings Section ID
	    );
	    add_settings_field(
	        'superpwa_cta_ios_chrome_msg_setting',                          // ID
	        esc_html__('iOS Chrome Message', 'super-progressive-web-apps-pro'), // Title
	        array($this, 'superpwa_ios_chrome_translate_text_cb'),      // CB
	        'superpwa_call_to_action_section',                      // Page slug
	        'superpwa_cta_ios_setting_section'                       // Settings Section ID
	    );
	    add_settings_field(
	        'superpwa_cta_ios_chrome_homescreen_setting',                          // ID
	        esc_html__('Add to Home Screen', 'super-progressive-web-apps-pro'), // Title
	        array($this, 'superpwa_ios_chrome_homescreen_translate_text_cb'),      // CB
	        'superpwa_call_to_action_section',                      // Page slug
	        'superpwa_cta_ios_setting_section'                       // Settings Section ID
	    );

	    add_settings_section('superpwa_shortcode_setting_section', esc_html__('Place via shortcode','super-progressive-web-apps-pro'), '__return_false', 'superpwa_call_to_action_section');
	    add_settings_field(
	        'superpwa_shortcode_opt_setting',                          // ID
	         esc_html__('Use Shortcode', 'super-progressive-web-apps-pro'), // Title
	        array($this, 'superpwa_shortcode_text_cb'),      // CB
	        'superpwa_call_to_action_section',                      // Page slug
	        'superpwa_shortcode_setting_section'                       // Settings Section ID
	    );

	   add_settings_field(
	        'superpwa_shortcode_opt_button_text_setting',                          // ID
	         esc_html__('Button Text', 'super-progressive-web-apps-pro'), // Title
	        array($this, 'superpwa_shortcode_buttontext_cb'),      // CB
	        'superpwa_call_to_action_section',                      // Page slug
	        'superpwa_shortcode_setting_section'                       // Settings Section ID
	    );
	}
	/**
	 * Validate and sanitize user input
	 *
	 * @since 1.7
	 */
	function superpwa_call_to_action_validater_sanitizer( $settings ) {
		
		// Sanitize and validate campaign source. Campaign source cannot be empty.
		$settings['add_to_home_msg'] = sanitize_text_field( $settings['add_to_home_msg'] ) == '' ? 'Welcome' : sanitize_text_field( $settings['add_to_home_msg'] );
		
		// Sanitize Button Text
		$settings['add_to_home_btn_text'] = sanitize_text_field( $settings['add_to_home_btn_text'] ) == '' ? 'Install' : sanitize_text_field( $settings['add_to_home_btn_text'] );
		
		// Sanitize Position
		$settings['add_to_home_pos'] = sanitize_text_field( $settings['add_to_home_pos'] ) == '' ? 'superpwa' : sanitize_text_field( $settings['add_to_home_pos'] );
		
		// Sanitize Show on Desktop CTA
		$settings['a2h_sticky_on_desktop'] = sanitize_text_field( $settings['a2h_sticky_on_desktop'] )==''? 0: sanitize_text_field( $settings['a2h_sticky_on_desktop'] );

		// Sanitize Show on iOS CTA
		$settings['a2h_sticky_hideon_ios'] = sanitize_text_field( $settings['a2h_sticky_hideon_ios'] )==''? 0: sanitize_text_field( $settings['a2h_sticky_hideon_ios'] );

		// Sanitize Show only iOS CTA
		$settings['a2h_sticky_show_only_on_ios'] = sanitize_text_field( $settings['a2h_sticky_show_only_on_ios'] )==''? 0: sanitize_text_field( $settings['a2h_sticky_show_only_on_ios'] );
		
		// Sanitize Show CTA banner without scroll
		$settings['a2h_banner_without_scroll_cta'] = sanitize_text_field( $settings['a2h_banner_without_scroll_cta'] )==''? 0: sanitize_text_field( $settings['a2h_banner_without_scroll_cta'] );
		// Sanitize Show CTA banner on scroll
		$settings['a2h_banner_on_scroll'] = sanitize_text_field( $settings['a2h_banner_on_scroll'] )==''? 1: sanitize_text_field( $settings['a2h_banner_on_scroll'] );

		// Sanitize Show CTA banner delay
		$settings['a2h_banner_delay_cta'] = sanitize_text_field( $settings['a2h_banner_delay_cta'] )==''? 0: sanitize_text_field( $settings['a2h_banner_delay_cta'] );
		// Sanitize Show CTA banner delay secs
		$settings['a2h_banner_delay_sec_cta'] = sanitize_text_field( $settings['a2h_banner_delay_sec_cta'] )==''? 5: sanitize_text_field( $settings['a2h_banner_delay_sec_cta'] );

		// Sanitize Menu Button
		$settings['a2h_menu_button_cta'] = sanitize_text_field( $settings['a2h_menu_button_cta'] )==''? 0: sanitize_text_field( $settings['a2h_menu_button_cta'] );

		//Bar Background Color
		$settings['bar_bg_color'] = sanitize_text_field( $settings['bar_bg_color'] )==''? '#1f1f1f': sanitize_text_field( $settings['bar_bg_color'] );

		//Container Background Color
		$settings['bar_container_bg_color'] = sanitize_text_field( $settings['bar_container_bg_color'] )==''? '#ff416c': sanitize_text_field( $settings['bar_container_bg_color'] );

		//Bar Text Color
		$settings['bar_text_color'] = sanitize_text_field( $settings['bar_text_color'] )==''? '#ffffff': sanitize_text_field( $settings['bar_text_color'] );

		//Button Background Color
		$settings['bar_btn_bg_color'] = sanitize_text_field( $settings['bar_btn_bg_color'] )==''? '#ffffff': sanitize_text_field( $settings['bar_btn_bg_color'] );

		//Button Text Color
		$settings['bar_btn_text_color'] = sanitize_text_field( $settings['bar_btn_text_color'] )==''? '#ff416a': sanitize_text_field( $settings['bar_btn_text_color'] );

		//Button Font Size
		$settings['bar_btn_font_size'] = sanitize_text_field( $settings['bar_btn_font_size'] )==''? '13': sanitize_text_field( $settings['bar_btn_font_size'] );

		//iOS message 
		$settings['ios_message'] = sanitize_text_field( $settings['ios_message'] )==''? esc_html__('Install this %sitename% on your iPhone %ICON_INSTALL% and then %bold% Add to Home Screen %/bold%', 'super-progressive-web-apps-pro'): sanitize_text_field( $settings['ios_message'] );
		
        //Shortcode button text
		$settings['shortcode_btn_text'] = sanitize_text_field( $settings['shortcode_btn_text'] ) == '' ? 'Install APP' : sanitize_text_field( $settings['shortcode_btn_text'] );
		
		return $settings;
	}
	/**
	 * Callbacks function for Call to action section
	 *
	 * @since 1.0
	 */
	function superpwa_call_to_action_section_cb(){
		echo esc_html__("This add-on makes easy for your users to add the website to the home screen. It consists of multiple features like Call to action bar which will help you to implement the ‘Add to Homescreen’ popup in the output with full control over it.", "super-progressive-web-apps-pro");
	}
	function superpwa_call_to_action_button_cb(){
		$settings = $this->superpwa_call_to_action_get_settings();
		echo '<fieldset><input name="superpwa_call_to_action_settings[add_to_home_msg]" id="add_to_home_msg" class="regular-text " type="text" placeholder="Welcome to my site" value="'.(isset($settings['add_to_home_msg']) && !empty($settings['add_to_home_msg']) ? $settings['add_to_home_msg'] : "Welcome to %sitename%").'"></fieldset>';
	}
	function superpwa_call_to_action_position_cb(){
		$settings = $this->superpwa_call_to_action_get_settings();
		echo '<fieldset><select name="superpwa_call_to_action_settings[add_to_home_pos]" id="add_to_home_pos" class="regular-text">'
                . '<option value="top" '.((isset($settings['add_to_home_pos']) && $settings['add_to_home_pos'] == 'top') ? 'selected="selected"' : "").'>Top</option>'
                . '<option value="bottom" '.((isset($settings['add_to_home_pos']) && $settings['add_to_home_pos'] == 'bottom') ? 'selected="selected"' : "").'>Bottom</option>'
                . '</select></fieldset>';
	}
	function superpwa_call_to_action_ondesktop_cb(){
		$settings = $this->superpwa_call_to_action_get_settings();
		echo '<fieldset><input data-id="a2h_sticky_on_desktop" class="superpwa-checkbox" id="a2h_sticky_on_desktop" name="superpwa_call_to_action_settings[a2h_sticky_on_desktop]" value="1" type="checkbox" '.((isset($settings['a2h_sticky_on_desktop']) && $settings['a2h_sticky_on_desktop'] == '1') ? 'checked=""' : "").'></fieldset>';
	}
	function superpwa_call_to_action_hideonios_device_cb(){
		$settings = $this->superpwa_call_to_action_get_settings();

		echo '<fieldset><input data-id="a2h_sticky_hideon_ios" class="superpwa-checkbox" id="a2h_sticky_hideonon_ios" name="superpwa_call_to_action_settings[a2h_sticky_hideon_ios]" value="1" type="checkbox" '.((isset($settings['a2h_sticky_hideon_ios']) && $settings['a2h_sticky_hideon_ios'] == '1') ? 'checked=""' : "").'></fieldset>';
	}

	function superpwa_call_to_action_showonlyon_ios_device_cb(){
		$settings = $this->superpwa_call_to_action_get_settings();

		echo '<fieldset><input data-id="a2h_sticky_show_only_on_ios" class="superpwa-checkbox" id="a2h_sticky_showonlyon_ios" name="superpwa_call_to_action_settings[a2h_sticky_show_only_on_ios]" value="1" type="checkbox" '.((isset($settings['a2h_sticky_show_only_on_ios']) && $settings['a2h_sticky_show_only_on_ios'] == '1') ? 'checked=""' : "").'></fieldset>';
	}

	function superpwa_call_to_action_display_cb(){
		$settings = $this->superpwa_call_to_action_get_settings();
		$delay_value = ( isset($settings['a2h_banner_delay_sec_cta']) && !empty( $settings['a2h_banner_delay_sec_cta'] ) )? $settings['a2h_banner_delay_sec_cta'] : 5;
		echo '
		      <style>.display-label{font-size:15px;} .label-show{padding-right:40px;} .label-onscroll{padding-right:68px;}.label-delay-cta{padding-right:15px;}.display-subchild{padding: 12px 12px 10px 25px;background: #ffffff;box-sizing: border-box;width: 30%; margin: 12px 15px 12px 15px;border-radius: 15px;}.hide,#display-subchild .hide{display:none}.cta-onscroll{margin-top:10px;}#a2h_banner_delay_sec_cta{width:30%;}.delay-cta-span{display:inline-block;}</style>
               <script>
		            function hideChildOption(e){
		               var subchild =  document.getElementById("display-subchild");

		               if(e.checked == false){
		               	subchild.classList.add("hide");
		               }else{
		               	subchild.classList.remove("hide");
		               }
		            }
		            function hideDelayOption(e){
		               var delaychild =  document.getElementById("delay-in-secs");

		               if(e.checked == false){
		               	delaychild.classList.add("hide");
		               }else{
		               	delaychild.classList.remove("hide");
		               }
		            }
            </script>
		      <fieldset>
                 <div class="display-cta">
			      <label class="display-label label-show"> Always Show</label> 
			      <input data-id="a2h_banner_without_scroll_cta" class="superpwa-checkbox"  onclick="hideChildOption(this)" id="a2h_banner_without_scroll_cta" name="superpwa_call_to_action_settings[a2h_banner_without_scroll_cta]" value="1" type="checkbox" '.((isset($settings['a2h_banner_without_scroll_cta']) && $settings['a2h_banner_without_scroll_cta'] == '1') ? 'checked=""' : "").'>
	                      <div id="display-subchild" class="display-cta display-subchild '.((isset($settings['a2h_banner_without_scroll_cta']) && $settings['a2h_banner_without_scroll_cta'] == '1') ? '' : 'hide').'">
				           <label class="display-label label-delay-cta">Delay</label>
				            <input data-id="a2h_banner_delay_cta" class="superpwa-checkbox"  onclick="hideDelayOption(this)" id="a2h_banner_delay_cta" name="superpwa_call_to_action_settings[a2h_banner_delay_cta]" value="1" type="checkbox" '.((isset($settings['a2h_banner_delay_cta']) && $settings['a2h_banner_delay_cta'] == '1') ? 'checked=""' : "").'>
                           <span id="delay-in-secs" class="delay-cta-span  '.((isset($settings['a2h_banner_delay_cta']) && $settings['a2h_banner_delay_cta'] == '1') ? '' : 'hide').'">
				             <label class="display-label label-delay-cta">Delay in seconds</label>
				            <input data-id="a2h_banner_delay_sec_cta" class="superpwa-text" id="a2h_banner_delay_sec_cta" name="superpwa_call_to_action_settings[a2h_banner_delay_sec_cta]" value="'.$delay_value.'" type="number" min="1">
				            </span>
                         </div>
                 <div class="display-cta cta-onscroll">
	              <label class="display-label label-onscroll" >Show On Scroll</label> <input data-id="a2h_banner_on_scroll" class="superpwa-checkbox" id="a2h_banner_on_scroll" name="superpwa_call_to_action_settings[a2h_banner_on_scroll]" value="1" type="checkbox" '.((isset($settings['a2h_banner_on_scroll']) && $settings['a2h_banner_on_scroll'] == '1') ? 'checked=""' : "").'>
	              </div>
		      </fieldset>';
	}
	function superpwa_call_to_action_menu_button_cb(){
		$settings = $this->superpwa_call_to_action_get_settings();
		echo '<fieldset><input data-id="a2h_menu_button_cta" class="superpwa-checkbox" id="a2h_menu_button_cta" name="superpwa_call_to_action_settings[a2h_menu_button_cta]" value="1" type="checkbox" '.((isset($settings['a2h_menu_button_cta']) && $settings['a2h_menu_button_cta'] == '1') ? 'checked=""' : "").'></fieldset>';
	}
	function superpwa_call_to_action_barbgcolor_cb(){
		$settings = $this->superpwa_call_to_action_get_settings();
		echo '<fieldset><input id="bar_bg_color" name="superpwa_call_to_action_settings[bar_bg_color]" type="text" class="superpwa-colorpicker" value="'.(isset( $settings['bar_bg_color'] ) ? sanitize_hex_color( $settings['bar_bg_color']) : '#1f1f1f').'" data-default-color="#1f1f1f"></fieldset>';
	}
	function superpwa_call_to_action_containerbgcolor_cb(){
		$settings = $this->superpwa_call_to_action_get_settings();
		echo '<fieldset><input id="bar_container_bg_color" name="superpwa_call_to_action_settings[bar_container_bg_color]" type="text" class="superpwa-colorpicker" value="'.(isset( $settings['bar_container_bg_color'] ) ? sanitize_hex_color( $settings['bar_container_bg_color']) : '#ff416c').'" data-default-color="#ff416c"></fieldset>';
	}
	function superpwa_call_to_action_bartextcolor_cb(){
		$settings = $this->superpwa_call_to_action_get_settings();
		echo '<fieldset><input id="bar_text_color" name="superpwa_call_to_action_settings[bar_text_color]" type="text" class="superpwa-colorpicker" value="'.(isset( $settings['bar_text_color'] ) ? sanitize_hex_color( $settings['bar_text_color']) : '#ffffff').'" data-default-color="#ffffff"></fieldset>';
	}
	function superpwa_call_to_action_buttonbgcolor_cb(){
		$settings = $this->superpwa_call_to_action_get_settings();
		echo '<fieldset><input id="bar_btn_bg_color" name="superpwa_call_to_action_settings[bar_btn_bg_color]" type="text" class="superpwa-colorpicker" value="'.(isset( $settings['bar_btn_bg_color'] ) ? sanitize_hex_color( $settings['bar_btn_bg_color']) : '#ffffff').'" data-default-color="#D5E0EB"></fieldset>';
	}
	function superpwa_call_to_action_buttontextcolor_cb(){
		$settings = $this->superpwa_call_to_action_get_settings();
		echo '<fieldset><input id="bar_btn_text_color" name="superpwa_call_to_action_settings[bar_btn_text_color]" type="text" class="superpwa-colorpicker" value="'.(isset( $settings['bar_btn_text_color'] ) ? sanitize_hex_color( $settings['bar_btn_text_color']) : '#ff416a').'" data-default-color="#ff416a"></fieldset>';
	}
	function superpwa_call_to_action_buttonfontsize_cb(){
		$settings = $this->superpwa_call_to_action_get_settings();
		echo '<fieldset><input id="bar_btn_font_size" name="superpwa_call_to_action_settings[bar_btn_font_size]" type="number" class="regular-text" value="'.(isset( $settings['bar_btn_font_size'] ) ? sanitize_text_field( $settings['bar_btn_font_size']) : 13).'" placeholder="13"></fieldset>';
	}
	function superpwa_ios_translate_text_cb(){
		$settings = $this->superpwa_call_to_action_get_settings();
		$message = ( isset($settings['ios_message']) && !empty( $settings['ios_message'] ) )? $settings['ios_message'] : '';
	    if(empty($message) ){
	        $message = esc_html__('Install this %sitename% on your iPhone %ICON_INSTALL% and then %bold% Add to Home Screen %/bold%', 'super-progressive-web-apps-pro');
	    }
		echo '<fieldset><input type="text" class="regular-text" name="superpwa_call_to_action_settings[ios_message]" style="width: 100%" value="'.$message.'">
        
        <p class="description">%sitename%, %bold%, %/bold%, %ICON_INSTALL% Add this to better looks (Only text)</p></fieldset>';
	}
	function superpwa_ios_chrome_translate_text_cb(){
		$settings = $this->superpwa_call_to_action_get_settings();
		$message = ( isset($settings['ios_chrome_msg']) && !empty( $settings['ios_chrome_msg'] ) )? $settings['ios_chrome_msg'] : 'Currently PWA is not supported in iOS Chrome So follow below steps:';
	    if(empty($message) ){
	        $message = esc_html__('Currently PWA is not supported in iOS Chrome So follow below steps:', 'super-progressive-web-apps-pro');
	    }
		echo '<fieldset><input type="text" class="regular-text" name="superpwa_call_to_action_settings[ios_chrome_msg]" style="width: 100%" value="'.$message.'"></fieldset>';
	}
	function superpwa_ios_chrome_homescreen_translate_text_cb(){
		$settings = $this->superpwa_call_to_action_get_settings();
		$message = ( isset($settings['ios_chrome_hscrn']) && !empty( $settings['ios_chrome_hscrn'] ) )? $settings['ios_chrome_hscrn'] : 'Add to Home Screen';
	    if(empty($message) ){
	        $message = esc_html__('Add to Home Screen', 'super-progressive-web-apps-pro');
	    }
		echo '<fieldset><input type="text" class="regular-text" name="superpwa_call_to_action_settings[ios_chrome_hscrn]" style="width: 100%" value="'.$message.'"></fieldset>';
	}
	function superpwa_shortcode_text_cb(){
		echo "<style>#spwa-shrtcode{border: 1px solid #cdcdcd;width: 40%;padding: 10px;}</style><input onclick='copycontentData(this, this.value)' id='spwa-shrtcode' value='[superpwa-add-to-home-button]' readonly><em id='shortcode_resp_message'></em>";
		echo '<script>
            function copycontentData(e, text){
                document.execCommand("copy");
                document.getElementById("shortcode_resp_message").innerText = "copied";
                e.addEventListener("copy", function(event) {
                  event.preventDefault();
                  if (event.clipboardData) {
                    event.clipboardData.setData("text/plain", text);
                     document.getElementById("shortcode_resp_message").innerText = "copied";
                  }
                });
                setTimeout(function(){ document.getElementById("shortcode_resp_message").innerText = ""; }, 1000);

            }
            
            </script>';
	}
	function superpwa_shortcode_buttontext_cb(){
		$settings = $this->superpwa_call_to_action_get_settings();
		echo '<fieldset><input name="superpwa_call_to_action_settings[shortcode_btn_text]" id="shortcode_btn_text" class="regular-text " type="text" placeholder="Install APP" value="'.(isset($settings['shortcode_btn_text']) && !empty($settings['shortcode_btn_text']) ? $settings['shortcode_btn_text'] : "Install APP").'"></fieldset>';
	}
	function superpwa_call_to_action_installbuttontext_cb(){
		$settings = $this->superpwa_call_to_action_get_settings();
		echo '<fieldset><input name="superpwa_call_to_action_settings[add_to_home_btn_text]" id="add_to_home_btn_text" class="regular-text " type="text" placeholder="Install" value="'.(isset($settings['add_to_home_btn_text']) && !empty($settings['add_to_home_btn_text']) ? $settings['add_to_home_btn_text'] : "Install").'"></fieldset>';
	}
	/**
	 * Add sub-menu page for call to action
	 * 
	 * @since 1.0
	 */
	function superpwa_call_to_action_sub_menu() {
		
		// call to action sub-menu
		add_submenu_page( 'superpwa', __( 'Super Progressive Web Apps Pro', 'super-progressive-web-apps-pro' ), __( 'Call To Action', 'super-progressive-web-apps-pro' ), 'manage_options', 'superpwa-call-to-action', array($this, 'superpwa_call_to_action_interface_render') );
	}

	/**
	 * Call To ACtion UI renderer
	 *
	 * @since 1.0
	 */ 
	function superpwa_call_to_action_interface_render(){
		// Authentication
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		// Handing save settings
		if ( isset( $_GET['settings-updated'] ) ) {
			// Add settings saved message with the class of "updated"
			add_settings_error( 'superpwa_settings_group', 'superpwa_call_to_action_settings_saved_message', __( 'Settings saved.', 'super-progressive-web-apps-pro' ), 'updated' );

			// Show Settings Saved Message
			settings_errors( 'superpwa_settings_group' );
		}
		// Get add-on info
		$addon_utm_tracking = superpwa_get_addons( 'call_to_action' );
        // Menu Bar Styles
		if(function_exists('superpwa_setting_tabs_styles')){
            superpwa_setting_tabs_styles();
         }

		?>
		<div class="wrap">	
			<h1><?php _e( 'Call To Action', 'super-progressive-web-apps-pro' ); ?>  <small>(<a href="<?php echo esc_url($addon_utm_tracking['link']) . '?utm_source=superpwa-plugin&utm_medium=utm-tracking-settings'?>"><?php echo esc_html__( 'Docs', 'super-progressive-web-apps' ); ?></a>)</small></h1>

			<?php 
			     // Menu Bar Html
                 if(function_exists('superpwa_setting_tabs_html')){
			        superpwa_setting_tabs_html(); 
			     }
			?>

			<form action="options.php" method="post" enctype="multipart/form-data">		
				<?php
				// Output nonce, action, and option_page fields for a settings page.
				settings_fields( 'superpwa_call_to_action_settings_group' );
				
				// Status
				do_settings_sections( 'superpwa_call_to_action_section' );	// Page slug
				
				// Output save settings button
				submit_button( __('Save Settings', 'super-progressive-web-apps-pro') );
				?>
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
	 * Load banner in frontend footer
	 * @param string null
	 */
	public function superpwa_call_to_action_sticky_banner()
	{
		// Get Settings Super-pwa-cta-addon
		$settings = $this->superpwa_call_to_action_get_settings();
		// Get Settings Super-pwa
		$parentsettings = superpwa_get_settings(); 
		$bar_container_style = '';
        if(isset($settings['bar_container_bg_color']) && !empty($settings['bar_container_bg_color'])){
            $bar_container_style = 'style="background-color:'.$settings['bar_container_bg_color'].';"';
        }
        $install_msg = (isset($settings['add_to_home_btn_text']) && !empty($settings['add_to_home_btn_text']) )? $settings['add_to_home_btn_text']: esc_html__('Install', 'super-progressive-web-apps-pro');
        do_action( 'wpml_register_single_string', 'super-progressive-web-apps-pro', 'Install Message', $install_msg );

        $install_msg = apply_filters('wpml_translate_single_string', $install_msg, 'super-progressive-web-apps-pro', 'Install Message' );

		$button = '<span class="superpwa-butn" style="background-color:'.(isset($settings['bar_btn_bg_color']) ? $settings['bar_btn_bg_color']: '#ffffff').';color:'.(isset($settings['bar_btn_text_color']) ? $settings['bar_btn_text_color']: '#ff416a').';font-size:'.(isset($settings['bar_btn_font_size']) ? $settings['bar_btn_font_size'].'px': '13px').'">'.$install_msg.'</span>';
            if(in_array($this->detect_user_agent_check(),array('crios')) ){
                $button = '<a target="_blank" href="'.get_site_url().'?superpwa-non-amp-install=true&non-amp=true'.'" class="superpwa-cta-btn superpwa-add-via-class">'.$button.'</a>'; 
            }
            
            $style = 'background-color:'.(isset($settings['bar_bg_color']) ? $settings['bar_bg_color']: '#1f1f1f').';'. ((isset($settings['add_to_home_pos']) && $settings['add_to_home_pos'] == 'bottom') ? 'bottom:0;' : 'top:0;') .'transition: '.(@$settings['add_to_home_pos']? $settings['add_to_home_pos'] : 'top').' 0.5s linear;';

               $add_to_home_msg = (isset($settings['add_to_home_msg'])? 
                    	str_replace(array('%sitename%'), array(get_bloginfo( 'name' )), $settings['add_to_home_msg'])
                    	: esc_html__('Welcome', 'super-progressive-web-apps-pro'));
            	do_action( 'wpml_register_single_string', 'super-progressive-web-apps-pro', 'Add to home message', $add_to_home_msg );

        		$add_to_home_msg = apply_filters('wpml_translate_single_string', $add_to_home_msg, 'super-progressive-web-apps-pro', 'Add to home message' );

            echo '<div class="superpwa-sticky-banner" id="superpwa-sticky-bar" style="'.$style.'" data-style="'.$style.'" data-position="'. ((isset($settings['add_to_home_pos']) && !empty($settings['add_to_home_pos']) ) ? $settings['add_to_home_pos'] : 'top') .'">'                 
            . '<div class="superpwa-cta-btn superpwa-add-via-class">
                <div class="superpwa-stky-blk" '.$bar_container_style.'>
                    <h2 style="color:'.(isset($settings['bar_text_color']) ? $settings['bar_text_color']: '#ffffff').'">'.$add_to_home_msg .'</h2>
                    '.$button.'
                </div>
                
               </div>
               <a class="superpwa_add_home_close">&times;</a>'
            .'</div>'; 


       $ios_message = '';
        if(isset($settings['ios_message']) && !empty($settings['ios_message'])){
            $ios_message = esc_html__($settings['ios_message'], 'super-progressive-web-apps-pro');
        }
        if(empty($ios_message)){
          $ios_message = esc_html__('Install this ' .$parentsettings['app_short_name'].' on your iPhone %ICON_INSTALL% and then %bold%Add to Home Screen%/bold%', 'super-progressive-web-apps-pro');
        }
        $ios_message = str_replace(
            array("%bold%", '%/bold%', "%ICON_INSTALL%","%sitename%"), 
            array('<span class="ath">', '</span>','<img class="superpwa-a2h-icon" src="'.SUPERPWA_PRO_PATH_SRC.'/assets/img/upload.png"/>', $parentsettings['app_short_name']),
             $ios_message);

        do_action( 'wpml_register_single_string', 'super-progressive-web-apps-pro', 'IOS Message', $ios_message );

        $ios_message = apply_filters('wpml_translate_single_string', $ios_message, 'super-progressive-web-apps-pro', 'IOS Message' );
        echo sprintf(
                '<div id="superpwa-iossafari-a2h-banner"  class="" %s>
                    <img class="superpwa-logo-icon"  src="%s"/>
                    <div class="superpwa-ov-txt"> 
                        <p>
                            %s
                        </p>
                    </div>
                    <a class="superpwa_ios_message_close">&times;</a>
                    <span class="arrow"></span>
                </div>'
                ,
                (isset($_GET['iosbanner']) ? 'style="display:block;"' : 'style="display:none;"'),
                $parentsettings['icon'],
                $ios_message

                );  
	}
	/**
	 * Load CSS and javascript file required for CTA
	 * @return string all css/javascript
	 */
	public function superpwa_cta_front_enque()
	{
		wp_enqueue_style( 'superpwa-cta-button-css', 
trailingslashit(SUPERPWA_PRO_PATH_SRC) . 'assets/css/superpwa-cta.css', false , SUPERPWA_PRO_VERSION );
	    wp_enqueue_script( 'superpwa-cta-button-script', SUPERPWA_PRO_PATH_SRC . 'assets/js/superpwa-frontend-cta.js', array() , SUPERPWA_PRO_VERSION, true );
	    $settings = $this->superpwa_call_to_action_get_settings();
        wp_localize_script('superpwa-cta-button-script', "superpwa_cta", 
        				array(
                            "a2h_sticky_on_desktop_cta"=>isset($settings['a2h_sticky_on_desktop'])? $settings['a2h_sticky_on_desktop']: 0,
                            "a2h_banner_without_scroll_cta"=>isset($settings['a2h_banner_without_scroll_cta'])? $settings['a2h_banner_without_scroll_cta']: 0,
                             "a2h_banner_delay_cta"=>isset($settings['a2h_banner_delay_cta'])? $settings['a2h_banner_delay_cta']: 0,
                             "a2h_banner_delay_sec_cta"=>isset($settings['a2h_banner_delay_sec_cta'])? $settings['a2h_banner_delay_sec_cta']: 5,
                             "a2h_banner_on_scroll"=>isset($settings['a2h_banner_on_scroll'])? $settings['a2h_banner_on_scroll']: 1,
                            "a2h_sticky_hideon_ios"=>isset($settings['a2h_sticky_hideon_ios'])? $settings['a2h_sticky_hideon_ios']: 0,
                            "a2h_sticky_show_only_on_ios"=>isset($settings['a2h_sticky_show_only_on_ios'])? $settings['a2h_sticky_show_only_on_ios']: 0,
                        )
        );
	    
	}

	public function superpwa_install_page_template($wp_query) {
	    if(isset($wp_query->query_vars['superpwa-non-amp-install']) && $wp_query->query_vars['superpwa-non-amp-install']=='true' && isset($wp_query->query_vars['non-amp']) && $wp_query->query_vars['non-amp']=='true'){
	        
	       $template =  SUPERPWA_PRO_PATH_ABS.'addons/superpwa-non-amp-install.php';
	        require_once $template;
	        exit;
	            
	    }
	}

	public function superpwa_add_error_template_query_var(){
     global $wp;
      $wp->add_query_var( 'superpwa-non-amp-install' );
      $wp->add_query_var( 'non-amp' );
    }
 	// Filter to add menu item custom fields
	public function superpwa_filterwp_edit_navmenuwalker(){
	    // Load menu item custom fields plugin
			if ( ! class_exists( 'SuperPWA_Pro_Menu_Item_Custom_Fields_Walker' ) ) {
				require_once SUPERPWA_PRO_PLUGIN_DIR_NAME . '/addons/custom-menu/walker-nav-menu-edit.php';
			}
			$walker = 'SuperPWA_Pro_Menu_Item_Custom_Fields_Walker';

			return $walker;
	}

	public function superpwa_cta_nav_menu_link_atts( $atts, $item, $args, $depth=0 ){

	    // Get Settings Super-pwa-cta-addon
		$settings = $this->superpwa_call_to_action_get_settings();               
	        if(isset($settings['a2h_menu_button_cta']) && ($settings['a2h_menu_button_cta']==1 || $settings['a2h_menu_button_cta']=='on') ){
	            $value = get_post_meta( $item->ID, 'menu-item-superpwapro-installable', true );

	            if($value){
	                 $atts['href'] = get_site_url().'?superpwa-non-amp-install=true&non-amp=true';
	                 $atts['target'] = '_blank';
	                 $item->target = "_blank";
	            }            
	        }        

	    return $atts;
	}

	public function superpwa_cta_special_nav_class( $classes, $item ) {
       
    	$settings = $this->superpwa_call_to_action_get_settings();               
	        if(isset($settings['a2h_menu_button_cta']) && ($settings['a2h_menu_button_cta']==1 || $settings['a2h_menu_button_cta']=='on') ){
	      
	         $value = get_post_meta( $item->ID, 'menu-item-superpwapro-installable', true );
	     
	            if($value){
	                $classes[] = 'superpwa-installable-btn-class';
	                $classes[] = 'superpwa-add-via-class';
	                
	            }
	         
	     }        
       
    	return $classes;
 	}

 	public function superpwa_menu_ctafp_dynamic_css(){
    
	   $settings = $this->superpwa_call_to_action_get_settings();
		     
		    ?>
		    <style>
		        ul li.superpwa-installable-btn-class a{
		            color: <?php echo isset($settings['bar_btn_text_color']) ? $settings['bar_btn_text_color'] : '#ffffff'; ?> !important;    
		            width:100% !important;
		        }
		       .superpwa-installable-btn-class{
		          background-color: <?php echo isset($settings['bar_btn_bg_color']) ? $settings['bar_btn_bg_color'] : '#2b2bff'; ?> ;    
		       } 
		    </style>

		<?php
	}

	/**
	 * Detect the requested user agent and reurn
	 * @return string name of the browser user agent
	 */
	function detect_user_agent_check( ){

            $user_agent_name ='others';           
            if     (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') || strpos($_SERVER['HTTP_USER_AGENT'], 'OPR/')) $user_agent_name = 'opera';
            elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Edge'))    $user_agent_name = 'edge';            
            elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox')) $user_agent_name ='firefox';
            elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') || strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7')) $user_agent_name = 'internet_explorer';
            elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'CriOS'))  $user_agent_name = 'crios';                        
            elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'iPod')) $user_agent_name = 'ipod';
            elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone')) $user_agent_name = 'iphone';
            elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'iPad')) $user_agent_name = 'ipad';
            elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'Android')) $user_agent_name = 'android';
            elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'webOS')) $user_agent_name = 'webos';
            elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome'))  $user_agent_name = 'chrome';
            elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari'))  $user_agent_name = 'safari';
            
            return $user_agent_name;
	}
	/**
	 * Add more options in localize variable
	 * @param $localize of localize data array
	 * @return array of new added options in array
	 */
	function superpwa_cta_localize_update($localize){
		$settings = $this->superpwa_call_to_action_get_settings();
		if(isset($settings['a2h_sticky_on_desktop']) && $settings['a2h_sticky_on_desktop']==1){
			$localize['enableOnDesktop'] = $settings['a2h_sticky_on_desktop'];
		}
		return $localize;
	}

	/**
	 *  @param $attr passed variable via shortcode
	 *  @return string all shortcode html with their style
	 */
	function shortcode_button_add_to_home( $attr = array() ){

		$settings = $this->superpwa_call_to_action_get_settings();
        $button_text = (isset($settings['shortcode_btn_text']) && !empty($settings['shortcode_btn_text']) ? $settings['shortcode_btn_text'] : esc_html__('Install APP', 'super-progressive-web-apps-pro'));
        if ( class_exists('Sitepress') ) {
	        do_action( 'wpml_register_single_string', 'super-progressive-web-apps-pro', 'Shortcode Text', $button_text );
	         

	        $button_text = apply_filters('wpml_translate_single_string', $button_text, 'super-progressive-web-apps-pro', 'Shortcode Text' );
         }

		 $defaultAttributes = array(
                    'title' => $button_text,
                    'align' => 'center',
                        );
		  $attr = wp_parse_args($attr, $defaultAttributes);
		$shortcodeCSS = '.superpwa-shortcode-button-wrap{text-align: '.$attr['align'].';}.superpwa-shortcode-button{width:20%;margin:0 auto; color: #2c2c2c;cursor: pointer;font-size: 2em;padding: 1.5rem;border: 0;transition: all 0.5s;border-radius: 10px;position: relative;min-width: 250px;}.superpwa-shortcode-button:hover{ background: #2b2bff;transition: all 0.5s;
        border-radius: 10px;box-shadow: 0px 6px 15px #0000ff61;padding: 1.5rem 3rem 1.5rem 1.5rem;color: #ffffff;}';
		$shortcodeCSS = apply_filters("superpwa_shortcode_button_css", $shortcodeCSS, $attr); 

		$html = '<div class="superpwa-shortcode-button-wrap"><div class="superpwa-shortcode-button superpwa-add-via-class">'.$attr['title'].'</div></div>';
		$html = apply_filters("superpwa_shortcode_button", $html, $attr);
		$html = "<style>".$shortcodeCSS."</style>".$html;
		return $html;
	}

}
function superpwapro_calltoaction(){
	return SPWAPcalltoaction::get_instance();
}
superpwapro_calltoaction();