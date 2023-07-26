<h1 id="v2ray-collector" align="center">V2ray Collector</h1>
<p align="center">
  <a href="https://t.me/v2raycollectorbot">
    <img src="https://img.shields.io/badge/Telegram_Bot-@v2raycollectorbot-darkblue?style=flat&logo=telegram" alt="Telegram Bot">
  </a>
  <a href="https://scrutinizer-ci.com/g/yebekhe/TelegramV2rayCollector/build-status/main">
    <img src="https://scrutinizer-ci.com/g/yebekhe/TelegramV2rayCollector/badges/build.png?b=main" alt="Build Status">
  </a>
  <a href="https://scrutinizer-ci.com/code-intelligence">
    <img src="https://scrutinizer-ci.com/g/yebekhe/TelegramV2rayCollector/badges/code-intelligence.svg?b=main" alt="Code Intelligence Status">
  </a>
  <a href="https://scrutinizer-ci.com/g/yebekhe/TelegramV2rayCollector/?branch=main">
    <img src="https://img.shields.io/scrutinizer/quality/g/yebekhe/TelegramV2rayCollector?style=flat&logo=scrutinizerci" alt="Scrutinizer Code Quality">
  </a>
</p>
<p align="center">
  <img src="https://img.shields.io/github/languages/top/yebekhe/TelegramV2rayCollector?color=5D6D7E" alt="Github Top Language">
  <img src="https://img.shields.io/github/license/yebekhe/TelegramV2rayCollector?color=5D6D7E" alt="GitHub license">
  <img alt="GitHub Repo stars" src="https://img.shields.io/github/stars/yebekhe/TelegramV2rayCollector">
  <img alt="GitHub commit activity (branch)" src="https://img.shields.io/github/commit-activity/t/yebekhe/TelegramV2rayCollector">
</p>
<p align="center">
  <b>This project is intended for educational purposes only. Any other use of it, including commercial, personal, or non-educational use, is not accepted!</b>
</p>
<p align="center">This is a PHP script that collects V2Ray subscription links from various Channels and saves them to different files based on their protocol type (VMess, VLess, Trojan, and Shadowsocks).</p>
<h2 id="instructions-usage">Instructions &amp; Usage</h2>
<p>Just import the following subscription link into the corresponding client. Use a client that at least support ss + vless + vmess + trojan.</p>
<table>
  <thead>
    <tr>
      <th>CONFIG TYPE</th>
      <th>NORMAL SUBSCRIPTION</th>
      <th>BASE64 SUBSCRIPTION</th>
      <th>SINGBOX SUBSCRIPTION</th>
      <th>CLASH SUBSCRIPTION</th>
      <th>CLASH.Meta SUBSCRIPTION</th>
      <th>SURFBOARD SUSCRIPTION</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>MIX of ALL</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/mix">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/mix_base64">BASE64 SUBSCRIPTION</a>
      </td>
      <td>
        -
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/clash/mix.yml">CLASH SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/meta/mix.yml">CLASH.Meta SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/surfboard/mix">SURFBOARD SUBSCRIPTION</a>
      </td>
    </tr>
    <tr>
      <td>Donated Servers</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/donated">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/donated_base64">BASE64 SUBSCRIPTION</a>
      </td>
      <td>
        -
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/clash/donated.yml">CLASH SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/meta/donated.yml">CLASH.Meta SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/surfboard/donated">SURFBOARD SUBSCRIPTION</a>
      </td>
    </tr>
    <tr>
      <td>VMESS</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vmess">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vmess_base64">BASE64 SUBSCRIPTION</a>
      </td>
      <td>
        -
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/clash/vmess.yml">CLASH SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/meta/vmess.yml">CLASH.Meta SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/surfboard/vmess">SURFBOARD SUBSCRIPTION</a>
      </td>
    </tr>
    <tr>
      <td>VLESS</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vless">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/vless_base64">BASE64 SUBSCRIPTION</a>
      </td>
      <td>
        -
      </td>
      <td>-</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/meta/vless.yml">CLASH.Meta SUBSCRIPTION</a>
      </td>
      <td>-</td>
    </tr>
    <tr>
      <td>REALITY</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/reality">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/reality_base64">BASE64 SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/reality.json">NEKOBOX 1.1.7 and BELLOW</a>
        <br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox_new.json">NEKOBOX 1.1.8</a>
        <br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/sfi_sfa.json">SFI and SFA</a>
      </td>
      <td>-</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/meta/reality.yml">CLASH.Meta SUBSCRIPTION</a>
      </td>
      <td>-</td>
    </tr>
    <tr>
      <td>TROJAN</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/trojan">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/trojan_base64">BASE64 SUBSCRIPTION</a>
      </td>
      <td>
        -
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/clash/trojan.yml">CLASH SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/meta/trojan.yml">CLASH.Meta SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main//surfboard/trojan">SURFBOARD SUBSCRIPTION</a>
      </td>
    </tr>
    <tr>
      <td>ShadowSocks</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/shadowsocks">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/shadowsocks_base64">BASE64 SUBSCRIPTION</a>
      </td>
      <td>
        -
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/clash/shadowsocks.yml">CLASH SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/meta/shadowsocks.yml">CLASH.Meta SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/surfboard/shadowsocks">SURFBOARD SUBSCRIPTION</a>
      </td>
    </tr>
  </tbody>
