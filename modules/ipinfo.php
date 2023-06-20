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
    if (is_ip($ip) === false) {
        $ip_address_array = dns_get_record($ip,  DNS_A);
        $randomKey = array_rand($ip_address_array);
        $ip = $ip_address_array[$randomKey]['ip'];
    }
    // Initialize cURL
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, 'https://api.country.is/' . $ip);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Send the HTTP request and get the response
    $response = curl_exec($ch);

    // Check for errors
    if (curl_error($ch)) {
        echo 'cURL error: ' . curl_error($ch);
    }

    // Close the cURL session
    curl_close($ch);

    // Print the response
    return $response;
}

?>
