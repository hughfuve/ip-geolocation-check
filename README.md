IP GeoLocation Check
====================

WordPress plugin that checks the geolocation of the visitor's IP and provides conditionals for use in themes or plugins.

## Instructions

To check for an IP Address in the US, simply use the condition `is_us()`

You can also check for IP Addresses in other countries by using the conditional `is_country( $country_code )` and pass the country code into it.  

This uses the service http://freegeoip.net/ to check IP address information.