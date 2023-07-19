<?php
header("Content-type: application/json;"); // Set response content type as JSON

include "modules/get_data.php"; // Include the get_data module
include "modules/config.php"; // Include the config module
include "modules/clash.php"; // Include the clash module
include "modules/ranking.php"; // Include the ranking module

function process_mix_json($input, $name)
{
    $mix_data_json = json_encode($input, JSON_PRETTY_PRINT); // Encode input array to JSON with pretty printing
    $mix_data_decode = json_decode($mix_data_json); // Decode the JSON into an object or array
    usort($mix_data_decode, "compare_time"); // Sort the decoded data using the "compare_time" function
    $mix_data_json = json_encode(
        $mix_data_decode,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    ); // Re-encode the sorted data to JSON with pretty printing and Unicode characters not escaped
    $mix_data_json = str_replace("&amp;", "&", $mix_data_json); // Replace HTML-encoded ampersands with regular ampersands
    $mix_data_json = str_replace("\\", "", $mix_data_json); // Remove backslashes from the JSON string
    file_put_contents("json/" . $name, $mix_data_json); // Save the JSON data to a file in the "json/" directory with the specified name
}

function fast_fix($input){
    $input = urldecode($input);
    $input = str_replace("amp;", "", $input);
    return $input;
}

$raw_url_base =
    "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main"; // Define the base URL for fetching raw data

$mix_data = []; // Initialize an empty array for mix data
$vmess_data = []; // Initialize an empty array for vmess data
$trojan_data = []; // Initialize an empty array for trojan data
$vless_data = []; // Initialize an empty array for vless data
$shadowsocks_data = []; // Initialize an empty array for shadowsocks data

//Get data from channels
foreach ($Types as $key => $type_array) {
    $count = count($type_array);
    for ($type_count = $count - 1; $type_count >= 0; $type_count--) {
        $current_type = $type_array[$type_count];
        switch ($current_type) {
            case "vmess":
                // Merge the results of `get_config` function with $vmess_data array
                $vmess_data = array_merge(
                    $vmess_data,
                    /** @scrutinizer ignore-call */ 
                    get_config($key, $current_type)
                );
                break;
            case "vless":
                // Merge the results of `get_config` function with $vless_data array
                $vless_data = array_merge(
                    $vless_data,
                    get_config($key, $current_type)
                );
                break;
            case "trojan":
                // Merge the results of `get_config` function with $trojan_data array
                $trojan_data = array_merge(
                    $trojan_data,
                    get_config($key, $current_type)
                );
                break;
            case "ss":
                // Merge the results of `get_config` function with $shadowsocks_data array
                $shadowsocks_data = array_merge(
                    $shadowsocks_data,
                    get_config($key, $current_type)
                );
                break;
            default:
                // Do nothing if unknown type is encountered
                break;
        }
    }
}

$base_donated_url = "https://yebekhe.000webhostapp.com/donate/donated_servers/";

$processed_subscription = [];
$usernames = [];
foreach ($donated_subscription as $url){
    $usernames = json_decode(file_get_contents($url), true);
    foreach ($usernames as $username){
        $subscription_data = file_get_contents($base_donated_url . $username);
        $processed_subscription = /** @scrutinizer ignore-call */ process_subscription($subscription_data, $username);
        foreach ($processed_subscription as $donated_type => $donated_data){
            switch ($donated_type){
                case "vmess" :
                    $vmess_data = array_merge(
                        $vmess_data,
                        $donated_data
                    );
                    break;
                case "vless" :
                    $vless_data = array_merge(
                        $vless_data,
                        $donated_data
                    );
                    break;
                case "ss" :
                    $shadowsocks_data = array_merge(
                        $shadowsocks_data,
                        $donated_data
                    );
                    break;
                case "trojan" :
                    $trojan_data = array_merge(
                        $trojan_data,
                        $donated_data
                    );
                    break;
            }
        }
    }
}

