<?php
function ping($ip, $port)
{
    $it = microtime(true);
    $check = @fsockopen($ip, $port, $errno, $errstr, 1);
    $ft = microtime(true);
    $militime = round(($ft - $it) * 1e3, 0.5);
    if ($check) {
        fclose($check);
        return $militime;
    } else {
        return "unavailable";
    }
}
?>
