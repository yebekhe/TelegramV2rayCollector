<?php
header("Content-type: application/json;");

include "modules/get_data.php";
include "modules/config.php";
include "modules/clash.php";
include "modules/ranking.php";

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

$channel_ranking_vmess = ranking($vmess_data, "vmess");
$channel_ranking_vless = ranking($vless_data, "vless");
$channel_ranking_trojan = ranking($trojan_data, "trojan");
$channel_ranking_shadowsocks = ranking($shadowsocks_data, "ss");

file_put_contents(
    "ranking/channel_ranking_vmess.json",
    json_encode($channel_ranking_vmess, JSON_PRETTY_PRINT)
);
file_put_contents(
    "ranking/channel_ranking_vless.json",
    json_encode($channel_ranking_vless, JSON_PRETTY_PRINT)
);
file_put_contents(
    "ranking/channel_ranking_trojan.json",
    json_encode($channel_ranking_trojan, JSON_PRETTY_PRINT)
);
file_put_contents(
    "ranking/channel_ranking_ss.json",
    json_encode($channel_ranking_shadowsocks, JSON_PRETTY_PRINT)
);

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
file_put_contents(
    "sub/shadowsocks_base64",
    base64_encode($fixed_string_shadowsocks)
);

$mix_data_json = json_encode($mix_data, JSON_PRETTY_PRINT);
$mix_data_decode = json_decode($mix_data_json);
usort($mix_data_decode, "compare_time");
$mix_data_json = json_encode($mix_data_decode, JSON_PRETTY_PRINT);
file_put_contents("json/configs.json", $mix_data_json);

file_put_contents(
    "clash/mix.yml",
    convert_to_clash(
        "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/mix_base64"
    )
);
file_put_contents(
    "clash/vmess.yml",
    convert_to_clash(
        "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vmess_base64"
    )
);
file_put_contents(
    "clash/trojan.yml",
    convert_to_clash(
        "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/trojan_base64"
    )
);
file_put_contents(
    "clash/shadowsocks.yml",
    convert_to_clash(
        "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/shadowsocks_base64"
    )
);

file_put_contents(
    "meta/mix.yml",
    convert_to_clash(
        "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/mix_base64",
        "meta"
    )
);
file_put_contents(
    "meta/vmess.yml",
    convert_to_clash(
        "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vmess_base64",
        "meta"
    )
);
file_put_contents(
    "meta/vless.yml",
    convert_to_clash(
        "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vless_base64",
        "meta"
    )
);
file_put_contents(
    "meta/reality.yml",
    convert_to_clash(
        "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/reality_base64",
        "meta"
    )
);
file_put_contents(
    "meta/trojan.yml",
    convert_to_clash(
        "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/trojan_base64",
        "meta"
    )
);
file_put_contents(
    "meta/shadowsocks.yml",
    convert_to_clash(
        "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/shadowsocks_base64",
        "meta"
    )
);

file_put_contents(
    "surfboard/mix",
    convert_to_clash(
        "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/mix_base64",
         "surfboard"
    )
);
file_put_contents(
    "surfboard/vmess",
    convert_to_clash(
        "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vmess_base64",
        "surfboard"
    )
);
file_put_contents(
    "surfboard/trojan",
    convert_to_clash(
        "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/trojan_base64",
        "surfboard"
    )
);
file_put_contents(
    "surfboard/shadowsocks",
    convert_to_clash(
        "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/shadowsocks_base64",
        "surfboard"
    )
);
