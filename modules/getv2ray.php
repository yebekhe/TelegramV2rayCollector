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
                $patern_vmess = "#vmess://(.*?)<#";
                preg_match_all($patern_vmess, $get, $match_vmess);
                for ($p = count($match_vmess[1]) - 1; $p >= 0; $p--) {
                    $match_vmess[1][$p] = "vmess://" . $match_vmess[1][$p];
                    if (strpos($match_vmess[1][$p], "<br/>") !== false) {
                        $match_vmess[1][$p] = substr(
                            $match_vmess[1][$p],
                            0,
                            strpos($match_vmess[1][$p], "<br/>")
                        );
                    }
                    $config = decode_vmess($match_vmess[1][$p]);
                    $ip = !empty($config["sni"]) ? $config["sni"] : (!empty($config["host"]) ? $config["host"] : $config["add"]);
                    $ip_info = ip_info($ip);
                    if isset($ip_info["country"]){
                        $location = $ip_info["country"];
                        $flag = getFlags($location);
                    }
                    else{
                        $flag = "🚩";
                    }
                    $config["ps"] = $flag . "|" . $channel . "|" . $p;
                    if ( count($config) !== 1 ) {
                        $final_config = encode_vmess($config);
                        $match_inverted[] = $final_config;
                    } 
                }
                $v2ray_array = ["vmess" => $match_inverted];
            } elseif ($type === "vless") {
                $patern_vless = "#vless://(.*?)<#";
                preg_match_all($patern_vless, $get, $match_vless);
                for ($v = count($match_vless[1]) - 1; $v >= 0; $v--) {
                    if (strpos($match_vless[1][$v], "<br/>") !== false) {
                        $match_vless[1][$v] = substr(
                            $match_vless[1][$v],
                            0,
                            strpos($match_vless[1][$v], "<br/>")
                        );
                    }
                    $config = parseProxyUrl(
                        "vless://" . $match_vless[1][$v],
                        "vless"
                    );
                    $ip = !empty($config["params"]["sni"]) ? $config["params"]["sni"] : (!empty($config["params"]["host"]) ? $config["params"]["host"] : $config["hostname"]);
                    $ip_info = ip_info($ip);
                    if isset($ip_info["country"]){
                        $location = $ip_info["country"];
                        $flag = getFlags($location);
                    }
                    else{
                        $flag = "🚩";
                    }
                    if ( stripos($match_vless[1][$v], "reality") !== false) {
                        $config["hash"] = "REALITY|" . $flag . "|" . $channel . "|" . $v;
                    } 
                    else {
                        $config["hash"] = $flag . "|" . $channel . "|" . $v;
                    }
                    $final_config = buildProxyUrl($config, "vless");
                    $match_inverted[] = urldecode($final_config);
                }
                $v2ray_array = ["vless" => $match_inverted];
            } elseif ($type === "trojan") {
                $patern_trojan = "#trojan://(.*?)<#";
                preg_match_all($patern_trojan, $get, $match_trojan);
                for ($v = count($match_trojan[1]) - 1; $v >= 0; $v--) {
                    if (strpos($match_trojan[1][$v], "<br/>") !== false) {
                        $match_trojan[1][$v] = substr(
                            $match_trojan[1][$v],
                            0,
                            strpos($match_trojan[1][$v], "<br/>")
                        );
                    }
                    $config = parseProxyUrl("trojan://" . $match_trojan[1][$v]);
                    $ip = !empty($config["params"]["sni"]) ? $config["params"]["sni"] : (!empty($config["params"]["host"]) ? $config["params"]["host"] : $config["hostname"]);
                    $ip_info = ip_info($ip);
                    if isset($ip_info["country"]){
                        $location = $ip_info["country"];
                        $flag = getFlags($location);
                    }
                    else{
                        $flag = "🚩";
                    }
                    $config["hash"] = $flag . "|" . $channel . "|" . $v;
                    $final_config = buildProxyUrl($config);
                    $match_inverted[] = urldecode($final_config);
                }
                $v2ray_array = ["trojan" => $match_inverted];
            } elseif ($type === "ss") {
                $patern_ss = "#[^vmle]ss://(.*?)<#";
                preg_match_all($patern_ss, $get, $match_ss);
                for ($v = count($match_ss[1]) - 1; $v >= 0; $v--) {
                    if (strpos($match_ss[1][$v], "<br/>") !== false) {
                        $match_ss[1][$v] = substr(
                            $match_ss[1][$v],
                            0,
                            strpos($match_ss[1][$v], "<br/>")
                        );
                    }
                    $config = ParseShadowsocks("ss://" . $match_ss[1][$v]);
                    $ip = $config["server_address"];
                    $ip_info = ip_info($ip);
                    if isset($ip_info["country"]){
                        $location = $ip_info["country"];
                        $flag = getFlags($location);
                    }
                    else{
                        $flag = "🚩";
                    }
                    $config["name"] = $flag . "|" . $channel . "|" . $v;
                    $final_config = BuildShadowsocks($config);
                    $match_inverted[] = urldecode($final_config);
                }
                $v2ray_array = ["ss" => $match_inverted];
            }

            if (isset($output_format) and $output_format === "text") {
                $output = "";
                foreach ($v2ray_array[$type] as $config) {
                    $output .= $output == "" ? $config : "\n" . $config;
                }
                return $output;
            } else {
                return json_encode($v2ray_array, 128);
            }
        } else {
            return "Error : Proxy type is not defined!";
        }
    } else {
        return "Error : Telegram Channel is not defined!";
    }
}
?>
