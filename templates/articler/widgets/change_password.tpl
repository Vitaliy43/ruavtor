<div id="change_password" style='padding:10px; background:#fff;'>
<h2>Смена пароля</h2>
<form action="{site_url('articler/change_password')}" method="POST" id="form_change_password" onsubmit="change_password();return false;">
<!--div onclick="change_email();return false;">123</div-->
<div>Введите пароль</div>
<div><input type="password" name="password" id="window_password" /></div>
<div>Новый пароль</div>
<div><input type="password" name="new_password" id="window_new_password" /></div>
<div>Подтверждение</div>
<div id="submit_change_password"><input type="password" name="confirm_password" id="confirm_password" /></div>
<div style="margin-top:10px;"><input type="submit" value="Сменить"/>
</div>
{form_csrf()}
</form>
</div>
