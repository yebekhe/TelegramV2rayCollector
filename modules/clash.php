<?php
function is_base64_encoded($string)
{
    if (base64_encode(base64_decode($string, true)) === $string) {
        return "true";
    } else {
        return "false";
    }
}

function process_vmess_clash($decoded_config, $output_type)
{
    $name = $decoded_config["ps"];
    if ($name === "" || is_null($decoded_config)) {
        return null;
    }
    $server = $decoded_config["add"];
    $port = $decoded_config["port"];
    $cipher = $decoded_config["scy"];
    $uuid = $decoded_config["id"];
    $alterId = $decoded_config["aid"];
    $tls = $decoded_config["tls"] === "tls" ? "true" : "false";
    $network = isset($decoded_config["net"]) ? $decoded_config["net"] : "tcp";
    if ($network === "ws") {
        $path = $decoded_config["path"];
        $host = $decoded_config["host"];
        $opts =
            '"ws-opts":{"path":"' .
            $path .
            '","headers":{"host":"' .
            $host .
            '"}}';
    } elseif ($network === "grpc") {
        $servicename = $decoded_config["path"];
        $mode = $decoded_config["type"];
        $opts =
            '"grpc-opts":{"grpc-service-name":"' .
            $servicename .
            '","grpc-mode":"' .
            $mode .
            '"}';
    } elseif ($network === "tcp") {
        $opts = "";
    }
    switch ($output_type){
        case "clash":
        case "meta":
            $vm_template =
            '  - {"name":"' .
            $name .
            '","type":"vmess","server":"' .
            $server .
            '","port":' .
            $port .
            ',"cipher":"' .
            $cipher .
            '","uuid":"' .
            $uuid .
            '","alterId":' .
            $alterId .
            ',"tls":' .
            $tls .
            ',"skip-cert-verify":true,"network":"' .
            $network .
            '",' .
            $opts .
            "}";
            break;
        case "surfboard":
            if ($network === "ws"){
                $vmes_aead = ($alterId === "0") ? "true" : "false";
                $vm_template = $name . ' = vmess, ' . $server . ', ' . $port . ', username = ' . $uuid . ', ws = true, tls = ' . $tls . ', vmess-aead = ' . $vmes_aead . ', ws-path = ' . $path . ', ws-headers = Host:"' . $host . '", skip-cert-verify = true, tfo = false';
            }else{
                return null;
            }
            break;
            
    }
    
    return  str_replace(",," , ",", $vm_template);
}

function process_trojan_clash($decoded_config, $output_type)
{
    $name = $decoded_config["hash"];
    if ($name === "" || is_null($decoded_config)) {
        return null;
    }
    $server = $decoded_config["hostname"];
    $port = $decoded_config["port"];
    $username = $decoded_config["username"];
    $sni = $decoded_config["params"]["sni"];
    $skip_cert =
        isset($decoded_config["params"]["allowInsecure"]) &&
        $decoded_config["params"]["allowInsecure"] === "1"
            ? "true"
            : "false";
    switch ($output_type){
        case "clash":
        case "meta":
            $tr_template =
            '  - {"name":"' .
            $name .
            '","type":"trojan","server":"' .
            $server .
            '","port":' .
            $port .
            ',"udp":true,"password":"' .
            $username .
            '","sni":"' .
            $sni .
            '","skip-cert-verify":' .
            $skip_cert .
            ',"network":"tcp"}';
            break;
        case "surfboard":
            $tr_template = $name . ' = trojan, ' . $server . ', ' . $port . ', password = ' . $username . ', udp-delay = true, skip-cert-verify = ' . $skip_cert . ', sni = ' . $sni . ', ws = false';
            break;
    }
    

    return $tr_template;
}

