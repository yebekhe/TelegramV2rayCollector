<?php
function convert_to_clash($url, $type)
{
    $repo_base_url = "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/";
    $protocol = str_replace("_base64", "", str_replace($repo_base_url, "", $url));
    $surf_url = "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/surfboard/$protocol";
    $base_url = [
        "clash" => "https://pub-api-1.bianyuan.xyz/sub?target=clash&url=",
        "meta" => "https://sub.bonds.id/sub2?target=clash&url=",
        "surfboard" =>
            "https://pub-api-1.bianyuan.xyz/sub?target=surfboard&url=",
    ];
    $params = [
        "clash" =>
            "&insert=false&config=https%3A%2F%2Fsubconverter.oss-ap-southeast-1.aliyuncs.com%2FRules%2FRemoteConfig%2Funiversal%2Furltest.ini&emoji=true&list=true&tfo=false&scv=false&fdn=false&sort=false",
        "meta" =>
            "&insert=false&config=base%2Fdatabase%2Fconfig%2Fcustom%2Fgroups%2Fallgroup_redir.ini&emoji=false&list=true&udp=true&tfo=false&expand=false&scv=true&fdn=false&sort=false&new_name=true&sort=false",
        "surfboard" =>
            "&insert=false&config=https%3A%2F%2Fsubconverter.oss-ap-southeast-1.aliyuncs.com%2FRules%2FRemoteConfig%2Funiversal%2Furltest.ini&emoji=true&list=true&tfo=false&scv=false&fdn=false&sort=false",
    ];
    $surf_url = "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/surfboard/"
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

    $replace_string =
        "#!MANAGED-CONFIG https://pub-api-1.bianyuan.xyz/sub?config=https%3A%2F%2Fsubconverter.oss-ap-southeast-1.aliyuncs.com%2FRules%2FRemoteConfig%2Funiversal%2Furltest.ini&emoji=true&fdn=false&insert=false&list=true&scv=false&sort=false&target=surfboard&tfo=false&url=" .
        $url .
        " interval=86400 strict=false\n\n";

    $url = $base_url[$type] . $url . $params[$type];
    $proxies = file_get_contents($url);
    $config_array = explode("\n", $proxies);
    $configs_name = extract_names($config_array, $type);
    $proxies = str_replace($replace_string, "", $proxies);
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

function extract_names($configs_array, $type)
{
    $configs_name = "";
    switch ($type) {
        case "clash":
            unset($configs_array[0]);
            $pattern = '/name:\s*("[^"]+"|[^,]+(?=,))/u';
            foreach ($configs_array as $config_data) {
                if (preg_match($pattern, $config_data, $matches)) {
                    $configs_name .= "      - " . $matches[1] . "\n";
                }
            }
            break;
        case "meta":
            $pattern = "/- name:\s+(.*)/";
            foreach ($configs_array as $config_data) {
                if (preg_match($pattern, $config_data, $matches)) {
                    $configs_name .= "      - " . $matches[1] . "\n";
                }
            }
            break;
        case "surfboard":
            unset($configs_array[0]);
            unset($configs_array[1]);
            foreach ($configs_array as $config_data) {
                $config_array = explode(" = ", $config_data);
                $configs_name .= $config_array[0] . ",";
            }
            break;
    }
    return $configs_name;
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
                "," .
                $configs_name .
                "\n";
            $proxy_group_urltest =
                $config_proxy_group["[Proxy Group]"][1] .
                "," .
                $configs_name .
                "\n";
            $proxy_group_fallback =
                $config_proxy_group["[Proxy Group]"][2] .
                "," .
                $configs_name .
                "\n";
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
