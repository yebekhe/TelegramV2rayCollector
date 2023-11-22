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
      <th colspan="2">SINGBOX SUBSCRIPTION</th>
      <th>CLASH SUBSCRIPTION</th>
      <th>CLASH.Meta SUBSCRIPTION</th>
      <th>SURFBOARD SUSCRIPTION</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>MIX of ALL</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/normal/mix">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/base64/mix">BASE64 SUBSCRIPTION</a>
      </td>
      <td>
        <h6>COUNRTY BASED</h2>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/117/mix.json">NEKOBOX Until 1.1.7</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/118/mix.json">NEKOBOX 1.1.8</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/sfasfi/mix.json">SFI/SFA</a>
      </td>
      <td>
        <h6>NO CATEGORIZED</h2>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/117/mixLite.json">NEKOBOX Until 1.1.7</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/118/mixLite.json">NEKOBOX 1.1.8</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/sfasfi/mixLite.json">SFI/SFA</a>
      </td>
      <td>
        <a href="https://yebekhe.link/api/toClash/?url=https%3A%2F%2Fraw.githubusercontent.com%2Fyebekhe%2FTelegramV2rayCollector%2Fmain%2Fsub%2Fbase64%2Fmix&type=clash&process=full">CLASH SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://yebekhe.link/api/toClash/?url=https%3A%2F%2Fraw.githubusercontent.com%2Fyebekhe%2FTelegramV2rayCollector%2Fmain%2Fsub%2Fbase64%2Fmix&type=meta&process=full">CLASH.Meta SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://yebekhe.link/api/toClash/?url=https%3A%2F%2Fraw.githubusercontent.com%2Fyebekhe%2FTelegramV2rayCollector%2Fmain%2Fsub%2Fbase64%2Fmix&type=surfboard&process=full">SURFBOARD SUBSCRIPTION</a>
      </td>
    </tr>
    <tr>
      <td>Donated Servers</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/normal/donated">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/base64/donated">BASE64 SUBSCRIPTION</a>
      </td>
      <td>
        <h5>CHECK IN MIX AND REALITY</h5>
      </td>
      <td>
        <h5>CHECK IN MIX AND REALITY</h5>
      </td>
      <td>
        -
      </td>
      <td>
        <a href="https://yebekhe.link/api/toClash/?url=https%3A%2F%2Fraw.githubusercontent.com%2Fyebekhe%2FTelegramV2rayCollector%2Fmain%2Fsub%2Fbase64%2Fdonated&type=meta&process=full">CLASH.Meta SUBSCRIPTION</a>
      </td>
      <td>
        -
      </td>
    </tr>
    <tr>
      <td>VMESS</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/normal/vmess">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/base64/vmess">BASE64 SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/117/vmess.json">NEKOBOX Until 1.1.7</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/118/vmess.json">NEKOBOX 1.1.8</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/sfasfi/vmess.json">SFI/SFA</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/117/vmessLite.json">NEKOBOX Until 1.1.7</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/118/vmessLite.json">NEKOBOX 1.1.8</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/sfasfi/vmessLite.json">SFI/SFA</a>
      </td>
      <td>
        <a href="https://yebekhe.link/api/toClash/?url=https%3A%2F%2Fraw.githubusercontent.com%2Fyebekhe%2FTelegramV2rayCollector%2Fmain%2Fsub%2Fbase64%2Fvmess&type=clash&process=full">CLASH SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://yebekhe.link/api/toClash/?url=https%3A%2F%2Fraw.githubusercontent.com%2Fyebekhe%2FTelegramV2rayCollector%2Fmain%2Fsub%2Fbase64%2Fvmess&type=meta&process=full">CLASH.Meta SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://yebekhe.link/api/toClash/?url=https%3A%2F%2Fraw.githubusercontent.com%2Fyebekhe%2FTelegramV2rayCollector%2Fmain%2Fsub%2Fbase64%2Fvmess&type=surfboard&process=full">SURFBOARD SUBSCRIPTION</a>
      </td>
    </tr>
    <tr>
      <td>VLESS</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/normal/vless">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/base64/vless">BASE64 SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/117/vless.json">NEKOBOX Until 1.1.7</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/118/vless.json">NEKOBOX 1.1.8</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/sfasfi/vless.json">SFI/SFA</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/117/vlessLite.json">NEKOBOX Until 1.1.7</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/118/vlessLite.json">NEKOBOX 1.1.8</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/sfasfi/vlessLite.json">SFI/SFA</a>
      </td>
      <td>-</td>
      <td>
        <a href="https://yebekhe.link/api/toClash/?url=https%3A%2F%2Fraw.githubusercontent.com%2Fyebekhe%2FTelegramV2rayCollector%2Fmain%2Fsub%2Fbase64%2Fvless&type=meta&process=full">CLASH.Meta SUBSCRIPTION</a>
      </td>
      <td>-</td>
    </tr>
    <tr>
      <td>REALITY</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/normal/reality">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/base64/reality">BASE64 SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/117/reality.json">NEKOBOX Until 1.1.7</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/118/reality.json">NEKOBOX 1.1.8</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/sfasfi/reality.json">SFI/SFA</a><br><br>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/117/realityLite.json">NEKOBOX Until 1.1.7</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/118/realityLite.json">NEKOBOX 1.1.8</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/sfasfi/realityLite.json">SFI/SFA</a>
      </td>
      <td>-</td>
      <td>
        <a href="https://yebekhe.link/api/toClash/?url=https%3A%2F%2Fraw.githubusercontent.com%2Fyebekhe%2FTelegramV2rayCollector%2Fmain%2Fsub%2Fbase64%2Freality&type=meta&process=full">CLASH.Meta SUBSCRIPTION</a>
      </td>
      <td>-</td>
    </tr>
    <tr>
      <td>TROJAN</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/normal/trojan">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/base64/trojan">BASE64 SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/117/trojan.json">NEKOBOX Until 1.1.7</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/118/trojan.json">NEKOBOX 1.1.8</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/sfasfi/trojan.json">SFI/SFA</a><br><br>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/117/trojanLite.json">NEKOBOX Until 1.1.7</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/118/trojanLite.json">NEKOBOX 1.1.8</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/sfasfi/trojanLite.json">SFI/SFA</a><br><br>
      </td>
      <td>
        <a href="https://yebekhe.link/api/toClash/?url=https%3A%2F%2Fraw.githubusercontent.com%2Fyebekhe%2FTelegramV2rayCollector%2Fmain%2Fsub%2Fbase64%2Ftrojan&type=clash&process=full">CLASH SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://yebekhe.link/api/toClash/?url=https%3A%2F%2Fraw.githubusercontent.com%2Fyebekhe%2FTelegramV2rayCollector%2Fmain%2Fsub%2Fbase64%2Ftrojan&type=meta&process=full">CLASH.Meta SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://yebekhe.link/api/toClash/?url=https%3A%2F%2Fraw.githubusercontent.com%2Fyebekhe%2FTelegramV2rayCollector%2Fmain%2Fsub%2Fbase64%2Ftrojan&type=surfboard&process=full">SURFBOARD SUBSCRIPTION</a>
      </td>
    </tr>
    <tr>
      <td>ShadowSocks</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/normal/shadowsocks">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/sub/base64/shadowsocks">BASE64 SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/117/shadowsocks.json">NEKOBOX Until 1.1.7</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/118/shadowsocks.json">NEKOBOX 1.1.8</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/sfasfi/shadowsocks.json">SFI/SFA</a><br><br>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/117/shadowsocksLite.json">NEKOBOX Until 1.1.7</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/nekobox/118/shadowsocksLite.json">NEKOBOX 1.1.8</a><br><br>
        <a href="https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/singbox/sfasfi/shadowsocksLite.json">SFI/SFA</a><br><br>
      </td>
      <td>
        <a href="https://yebekhe.link/api/toClash/?url=https%3A%2F%2Fraw.githubusercontent.com%2Fyebekhe%2FTelegramV2rayCollector%2Fmain%2Fsub%2Fbase64%2Fshadowsocks&type=clash&process=full">CLASH SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://yebekhe.link/api/toClash/?url=https%3A%2F%2Fraw.githubusercontent.com%2Fyebekhe%2FTelegramV2rayCollector%2Fmain%2Fsub%2Fbase64%2Fshadowsocks&type=meta&process=full">CLASH.Meta SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://yebekhe.link/api/toClash/?url=https%3A%2F%2Fraw.githubusercontent.com%2Fyebekhe%2FTelegramV2rayCollector%2Fmain%2Fsub%2Fbase64%2Fshadowsocks&type=surfboard&process=full">SURFBOARD SUBSCRIPTION</a>
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

<h2 id="contribution">Contribution</h2>
<p>If you have Channel and you want to add it to this project or you know Channels that can be used in this project you can ask for addition in Issues.</p>
<h2 id="license">License</h2>
<p>This project is licensed under the MIT License - see the LICENSE file for details.</p>
