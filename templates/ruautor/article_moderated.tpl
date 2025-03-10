
{if isset($is_moderator) and $is_moderator == 1 and empty($show)}
	<form action="{site_url('moderator/resolution')}/{$id}" id="moderate_form" method="POST" enctype="multipart/form-data">
{/if}
{if $activity == 2}
	<p><em>{time_convert_date_to_text($data_published,$main_language)}</em></p>
{else:}
	<p><em>{time_convert_date_to_text($data_saved,$main_language)}</em></p>
{/if}
{if isset($is_select_headings)}
<label class="field-wrap">Рубрика<span class="select-mark">▼</span>
	{$headings}
</label>
{/if}

{if isset($is_moderator) and $is_moderator == 1}
{literal}
<style type="text/css">
.article_content {border: 1px solid #8C7E7E;}
</style>
<script type="text/javascript">

$(function() {
	$('#change_url').hide();
	$("#link_change_url").click(function() {
		$('#change_url').slideToggle('normal');
		return false;
});
});
</script>
{/literal}
<input type="hidden" name="article_id" value="{$id}"/>
{if isset($rating_for_homefeed)}
<input type="hidden" name="rating_for_homefeed" id="rating_for_homefeed" value="{$rating_for_homefeed}"/>
{/if}

{/if}
{if isset($header) && $is_moderator == 1 && isset($is_select_headings)}
<label class="field-wrap" style="margin-bottom: 2px;">{lang('heading')}
{if isset($errors.header)}
&nbsp;{$errors.header}
{/if}
</label>
{if $activity != 2}
	<input type="text" name="header" id="publisher_header" onchange="translit();" value="{$header}"/>
{else:}
	<input type="text" name="header" id="publisher_header" value="{$header}"/>
{/if}
{/if}

{if isset($url) && $activity != 2}
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

{elseif isset($url) && $activity == 2}

{if isset($is_moderator)}
<div style="margin-top:-10px;margin-bottom:5px;padding-left:3px;">
	<a href="#change_url" onclick="change_url();return false;" id="link_change_url">
		{lang('modify_url')}
	</a>
</div>
<div id="change_url">
<input type="text" name="url" id="url" value="{$url}" />
</div>


{/if}

{/if}
{if isset($author_name)}
	<p style="text-align: left;">{lang('author')}: <strong><a href="{site_url('avtory')}/{$username}" style="color:black;text-decoration:underline;" target="_blank">{$author_name} {$author_family}</a></strong>
	{if empty($is_select_headings)}
<br />	{lang('rubric')}: <strong><a href="{site_url('')}{$headings_url}" style="color:black;text-decoration:underline;" target="_blank">{$headings}</a></strong>
{/if}
	 </p>

{else:}
	<p style="text-align: left;">{lang('author')}: <strong><a href="{site_url('avtory')}/{$username}" style="color:black;text-decoration:underline;" target="_blank">{$username}</a></strong>
	{if empty($is_select_headings)}
<br />	{lang('rubric')}: <strong>{$headings}</strong>
{/if}
{/if}
{if isset($rating)}
<p style="text-align: right;margin-top:-10px;" id="article_rating">{lang('rating')}: <strong>{$rating}</strong></p>

{/if}
{if isset($num_visites_all) && $num_visites_all > 0}
<div>{lang('views')}:</div>
<div id="all_num_visites">{lang('total_views')} - <b>{$num_visites_all}</b></div>
<div id="average_num_visites">{lang('views_last_day')} - <b>{$num_visites_avg}</b></div>
{/if}
{if isset($is_edited)}
<label class="field-wrap" style="margin-top:5px;">{lang('annotation')}
<textarea id="annotation" name="annotation" rows="3" style="width:100%;">
{$annotation}
</textarea>
</label>
<label class="field-wrap" style="margin-top:5px;">{lang('description_meta')}
<textarea id="description" name="description" rows="3" style="width:100%;">
{$description}
</textarea>
</label>

<label class="field-wrap" style="margin-top:5px;">{lang('keywords_meta')}
<textarea id="keywords" name="keywords" rows="2" style="width:100%;">
{$keywords}
</textarea>
</label>

<label class="field-wrap">{lang('image')}
	{if isset($errors.upload_image)}
		<span>{$errors.upload_image}</span>
	{/if}
	<input type="file" name="image"/>
</label>
<div>{lang('full_text')}</div>
{/if}
<div class="article_content">
{$content}
</div>
{if isset($is_moderator) and $is_moderator == 1}
<br>
{if empty($is_edited) and $activity == 2}

{elseif isset($is_edited) and $activity == 2}
<input type="submit" name="publish" value="{lang('modify')}" onclick="validate_moderate_form('{$id}','publish');return false;"/>
<input type="hidden" name="is_published" value="1"/>
{else:}

{if $activity == 1 and empty($show)}

<div>

{if isset($outer_link)}
<input type="hidden" name="outer_link" id="outer_link" value="{$outer_link.link}"/>
<table width="60%" cellspacing="3">
<tr>
<td nowrap="">{lang('external_link')}:</td>
<td nowrap=""><a href="{$outer_link.link}" target="_blank">{$outer_link.link}</a></td>
<td align="left" nowrap=""><input type="checkbox" id="enable_link" onclick="set_link();">&nbsp;{lang('attach_link')}</td>
</tr>
<tr class="pay_for_link">
{
$buffer = $rating_for_homefeed - 1;
$initial_rating = lang('initial_rating');
$initial_rating = str_replace('%balls%',$buffer,$initial_rating);
}
<td nowrap="">{$initial_rating}</td>
<td><input type="text" id="initial_rating" value="0" name="initial_rating"/>
</td>
<td></td>
</tr>
<tr class="pay_for_link">
<td nowrap="">{lang('money_bonus')}</td>
<td><input type="text" id="add_score" value="0" name="add_score" /></td>
<td></td>
</tr>
</table>
{/if}

<br>
<div style="margin-bottom:10px;">
	<span>
		<input type="checkbox" name="is_special" style="margin-left:-8px;">
	</span>
	<span>
	{
		$bonus_leaving = lang('bonus_leaving_sandbox');
		$bonus_leaving = str_replace('%bonus%',bonus_for_homefeed,$bonus_leaving);
		
	}
		{$bonus_leaving}
	</span>
</div>
<input type="submit" name="publish" value="{lang('publish')}" onclick="validate_moderate_form('{$id}','publish');return false;"/>
<input type="hidden" name="hidden_publish" id="hidden_publish" value="0"/>
</div><br>
<div>
<input type="submit" name="reject" value="{lang('reject_with_statement')}" onclick="validate_moderate_form('{$id}','reject');return false;" style="width: 250px;"/>
<input type="hidden" name="hidden_reject" id="hidden_reject" value="0"/>
</div>

<div>
<textarea name="reason" id="moderate_reason"></textarea>
</div>
{elseif $activity == 2 and empty($show)}
<div>
<br><br>
<input type="submit" name="publish" value="{lang('modify')}" onclick="validate_moderate_form('{$id}','publish');return false;"/>
<input type="hidden" name="hidden_publish" id="hidden_publish" value="1"/>
</div>
{/if}

{/if}
{if isset($is_moderator) and $is_moderator == 1}
{form_csrf()}
{/if}

</form>
{/if}
{if isset($is_moderator) && $is_moderator == 1 && empty($is_edited)}
<div style="margin-bottom:10px;"><a href="{site_url('moderator/edit')}/{$id}" target="_blank">{lang('edit_article')}</a></div>
{/if}
