
<div id="list_articles">
	{if isset($message)}
	<div>{$message}</div>
	{/if}
	<div id="add_article" style="margin-bottom:15px;text-align:center;">
		<a href="{site_url('private/add')}" style="color:inherit;text-decoration:none;" target="_blank">
			<img width="16" height="16" src="/{$THEME}/images/add.png" style="margin-bottom:-3px;"/>
			<span style="text-decoration:underline;margin-left:-9px;color:#166D66;">Добавить материал</span>
		</a>
	</div>
{if count($articles) > 0}
{foreach $articles as $article}
<div class="small_news" id="row_{$article.id}">
<a href="{$article.url}" target="_blank">
<h2>
{$article.header}
</h2>
</a>
{if $article.annotation}
	<p style="text-align: left;">{$article.annotation}</p>
{/if}
<p style="text-align: left;">Дата создания: 
{time_change_show_data($article.data_added)}
</p>
{if $article.data_last_modified != '0000-00-00 00:00:00'}
	<p style="text-align: left;">Дата изменения: 
	{time_change_show_data($article.data_last_modified)}
	</p>
{/if}

<p style="text-align: left;">Действия:
<a href="{site_url('private/update')}/{$article.id}" title="Редактировать статью" id="edit_{$article.id}" target="_blank" style="text-decoration:none;">
<img src="/{$THEME}/images/icon_add.png" width="12" height="12"/>
</a>
&nbsp;
<a href="{site_url('private/delete')}/{$article.id}" title="Удалить статью" onclick="delete_private_article(this.href,'{$article.id}');return false;" id="delete_{$article.id}">
<img src="/{$THEME}/images/icon_delete.png" width="12" height="12"/>
</a>
</p>
</div>
{/foreach}
<div class="pagination">
{$paginator}
</div>
{else:}
	<div>Раздел пуст</div>
{/if}
</div>