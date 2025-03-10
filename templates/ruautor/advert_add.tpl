
{if $is_update}
	<h2>Редактировать рекламный блок</h2>
{else:}
	<h2>Добавить рекламный блок</h2>
{/if}

{if $message}
	<div style="color: red;">{$message}</div>
{/if}
<div class="container_editor">
	{if $is_update}
		<form action="{site_url('moderator/advert_update/'.$advert['id'])}" method="POST" name="form_add_advert" id="form_add_advert" onsubmit="validate_advert_form();return false;">
	{else:}
		<form action="{site_url('moderator/advert_add')}" method="POST" name="form_add_advert" id="form_add_advert" onsubmit="validate_advert_form();return false;">
	{/if}


<table cellspacing="10" style="margin-top: 10px;">
	<tr>
		<td valign="top">Название</td>
		<td valign="top" align="center" style="padding-left: 20px;">
			{if isset($advert['name'])}
				<input type="text" name="name" id="advert_name" value="{$advert['name']}" style="width: 200px;"/>
			{else:}
				<input type="text" name="name" id="advert_name" value="" style="width: 200px;"/>
			{/if}
		</td>
	</tr>
	<tr>
		<td colspan="2" style="font-size: 3px;">&nbsp;</td>
	</tr>
	<tr>
		<td valign="top">Место отображения</td>
		<td valign="top" align="center" style="padding-left: 20px;">
			{$types_box}
		</td>
	</tr>
	<tr>
		<td colspan="2" style="font-size: 3px;">&nbsp;</td>
	</tr>
	<tr>
		<td valign="top">Код</td>
		<td valign="top" align="center" style="padding-left: 20px;">
			<textarea name="code" rows="2" cols="10" style="width: 200px;" id="advert_code">{$advert['code']}</textarea>
		</td>
	</tr>
</table>


<div class="button_publish">
	<input type="submit" name="submit" value="Сохранить"/>
</div>
<br>
{form_csrf()}
</form>
</div>


