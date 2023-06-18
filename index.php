<?php

error_reporting(0);
header("Content-type: application/json;");

include "functions.php";
include "config.php";

$mix_data = "";
$vmess_data = "";
$trojan_data = "";
$vless_data = "";
$shadowsocks_data = "";

for ($p = count($Channel) - 1; $p >= 0; $p--) {
    $CH = $Channel[$p];
    for (
        $type_count = count($Types[$CH]) - 1;
        $type_count >= 0;
        $type_count--
    ) {
        if ($Types[$CH][$type_count] === "vmess"){
            if ($vmess_data === ""){
                $vmess_data = get_v2ray($CH, $Types[$CH][$type_count] , "text");
            }
            else{
                $vmess_data .= "\n" . get_v2ray($CH, $Types[$CH][$type_count] , "text");
            }
        }
        elseif ($Types[$CH][$type_count] === "vless"){
            if ($vless_data === ""){
                $vless_data = get_v2ray($CH, $Types[$CH][$type_count] , "text");
            }
            else{
                $vless_data .= "\n" . get_v2ray($CH, $Types[$CH][$type_count] , "text");
            }
        }
        elseif ($Types[$CH][$type_count] === "trojan"){
            if ($trojan_data === ""){
                $trojan_data = get_v2ray($CH, $Types[$CH][$type_count] , "text");
            }
            else{
                $trojan_data .= "\n" . get_v2ray($CH, $Types[$CH][$type_count] , "text");
            }
        }
        elseif ($Types[$CH][$type_count] === "ss"){
            if ($shadowsocks_data === ""){
                $shadowsocks_data = get_v2ray($CH, $Types[$CH][$type_count] , "text");
            }
            else{
                $shadowsocks_data .= "\n" . get_v2ray($CH, $Types[$CH][$type_count] , "text");
            }
        }


        if ($mix_data === ""){
            $data = get_v2ray($CH, $Types[$CH][$type_count] , "text");
        }
        else{
            $data .= "\n" . get_v2ray($CH, $Types[$CH][$type_count] , "text");
        }
    }
}

file_put_contents("mix" , $mix_data);
file_put_contents("vmess", $vmess_data);
file_put_contents("vless", $vless_data);
file_put_contents("trojan", $trojan_data);
file_put_contents("ShadowSocks", $shadowsocks_data);

?>
