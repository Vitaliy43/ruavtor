{literal}
<style type="text/css">
.paginator a {color:black;}
</style>
{/literal}
<h2>{lang('comments_list')}</h2>
{if count($comments) > 0}
<table width="100%" class="sandbox">
<tr>
<th align="left">{lang('article')}</th>
<th align="left">{lang('rubric')}</th>
<th align="left">{lang('comment_text')}</th>
<th align="left">{lang('date')}</th>
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
<div>{lang('rubric_empty')}</div>
{/if}