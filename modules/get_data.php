<?php
include "flag.php";
include "ipinfo.php";
include "shadowsocks.php";
include "vmess.php";
include "xray.php";
include "ping.php";

function openLink($url)
{
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36",
        CURLOPT_FOLLOWLOCATION => true,
    ]);
    return curl_exec($ch);
}

function convert_to_iran_time($utc_timestamp)
{
    $utc_datetime = new DateTime($utc_timestamp);
    $utc_datetime->setTimezone(new DateTimeZone("Asia/Tehran"));
    return $utc_datetime->format("Y-m-d H:i:s");
}

function get_config_time($type, $input)
{
    preg_match_all(
        "/" . $type . ':\/\/[^"]+(?:[^<]+<[^<]+)*<time datetime="([^"]+)"/',
        $input,
        $times
    );
    return $times;
}

function get_config_items($type, $input)
{
    preg_match_all("#" . $type . "://(.*?)<#", $input, $items);
    return $items;
}

function is_valid($input)
{
    if (stripos($input, "…") !== false or stripos($input, "...") !== false) {
        return false;
    }
    return true;
}

function is_reality($input, $type)
{
    $is_reality = false;
    switch ($type) {
        case "vmess":
            $is_reality = false;
            break;

        case "vless":
            if (stripos($input, "reality") !== false) {
                $is_reality = true;
            } else {
                $is_reality = false;
            }
            break;
        case "trojan":
            $is_reality = false;
            break;
        case "ss":
            $is_reality = false;
            break;
    }
    return $is_reality;
}

function get_ip($input, $type, $is_reality)
{
    $ip = "";
    switch ($type) {
        case "vmess":
            $ip = !empty($input["sni"])
                ? $input["sni"]
                : (!empty($input["host"])
                    ? $input["host"]
                    : $input["add"]);
            break;
        case "vless":
            $ip = $is_reality
                ? $input["hostname"]
                : (!empty($input["params"]["sni"])
                    ? $input["params"]["sni"]
                    : (!empty($input["params"]["host"])
                        ? $input["params"]["host"]
                        : $input["hostname"]));
            break;
        case "trojan":
            $ip = !empty($input["params"]["sni"])
                ? $input["params"]["sni"]
                : (!empty($input["params"]["host"])
                    ? $input["params"]["host"]
                    : $input["hostname"]);
            break;
        case "ss":
            $ip = $input["server_address"];
            break;
    }
    return $ip;
}
function get_port($input, $type)
{
    $port = "";
    switch ($type) {
        case "vmess":
            $port = $input["port"];
            break;
        case "vless":
            $port = $input["port"];
            break;
        case "trojan":
            $port = $input["port"];
            break;
        case "ss":
            $port = $input["server_port"];
            break;
    }
    return $port;
}

function get_flag($ip)
{
    $flag = "";
    $ip_info = ip_info($ip);
    if (isset($ip_info["country"])) {
        $location = $ip_info["country"];
        $flag = getFlags($location);
    } else {
        $flag = "🚩";
    }
    return $flag;
}

function get_channels_assets()
{
    return json_decode(
        file_get_contents("modules/channels/channels_assets.json"),
        true
    );
}

function generate_name($channel, $flag, $ping, $is_reality)
{
    $name = "";
    switch ($is_reality) {
        case true:
            $name = "REALITY|" . "@" . $channel . " | " . $flag . " | " . $ping;
            break;
        case false:
            $name = "@" . $channel . " | " . $flag . " | " . $ping;
            break;
    }
    return $name;
}

function parse_config($input, $type, $is_sub = false)
{
    $parsed_config = [];
    switch ($type) {
        case "vmess":
            $parsed_config = $is_sub ? decode_vmess($input) : decode_vmess($type . "://" . $input);
            break;
        case "vless":
            $parsed_config = $is_sub ? parseProxyUrl($input, "vless") : parseProxyUrl($type . "://" . $input, "vless");
            break;
        case "trojan":
            $parsed_config = $is_sub ? parseProxyUrl($input) : parseProxyUrl($type . "://" . $input);
            break;
        case "ss":
            $parsed_config = $is_sub ? ParseShadowsocks($input) : ParseShadowsocks($type . "://" . $input);
            break;
    }
    return $parsed_config;
}

