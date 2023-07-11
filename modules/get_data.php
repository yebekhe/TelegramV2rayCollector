<?php
include "flag.php";
include "ipinfo.php";
include "shadowsocks.php";
include "vmess.php";
include "xray.php";
include "ping.php";

function convert_to_iran_time($utc_timestamp)
{
    $utc_datetime = new DateTime($utc_timestamp);
    $utc_datetime->setTimezone(new DateTimeZone("Asia/Tehran"));
    return $utc_datetime->format("Y-m-d H:i:s");
}

function get_config($channel, $type)
{
    $get = file_get_contents("https://t.me/s/" . $channel);
    $channels_assets = json_decode(file_get_contents("modules/channels/channels_assets.json"), true);
    
    if ($type === "vmess") {
        preg_match_all(
            '/vmess:\/\/[^"]+(?:[^<]+<[^<]+)*<time datetime="([^"]+)"/',
            $get,
            $matches
        );
        $patern_config = "#vmess://(.*?)<#";
    } elseif ($type === "vless") {
        preg_match_all(
            '/vless:\/\/[^"]+(?:[^<]+<[^<]+)*<time datetime="([^"]+)"/',
            $get,
            $matches
        );
        $patern_config = "#vless://(.*?)<#";
    } elseif ($type === "trojan") {
        preg_match_all(
            '/trojan:\/\/[^"]+(?:[^<]+<[^<]+)*<time datetime="([^"]+)"/',
            $get,
            $matches
        );
        $patern_config = "#trojan://(.*?)<#";
    } elseif ($type === "ss") {
        preg_match_all(
            '/[^vmle]ss:\/\/[^"]+(?:[^<]+<[^<]+)*<time datetime="([^"]+)"/',
            $get,
            $matches
        );
        $patern_config = "#[^vmle]ss://(.*?)<#";
    }

    preg_match_all($patern_config, $get, $configs);
    $final_data = [];
    $key_limit = count($configs[1]) - 4;
    foreach ($configs[1] as $key => $config) {
        if ($key >= $key_limit) {
            if (stripos($config, "…") !== false or stripos($config, "...") !== false) {
                null;
            } else {
                if (strpos($config, "<br/>") !== false) {
                    $config = substr($config, 0, strpos($config, "<br/>"));
                }
                if ($type === "vmess") {
                    $the_config = decode_vmess($type . "://" . $config);
                    $ip = !empty($the_config["sni"])
                        ? $the_config["sni"]
                        : (!empty($the_config["host"])
                            ? $the_config["host"]
                            : $the_config["add"]);
                    $port = $the_config["port"];
                    @$ping_data = ping($ip, $port);
                    if ($ping_data !== "unavailable") {
                        $ip_info = ip_info($ip);
                        if (isset($ip_info["country"])) {
                            $location = $ip_info["country"];
                            $flag = getFlags($location);
                        } else {
                            $flag = "🚩";
                        }
                        $the_config["ps"] =
                            "@" . $channel . "|" . $flag . "|" . $ping_data;
                        if (count($the_config) !== 1) {
                            $final_config = encode_vmess($the_config);
                            $final_data[$key]["channel"]['username'] = $channel;
                            $final_data[$key]["channel"]['title'] = $channels_assets[$channel]['title'];
                            $final_data[$key]["channel"]['logo'] = $channels_assets[$channel]['logo'];
                            $final_data[$key]["type"] = $type;
                            $final_data[$key]["config"] = $final_config;
                            $final_data[$key]["time"] = convert_to_iran_time(
                                $matches[1][$key]
                            );
                        }
                    }
                } elseif ($type === "vless") {
                    $the_config = parseProxyUrl(
                        $type . "://" . $config,
                        "vless"
                    );
                    $ip = !empty($the_config["params"]["sni"])
                        ? $the_config["params"]["sni"]
                        : (!empty($the_config["params"]["host"])
                            ? $the_config["params"]["host"]
                            : $the_config["hostname"]);
                    $port = $the_config["port"];
                    @$ping_data = ping($ip, $port);
                    if ($ping_data !== "unavailable") {
                        $ip_info = ip_info($ip);
                        if (isset($ip_info["country"])) {
                            $location = $ip_info["country"];
                            $flag = getFlags($location);
                        } else {
                            $flag = "🚩";
                        }
                        if (stripos($config, "reality") !== false) {
                            $the_config["hash"] =
                                "REALITY|" .
                                "@" .
                                $channel .
                                "|" .
                                $flag .
                                "|" .
                                $ping_data;
                            $type = "reality";
                        } else {
                            $the_config["hash"] =
                                "@" . $channel . "|" . $flag . "|" . ping($ip, $port);
                        }
                        $final_config = buildProxyUrl($the_config, "vless");
                        $final_data[$key]["channel"]['username'] = $channel;
                        $final_data[$key]["channel"]['title'] = $channels_assets[$channel]['title'];
                        $final_data[$key]["channel"]['logo'] = $channels_assets[$channel]['logo'];
                        $final_data[$key]["type"] = $type;
                        $final_data[$key]["config"] = urldecode($final_config);
                        $final_data[$key]["time"] = convert_to_iran_time(
                            $matches[1][$key]
                        );
                    }
                } elseif ($type === "trojan") {
                    $the_config = parseProxyUrl($type . "://" . $config);
                    $ip = !empty($the_config["params"]["sni"])
                        ? $the_config["params"]["sni"]
                        : (!empty($the_config["params"]["host"])
                            ? $the_config["params"]["host"]
                            : $the_config["hostname"]);
                    $port = $the_config["port"];
                    @$ping_data = ping($ip, $port);
                    if ($ping_data !== "unavailable") {
                        $ip_info = ip_info($ip);
                        if (isset($ip_info["country"])) {
                            $location = $ip_info["country"];
                            $flag = getFlags($location);
                        } else {
                            $flag = "🚩";
                        }
                        $the_config["hash"] =
                            $flag . "|@" . $channel . "|" . $ping_data;
                        $final_config = buildProxyUrl($the_config);
                        $final_data[$key]["channel"]['username'] = $channel;
                        $final_data[$key]["channel"]['title'] = $channels_assets[$channel]['title'];
                        $final_data[$key]["channel"]['logo'] = $channels_assets[$channel]['logo'];
                        $final_data[$key]["type"] = $type;
                        $final_data[$key]["config"] = urldecode($final_config);
                        $final_data[$key]["time"] = convert_to_iran_time(
                            $matches[1][$key]
                        );
                    }
                } elseif ($type === "ss") {
                    $the_config = ParseShadowsocks($type . "://" . $config);
                    $ip = $the_config["server_address"];
                    $port = $the_config["server_port"];
                    @$ping_data = ping($ip, $port);
                    if ($ping_data !== "unavailable") {
                        $ip_info = ip_info($ip);
                        if (isset($ip_info["country"])) {
                            $location = $ip_info["country"];
                            $flag = getFlags($location);
                        } else {
                            $flag = "🚩";
                        }
                        $the_config["name"] =
                            "@" . $channel . "|" . $flag . "|" . $ping_data;
                        $final_config = BuildShadowsocks($the_config);
                        $final_data[$key]["channel"]['username'] = $channel;
                        $final_data[$key]["channel"]['title'] = $channels_assets[$channel]['title'];
                        $final_data[$key]["channel"]['logo'] = $channels_assets[$channel]['logo'];
                        $final_data[$key]["type"] = $type;
                        $final_data[$key]["config"] = urldecode($final_config);
                        $final_data[$key]["time"] = convert_to_iran_time(
                            $matches[1][$key]
                        );
                    }
                }
            }
        }
    }
    return $final_data;
}
?>
