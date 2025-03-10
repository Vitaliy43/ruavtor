

<h2>Редактировать рекламный блок "{$advert['name']}"</h2>

{if $message}
	<div style="color: red;">{$message}</div>
{/if}
<div class="container_editor">
	<form action="{site_url('moderator/default_advert_update/'.$advert['id'])}" method="POST" name="form_add_advert" id="form_add_advert">

<table cellspacing="10" style="margin-top: 10px;">
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