function process_shadowsocks_clash($decoded_config, $output_type)
{
    $name = $decoded_config["name"];
    if ($name === "" || is_null($name) || is_null($decoded_config)) {
        return null;
    }
    $server = $decoded_config["server_address"];
    $port = $decoded_config["server_port"];
    $password = $decoded_config["password"];
    $cipher = $decoded_config["encryption_method"];
    switch ($output_type){
        case "clash":
        case "meta":
            $ss_template =
            '  - {"name":"' .
            $name .
            '","type":"ss","server":"' .
            $server .
            '","port":' .
            $port .
            ',"password":"' .
            $password .
            '","cipher":"' .
            $cipher .
            '"}';
            break;
        case "surfboard":
            $ss_template = $name . ' = ss, ' . $server . ', ' . $port . ', encrypt-method = ' . $cipher . ', password = ' . $password;
            break;
        }
    return $ss_template;
}

function process_vless_clash($decoded_config, $output_type)
{
    $name = $decoded_config["hash"];
    if ($name === "" || is_null($decoded_config)) {
        return null;
    }
    $server = $decoded_config["hostname"];
    $port = $decoded_config["port"];
    $username = $decoded_config["username"];
    $sni = $decoded_config["params"]["sni"];
    $skip_cert =
        isset($decoded_config["params"]["allowInsecure"]) &&
        $decoded_config["params"]["allowInsecure"] === "1"
            ? "true"
            : "false";
    $tls = $decoded_config["params"]["security"] === "tls" ? "true" : "false";
    $flow =
        $decoded_config["params"]["flow"] === "tls"
            ? $decoded_config["params"]["flow"]
            : "";
    $network = isset($decoded_config["params"]["type"])
        ? $decoded_config["params"]["type"]
        : "tcp";
    if ($network === "ws") {
        $path = isset($decoded_config["params"]["path"])
            ? $decoded_config["path"]
            : "/";
        $host = isset($decoded_config["params"]["host"])
            ? $decoded_config["params"]["host"]
            : "";
        $opts =
            '"ws-opts":{"path":"' .
            $path .
            '","headers":{"host":"' .
            $host .
            '"}}';
    } elseif ($network === "grpc") {
        $servicename = $decoded_config["params"]["serviceName"];
        $opts = '"grpc-opts":{"grpc-service-name":"' . $servicename . '"}';
    } elseif ($network === "tcp") {
        $opts = "";
    }
    $reality =
        $decoded_config["params"]["security"] === "reality" ? "true" : "false";
    if ($reality === "true") {
        $pbk = $decoded_config["params"]["pbk"];
        $sid = $decoded_config["params"]["sid"];
        $reality_opts =
            '"reality-opts":{"public-key":"' .
            $pbk .
            '","short-id":"' .
            $sid .
            '"}';
    }
    $fingerprint = isset($decoded_config["params"]["fp"])
        ? $decoded_config["params"]["fp"]
        : "safari";
        switch ($output_type){
            
            case "meta":
                $vl_template =
                '  - {"name":"' .
                    $name .
                    '","type":"vless","server":"' .
                    $server .
                    '","port":' .
                    $port .
                    ',"udp":true,"uuid":"' .
                    $username .
                    '","tls":' .
                    $tls .
                    ',"servername":"' .
                    $sni .
                    '","flow":"' .
                    $flow .
                    '","network":"' . 
                    $network . 
                    '",' .
                    $opts .
                    "," .
                    $reality_opts .
                    ',"client-fingerprint":"' .
                    $fingerprint .
                    '"}';
                    break;
            case "clash":
            case "surfboard":
                return null;
        }
    

    return str_replace(",," , ",", $vl_template);
    
}

function process_convert($config, $type, $output_type)
{
    switch ($type) {
        case "vmess":
            return process_vmess_clash($config, $output_type);
        case "vless":
            return process_vless_clash($config, $output_type);
        case "trojan":
            return process_trojan_clash($config, $output_type);
        case "ss":
            return process_shadowsocks_clash($config, $output_type);
    }
}

