<div id="change_email" style='padding:10px; background:#fff;'>
<h2>Смена email</h2>
<form action="{site_url('articler/change_email')}" method="POST" id="form_change_email" onsubmit="change_email();return false;">
<!--div onclick="change_email();return false;">123</div-->
<div>Текущий email</div>
<div><input type="text" name="email" id="window_email" value="{$current_email}"/></div>
<div>Новый email</div>
<div><input type="text" name="new_email" id="window_new_email" /></div>
<div>Подтверждение</div>
<div id="submit_change_email"><input type="text" name="confirm_email" id="confirm_email" /></div>
<div style="margin-top:10px;"><input type="submit" value="Сменить"/>
</div>
{form_csrf()}
</form>
</div>
