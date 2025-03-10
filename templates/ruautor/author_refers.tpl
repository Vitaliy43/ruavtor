<div style="margin-left:5px;">
<div>
	<h3>{lang('generator_referral_link')}</h3>
<div><label>{lang('initial_link')}:</label><input type="text" id="url_before" maxlength="200" style="width:300px;margin-left: 20px;"/>
<button value="generate" onclick="generate_refer('{site_url()}',{$user_id});return false;">{lang('generate')}</button>
</div>
<div style="margin-top: 10px;"><label>{lang('after_link')}:</label><input type="text" id="url_after" maxlength="200" style="width:420px;" readonly="" onclick="autoselect(this);"/></div>
</div>
<div id="generator_result"></div>
{if isset($days_refer)}
{
	$tills_end_grace_period = lang('tills_end_grace_period');
	$tills_end_grace_period = str_replace('%days%',$days_refer,tills_end_grace_period);
}
	<div>{$tills_end_grace_period}</div>
{/if}
<input type="hidden" id="user_id" value="{$user_id}"/>
{if count($refers) > 0}
<table width="40%" style="margin-top: 20px;">
<tr>
	<th>{lang('referral')}</th>
	<th>{('date')}</th>
</tr>
{foreach $refers as $refer}
<tr>
	<td><a href="{site_url('avtory')}/{$refer.username}" target="_blank">{$refer.username}</a></td>
	<td>{time_change_show_data($refer.data)}</td>
</tr>
{/foreach}
</table>
{/if}
</div>