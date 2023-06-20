# TelegramV2rayCollector
[![Collector](https://github.com/yebekhe/TelegramV2rayCollector/actions/workflows/php.yml/badge.svg)](https://github.com/yebekhe/TelegramV2rayCollector/actions/workflows/php.yml) [![Clash](https://github.com/yebekhe/TelegramV2rayCollector/actions/workflows/clash.yml/badge.svg)](https://github.com/yebekhe/TelegramV2rayCollector/actions/workflows/clash.yml)

This is a PHP script that collects V2Ray subscription links from various Telegram channels and saves them to different files based on their protocol type (VMess, VLess, Trojan, and Shadowsocks). The collected links are stored in text format.

## How it Works
The script includes two modules `getv2ray.php` and `config.php`. 

The `config.php` file contains an array of Telegram channels and the types of protocols they offer for V2ray subscriptions. 

The `getv2ray.php` module is responsible for fetching the latest subscription links from a Telegram channel and formatting them as plain text. It takes three arguments: `$channel` represents the Telegram channel name, `$type` represents the type of V2Ray protocol, and `$format` specifies the format of the output (text or JSON).

The main script iterates over each channel and the protocol types they offer, then passes the appropriate arguments to `getv2ray.php` to retrieve the subscription links. The collected links are then concatenated into a single string and saved to individual files based on their protocol type.

## Instructions & Usage

Just import the following subscription link into the corresponding client. Use a client that at least support ss + vless + vmess + trojan.

| CONFIG TYPE | NORMAL SUBSCRIPTION | BASE64 SUBSCRIPTION | CLASH SUBSCRITION |
|---|---|---|---|
| MIX of ALL | [NORMAL SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/mix) | [BASE64 SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/mix_base64) | [CLASH SUBSCRITION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/clash/mix.yml) |
| VMESS | [NORMAL SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vmess) | [BASE64 SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vmess_base64) | [CLASH SUBSCRITION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/clash/vmess.yml) |
| VLESS | [NORMAL SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vless) | [BASE64 SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vless_base64) | - |
| REALITY | [NORMAL SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/reality) | [BASE64 SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/reality_base64) | - |
| TROJAN | [NORMAL SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/trojan) | [BASE64 SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/trojan_base64) | [CLASH SUBSCRITION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/clash/trojan.yml) |
| ShadowSocks | [NORMAL SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/shadowsocks) | [BASE64 SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/shadowsocks_base64) | [CLASH SUBSCRITION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/clash/ss.yml) |

## Manual Subs Conversion
- If your client does not support the formats that provided here use below services to convert them to your client format (like surfboard)
> Services for online sub conversion: 
- [sub-web-modify](https://sub.v1.mk/)
- [bianyuan](https://bianyuan.xyz/)  

- **If you don't like the groups and rules that are already set, you can simply use bianyuan API like this (ONLY FOR BASE64 SUBSCRIPTION)::**  
> don't use this API for your personal subs! Pls run the subconverter locally
```
https://pub-api-1.bianyuan.xyz/sub?target=(OutputFormat)&url=(SubUrl)&insert=false

For Example:
(OutputFormat) = clash
(SubUrl) = https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/mix_base64

https://pub-api-1.bianyuan.xyz/sub?target=clash&url=https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/mix_base64&insert=false

Now you can use the link above to import the subs into your client
```
## NODE Sources
This project currently utilizes Telegram channels as the source of v2ray nodes.

Telegram Channels utilized in this Project:

| Telegram Channels | Telegram Channels | Telegram Channels |
| -------- | -------- | -------- |
| [V2rayNGn](https://t.me/V2rayNGn) | [free4allVPN](https://t.me/free4allVPN) | [PrivateVPNs](https://t.me/PrivateVPNs) |
| [DirectVPN](https://t.me/DirectVPN) | [ProxyFn](https://t.me/ProxyFn) | [v2ray_outlineir](https://t.me/v2ray_outlineir) |
| [NetAccount](https://t.me/NetAccount) | [oneclickvpnkeys](https://t.me/oneclickvpnkeys) | [daorzadannet](https://t.me/daorzadannet) |
| [Outline_Vpn](https://t.me/Outline_Vpn) | [vpn_xw](https://t.me/vpn_xw) | [prrofile_purple](https://t.me/prrofile_purple) |
| [ShadowSocks_s](https://t.me/ShadowSocks_s) | [azadi_az_inja_migzare](https://t.me/azadi_az_inja_migzare) | [WomanLifeFreedomVPN](https://t.me/WomanLifeFreedomVPN) |
| [internet4iran](https://t.me/internet4iran) | [LegenderY_Servers](https://t.me/LegenderY_Servers) | [vpnfail_v2ray](https://t.me/vpnfail_v2ray) |
| [UnlimitedDev](https://t.me/UnlimitedDev) | [vmessorg](https://t.me/vmessorg) | [v2rayNG_Matsuri](https://t.me/v2rayNG_Matsuri) |
| [v2rayngvpn](https://t.me/v2rayngvpn) | [vpn_ioss](https://t.me/vpn_ioss) | [v2freevpn](https://t.me/v2freevpn) |
| [customv2ray](https://t.me/customv2ray) | [FalconPolV2rayNG](https://t.me/FalconPolV2rayNG) | [Jeyksatan](https://t.me/Jeyksatan) |
| [hassan_saboorii](https://t.me/hassan_saboorii) | [v2rayvmess](https://t.me/v2rayvmess) | [v2rayNGNeT](https://t.me/v2rayNGNeT) |
| [server01012](https://t.me/server01012) | [ShadowProxy66](https://t.me/ShadowProxy66) | [ipV2Ray](https://t.me/ipV2Ray) |
| [kiava](https://t.me/kiava) | [Helix_Servers](https://t.me/Helix_Servers) | [PAINB0Y](https://t.me/PAINB0Y) |
| [VpnProSec](https://t.me/VpnProSec) | [VlessConfig](https://t.me/VlessConfig) | [NIM_VPN_ir](https://t.me/NIM_VPN_ir) |
| [hashmakvpn](https://t.me/hashmakvpn) | [X_Her0](https://t.me/X_Her0) | [Napsternetvirani](https://t.me/Napsternetvirani) |
| [iran_ray](https://t.me/iran_ray) | [INIT1984](https://t.me/INIT1984) | [EXOGAMERS](https://t.me/EXOGAMERS) |
| [ServerNett](https://t.me/ServerNett) | [Pinkpotatocloud](https://t.me/Pinkpotatocloud) | [CloudCityy](https://t.me/CloudCityy) |
| [DarkVPNpro](https://t.me/DarkVPNpro) | [Qv2raychannel](https://t.me/Qv2raychannel) | [xrayzxn](https://t.me/xrayzxn) |
| [shopingv2ray](https://t.me/shopingv2ray) | [xrayproxy](https://t.me/xrayproxy) | [Proxy_PJ](https://t.me/Proxy_PJ) |


## Contribution
If you have a Telegram channel and you want to add it to this project or you know Telegram channels that can be used in this project you can ask for addition in Issues.

## License
This project is licensed under the MIT License - see the LICENSE file for details.
