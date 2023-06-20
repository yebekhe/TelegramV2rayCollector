# TelegramV2rayCollector
[![Collect Configs](https://github.com/yebekhe/TelegramV2rayCollector/actions/workflows/php.yml/badge.svg)](https://github.com/yebekhe/TelegramV2rayCollector/actions/workflows/php.yml)

This is a PHP script that collects V2Ray subscription links from various Telegram channels and saves them to different files based on their protocol type (VMess, VLess, Trojan, and Shadowsocks). The collected links are stored in text format.

## How it Works
The script includes two modules `getv2ray.php` and `config.php`. 

The `config.php` file contains an array of Telegram channels and the types of protocols they offer for V2ray subscriptions. 

The `getv2ray.php` module is responsible for fetching the latest subscription links from a Telegram channel and formatting them as plain text. It takes three arguments: `$channel` represents the Telegram channel name, `$type` represents the type of V2Ray protocol, and `$format` specifies the format of the output (text or JSON).

The main script iterates over each channel and the protocol types they offer, then passes the appropriate arguments to `getv2ray.php` to retrieve the subscription links. The collected links are then concatenated into a single string and saved to individual files based on their protocol type.

## Usage
Just import the following subscription link into the corresponding client. Use a client that at least support ss + vless + vmess + trojan.

| CONFIG TYPE | NORMAL SUBSCRIPTION | BASE64 SUBSCRIPTION |
|---|---|---|
| MIX of ALL | [NORMAL SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/mix) | [BASE64 SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/mix_base64) |
| VMESS | [NORMAL SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vmess) | [BASE64 SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vmess_base64) |
| VLESS | [NORMAL SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vless) | [BASE64 SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vless_base64) |
| REALITY | [NORMAL SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/reality) | [BASE64 SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/reality_base64) |
| TROJAN | [NORMAL SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/trojan) | [BASE64 SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/trojan_base64) |
| ShadowSocks | [NORMAL SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/shadowsocks) | [BASE64 SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/shadowsocks_base64) |


## Contribution
If you have a Telegram channel and you want to add it to this project or you know Telegram channels that can be used in this project you can ask for addition in Issues.

## License
This project is licensed under the MIT License - see the LICENSE file for details.
