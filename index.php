<?php

error_reporting(0);
header("Content-type: application/json;");

include "modules/getv2ray.php";
include "modules/config.php";

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

    }
}
$fixed_string_vless = str_replace("&amp;", "&", $vless_data);
$fixed_string_trojan = str_replace("&amp;", "&", $trojan_data);
$mix_data = $vmess_data . "\n" . $fixed_string_vless . "\n" . $fixed_string_trojan . "\n" . $shadowsocks_data ;

file_put_contents("mix" , $mix_data);
file_put_contents("vmess", $vmess_data);
file_put_contents("vless", $fixed_string_vless);
file_put_contents("trojan", $fixed_string_trojan);
file_put_contents("ShadowSocks", $shadowsocks_data);

?>
