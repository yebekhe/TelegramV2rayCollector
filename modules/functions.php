<?php
include "flag.php";
include "ipinfo.php";

function decode_vmess($vmess_config)
{
    $vmess_data = substr($vmess_config, 8); // remove "vmess://"
    $decoded_data = json_decode(base64_decode($vmess_data), true);
    return $decoded_data;
}

function encode_vmess($config)
{
    $encoded_data = base64_encode(json_encode($config));
    $vmess_config = "vmess://" . $encoded_data;
    return $vmess_config;
}

function ParseShadowsocks($config_str)
{
    // Parse the config string as a URL
    $url = parse_url($config_str);

    // Extract the encryption method and password from the user info
    list($encryption_method, $password) = explode(
        ":",
        base64_decode($url["user"])
    );

    // Extract the server address and port from the host and path
    $server_address = $url["host"];
    $server_port = $url["port"];

    // Extract the name from the fragment (if present)
    $name = isset($url["fragment"]) ? urldecode($url["fragment"]) : null;

    // Create an array to hold the server configuration
    $server = [
        "encryption_method" => $encryption_method,
        "password" => $password,
        "server_address" => $server_address,
        "server_port" => $server_port,
        "name" => $name,
    ];

    // Return the server configuration as a JSON string
    return $server;
}

function BuildShadowsocks($server)
{
    // Encode the encryption method and password as a Base64-encoded string
    $user = base64_encode(
        $server["encryption_method"] . ":" . $server["password"]
    );

    // Construct the URL from the server address, port, and user info
    $url = "ss://$user@{$server["server_address"]}:{$server["server_port"]}";

    // If the name is present, add it as a fragment to the URL
    if (!empty($server["name"])) {
        $url .= "#" . urlencode($server["name"]);
    }

    // Return the URL as a string
    return $url;
}


function get_v2ray($channel, $type, $output_format = "text")
{
    if (!is_null($channel)) {
        $get = file_get_contents("https://t.me/s/" . $channel);
        if (!is_null($type)) {
            if ($type === "vmess") {
                $patern = "#<code>vmess://(.*?)<#";
                preg_match_all($patern, $get, $match);
                for ($p = count($match[1]) - 1; $p >= 0; $p--) {
                    $match[1][$p] = "vmess://" . $match[1][$p];
                    if (strpos($match[1][$p], "<br/>") !== false) {
                        $match[1][$p] = substr(
                            $match[1][$p],
                            0,
                            strpos($match[1][$p], "<br/>")
                        );
                    }
                    $config = decode_vmess($match[1][$p]);
                    $ip = !empty($config['sni']) ? $config['sni'] : (!empty($config['host']) ? $config['host'] : $config['add']);
                    $ip_info = ip_info($ip);
                    $location = $ip_info['countryCode'];
                    $flag = getFlags($location);
                    $config["ps"] = $flag . "|" . $channel;
                    $final_config = encode_vmess($config);
                    $matchinv[] = $final_config;
                }
                $sstparray = ["vmess" => $matchinv];
            } elseif ($type === "vless") {
                $patern2 = "#<code>vless://(.*?)<#";
                preg_match_all($patern2, $get, $matchv);
                for ($v = count($matchv[1]) - 1; $v >= 0; $v--) {
                    $config = parseProxyUrl("vless://" . $matchv[1][$v], "vless");
                    $ip = !empty($config['params']['sni']) ? $config['params']['sni'] : (!empty($config['params']['host']) ? $config['params']['host'] : $config['hostname']);
                    $ip_info = ip_info($ip);
                    $location = $ip_info['countryCode'];
                    $flag = getFlags($location);
                    $config["hash"] = $flag . "|" . $channel;
                    $final_config = buildProxyUrl($config, "vless");
                    $matchinv2[] = urldecode($final_config);
                }
                $sstparray = ["vless" => $matchinv2];
            } elseif ($type === "trojan") {
                $patern3 = "#<code>trojan://(.*?)<#";
                preg_match_all($patern3, $get, $matcht);
                for ($v = count($matcht[1]) - 1; $v >= 0; $v--) {
                    $config = parseProxyUrl("trojan://" . $matcht[1][$v]);
                    $ip = !empty($config['params']['sni']) ? $config['params']['sni'] : (!empty($config['params']['host']) ? $config['params']['host'] : $config['hostname']);
                    $ip_info = ip_info($ip);
                    $location = $ip_info['countryCode'];
                    $flag = getFlags($location);
                    $config["hash"] = $flag . "|" . $channel;
                    $final_config = buildProxyUrl($config);
                    $matchinv3[] = urldecode($final_config);
                }
                $sstparray = ["trojan" => $matchinv3];
            } elseif ($type === "ss") {
                $patern4 = "#[^vmle]ss://(.*?)<#";
                preg_match_all($patern4, $get, $matchs);
                for ($v = count($matchs[1]) - 1; $v >= 0; $v--) {
                    $config = ParseShadowsocks("ss://" . $matchs[1][$v]);
                    $ip = $config['server_address'];
                    $ip_info = ip_info($ip);
                    $location = $ip_info['countryCode'];
                    $flag = getFlags($location);
                    $config["name"] = $flag . "|" . $channel;
                    $final_config = BuildShadowsocks($config);
                    $matchinv4[] = urldecode($final_config);
                }
                $sstparray = ["ss" => $matchinv4];
            }

            if (isset($output_format) and $output_format === "text") {
                $output = "";
                foreach ($sstparray[$type] as $config) {
                    $output .= $output == "" ? $config : "\n" . $config;
                }
                return $output;
            } else {
                return json_encode($sstparray, 128);
            }

        } else {
            return "Error : Proxy type is not defined!";
        }
    } else {
        return "Error : Telegram Channel is not defined!";
    }
}

?>
