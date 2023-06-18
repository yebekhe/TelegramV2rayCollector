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
                    $location = $ip_info['country'];
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
                    $location = $ip_info['country'];
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
                    $location = $ip_info['country'];
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
