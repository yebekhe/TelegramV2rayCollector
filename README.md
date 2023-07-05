# V2ray Collector
[![Collector](https://github.com/yebekhe/TelegramV2rayCollector/actions/workflows/php.yml/badge.svg)](https://github.com/yebekhe/TelegramV2rayCollector/actions/workflows/php.yml) [![Channels](https://github.com/yebekhe/TelegramV2rayCollector/actions/workflows/channel_assets.yml/badge.svg)](https://github.com/yebekhe/TelegramV2rayCollector/actions/workflows/channel_assets.yml)

<b>This project is intended for educational purposes only. Any other use of it, including commercial, personal, or non-educational use, is not accepted!</b>

This is a PHP script that collects V2Ray subscription links from various Channels and saves them to different files based on their protocol type (VMess, VLess, Trojan, and Shadowsocks).

## Instructions & Usage

Just import the following subscription link into the corresponding client. Use a client that at least support ss + vless + vmess + trojan.

| CONFIG TYPE | NORMAL SUBSCRIPTION | BASE64 SUBSCRIPTION | CLASH SUBSCRIPTION | CLASH.Meta SUBSCRIPTION |
|---|---|---|---|---|
| MIX of ALL | [NORMAL SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/mix) | [BASE64 SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/mix_base64) | [CLASH SUBSCRIPTION](https://github.com/yebekhe/TelegramV2rayCollector/raw/main/clash/mix.yml) | [CLASH.Meta SUBSCRIPTION](https://github.com/yebekhe/TelegramV2rayCollector/raw/main/meta/mix.yml) |
| VMESS | [NORMAL SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vmess) | [BASE64 SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vmess_base64) | [CLASH SUBSCRIPTION](https://github.com/yebekhe/TelegramV2rayCollector/raw/main/clash/vmess.yml) | [CLASH.Meta SUBSCRIPTION](https://github.com/yebekhe/TelegramV2rayCollector/raw/main/meta/vmess.yml) |
| VLESS | [NORMAL SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vless) | [BASE64 SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vless_base64) | - | [CLASH.Meta SUBSCRIPTION](https://github.com/yebekhe/TelegramV2rayCollector/raw/main/meta/vless.yml) |
| REALITY | [NORMAL SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/reality) | [BASE64 SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/reality_base64) | - | [CLASH.Meta SUBSCRIPTION](https://github.com/yebekhe/TelegramV2rayCollector/raw/main/meta/reality.yml) |
| TROJAN | [NORMAL SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/trojan) | [BASE64 SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/trojan_base64) | [CLASH SUBSCRIPTION](https://github.com/yebekhe/TelegramV2rayCollector/raw/main/clash/trojan.yml) | [CLASH.Meta SUBSCRIPTION](https://github.com/yebekhe/TelegramV2rayCollector/raw/main/meta/trojan.yml) |
| ShadowSocks | [NORMAL SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/shadowsocks) | [BASE64 SUBSCRIPTION](https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/shadowsocks_base64) | [CLASH SUBSCRIPTION](https://github.com/yebekhe/TelegramV2rayCollector/raw/main/clash/shadowsocks.yml) | [CLASH.Meta SUBSCRIPTION](https://github.com/yebekhe/TelegramV2rayCollector/raw/main/meta/shadowsocks.yml) |

## Manual Subs Conversion
- If your client does not support the formats that provided here use below services to convert them to your client format (like surfboard)
> Services for online sub conversion:
- [v2rayse](https://v2rayse.com/en/node-convert)
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
This project currently utilizes Channels as the source of v2ray nodes.

Channels utilized in this Project:

| Channels | Channels | Channels | Channels |
| -------- | -------- | -------- | -------- |
| [V2rayNGn](https://t.me/V2rayNGn) | [free4allVPN](https://t.me/free4allVPN) | [PrivateVPNs](https://t.me/PrivateVPNs) | [V2rayng_Fast](https://t.me/V2rayng_Fast) |
| [DirectVPN](https://t.me/DirectVPN) | [v2rayngvp](https://t.me/v2rayngvp) | [v2ray_outlineir](https://t.me/v2ray_outlineir) | [v2ray_swhil](https://t.me/v2ray_swhil) |
| [NetAccount](https://t.me/NetAccount) | [oneclickvpnkeys](https://t.me/oneclickvpnkeys) | [daorzadannet](https://t.me/daorzadannet) | [LoRd_uL4mo](https://t.me/LoRd_uL4mo) |
| [Outline_Vpn](https://t.me/Outline_Vpn) | [vpn_xw](https://t.me/vpn_xw) | [prrofile_purple](https://t.me/prrofile_purple) | [proxyymeliii](https://t.me/proxyymeliii) |
| [ShadowSocks_s](https://t.me/ShadowSocks_s) | [azadi_az_inja_migzare](https://t.me/azadi_az_inja_migzare) | [WomanLifeFreedomVPN](https://t.me/WomanLifeFreedomVPN) | [MsV2ray](https://t.me/MsV2ray) |
| - | [LegenderY_Servers](https://t.me/LegenderY_Servers) | - | [free_v2rayyy](https://t.me/free_v2rayyy) |
| [UnlimitedDev](https://t.me/UnlimitedDev) | [vmessorg](https://t.me/vmessorg) | [v2rayNG_Matsuri](https://t.me/v2rayNG_Matsuri) | [v2ray1_ng](https://t.me/v2ray1_ng) |
| [v2rayngvpn](https://t.me/v2rayngvpn) | [vpn_ioss](https://t.me/vpn_ioss) | [v2freevpn](https://t.me/v2freevpn) | [vless_vmess](https://t.me/vless_vmess) |
| [customv2ray](https://t.me/customv2ray) | [FalconPolV2rayNG](https://t.me/FalconPolV2rayNG) | - | [MTConfig](https://t.me/MTConfig) |
| - | - | [v2rayNGNeT](https://t.me/v2rayNGNeT) | [PNG_V2RayNG](https://t.me/PNG_V2RayNG) |
| - | [ShadowProxy66](https://t.me/ShadowProxy66) | [ipV2Ray](https://t.me/ipV2Ray) | [v2rayNG_VPNN](https://t.me/v2rayNG_VPNN) |
| [kiava](https://t.me/kiava) | [Helix_Servers](https://t.me/Helix_Servers) | [PAINB0Y](https://t.me/PAINB0Y) | [vmess_vless_v2rayng](https://t.me/vmess_vless_v2rayng) |
| [VpnProSec](https://t.me/VpnProSec) | [VlessConfig](https://t.me/VlessConfig) | [NIM_VPN_ir](https://t.me/NIM_VPN_ir) | - |
| [hashmakvpn](https://t.me/hashmakvpn) | [X_Her0](https://t.me/X_Her0) | - | [Cov2ray](https://t.me/Cov2ray) |
| [iran_ray](https://t.me/iran_ray) | [INIT1984](https://t.me/INIT1984) | - | [V2RayTz](https://t.me/V2RayTz) |
| [ServerNett](https://t.me/ServerNett) | [Pinkpotatocloud](https://t.me/Pinkpotatocloud) | [CloudCityy](https://t.me/CloudCityy) | [VmessProtocol](https://t.me/VmessProtocol) |
| [DarkVPNpro](https://t.me/DarkVPNpro) | [Qv2raychannel](https://t.me/Qv2raychannel) | - | [MehradLearn](https://t.me/MehradLearn) |
| [shopingv2ray](https://t.me/shopingv2ray) | [xrayproxy](https://t.me/xrayproxy) | [Proxy_PJ](https://t.me/Proxy_PJ) | [SafeNet_Server](https://t.me/SafeNet_Server) |
| [ovpn2](https://t.me/ovpn2) | [OutlineVpnOfficial](https://t.me/OutlineVpnOfficial) | - | [TheHotVPN](https://t.me/TheHotVPN) |
| [lrnbymaa](https://t.me/lrnbymaa) | [v2ray_vpn_ir](https://t.me/v2ray_vpn_ir) | [ConfigsHUB](https://t.me/ConfigsHUB) | [freeconfigv2](https://t.me/freeconfigv2) |
| [vpn_tehran](https://t.me/vpn_tehran) | [v2_team](https://t.me/v2_team) | - | [V2rayngninja](https://t.me/V2rayngninja) |
| [iSegaro](https://t.me/iSegaro) | [bright_vpn](https://t.me/bright_vpn) | [talentvpn](https://t.me/talentvpn) | [proxystore11](https://t.me/proxystore11) |
| [yaney_01](https://t.me/yaney_01) | [rayvps](https://t.me/rayvps) | [free1_vpn](https://t.me/free1_vpn) | [Parsashonam](https://t.me/Parsashonam) |


## Contribution
If you have Channel and you want to add it to this project or you know Channels that can be used in this project you can ask for addition in Issues.

## License
This project is licensed under the MIT License - see the LICENSE file for details.