// Extract the "config" value from each object in $vmess_data and store it in $vmess_array
$vmess_array = array_map(function ($object) {
    return $object["config"];
}, $vmess_data);

// Extract the "config" value from each object in $vless_data and store it in $vless_array
$vless_array = array_map(function ($object) {
    return $object["config"];
}, $vless_data);

// Extract the "config" value from each object in $trojan_data and store it in $trojan_array
$trojan_array = array_map(function ($object) {
    return $object["config"];
}, $trojan_data);

// Extract the "config" value from each object in $shadowsocks_data and store it in $shadowsocks_array
$shadowsocks_array = array_map(function ($object) {
    return $object["config"];
}, $shadowsocks_data);

$vmess = implode("\n", $vmess_array);
$vless = implode("\n", $vless_array);
$trojan = implode("\n", $trojan_array);
$shadowsocks = implode("\n", $shadowsocks_array);

$fixed_string_vmess = remove_duplicate_vmess($vmess);
$fixed_string_vmess_array = explode("\n", $fixed_string_vmess);
$json_vmess_array = [];

// Iterate over $vmess_data and $fixed_string_vmess_array to find matching configurations
foreach ($vmess_data as $vmess_config_data) {
    foreach ($fixed_string_vmess_array as $vmess_config) {
        if (
            decode_vmess($vmess_config)["ps"] ===
            decode_vmess($vmess_config_data["config"])["ps"]
        ) {
            // Add matching configuration to $json_vmess_array
            $json_vmess_array[] = $vmess_config_data;
        }
    }
}

$string_vless = fast_fix($vless);
$fixed_string_vless = remove_duplicate_xray($string_vless, "vless");
$fixed_string_vless_array = explode("\n", $fixed_string_vless);
$json_vless_array = [];

// Iterate over $vless_data and $fixed_string_vless_array to find matching configurations
foreach ($vless_data as $vless_config_data) {
    foreach ($fixed_string_vless_array as $vless_config) {
        if (
            parseProxyUrl($vless_config, "vless")["hash"] ===
            parseProxyUrl($vless_config_data["config"], "vless")["hash"]
        ) {
            // Add matching configuration to $json_vless_array
            $json_vless_array[] = $vless_config_data;
        }
    }
}

$string_trojan = fast_fix($trojan);
$fixed_string_trojan = remove_duplicate_xray($string_trojan, "trojan");
$fixed_string_trojan_array = explode("\n", $fixed_string_trojan);
$json_trojan_array = [];

// Iterate over $trojan_data and $fixed_string_trojan_array to find matching configurations
foreach ($trojan_data as $trojan_config_data) {
    foreach ($fixed_string_trojan_array as $key => $trojan_config) {
        if (
            parseProxyUrl($trojan_config)["hash"] ===
            parseProxyUrl($trojan_config_data["config"])["hash"]
        ) {
            // Add matching configuration to $json_trojan_array
            $json_trojan_array[$key] = $trojan_config_data;
        }
    }
}

$string_shadowsocks = fast_fix($shadowsocks);
$fixed_string_shadowsocks = remove_duplicate_ss($string_shadowsocks);
$fixed_string_shadowsocks_array = explode("\n", $fixed_string_shadowsocks);
$json_shadowsocks_array = [];

