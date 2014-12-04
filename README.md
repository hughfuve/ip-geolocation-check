IP GeoLocation Check for PHP
====================

General Class that checks the geolocation of the visitor's IP.

## Instructions

This uses the service http://freegeoip.net/ to check IP address information.

Derived from dustyf's WP plugin, modified by Hughfuve for general PHP use.
Licence: My works are MIT licence 

## REQUIREMENTS:
    Requires session_start(); to be called.. because uses sessions, there is a test in constructor.
    Uses basic curl calls, (requires curl to be enabled in your PHP install

    REF https://github.com/Faison/ip-geolocation-check/blob/master/trico-geolocate-ip.php
## USAGE:
    $geoObj = new geo($ip (optional)); if $ip='' then collect for client IP;
  
## METHODS:
    whatIsClientIP()         ;returns your client IP
    isUS()                   ;returns true/false for US
    isCountry($countryCode)  ;returns true/false for country code
    getRemoteData($url)      ;makes a curl call to freegeoip.net/JSON and returns the json data struct
    getGeoData($ip='')       ;decodes JSON fills $geoData with geo data for IP
 
 ## FILLS geoData WITH:
    this->geoData= stdClass Object
        [ip] => 50.53.xx.xxx
        [country_code]  => US
        [country_name]  => United States
        [region_code]   => OR
        [region_name]   => Oregon
        [city]          => Beaverton
        [zip_code]      => 97007
        [time_zone]     => America/Los_Angeles
        [latitude]      => 45.446
        [longitude]     => -122.882
        [metro_code]    => 820
    
 
## DERIVED FROM:  
Plugin Name: IP Geolocation Check
Plugin URI: http://dustyf.com
Description: Finds the user's IP address and checks the geolocation of it.
Author: Dustin Filippini
Version: 0.1
Author URI: http://dustyf.com
