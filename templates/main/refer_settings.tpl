
<div id="list_articles">
	{if isset($message)}
	<div>{$message}</div>
	{/if}
	
{if count($exceptions) > 0}
<h2>
Страницы исключений
</h2>
<div id="add_article" style="margin-bottom:15px;text-align:center;">
		<a href="{site_url('moderator/refer_exceptions/add')}" style="color:inherit;text-decoration:none;">
			<img width="16" height="16" src="/{$THEME}/images/add.png" style="margin-bottom:-3px;"/>
			<span style="text-decoration:underline;margin-left:-9px;color:#166D66;">Добавить исключение</span>
		</a>
	</div>
<table width="50%">
	<tr>
		<th>Ссылка</th>
		<th>Описание</th>
		<th></th>
	</tr>
	{foreach $exceptions as $exception}
		<tr id="row_{$exception.id}">
		
			<td>{$exception.page}</td>
			<td>{$exception.description}</td>
			<td>
				<a href="{site_url('moderator/refer_exceptions/delete')}/{$exception.id}" title="Удалить исключение" onclick="delete_exception(this,{$exception.id});return false;" id="delete_{$exception.id}">
				<img src="/{$THEME}/images/icon_delete.png" width="12" height="12"/>
				</a>
			</td>
		</tr>
	{/foreach}

</table>

<div id="container_refer_add" style="margin-top: 15px;">
<div>Период реферальной добавки, дней</div>
	<input type="text" id="add_refer" value="{$arr.add}"/><span style="display: none;" >..Обновлено</span>
<div>Начисление юзеру во время льготного периода, %</div>
	<input type="text" id="add_user" value="{$arr.add_user}"/><span style="display: none;" >..Обновлено</span>
<div> Процент рефералу от привлеченных юзеров, %</div>
	<input type="text" id="add_user_refer" value="{$arr.add_refer}"/><span style="display: none;" >..Обновлено</span>
	<div style="margin-top: 5px;" ><button value="Обновить" onclick="change_settings();" id="refer_add_button">Обновить</button>
</div>
</div>
{else:}
	<div>Раздел пуст</div>
{/if}
</div>