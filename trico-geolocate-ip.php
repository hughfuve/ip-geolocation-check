<?php
/*
Plugin Name: IP Geolocation Check
Plugin URI: http://dustyf.com
Description: Finds the user's IP address and checks the geolocation of it.
Author: Dustin Filippini
Version: 0.1
Author URI: http://dustyf.com
*/

/**
 * Find the IP of the visitor
 */
function what_is_client_ip() {
    $ipaddress = '';
    if ( $_SERVER['HTTP_CLIENT_IP'] ) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } else if ( $_SERVER['HTTP_X_FORWARDED_FOR'] ) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if ( $_SERVER['HTTP_X_FORWARDED'] ) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    } else if ( $_SERVER['HTTP_FORWARDED_FOR'] ) {
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if ( $_SERVER['HTTP_FORWARDED'] ) {
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    } else if ( $_SERVER['REMOTE_ADDR'] ) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    } else {
        $ipaddress = 'UNKNOWN';
    }
 
    return $ipaddress;
}

/**
 * A convenience function that is a wrapper for is_country with 'US' passed in as the country code.
 */
function is_us(){
	
	return is_country( 'US' );
	
}

/**
 * A conditionaly that allows you to pass a country code
 * to check if the IP address is from there.
 */
function is_country( $country_code ){
	if ( ! isset( $_SESSION['country'] ) ) {
		$ipaddress = what_is_client_ip();
		$url = 'http://freegeoip.net/json/' . $ipaddress;
		$response = wp_remote_get( $url );
		$body = json_decode( $response['body'] );
		$country = $body->country_code;
		$_SESSION['country'] = $country;
	} 

	if ( $country_code !== $_SESSION['country'] ) {
		return false;
	} else {
		return true;
	}
}
