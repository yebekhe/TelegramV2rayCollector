<?php
include "flag.php";
include "ipinfo.php";
include "shadowsocks.php";
include "vmess.php";
include "xray.php";
include "tuic.php";
include "hysteria2.php";
include "ping.php";

function numberToEmoji($number)
{
    $map = [
        "0" => "0️⃣",
        "1" => "1️⃣",
        "2" => "2️⃣",
        "3" => "3️⃣",
        "4" => "4️⃣",
        "5" => "5️⃣",
        "6" => "6️⃣",
        "7" => "7️⃣",
        "8" => "8️⃣",
        "9" => "9️⃣",
    ];

    $emoji = "";
    $digits = str_split($number);

    foreach ($digits as $digit) {
        if (count($digits) === 1) {
            $emoji = $map["0"];
        }
        if (isset($map[$digit])) {
            $emoji .= $map[$digit];
        }
    }

    return $emoji;
}

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
    preg_match_all("#>" . $type . "://(.*?)<#", $input, $items);
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
    switch ($type) {
        case "vmess":
            return false;
        case "vless":
            if (stripos($input, "reality") !== false) {
                return true;
            } else {
                return false;
            }
        case "trojan":
            return false;
        case "tuic":
            return false;
        case "hy2":
            return false;
        case "ss":
            return false;
    }
}

function check_pbk($input)
{
    if (stripos($input, "pbk=&") !== false) {
        return false;
    } else {
        return true;
    }
}

function get_ip($input, $type, $is_reality)
{
    switch ($type) {
        case "vmess":
            return get_vmess_ip($input);
        case "vless":
            return get_vless_ip($input, $is_reality);
        case "trojan":
            return get_trojan_ip($input);
        case "ss":
            return get_ss_ip($input);
        case "tuic":
            return get_tuic_ip($input);
        case "hy2":
            return get_hy2_ip($input);
    }
}

function get_address($input, $type)
{
    switch ($type) {
        case "vmess":
            return $input["add"];
        case "vless":
        case "trojan":
            return $input["hostname"];
        case "tuic":
            return $input["hostname"];
        case "hy2":
            return $input["hostname"];
        case "ss":
            return $input["server_address"];
    }
}

function is_number_with_dots($s)
{
    /*
     * Returns true if the given string contains only digits and dots, and false otherwise.
     */
    for ($i = 0; $i < strlen($s); $i++) {
        $c = $s[$i];
        if (!ctype_digit($c) && $c != ".") {
            return false;
        }
    }
    return true;
}

function is_valid_address($address)
{
    $ipv4_pattern = '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/';
    $ipv6_pattern = '/^[0-9a-fA-F:]+$/'; // matches any valid IPv6 address

    if (
        preg_match($ipv4_pattern, $address) ||
        preg_match($ipv6_pattern, $address)
    ) {
        return true;
    } elseif (is_number_with_dots($address) === false) {
        if (
            substr($address, 0, 8) === "https://" ||
            substr($address, 0, 7) === "http://"
        ) {
            $url = filter_var($address, FILTER_VALIDATE_URL);
        } else {
            $url = filter_var("https://" . $address, FILTER_VALIDATE_URL);
        }
        if ($url !== false) {
            return true;
        } else {
            return false;
        }
    }
    return false;
}

function get_vmess_ip($input)
{
    return !empty($input["sni"])
        ? $input["sni"]
        : (!empty($input["host"])
            ? $input["host"]
            : $input["add"]);
}

function get_vless_ip($input, $is_reality)
{
    return $is_reality
        ? $input["hostname"]
        : (!empty($input["params"]["sni"])
            ? $input["params"]["sni"]
            : (!empty($input["params"]["host"])
                ? $input["params"]["host"]
                : $input["hostname"]));
}

function get_trojan_ip($input)
{
    return !empty($input["params"]["sni"])
        ? $input["params"]["sni"]
        : (!empty($input["params"]["host"])
            ? $input["params"]["host"]
            : $input["hostname"]);
}

