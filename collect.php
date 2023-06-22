<?php
header("Content-type: application/json;");

include "modules/get_data.php";
include "modules/config.php";
include "modules/clash.php";

function get_reality($input)
{
    $array = explode("\n", $input);
    $output = "";
    foreach ($array as $item) {
        if (stripos($item, "reality")) {
            $output .= $output === "" ? $item : "\n$item";
        }
    }
    return $output;
}

function compare_time($a, $b) {
    $a_time = strtotime($a->time);
    $b_time =strtotime($b->time);
    if ($a_time == $b_time) {
        return 0;
    }
    return ($a_time > $b_time) ? -1 : 1;
}

$mix_data = [];
$vmess_data = [];
$trojan_data = [];
$vless_data = [];
$shadowsocks_data = [];


for ($p = count($Channel) - 1; $p >= 0; $p--) {
    $CH = $Channel[$p];
    for (
        $type_count = count($Types[$CH]) - 1;
        $type_count >= 0;
        $type_count--
    ) {
        if ($Types[$CH][$type_count] === "vmess") {
            $vmess_data = array_merge(
                $vmess_data,
                get_config($CH, $Types[$CH][$type_count])
            );
        } elseif ($Types[$CH][$type_count] === "vless") {
            $vless_data = array_merge(
                $vless_data,
                get_config($CH, $Types[$CH][$type_count])
            );
        } elseif ($Types[$CH][$type_count] === "trojan") {
            $trojan_data = array_merge(
                $trojan_data,
                get_config($CH, $Types[$CH][$type_count])
            );
        } elseif ($Types[$CH][$type_count] === "ss") {
            $shadowsocks_data = array_merge(
                $shadowsocks_data,
                get_config($CH, $Types[$CH][$type_count])
            );
        }
    }
}

$vmess_array = [];
$vless_array = [];
$trojan_array = [];
$shadowsocks_array = [];

foreach ($vmess_data as $object) {
    $vmess_array[] = $object['config'];
}
foreach ($vless_data as $object) {
    $vless_array[] = $object['config'];
}
foreach ($trojan_data as $object) {
    $trojan_array[] = $object['config'];
}
foreach ($shadowsocks_data as $object) {
    $shadowsocks_array[] = $object['config'];
}

$vmess = implode("\n", $vmess_array);
$vless = implode("\n", $vless_array);
$trojan = implode("\n", $trojan_array);
$shadowsocks = implode("\n", $shadowsocks_array);

$fixed_string_vmess = remove_duplicate_vmess($vmess);
$string_vless = str_replace("&amp;", "&", $vless);
$fixed_string_vless = remove_duplicate_xray($string_vless, "vless");
$string_trojan = str_replace("&amp;", "&", $trojan);
$fixed_string_trojan = remove_duplicate_xray($string_trojan, "trojan");
$fixed_string_shadowsocks = remove_duplicate_ss($shadowsocks);
$fixed_string_reality = get_reality($fixed_string_vless);

$mix =
    $fixed_string_vmess .
    "\n" .
    $fixed_string_vless .
    "\n" .
    $fixed_string_trojan .
    "\n" .
    $fixed_string_shadowsocks;

$mix_data = array_merge(
    $vmess_data,
    $vless_data,
    $trojan_data,
    $shadowsocks_data
);

file_put_contents("sub/mix", $mix);
file_put_contents("sub/vmess", $fixed_string_vmess);
file_put_contents("sub/vless", $fixed_string_vless);
file_put_contents("sub/reality", $fixed_string_reality);
file_put_contents("sub/trojan", $fixed_string_trojan);
file_put_contents("sub/shadowsocks", $fixed_string_shadowsocks);

file_put_contents("sub/mix_base64", base64_encode($mix));
file_put_contents("sub/vmess_base64", base64_encode($fixed_string_vmess));
file_put_contents("sub/vless_base64", base64_encode($fixed_string_vless));
file_put_contents("sub/reality_base64", base64_encode($fixed_string_reality));
file_put_contents("sub/trojan_base64", base64_encode($fixed_string_trojan));
file_put_contents("sub/shadowsocks_base64", base64_encode($fixed_string_shadowsocks));

$mix_data_json = json_encode($mix_data, JSON_PRETTY_PRINT);
$mix_data_decode = json_decode($mix_data_json);
usort($mix_data_decode, 'compare_time');
$mix_data_json = json_encode($mix_data_decode, JSON_PRETTY_PRINT);
file_put_contents("json/configs.json", $mix_data_json);

file_put_contents("clash/mix.yml", convert_to_clash("https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/mix_base64"));
file_put_contents("clash/vmess.yml", convert_to_clash("https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vmess_base64"));
file_put_contents("clash/trojan.yml", convert_to_clash("https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/trojan_base64"));
file_put_contents("clash/shadowsocks.yml", convert_to_clash("https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/shadowsocks_base64"));
