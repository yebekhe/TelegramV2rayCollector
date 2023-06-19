# TelegramV2rayCollector

This is a PHP script that collects V2Ray subscription links from various Telegram channels and saves them to different files based on their protocol type (VMess, VLess, Trojan, and Shadowsocks). The collected links are stored in text format.

## How it Works
The script includes two modules `getv2ray.php` and `config.php`. 

The `config.php` file contains an array of Telegram channels and the types of protocols they offer for V2ray subscriptions. 

The `getv2ray.php` module is responsible for fetching the latest subscription links from a Telegram channel and formatting them as plain text. It takes three arguments: `$channel` represents the Telegram channel name, `$type` represents the type of V2Ray protocol, and `$format` specifies the format of the output (text or JSON).

The main script iterates over each channel and the protocol types they offer, then passes the appropriate arguments to `getv2ray.php` to retrieve the subscription links. The collected links are then concatenated into a single string and saved to individual files based on their protocol type.

## Usage
Just import the following subscription link into the corresponding client. Use a client that at least support ss + vless + vmess + trojan.

- [`Mix of All`](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/mix)
- [`Mix of All - Base64`](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/mix_base64)

- [`Just VMESS`](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vmess)
- [`Just VMESS - Base64`](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vmess_base64)

- [`Just VLESS`](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vless)
- [`Just VLESS - Base64`](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vless_base64)

- [`Just Trojan`](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/trojan)
- [`Just Trojan - Base64`](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/trojan_base64)

- [`Just ShadowSocks`](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/shadowsocks)
- [`Just ShadowSocks - Base64`](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/shadowsocks_base64)

## Contribution
If you have a Telegram channel and you want to add it to this project or you know Telegram channels that can be used in this project you can ask for addition in Issues.

## License
This project is licensed under the MIT License - see the LICENSE file for details.
