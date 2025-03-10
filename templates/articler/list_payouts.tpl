{literal}
<style type="text/css">
.paginator a {color:black;}
</style>
{/literal}
<h2>Заявления на выплату</h2>
{if count($payouts) > 0}
<table width="100%" class="sandbox" cellpadding="4" cellspacing="2" id="list_payouts">
<tr>
<th align="left">Автор</th>
<th align="left">Сумма, руб.</th>
<th align="left">Дата заказа</th>
<th align="left">Кошелек</th>
<th align="left"></th>
</tr>
{foreach $payouts as $payout}
<tr id="payout_{$payout.id}">
<td >
<a href="{site_url('avtory')}/{$payout.username}" target="_blank">{ucfirst($payout.username)}</a>
</td>
<td>
{$payout.payout}
</td>
<td>
{time_change_show_data($payout.data_order)}
</td>
<td>
{$payout.purse}
</td>
<td align="right" id="link_payout_{$payout.id}">
<a href="{site_url('moderator/make_payout')}/{$payout.id}" title="Оформить выплату" onclick="make_payout(this.href,{$payout.id});return false;">
<img src="/{$THEME}/images/icon_add.png" width="12" height="12"/>
</a>
</td>
</tr>
{/foreach}
</table>
<div class="paginator">
{$paginator}
</div>
{else:}
<div>Раздел пуст</div>
{/if}