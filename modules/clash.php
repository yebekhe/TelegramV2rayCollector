<?php
function convert_to_clash($input , $type = "clash")
{
  if ($type === "clash"){
  $data = file_get_contents("https://pub-api-1.bianyuan.xyz/sub?target=clash&url=" . $input . "&insert=false&config=https%3A%2F%2Fraw.githubusercontent.com%2FACL4SSR%2FACL4SSR%2Fmaster%2FClash%2Fconfig%2FACL4SSR_Online_Mini.ini&emoji=true&list=false&tfo=false&scv=false&fdn=false&sort=false&new_name=true");
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
    '全球直连' => 'GLOBAL DIRECT',
    '漏网之鱼' => 'LEAK',
  );
  $config = str_replace(array_keys($translations), array_values($translations), $data);
  return $config;
  }
  elseif ($type === "meta"){
    $data = file_get_contents("https://sub.bonds.id/sub2?target=clash&url=" . $input . "&insert=false&config=base%2Fdatabase%2Fconfig%2Fcustom%2Fgroups%2Fallgroup_redir.ini&emoji=false&list=false&udp=true&tfo=false&expand=false&scv=true&fdn=false&sort=false&new_name=true");
    $translations = array(
    'FASTSSH-SSHKIT-HOWDY' => 'MANUAL',
      );
    $config = str_replace(array_keys($translations), array_values($translations), $data);
    return $config;
  }
}

?>
