<div id="change_purse" style='padding:10px; background:#fff;'>
{if $have_purse == 1}
	<h2>Смена кошелька</h2>
{else:}
	<h2>Назначение кошелька</h2>
{/if}
<form action="{site_url('articler/change_purse')}" method="POST" id="form_change_purse" onsubmit="change_purse(this);return false;">
{if $have_purse == 1}
	<input type="hidden" id="type_change" name="type_change" value="update"/>
{else:}
	<input type="hidden" id="type_change" name="type_change" value="insert"/>
{/if}
<!--div onclick="change_email();return false;">123</div-->
{if $have_purse == 1}
	<div>Текущий кошелек</div>
{else:}
	<div>Введите  кошелек</div>
{/if}
<div><input type="text" name="purse" id="purse" value="{$purse}"/></div>
{if $have_purse == 1}
<div>Новый кошелек</div>
<div><input type="text" name="new_purse" id="new_purse"/></div>
<div>Подтверждение</div>
<div id="submit_change_purse"><input type="text" name="confirm_purse" id="confirm_purse"/></div>
{/if}
{if $have_purse == 1}
	<div style="margin-top:10px;"><input type="submit" value="Сменить"/>
{else:}
	<div style="margin-top:10px;"><input type="submit" value="Назначить"/>
{/if}
</div>
{form_csrf()}
</form>
</div>