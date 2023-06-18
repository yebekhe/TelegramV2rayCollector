<?php

function is_ip($string) {
  $ip_pattern = '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/';
  if (preg_match($ip_pattern, $string)) {
    return true;
  } else {
    return false;
  }
}

function ip_info($ip)
{
    if (is_ip($ip) === true){
        $ipinfo = json_decode(
            file_get_contents("https://api.country.is/" . $ip),
            true
        );
        return $ipinfo;
    }
    else{
        $ip_address_array = dns_get_record($ip ,  DNS_A);
        $ip_address_count = count($ip_address_array);
        $random_key = rand(0, $ip_address_count - 1);
        $random_ip_of_url = $ip_address_array[$random_key]['ip'];
        $ipinfo = json_decode(
            file_get_contents("https://api.country.is/" . $random_ip_of_url),
            true
        );
        return $ipinfo;
    }
}

?>
