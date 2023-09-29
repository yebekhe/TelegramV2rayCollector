<?php
header("Content-type: application/json;"); // Set response content type as JSON

include "modules/get_data.php"; // Include the get_data module
include "modules/config.php"; // Include the config module
include "modules/ranking.php"; // Include the ranking module
include "modules/singbox.php"; // Include the singbox module

function addHeader ($subscription, $subscriptionName) {
    $headerText = "#profile-title: base64:" . base64_encode($subscriptionName) . "
#profile-update-interval: 1
#subscription-userinfo: upload=0; download=0; total=10737418240000000; expire=2546249531
#support-url: https://t.me/v2raycollector
#profile-web-page-url: https://github.com/yebekhe/TelegramV2rayCollector

";

    return $headerText . $subscription;
}

function deleteFolder($folder) {
    if (!is_dir($folder)) {
        return;
    }
    $files = glob($folder . '/*');
    foreach ($files as $file) {
        is_dir($file) ? deleteFolder($file) : unlink($file);
    }
    rmdir($folder);
}

function seprate_by_country($configs){
    $configsArray = explode("\n", $configs);
    $configLocation = "";
    $output = [];
    foreach ($configsArray as $config) {
        $configType = detect_type($config);

        if ($configType === "vmess") {
            $configName = parse_config($config, "vmess", true)['ps'];
        } elseif ($configType === "vless" || $configType === "trojan" ){
            $configName = parse_config($config, $configType)['hash'];
        } elseif ($configType === "ss"){
            $configName = parse_config($config, "ss")['name'];
        } elseif ($configType === "tuic"){
            $configName = parse_config($config, "tuic")['hash'];
        } elseif ($configType === "hy2"){
            $configName = parse_config($config, "hy2")['hash'];
        }

        if (stripos($configName, "RELAY🚩")){
            $configLocation = "RELAY";
        } else {
            $pattern = '/\b([A-Z]{2})\b[\x{1F1E6}-\x{1F1FF}]{2}/u';
            preg_match_all($pattern, $configName, $matches);
            $configLocation = $matches[1][0];
        }

        if (!isset($output[$configLocation])){
            $output[$configLocation] = [];
        }
        
        if (!in_array($config, $output[$configLocation])) {
            $output[$configLocation][] = $config;
        }
    }
    return $output;
}

function process_mix_json($input, $name)
{
    $mix_data_json = json_encode($input, JSON_PRETTY_PRINT); // Encode input array to JSON with pretty printing
    $mix_data_decode = json_decode($mix_data_json); // Decode the JSON into an object or array
    usort($mix_data_decode, "compare_time"); // Sort the decoded data using the "compare_time" function
    $mix_data_json = json_encode(
        $mix_data_decode,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    ); // Re-encode the sorted data to JSON with pretty printing and Unicode characters not escaped
    $mix_data_json = urldecode($mix_data_json);
    $mix_data_json = str_replace("amp;", "", $mix_data_json); // Replace HTML-encoded ampersands with regular ampersands
    $mix_data_json = str_replace("\\", "", $mix_data_json); // Remove backslashes from the JSON string
    file_put_contents("json/" . $name, $mix_data_json); // Save the JSON data to a file in the "json/" directory with the specified name
}

function fast_fix($input){
    $input = urldecode($input);
    $input = str_replace("amp;", "", $input);
    return $input;
}

function config_array($input){
    return array_map(function ($object) {
    return $object["config"];
}, $input);
}

$raw_url_base =
    "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main"; // Define the base URL for fetching raw data

$mix_data = []; // Initialize an empty array for mix data
$vmess_data = []; // Initialize an empty array for vmess data
$trojan_data = []; // Initialize an empty array for trojan data
$vless_data = []; // Initialize an empty array for vless data
$shadowsocks_data = []; // Initialize an empty array for shadowsocks data
$tuic_data = []; // Initialize an empty array for tuic data
$hy2_data = []; // Initialize an empty array for hy2 data


