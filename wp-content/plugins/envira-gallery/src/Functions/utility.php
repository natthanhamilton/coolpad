<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

use Envira\Frontend\Background;
use Envira\Utils\Mobile_Detect;

/**
 * Size Conversions
 *
 * @author Chris Christoff
 * @since 1.7.0
 *
 * @param  unknown	  $v
 * @return int|string
 */
function envira_let_to_num( $v ) {
	$l	  = substr( $v, -1 );
	$ret = substr( $v, 0, -1 );

	switch ( strtoupper( $l ) ) {
		case 'P': // fall-through
		case 'T': // fall-through
		case 'G': // fall-through
		case 'M': // fall-through
		case 'K': // fall-through
			$ret *= 1024;
			break;
		default:
			break;
	}

	return $ret;
}

/**
 * Helper function to detect mobile.
 *
 * @since 1.7.0
 *
 * @access public
 * @return void
 */
function envira_mobile_detect(){

	return new Envira\Utils\Mobile_Detect;

}

/**
 * Utility function for debugging
 *
 * @since 1.7.0
 *
 * @access public
 * @param array $array (default: array())
 * @return void
 */
function envira_pretty_print( $array = array() ){

	echo '<pre>';

	print_r( $array );

	echo '</pre>';

}

/**
 * envira_background_request function.
 * 
 * @access public
 * @param mixed $data
 * @param mixed $type
 * @return void
 */
function envira_background_request( $data, $type ){
	
	if ( !is_array( $data ) ){
		return false;
	}
	
	if ( !isset( $type ) ){
		return false;
	}
	
	$background = new Envira\Frontend\Background;
	
	$background->background_request( $data, $type );
	
}