function get_tuic_ip($input)
{
    return $input["hostname"];
}

function get_hy2_ip($input)
{
    return $input["hostname"];
}

function get_ss_ip($input)
{
    return $input["server_address"];
}

function get_port($input, $type)
{
    switch ($type) {
        case "vmess":
            return $input["port"];
        case "vless":
        case "trojan":
            return $input["port"];
        case "tuic":
            return $input["port"];
        case "hy2":
            return $input["port"];
        case "ss":
            return $input["server_port"];
    }
}

function get_flag($ip)
{
    $flag = "";
    $ip_info = ip_info($ip);
    if ($ip_info->country != "XX") {
        $location = $ip_info->country;
        $flag = $location . getFlags($location);
    } else {
        $flag = "RELAY🚩";
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

function generate_name($channel, $flag, $is_reality, $number, $type)
{
    $name = "";
    if ($channel === "iP_CF") {
        $channel = "FAKEOFTVC";
    }
    switch ($is_reality) {
        case true:
            return
                "رایگان | REALITY | " .
                "@" .
                $channel .
                " | " .
                $flag .
                " | " .
                numberToEmoji($number);
        case false:
            return
                "رایگان | " .
                $type .
                " | @" .
                $channel .
                " | " .
                $flag .
                " | " .
                numberToEmoji($number);
    }
}

function parse_config($input, $type, $is_sub = false)
{
    switch ($type) {
        case "vmess":
            return $is_sub
                ? decode_vmess($input)
                : decode_vmess($type . "://" . $input);
        case "vless":
        case "trojan":
            return $is_sub
                ? parseProxyUrl($input, $type)
                : parseProxyUrl($type . "://" . $input, $type);
        case "tuic":
            return $is_sub
                ? parseTuic($input)
                : parseTuic($type . "://" . $input);
        case "hy2":
            return $is_sub
                ? parseHy2($input)
                : parseHy2($type . "://" . $input);
        case "ss":
            return $is_sub
                ? ParseShadowsocks($input)
                : ParseShadowsocks($type . "://" . $input);
    }
}

function build_config($input, $type)
{
    switch ($type) {
        case "vmess":
            return encode_vmess($input);
        case "vless":
        case "trojan":
            return buildProxyUrl($input, $type);
        case "tuic":
            return buildTuic($input);
        case "hy2":
            return buildHy2($input);
        case "ss":
            return BuildShadowsocks($input);
    }
}

function get_config($channel, $type)
{
    $name_array = [
        "vmess" => "ps",
        "vless" => "hash",
        "trojan" => "hash",
        "ss" => "name",
        "tuic" => "hash",
        "hy2" => "hash"
    ];
    // Fetch the content of the Telegram channel URL
    $get = file_get_contents("https://t.me/s/" . $channel);

    // Load channels_assets JSON data
    $channels_assets = get_channels_assets();

    $matches = get_config_time($type, $get);
    $configs = get_config_items($type, $get);

    $final_data = [];
    if ($channel === "V2rayCollectorDonate") {
        $key_limit = count($configs[1]) - 20;
    } else {
        $key_limit = count($configs[1]) - 7;
    }
    $config_number = 1;

    foreach (array_reverse($configs[1]) as $key => $config) {
        if ($key >= $key_limit) {
            if (is_valid($config)) {
                if (strpos($config, "<br/>") !== false) {
                    $config = substr($config, 0, strpos($config, "<br/>"));
                }

                $is_reality = is_reality($config, $type);

                $the_config = parse_config($config, $type);
                $check_pbk = $is_reality ? check_pbk($config) : true;

                $address = get_address($the_config, $type);
                if ($check_pbk) {
                    if (is_valid_address($address)) {
                        $ip = get_ip($the_config, $type, $is_reality);
                        $port = get_port($the_config, $type);

                        @$ping_data = ping($ip, $port);
                        if ($ping_data !== "unavailable" || $type === "tuic") {
                            $flag = get_flag($ip);

                            $name_key = $name_array[$type];
                            $the_config[$name_key] = generate_name(
                                $channel,
                                $flag,
                                $is_reality,
                                $config_number,
                                strtoupper($type)
                            );

                            $final_config = build_config($the_config, $type);

                            $final_data[$key]["channel"]["username"] = $channel === "iP_CF" ? "FAKEOFTVC" : $channel;
                            $final_data[$key]["channel"]["title"] =
                                $channels_assets[$channel]["title"];
                            $final_data[$key]["channel"]["logo"] =
                                $channels_assets[$channel]["logo"];
                            $final_data[$key]["type"] = $is_reality
                                ? "reality"
                                : $type;
                            $final_data[$key]["config"] = $final_config;
                            $final_data[$key]["ping"] = $ping_data;
                            $final_data[$key]["time"] = convert_to_iran_time(
                                $matches[1][$key]
                            );
                            $config_number++;
                        }
                    }
                }
            }
        }
    }
    // Return the final data array
    return $final_data;
}

function detect_type($input)
{
    if (substr($input, 0, 8) === "vmess://") {
        return "vmess";
    } elseif (substr($input, 0, 8) === "vless://") {
        return "vless";
    } elseif (substr($input, 0, 9) === "trojan://") {
        return "trojan";
    } elseif (substr($input, 0, 5) === "ss://") {
        return "ss";
    } elseif (substr($input, 0, 7) === "tuic://") {
        return "tuic";
    } elseif (substr($input, 0, 6) === "hy2://") {
        return "hy2";
    } 
    

}

function process_subscription($input, $channel)
{
    $name_array = [
        "vmess" => "ps",
        "vless" => "hash",
        "trojan" => "hash",
        "ss" => "name",
        "tuic" => "hash",
        "hy2" => "hash",
    ];

    $final_data = [];
    $configs = explode("\n", $input);
    $array_helper_vmess = 0;
    $array_helper_vless = 0;
    $array_helper_ss = 0;
    $array_helper_trojan = 0;
    $array_helper_tuic = 0;
    $array_helper_hy2 = 0;
    $config_number = 1;
    $i = 0;
    $channel = $channel . " | Donated";
    foreach ($configs as $config) {
        $type = detect_type($config);
        $is_reality = is_reality($config, $type);

        $the_config = parse_config($config, $type, true);
        $check_pbk = $is_reality ? check_pbk($config) : true;

        $address = get_address($the_config, $type);
        if ($check_pbk) {
            if (is_valid_address($address)) {
                $ip = get_ip($the_config, $type, $is_reality);
                $port = get_port($the_config, $type);

                @$ping_data = ping($ip, $port);
                if ($ping_data !== "unavailable" || $type === "tuic") {
                    $flag = get_flag($ip);

                    $name_key = $name_array[$type];
                    $the_config[$name_key] = generate_name(
                        $channel,
                        $flag,
                        $is_reality,
                        $config_number,
                        strtoupper($type)
                    );
                    $final_config = build_config($the_config, $type);

                    $key = ${"array_helper_$type"};

                    $final_data[$type][$key]["channel"]["username"] = $channel;
                    $final_data[$type][$key]["channel"]["title"] = $channel;
                    $final_data[$type][$key]["channel"]["logo"] = "null";
                    $final_data[$type][$key]["type"] = $is_reality
                        ? "reality"
                        : $type;
                    $final_data[$type][$key]["config"] = $final_config;
                    $final_data[$type][$key]["ping"] = $ping_data;
                    $final_data[$type][$key]["time"] = tehran_time();

                    $key++;
                    ${"array_helper_$type"} = $key;
                    $config_number++;
                }
            }
        }
    }
    $i++;
    return $final_data;
}
