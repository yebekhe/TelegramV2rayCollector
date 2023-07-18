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
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36",
            CURLOPT_FOLLOWLOCATION => true,
        ));
        return curl_exec($ch);
    }

function convert_to_iran_time($utc_timestamp)
{
    $utc_datetime = new DateTime($utc_timestamp);
    $utc_datetime->setTimezone(new DateTimeZone("Asia/Tehran"));
    return $utc_datetime->format("Y-m-d H:i:s");
}

function get_config($channel, $type)
{
    // Fetch the content of the Telegram channel URL
    $get = file_get_contents("https://t.me/s/" . $channel);

    // Load channels_assets JSON data
    $channels_assets = json_decode(
        file_get_contents("modules/channels/channels_assets.json"),
        true
    );

    // Determine the pattern and perform matching based on the type
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

    // Extract configurations based on the pattern
    preg_match_all($patern_config, $get, $configs);
    $final_data = [];
    $key_limit = count($configs[1]) - 3;

    // Iterate through each configuration
    foreach ($configs[1] as $key => $config) {
        if ($key >= $key_limit) {
            // Check for ellipsis or invalid characters in the config
            if (
                stripos($config, "…") !== false or
                stripos($config, "...") !== false
            ) {
                null;
            } else {
                // Process the configuration based on the type
                if (strpos($config, "<br/>") !== false) {
                    $config = substr($config, 0, strpos($config, "<br/>"));
                }
                if ($type === "vmess") {
                    // Decode the vmess configuration
                    $the_config = decode_vmess($type . "://" . $config);

                    // Extract IP and port from the decoded configuration
                    $ip = !empty($the_config["sni"])
                        ? $the_config["sni"]
                        : (!empty($the_config["host"])
                            ? $the_config["host"]
                            : $the_config["add"]);
                    $port = $the_config["port"];

                    // Ping the IP and port to get the response time
                    @$ping_data = ping($ip, $port);

                    // If ping data is available
                    if ($ping_data !== "unavailable") {
                        // Get IP information (country) and flag
                        $ip_info = ip_info($ip);
                        if (isset($ip_info["country"])) {
                            $location = $ip_info["country"];
                            $flag = getFlags($location);
                        } else {
                            $flag = "🚩";
                        }

                        // Update the configuration with channel info, flag, and ping data
                        $the_config["ps"] =
                            "@" . $channel . "|" . $flag . "|" . $ping_data;

                        // Encode the updated configuration
                        $final_config = encode_vmess($the_config);

                        // Build the final data array with channel, type, config, and time
                        $final_data[$key]["channel"]["username"] = $channel;
                        $final_data[$key]["channel"]["title"] =
                            $channels_assets[$channel]["title"];
                        $final_data[$key]["channel"]["logo"] =
                            $channels_assets[$channel]["logo"];
                        $final_data[$key]["type"] = $type;
                        $final_data[$key]["config"] = $final_config;
                        $final_data[$key]["ping"] = $ping_data;
                        $final_data[$key]["time"] = convert_to_iran_time(
                            $matches[1][$key]
                        );
                    }
                } elseif ($type === "vless") {
                    // Parse the vless configuration
                    $the_config = parseProxyUrl(
                        $type . "://" . $config,
                        "vless"
                    );

                    // Extract IP and port from the parsed vless configuration
                    $ip = !empty($the_config["params"]["sni"])
                        ? $the_config["params"]["sni"]
                        : (!empty($the_config["params"]["host"])
                            ? $the_config["params"]["host"]
                            : $the_config["hostname"]);
                    if (stripos($config, "reality") !== false) {
                        $ip = $the_config["hostname"];
                    }
                    $port = $the_config["port"];

                    // Ping the IP and port to get the response time
                    @$ping_data = ping($ip, $port);

                    // If ping data is available
                    if ($ping_data !== "unavailable") {
                        // Get IP information (country) and flag
                        $ip_info = ip_info($ip);
                        if (isset($ip_info["country"])) {
                            $location = $ip_info["country"];
                            $flag = getFlags($location);
                        } else {
                            $flag = "🚩";
                        }

                        // Update the configuration with channel info, flag, and ping data
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
                                "@" .
                                $channel .
                                "|" .
                                $flag .
                                "|" .
                                ping($ip, $port);
                        }

                        // Build the final vless configuration
                        $final_config = buildProxyUrl($the_config, "vless");

                        // Build the final data array with channel, type, config, and time
                        $final_data[$key]["channel"]["username"] = $channel;
                        $final_data[$key]["channel"]["title"] =
                            $channels_assets[$channel]["title"];
                        $final_data[$key]["channel"]["logo"] =
                            $channels_assets[$channel]["logo"];
                        $final_data[$key]["type"] = $type;
                        $final_data[$key]["config"] = urldecode($final_config);
                        $final_data[$key]["ping"] = $ping_data;
                        $final_data[$key]["time"] = convert_to_iran_time(
                            $matches[1][$key]
                        );
                    }
                } elseif ($type === "trojan") {
                    // Parse the trojan configuration
                    $the_config = parseProxyUrl($type . "://" . $config);

                    // Extract IP and port from the parsed trojan configuration
                    $ip = !empty($the_config["params"]["sni"])
                        ? $the_config["params"]["sni"]
                        : (!empty($the_config["params"]["host"])
                            ? $the_config["params"]["host"]
                            : $the_config["hostname"]);
                    $port = $the_config["port"];

                    // Ping the IP and port to get the response time
                    @$ping_data = ping($ip, $port);

                    // If ping data is available
                    if ($ping_data !== "unavailable") {
                        // Get IP information (country) and flag
                        $ip_info = ip_info($ip);
                        if (isset($ip_info["country"])) {
                            $location = $ip_info["country"];
                            $flag = getFlags($location);
                        } else {
                            $flag = "🚩";
                        }

                        // Update the configuration with channel info, flag, and ping data
                        $the_config["hash"] =
                            "@" . $channel . "|" . $flag . "|" . $ping_data;

                        // Build the final trojan configuration
                        $final_config = buildProxyUrl($the_config);

                        // Build the final data array with channel, type, config, and time
                        $final_data[$key]["channel"]["username"] = $channel;
                        $final_data[$key]["channel"]["title"] =
                            $channels_assets[$channel]["title"];
                        $final_data[$key]["channel"]["logo"] =
                            $channels_assets[$channel]["logo"];
                        $final_data[$key]["type"] = $type;
                        $final_data[$key]["config"] = urldecode($final_config);
                        $final_data[$key]["ping"] = $ping_data;
                        $final_data[$key]["time"] = convert_to_iran_time(
                            $matches[1][$key]
                        );
                    }
                } elseif ($type === "ss") {
                    // Parse the shadowsocks configuration
                    $the_config = ParseShadowsocks($type . "://" . $config);

                    // Extract IP and port from the parsed shadowsocks configuration
                    $ip = $the_config["server_address"];
                    $port = $the_config["server_port"];

                    // Ping the IP and port to get the response time
                    @$ping_data = ping($ip, $port);
                    // If ping data is available
                    if ($ping_data !== "unavailable") {
                        // Get IP information (country) and flag
                        $ip_info = ip_info($ip);
                        if (isset($ip_info["country"])) {
                            $location = $ip_info["country"];
                            $flag = getFlags($location);
                        } else {
                            $flag = "🚩";
                        }

                        // Update the configuration with channel info, flag, and ping data
                        $the_config["remarks"] =
                            "@" . $channel . "|" . $flag . "|" . $ping_data;

                        // Build the final shadowsocks configuration
                        $final_config = BuildShadowsocks($the_config);

                        // Build the final data array with channel, type, config, and time
                        $final_data[$key]["channel"]["username"] = $channel;
                        $final_data[$key]["channel"]["title"] =
                            $channels_assets[$channel]["title"];
                        $final_data[$key]["channel"]["logo"] =
                            $channels_assets[$channel]["logo"];
                        $final_data[$key]["type"] = $type;
                        $final_data[$key]["config"] = urldecode($final_config);
                        $final_data[$key]["ping"] = $ping_data;
                        $final_data[$key]["time"] = convert_to_iran_time(
                            $matches[1][$key]
                        );
                    }
                }
            }
        }
    }

    // Return the final data array
    return $final_data;
}

