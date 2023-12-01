<?php

header("Content-type: application/json;");

function create_tehran_timestamp_tomorrow() {
  date_default_timezone_set('Asia/Tehran');
  $dateTomorrow = new DateTime('tomorrow');
  $timestampTomorrow = strtotime($dateTomorrow->format('Y-m-d H:i:s'));
  return $timestampTomorrow;
}

function isEvenLength($str) {
    $length = strlen($str);
    return $length % 2 == 0;
}

function process_jsons($input, $locationNames){
    $input[0]['outbounds'] = array_merge($input[0]['outbounds'], array_filter($locationNames));
    return $input;
}

function extract_names($input){
    foreach($input as $config){
        if ($config['tag'] !== ""){
             $locationNames[] = $config['tag'];
        }
    }
    return $locationNames;
}

function processWsPath($input) {
  if (strpos($input, '/') === 0) {
    $input = substr($input, 1);
  }
  $max_early_data = 0;
  if (strpos($input, '?ed=2048') !== false) {
    $input = str_replace('?ed=2048', '', $input);
    $max_early_data = 2048;
  }
  $output = [
      "path" => "/" . $input,
      "max_early_data" => $max_early_data
  ];
  
  return $output;
}

function VmessSingbox($VmessUrl)
{
    $decode_vmess = decode_vmess($VmessUrl);
    if (is_null($decode_vmess["ps"]) || $decode_vmess["ps"] === "") {
        return null;
    }
    $configResult = [
        "tag" => $decode_vmess["ps"],
        "type" => "vmess",
        "server" => $decode_vmess["add"],
        "server_port" => intval($decode_vmess["port"]),
        "uuid" => $decode_vmess["id"],
        "security" => "auto",
        "alter_id" => intval($decode_vmess["aid"]),
        "global_padding" => false,
        "authenticated_length" => true,
        "packet_encoding" => "",
        "multiplex" => [
            "enabled" => false,
            "protocol" => "smux",
            "max_streams" => 32,
        ],
    ];

    if ($decode_vmess["port"] === "443" || $decode_vmess["tls"] === "tls") {
        $configResult["tls"] = [
            "enabled" => true,
            "server_name" =>
                $decode_vmess["sni"]
                ?? $decode_vmess["add"],
            "insecure" => true,
            "disable_sni" => false,
            "utls" => [
                "enabled" => true,
                "fingerprint" => "chrome",
            ],
        ];
    }
    
    if ($decode_vmess["net"] === "ws") {
        $pathProcess = processWsPath($decode_vmess["path"]);
        $configResult["transport"] = [
            "type" => $decode_vmess["net"],
            "path" => $pathProcess['path'],
            "headers" => [
                "Host" =>
                    $decode_vmess["host"]
                    ?? $decode_vmess["add"],
            ],
            "max_early_data" => $pathProcess['max_early_data'],
            "early_data_header_name" => "Sec-WebSocket-Protocol",
        ];
        if ($configResult["transport"]["headers"]["Host"] === "" || is_null($configResult["transport"]["headers"]["Host"])) return null;
    } elseif ($decode_vmess["net"] === "grpc") {
        $configResult["transport"] = [
            "type" => $decode_vmess["net"],
            "service_name" => $decode_vmess["path"] ?? "",
            "idle_timeout" => "15s",
            "ping_timeout" => "15s",
            "permit_without_stream" => false,
        ];
      if ($configResult["transport"]["service_name"] === "" || is_null($configResult["transport"]["service_name"])) return null;
    }

    return $configResult;
}

