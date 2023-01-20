<?php
namespace WidgiLabs\WP\Plugin\iDealBiz\GFFieldAddon;

class Upload {

	/**
	 * Upload a file.
	 *
	 * @since  1.0.0
	 * @return int|\WP_Error The attachment ID or the error.
	 */
	public static function media() {

		require_once( ABSPATH . 'wp-admin/includes/image.php' );
	    require_once( ABSPATH . 'wp-admin/includes/file.php' );
	    require_once( ABSPATH . 'wp-admin/includes/media.php' );

		return \media_handle_upload( 'file', 0 );
	}
}
