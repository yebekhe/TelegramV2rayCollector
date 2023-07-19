<?php

function tehran_time()
{
    date_default_timezone_set("Asia/Tehran");
    $tehran_time = time();
    $formatted_time = date("Y-m-d H:i:s", $tehran_time);

    return $formatted_time;
}

function same_date($time_stamp)
{
    $current_date = substr(tehran_time(), 0, 10);
    $compare_date = substr($time_stamp, 0, 10);
    if ($current_date != $compare_date) {
        return false;
    } else {
        return true;
    }
}

function compare_time($a, $b)
{
    $a_time = strtotime($a->time);
    $b_time = strtotime($b->time);
    if ($a_time == $b_time) {
        return 0;
    }
    return $a_time > $b_time ? -1 : 1;
}

function point_array($input){
    $usernames = array_column(array_column($input, "channel"), "username");
    $point_array = array_count_values($usernames);
    return $point_array;
}

function lowest_ping($input){
    return array_reduce($input, function($min, $item) {
        return ($item['ping'] < $min) ? $item['ping'] : $min;
    }, PHP_FLOAT_MAX);
}
function time_coefficient($config_time){
    $time_diff = strtotime(tehran_time()) - strtotime($config_time);
    $time_coefficient = 1 / ($time_diff + 1);
    return $time_coefficient;
}
function ping_coefficient($config_ping, $lowest_ping){
    $ping_diff = $config_ping - $lowest_ping;
    $ping_coefficient = 1 / ($ping_diff + 1);
    return $ping_coefficient;
}

function process_rank_file($type){
    $last_point_array = [];
    if (file_exists("ranking/channel_ranking_" . $type . ".json")) {
        $last_point_array = json_decode(
            file_get_contents("ranking/channel_ranking_" . $type . ".json"),
            true
        );
        $last_rank_date = $last_point_array['date'];
        if (same_date($last_rank_date) === false){
            unlink("ranking/channel_ranking_" . $type . ".json");
            $last_point_array['points'] = [];
        }
    } else {
        $last_point_array['points'] = [];
    }
    return $last_point_array;
}

function process_points($point_array, $last_point_array){
    if (!empty(array_diff_assoc($point_array, $last_point_array['points']))) {
        foreach ($point_array as $channel => $point) {
            if (array_key_exists($channel, $last_point_array['points'])) {
                $last_point_array['points'][$channel] += $point;
            } else {
                $last_point_array['points'][$channel] = $point;
            }
        }
    }
    return $last_point_array;
}

function ranking($input, $type)
{
    $point_array = point_array($input);
    $lowest_ping = lowest_ping($input);

    foreach ($input as $key => $config){
        
        $username_ch = $config['channel']['username'];

        $time_coefficient = time_coefficient($config['time']);
        $point_array[$username_ch] += $time_coefficient;

        $ping_coefficient = ping_coefficient($config['ping'], $lowest_ping);
        $point_array[$username_ch] += $ping_coefficient;
        
        switch ($config['type']){
            case "reality":
                $point_array[$username_ch] += 1 ;
                break;
        }
    }
    
    $last_point_array = process_points($point_array, process_rank_file($type));

    $final_point_array = [
        "date" => tehran_time(),
        "points" => $last_point_array['points'],
    ];

    arsort($final_point_array['points']);

    return $final_point_array;
}