</table>
<p>Also you can use following subscription link generated by my other project V2Hub witch tests some v2ray subscriptions and merge active configs.</p>
<table>
  <thead>
    <tr>
      <th>CONFIG TYPE</th>
      <th>NORMAL SUBSCRIPTION</th>
      <th>BASE64 SUBSCRIPTION</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Merged</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/merged">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/merged_base64">BASE64 SUBSCRIPTION</a>
      </td>
    </tr>
    <tr>
      <td>VMESS</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/Split/Normal/vmess">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/Split/Base64/vmess">BASE64 SUBSCRIPTION</a>
      </td>
      </tr>
    <tr>
      <td>VLESS</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/Split/Normal/vless">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/Split/Base64/vless">BASE64 SUBSCRIPTION</a>
      </td>
      </tr>
    <tr>
      <td>REALITY</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/Split/Normal/reality">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/Split/Base64/reality">BASE64 SUBSCRIPTION</a>
      </td>
      </tr>
    <tr>
      <td>TROJAN</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/Split/Normal/trojan">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/Split/Base64/trojan">BASE64 SUBSCRIPTION</a>
      </td>
      </tr>
    <tr>
      <td>ShadowSocks</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/Split/Normal/shadowsocks">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/Split/Base64/shadowsocks">BASE64 SUBSCRIPTION</a>
      </td>
      </tr>
  </tbody>
</table>
<h2 id="manual-subs-conversion">Manual Subs Conversion</h2>
<ul>
  <li>If your client does not support the formats that provided here use below services to convert them to your client format (like surfboard) <blockquote>
      <p>Services for online sub conversion:</p>
    </blockquote>
  </li>
  <li>
    <a href="https://v2rayse.com/en/node-convert">v2rayse</a>
  </li>
  <li>
    <a href="https://sub.v1.mk/">sub-web-modify</a>
  </li>
  <li>
    <p>
      <a href="https://bianyuan.xyz/">bianyuan</a>
    </p>
  </li>
  <li>
    <p>
      <strong>If you don&#39;t like the groups and rules that are already set, you can simply use bianyuan API like this (ONLY FOR BASE64 SUBSCRIPTION)::</strong>
    </p>
    <blockquote>
      <p>don&#39;t use this API for your personal subs! Pls run the subconverter locally ``` <a href="https://pub-api-1.bianyuan.xyz/sub?target=(OutputFormat)&amp;url=(SubUrl)&amp;insert=false">https://pub-api-1.bianyuan.xyz/sub?target=(OutputFormat)&amp;url=(SubUrl)&amp;insert=false</a>
      </p>
    </blockquote>
  </li>
</ul>
<p>For Example: (OutputFormat) = clash (SubUrl) = <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/mix_base64">https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/mix_base64</a>
</p>
<p>
  <a href="https://pub-api-1.bianyuan.xyz/sub?target=clash&amp;url=https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/mix_base64&amp;insert=false">https://pub-api-1.bianyuan.xyz/sub?target=clash&amp;url=https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/mix_base64&amp;insert=false</a>
