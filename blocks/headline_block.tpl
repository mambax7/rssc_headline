<{* $Id: headline_block.html,v 1.1 2011/12/29 14:41:31 ohwada Exp $ *}>
<{* change xoopsheadline to rssc_headline $ *}>

<a href="<{$site_url}>" target="_blank"><{$site_name}></a><br>
<{if $image.url != ""}>
<img src="<{$image.url}>" width="<{$image.width|default:88}>" height="<{$image.height|default:31}>" alt="<{$image.title}>"/><br>
    <{/if}>

<ul>
    <{section name=i loop=$items}>
    <{if $items[i].title != ""}>
    <li><a href="<{$xoops_url}>/modules/rssc_headline/index.php?id=<{$site_id}>#<{$items[i].link}>"><{$items[i].title}></a></li>
    <{/if}>
    <{/section}>
</ul>
