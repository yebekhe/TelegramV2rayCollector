<?php
header("Content-type: application/json;");
function to_clash($subscription)
{
    $url = "https://pub-api-1.bianyuan.xyz/sub?target=clash&url=";
    $config_str = file_get_contents($url . $subscription . "&insert=false");
    $translations = array(
        '节点选择' => 'MANUAL',
        '自动选择' => 'URL-TEST',
        '全球直连' => 'DIRECT',
        'NETFLIX' => 'NETFLIX',
        '广告拦截' => 'AD-Blocking',
        '全球拦截' => 'GLOBAL-Blocking',
        '运营劫持' => 'Operation-Hijacking',
        '国外媒体' => 'FOREIGN-Media',
        '国内媒体' => 'DOMESTIC-Media',
        '微软服务' => 'MICROSOFT-Services',
        '电报信息' => 'TELEGRAM-Information',
        '苹果服务' => 'APPLE-Services',
        '全球直连' => 'GLOBAL-Direct',
        '漏网之鱼' => 'LEAKAGE',
    );
    $config_en = str_replace(array_keys($translations), array_values($translations), $config_str);

    return $config_en;
}

$clash_mix = to_clash("https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/mix_base64");
$clash_vmess = to_clash("https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vmess_base64");
$clash_trojan = to_clash("https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/trojan_base64");
$clash_ss = to_clash("https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/shadowsocks_base64");
file_put_contents("clash/mix.yml", $clash_mix);
file_put_contents("clash/vmess.yml", $clash_vmess);
file_put_contents("clash/trojan.yml", $clash_trojan);
file_put_contents("clash/ss.yml", $clash_ss);
?>