</p>
<p>Now you can use the link above to import the subs into your client ```</p>
<h2 id="node-sources">NODE Sources</h2>
<p>This project currently utilizes Channels as the source of v2ray nodes.</p>
<p>Channels utilized in this Project:</p>
<table>
  <thead>
    <tr>
      <th>Channels</th>
      <th>Channels</th>
      <th>Channels</th>
      <th>Channels</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
        <a href="https://t.me/V2rayNGn">V2rayNGn</a>
      </td>
      <td>
        <a href="https://t.me/free4allVPN">free4allVPN</a>
      </td>
      <td>
        <a href="https://t.me/PrivateVPNs">PrivateVPNs</a>
      </td>
      <td>
        <a href="https://t.me/V2rayng_Fast">V2rayng_Fast</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="https://t.me/DirectVPN">DirectVPN</a>
      </td>
      <td>
        <a href="https://t.me/v2rayngvp">v2rayngvp</a>
      </td>
      <td>
        <a href="https://t.me/v2ray_outlineir">v2ray_outlineir</a>
      </td>
      <td>
        <a href="https://t.me/v2ray_swhil">v2ray_swhil</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="https://t.me/NetAccount">NetAccount</a>
      </td>
      <td>
        <a href="https://t.me/oneclickvpnkeys">oneclickvpnkeys</a>
      </td>
      <td>
        <a href="https://t.me/daorzadannet">daorzadannet</a>
      </td>
      <td>
        <a href="https://t.me/LoRd_uL4mo">LoRd_uL4mo</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="https://t.me/Outline_Vpn">Outline_Vpn</a>
      </td>
      <td>
        <a href="https://t.me/vpn_xw">vpn_xw</a>
      </td>
      <td>
        <a href="https://t.me/prrofile_purple">prrofile_purple</a>
      </td>
      <td>
        <a href="https://t.me/proxyymeliii">proxyymeliii</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="https://t.me/ShadowSocks_s">ShadowSocks_s</a>
      </td>
      <td>
        <a href="https://t.me/azadi_az_inja_migzare">azadi_az_inja_migzare</a>
      </td>
      <td>
        <a href="https://t.me/WomanLifeFreedomVPN">WomanLifeFreedomVPN</a>
      </td>
      <td>
        <a href="https://t.me/MsV2ray">MsV2ray</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="https://t.me/Hope_Net">Hope_Net</a>
      </td>
      <td>
        <a href="https://t.me/VPNCUSTOMIZE">VPNCUSTOMIZE</a>
      </td>
      <td>
        <a href="https://t.me/VPNCLOP">VPNCLOP</a>
      </td>
      <td>
        <a href="https://t.me/free_v2rayyy">free_v2rayyy</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="https://t.me/UnlimitedDev">UnlimitedDev</a>
      </td>
      <td>
        <a href="https://t.me/vmessorg">vmessorg</a>
      </td>
      <td>
        <a href="https://t.me/v2rayNG_Matsuri">v2rayNG_Matsuri</a>
      </td>
      <td>
        <a href="https://t.me/v2ray1_ng">v2ray1_ng</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="https://t.me/v2rayngvpn">v2rayngvpn</a>
      </td>
      <td>
        <a href="https://t.me/vpn_ioss">vpn_ioss</a>
      </td>
      <td>
        <a href="https://t.me/MoV2ray">MoV2ray</a>
      </td>
      <td>
        <a href="https://t.me/vless_vmess">vless_vmess</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="https://t.me/customv2ray">customv2ray</a>
      </td>
      <td>
        <a href="https://t.me/FalconPolV2rayNG">FalconPolV2rayNG</a>
      </td>
      <td>
        <a href="https://t.me/fnet00">fnet00</a>
      </td>
      <td>
        <a href="https://t.me/MTConfig">MTConfig</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="https://t.me/V2pedia">V2pedia</a>
      </td>
      <td>
        <a href="https://t.me/molovpn">molovpn</a>
      </td>
      <td>
        <a href="https://t.me/v2rayNGNeT">v2rayNGNeT</a>
      </td>
      <td>
        <a href="https://t.me/PNG_V2RayNG">PNG_V2RayNG</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="https://t.me/melov2ray">melov2ray</a>
      </td>
      <td>
        <a href="https://t.me/ShadowProxy66">ShadowProxy66</a>
      </td>
      <td>
        <a href="https://t.me/ipV2Ray">ipV2Ray</a>
      </td>
      <td>
        <a href="https://t.me/v2rayNG_VPNN">v2rayNG_VPNN</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="https://t.me/kiava">kiava</a>
      </td>
      <td>
        <a href="https://t.me/Helix_Servers">Helix_Servers</a>
      </td>
      <td>
        <a href="https://t.me/PAINB0Y">PAINB0Y</a>
      </td>
      <td>
        <a href="https://t.me/vmess_vless_v2rayng">vmess_vless_v2rayng</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="https://t.me/VpnProSec">VpnProSec</a>
      </td>
      <td>
        <a href="https://t.me/VlessConfig">VlessConfig</a>
      </td>
      <td>
        <a href="https://t.me/NIM_VPN_ir">NIM_VPN_ir</a>
      </td>
      <td>
        <a href="https://t.me/polproxy">polproxy</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="https://t.me/hashmakvpn">hashmakvpn</a>
      </td>
      <td>
        <a href="https://t.me/Royalping_ir">Royalping_ir</a>
      </td>
      <td>
        <a href="https://t.me/Outlinev2rayNG">Outlinev2rayNG</a>
      </td>
      <td>
        <a href="https://t.me/Cov2ray">Cov2ray</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="https://t.me/iran_ray">iran_ray</a>
      </td>
      <td>
        <a href="https://t.me/INIT1984">INIT1984</a>
      </td>
      <td>
        <a href="https://t.me/iP_CF">iP_CF</a>
      </td>
      <td>
        <a href="https://t.me/V2RayTz">V2RayTz</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="https://t.me/ServerNett">ServerNett</a>
      </td>
      <td>
        <a href="https://t.me/Pinkpotatocloud">Pinkpotatocloud</a>
      </td>
      <td>
        <a href="https://t.me/CloudCityy">CloudCityy</a>
      </td>
      <td>
        <a href="https://t.me/VmessProtocol">VmessProtocol</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="https://t.me/v2rayng_vpnrog">v2rayng_vpnrog</a>
      </td>
      <td>
        <a href="https://t.me/Qv2raychannel">Qv2raychannel</a>
      </td>
      <td>
        <a href="https://t.me/v2rayng_config_amin">v2rayng_config_amin</a>
      </td>
      <td>
        <a href="https://t.me/MehradLearn">MehradLearn</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="https://t.me/shopingv2ray">shopingv2ray</a>
      </td>
      <td>
        <a href="https://t.me/xrayproxy">xrayproxy</a>
      </td>
      <td>
        <a href="https://t.me/Proxy_PJ">Proxy_PJ</a>
      </td>
      <td>
        <a href="https://t.me/SafeNet_Server">SafeNet_Server</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="https://t.me/ovpn2">ovpn2</a>
      </td>
      <td>
        <a href="https://t.me/OutlineVpnOfficial">OutlineVpnOfficial</a>
      </td>
      <td>-</td>
      <td>-</td>
    </tr>
    <tr>
      <td>
        <a href="https://t.me/lrnbymaa">lrnbymaa</a>
      </td>
      <td>
        <a href="https://t.me/v2ray_vpn_ir">v2ray_vpn_ir</a>
      </td>
      <td>
        <a href="https://t.me/ConfigsHUB">ConfigsHUB</a>
      </td>
      <td>
        <a href="https://t.me/freeconfigv2">freeconfigv2</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="https://t.me/vpn_tehran">vpn_tehran</a>
      </td>
      <td>
        <a href="https://t.me/v2_team">v2_team</a>
      </td>
      <td>-</td>
      <td>
        <a href="https://t.me/V2rayngninja">V2rayngninja</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="https://t.me/iSegaro">iSegaro</a>
      </td>
      <td>
        <a href="https://t.me/bright_vpn">bright_vpn</a>
      </td>
      <td>
        <a href="https://t.me/talentvpn">talentvpn</a>
      </td>
      <td>
        <a href="https://t.me/proxystore11">proxystore11</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="https://t.me/yaney_01">yaney_01</a>
      </td>
      <td>
        <a href="https://t.me/rayvps">rayvps</a>
      </td>
      <td>
        <a href="https://t.me/free1_vpn">free1_vpn</a>
      </td>
      <td>
        <a href="https://t.me/Parsashonam">Parsashonam</a>
      </td>
    </tr>
  </tbody>
</table>
<h2 id="contribution">Contribution</h2>
<p>If you have Channel and you want to add it to this project or you know Channels that can be used in this project you can ask for addition in Issues.</p>
<h2 id="license">License</h2>
<p>This project is licensed under the MIT License - see the LICENSE file for details.</p>
