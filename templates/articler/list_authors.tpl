{literal}
<style type="text/css">
.paginator a {color:black;}
</style>
{/literal}
{if count($authors) > 0}
<div>
<table border="0" align="left" width="100%">
<tbody>
<tr>
<td><span style="color: #000000; font-size: x-small; background-color: #c0c0c0;"><strong>&nbsp;№</strong></span><br /> <span style="color: #000000; font-size: x-small; background-color: #c0c0c0;"><strong>п/п&nbsp;</strong></span></td>
<td style="text-align: center;"><span style="color: #000000; background-color: #c0c0c0;"><strong><span style="font-size: small;">&nbsp;Ник&nbsp;</span></strong></span></td>
<td style="text-align: center;"><span style="color: #000000; background-color: #c0c0c0;"><strong><span style="font-size: small;">&nbsp;Имя&nbsp;</span></strong></span></td>
<td><span style="color: #000000; background-color: #c0c0c0;"><strong><span style="font-size: small;">Кол-во статей</span></strong></span></td>
<td><span style="font-size: small; color: #000000; background-color: #c0c0c0;"><strong>&nbsp;Рейтинг&nbsp;</strong></span></td>
<td><span style="background-color: #c0c0c0;"><strong><span style="font-size: small; color: #000000;">&nbsp;Статус&nbsp;</span></strong></span></td>
</tr>
{$counter = 1}
{foreach $authors as $author}
<tr>
<td style="text-align: center;"><span style="color: #008000;">{$counter}</span></td>
<td><span style="color: #339966;">&nbsp;<a class="url fn n" title="Посмотреть все записи автора {$author.username}" href="{site_url('avtory')}/{$author.username}" target="_blank">{$author.username}</a></span></td>
<td><strong><span style="color: #008000;">{$author.name} {$author.family}</span></strong></td>
<td style="text-align: center;"><strong><span style="color: #008000;">{$author.num_articles}</span></strong></td>
<td style="text-align: center;"><span style="color: #008000;">{$author.author_rating}</span></td>
<td style="text-align: center;"><span style="color: #008000;">{$author.status}</span></td>
</tr>
{$counter ++}
{/foreach}
</tbody>
</table>
<div class="pagination" align="left">
{$paginator}
</div>
</div>
{else:}
<div>Раздел пуст</div>
{/if}
