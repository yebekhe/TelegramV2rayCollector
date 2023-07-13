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

function ranking($input, $type)
{
    $last_point_array = [];
    $usernames = array_column(array_column($input, "channel"), "username");
    $point_array = array_count_values($usernames);

    foreach ($input as $key => $config){
        $username_ch = $config['channel']['username'];
        if((strtotime(tehran_time()) - strtotime($config['time'])) <= 1800){
            $point_array[$username_ch] += 2 ;
        } elseif((strtotime(tehran_time()) - strtotime($config['time'])) <= 3600){
            $point_array[$username_ch] += 1 ;
        } elseif((strtotime(tehran_time()) - strtotime($config['time'])) >= 86400){
            $point_array[$username_ch] -= 3 ;
        } elseif((strtotime(tehran_time()) - strtotime($config['time'])) >= 172800){
            $point_array[$username_ch] -= 4 ;
        } 
        
        if($config['type'] === "reality"){
            $point_array[$username_ch] += 4 ;
        }
    }

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

    if (!empty(array_diff_assoc($point_array, $last_point_array['points']))) {
        foreach ($point_array as $channel => $point) {
            if (array_key_exists($channel, $last_point_array['points'])) {
                $last_point_array['points'][$channel] += $point;
            } else {
                $last_point_array['points'][$channel] = $point;
            }
        }
    }

    $final_point_array = [
        "date" => tehran_time(),
        "points" => $last_point_array['points'],
    ];

    arsort($final_point_array['points']);

    return $final_point_array;
}

?>