function VlessSingbox($VlessUrl)
{
    $decoded_vless = parseProxyUrl($VlessUrl, "vless");
    //print_r($decoded_vless);
    if (is_null($decoded_vless["hash"]) || $decoded_vless["hash"] === "") {
        return null;
    }
    $configResult = [
        "tag" => $decoded_vless["hash"],
        "type" => "vless",
        "server" => $decoded_vless["hostname"],
        "server_port" => intval($decoded_vless["port"]),
        "uuid" => $decoded_vless["username"],
        "flow" => !is_null($decoded_vless["params"]["flow"])
            ? "xtls-rprx-vision"
            : "",
        "packet_encoding" => "xudp",
        "multiplex" => [
            "enabled" => false,
            "protocol" => "smux",
            "max_streams" => 32,
        ],
    ];

    if (
        $decoded_vless["port"] === "443" ||
        $decoded_vless["params"]["security"] === "tls" ||
        $decoded_vless["params"]["security"] === "reality"
    ) {
        $configResult["tls"] = [
            "enabled" => true,
            "server_name" => $decoded_vless["params"]["sni"] ?? "",
            "insecure" => false,
            "utls" => [
                "enabled" => true,
                "fingerprint" => "chrome",
            ],
        ];

        if (
            $decoded_vless["params"]["security"] === "reality" ||
            isset($decoded_vless["params"]["pbk"])
        ) {
            $configResult["tls"]["utls"]["fingerprint"] = $decoded_vless["params"]["fp"];
            $configResult["tls"]["reality"] = [
                "enabled" => true,
                "public_key" => !is_null($decoded_vless["params"]["pbk"])
                    ? $decoded_vless["params"]["pbk"]
                    : "",
                "short_id" => !is_null($decoded_vless["params"]["sid"])
                    ? $decoded_vless["params"]["sid"]
                    : "",
            ];
        if (
            is_null($decoded_vless["params"]["pbk"]) or
            $decoded_vless["params"]["pbk"] === ""
        ) {
            return null;
        }
        }
    }
    $transportTypes = [
        "ws" => [
            "type" => $decoded_vless["params"]["type"],
            "path" => processWsPath($decoded_vless["params"]["path"])['path'],
            "headers" => [
                "Host" => $decoded_vless["params"]["host"] ?? $decoded_vless["hostname"] ?? "",
            ],
            "max_early_data" => processWsPath($decoded_vless["params"]["path"])['max_early_data'],
            "early_data_header_name" => "Sec-WebSocket-Protocol",
        ],
        "grpc" => [
            "type" => $decoded_vless["params"]["type"],
            "service_name" => $decoded_vless["params"]["serviceName"] ?? "",
            "idle_timeout" => "15s",
            "ping_timeout" => "15s",
            "permit_without_stream" => false,
        ],
    ];
    if (isset($decoded_vless["params"]["type"])) {
        if (
            $decoded_vless["params"]["type"] === "ws" ||
            $decoded_vless["params"]["type"] === "grpc"
        ) {
            $configResult["transport"] =
                $transportTypes[$decoded_vless["params"]["type"]];
        }
    }
    if ($decoded_vless["params"]["type"] === "ws" && ($configResult["transport"]["headers"]["Host"] === "" || is_null($configResult["transport"]["headers"]["Host"]))) return null;
    if ($decoded_vless["params"]["type"] === "grpc" && ($configResult["transport"]["service_name"] === "" || is_null($configResult["transport"]["service_name"]))) return null;
    return $configResult;
}

function TrojanSingbox($TrojanUrl)
{
    $decoded_trojan = parseProxyUrl($TrojanUrl);
    if (is_null($decoded_trojan["hash"]) || $decoded_trojan["hash"] === "") {
        return null;
    }
    $configResult = [
        "tag" => $decoded_trojan["hash"],
        "type" => "trojan",
        "server" => $decoded_trojan["hostname"],
        "server_port" => intval($decoded_trojan["port"]),
        "password" => $decoded_trojan["username"],
        "multiplex" => [
            "enabled" => false,
            "protocol" => "smux",
            "max_streams" => 32,
        ],
    ];

    if (
        $decoded_trojan["port"] === "443" ||
        $decoded_trojan["params"]["security"] === "tls"
    ) {
        $configResult["tls"] = [
            "enabled" => true,
            "server_name" => $decoded_trojan["params"]["sni"] ?? "",
            "insecure" => true,
            "utls" => [
                "enabled" => true,
                "fingerprint" => "chrome",
            ],
        ];
    }

    $transportTypes = [
        "ws" => [
            "type" => $decoded_trojan["params"]["type"],
            "path" => processWsPath($decoded_trojan["params"]["path"])["path"],
            "headers" => [
                "Host" => $decoded_trojan["params"]["host"] ?? $decoded_trojan["hostname"] ?? "",
            ],
        ],
        "grpc" => [
            "type" => $decoded_trojan["params"]["type"],
            "service_name" => $decoded_trojan["params"]["serviceName"] ?? "",
            "idle_timeout" => "15s",
            "ping_timeout" => "15s",
            "permit_without_stream" => false,
        ],
    ];
    if (isset($decoded_trojan["params"]["type"])) {
        if (
            $decoded_trojan["params"]["type"] === "ws" ||
            $decoded_trojan["params"]["type"] === "grpc"
        ) {
            $configResult["transport"] =
                $transportTypes[$decoded_trojan["params"]["type"]];
        }
    }
    if ($decoded_trojan["params"]["type"] === "ws" && ($configResult["transport"]["headers"]["Host"] === "" || is_null($configResult["transport"]["headers"]["Host"]))) return null;
    if ($decoded_trojan["params"]["type"] === "grpc" && ($configResult["transport"]["service_name"] === "" || is_null($configResult["transport"]["service_name"]))) return null;
    return $configResult;
}

