{literal}
<style type="text/css">
.paginator a {color:black;}
</style>
{/literal}
<h2>Список комментариев</h2>
{if count($comments) > 0}
<table width="100%" class="sandbox">
<tr>
<th align="left">Статья</th>
<th align="left">Раздел</th>
<th align="left">Текст комментария</th>
<th align="left">Дата</th>
</tr>
{foreach $comments as $comment}
<tr>
<td>
<a href="{$comment.article_url}#comment_{$comment.id}">{$comment.article_header}</a>
</td>
<td>
<a href="{site_url('')}{$comment.heading_name}">
{$comment.heading_name_russian}
</a>
</td>
<td>
{$comment.comment}
</td>
<td>
{time_change_show_data($comment.data)}
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