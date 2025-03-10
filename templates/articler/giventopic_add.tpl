{if isset($message)}
	<div>{$message}</div>
{/if}
<div class="container_editor">
{if isset($is_update)}
	<form action="{site_url('moderator/giventopic/update')}/{$id}" method="POST" name="form_add_giventopic" id="form_add_giventopic" onsubmit="validate_giventopic_form();return false;">
{else:}
	<form action="{site_url('moderator/giventopic/add')}" method="POST" name="form_add_giventopic" id="form_add_giventopic" onsubmit="validate_giventopic_form();return false;">
{/if}
<div>Заголовок</div>
<input type="text" name="header" id="publisher_header" onchange="translit();" value="{$header}" style="width:75%;"/>
<div>URL</div>
{if isset($errors.url_empty)}
&nbsp;{$errors.url_empty}
{elseif isset($errors.url_wrong)}
&nbsp;{$errors.url_wrong}
{elseif isset($errors.url_exists)}
&nbsp;{$errors.url_exists}
{/if}
</div>
{if $url != ''}
<input type="text" name="url" id="url" value="{$url}" style="width:75%;"/>
{else:}
<input type="text" name="url" id="url" style="width:75%;"/>
{/if}
<br>
<div>Рубрика
{if isset($errors.headings)}
&nbsp;{$errors.headings}
{/if}
</div><br>
{$headings}
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
