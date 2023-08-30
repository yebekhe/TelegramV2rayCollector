<?php

function ParseTuic ($config_str) {
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
        "password" => isset($parsedUrl["pass"]) ? $parsedUrl["pass"] : "",
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
