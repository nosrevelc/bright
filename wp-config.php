<?php
define( 'WP_CACHE', true ); // Added by WP Rocket


 
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH 
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
/**ALL TIPE FILE */
define('ALLOW_UNFILTERED_UPLOADS', true);

define( 'DB_NAME', 'idealbizeu_bd');
/** MySQL database username */
define( 'DB_USER', 'idealbizeu_admin');
/** MySQL database password */ 
define( 'DB_PASSWORD', 'unYwMsS8M3eEbEC2');
/** MySQL hostname */



define( 'DB_HOST', 'localhost:3306');




/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );
/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '^+|}gY>T%lDm|#ipT#I%!{VF}|_(znhs7&a4;%{r=&e!03Gyt06(d8WBF[Yi )^y');
define('SECURE_AUTH_KEY',  'v+ ,+g|DV7oQ,D@n><rWrS2hb!D+90g#A)%S}8@M][wu%_pNSq:22=X[<S~Y4`+j');
define('LOGGED_IN_KEY',    ')Ud7rfOx+h~+b>`o xp_Z[UOzvr;dgz5*O^Ecgh[yp`H:H #O-4gr0}xb9I=@k+`');
define('NONCE_KEY',        '56y*[EUxI/x%:i|cxU`%v(.-95=4.fY5/`Ks,shOsx7f4P3?4 =`]pBD;#`>IOa@');
define('AUTH_SALT',        '^5/~2eUXV-V7<xtFud&8W=U&q%Z|$UmbuGG.NRT eT=ICO1Vd+EQ?Pvhq/a+R{Xw');
define('SECURE_AUTH_SALT', '#wls(_1;k++Wb-Y^D? ^9 9W);uM6R[)!Y!Tppnwd+jJZY&N#17k^!Oz)Ydm6:%|');
define('LOGGED_IN_SALT',   'ew#ph>RA|_c)3e]H(5y1O#16G>Mr~~-te.4KSp]^Pc^Qy56Vr1$R>%?QVWPw1}e[');
define('NONCE_SALT',       'UavWI,Dx&&)M-1X@7J#?OX9U>^<EBd-qgwI]&v4CN`z!*$C5V/+X4}Tko+[eQ/R9');
/**#@-*/
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'ib_';
$_SERVER['HTTPS'] = 'on';
define('WP_MEMORY_LIMIT', '1024M');
define( 'WP_MAX_MEMORY_LIMIT', '2048M' );
define( 'MEDIA_TRASH', true );
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */

error_reporting(E_ALL);

if(isset($_GET['debug'])){
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', true );
}else{
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
}


define( 'WPS_DEBUG', true );
/* define('WP_HOME','https://idealbiz.eu');
define('WP_SITEURL','https://idealbiz.eu'); */
define('WP_ALLOW_MULTISITE', true);
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'idealbiz.eu');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
define( 'WP_DEFAULT_THEME', 'idealbiz' );
define( 'MYCRED_DEFAULT_TYPE_KEY', 'ibz-coin' );
define( 'MYCRED_DEFAULT_LABEL', 'iDealBiz Coin' );
define( 'CUSTOM_USER_TABLE', 'ib_users' );
define( 'CUSTOM_USER_META_TABLE', 'ib_usermeta' );
define('DISABLE_WP_CRON', true);
//NPMM - Desabilita Auto Update
define( 'WP_AUTO_UPDATE_CORE', false );



/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}
/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
// define('ENABLE_CACHE', TRUE);
//Alterado Pelo Cleverson
define('ALTERNATE_WP_CRON', true);

//AUTORIZA UPDATE DOS PLUGIN SEM SER VIA FTP
define("FS_METHOD", "direct");
