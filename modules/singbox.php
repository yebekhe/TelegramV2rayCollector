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

// Parse the VLESS URI
function vless_reality_json($vless_uri){
    $decoded_config = parseProxyUrl($vless_uri, "vless");

    $name = $decoded_config["hash"];
    if ($name === "") {
        return null;
    }
    $server = $decoded_config["hostname"];
    $port = $decoded_config["port"];
    $username = $decoded_config["username"];
    $sni = isset($decoded_config["params"]["sni"])
        ? $decoded_config["params"]["sni"]
        : "";
    $tls =
        isset($decoded_config["params"]["security"]) &&
        $decoded_config["params"]["security"] === "tls"
            ? "true"
            : "false";
    $flow =
        isset($decoded_config["params"]["flow"]) &&
        $decoded_config["params"]["flow"] !== ""
            ? $decoded_config["params"]["flow"] 
            : "";
    $network = isset($decoded_config["params"]["type"])
        ? $decoded_config["params"]["type"]
        : "tcp";
    if ($network === "grpc") {
        $transport = isset($decoded_config["params"]["serviceName"]) ? ',"transport":{"type":"grpc", "service_name":"' . $decoded_config["params"]["serviceName"] . '"}' : "";
    } else {
        $network = "tcp";
        $transport = "";
    }
    $fingerprint =
        isset($decoded_config["params"]["fp"]) &&
        $decoded_config["params"]["fp"] !== "random" &&
        $decoded_config["params"]["fp"] !== "ios" &&
        $decoded_config["params"]["fp"] !== "android"
            ? $decoded_config["params"]["fp"]
            : "chrome";
    $reality =
        isset($decoded_config["params"]["security"]) &&
        $decoded_config["params"]["security"] === "reality"
            ? "true"
            : "false";
    if ($reality === "true") {
        $pbk = $decoded_config["params"]["pbk"];
        $sid =
            isset($decoded_config["params"]["sid"]) &&
            $decoded_config["params"]["sid"] !== ""
                ? $decoded_config["params"]["sid"]
                : "";
        $tls = "true";
        $fingerprint =
            isset($decoded_config["params"]["fp"]) &&
            $decoded_config["params"]["fp"] !== "random" &&
            $decoded_config["params"]["fp"] !== "ios"
                ? $decoded_config["params"]["fp"] 
                : "chrome";
    } 

    
    $output = '{"server":"' . $server . '", "server_port":' . $port . ', "tag": "' . $name . '", "tls":{"enabled": true, "reality":{"enabled": true, "public_key":"' . $pbk . '", "short_id": "' . $sid . '"}, "server_name":"' . $sni . '", "utls":{"enabled": true, "fingerprint":"' . $fingerprint . '"}}' . $transport . ', "type":"vless", "flow":"' . $flow . '", "uuid":"' . $username . '"}';


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
        $outbound[] = json_decode($json_output, true);
    }
    $json_map = ["nekobox" => "template.json", "sfi" => "sfi.json"];
    $template = json_decode(file_get_contents("modules/singbox/" . $json_map[$output]), true);
    $manual_json = json_decode(file_get_contents("modules/singbox/manual.json"), true);
    $url_test_json = json_decode(file_get_contents("modules/singbox/url_test.json"), true);

    $names = extract_names($outbound);
    $manual_outbound = process_jsons($manual_json, $names);
    $url_test_outbound = process_jsons($url_test_json, $names);

    $template['outbounds'] = array_merge($manual_outbound, $url_test_outbound, $outbound,  $template['outbounds']);
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
