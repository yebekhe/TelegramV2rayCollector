<?php

header("Content-type: application/json;");

include "modules/getv2ray.php";
include "modules/config.php";

function remove_duplicate_vmess($input)
{
    $array = explode("\n", $input);
    foreach ($array as $item) {
        $parts = decode_vmess($item);
        $part_ps = $parts["ps"];
        unset($parts["ps"]);
        ksort($parts);
        $part_serialize = serialize($parts);
        $result[$part_serialize][] = $part_ps ?? "";
    }
    $finalResult = [];
    foreach ($result as $serial => $ps) {
        $partAfterHash = $ps[0] ?? "";
        $part_serialize = unserialize($serial);
        $part_serialize["ps"] = $partAfterHash;
        $finalResult[] = encode_vmess($part_serialize);
    }
    $output = "";
    foreach ($finalResult as $config) {
        $output .= $output == "" ? $config : "\n" . $config;
    }
    return $output;
}

function remove_duplicate_non_vmess($input)
{
    $array = explode("\n", $input);

    foreach ($array as $item) {
        $parts = explode("#", $item);
        $query = parse_url($parts[0], PHP_URL_QUERY);
        parse_str($query, $queryParams);
        ksort($queryParams);
        $queryString = http_build_query($queryParams);
        $result[$parts[0] . '?' . $queryString][] = $parts[1] ?? "";
    }

    $finalResult = [];
    foreach ($result as $url => $parts) {
        $partAfterHash = $parts[0] ?? "";
        $finalResult[] = $url . "#" . $partAfterHash;
    }

    $output = "";
    foreach ($finalResult as $config) {
        $output .= $output == "" ? $config : "\n" . $config;
    }
    return $output;
}

function get_reality($input){
    $array = explode("\n", $input);
    $output = "";
    foreach ($array as $item) {
        if (stripos($item, "reality")){
            $output .= $output === "" ? $item : "\n$item";
        }
    }
    return $output;
}

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
        if ($Types[$CH][$type_count] === "vmess") {
            if ($vmess_data === "") {
                $vmess_data = get_v2ray($CH, $Types[$CH][$type_count], "text");
            } else {
                $vmess_data .= "\n" . get_v2ray($CH, $Types[$CH][$type_count], "text");
            }
        } elseif ($Types[$CH][$type_count] === "vless") {
            if ($vless_data === "") {
                $vless_data = get_v2ray($CH, $Types[$CH][$type_count], "text");
            } else {
                $vless_data .= "\n" . get_v2ray($CH, $Types[$CH][$type_count], "text");
            }
        } elseif ($Types[$CH][$type_count] === "trojan") {
            if ($trojan_data === "") {
                $trojan_data = get_v2ray($CH, $Types[$CH][$type_count], "text");
            } else {
                $trojan_data .= "\n" . get_v2ray($CH, $Types[$CH][$type_count], "text");
            }
        } elseif ($Types[$CH][$type_count] === "ss") {
            if ($shadowsocks_data === "") {
                $shadowsocks_data = get_v2ray(
                    $CH,
                    $Types[$CH][$type_count],
                    "text"
                );
            } else {
                $shadowsocks_data .= "\n" . get_v2ray($CH, $Types[$CH][$type_count], "text");
            }
        }
    }
}

$fixed_string_vmess = remove_duplicate_vmess($vmess_data);
$string_vless = str_replace("&amp;", "&", $vless_data);
$fixed_string_vless = remove_duplicate_non_vmess($string_vless);
$string_trojan =  str_replace("&amp;", "&", $trojan_data);
$fixed_string_trojan = remove_duplicate_non_vmess($string_trojan);
$fixed_string_shadowsocks = remove_duplicate_non_vmess($shadowsocks_data);
$fixed_string_reality = get_reality($fixed_string_vless);

$mix_data =
    $fixed_string_vmess .
    "\n" .
    $fixed_string_vless .
    "\n" .
    $fixed_string_trojan .
    "\n" .
    $fixed_string_shadowsocks;

file_put_contents("sub/mix", $mix_data);
file_put_contents("sub/vmess", $fixed_string_vmess);
file_put_contents("sub/vless", $fixed_string_vless);
file_put_contents("sub/trojan", $fixed_string_trojan);
file_put_contents("sub/shadowsocks", $fixed_string_shadowsocks);
file_put_contents("sub/mix_base64", base64_encode($mix_data));
file_put_contents("sub/vmess_base64", base64_encode($fixed_string_vmess));
file_put_contents("sub/vless_base64", base64_encode($fixed_string_vless));
file_put_contents("sub/trojan_base64", base64_encode($fixed_string_trojan));
file_put_contents(
    "sub/shadowsocks_base64",
    base64_encode($fixed_string_shadowsocks)
);
file_put_contents("sub/reality", $fixed_string_reality);
file_put_contents("sub/reality_base64", base64_encode($fixed_string_reality));
?>
