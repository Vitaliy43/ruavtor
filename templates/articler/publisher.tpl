
<script type="text/javascript">
{literal}
$(document).ready(function(){
	{/literal}
//		$('.article_header').html('"'+'{$header}'+'"');
		//$('.article_header').text(123);
//		myAlert('{$header}');
		{literal}
	});
	{/literal}


</script>

<div class="container_editor">
{if $id == 0}
<form action="{site_url('publish')}" method="POST" onsubmit="validate_publish();return false;" name="form_publish">
{else:}
<form action="{site_url('publish/update')}/{$id}" method="POST" onsubmit="validate_publish();return false;" name="form_publish">
{if isset($outer_link)}
<input type="hidden" name="hidden_outer_link" id="hidden_outer_link" value="{$outer_link}"/>
{/if}
<input type="hidden" name="article_id" id="article_id" value="{$id}"/>
{/if}
<br>
<div>Рубрика
{if isset($errors.headings)}
&nbsp;{$errors.headings}
{/if}
</div><br>
{$headings}
<div>Заголовок
{if isset($errors.header)}
&nbsp;{$errors.header}
{/if}
</div>
<input type="text" name="header" id="publisher_header" onchange="translit();" value="{$header}" style="width:75%;"/>
<div>URL
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
<div>Аннотация (150 - 300 символов)</div>
<div>
<textarea id="annotation" name="annotation" rows="3" style="width:75%;">
{$annotation}
</textarea>
</div>
<br>
<div style="margin-top:5px;">Описание (мета-тег description) (100 - 200 символов)</div>
<div>
<textarea id="description" name="description" rows="3" style="width:75%;">
{$description}
</textarea>
</div>
<br>
<div style="margin-top:5px;">Ключевые слова (мета-тег keywords) (40 - 100 символов)</div>
<div>
<textarea id="keywords" name="keywords" rows="2" style="width:75%;">
{$keywords}
</textarea>
</div>
<br>
<div>Полный текст</div>
<div id="container_editor">
 {$editor}
</div>
{if $type == 'update'}
<div id="container_outer_link">
Внешняя ссылка на Руавтор:
{if isset($outer_link)}
<span id="show_add_outer_link">
<b>{$outer_link}</b>
</span>
<a href="#add_outer_link" style="margin-left:10px;" title="Изменение внешней ссылки" onclick="show_outer_link();">Изменить</a>
{else:}
<a href="#add_outer_link" style="margin-left:10px;" title="Добавление внешней ссылки" id="show_add_outer_link" onclick="show_outer_link();">Добавить</a>
{/if}
</div>
{/if}
<div class="button_publish">
<input type="submit" name="submit" value="Сохранить"/>
</div>
{form_csrf()}
</form>
</div>
<!--br><div onclick="validate_publish()">Test</div-->