function generate_proxies($input, $output_type)
{
    if (is_valid_address($input)){
        if (is_base64_encoded(file_get_contents($input)) === "true") {
            $v2ray_subscription = base64_decode(file_get_contents($input), true);
        } else {
            $v2ray_subscription = file_get_contents($input);
        }
    } else {
        if (is_base64_encoded($input) === "true"){
            $v2ray_subscription = base64_decode($input, true);
        } else {
            $v2ray_subscription = $input;
        }
    }

    $configs_array = explode("\n", $v2ray_subscription);
    foreach ($configs_array as $config) {
        $type = detect_type($config);
        $decoded_config = suitable_output(parse_config($config, $type) , $output_type);
        $proxies .= (!is_null(process_convert($decoded_config, $type, $output_type))) ? process_convert($decoded_config, $type, $output_type) . "\n" : null;
    }

    return $proxies;
}

function suitable_output($input, $output_type){
    switch ($output_type) {
        case 'clash':
        case 'surfboard':
            foreach ($input as $key => $config) {
                if (detect_type($config) === "vless"){
                    unset($input[$key]);
                }
                if ($config === "trojan://" || $config === "ss://Og==@:"){
                    unset($input[$key]);
                }
            }
            break;
        case 'meta':
            foreach ($input as $key => $config) {
                if ($config === "trojan://" || $config === "ss://Og==@:"){
                    unset($input[$key]);
                }
            }
            break;
    }
    return $input;
}

function extract_names($configs, $type)
{
    $configs_name = "";
    $configs_array = explode("\n", $configs);
    switch ($type) {
        case "meta":
        case "clash":
            unset($configs_array[0]);
            $pattern = '/"name":"(.*?)"/';
            foreach ($configs_array as $config_data) {
                if (preg_match($pattern, $config_data, $matches)) {
                    $configs_name .= "      - " . $matches[1] . "\n";
                }
            }
            break;
        case "surfboard":
            foreach ($configs_array as $config_data) {
                $config_array = explode(" = ", $config_data);
                $configs_name .= $config_array[0] . ",";
            }
            break;
    }
    return str_replace(",," , ",", $configs_name);
}

function full_config($input, $type, $protocol = "mix"){
    $surf_url = "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/surfboard/" . $protocol;

    $config_start = [
        "clash" => [
            "port: 7890",
            "socks-port: 7891",
            "allow-lan: true",
            "mode: Rule",
            "log-level: info",
            "ipv6: true",
            "external-controller: 0.0.0.0:9090",
        ],
        "meta" => [
            "port: 7890",
            "socks-port: 7891",
            "allow-lan: true",
            "mode: Rule",
            "log-level: info",
            "ipv6: true",
            "external-controller: 0.0.0.0:9090",
        ],
        "surfboard" => [
            "#!MANAGED-CONFIG " . $surf_url . " interval=60 strict=false",
            "",
            "[General]",
            "loglevel = notify",
            "interface = 127.0.0.1",
            "skip-proxy = 127.0.0.1, 192.168.0.0/16, 10.0.0.0/8, 172.16.0.0/12, 100.64.0.0/10, localhost, *.local",
            "ipv6 = true",
            "dns-server = system, 223.5.5.5",
            "exclude-simple-hostnames = true",
            "enhanced-mode-by-rule = true",
        ],
    ];

    $config_proxy_group = [
        "clash" => [
            "proxy-groups:" => [
                "MANUAL" => [
                    "  - name: MANUAL",
                    "    type: select",
                    "    proxies:",
                    "      - URL-TEST",
                    "      - FALLBACK",
                ],
                "URL-TEST" => [
                    "  - name: URL-TEST",
                    "    type: url-test",
                    "    url: http://www.gstatic.com/generate_204",
                    "    interval: 300",
                    "    tolerance: 50",
                    "    proxies:",
                ],
                "FALLBACK" => [
                    "  - name: FALLBACK",
                    "    type: fallback",
                    "    url: http://www.gstatic.com/generate_204",
                    "    interval: 300",
                    "    proxies:",
                ],
            ],
        ],
        "meta" => [
            "proxy-groups:" => [
                "MANUAL" => [
                    "  - name: MANUAL",
                    "    type: select",
                    "    proxies:",
                    "      - URL-TEST",
                    "      - FALLBACK",
                ],
                "URL-TEST" => [
                    "  - name: URL-TEST",
                    "    type: url-test",
                    "    url: http://www.gstatic.com/generate_204",
                    "    interval: 300",
                    "    tolerance: 50",
                    "    proxies:",
                ],
                "FALLBACK" => [
                    "  - name: FALLBACK",
                    "    type: fallback",
                    "    url: http://www.gstatic.com/generate_204",
                    "    interval: 300",
                    "    proxies:",
                ],
            ],
        ],
        "surfboard" => [
            "[Proxy Group]" => [
                "MANUAL = select,URL-TEST,FALLBACK,",
                "URL-TEST = url-test,",
                "FALLBACK = fallback,",
            ],
        ],
    ];

    $config_proxy_rules = [
        "clash" => ["rules:", " - GEOIP,IR,DIRECT", " - MATCH,MANUAL"],
        "meta" => ["rules:", " - GEOIP,IR,DIRECT", " - MATCH,MANUAL"],
        "surfboard" => ["[Rule]", "GEOIP,IR,DIRECT", "FINAL,MANUAL"],
    ];

    $proxies = generate_proxies($input, $type);
    $configs_name = extract_names($proxies, $type);
    $full_configs = generate_full_config(
        $config_start[$type],
        $proxies,
        $config_proxy_group[$type],
        $config_proxy_rules[$type],
        $configs_name,
        $type
    );
    return $full_configs;
}

