{
	$finance_for = lang('finance_for');
	$finance_for = str_replace('%login%',$login,$finance_for);
}
<h2>{$finance_for}</h2>
<input type="hidden" id="user_id" value="{$user_id}"/>
<table width="40%">
<tr>
	<td>{lang('earned_total')}:</td>
	<td>{$all_earn} {lang('money')}</td>
</tr>
<tr>
	<td>{lang('paidout_total')}:</td>
	<td>{$all_paid} {lang('money')}</td>
</tr>
<tr>
	<td>{lang('remaining_balance')}:</td>
	<td>{$remains} {lang('money')}</td>
</tr>
<tr>
	<td>{lang('amount_due')}:</td>
	<td id="enable_payout">{$available_for_pay} {lang('money')}</td>
</tr>
</table>

{if $available_for_pay >= $lowest_sum_for_pay}
	<div id="ajax_load"><a href="{site_url('articler/order_payout')}" onclick="order_payout(this);return false;">{lang('request_payment')}</a></div>
{/if}