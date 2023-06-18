<?php
include "flag.php";
include "ipinfo.php";
include "xray.php";
include "vmess.php";
include "shadowsocks.php";

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
                    $location = $ip_info['country'];
                    $flag = getFlags($location);
                    $config["ps"] = $flag . "|" . $channel;
                    $final_config = encode_vmess($config);
                    $match_inverted[] = $final_config;
                }
                $v2ray_array = ["vmess" => $match_inverted];
                $v2ray_array_final = array_values(array_unique($v2ray_array));
            } elseif ($type === "vless") {
                $patern2 = "#<code>vless://(.*?)<#";
                preg_match_all($patern2, $get, $match);
                for ($v = count($match[1]) - 1; $v >= 0; $v--) {
                    $config = parseProxyUrl("vless://" . $match[1][$v], "vless");
                    $ip = !empty($config['params']['sni']) ? $config['params']['sni'] : (!empty($config['params']['host']) ? $config['params']['host'] : $config['hostname']);
                    $ip_info = ip_info($ip);
                    $location = $ip_info['country'];
                    $flag = getFlags($location);
                    $config["hash"] = $flag . "|" . $channel;
                    $final_config = buildProxyUrl($config, "vless");
                    $match_inverted[] = urldecode($final_config);
                }
                $v2ray_array = ["vless" => $match_inverted];
                $v2ray_array_final = array_values(array_unique($v2ray_array));
            } elseif ($type === "trojan") {
                $patern3 = "#<code>trojan://(.*?)<#";
                preg_match_all($patern3, $get, $match);
                for ($v = count($match[1]) - 1; $v >= 0; $v--) {
                    $config = parseProxyUrl("trojan://" . $match[1][$v]);
                    $ip = !empty($config['params']['sni']) ? $config['params']['sni'] : (!empty($config['params']['host']) ? $config['params']['host'] : $config['hostname']);
                    $ip_info = ip_info($ip);
                    $location = $ip_info['country'];
                    $flag = getFlags($location);
                    $config["hash"] = $flag . "|" . $channel;
                    $final_config = buildProxyUrl($config);
                    $match_inverted[] = urldecode($final_config);
                }
                $v2ray_array = ["trojan" => $match_inverted];
                $v2ray_array_final = array_values(array_unique($v2ray_array));
            } elseif ($type === "ss") {
                $patern4 = "#[^vmle]ss://(.*?)<#";
                preg_match_all($patern4, $get, $match);
                for ($v = count($match[1]) - 1; $v >= 0; $v--) {
                    $config = ParseShadowsocks("ss://" . $match[1][$v]);
                    $ip = $config['server_address'];
                    $ip_info = ip_info($ip);
                    $location = $ip_info['country'];
                    $flag = getFlags($location);
                    $config["name"] = $flag . "|" . $channel;
                    $final_config = BuildShadowsocks($config);
                    $match_inverted[] = urldecode($final_config);
                }
                $v2ray_array = ["ss" => $match_inverted];
                $v2ray_array_final = array_values(array_unique($v2ray_array));
            }

            if (isset($output_format) and $output_format === "text") {
                $output = "";
                foreach ($v2ray_array_final[$type] as $config) {
                    $output .= $output == "" ? $config : "\n" . $config;
                }
                return $output;
            } else {
                return json_encode($v2ray_array_final, 128);
            }

        } else {
            return "Error : Proxy type is not defined!";
        }
    } else {
        return "Error : Telegram Channel is not defined!";
    }
}

?>