function array_to_string($input)
{
    return implode("\n", $input);
}

function generate_full_config(
    $config_start,
    $proxies,
    $config_proxy_group,
    $config_proxy_rules,
    $configs_name,
    $type
) {
    $config_start_string = array_to_string($config_start);
    switch ($type) {
        case "clash":
        case "meta":
            $proxies = "\nproxies:\n" . $proxies;
            $proxy_group_string = "proxy-groups:";
            $proxy_group_manual =
                array_to_string(
                    $config_proxy_group["proxy-groups:"]["MANUAL"]
                ) .
                "\n" .
                $configs_name;
            $proxy_group_urltest =
                array_to_string(
                    $config_proxy_group["proxy-groups:"]["URL-TEST"]
                ) .
                "\n" .
                $configs_name;
            $proxy_group_fallback =
                array_to_string(
                    $config_proxy_group["proxy-groups:"]["FALLBACK"]
                ) .
                "\n" .
                $configs_name;
            break;
        case "surfboard":
            $proxies = "\n[Proxy]\nDIRECT = direct\n" . $proxies;
            $proxy_group_string = "[Proxy Group]";
            $proxy_group_manual =
                $config_proxy_group["[Proxy Group]"][0] .
                $configs_name .
                "\n";
            $proxy_group_manual = str_replace(",,", "", $proxy_group_manual);
            $proxy_group_urltest =
                $config_proxy_group["[Proxy Group]"][1] .
                $configs_name .
                "\n";
            $proxy_group_urltest = str_replace(",,", "", $proxy_group_urltest);
            $proxy_group_fallback =
                $config_proxy_group["[Proxy Group]"][2] .
                $configs_name .
                "\n";
            $proxy_group_fallback = str_replace(",,", "", $proxy_group_fallback);
            break;
    }
    $proxy_group_string .=
        "\n" .
        $proxy_group_manual .
        $proxy_group_urltest .
        $proxy_group_fallback;
    $proxy_rules = array_to_string($config_proxy_rules);
    $output =
        $config_start_string .
        "\n" .
        $proxies .
        "\n" .
        $proxy_group_string .
        "\n" .
        $proxy_rules;
    return $output;
}
