<?php
/**
 * APP Shortcut
 *
 * @since 1.4
 *
 * @Class SPWAPAppShortcut()			Add all features of call to action
 *      @function
 */
// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
  * Features of APP shortcut
 */
class SPWAP_AppShortcut
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
    private $_settings = array();
    /**
     * Constructor
     */
    public function __construct()
    {
        if (is_admin()) {
            add_action('admin_menu', array($this, 'app_shortcut_sub_menu'));
            add_action('admin_init', array($this, 'app_shortcut_register_settings'));
            add_action( 'admin_enqueue_scripts', array( $this, 'app_shortcut_admin_enqueue' ) );
        } else {
            add_filter('superpwa_manifest', array($this, 'app_shortcut_add_manifest'), 11, 1);
        }
    }
    /**
     * Gets an instance of our SPWAP_AppShortcut class.
     *
     * @return SPWAP_AppShortcut Object
     */
    public static function get_instance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    /**
     * Get Call To Action settings
     *
     * @since 1.7
     */
    public function superpwa_app_shortcut_get_settings()
    {
        $defaults = array();
        $values = get_option('superpwa_app_shortcut_settings', $defaults);
     
        return $values;
    }

    public function app_shortcut_add_manifest($manifest)
    {
        $settings = $this->superpwa_app_shortcut_get_settings();
        if (isset($manifest['shortcuts'])) { 
            unset($manifest['shortcuts']);
        }
        foreach ( $settings['shortcuts'] as $key=> $listicon ) {
            $listicon['icons']= array(
                                        array(
                                            'src'   => $listicon['icons'],
                                            'sizes' => '192x192',
                                            'type'  => "image/png"
                                        )
                                    );
            $manifest['shortcuts'][] = $listicon;
        }
        return $manifest;
    }

    /**
     * Add sub-menu page for app shortcut
     *
     * @since 1.3
     */
    public function app_shortcut_sub_menu()
    {
        
        // call to action sub-menu
        add_submenu_page(
            'superpwa',
            __('Super Progressive Web Apps Pro', 'super-progressive-web-apps-pro'),
            __('APP Shortcut', 'super-progressive-web-apps-pro'),
            'manage_options',
            'superpwa-app-shortcut',
            array($this, 'app_shortcut_interface_render')
        );
    }

    /**
     * app shortcut UI renderer
     *
     * @since 1.3
     */
    public function app_shortcut_interface_render()
    {
        // Authentication
        if (! current_user_can('manage_options')) {
            return;
        }
        // Handing save settings
        if (isset($_GET['settings-updated'])) {
            // Add settings saved message with the class of "updated"
            add_settings_error('superpwa_settings_group', 'superpwa_app_shortcut_settings_saved_message', __('Settings saved.', 'super-progressive-web-apps-pro'), 'updated');

            // Show Settings Saved Message
            settings_errors('superpwa_settings_group');
        } 
        // Get add-on info
	    $addon_utm_tracking = superpwa_get_addons( 'app_shortcut' );
        // Menu Bar Styles
        if(function_exists('superpwa_setting_tabs_styles')){
            superpwa_setting_tabs_styles();
         }
        ?>
		<div class="wrap">	
			<h1><?php _e('APP Shortcut', 'super-progressive-web-apps-pro'); ?> <small>(<a href="<?php echo esc_url($addon_utm_tracking['link']) . '?utm_source=superpwa-plugin&utm_medium=utm-tracking-settings'?>"><?php echo esc_html__( 'Docs', 'super-progressive-web-apps' ); ?></a>)</small></h1>
			<?php 
                 // Menu Bar Html
                 if(function_exists('superpwa_setting_tabs_html')){
                    superpwa_setting_tabs_html(); 
                 }
            ?>
			<form action="options.php" method="post" enctype="multipart/form-data">		
				<?php
                // Output nonce, action, and option_page fields for a settings page.
                settings_fields('superpwa_app_shortcut_settings_group');
                
        // Status // Page slug
        do_settings_sections('superpwa_app_shortcut_section');
                
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
     * Register Call To Action settings
     *
     * @since 	1.7
     */
    public function app_shortcut_register_settings()
    {
        // Register Setting
        register_setting(
            'superpwa_app_shortcut_settings_group', // Group name
            'superpwa_app_shortcut_settings', //Setting name = html form <input> name on settings form
            'superpwa_app_shortcut_validator_sanitizer' // Input validator and sanitizer
        );
        // App shortcut
        add_settings_section(
            'superpwa_app_shortcut_section', // ID
            __return_false(), // Title
            array($this, 'superpwa_app_shortcut_section_cb'), // Callback Function
            'superpwa_app_shortcut_section' // Page slug
        );
        // Button Text
        add_settings_section(
            'superpwa_app_shortcut_fields', // ID
            __return_false(), // Title
            array($this, 'app_shortcut_fields_cb'), // CB
            'superpwa_app_shortcut_section', // Page slug
            'superpwa_app_shortcut_section' // Settings Section ID
        );
    }
    /**
     * Callbacks function for APP Shortcut section
     *
     * @since 1.4
     */
    
    public function superpwa_app_shortcut_section_cb()
    {
        ?><p><?php
        
        echo esc_html__("This Addon make Easy access to those tasks from anywhere the app icon is displayed will enhance user's productivity as well as increase their engagement with the web app.", 'super-progressive-web-apps-pro'); ?></p><?php
    }

    public function app_shortcut_fields_cb()
    {
        $settingsValue = $this->superpwa_app_shortcut_get_settings();
        if (isset($settingsValue['shortcuts']) && !empty($settingsValue['shortcuts'])) {
            $previousData = $settingsValue['shortcuts'];
        } else {
            $previousData = array(
                                array(
                                    'name'=> '',
                                    'short_name'=>'',
                                    'description'=>'',
                                    'url'=>'',
                                    'icons'=>''
                                )
                            );
        }
        ?>
        <div class="superpwa-wrp">
                <div class="superpwa-repeater-wrap">
                    <div class="superpwa-repeater-sec-label">
                        <div class="field label">Name</div>
                        <div class="field label">Short name</div>
                        <div class="field label">Description</div>
                        <div class="field label">URL</div>
                        <div class="field label">Icons<div class="desc">Select 192x192 images</div></div>
                        <div class="field action"></div>
                    </div>
                    <?php
                        $i=0;
                        foreach ($previousData as $key => $oData) {
                            ?>
                    <div class="superpwa-repeater-sec" data-current="<?php echo $i; ?>">
                        <div class="field">
                            <input type="text" name="superpwa_app_shortcut_settings[shortcuts][<?php echo $i; ?>][name]" value="<?php echo $oData['name']?>">
                        </div>
                        <div class="field">
                            <input type="text" name="superpwa_app_shortcut_settings[shortcuts][<?php echo $i; ?>][short_name]" value="<?php echo $oData['short_name']?>">
                        </div>
                        <div class="field">
                            <input type="text" name="superpwa_app_shortcut_settings[shortcuts][<?php echo $i; ?>][description]" value="<?php echo $oData['description']?>">
                        </div>
                        <div class="field">
                            <input type="text" name="superpwa_app_shortcut_settings[shortcuts][<?php echo $i; ?>][url]" value="<?php echo $oData['url']?>">
                        </div>
                        <div class="field">
                            <input type="text" name="superpwa_app_shortcut_settings[shortcuts][<?php echo $i; ?>][icons]" class="superpwa-icon-field" readonly value="<?php echo $oData['icons']?>">
                            <button type="button" class="button superpwa-set-shortcut-icon" data-editor="content">
                                <span class="dashicons dashicons-format-image" style="margin-top: 4px;"></span> 
                            </button>
                        </div>
                        <div class="field action">
                            <span class="dashicons dashicons-trash superpwa-repeater-trash"></span> 
                            
                        </div>
                    </div>
                    <?php
                        $i++; 
                        } ?>
                </div>
                <div class="superpwa-inc-desc">
                    <button type="button" id="add-new-shortcut-row">+ Add New</button>
                </div>
            </div>
            <script type="template/javascript" id="superpwa-appshortcut-template"><div class="superpwa-repeater-sec"  data-current="%i%"><div class="field"> <input type="text" name="superpwa_app_shortcut_settings[shortcuts][%i%][name]"></div><div class="field"> <input type="text" name="superpwa_app_shortcut_settings[shortcuts][%i%][short_name]"></div><div class="field"> <input type="text" name="superpwa_app_shortcut_settings[shortcuts][%i%][description]"></div><div class="field"> <input type="text" name="superpwa_app_shortcut_settings[shortcuts][%i%][url]"></div><div class="field"> <input type="text" name="superpwa_app_shortcut_settings[shortcuts][%i%][icons]" class="superpwa-icon-field" readonly> <button type="button" class="button superpwa-set-shortcut-icon" data-editor="content"> <span class="dashicons dashicons-format-image" style="margin-top: 4px;"></span> </button></div><div class="field action"> <span class="dashicons dashicons-trash superpwa-repeater-trash"></span></div></div></script>
        <?php
    }
    public function app_shortcut_admin_enqueue($hooks)
    {
        if (!in_array($hooks, array('superpwa_page_superpwa-app-shortcut', 'super-pwa_page_superpwa-app-shortcut')) && strpos($hooks, 'superpwa-app-shortcut') == false) {
            return false;
        }
        wp_enqueue_script('appshortcut-admin-script', SUPERPWA_PRO_PATH_SRC . '/assets/js/admin-app-shortcut.js', array('superpwa-main-js'), SUPERPWA_PRO_VERSION, true);

        wp_enqueue_style('appshortcut-admin-style', SUPERPWA_PRO_PATH_SRC . '/assets/css/admin-app-shortcut.css', array(), SUPERPWA_PRO_VERSION, 'all');


    }

    /**
     * Validate and sanitize user input
     *
     * @since 1.3
     */
    public function superpwa_app_shortcut_validator_sanitizer($settings)
    {
        return $settings;
    }
    
}
function superpwapro_appshortcut()
{
    return SPWAP_AppShortcut::get_instance();
}
superpwapro_appshortcut();