//Get data from channels
foreach ($Types as $channelUsername => $type_array) {
    $count = count($type_array);
    for ($type_count = $count - 1; $type_count >= 0; $type_count--) {
        $current_type = $type_array[$type_count];
        if ($current_type === "vmess") {
                // Merge the results of `get_config` function with $vmess_data array
                $vmess_data = array_merge(
                    $vmess_data,
                    /** @scrutinizer ignore-call */ 
                    get_config($channelUsername, $current_type)
                );
        } 
        if ($current_type === "vless") {
                // Merge the results of `get_config` function with $vless_data array
                $vless_data = array_merge(
                    $vless_data,
                    /** @scrutinizer ignore-call */
                    get_config($channelUsername, $current_type)
                );
        } 
        if ($current_type === "trojan") {
                // Merge the results of `get_config` function with $trojan_data array
                $trojan_data = array_merge(
                    $trojan_data,
                    /** @scrutinizer ignore-call */
                    get_config($channelUsername, $current_type)
                );
        } 
        if($current_type === "ss") {
                // Merge the results of `get_config` function with $shadowsocks_data array
                $shadowsocks_data = array_merge(
                    $shadowsocks_data,
                    /** @scrutinizer ignore-call */
                    get_config($channelUsername, $current_type)
                );
        } 
        if ($current_type === "tuic") {
                // Merge the results of `get_config` function with $tuic_data array
                $tuic_data = array_merge(
                    $tuic_data,
                    /** @scrutinizer ignore-call */
                    get_config($channelUsername, $current_type)
                );
        }
        if ($current_type === "hy2") {
            // Merge the results of `get_config` function with $tuic_data array
            $hy2_data = array_merge(
                $hy2_data,
                /** @scrutinizer ignore-call */
                get_config($channelUsername, $current_type)
            );
    }
    }
}

$donated_vmess_data = []; // Initialize an empty array for vmess data
$donated_trojan_data = []; // Initialize an empty array for trojan data
$donated_vless_data = []; // Initialize an empty array for vless data
$donated_shadowsocks_data = []; // Initialize an empty array for shadowsocks data
$donated_tuic_data = []; // Initialize an empty array for tuic data
$donated_hy2_data = []; // Initialize an empty array for tuic data

$base_donated_url = "https://yebekhe.000webhostapp.com/donate/donated_servers/";

$processed_subscription = [];
$usernames = [];
foreach ($donated_subscription as $url){
    $max_attempts = 3;
    $attempts = 0;
    while ($attempts < $max_attempts) {
        try {
            $usernames = json_decode(file_get_contents($url), true);
            break; // Success, so break out of the loop
        } catch (Exception $e) {
            // Handle the error here, e.g. by logging it
            $attempts++;
            if ($attempts == $max_attempts) {
             // Reached max attempts, so throw an exception to indicate failure
                throw new Exception('Failed to retrieve data after ' . $max_attempts . ' attempts.');
            }
        sleep(1); // Wait for 1 second before retrying
        }
    }
    foreach ($usernames as $username){
        $subscription_data = file_get_contents($base_donated_url . $username);
        $processed_subscription = /** @scrutinizer ignore-call */ process_subscription($subscription_data, $username);
        foreach ($processed_subscription as $donated_type => $donated_data){
            if ($donated_type === "vmess") {
                    $donated_vmess_data = array_merge(
                        $donated_vmess_data,
                        $donated_data
                    );
            } 
            if ($donated_type === "vless") {
                    $donated_vless_data = array_merge(
                        $donated_vless_data,
                        $donated_data
                    );
            } 
            if ($donated_type === "ss") {
                    $donated_shadowsocks_data = array_merge(
                        $donated_shadowsocks_data,
                        $donated_data
                    );
            } 
            if ($donated_type === "trojan") {
                    $donated_trojan_data = array_merge(
                        $donated_trojan_data,
                        $donated_data
                    );
            } 
            if ($donated_type === "tuic") {
                    $donated_tuic_data = array_merge(
                        $donated_tuic_data,
                        $donated_data
                    );
            }
            if ($donated_type === "hy2") {
                $donated_hy2_data = array_merge(
                    $donated_hy2_data,
                    $donated_data
                );
        }
        }
    }
}

