{if isset($message)}
	<div>{$message}</div>
{/if}
<div class="container_editor">
{if isset($is_update)}
	<form action="{site_url('moderator/rubric_update')}/{$id}" method="POST" name="form_add_rubric" id="form_add_rubric" onsubmit="validate_rubric_form();return false;">
{else:}
	<form action="{site_url('moderator/rubric_add')}" method="POST" name="form_add_rubric" id="form_add_rubric" onsubmit="validate_rubric_form();return false;">
{/if}
<div>Название</div>
<input type="text" name="name_russian" id="publisher_header" onchange="translit();" value="{$header}" style="width:75%;"/>
<div>URL</div>
<input type="text" name="name" id="url" value="{$url}" style="width:75%;"/>
<div>Тип рубрики</div>
<div>
	{$type_headings}
</div>
<div>Рекламные блоки</div>
<div>
	{$adverts_block}
</div>
{if isset($is_update)}
	<input type="hidden" name="is_update" value="1"/>
{/if}
<div class="button_publish">
	<input type="submit" name="submit" value="Сохранить"/>
</div>
<br>
{form_csrf()}
</form>
</div>
