<?php

function parseProxyUrl($url, $type = "trojan")
{
    // Parse the URL into components
    $parsedUrl = parse_url($url);

    // Extract the parameters from the query string
    $params = [];
    if (isset($parsedUrl["query"])) {
        parse_str($parsedUrl["query"], $params);
    }

    // Construct the output object
    $output = [
        "protocol" => $type,
        "username" => isset($parsedUrl["user"]) ? $parsedUrl["user"] : "",
        "hostname" => isset($parsedUrl["host"]) ? $parsedUrl["host"] : "",
        "port" => isset($parsedUrl["port"]) ? $parsedUrl["port"]: "",
        "params" => $params,
        "hash" => isset($parsedUrl["fragment"]) ? $parsedUrl["fragment"] : "",
    ];

    return $output;
}

function buildProxyUrl($obj, $type = "trojan")
{
    $url = $type . "://";
    $url .= addUsernameAndPassword($obj);
    $url .= $obj["hostname"];
    $url .= addPort($obj);
    $url .= addParams($obj);
    $url .= addHash($obj);
    return $url;
}

function addUsernameAndPassword($obj)
{
    $url = "";
    if ($obj["username"] !== "") {
        $url .= $obj["username"];
        if (isset($obj["pass"]) && $obj["pass"] !== "") {
            $url .= ":" . $obj["pass"];
        }
        $url .= "@";
    }
    return $url;
}

function addPort($obj)
{
    $url = "";
    if (isset($obj["port"]) && $obj["port"] !== "") {
        $url .= ":" . $obj["port"];
    }
    return $url;
}

function addParams($obj)
{
    $url = "";
    if (!empty($obj["params"])) {
        $url .= "?" . http_build_query($obj["params"]);
    }
    return $url;
}

function addHash($obj)
{
    $url = "";
    if (isset($obj["hash"]) && $obj["hash"] !== "") {
        $url .= "#" . $obj["hash"];
    }
    return $url;
}

function remove_duplicate_xray($input, $type)
{
    $array = explode("\n", $input);

    foreach ($array as $item) {
        $parts = parseProxyUrl($item, $type);
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
        $finalResult[] = buildProxyUrl($part_serialize, $type);
    }

    $output = "";
    foreach ($finalResult as $config) {
        $output .= $output == "" ? $config : "\n" . $config;
    }
    return $output;
}


function get_reality($input)
{
    $array = explode("\n", $input);
    $output = "";
    foreach ($array as $item) {
        if (stripos($item, "reality")) {
            $output .= $output === "" ? $item : "\n$item";
        }
    }
    return $output;
}
