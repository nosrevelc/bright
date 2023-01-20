<?php
/**
 * Customer Reset Password email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-reset-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<div style="background-color: #f7f7f7;">

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>
<p style="white-space: pre-wrap;">
<?php 
echo  pll__( 'Hi ###USERNAME###, This notice confirms that your password was changed on ###SITENAME###. If you did not change your password, please contact the Site Administrator at ###ADMIN_EMAIL### This email has been sent to ###EMAIL### Regards,  All at ###SITENAME###  ###SITEURL###' );
?>
</p>  

<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer', $email );
?>
</div>