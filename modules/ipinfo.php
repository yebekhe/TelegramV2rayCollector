<?php

function ip_info($ip)
{
    $ipinfo = json_decode(
        file_get_contents("https://api.country.is/" . $ip),
        true
    );
    return $ipinfo;
}

?>
