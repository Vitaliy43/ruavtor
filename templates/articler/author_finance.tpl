<h2>Финансы для {$login}</h2>
<input type="hidden" id="user_id" value="{$user_id}"/>
<table width="40%">
<tr>
	<td>Всего заработано:</td>
	<td>{$all_earn}</td>
</tr>
<tr>
	<td>Всего выплачено:</td>
	<td>{$all_paid}</td>
</tr>
<tr>
	<td>Остаток в системе:</td>
	<td>{$remains}</td>
</tr>
<tr>
	<td>Доступно к выплате:</td>
	<td id="enable_payout">{$available_for_pay}</td>
</tr>
</table>

{if $available_for_pay >= $lowest_sum_for_pay}
	<div id="ajax_load"><a href="{site_url('articler/order_payout')}" onclick="order_payout(this);return false;">Заказать выплату</a></div>
{/if}