<?php
function convert_to_clash($input)
{
  $data = file_get_contents("https://pub-api-1.bianyuan.xyz/sub?target=clash&url=" . $input . "&insert=false");
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
  $config_en = str_replace(array_keys($translations), array_values($translations), $data);
  return $config_en;
}
?>