function ShadowsocksSingbox($ShadowsocksUrl) {
    $decoded_shadowsocks = ParseShadowsocks($ShadowsocksUrl);
    if (is_null($decoded_shadowsocks['name']) || $decoded_shadowsocks['name'] === ""){
        return null;
    }
    if ($decoded_shadowsocks['encryption_method'] === "chacha20-poly1305") {
        return null;
    }
    $configResult = [
        'tag' => $decoded_shadowsocks['name'],
        'type' => "shadowsocks",
        'server' => $decoded_shadowsocks['server_address'],
        'server_port' => intval($decoded_shadowsocks['server_port']),
        'method' => $decoded_shadowsocks['encryption_method'],
        'password' => $decoded_shadowsocks['password'],
        'plugin' => "",
        'plugin_opts' => ""
    ];
    return $configResult;
}

function TuicSingbox($TuicUrl) {
    $decodedTuic = ParseTuic($TuicUrl);
    if (
        is_null($decodedTuic['hash']) ||
        $decodedTuic['hash'] === ""
    ) {
        return null;
    }

    $configResult = [
        "tag" => $decodedTuic["hash"],
        "type" => "tuic",
        "server" => $decodedTuic['hostname'],
        "server_port" => intval($decodedTuic['port']),
        "uuid" => $decodedTuic['username'],
        "password" => $decodedTuic['pass'],
        "congestion_control" => $decodedTuic['params']['congestion_control'],
        "udp_relay_mode" => $decodedTuic['params']['udp_relay_mode'],
        "zero_rtt_handshake" => false,
        "heartbeat" => "10s",
        "network" => "tcp",
    ];

    $configResult['tls'] = [
            "enabled" => true,
            "disable_sni" => isset($decodedTuic['params']['sni']) ? false : true,
            "server_name" => isset($decodedTuic['params']['sni']) ? $decodedTuic['params']['sni'] : "",
            "insecure" => isset($decodedTuic['params']['allow_insecure']) && intval($decodedTuic['params']['allow_insecure']) === 1 ? true : false,
            "alpn" => [
                "h3",
                "spdy/3.1"
            ],
        ];
    if (!isset($decodedTuic['params']['alpn']) || is_null($decodedTuic['params']['alpn']) || $decodedTuic['params']['alpn'] === "") {
      unset($configResult['tls']["alpn"]);
    }

    return $configResult;
}

function Hy2Singbox($Hy2Url) {
    $decodedHy2 = ParseTuic($Hy2Url);
    if (
        is_null($decodedHy2['hash']) ||
        $decodedHy2['hash'] === ""
    ) {
        return null;
    }

    $configResult = [
        "tag" => $decodedHy2["hash"],
        "type" => "hysteria2",
        "server" => $decodedHy2['hostname'],
        "server_port" => intval($decodedHy2['port']),
        "up_mbps" => 0,
        "down_mbps" => 0,
        "password" => $decodedHy2['username'],
        "network" => "tcp",
    ];


    $configResult['obfs'] = [
        "type" => $decodedHy2['params']['obfs'],
        "password" => $decodedHy2['params']['obfs-password'],
    ];
    $configResult['tls'] = [
            "enabled" => true,
            "disable_sni" => isset($decodedHy2['params']['sni']) ? false : true,
            "server_name" => isset($decodedHy2['params']['sni']) ? $decodedHy2['params']['sni'] : "",
            "insecure" => isset($decodedHy2['params']['insecure']) && intval($decodedHy2['params']['insecure']) === 1 ? true : false,
            "alpn" => [
                "h3"
            ],
    ];
    /*if (!isset($decodedHy2['params']['alpn']) || is_null($decodedHy2['params']['alpn']) || $decodedHy2['params']['alpn'] === "") {
      unset($configResult['tls']["alpn"]);
    }*/

    return $configResult;
}