function build_config($input, $type)
{
    $build_config = "";
    switch ($type) {
        case "vmess":
            $build_config = encode_vmess($input);
            break;
        case "vless":
            $build_config = buildProxyUrl($input, "vless");
            break;
        case "trojan":
            $build_config = buildProxyUrl($input);
            break;
        case "ss":
            $build_config = BuildShadowsocks($input);
            break;
    }
    return $build_config;
}

function get_config($channel, $type)
{
    $name_array = [
        "vmess" => "ps",
        "vless" => "hash",
        "trojan" => "hash",
        "ss" => "remarks",
    ];
    // Fetch the content of the Telegram channel URL
    $get = file_get_contents("https://t.me/s/" . $channel);

    // Load channels_assets JSON data
    $channels_assets = get_channels_assets();

    $matches = get_config_time($type, $get);
    $configs = get_config_items($type, $get);

    $final_data = [];
    $key_limit = count($configs[1]) - 3;

    foreach ($configs[1] as $key => $config) {
        if ($key >= $key_limit) {
            if (is_valid($config)) {
                if (strpos($config, "<br/>") !== false) {
                    $config = substr($config, 0, strpos($config, "<br/>"));
                }

                $is_reality = is_reality($config, $type);

                $the_config = parse_config($config, $type);

                $ip = get_ip($the_config, $type, $is_reality);
                $port = get_port($the_config, $type);

                @$ping_data = ping($ip, $port);
                if ($ping_data !== "unavailable") {
                    $flag = get_flag($ip);

                    $name_key = $name_array[$type];
                    $the_config[$name_key] = generate_name(
                        $channel,
                        $flag,
                        $ping_data,
                        $is_reality
                    );
                    $final_config = build_config($the_config, $type);

                    $final_data[$key]["channel"]["username"] = $channel;
                    $final_data[$key]["channel"]["title"] =
                        $channels_assets[$channel]["title"];
                    $final_data[$key]["channel"]["logo"] =
                        $channels_assets[$channel]["logo"];
                    $final_data[$key]["type"] = $is_reality ? "reality" : $type;
                    $final_data[$key]["config"] = $final_config;
                    $final_data[$key]["ping"] = $ping_data;
                    $final_data[$key]["time"] = convert_to_iran_time(
                        $matches[1][$key]
                    );
                }
            }
        }
    }
    // Return the final data array
    return $final_data;
}

function detect_type($input)
{
    $type = "";
    if (substr($input, 0, 8) === "vmess://") {
        $type = "vmess";
    } elseif (substr($input, 0, 8) === "vless://") {
        $type = "vless";
    } elseif (substr($input, 0, 9) === "trojan://") {
        $type = "trojan";
    } elseif (substr($input, 0, 5) === "ss://") {
        $type = "ss";
    }

    return $type;
}

function process_subscription($input, $channel)
{
    $name_array = [
        "vmess" => "ps",
        "vless" => "hash",
        "trojan" => "hash",
        "ss" => "remarks",
    ];

    $final_data = [];
    $configs = explode("\n", $input);
    $array_helper_vmess = 0;
    $array_helper_vless = 0;
    $array_helper_ss = 0;
    $array_helper_trojan = 0;
    foreach ($configs as $config) {
        $type = detect_type($config);
        $is_reality = is_reality($config, $type);

        $the_config = parse_config($config, $type, true);

        $ip = get_ip($the_config, $type, $is_reality);
        $port = get_port($the_config, $type);

        @$ping_data = ping($ip, $port);
        if ($ping_data !== "unavailable") {
            $flag = get_flag($ip);

            $name_key = $name_array[$type];
            $the_config[$name_key] = generate_name(
                $channel,
                $flag,
                $ping_data,
                $is_reality
            );
            $final_config = build_config($the_config, $type);

            $key = ${"array_helper_$type"};

            $final_data[$type][$key]["channel"]["username"] = $channel;
            $final_data[$type][$key]["channel"]["title"] = $channel;
            $final_data[$type][$key]["channel"]["logo"] = "null";
            $final_data[$type][$key]["type"] = $is_reality ? "reality" : $type;
            $final_data[$type][$key]["config"] = $final_config;
            $final_data[$type][$key]["ping"] = $ping_data;
            $final_data[$type][$key]["time"] = tehran_time();

            $key++;
            ${"array_helper_$type"} = $key;
        }
    }
    return $final_data;
}
