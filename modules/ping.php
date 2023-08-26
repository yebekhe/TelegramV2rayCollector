<?php
function ping($ip, $port)
{
    $it = microtime(true);
    $check = @fsockopen($ip, $port, $errno, $errstr, 0.2);
    $ft = microtime(true);
    $militime = round(($ft - $it) * 1e3, 2);
    if ($check) {
        fclose($check);
        return $militime;
    } else {
        return "unavailable";
    }
}

function check_the_host($host)
{
    if (empty($host)) {
        return null;
    }

    $url = "https://check-host.net/check-ping";
    $nodes = ["ir3.node.check-host.net", "ir4.node.check-host.net", "ir1.node.check-host.net"];
    $params = http_build_query([
        'host' => $host,
        'node' => $nodes,
    ]);

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url . '?' . $params,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["Accept: application/json"],
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "cURL error: " . curl_error($ch);
        return null;
    }

    $request_id = json_decode($response, true)["request_id"];
    curl_close($ch);

    return $request_id;
}

function check_the_ping($request_id)
{
    if (empty($request_id)) {
        return "Request ID not found!";
    }

    $url = "https://check-host.net/check-result/" . $request_id;

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["Accept: application/json"],
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "cURL error: " . curl_error($ch);
        return null;
    }

    $decoded_response = json_decode($response, true);

    $pings = [];
    $nodes = ["ir1.node.check-host.net", "ir3.node.check-host.net", "ir4.node.check-host.net"];

    foreach ($nodes as $node) {
        if (empty($decoded_response[$node])) {
            continue;
        }

        $count = 0;
        $ping_sum = 0;
        foreach ($decoded_response[$node][0] as $value) {
            if (@$value[0] == "OK") {
                $count++;
                $ping_sum += $value[1];
            }
        }

        if ($count !== 0) {
            $ping_avg = (@$ping_sum / $count) * 1000;
            $pings[$node] = $ping_avg;
        }
    }

    $json_pings = json_encode($pings, JSON_PRETTY_PRINT);

    curl_close($ch);

    return $json_pings;
}

function accuracy($accuracy)
{
    $accuracy = (int) $accuracy;

    if ($accuracy < 0) {
        $accuracy = 0;
    } elseif ($accuracy > 10) {
        $accuracy = 10;
    }

    sleep($accuracy);
}

function filtered_or_not($input, $accuracy = 3){
    $request_id = check_the_host($input);
    accuracy($accuracy);
    $pings = check_the_ping($request_id);
    $check_host_data = json_decode($pings, true);
    $ping_count = 0;
    $precent = [100, 66, 33, 0];
    if (!is_null($check_host_data)){
        $ping_count = count($check_host_data);
        $output = $precent[$ping_count] >= 66 ? true : false ;
    }
    return $output;
}

