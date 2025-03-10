{if $is_added == 1 && $action == 'insert'}
<input type="hidden" name="article_id" id="article_id" value="{$article_id}"/>
<input type="hidden" name="hidden_article_header" id="hidden_article_header" value="{$article_header}"/>
{literal}
<script type="text/javascript">
	$(document).ready(function(){
	{/literal}
		//$('#article_header').html('<<'+$('#hidden_article_header').val()+'>>');
		$('#article_header').html('"'+'{$article_header}'+'"');
		var add_outer_link = $('#add_outer_link').html();
		{literal}
		$.colorbox({html: add_outer_link, open: true, title: 'Добавление внешней ссылки', width:"50%"});

	});
	{/literal}
</script>

{/if}
{if isset($is_moderator)}
	{$frm_search}
{/if}
<div id="list_articles">
{if isset($cur_page)}
<input type="hidden" id="cur_page" value="{$cur_page}"/>
{/if}
<div>{$message}</div>
{if count($articles) > 0}
{if empty($is_moderator)}
{else:}

{/if}


{foreach $articles as $article}
<div class="small_news" id="row_{$article.id}">

{if $article.activity == 2}
<a href="{$article.url}" target="_blank">
{else:}
{if isset($is_moderator)}
<a href="{site_url('moderate/article')}/{$article.id}" target="_blank">
{else:}
<a href="{site_url('raw/article')}/{$article.id}" target="_blank">
{/if}
{/if}
<h2>
{$article.header}
</h2>
</a>
	<p style="text-align: left;">{$article.annotation}</p>

{if empty($is_moderator)}
<p style="text-align: left;" id="status_{$article.id}">Статус: 
{if $article.activity == 0}
Черновик
{elseif $article.activity == 1}
На модерации
{elseif $article.activity == -1}

{if isset($article.rejection_reason)}

<span class="link_info" title="{$article.rejection_reason}">Отвергнуто</span>
{/if}
{else:}
Опубликовано
{/if}
</p>
{else:}
	<p style="text-align: left;">Рубрика: <a href="/{$article.heading}" target="_blank">{$article.heading_name}</a></p>
{/if}

{if empty($is_moderator)}
<p style="text-align: left;">Дата создания: 
{time_change_show_data($article.data_saved)}
</p>
{else:}
{if $article.data_moderated != $article.data_published}}
<p style="text-align: left;">Дата модерации: 
{time_change_show_data($article.data_moderated)}
</p>
{/if}
{/if}
{if empty($is_moderator)}
{if $article.data_last_modified == '0000-00-00 00:00:00'}

{else:}
<p style="text-align: left;">Дата последнего изменения
{time_change_show_data($article.data_last_modified)}
</p>
{/if}

{/if}

{if isset($is_moderator) && $activity == 1}

{else:}
{if $article.data_published == '0000-00-00 00:00:00'}

{else:}
<p style="text-align: left;">Дата публикации: 
{time_change_show_data($article.data_published)}
</p>
{/if}

{/if}
{if isset($is_moderator)}
{if $article.author_name}
<p style="text-align: left;">Имя: 
<a href="{site_url('avtory')}/{$article.username}">{$article.author_name} {$article.family}</a>
</p>
{else:}
<p style="text-align: left;">Логин: 
<a href="{site_url('avtory')}/{$article.username}">{ucfirst($article.username)}</a>
</p>
{/if}
{if $article.rating}
{if $article.activity == 2}
<p style="text-align: left;">Рейтинг:
    <input type="text" id="rating_{$article.id}" value="{$article.rating}" class="input_text" style="width:34px;" />
    <input type="hidden" id="old_rating_{$article.id}" value="{$article.rating}">
    <input type="button" onclick="change_rating_article('{site_url('moderator/change_article_rating')}/{$article.id}','{site_url('')}',{$article.id});return false;" value="Изменить" id="button_rating_{$article.id}"/>
</p>
{if $article.payment || $article.num_visites}
	<p>Начисления:</p>
{if $article.payment}
	<p>За выход из песочницы: {$article.payment} wmr</p>
{/if}
{if $article.num_visites}
	<p>За посещаемость: {$article.num_visites} wmr</p>
{/if}
{/if}
{else:}
<p style="text-align: left;">Рейтинг: {$article.rating}
{/if}

</p>
{else:}

{if $article.activity == 2}
    <p style="text-align: left;">Рейтинг:
    <input type="text" id="rating_{$article.id}" value="0" class="input_text" style="width:34px;" />
     <input type="hidden" id="old_rating_{$article.id}" value="{$article.rating}">
    <input type="button" onclick="change_rating_article('{site_url('moderator/change_article_rating')}/{$article.id}','{site_url('')}',{$article.id});return false;" value="Изменить" id="button_rating_{$article.id}"/>
    </p>
{else:}
<p style="text-align: left;">Рейтинг: 0
</p>
{/if}
{/if}
{if $article.link}
    <p style="text-align: left;">Внешняя ссылка <a href="{$article.link}" target="_blank">{$article.link}</a>
    </p>
{/if}
{if $article.is_special}
    <p style="text-align: left;text-decoration:underline;">Особая статья
    </p>
{/if}
{if isset($is_editor) && $activity == 2}

{else:}
<p style="text-align: left;">Действия:
<a href="{site_url('moderator/edit')}/{$article.id}" title="Редактировать статью" id="edit_{$article.id}" target="_blank">
Редактировать
</a>
&nbsp;
{if isset($is_moderator) && defined('MIRROR_MODE') && time_db_to_mktime($article.data_published) > BEGIN_MIGRATION}
<a href="{site_url('uploads')}/copy_ruavtor.php?article_id={$article.id}" title="Публиковать на Ruavtor.ru" target="_blank">
Публиковать на Ruavtor.ru
</a>
{/if}
</p>
{/if}
{else:}
{if $article.activity != 2}

{if isset($article.outer_link)}
	<p style="text-align: left;">Прикреплена ссылка {$article.outer_link}</p>
{/if}

<p style="text-align: left;" class="actions">
Действия:
<a href="{site_url('publish/update')}/{$article.id}" title="Редактировать статью" id="edit_{$article.id}" target="_blank">
<img src="/{$THEME}/images/icon_edit.png" width="12" height="12" />
</a>
&nbsp;
<a href="{site_url('publish/delete')}/{$article.id}" title="Удалить статью" onclick="delete_article(this.href,'{$article.id}','{$article.activity}');return false;" id="delete_{$article.id}">
<img src="/{$THEME}/images/icon_delete.png" width="12" height="12"/>
</a>
{/if}
&nbsp;
{if $article.activity == 0 || $article.activity == -1}
<a href="{site_url('publish/add')}/{$article.id}" title="Отправить статью на модерацию" onclick="send_moderate(this.href,'{$article.id}','{$article.activity}');return false;" id="send_{$article.id}">
<img src="/{$THEME}/images/icon_add.png" width="12" height="12"/>
</a>

{elseif $article.activity == 1}
<a href="{site_url('publish/cancel')}/{$article.id}" title="Вернуть статью с модерации" onclick="cancel_moderate(this.href,'{$article.id}','{$article.activity}');return false;" id="cancel_{$article.id}">
<img src="/{$THEME}/images/icon_cancel.png" width="12" height="12"/>
</a>
{/if}
</p>
{/if}
</div>
<hr>
{/foreach}
<div class="pagination">
{$paginator}
</div>
{else:}
<div>Раздел пуст</div>
{/if}
</div>
