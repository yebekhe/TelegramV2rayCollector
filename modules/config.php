<?php
date_default_timezone_set("Asia/Tehran");

$Types = json_decode(file_get_contents("https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/modules/channels.json"), true);

$donated_subscription = [
    "https://yebekhe.000webhostapp.com/donate/donated_servers/donated_server.json"
];
