<?php

include "flag.php";
include "ipinfo.php";
include "ping.php";
include "xray.php";
include "vmess.php";
include "shadowsocks.php";

function convert_to_iran_time($utc_timestamp)
{
    $utc_datetime = new DateTime($utc_timestamp);
    $utc_datetime->setTimezone(new DateTimeZone("Asia/Tehran"));
    return $utc_datetime->format("Y-m-d H:i:s");
}

function get_config($channel, $type)
{
    $get = file_get_contents("https://t.me/s/" . $channel);
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
    foreach ($configs[1] as $key => $config) {
        if (stripos($config, "...") !== false) {
            null;
        } else {
            if (strpos($config, "<br/>") !== false) {
                $config = substr($config, 0, $config, "<br/>");
            }
            if ($type === "vmess") {
                $the_config = decode_vmess($type . "://" . $config);
                $ip = !empty($the_config["sni"])
                    ? $the_config["sni"]
                    : (!empty($the_config["host"])
                        ? $the_config["host"]
                        : $the_config["add"]);
                $port = $the_config["port"];
                if (ping($ip, $port) !== "unavailable") {
                    $ip_info = ip_info($ip);
                    if (isset($ip_info["country"])) {
                        $location = $ip_info["country"];
                        $flag = getFlags($location);
                    } else {
                        $flag = "🚩";
                    }
                    $the_config["ps"] = $flag . "|" . $channel . "|" . ping($ip, $port);
                    if (count($the_config) !== 1) {
                        $final_config = encode_vmess($the_config);
                        $final_data[$key]["channel"] = $channel;
                        $final_data[$key]["config"] = $final_config;
                        $final_data[$key]["time"] = convert_to_iran_time(
                            $matches[1][$key]
                        );
                    }
                }
            } elseif ($type === "vless") {
                $the_config = parseProxyUrl($type . "://" . $config, "vless");
                $ip = !empty($the_config["params"]["sni"])
                    ? $the_config["params"]["sni"]
                    : (!empty($the_config["params"]["host"])
                        ? $the_config["params"]["host"]
                        : $the_config["hostname"]);
                $port = $the_config["port"];
                if (ping($ip, $port) !== "unavailable") {
                    $ip_info = ip_info($ip);
                    if (isset($ip_info["country"])) {
                        $location = $ip_info["country"];
                        $flag = getFlags($location);
                    } else {
                        $flag = "🚩";
                    }
                    if (stripos($config, "reality") !== false) {
                        $the_config["hash"] =
                            "REALITY|" . $flag . "|" . $channel . "|" . ping($ip, $port);
                    } else {
                        $the_config["hash"] = $flag . "|" . $channel . "|" . ping($ip, $port);
                    }
                    $final_config = buildProxyUrl($the_config, "vless");
                    $final_data[$key]["channel"] = $channel;
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
                if (ping($ip, $port) !== "unavailable") {
                    $ip_info = ip_info($ip);
                    if (isset($ip_info["country"])) {
                        $location = $ip_info["country"];
                        $flag = getFlags($location);
                    } else {
                        $flag = "🚩";
                    }
                    $the_config["hash"] = $flag . "|" . $channel . "|" . ping($ip, $port);
                    $final_config = buildProxyUrl($the_config);
                    $final_data[$key]["channel"] = $channel;
                    $final_data[$key]["config"] = urldecode($final_config);
                    $final_data[$key]["time"] = convert_to_iran_time(
                        $matches[1][$key]
                    );
                }
            } elseif ($type === "ss") {
                $the_config = ParseShadowsocks($type . "://" . $config);
                $ip = $the_config["server_address"];
                $port = $the_config["server_port"];
                if (ping($ip, $port) !== "unavailable") {
                    $ip_info = ip_info($ip);
                    if (isset($ip_info["country"])) {
                        $location = $ip_info["country"];
                        $flag = getFlags($location);
                    } else {
                        $flag = "🚩";
                    }
                    $the_config["name"] = $flag . "|" . $channel . "|" . ping($ip, $port);
                    $final_config = BuildShadowsocks($the_config);
                    $final_data[$key]["channel"] = $channel;
                    $final_data[$key]["config"] = urldecode($final_config);
                    $final_data[$key]["time"] = convert_to_iran_time(
                        $matches[1][$key]
                    );
                }
            }
        }
    }
    return $final_data;
}

?>
