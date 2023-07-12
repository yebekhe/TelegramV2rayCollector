<?php
header("Content-type: application/json;");

include "modules/get_data.php";
include "modules/config.php";
include "modules/clash.php";
include "modules/ranking.php";

function process_mix_json($input, $name)
{
    $mix_data_json = json_encode($input, JSON_PRETTY_PRINT);
    $mix_data_decode = json_decode($mix_data_json);
    usort($mix_data_decode, "compare_time");
    $mix_data_json = json_encode($mix_data_decode, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $mix_data_json = str_replace("&amp;", "&", $mix_data_json);
    $mix_data_json = str_replace("\\", "", $mix_data_json);
    file_put_contents("json/" . $name, $mix_data_json);
}

$raw_url_base =
    "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main";

$mix_data = [];
$vmess_data = [];
$trojan_data = [];
$vless_data = [];
$shadowsocks_data = [];

foreach ($Types as $key => $type_array) {
    for (
        $type_count = count($type_array) - 1;
        $type_count >= 0;
        $type_count--
    ) {
        if ($type_array[$type_count] === "vmess") {
            $vmess_data = array_merge(
                $vmess_data,
                get_config($key, $type_array[$type_count])
            );
        } elseif ($type_array[$type_count] === "vless") {
            $vless_data = array_merge(
                $vless_data,
                get_config($key, $type_array[$type_count])
            );
        } elseif ($type_array[$type_count] === "trojan") {
            $trojan_data = array_merge(
                $trojan_data,
                get_config($key, $type_array[$type_count])
            );
        } elseif ($type_array[$type_count] === "ss") {
            $shadowsocks_data = array_merge(
                $shadowsocks_data,
                get_config($key, $type_array[$type_count])
            );
        }
    }
}

$vmess_array = [];
$vless_array = [];
$trojan_array = [];
$shadowsocks_array = [];

foreach ($vmess_data as $object) {
    $vmess_array[] = $object["config"];
}
foreach ($vless_data as $object) {
    $vless_array[] = $object["config"];
}
foreach ($trojan_data as $object) {
    $trojan_array[] = $object["config"];
}
foreach ($shadowsocks_data as $object) {
    $shadowsocks_array[] = $object["config"];
}

$vmess = implode("\n", $vmess_array);
$vless = implode("\n", $vless_array);
$trojan = implode("\n", $trojan_array);
$shadowsocks = implode("\n", $shadowsocks_array);

$fixed_string_vmess = remove_duplicate_vmess($vmess);
$fixed_string_vmess_array = explode("\n", $fixed_string_vmess);
$json_vmess_array = [];

foreach ($vmess_data as $vmess_config_data) {
    foreach ($fixed_string_vmess_array as $vmess_config) {
        if (decode_vmess($vmess_config)['ps'] === decode_vmess($vmess_config_data['config'])['ps']) {
            $json_vmess_array[] = $vmess_config_data;
        }
    }
}

$string_vless = str_replace("&amp;", "&", $vless);
$fixed_string_vless = remove_duplicate_xray($string_vless, "vless");
$fixed_string_vless_array = explode("\n", $fixed_string_vless);
$json_vless_array = [];

foreach ($vless_data as $vless_config_data) {
    foreach ($fixed_string_vless_array as $vless_config) {
        if (parseProxyUrl($vless_config, "vless")['hash'] === parseProxyUrl($vless_config_data['config'], "vless")['hash']) {
            $json_vless_array[] = $vless_config_data;
        }
    }
}

$string_trojan = str_replace("&amp;", "&", $trojan);
$fixed_string_trojan = remove_duplicate_xray($string_trojan, "trojan");
$fixed_string_trojan_array = explode("\n", $fixed_string_trojan);
$json_trojan_array = [];

foreach ($trojan_data as $trojan_config_data) {
    foreach ($fixed_string_trojan_array as $key => $trojan_config) {
        if (parseProxyUrl($trojan_config)['hash'] === parseProxyUrl($trojan_config_data['config'])['hash']) {
            $json_trojan_array[$key] = $trojan_config_data;
        }
    }
}

$fixed_string_shadowsocks = remove_duplicate_ss($shadowsocks);
$fixed_string_shadowsocks_array = explode("\n", $fixed_string_shadowsocks);
$json_shadowsocks_array = [];

foreach ($shadowsocks_data as $shadowsocks_config_data) {
    foreach ($fixed_string_shadowsocks_array as $shadowsocks_config) {
        if (ParseShadowsocks($shadowsocks_config)['name'] === ParseShadowsocks($shadowsocks_config_data['config'])['name']) {
            $json_shadowsocks_array[] = $shadowsocks_config_data;
        }
    }
}

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

$mix_data_deduplicate = array_merge($json_vmess_array, $json_vless_array, $json_trojan_array, $json_shadowsocks_array);

$subscription_types = ["mix" => base64_encode($mix), "vmess" => base64_encode($fixed_string_vmess), "vless" => base64_encode($fixed_string_vless), "reality" => base64_encode($fixed_string_reality), "trojan" => base64_encode($fixed_string_trojan), "shadowsocks" => base64_encode($fixed_string_shadowsocks)];

foreach ($subscription_types as $subscription_type => $subscription_data) {
    file_put_contents("sub/" . $subscription_type, base64_decode($subscription_data));
    file_put_contents("sub/" . $subscription_type . "_base64", $subscription_data);
}

process_mix_json($mix_data, "configs.json");
process_mix_json($mix_data_deduplicate, "configs_deduplicate.json");

$clash_types = ["mix" => ["clash" => convert_to_clash($raw_url_base . "/sub/mix_base64"), "meta" => convert_to_clash($raw_url_base . "/sub/mix_base64", "meta"), "surfboard" => convert_to_clash($raw_url_base . "/sub/mix_base64", "surfboard")], "vmess" => ["clash" => convert_to_clash($raw_url_base . "/sub/vmess_base64"), "meta" => convert_to_clash($raw_url_base . "/sub/vmess_base64", "meta"), "surfboard" => convert_to_clash($raw_url_base . "/sub/vmess_base64", "surfboard")], "vless" => ["meta" => convert_to_clash($raw_url_base . "/sub/vless_base64", "meta")], "reality" => ["meta" => convert_to_clash($raw_url_base . "/sub/reality_base64", "meta")], "trojan" => ["clash" => convert_to_clash($raw_url_base . "/sub/trojan_base64"), "meta" => convert_to_clash($raw_url_base . "/sub/trojan_base64", "meta"), "surfboard" => convert_to_clash($raw_url_base . "/sub/trojan_base64", "surfboard")], "shadowsocks" => ["clash" => convert_to_clash($raw_url_base . "/sub/shadowsocks_base64"), "meta" => convert_to_clash($raw_url_base . "/sub/shadowsocks_base64", "meta"), "surfboard" => convert_to_clash($raw_url_base . "/sub/shadowsocks_base64", "surfboard")]];

foreach ($clash_types as $clash_type => $clash_datas) {
    foreach ($clash_datas as $which => $clash_data) {
        if ($which !== "surfboard") {
            file_put_contents($which . "/" . $clash_type . ".yml", $clash_data);
        } else {
            file_put_contents($which . "/" . $clash_type, $clash_data);
        }
    }
}

// Channels ranking

$data = [
    ["data" => $vmess_data, "filename" => "channel_ranking_vmess.json", "type" => "vmess"],
    ["data" => $vless_data, "filename" => "channel_ranking_vless.json", "type" => "vless"],
    ["data" => $trojan_data, "filename" => "channel_ranking_trojan.json", "type" => "trojan"],
    ["data" => $shadowsocks_data, "filename" => "channel_ranking_ss.json", "type" => "ss"]
];

foreach ($data as $item) {
    $channel_ranking = ranking($item['data'], $item['type']);
    $json_content = json_encode($channel_ranking, JSON_PRETTY_PRINT);
    file_put_contents("ranking/{$item['filename']}", $json_content);
}
