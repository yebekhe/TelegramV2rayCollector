<?php

function parseTuic ($config_str) {
    $parsedUrl = parse_url($config_str);

    // Extract the parameters from the query string
    $params = [];
    if (isset($parsedUrl["query"])) {
        parse_str($parsedUrl["query"], $params);
    }

    // Construct the output object
    $output = [
        "protocol" => "tuic",
        "username" => isset($parsedUrl["user"]) ? $parsedUrl["user"] : "",
        "pass" => isset($parsedUrl["pass"]) ? $parsedUrl["pass"] : "",
        "hostname" => isset($parsedUrl["host"]) ? $parsedUrl["host"] : "",
        "port" => isset($parsedUrl["port"]) ? $parsedUrl["port"] : "",
        "params" => $params,
        "hash" => isset($parsedUrl["fragment"]) ? $parsedUrl["fragment"] : "",
    ];

    return $output;

}

function buildTuic($obj)
{
    $url = "tuic://";
    $url .= addUsernameAndPassword($obj);
    $url .= $obj["hostname"];
    $url .= addPort($obj);
    $url .= addParams($obj);
    $url .= addHash($obj);
    return $url;
}

function remove_duplicate_tuic($input)
{
    $array = explode("\n", $input);

    foreach ($array as $item) {
        $parts = parseTuic($item);
        $part_hash = $parts["hash"];
        unset($parts["hash"]);
        ksort($parts["params"]);
        $part_serialize = base64_encode(serialize($parts));
        $result[$part_serialize][] = $part_hash ?? "";
    }

    $finalResult = [];
    foreach ($result as $url => $parts) {
        $partAfterHash = $parts[0] ?? "";
        $part_serialize = unserialize(base64_decode($url));
        $part_serialize["hash"] = $partAfterHash;
        $finalResult[] = buildTuic($part_serialize);
    }

    $output = "";
    foreach ($finalResult as $config) {
        $output .= $output == "" ? $config : "\n" . $config;
    }
    return $output;
}
