<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Fep_Emails {
	private static $instance;

	public static function init() {
		if ( ! self::$instance instanceof self ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	function actions_filters() {
		if ( isset( $_POST['action'] ) && 'fep_update_ajax' == $_POST['action'] ) {
			return;
		}
		if ( true != apply_filters( 'fep_enable_email_send', true ) ) {
			return;
		}
		add_action( 'fep_status_to_publish', array( $this, 'send_email' ), 99, 2 );

		if ( '1' == fep_get_option( 'notify_ann', '1' ) ) {
			add_action( 'fep_status_to_publish', array( $this, 'notify_users' ), 99, 2 );
		}
	}

	function send_email( $mgs, $prev_status ) {
		if ( 'message' != $mgs->mgs_type ) {
			return;
		}
		if ( fep_get_meta( $mgs->mgs_id, '_fep_email_sent', true ) ) {
			return;
		}

		$participants = fep_get_participants( $mgs->mgs_id );
		$participants = apply_filters( 'fep_filter_send_email_participants', $participants, $mgs->mgs_id );
		if ( $participants && is_array( $participants ) ) {
			$participants = array_unique( array_filter( $participants ) );
			$subject  = get_bloginfo( 'name' ) . ': ' . __( 'New Message', 'front-end-pm' );
			$message  = __( 'You have received a new message in', 'front-end-pm' ) . "\r\n";
			$message .= get_bloginfo( 'name' ) . "\r\n";
			$message .= sprintf( __( 'From: %s', 'front-end-pm' ), fep_user_name( $mgs->mgs_author ) ) . "\r\n";
			$message .= sprintf( __( 'Subject: %s', 'front-end-pm' ), $mgs->mgs_title ) . "\r\n";
			$message .= __( 'Please Click the following link to view full Message.', 'front-end-pm' ) . "\r\n";
			$message .= fep_query_url( 'messagebox' ) . "\r\n";
			if ( 'html' == fep_get_option( 'email_content_type', 'plain_text' ) ) {
				$message      = nl2br( $message );
				$content_type = 'text/html';
			} else {
				$content_type = 'text/plain';
			}
			$attachments             = array();
			$headers                 = array();
			$headers['from']         = 'From: ' . stripslashes( fep_get_option( 'from_name', wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) ) ) . ' <' . fep_get_option( 'from_email', get_bloginfo( 'admin_email' ) ) . '>';
			$headers['content_type'] = "Content-Type: $content_type";
			fep_add_email_filters();

			foreach ( $participants as $participant ) {
				if ( $participant == $mgs->mgs_author ) {
					continue;
				}

				if ( ! fep_get_user_option( 'allow_emails', 1, $participant ) ) {
					continue;
				}
				$to = fep_get_userdata( $participant, 'user_email', 'id' );
				if ( ! $to ) {
					continue;
				}
				$content = apply_filters( 'fep_filter_before_email_send', compact( 'subject', 'message', 'headers', 'attachments' ), $mgs, $to );

				if ( empty( $content['subject'] ) || empty( $content['message'] ) ) {
					continue;
				}
				wp_mail( $to, $content['subject'], $content['message'], $content['headers'], $content['attachments'] );
			} //End foreach
			fep_remove_email_filters();
			fep_update_meta( $mgs->mgs_id, '_fep_email_sent', time() );
		}
	}

	// Mass emails when announcement is created
	function notify_users( $mgs, $prev_status ) {
		if ( 'announcement' != $mgs->mgs_type ) {
			return;
		}
		if ( fep_get_meta( $mgs->mgs_id, '_fep_email_sent', true ) ) {
			return;
		}

		$user_ids = fep_get_participants( $mgs->mgs_id );
		if ( ! $user_ids ) {
			return;
		}
		cache_users( $user_ids );

		$to          = fep_get_option( 'ann_to', get_bloginfo( 'admin_email' ) );
		$user_emails = array();
		foreach ( $user_ids as $user_id ) {
			if ( $user_id === $mgs->mgs_author ) {
				continue;
			}
			if ( fep_get_user_option( 'allow_ann', 1, $user_id ) ) { 
				$user_emails[] = fep_get_userdata( $user_id, 'user_email', 'id' );
			}
		}
		$subject  = get_bloginfo( 'name' ) . ': ' . __( 'New Announcement', 'front-end-pm' );



	
		$message = '<table border="0" width="100%" cellspacing="0" cellpadding="0">
		<tbody>
		<tr>
		<td align="center" valign="top">
		<div id="template_header_image">
		<p style="margin-top: 0;"> 
		<img style="border: none; display: inline; font-size: 14px; font-weight: bold; height: auto; line-height: 100%; outline: none; text-decoration: none; text-transform: capitalize;" src="'.get_option( 'woocommerce_email_header_image' ).'" alt="iDealBiz" />«flag»</p>
		
		</div>
		<table id="template_container" style="box-shadow: 0 1px 4px rgba(0,0,0,0.1) !important; background-color: #ffffff; border: 1px solid #dedede; border-radius: 3px !important;" border="0" width="600" cellspacing="0" cellpadding="0">
		<tbody>
		<tr>
		<td align="center" valign="top">
		<h4><!-- Header --></h4>
		<table id="template_header" style="background-color: #14307b; border-radius: 3px 3px 0 0 !important; color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;" border="0" width="600" cellspacing="0" cellpadding="0">
		<tbody>
		<tr>
		<td id="header_wrapper" style="padding: 36px 48px; display: block;">
		<h2 style="color: #ffffff; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: left; text-shadow: 0 1px 0 #42ace7; -webkit-font-smoothing: antialiased;">';
		$message .= __( 'A new Announcement is Published in ', 'front-end-pm' ) . "\r\n";
		$message .= get_bloginfo( 'name' ) . "\r\n";
		$message .='</h2>
		</td>
		</tr>
		</tbody>
		</table>
		<!-- End Header --></td>
		</tr>
		<tr>
		<td align="center" valign="top"><!-- Body -->
		<table id="template_body" border="0" width="600" cellspacing="0" cellpadding="0">
		<tbody>
		<tr>
		<td id="body_content" style="background-color: #ffffff;" valign="top"><!-- Content -->
		<table border="0" width="100%" cellspacing="0" cellpadding="20">
		<tbody>
		<tr>
		<td style="padding: 48px;" valign="top">
		<div id="body_content_inner" style="color: #636363; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; font-size: 14px; line-height: 150%; text-align: left;">';

		$message .= sprintf( __( 'Title: %s', 'front-end-pm' ), $mgs->mgs_title ) . "<br/>";
		$message .= __( 'Please Click the following link to view full Announcement.', 'front-end-pm' ) . "<br/>";
		$message .= fep_query_url( 'announcements' ) . "<br/>";

		$message.=__('Thank You.','idealbiz').'
		
		</div>
		&nbsp;</td>
		</tr>
		</tbody>
		</table>
		<!-- End Content --></td>
		</tr>
		</tbody>
		</table>
		<!-- End Body --></td>
		</tr>
		<tr>
		<td align="center" valign="top"><!-- Footer -->
		<table id="template_footer" border="0" width="600" cellspacing="0" cellpadding="10">
		<tbody>
		<tr>
		<td style="padding: 0; -webkit-border-radius: 6px;" valign="top">
		<table border="0" width="100%" cellspacing="0" cellpadding="10">
		<tbody>
		<tr>
		<td id="credit" style="padding: 0 48px 48px 48px; -webkit-border-radius: 6px; border: 0; color: #71c1ed; font-family: Arial; font-size: 12px; line-height: 125%; text-align: center;" colspan="2" valign="middle"><a href="https://idealbiz.pt/" target="_blank" rel="noopener">iDealBiz - A marketplace for businesses</a></td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		<!-- End Footer --></td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>';

		
		$content_type = 'text/html';
			
		$attachments             = array();
		$headers                 = array();
		$headers['from']         = 'From: ' . stripslashes( fep_get_option( 'from_name', wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) ) ) . ' <' . fep_get_option( 'from_email', get_bloginfo( 'admin_email' ) ) . '>';
		$headers['content_type'] = "Content-Type: $content_type";

		$content = apply_filters( 'fep_filter_before_announcement_email_send', compact( 'subject', 'message', 'headers', 'attachments' ), $mgs, $user_emails );

		if ( empty( $content['subject'] ) || empty( $content['message'] ) ) {
			return false;
		}

		do_action( 'fep_action_before_announcement_email_send', $content, $mgs, $user_emails );

		if ( ! apply_filters( "fep_announcement_email_send_{$mgs->mgs_id}", true ) ) {
			return false;
		}
		$chunked_bcc = array_chunk( $user_emails, 25 );
		fep_add_email_filters( 'announcement' );
		foreach ( $chunked_bcc as $bcc_chunk ) {
			if ( ! $bcc_chunk ) {
				continue;
			}
			$content['headers']['Bcc'] = 'Bcc: ' . implode( ',', $bcc_chunk );

			wp_mail( $to, $content['subject'], $content['message'], $content['headers'], $content['attachments'] );
		}
		fep_remove_email_filters( 'announcement' );
		fep_update_meta( $mgs->mgs_id, '_fep_email_sent', time() );
	}
} //END CLASS

add_action( 'wp_loaded', array( Fep_Emails::init(), 'actions_filters' ) );