$string_donated_vmess = $donated_vmess_data !== [] ? remove_duplicate_vmess(implode("\n", config_array($donated_vmess_data))) : "";
$string_donated_vless = $donated_vless_data !== [] ? remove_duplicate_xray(fast_fix(implode("\n", config_array($donated_vless_data))), "vless") : "";
$string_donated_trojan = $donated_trojan_data !== [] ? remove_duplicate_xray(fast_fix(implode("\n", config_array($donated_trojan_data))), "trojan") : "";
$string_donated_shadowsocks = $donated_shadowsocks_data !== [] ? remove_duplicate_ss(fast_fix(implode("\n", config_array($donated_shadowsocks_data)))) : "";
$string_donated_tuic = $donated_tuic_data !== [] ? remove_duplicate_tuic(fast_fix(implode("\n", config_array($donated_tuic_data)))) : "";
$string_donated_hy2 = $donated_tuic_hy2 !== [] ? remove_duplicate_hy2(fast_fix(implode("\n", config_array($donated_hy2_data)))) : "";
$string_donated_reality = get_reality($string_donated_vless);

$donated_mix =
    $string_donated_vmess .
    "\n" .
    $string_donated_vless .
    "\n" .
    $string_donated_trojan .
    "\n" .
    $string_donated_shadowsocks .
    "\n" .
    $string_donated_tuic .
    "\n" .
    $string_donated_hy2;
$donated_array = explode("\n", $donated_mix);

foreach ($donated_array as $key => $donated_config){
    if ($donated_config === ""){
        unset($donated_array[$key]);
    }
}

$donated_mix = implode("\n", $donated_array);

file_put_contents("sub/normal/donated", addHeader($donated_mix, "TVC | DONATED"));
file_put_contents("sub/base64/donated", base64_encode(addHeader($donated_mix, "TVC | DONATED")));

// Extract the "config" value from each object in $type_data and store it in $type_array
$vmess_array = config_array($vmess_data);
$vless_array = config_array($vless_data);
$trojan_array = config_array($trojan_data);
$shadowsocks_array = config_array($shadowsocks_data);
$tuic_array = config_array($tuic_data);
$hy2_array = config_array($hy2_data);

$fixed_string_vmess = remove_duplicate_vmess(implode("\n", $vmess_array));
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

$string_vless = fast_fix(implode("\n", $vless_array));
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

$string_trojan = fast_fix(implode("\n", $trojan_array));
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

$string_shadowsocks = fast_fix(implode("\n", $shadowsocks_array));
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

$string_tuic = fast_fix(implode("\n", $tuic_array));
$fixed_string_tuic = remove_duplicate_tuic($string_tuic);
$fixed_string_tuic_array = explode("\n", $fixed_string_tuic);
$json_tuic_array = [];

// Iterate over $tuic_data and $fixed_string_tuic_array to find matching configurations
foreach ($tuic_data as $tuic_config_data) {
    foreach ($fixed_string_tuic_array as $key => $tuic_config) {
        if (
            parseTuic($tuic_config)["hash"] ===
            parseTuic($tuic_config_data["config"])["hash"]
        ) {
            // Add matching configuration to $json_tuic_array
            $json_tuic_array[$key] = $tuic_config_data;
        }
    }
}

$string_hy2 = fast_fix(implode("\n", $hy2_array));
$fixed_string_hy2 = remove_duplicate_hy2($string_hy2);
$fixed_string_hy2_array = explode("\n", $fixed_string_hy2);
$json_hy2_array = [];

