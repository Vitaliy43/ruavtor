
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
 <div class="block-title">{lang('add_material')}</div>

{if $id == 0}
<form action="{site_url('publish')}" method="POST" onsubmit="validate_publish();return false;" name="form_publish" class="edit-block" enctype="multipart/form-data">
{else:}
<form action="{site_url('publish/update')}/{$id}" method="POST" onsubmit="validate_publish();return false;" name="form_publish" class="edit-block" enctype="multipart/form-data">
{if isset($outer_link)}
<input type="hidden" name="hidden_outer_link" id="hidden_outer_link" value="{$outer_link}"/>
{/if}
<input type="hidden" name="article_id" id="article_id" value="{$id}"/>
{/if}

<label class="field-wrap">{lang('rubric')}<span class="select-mark">▼</span>
{if isset($errors.headings)}
&nbsp;{$errors.headings}
{/if}
	{$headings}
</label>

<label class="field-wrap">{lang('title')}
{if isset($errors.header)}
&nbsp;{$errors.header}
{/if}
</label>
<input type="text" name="header" id="publisher_header" onchange="translit();" value="{$header}" style="width:75%;"/>
<label class="field-wrap">URL
{if isset($errors.url_empty)}
&nbsp;{$errors.url_empty}
{elseif isset($errors.url_wrong)}
&nbsp;{$errors.url_wrong}
{elseif isset($errors.url_exists)}
&nbsp;{$errors.url_exists}
{/if}
</label>
{if $url != ''}
<input type="text" name="url" id="url" value="{$url}" style="width:75%;"/>
{else:}
<input type="text" name="url" id="url" style="width:75%;"/>
{/if}
<label class="field-wrap">{lang('annotation_briefly')}
	<textarea id="annotation" name="annotation" rows="5" cols="30" placeholder="{lang('annotation')}">{$annotation}</textarea>
</label>

<label class="field-wrap">{lang('description')}
	<textarea id="description" name="description" rows="5" cols="30" placeholder="{lang('description_meta')}">{$description}</textarea>
</label>

<label class="field-wrap">{lang('keywords')}
	<textarea id="keywords" name="keywords" rows="5" cols="30" placeholder="{lang('keywords_meta')}">{$keywords}</textarea>
</label>
<label class="field-wrap">{lang('image')}
	{if isset($errors.upload_image)}
		<span>{$errors.upload_image}</span>
	{/if}
	<input type="file" name="image"/>
</label>
<div>{lang('full_text')}</div>
<div id="container_editor">
 {$editor}
</div>
{if $type == 'update'}
<div id="container_outer_link">
{lang('external_link_to_site')}:
{if isset($outer_link)}
<span id="show_add_outer_link">
<b>{$outer_link}</b>
</span>
<a href="#add_outer_link" style="margin-left:10px;" title="{lang('changing_external_link')}" onclick="show_outer_link();">{lang('change')}</a>
{else:}
<a href="#add_outer_link" style="margin-left:10px;" title="{lang('adding_external_link')}" id="show_add_outer_link" onclick="show_outer_link();">{lang('add')}</a>
{/if}
</div>
{/if}
<div class="button_publish">
<input type="submit" name="submit" value="{lang('save')}"/>
</div>
{form_csrf()}
</form>
</div>
