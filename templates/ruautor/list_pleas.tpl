{literal}
<style type="text/css">
.paginator a {color:black;}
</style>
{/literal}
<h2>Список жалоб</h2>
{if count($pleas) > 0}
<table width="100%" class="sandbox" cellpadding="4" cellspacing="2" id="list_pleas">
<tr>
<th align="left">Комментарий</th>
<th align="left">Пользователь</th>
<th align="left">Текст жалобы</th>
<th align="left">Дата</th>
<th align="left">Статус</th>
<th align="left"></th>
</tr>
{foreach $pleas as $plea}
<tr>
<td id="comment_{$plea.id}">
{if (mb_strlen($plea['comment']) >=30 ):
$comment = mb_substr($plea['comment'],0,30).'...';
else:
$comment = $plea['comment'];
endif;
}

<a href="{$plea.article_url}#comment_{$plea.comment_id}" title="{$plea.comment}" target="_blank">{$comment}</a>
</td>
<td id="username_{$plea.id}">
<a href="{site_url('avtory')}/{$plea.username}" target="_blank">
{ucfirst($plea.username)}
</a>
</td>
<td id="plea_{$plea.id}">
{if (mb_strlen($plea['plea']) >=50 ):
$text_plea = mb_substr($plea['plea'],0,50).'...';
else:
$text_plea = $plea['plea'];
endif;
}
<span title="{$plea.plea}">
{$text_plea}
</span>
</td>
<td nowrap="" id="time_{$plea.id}">
{time_change_show_data($plea.data)}
</td>
<td nowrap="" id="status_plea_{$plea.id}">
{if $plea.considered == 0}
Не рассмотрена
{else:}
Рассмотрена
{/if}
</td>
<td align="right" id="link_reply_{$plea.id}">
{if $plea.considered == 0}
<a href="#reply_plea" title="Рассмотреть жалобу" onclick="reply_plea_modal(this.href,{$plea.id},'{site_url('')}');return false;" id="consider_plea_{$plea.id}" class="reply_modal">
<img src="/{$THEME}/images/icon_add.png" width="12" height="12"/>
</a>
{else:}
<img src="/{$THEME}/images/reply.png" width="12" height="12"/>
{/if}
</td>
</tr>
{/foreach}
</table>
<div class="paginator">
{$paginator}
</div>
{else:}
<div>Раздел пуст</div>
{/if}