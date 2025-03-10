
<div id="list_articles">
	<div id="add_article" style="margin-bottom:15px;text-align:center;">
		<a href="{site_url('moderator/giventopic/add')}" style="color:inherit;text-decoration:none;" target="_blank">
			<img width="16" height="16" src="/{$THEME}/images/add.png" style="margin-bottom:-3px;"/>
			<span style="text-decoration:underline;margin-left:-9px;color:#166D66;">Добавить тему</span>
		</a>
	</div>
{if count($topics) > 0}
<table width="100%">
	<tr>
		<th>Название</th>
		<th>Рубрика</th>
		<th colspan="2" nowrap="">Бронь</th>
		<th>Опубликована</th>
		<th></th>
	</tr>
{foreach $topics as $topic}
	<tr id="row_{$topic.id}">
		<td>{$topic.header}</td>
			<td>{$topic.name_russian}</td>		
			<td colspan="2">
				{if $topic.booking}
					{if !$topic.used}
					<a href="{site_url('avtory')}/{$topic.username}" target="_blank">{$topic.name} {$topic.family}</a>
					<span style="margin-left:10px;">До {time_change_show_data($topic.end_booking)}</span>
					{/if}
				{else:}
					{if !$topic.used}
					Свободна
					{/if}
				{/if}
			</td>
			<td>
			{if $topic.used}
				Да
			{else:}
				Нет
			{/if}
			</td>
			<td nowrap="">
			<a href="{site_url('moderator/giventopic/update')}/{$topic.id}" title="Редактировать тему" target="_blank" style="text-decoration:none;">
<img src="/{$THEME}/images/icon_add.png" width="12" height="12"/>
</a>
&nbsp;
<a href="{site_url('moderator/giventopic/delete')}/{$topic.id}" title="Удалить тему" style="text-decoration:none;" onclick="delete_giventopic(this,{$topic.id});return false;" id="delete_{$topic.id}">
<img src="/{$THEME}/images/icon_delete.png" width="12" height="12"/>
</a>
</td>
	</tr>
{/foreach}
</table>
{else:}
	<div>Раздел пуст</div>
{/if}
</div>
