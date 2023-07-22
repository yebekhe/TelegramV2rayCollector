<?php
function convert_to_clash($input, $type = "clash")
{
    // Mapping of translations for different options
    $translations = array(
        '节点选择' => 'MANUAL',
        '自动选择' => 'AUTO SELECT',
        '全球直连' => 'DIRECT',
        'NETFLIX' => 'NETFLIX',
        '广告拦截' => 'AD BLOCK',
        '全球拦截' => 'GLOBAL BLOCK',
        '运营劫持' => 'OPERATION',
        '国外媒体' => 'FOREIGN MEDIA',
        '国内媒体' => 'DOMESTIC MEDIA',
        '微软服务' => 'MICROSOFT',
        '电报信息' => 'TELEGRAM',
        '苹果服务' => 'APPLE',
        '漏网之鱼' => 'LEAK',
        'FASTSSH-SSHKIT-HOWDY' => 'MANUAL',
        '- GEOIP,CN,🎯 DIRECT' => '- GEOIP,IR,🎯 DIRECT',
        '- MATCH,🐟 LEAK' => '- MATCH,🚀 MANUAL',
    );

    $url = '';
    $config = '';

    switch ($type) {
        case 'clash':
            // Construct URL for Clash conversion
            $url = "https://pub-api-1.bianyuan.xyz/sub?target=clash&url=" . $input . "&insert=false&config=https%3A%2F%2Fraw.githubusercontent.com%2FACL4SSR%2FACL4SSR%2Fmaster%2FClash%2Fconfig%2FACL4SSR_Online_Mini.ini&emoji=true&list=false&tfo=false&scv=false&fdn=false&sort=false&new_name=true";
            break;
        case 'meta':
            // Construct URL for Meta conversion
            $url = "https://sub.bonds.id/sub2?target=clash&url=" . $input . "&insert=false&config=base%2Fdatabase%2Fconfig%2Fcustom%2Fgroups%2Fallgroup_redir.ini&emoji=false&list=false&udp=true&tfo=false&expand=false&scv=true&fdn=false&sort=false&new_name=true";
            break;
        case 'surfboard':
            // Construct URL for Surfboard conversion
            $url = "https://pub-api-1.bianyuan.xyz/sub?target=surfboard&url=" . $input . "&insert=false&config=https%3A%2F%2Fsubconverter.oss-ap-southeast-1.aliyuncs.com%2FRules%2FRemoteConfig%2Funiversal%2Furltest.ini&emoji=true&list=false&tfo=false&scv=false&fdn=false&sort=false";
            break;
    }

    if (!empty($url)) {
        // Fetch data from the specified URL
        $data = file_get_contents($url);
        
        // Replace translations in the fetched data
        $config = strtr($data, $translations);
        $config = preg_replace('/^\s*profile:\s*\n\s*store-selected:\s*true\s*\n/m', '', $config);
        $new_config = str_replace(
            "rules:\n  - MATCH,FASTSSH-SSHKIT-HOWDY",
            "name: DIRECT\n" .
            "type: select\n" .
            "proxies:\n" .
            "  -DIRECT\n" .
            "rules:\n" .
            "  - GEOIP,IR,Global\n" .
            "  - MATCH,MANUAL",
        $config
        );
        
    }

    return $config;
}
