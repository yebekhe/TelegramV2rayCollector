<?php

header("Content-type: application/json;");

function is_base64_encoded($string)
{
    if (base64_encode(base64_decode($string, true)) === $string) {
        return "true";
    } else {
        return "false";
    }
}

function get_singbox_name($decoded_config){
    $name = $decoded_config["hash"];
    if ($name === "") {
        return null;
    }
    return $name;
}

function get_singbox_server($decoded_config){
    return $decoded_config["hostname"];
}

function get_singbox_port($decoded_config){
    return isset($decoded_config["port"]) ? $decoded_config["port"] : 443;
}

function get_singbox_username($decoded_config){
    return $decoded_config["username"];
}

function get_singbox_sni($decoded_config){
    return isset($decoded_config["params"]["sni"]) ? $decoded_config["params"]["sni"] : "";
}

function get_singbox_tls($decoded_config){
    return isset($decoded_config["params"]["security"]) && $decoded_config["params"]["security"] === "tls" ? "true" : "false";
}

function get_singbox_flow($decoded_config){
    return isset($decoded_config["params"]["flow"]) ? "xtls-rprx-vision" : "";
}

function get_singbox_network($decoded_config){
    return isset($decoded_config["params"]["type"]) ? $decoded_config["params"]["type"] : "tcp";
}

function get_singbox_transport($decoded_config, $network){
    if ($network === "grpc") {
        return isset($decoded_config["params"]["serviceName"]) ? ',"transport":{"type":"grpc", "service_name":"' . $decoded_config["params"]["serviceName"] . '"}' : "";
    } else {
        return "";
    }
}

function get_singbox_fingerprint($decoded_config){
    return isset($decoded_config["params"]["fp"]) && $decoded_config["params"]["fp"] !== "random" && $decoded_config["params"]["fp"] !== "ios" && $decoded_config["params"]["fp"] !== "android" ? $decoded_config["params"]["fp"] : "chrome";
}

function get_singbox_reality($decoded_config){
    return isset($decoded_config["params"]["security"]) && $decoded_config["params"]["security"] === "reality" ? "true" : "false";
}

function get_singbox_pbk($decoded_config){
    if (!isset($decoded_config["params"]["pbk"]) || $decoded_config["params"]["pbk"] === ""){
        return null;
    }
    return $decoded_config["params"]["pbk"];
}

function get_singbox_sid($decoded_config){
    return isset($decoded_config["params"]["sid"]) && $decoded_config["params"]["sid"] !== "" ? $decoded_config["params"]["sid"] : "";
}

function get_singbox_output($server, $port, $name, $tls, $sni, $pbk, $sid, $fingerprint, $transport, $flow, $network, $username, $reality){
    $output = '{"server":"' . $server . '", "server_port":' . $port . ', "tag": "' . $name . '", "tls":{"enabled": true, "reality":{"enabled": ' . $reality . ', "public_key":"' . $pbk . '", "short_id": "' . $sid . '"}, "server_name":"' . $sni . '", "utls":{"enabled": true, "fingerprint":"' . $fingerprint . '"}}' . $transport . ', "type":"vless", "flow":"' . $flow . '", "uuid":"' . $username . '"}';
    return $output;
}

function vless_reality_json($vless_uri){
    $decoded_config = parseProxyUrl($vless_uri, "vless");

    $name = get_singbox_name($decoded_config);
    if ($name === null) {
        return null;
    }
    $server = get_singbox_server($decoded_config);
    $port = get_singbox_port($decoded_config);
    $username = get_singbox_username($decoded_config);
    $sni = get_singbox_sni($decoded_config);
    $tls = get_singbox_tls($decoded_config);
    $flow = get_singbox_flow($decoded_config);
    $network = get_singbox_network($decoded_config);
    $transport = get_singbox_transport($decoded_config, $network);
    $fingerprint = get_singbox_fingerprint($decoded_config);
    $reality = get_singbox_reality($decoded_config);

    if ($reality === "true") {
        $pbk = get_singbox_pbk($decoded_config);
        if ($pbk === null) {
            return null;
        }
        $sid = get_singbox_sid($decoded_config);
        $tls = "true";
        $fingerprint = isset($decoded_config["params"]["fp"]) && $decoded_config["params"]["fp"] !== "random" && $decoded_config["params"]["fp"] !== "ios" ? $decoded_config["params"]["fp"] : "chrome";
    } 

    $output = get_singbox_output($server, $port, $name, $tls, $sni, $pbk, $sid, $fingerprint, $transport, $flow, $network, $username, $reality);

    return $output; // return the JSON configuration
}

function generate_output($input, $output){
    $outbound = [];
    $v2ray_subscription = $input;

    $configs_array = explode("\n", $v2ray_subscription);
    foreach ($configs_array as $config) {
        if (stripos($config, "security=reality")){
            $json_output = vless_reality_json($config);
        }
        if ($json_output !== null){
            $the_name = json_decode($json_output, true)['tag'];
            if (stripos($the_name, "RELAY🚩")){
                $server_location = "RELAY🚩";
            } else {
                $pattern = '/\b[A-Z]{2}\b[\x{1F1E6}-\x{1F1FF}]{2}/u';
                preg_match_all($pattern, $the_name, $matches);
                $server_location = $matches[0][0];
            }
            $outbound[$server_location][] = json_decode($json_output, true);
        }
    }
    $json_map = ["nekobox_old" => "nekobox_1.1.7.json", "nekobox_new" => "nekobox_1.1.8.json", "sfi" => "sfi.json"];
    $template = json_decode(file_get_contents("modules/singbox/" . $json_map[$output]), true);
    $manual_json = json_decode(file_get_contents("modules/singbox/manual.json"), true);
    $url_test_json = json_decode(file_get_contents("modules/singbox/url_test.json"), true);
    
    $url_test_outbound =[];
    $outbounds = [];
    $names = [];
    $all_test_group_outbound = [];
    foreach ($outbound as $name => $test_group){
        $names[] = $name;
        $test_group_names = extract_names($test_group);
        $url_test_json[0]['tag'] = $name;
        $test_group_outbound = process_jsons($url_test_json, $test_group_names);
        $all_test_group_outbound = array_merge($all_test_group_outbound, $test_group_outbound);
        $outbounds = array_merge($outbounds, $test_group);
    }

    $manual_outbound = process_jsons($manual_json, $names);
    $url_test_json[0]['tag'] = "URL-TEST";
    $url_test_outbound = process_jsons($url_test_json, $names);
    $url_test_outbound = array_merge($url_test_outbound, $all_test_group_outbound);

    $template['outbounds'] = array_merge($manual_outbound, $url_test_outbound, $outbounds,  $template['outbounds']);
    return json_encode($template, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

function process_jsons($input, $names){
    $input[0]['outbounds'] = array_merge($input[0]['outbounds'], $names);
    return $input;
}

function extract_names($input){
    foreach($input as $config){
            $names[] = $config['tag'];
    }
    return $names;
}
