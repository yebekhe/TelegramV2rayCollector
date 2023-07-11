<?php
function convert_to_surfboard($input , $type = "clash")
{
  if ($type === "surfboard"){
  $data = file_get_contents("https://pub-api-1.bianyuan.xyz/sub?target=surfboard&url=" . $input . "&insert=false&config=https%3A%2F%2Fsubconverter.oss-ap-southeast-1.aliyuncs.com%2FRules%2FRemoteConfig%2Funiversal%2Furltest.ini&emoji=true&list=false&tfo=false&scv=false&fdn=false&sort=false");
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
  ?>
