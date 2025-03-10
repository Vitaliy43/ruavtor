<div style="margin-left:5px;">
<div>
	<h3>Генератор реферальной ссылки</h3>
<div><label>Ссылка до:</label><input type="text" id="url_before" maxlength="200" style="width:300px;margin-left: 20px;"/>
<button value="generate" onclick="generate_refer('{site_url()}',{$user_id});return false;">Генерировать</button>
</div>
<div style="margin-top: 10px;"><label>Ссылка после:</label><input type="text" id="url_after" maxlength="200" style="width:420px;" readonly="" onclick="autoselect(this);"/></div>
</div>
<div id="generator_result"></div>
{if isset($days_refer)}
	<div>До окончания льготного периода {$days_refer} дней</div>
{/if}
<input type="hidden" id="user_id" value="{$user_id}"/>
{if count($refers) > 0}
<table width="40%" style="margin-top: 20px;">
<tr>
	<th>Реферал</th>
	<th>Дата</th>
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