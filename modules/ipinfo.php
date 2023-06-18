<?php

function ip_info($ip)
{
    $ipinfo = json_decode(
        file_get_contents("http://ip-api.com/json/" . $ip),
        true
    );
    return $ipinfo;
}

?>
