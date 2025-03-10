<h1 class="header_article">{$header}</h1>
{literal}
<style type="text/css">
.article_content {border: 1px solid #8C7E7E;}
</style>
{/literal}
{if $activity != 2}
<form action="{site_url('moderator/resolution')}/{$id}" id="moderate_form" method="POST">
{/if}
<div style="margin-bottom:5px;">Рубрика
{if isset($is_select_headings)}
{$headings}
{else:}
&nbsp;
&lt;&lt;&nbsp;{$headings}&nbsp;&gt;&gt;
{/if}
</div>
{if $activity != 2}
<div>Заголовок
{if isset($errors.header)}
&nbsp;{$errors.header}
{/if}
</div>
<input type="text" name="header" id="publisher_header" onchange="translit();" value="{$header}"/>

{if isset($url)}
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
<input type="text" name="url" id="url" value="{$url}"/>
{else:}
<input type="text" name="url" id="url"/>
{/if}

{/if}
{/if}
<div>Аннотация</div>
<div>
<textarea id="annotation" name="annotation" rows="3" style="width:75%;">
{$annotation}
</textarea>
</div>
<br>
<div>Полный текст</div><div class="article_content">
{$content}
</div>
<br>
{if $activity == 2}

<!--a href="{site_url('moderator/edit')}/{$id}" style="text-decoration:underline;" target="_blank">Редактировать</a-->
{elseif isset($is_edited)}
<input type="submit" name="publish" value="Изменить" onclick="validate_moderate_form('{$id}','publish');return false;"/>
<input type="hidden" name="is_published" value="1"/>
{else:}

{if $activity == 1}
<div>

<br><br>
<input type="submit" name="publish" value="Публиковать" onclick="validate_moderate_form('{$id}','publish');return false;"/>
<input type="hidden" name="hidden_publish" id="hidden_publish" value="0"/>
</div><br>
<div>
<input type="submit" name="reject" value="Отвергнуть с формулировкой" onclick="validate_moderate_form('{$id}','reject');return false;"/>
<!--input type="submit" name="reject" value="Отвергнуть с формулировкой" /-->
<input type="hidden" name="hidden_reject" id="hidden_reject" value="0"/>
</div>

<div>
<textarea name="reason" id="moderate_reason"></textarea>
</div>
{/if}
{/if}
{if $activity != 2}
{form_csrf()}
</form>
{/if}
