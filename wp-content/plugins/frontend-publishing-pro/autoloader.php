<?php

if (!defined('WPINC')) die;

require_once 'vendor/autoload.php';

function wpfepp_autoload_function( $classname )
{
	if( strpos( $classname, 'WPFEPP' ) === false ) {
		return;
	}

	$class = str_replace( '\\', DIRECTORY_SEPARATOR, $classname );
	$class = str_replace( 'WPFEPP', 'inc', $class );

	// create the actual filepath
	$filePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . $class . '.php';

	 // check if the file exists
	 if(file_exists($filePath))
	 {
		// require once on the file
		require_once $filePath;
	 }
}
spl_autoload_register('wpfepp_autoload_function');