{literal}
<style type="text/css">
.article_content {border: 1px solid #8C7E7E;}
</style>
{/literal}
<div class="article_content">
{$content}
</div>
{if isset($is_moderator)}
<div style="margin-bottom:10px;"><a href="{site_url('private/update')}/{$id}" target="_blank">Править статью</a></div>
{/if}
<br>