// Iterate over $hy2_data and $fixed_string_hy2_array to find matching configurations
foreach ($hy2_data as $hy2_config_data) {
    foreach ($fixed_string_hy2_array as $key => $hy2_config) {
        if (
            parsehy2($hy2_config)["hash"] ===
            parsehy2($hy2_config_data["config"])["hash"]
        ) {
            // Add matching configuration to $json_hy2_array
            $json_hy2_array[$key] = $hy2_config_data;
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
    $fixed_string_shadowsocks .
    "\n" .
    $fixed_string_tuic .
    "\n" .
    $fixed_string_hy2 .
    "\n" .
    $donated_mix;

$mix_data = array_merge(
    $vmess_data,
    $vless_data,
    $trojan_data,
    $shadowsocks_data,
    $tuic_data,
    $hy2_data
);

$mix_data_deduplicate = array_merge(
    $json_vmess_array,
    $json_vless_array,
    $json_trojan_array,
    $json_shadowsocks_array,
    $json_tuic_array,
    $json_hy2_array
);

$subscription_types = [
    "mix" => base64_encode(addHeader($mix, "TVC | MIX")),
    "vmess" => base64_encode(addHeader($fixed_string_vmess, "TVC | VMESS")),
    "vless" => base64_encode(addHeader($fixed_string_vless, "TVC | VLESS")),
    "reality" => base64_encode(addHeader($fixed_string_reality, "TVC | REALITY")),
    "trojan" => base64_encode(addHeader($fixed_string_trojan, "TVC | TROJAN")),
    "shadowsocks" => base64_encode(addHeader($fixed_string_shadowsocks, "TVC | SHADOWSOCKS")),
    "tuic" => base64_encode(addHeader($fixed_string_tuic, "TVC | TUIC")),
    "hysteria2" => base64_encode(addHeader($fixed_string_hy2, "TVC | HYSTERIA2")),
];

// Write subscription data to files
foreach ($subscription_types as $subscription_type => $subscription_data) {
    file_put_contents(
        "sub/normal/" . $subscription_type,
        base64_decode($subscription_data)
    );
    file_put_contents(
        "sub/base64/" . $subscription_type,
        $subscription_data
    );
}

$countryBased = seprate_by_country($mix);
deleteFolder("country");
mkdir("country");
foreach ($countryBased as $country => $configsArray) {
    if (!is_dir("country/". $country)) {
        mkdir("country/". $country);
    }
    $configsSub = implode("\n", $configsArray);
    file_put_contents("country/". $country . "/normal", $configsSub);
    file_put_contents("country/". $country . "/base64", base64_encode($configsSub));
}

process_mix_json($mix_data, "configs.json");
process_mix_json($mix_data_deduplicate, "configs_deduplicate.json");

$singboxTypes = [
    "mix" => $mix,
    "vmess" => $fixed_string_vmess,
    "vless" => $fixed_string_vless,
    "trojan" => $fixed_string_trojan,
    "shadowsocks" => $fixed_string_shadowsocks,
    "tuic" => $fixed_string_tuic,
    "hysteria2" => $fixed_string_hy2,
];

foreach ($singboxTypes as $singboxType => $subContents) {
    file_put_contents("singbox/nekobox/118/" . $singboxType . ".json", GenerateConfig($subContents, "nnew", $singboxType));
    file_put_contents("singbox/nekobox/117/" . $singboxType . ".json", GenerateConfig($subContents, "nold", $singboxType));
    file_put_contents("singbox/sfasfi/" . $singboxType . ".json", GenerateConfig($subContents, "sfia", $singboxType));
    file_put_contents("singbox/nekobox/118/" . $singboxType . "Lite.json", GenerateConfigLite($subContents, "nnew", $singboxType));
    file_put_contents("singbox/nekobox/117/" . $singboxType . "Lite.json", GenerateConfigLite($subContents, "nold", $singboxType));
    file_put_contents("singbox/sfasfi/" . $singboxType . "Lite.json", GenerateConfigLite($subContents, "sfia", $singboxType));
}

$the_string_reality_singbox = $fixed_string_reality . "\n" . $string_donated_reality ;
$string_reality_singbox = remove_duplicate_xray($the_string_reality_singbox, "vless");

file_put_contents("singbox/nekobox/117/reality.json", GenerateConfig($string_reality_singbox, "nold", "reality"));
file_put_contents("singbox/nekobox/118/reality.json", GenerateConfig($string_reality_singbox, "nnew", "reality"));
file_put_contents("singbox/sfasfi/reality.json", GenerateConfig($string_reality_singbox,"sfia", "reality"));
file_put_contents("singbox/nekobox/117/realityLite.json", GenerateConfigLite($string_reality_singbox, "nold", "reality"));
file_put_contents("singbox/nekobox/118/realityLite.json", GenerateConfigLite($string_reality_singbox, "nnew", "reality"));
file_put_contents("singbox/sfasfi/realityLite.json", GenerateConfigLite($string_reality_singbox,"sfia", "reality"));

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
    [
        "data" => $tuic_data,
        "filename" => "channel_ranking_tuic.json",
        "type" => "tuic",
    ],
    [
        "data" => $hy2_data,
        "filename" => "channel_ranking_hy2.json",
        "type" => "hy2",
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