function GenerateConfig($input, $output, $theType){
    $outbound = [];
    $v2ray_subscription = str_replace(" ", "%20", $input);

    $configArray = explode("\n", $v2ray_subscription);
    foreach ($configArray as $config) {
        $configType = detect_type($config);
        $config = str_replace("%20", " ", $config);
        switch($configType) {
            case "vmess":
                $configSingbox = VmessSingbox($config);
                break;
            case "vless":
                $configSingbox = VlessSingbox($config);
                break;
            case "trojan":
                $configSingbox = TrojanSingbox($config);
                break;
            case "tuic":
                $configSingbox = TuicSingbox($config);
                break;
            case "hy2":
                $configSingbox = Hy2Singbox($config);
                break;
            case "ss":
                $configSingbox = ShadowsocksSingbox($config);
                break;
        }
        if (!is_null($configSingbox)){
            $configName = $configSingbox['tag'];
            if (stripos($configName, "RELAY🚩")){
                $configLocation = "RELAY🚩";
            } else {
                $pattern = '/\b[A-Z]{2}\b[\x{1F1E6}-\x{1F1FF}]{2}/u';
                preg_match_all($pattern, $configName, $matches);
                $configLocation = $matches[0][0];
            }
            if (!in_array($configSingbox, $outbound[$configLocation])) {
                $outbound[$configLocation][] = $configSingbox;
            }
        }
    }
    $templateMap = [
        "nold" => "nekobox_1.1.7.json", 
        "nnew" => "nekobox_1.1.8.json", 
        "sfia" => "sfi.json"
    ];
    $templateBase = json_decode(
        file_get_contents("modules/singbox/" . $templateMap[$output]), 
        true
    );
    $templateManual = json_decode(
        file_get_contents("modules/singbox/manual.json"), 
        true
    );
    $templateUrltest = json_decode(
        file_get_contents("modules/singbox/url_test.json"), 
        true
    );
    
    $outboundUrltest =[];
    $outboundSingles = [];
    $locationNames = [];
    $outboundBasedOnLocationFull = [];
    foreach ($outbound as $location => $outboundEachLocation){
        $locationNames[] = $location;
        $eachLocationNames = extract_names($outboundEachLocation);
        $templateUrltest[0]['tag'] = $location;
        $outboundBasedOnLocation = process_jsons($templateUrltest, $eachLocationNames);
        $outboundBasedOnLocationFull = array_merge($outboundBasedOnLocationFull, $outboundBasedOnLocation);
        $outboundSingles = array_merge($outboundSingles, $outboundEachLocation);
    }

    $templateManual = process_jsons($templateManual, $locationNames);
    $templateUrltest[0]['tag'] = "URL-TEST | رایگان";
    $configNamesFull = extract_names($outboundSingles);
    $outboundUrltest = process_jsons($templateUrltest, $configNamesFull);
    $outboundUrltest = array_merge($outboundUrltest, $outboundBasedOnLocationFull);

    $templateBase['outbounds'] = array_merge($templateManual, $outboundUrltest, $outboundSingles,  $templateBase['outbounds']);
    $finalJson = json_encode($templateBase, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    $headerText = "//profile-title: base64:" . base64_encode("TVC | " . strtoupper($theType)) . "
//profile-update-interval: 1
//subscription-userinfo: upload=0; download=0; total=10737418240000000; expire=2546249531
//support-url: https://t.me/v2raycollector
//profile-web-page-url: https://github.com/yebekhe/TelegramV2rayCollector

";
    $createJsonc = $headerText . $finalJson ;
    return $createJsonc;
}

function GenerateConfigLite($input, $output, $theType){
    $outbound = [];
    $v2ray_subscription = $input;

    $configArray = explode("\n", $v2ray_subscription);
    foreach ($configArray as $config) {
        $configType = detect_type($config);
        switch($configType) {
            case "vmess":
                $configSingbox = VmessSingbox($config);
                break;
            case "vless":
                $configSingbox = VlessSingbox($config);
                break;
            case "trojan":
                $configSingbox = TrojanSingbox($config);
                break;
            case "tuic":
                $configSingbox = TuicSingbox($config);
                break;
            case "hy2":
                $configSingbox = Hy2Singbox($config);
                break;
            case "ss":
                $configSingbox = ShadowsocksSingbox($config);
                break;
        }
        if (!is_null($configSingbox)){
            if (!in_array($configSingbox, $outbound)) {
                $outbound[] = $configSingbox;
            }
        }
    }
    $templateMap = [
        "nold" => "nekobox_1.1.7.json", 
        "nnew" => "nekobox_1.1.8.json", 
        "sfia" => "sfi.json"
    ];
    $templateBase = json_decode(
        file_get_contents("modules/singbox/" . $templateMap[$output]), 
        true
    );
    $templateManual = json_decode(
        file_get_contents("modules/singbox/manual.json"), 
        true
    );
    $templateUrltest = json_decode(
        file_get_contents("modules/singbox/url_test.json"), 
        true
    );

    $names = extract_names($outbound);
    $outboundManual = process_jsons($templateManual, $names);
    $outboundUrltest = process_jsons($templateUrltest, $names);

    $templateBase['outbounds'] = array_merge($outboundManual, $outboundUrltest, $outbound,  $templateBase['outbounds']);
    $finalJson = json_encode($templateBase, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    $headerText = "//profile-title: base64:" . base64_encode("TVC | " . strtoupper($theType)) . "
//profile-update-interval: 1
//subscription-userinfo: upload=0; download=0; total=10737418240000000; expire=2546249531
//support-url: https://t.me/v2raycollector
//profile-web-page-url: https://github.com/yebekhe/TelegramV2rayCollector

";
    $createJsonc = $headerText . $finalJson ;
    return $createJsonc;
}
