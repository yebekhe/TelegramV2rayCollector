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
        "port" => isset($parsedUrl["port"])
            ? $parsedUrl["port"]
            : ($type === "trojan"
                ? 80
                : 443),
        "params" => $params,
        "hash" => isset($parsedUrl["fragment"]) ? $parsedUrl["fragment"] : "",
    ];

    return $output;
}

function buildProxyUrl($obj, $type = "trojan")
{
    // Construct the base URL
    $url = $type . "://";
    if ($obj["username"] !== "") {
        $url .= $obj["username"];
        if (isset($obj["pass"]) && $obj["pass"] !== "") {
            $url .= ":" . $obj["pass"];
        }
        $url .= "@";
    }
    $url .= $obj["hostname"];
    if (
        isset($obj["port"]) &&
        $obj["port"] !== "" &&
        $obj["port"] !== ($type === "trojan" ? 80 : 443)
    ) {
        $url .= ":" . $obj["port"];
    }

    // Add the query parameters
    if (!empty($obj["params"])) {
        $url .= "?" . http_build_query($obj["params"]);
    }

    // Add the fragment identifier
    if (isset($obj["hash"]) && $obj["hash"] !== "") {
        $url .= "#" . $obj["hash"];
    }

    return $url;
}
?>
