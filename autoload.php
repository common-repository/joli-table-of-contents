<?php

defined( 'WPINC' ) || die;

require_once( ABSPATH.WPINC.'/class-phpass.php' );

spl_autoload_register( function( $className ) {
	$namespaces = [
		'WPJoli\\JoliTOC\\' => __DIR__.'/core/',
		'WPJoli\\JoliTOC\\v1\\' => __DIR__.'/v1/core/',
		'Cocur\\Slugify\\' => __DIR__.'/vendor/slugify/',
		'Cocur\\Slugify\\v1' => __DIR__.'/v1/vendor/slugify/',
		// 'WPJoliVendor\\JoliToc\\' => __DIR__.'/vendor/',
	];
	foreach( $namespaces as $prefix => $baseDir ) {
		$len = strlen( $prefix );
		if( strncmp( $prefix, $className, $len ) !== 0 )continue;
		$file = $baseDir.str_replace( '\\', '/', substr( $className, $len )).'.php';
		if( !file_exists( $file ))continue;
		require $file;
		break;
	}
});
