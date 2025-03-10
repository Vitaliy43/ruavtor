
<div id="list_articles">
	<div id="add_article" style="margin-bottom:15px;text-align:center;">
		<a href="{site_url('moderator/rubric_add')}" style="color:inherit;text-decoration:none;" target="_blank">
			<img width="16" height="16" src="/{$THEME}/images/add.png" style="margin-bottom:-3px;"/>
			<span style="text-decoration:underline;margin-left:-9px;color:#166D66;">Добавить рубрику</span>
		</a>
	</div>
<table width="80%">
	<tr>
		<th>Название</th>
		<th>Тип рубрики</th>
		<th></th>
	</tr>
{foreach $headings as $heading}
	<tr>
		<td width="50%"><a href="{site_url('')}{$heading.name}" target="_blank">{$heading.name_russian}</a></td>
		{if $heading.type_heading == 1}
			<td>Основная</td>
		{elseif $heading.type_heading == 2}
			<td>Особая</td>
		{else:}
			<td>Скрытая</td>		
		{/if}
			<td>
			<a href="{site_url('moderator/rubric_update')}/{$heading.id}" title="Редактировать рубрику" target="_blank" style="text-decoration:none;">
<img src="/{$THEME}/images/icon_add.png" width="12" height="12"/>
</a>
</td>
	</tr>
{/foreach}
</table>
</div>
