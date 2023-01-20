<?php
/**
* Plugin Name: Loader MU Plugins 
* Plugin URI: https://www.md3.pt
* Description: This a is plugin to load all Mu Plugins
* Version: 1.0
* Author: Your md3
* Author URI: https://www.md3.pt
**/


function wpdocs_enqueue_custom_admin_style() {
    wp_register_style( 'custom_wp_admin_css', WPMU_PLUGIN_URL.'/assets/css/admin-style.css', false, '1.0.0' );
    wp_enqueue_style( 'custom_wp_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'wpdocs_enqueue_custom_admin_style' );


$_dirOpen = opendir(dirname(__FILE__));
while (($_item = readdir($_dirOpen))) {
    if ((!is_file($_item)) && (!strpos($_item, '.'))) {
        if ($_item != '.' && $_item != '..') {
            foreach (glob(WPMU_PLUGIN_DIR . '/' . $_item . '/*.php') as $_plugin_file) {
                //echo $_plugin_file;
                include_once($_plugin_file);
            }
        }
    }
}closedir($_dirOpen); 



// ACF  SAVE  
add_filter('acf/settings/save_json', 'my_acf_json_save_point');
function my_acf_json_save_point( $path ) {
    // update path
    $path = WPMU_PLUGIN_DIR . '/advanced-custom-fields-pro/acf-json';
    // return
    return $path; 
}

//var_dump(get_site_url() == 'http://idealbiz.lc/pt');
//if(get_main_site_id() == get_current_blog_id()){
if(86 == get_current_blog_id()){

}else{
    require('advanced-custom-fields-pro/acf-json/acf.php');

    add_action( 'admin_menu', 'prefix_remove_menu_pages' );
    function prefix_remove_menu_pages() {
        remove_menu_page('edit.php?post_type=acf-field-group');
        // Remove any item you want
    }
}

require('idb-options/idb-options.php');