{if isset($message)}
	<div>{$message}</div>
{/if}
<div class="container_editor">
<form action="{site_url('private/list_articles')}" method="POST" name="form_add_private" id="form_add_private" onsubmit="validate_private_form();return false;">
<div>Заголовок</div>
<input type="text" name="header" id="publisher_header" onchange="translit();" value="{$header}" style="width:75%;"/>
<div>URL</div>
<input type="text" name="url" id="url" value="{$url}" style="width:75%;"/>
<div>Аннотация</div>
<div>
<textarea id="annotation" name="annotation" rows="3" style="width:75%;">
{$annotation}
</textarea>
</div>
<br>
<div>Полный текст</div>
<div id="container_editor">
 {$editor}
</div>
{if isset($is_update)}
	<input type="hidden" name="is_update" value="1"/>
	<input type="hidden" name="article_id" value="{$article_id}"/>

{/if}
<div class="button_publish">
<input type="submit" name="submit" value="Сохранить"/>
</div>
{form_csrf()}
</form>
</div>
