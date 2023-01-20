<?php
/**
 * QR Code Generator
 *
 * @since 1.16
 *
 * @Class SPWAP_QrCodeGenerator()			Add all features of QR Code Generator
 *      @function
 */
// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
  * Features of Data Analytics
 */
class SPWAP_QrCodeGenerator
{
	 /**
     * Get the unique instance of the class
     *
     * @var SPWAP_QrCodeGenerator
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
            add_action('admin_menu', array($this, 'qr_code_generator_sub_menu'));
            add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );
        }
    }
    /**
     * Gets an instance of our SPWAP_QrCodeGenerator class.
     *
     * @return SPWAP_QrCodeGenerator Object
     */
    public static function get_instance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

        /**
     * Add sub-menu page for Qr Code Generator
     *
     * @since 1.5
     */
    public function qr_code_generator_sub_menu()
    {
        
        // Qr Code Generator sub-menu
        add_submenu_page(
            'superpwa',
            __('Super Progressive Web Apps Pro', 'super-progressive-web-apps-pro'),
            __('QR Code Generator', 'super-progressive-web-apps-pro'),
            'manage_options',
            'superpwa-qr-code-generator',
            array($this, 'superpwa_qr_code_generator_interface_render')
        );
    }



	function load_scripts($hooks){

		if (!in_array($hooks, array('superpwa_page_superpwa-qr-code-generator', 'super-pwa_page_superpwa-qr-code-generator')) && strpos($hooks, 'superpwa-qr-code-generator') == false) {
          return false;
        }
		wp_enqueue_script( 'qr-code-library-js', SUPERPWA_PRO_PATH_SRC . 'assets/js/admin-qrcode.min.js',array(), SUPERPWA_PRO_VERSION,true );

		wp_enqueue_script( 'qrcode-generator-js', SUPERPWA_PRO_PATH_SRC . 'assets/js/admin-qrcode-generator.js',array(), SUPERPWA_PRO_VERSION,true );

		wp_localize_script('qrcode-generator-js', "qrcodedata", 
    				array(
                        "home_url" => get_site_url(),
                       )
           		 );

	}

/**
 * QR Code Generator UI renderer
 *
 * @since 2.1.18
 */ 
function superpwa_qr_code_generator_interface_render() {
	
	// Authentication
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	
	// Get add-on info
	$addon_utm_tracking = superpwa_get_addons( 'qr_code_generator' );

	superpwa_setting_tabs_styles();
	?>
	<style type="text/css">
		.qr-code-container{margin: 30px 20px 20px 10px;}
		.qrcode-option-title{float:left;width: 18%}
		.qrcode-result{float: left;}

	</style>
	<div class="wrap">
		<h1><?php _e( 'QR Code Generator', 'super-progressive-web-apps' ); ?> <small>(<a href="<?php echo esc_url($addon_utm_tracking['link']) . '?utm_source=superpwa-plugin&utm_medium=utm-tracking-settings'?>"><?php echo esc_html__( 'Docs', 'super-progressive-web-apps-pro' ); ?></a>)</small></h1>

		  <?php superpwa_setting_tabs_html(); ?>
          <span>This add-on provides you with QR Code which you can download & share with your users to provide them with engaging user experience </span>
          <div class="qr-code-container" style="margin: 30px 20px 20px 10px;">
          	<div class="qrcode-option-title"> 
                <h3>QR Code</h3>
          	</div>
          	<div class="qrcode-result"> 

			    <div id='qrcode'></div>
	      		<a href="" id="download-qr" class="button" style="margin-top:20px; " onclick="downloadQRCode(event)" download>Download QR Code</a>
      		</div>
      		<div class="clear"></div>
          </div>
	</div>
    <?php superpwa_newsletter_form(); ?>
	<?php
}


}

function superpwapro_qrcodegenerator(){
	return SPWAP_QrCodeGenerator::get_instance();
}
superpwapro_qrcodegenerator();