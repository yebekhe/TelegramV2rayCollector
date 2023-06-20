<?php
function ping($ip, $port)
{
    $it = microtime(1);
    $check = fsockopen($ip, $port, $errno, $errstr, 30);
    $ft = microtime(1);
    $militime = round(($ft - $it) * 1e3, 2);
    return $check ? $militime : "unavailble";
}
?>
