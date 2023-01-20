<?php
/**
 * Plugin Name: Super Progressive Web Apps PRO
 * Plugin URI: https://superpwa.com/?utm_source=superpwa-plugin&utm_medium=plugin-uri
 * Description: PRO version of Super Progressive Web App
 * Author: SuperPWA
 * Author URI: https://superpwa.com/?utm_source=superpwa-plugin&utm_medium=author-uri
 * Contributors: SuperPWA Team
 * Version: 1.18
 * Text Domain: super-progressive-web-apps-pro
 * Domain Path: /languages
 * License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

/**
 * SuperPWA PRO current version
 *
 * @since 1.0
 */
if ( ! defined( 'SUPERPWA_PRO_VERSION' ) ) {
	define( 'SUPERPWA_PRO_VERSION'	, '1.18' ); 
}

/**
 * Absolute path to the plugin directory. 
 * eg - /var/www/html/wp-content/plugins/super-progressive-web-apps-pro/
 *
 * @since 1.0
 */
if ( ! defined( 'SUPERPWA_PRO_PATH_ABS' ) ) {
	define( 'SUPERPWA_PRO_PATH_ABS'	, plugin_dir_path( __FILE__ ) ); 
}

/**
 * Link to the plugin folder. 
 * eg - https://example.com/wp-content/plugins/super-progressive-web-apps/
 *
 * @since 1.0
 */
if ( ! defined( 'SUPERPWA_PRO_PATH_SRC' ) ) {
	define('SUPERPWA_PRO_PATH_SRC'	, plugin_dir_url( __FILE__ ) ); 
}

/**
 * Full path to the plugin file. 
 * eg - /var/www/html/wp-content/plugins/Super-Progressive-Web-Apps/superpwa.php
 *
 * @since 1.0
 */
if ( ! defined( 'SUPERPWA_PRO_PLUGIN_FILE' ) ) {
	define( 'SUPERPWA_PRO_PLUGIN_FILE', __FILE__ ); 
}

if ( ! defined( 'SUPERPWA_PRO_PLUGIN_DIR_NAME' ) ) {
  define( 'SUPERPWA_PRO_PLUGIN_DIR_NAME', dirname( __FILE__ ) ); 
}

/**
 * this is the URL our updater / license checker pings. 
 * This should be the URL of the site or source
 */
if ( ! defined( 'SUPERPWA_PRO_DATA_STORE_URL' ) ) {
	define( 'SUPERPWA_PRO_DATA_STORE_URL', 'https://superpwa.com/' );
}

/**
 * The name of your product. 
 * This should match the download name in EDD exactly
 */
if ( ! defined( 'SUPERPWA_PRO_DATA_ITEM_NAME' ) ) {
	define( 'SUPERPWA_PRO_DATA_ITEM_NAME', 'Super Progressive Web Apps Pro' );
}

/**
 * The download ID.
 *  This is the ID of product and should match the download ID visible in your Downloads list.
 */
if ( ! defined( 'SUPERPWA_PRO_LICENSE_PAGE' ) ) {
	define( 'SUPERPWA_PRO_LICENSE_PAGE', 'super-progressive-web-apps-pro' );
}

// Load everything
add_action("plugins_loaded", 'superpwa_pro_init');
/**
 * initialize the plugin Enabled features
 * @param  null
 * @return null
 */
function superpwa_pro_init()
{
	require_once( SUPERPWA_PRO_PATH_ABS . 'loader.php' );
}

//Update Code
require_once dirname( __FILE__ ) . '/updater/EDD_SL_Plugin_Updater.php'; 

function superpwa_pro_updater(){
   
        $selectedOption      = get_option('superpwa_pro_upgrade_license',true);

        $license_key         = isset($selectedOption['pro']['license_key'])? $selectedOption['pro']['license_key']:'';        
        $licensestatus       = isset($selectedOption['pro']['license_key_status'])? $selectedOption['pro']['license_key_status']:'';

 
// setup the updater
  $edd_updater = new SUPERPWAPRO_Data_EDD_SL_Plugin_Updater( SUPERPWA_PRO_DATA_STORE_URL, __FILE__, array(
      'version'         => SUPERPWA_PRO_VERSION,      // current version number
      'license'         => $license_key,             // license key (used get_option above to retrieve from DB)
      'license_status'  => $licensestatus,
      'item_name'       => SUPERPWA_PRO_DATA_ITEM_NAME,      // name of this plugin
      'author'          => 'SuperPWA Team',           // author of this plugin
      'beta'            => false,
    )
  );      
}

add_action( 'admin_init', 'superpwa_pro_updater', 0);

// Notice to enter license key once activate the plugin
$path = plugin_basename( __FILE__ );
add_action("after_plugin_row_{$path}", function( $plugin_file, $plugin_data, $status ) {
   // global $redux_builder_amp;
    
        if(! defined('SUPERPWA_PRO_ITEM_FOLDER_NAME')){
        	$folderName = basename(__DIR__);
            define( 'SUPERPWA_PRO_ITEM_FOLDER_NAME', $folderName );
        }
        
        $selectedOption      = get_option('superpwa_pro_upgrade_license',true);                        
        $licensestatus       = isset($selectedOption['pro']['license_key_status'])? $selectedOption['pro']['license_key_status']:'';
        $license_key         = isset($selectedOption['pro']['license_key'])? $selectedOption['pro']['license_key']:'';
        if(empty($license_key)){
             
            echo "<tr class='active'><td>&nbsp;</td><td colspan='2'><a href='".esc_url(  self_admin_url( 'admin.php?page=superpwa-upgrade' )  )."'>Please enter the license key</a> to get the <strong>latest features</strong> and <strong>stable updates</strong></td></tr>";
            
         }elseif($licensestatus=="active"){
                          
            $update_cache = get_site_transient( 'update_plugins' );
            $update_cache = is_object( $update_cache ) ? $update_cache : new stdClass();
            if(isset($update_cache->response[ SUPERPWA_PRO_ITEM_FOLDER_NAME."/".SUPERPWA_PRO_ITEM_FOLDER_NAME.".php" ]) 
                && empty($update_cache->response[ SUPERPWA_PRO_ITEM_FOLDER_NAME."/".SUPERPWA_PRO_ITEM_FOLDER_NAME.".php" ]->download_link) 
             )
                    
            {
               unset($update_cache->response[ SUPERPWA_PRO_ITEM_FOLDER_NAME."/".SUPERPWA_PRO_ITEM_FOLDER_NAME.".php" ]);
               set_site_transient( 'update_plugins', $update_cache );
            }
            
            
            
        }
    }, 10, 3 );

//Plugin Updater code ends here