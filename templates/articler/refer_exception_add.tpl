{if isset($message)}
	<div>{$message}</div>
{/if}
<div style="margin-left: 10px;">
<form action="{site_url('moderator/refer_exceptions/add')}" method="POST" name="form_add_exception" >
<div>URL</div>
<input type="text" name="url" value="{$header}" style="width:75%;"/>
<div>Описание</div>
<input type="text" name="description" value="{$url}" style="width:75%;" maxlength="100"/>
<br>
<div class="button_publish">
<input type="submit" name="submit" value="Сохранить"/>
</div>
{form_csrf()}
</form>
</div>