function process_subscription($input, $channel)
{
    $final_data = [];
    $configs = explode("\n", $input);
    $array_helper_vmess = 0;
    $array_helper_vless = 0;
    $array_helper_ss = 0;
    $array_helper_trojan = 0;
    foreach ($configs as $config) {
        if (substr($config, 0, 8) === "vmess://") {
            // Decode the vmess configuration
            $the_config = decode_vmess($config);

            // Extract IP and port from the decoded configuration
            $ip = !empty($the_config["sni"])
                ? $the_config["sni"]
                : (!empty($the_config["host"])
                    ? $the_config["host"]
                    : $the_config["add"]);
            $port = $the_config["port"];

            // Ping the IP and port to get the response time
            @$ping_data = ping($ip, $port);

            // If ping data is available
         //   if ($ping_data !== "unavailable") {
                // Get IP information (country) and flag
                $ip_info = ip_info($ip);
                if (isset($ip_info["country"])) {
                    $location = $ip_info["country"];
                    $flag = getFlags($location);
                } else {
                    $flag = "🚩";
                }

                // Update the configuration with channel info, flag, and ping data
                $the_config["ps"] =
                    "@" . $channel . "|" . $flag . "|" . $ping_data;

                // Encode the updated configuration
                $final_config = encode_vmess($the_config);

                // Build the final data array with channel, type, config, and time
                $final_data["vmess"][$array_helper_vmess]["channel"][
                    "username"
                ] = $channel;
                $final_data["vmess"][$array_helper_vmess]["channel"][
                    "title"
                ] = $channel;
                $final_data["vmess"][$array_helper_vmess]["channel"]["logo"] =
                    "null";
                $final_data["vmess"][$array_helper_vmess]["type"] = "vmess";
                $final_data["vmess"][$array_helper_vmess][
                    "config"
                ] = $final_config;
                $final_data["vmess"][$array_helper_vmess]["ping"] = $ping_data;
                $final_data["vmess"][$array_helper_vmess][
                    "time"
                ] = tehran_time();
                $array_helper_vmess++;
        //    }
        } elseif (substr($config, 0, 8) === "vless://") {
            // Parse the vless configuration
            $the_config = parseProxyUrl($config, "vless");

            // Extract IP and port from the parsed vless configuration
            $ip = !empty($the_config["params"]["sni"])
                ? $the_config["params"]["sni"]
                : (!empty($the_config["params"]["host"])
                    ? $the_config["params"]["host"]
                    : $the_config["hostname"]);
            if (stripos($config, "reality") !== false) {
                $ip = $the_config["hostname"];
            }
            $port = $the_config["port"];

            // Ping the IP and port to get the response time
            @$ping_data = ping($ip, $port);

            // If ping data is available
            //if ($ping_data !== "unavailable") {
                // Get IP information (country) and flag
                $ip_info = ip_info($ip);
                if (isset($ip_info["country"])) {
                    $location = $ip_info["country"];
                    $flag = getFlags($location);
                } else {
                    $flag = "🚩";
                }

                // Update the configuration with channel info, flag, and ping data
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
                    $type = "vless";
                }

                // Build the final vless configuration
                $final_config = buildProxyUrl($the_config, "vless");

                // Build the final data array with channel, type, config, and time
                $final_data["vless"][$array_helper_vless]["channel"][
                    "username"
                ] = $channel;
                $final_data["vless"][$array_helper_vless]["channel"][
                    "title"
                ] = $channel;
                $final_data["vless"][$array_helper_vless]["channel"]["logo"] =
                    "null";
                $final_data["vless"][$array_helper_vless]["type"] = $type;
                $final_data["vless"][$array_helper_vless]["config"] = urldecode(
                    $final_config
                );
                $final_data["vless"][$array_helper_vless]["ping"] = $ping_data;
                $final_data["vless"][$array_helper_vless][
                    "time"
                ] = tehran_time();
                $array_helper_vless++;
           // }
        } elseif (substr($config, 0, 5) === "ss://") {
            // Parse the shadowsocks configuration
            $the_config = ParseShadowsocks($config);

            // Extract IP and port from the parsed shadowsocks configuration
            $ip = $the_config["server_address"];
            $port = $the_config["server_port"];

            // Ping the IP and port to get the response time
            @$ping_data = ping($ip, $port);
            // If ping data is available
        //    if ($ping_data !== "unavailable") {
                // Get IP information (country) and flag
                $ip_info = ip_info($ip);
                if (isset($ip_info["country"])) {
                    $location = $ip_info["country"];
                    $flag = getFlags($location);
                } else {
                    $flag = "🚩";
                }

                // Update the configuration with channel info, flag, and ping data
                $the_config["remarks"] =
                    "@" . $channel . "|" . $flag . "|" . $ping_data;

                // Build the final shadowsocks configuration
                $final_config = BuildShadowsocks($the_config);

                // Build the final data array with channel, type, config, and time
                $final_data["ss"][$array_helper_ss]["channel"][
                    "username"
                ] = $channel;
                $final_data["ss"][$array_helper_ss]["channel"][
                    "title"
                ] = $channel;
                $final_data["ss"][$array_helper_ss]["channel"]["logo"] = "null";
                $final_data["ss"][$array_helper_ss]["type"] = "ss";
                $final_data["ss"][$array_helper_ss]["config"] = urldecode(
                    $final_config
                );
                $final_data["ss"][$array_helper_ss]["ping"] = $ping_data;
                $final_data["ss"][$array_helper_ss]["time"] = tehran_time();
                $array_helper_ss++;
          //  }
        } elseif (stripos($config, "trojan://")) {
            // Parse the trojan configuration
            $the_config = parseProxyUrl($config);

            // Extract IP and port from the parsed trojan configuration
            $ip = !empty($the_config["params"]["sni"])
                ? $the_config["params"]["sni"]
                : (!empty($the_config["params"]["host"])
                    ? $the_config["params"]["host"]
                    : $the_config["hostname"]);
            $port = $the_config["port"];

            // Ping the IP and port to get the response time
            @$ping_data = ping($ip, $port);

            // If ping data is available
          //  if ($ping_data !== "unavailable") {
                // Get IP information (country) and flag
                $ip_info = ip_info($ip);
                if (isset($ip_info["country"])) {
                    $location = $ip_info["country"];
                    $flag = getFlags($location);
                } else {
                    $flag = "🚩";
                }

                // Update the configuration with channel info, flag, and ping data
                $the_config["hash"] =
                    "@" . $channel . "|" . $flag . "|" . $ping_data;

                // Build the final trojan configuration
                $final_config = buildProxyUrl($the_config);

                // Build the final data array with channel, type, config, and time
                $final_data["trojan"][$array_helper_trojan][
                    "username"
                ] = $channel;
                $final_data["trojan"][$array_helper_trojan]["channel"][
                    "title"
                ] = $channel;
                $final_data["trojan"][$array_helper_trojan]["channel"]["logo"] =
                    "null";
                $final_data["trojan"][$array_helper_trojan]["type"] = "trojan";
                $final_data["trojan"][$array_helper_trojan][
                    "config"
                ] = urldecode($final_config);
                $final_data["trojan"][$array_helper_trojan]["ping"] = $ping_data;
                $final_data["trojan"][$array_helper_trojan][
                    "time"
                ] = tehran_time();
                $array_helper_trojan++;
          //  }
        }
    }
    return $final_data; 
}
?>
