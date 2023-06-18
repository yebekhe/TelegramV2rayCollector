<?php

function ip_info($ip)
{
    $ipinfo = json_decode(
        file_get_contents("https://ipapi.co/" . $ip . "/json/"),
        true
    );
    return $ipinfo;
}

?>