// Iterate over $shadowsocks_data and $fixed_string_shadowsocks_array to find matching configurations
foreach ($shadowsocks_data as $shadowsocks_config_data) {
    foreach ($fixed_string_shadowsocks_array as $shadowsocks_config) {
        if (
            ParseShadowsocks($shadowsocks_config)["name"] ===
            ParseShadowsocks($shadowsocks_config_data["config"])["name"]
        ) {
            // Add matching configuration to $json_shadowsocks_array
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

$mix_data_deduplicate = array_merge(
    $json_vmess_array,
    $json_vless_array,
    $json_trojan_array,
    $json_shadowsocks_array
);

$subscription_types = [
    "mix" => base64_encode($mix),
    "vmess" => base64_encode($fixed_string_vmess),
    "vless" => base64_encode($fixed_string_vless),
    "reality" => base64_encode($fixed_string_reality),
    "trojan" => base64_encode($fixed_string_trojan),
    "shadowsocks" => base64_encode($fixed_string_shadowsocks),
];

// Write subscription data to files
foreach ($subscription_types as $subscription_type => $subscription_data) {
    file_put_contents(
        "sub/" . $subscription_type,
        base64_decode($subscription_data)
    );
    file_put_contents(
        "sub/" . $subscription_type . "_base64",
        $subscription_data
    );
}

process_mix_json($mix_data, "configs.json");
process_mix_json($mix_data_deduplicate, "configs_deduplicate.json");

$clash_types = [
    "mix" => [
        "clash" => convert_to_clash($raw_url_base . "/sub/mix_base64"),
        "meta" => convert_to_clash($raw_url_base . "/sub/mix_base64", "meta"),
        "surfboard" => convert_to_clash(
            $raw_url_base . "/sub/mix_base64",
            "surfboard"
        ),
    ],
    "vmess" => [
        "clash" => convert_to_clash($raw_url_base . "/sub/vmess_base64"),
        "meta" => convert_to_clash($raw_url_base . "/sub/vmess_base64", "meta"),
        "surfboard" => convert_to_clash(
            $raw_url_base . "/sub/vmess_base64",
            "surfboard"
        ),
    ],
    "vless" => [
        "meta" => convert_to_clash($raw_url_base . "/sub/vless_base64", "meta"),
    ],
    "reality" => [
        "meta" => convert_to_clash(
            $raw_url_base . "/sub/reality_base64",
            "meta"
        ),
    ],
    "trojan" => [
        "clash" => convert_to_clash($raw_url_base . "/sub/trojan_base64"),
        "meta" => convert_to_clash(
            $raw_url_base . "/sub/trojan_base64",
            "meta"
        ),
        "surfboard" => convert_to_clash(
            $raw_url_base . "/sub/trojan_base64",
            "surfboard"
        ),
    ],
    "shadowsocks" => [
        "clash" => convert_to_clash($raw_url_base . "/sub/shadowsocks_base64"),
        "meta" => convert_to_clash(
            $raw_url_base . "/sub/shadowsocks_base64",
            "meta"
        ),
        "surfboard" => convert_to_clash(
            $raw_url_base . "/sub/shadowsocks_base64",
            "surfboard"
        ),
    ],
];

// Write Clash configuration data to files
foreach ($clash_types as $clash_type => $clash_datas) {
    foreach ($clash_datas as $which => $clash_data) {
        if ($which !== "surfboard") {
            file_put_contents($which . "/" . $clash_type . ".yml", $clash_data);
        } else {
            file_put_contents($which . "/" . $clash_type, $clash_data);
        }
    }
}

$data = [
    [
        "data" => $vmess_data,
        "filename" => "channel_ranking_vmess.json",
        "type" => "vmess",
    ],
    [
        "data" => $vless_data,
        "filename" => "channel_ranking_vless.json",
        "type" => "vless",
    ],
    [
        "data" => $trojan_data,
        "filename" => "channel_ranking_trojan.json",
        "type" => "trojan",
    ],
    [
        "data" => $shadowsocks_data,
        "filename" => "channel_ranking_ss.json",
        "type" => "ss",
    ],
];

// Process each item in the data array
foreach ($data as $item) {
    // Calculate ranking for the specific type of data
    $channel_ranking = ranking($item["data"], $item["type"]);

    // Convert the ranking to JSON format
    $json_content = json_encode($channel_ranking, JSON_PRETTY_PRINT);

    // Write the JSON content to a file in the "ranking" directory
    file_put_contents("ranking/{$item["filename"]}", $json_content);
}
