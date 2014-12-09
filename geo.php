<?PHP
/*
Derived from dustyf's WP plugin, modified by Hughfuve for general PHP use.
Licence: My works are MIT licence 

REQUIREMENTS:
    Requires session_start(); to be called.. because uses sessions.
    Uses basic curl calls, (requires curl to be enabled in your PHP install

    REF https://github.com/Faison/ip-geolocation-check/blob/master/trico-geolocate-ip.php
USAGE:
    $geoObj = new geo($ip (optional)); if $ip='' then collect for client IP;
  
METHODS:
    whatIsClientIP()         ;returns your client IP
    isUS()                   ;returns true/false for US
    isCountry($countryCode)  ;returns true/false for country code
    getRemoteData($url)      ;makes a curl call to freegeoip.net/JSON and returns the json data struct
    getGeoData($ip='')       ;decodes JSON fills $geoData with geo data for IP
 
 $this->geoData=[stdClass Object
(
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
)
 
  
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


class geo
{
    public $ipAddress = 0;
    public $geoData;

    function __construct($ip='')
    {
        if (!isset($_SESSION)){
            session_start();
        }        
        $this->geoData = $this->getGeoData($ip);
    }

    function whatIsClientIP()
    {
        $this->ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $this->ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $this->ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $this->ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $this->ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $this->ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $this->ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $this->ipaddress = 'UNKNOWN';
        }

        return $this->ipaddress;
    }

    /**
     * A convenience function that is a wrapper for is_country with 'US' passed in as the country code.
     */

    function isUS()
    {

        return $this->isCountry('US');

    }

    function getGeoData($ip='')    {
        if($ip==''){
            if (!isset($_SESSION['geoData'])) {           //trying to cache the data and save calls to freegeoip because we only get 10k calls per hour.
                                                          // but using session space to hold your data is looking for trouble with resource overload
                $this->ipaddress = $this->whatIsClientIP();
                $response   = $this->getRemoteData("http://freegeoip.net/json/" . $this->ipaddress);

                $this->geoData = json_decode($response);
                $_SESSION['geoData'] = $this->geoData;
            } else {
                $this->geoData = $_SESSION['geoData'];
            }         
        }else{
                $response   = $this->getRemoteData("http://freegeoip.net/json/" . $ip);
                $this->geoData = json_decode($response);            
        }
        return $this->geoData;
    }

    /**
     * A conditional that allows you to pass a country code
     * to check if the IP address is from there.
     */
    function isCountry($countryCode)
    {        
        $this->geoData = $this->getGeoData();
        
        if ($countryCode !== $this->geoData->country_code) {
            return false;
        } else {
            return true;
        }
    }


    function getRemoteData($url){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($httpcode>=200 && $httpcode<300) ? $data : false;
    }

}

?>