<?php
/*
if ( !defined( 'SUNRISE_LOADED' ) )
	define( 'SUNRISE_LOADED', 1 );

if ( defined( 'COOKIE_DOMAIN' ) ) {
	die( 'The constant "COOKIE_DOMAIN" is defined (probably in wp-config.php). Please remove or comment out that define() line.' );
}

$wpdb->dmtable = $wpdb->base_prefix . 'domain_mapping';
$dm_domain = $_SERVER[ 'HTTP_HOST' ];

if( ( $nowww = preg_replace( '|^www\.|', '', $dm_domain ) ) != $dm_domain )
	$where = $wpdb->prepare( 'domain IN (%s,%s)', $dm_domain, $nowww );
else
	$where = $wpdb->prepare( 'domain = %s', $dm_domain );

$wpdb->suppress_errors();
$domain_mapping_id = $wpdb->get_var( "SELECT blog_id FROM {$wpdb->dmtable} WHERE {$where} ORDER BY CHAR_LENGTH(domain) DESC LIMIT 1" );
$wpdb->suppress_errors( false );
if( $domain_mapping_id ) {
	$current_blog = $wpdb->get_row("SELECT * FROM {$wpdb->blogs} WHERE blog_id = '$domain_mapping_id' LIMIT 1");
	$current_blog->domain = $dm_domain;
	$current_blog->path = '/';
	$blog_id = $domain_mapping_id;
	$site_id = $current_blog->site_id;

	define( 'COOKIE_DOMAIN', $dm_domain );

	$current_site = $wpdb->get_row( "SELECT * from {$wpdb->site} WHERE id = '{$current_blog->site_id}' LIMIT 0,1" );
	$current_site->blog_id = $wpdb->get_var( "SELECT blog_id FROM {$wpdb->blogs} WHERE domain='{$current_site->domain}' AND path='{$current_site->path}'" );
	if ( function_exists( 'get_site_option' ) )
		$current_site->site_name = get_site_option( 'site_name' );
	elseif ( function_exists( 'get_current_site_name' ) )
		$current_site = get_current_site_name( $current_site );

	define( 'DOMAIN_MAPPING', 1 );
}



// Filters the domain that is displayed/output into HTML

add_filter( 'pre_option_home', 'dev_pre_url_filter', 1 );

add_filter( 'pre_option_siteurl', 'dev_pre_url_filter', 1 );

add_filter( 'the_content', 'dev_content_filter', 100 );

add_filter( 'content_url', 'dev_content_url_filter', 100, 2 );

add_filter( 'post_thumbnail_html', 'dev_content_filter', 100 );

add_filter( 'wp_get_attachment_link', 'dev_content_filter', 100 );

add_filter( 'wp_get_attachment_url', 'dev_content_filter', 100 );

add_filter( 'upload_dir', 'dev_upload_dir_filter', 10 );


function dev_pre_url_filter() {

global $wpdb, $path, $switched;

$url;

$switched_path;

$blog_id = get_current_blog_id();


if ( ! $switched ) {

$url = is_ssl() ? 'https://' : 'http://';

$url .= WP_DEVELOPMENT_DOMAIN;

if ( ! is_main_site() ) {

$url .= rtrim( $path, '/' );

}



return $url;

} else {

$switched_path = $wpdb->get_var( "SELECT path FROM {$wpdb->blogs} WHERE blog_id = {$blog_id} ORDER BY CHAR_LENGTH(path) DESC LIMIT 1" );

$url = is_ssl() ? 'https://' : 'http://';

$url .= WP_DEVELOPMENT_DOMAIN;

$url .= rtrim( $switched_path, '/' );


return $url;

}

}


function dev_content_filter( $post_content ) {

global $wpdb;


$blog_details = get_blog_details();

$original_url = $wpdb->get_var( "SELECT domain FROM {$wpdb->dmtable} WHERE blog_id = {$blog_details->blog_id} ORDER BY CHAR_LENGTH(domain) DESC LIMIT 1" );

$dev_url = WP_DEVELOPMENT_DOMAIN . $blog_details->path;


if ( $original_url !== null ) {

$post_content = str_replace( $original_url . '/', $original_url, $post_content );

$post_content = str_replace( $original_url, $dev_url, $post_content );

}

// Change all url's to point to staging (images, anchors, anything within the post content)

$post_content = str_replace( WP_PRODUCTION_DOMAIN, WP_DEVELOPMENT_DOMAIN, $post_content );

// Change urls for "uploads" to point to production so images are visible

$post_content = str_replace( WP_DEVELOPMENT_DOMAIN . $blog_details->path . 'wp-content/uploads', WP_PRODUCTION_DOMAIN . $blog_details->path . 'wp-content/uploads', $post_content );


return $post_content;

}




function dev_content_url_filter( $url, $path ) {

if ( ! empty( $path ) && strpos( $path, 'uploads' ) !== false ) {

return str_replace( WP_DEVELOPMENT_DOMAIN, WP_PRODUCTION_DOMAIN, $url );

}


return $url;

}


function dev_upload_dir_filter( $param ) {

$param['url'] = str_replace( WP_DEVELOPMENT_DOMAIN, WP_PRODUCTION_DOMAIN, $param['url'] );

$param['baseurl'] = str_replace( WP_DEVELOPMENT_DOMAIN, WP_PRODUCTION_DOMAIN, $param['baseurl'] );


return $param;

}



function dev_get_site_by_path( $_site, $_domain, $_path, $_segments, $_paths ) {

global $wpdb, $path;


// So that there is a possible match in the database, set $_domain to be WP_PRODUCTION_DOMAIN

$_domain = WP_PRODUCTION_DOMAIN;


// Search for a site matching the domain and first path segment

$site = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->blogs WHERE domain = %s and path = %s", $_domain, $_paths[0] ) );

$current_path = $_paths[0];


if ( $site === null ) {

// Specifically for the main blog – if a site is not found then load the main blog

$site = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->blogs WHERE domain = %s and path = %s", $_domain, '/' ) );

$current_path = '/';

}


// Set path to match the first segment

$path = $current_path;


return $site;

}


add_filter( 'pre_get_site_by_path', 'dev_get_site_by_path', 1, 5 );
*/