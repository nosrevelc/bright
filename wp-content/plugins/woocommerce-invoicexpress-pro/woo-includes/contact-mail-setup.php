<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Contact form setup.
 *
 * @since  0.0.1
 * @author WidgiLabs <dev@widgilabs.com>
 */
if ( ! isset( $_POST['submitted'] ) ) {
	return;
}

$error_messages = false;
$email = array();

if ( trim( $_POST['contactSubject'] ) === '' ) {
	$error_messages = true;
} else {
	$email_setup['subject'] = $_POST['contactSubject'];
}

if ( trim( $_POST['commentsText'] ) === '' ) {
	$error = true;
} else {
	$email_setup['comment'] = $_POST['commentsText'];
}

if ( ! $error ) {

	$email_setup['user_name']  = $_POST['user_name'];
	$email_setup['user_email'] = $_POST['user_email'];

	$core_info    = wc_ie_get_core_info();
	$plugins_info = wc_ie_get_plugins_info();
	$theme_info   = wc_ie_get_theme_info();

	$debug = " --- DEBUG INFORMATION ---\n\n";
	foreach ( $core_info as $core => $info ) {
		$debug .= strtoupper( $core ) . "\n\n";
		if ( $info !== '' ) {
			foreach ( $info as $key => $value ) {
				$debug .= $key . ': ' . $value . "\n\n";
			}
		}
	}

	foreach ( $plugins_info as $plugin => $info ) {
		if ( count( $info ) ) {
			$debug .= strtoupper( $plugin ) . "\n\n";
			foreach ( $info as $key => $bit ) {
				$debug .= 'File: ' . $key . "\n\n";
				foreach ( $bit as $key => $value ) {
					if ( $value !== '' ) {
						$debug .= $key . ': ' . $value . "\n\n";
					}
				}
			}
		}
	}

	if ( ! empty( $theme_info ) ) {
		$debug .= "THEMES\n\n";
		foreach ( $theme_info as $theme => $info ) {
			if ( $info !== '' ) {
				$debug .= $theme . ': ' . $info . "\n\n";
			}
		}
	}

	if ( wp_load_alloptions() ) {
		$debug .= "INVOICEXPRESS SETTINGS\n\n";
		foreach ( wp_load_alloptions() as $key => $value ) {
			if ( stristr( $key, 'wc_ie_' ) ) {
				$debug .= $key . ': ' . $value . "\n\n";
			}
		}
	}

	$email_to = 'support@widgilabs.com';
	$subject  = $email_setup['subject'];
	$body     = 'Name: ' . $email_setup['user_name']. "\n\n"  . 'Email: ' . $email_setup['user_email']. "\n\n"  . 'Comments: ' . $email_setup['comment'] . "\n\n" . $debug;
	$headers  = 'From: ' . $email_setup['user_name'] . ' <' . $email_to . '>' . "\r\n" . 'Reply-To: ' . $email_setup['user_email'];

	if ( wp_mail( $email_to, $subject, $body, $headers ) ) {
		wp_redirect( admin_url( 'admin.php?page=woocommerce_invoicexpress&tab=support&status=success' ) );
		return;
	}

	wp_redirect( admin_url( 'admin.php?page=woocommerce_invoicexpress&tab=support&status=failure' ) );
}
