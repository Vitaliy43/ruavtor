<div id="change_purse" style='padding:10px; background:#fff;'>
<?php if($have_purse == 1): ?>
	<h2>Смена кошелька</h2>
<?php else:?>
	<h2>Назначение кошелька</h2>
<?php endif; ?>
<form action="<?php echo site_url ('articler/change_purse'); ?>" method="POST" id="form_change_purse" onsubmit="change_purse(this);return false;">
<?php if($have_purse == 1): ?>
	<input type="hidden" id="type_change" name="type_change" value="update"/>
<?php else:?>
	<input type="hidden" id="type_change" name="type_change" value="insert"/>
<?php endif; ?>
<!--div onclick="change_email();return false;">123</div-->
<?php if($have_purse == 1): ?>
	<div>Текущий кошелек</div>
<?php else:?>
	<div>Введите  кошелек</div>
<?php endif; ?>
<div><input type="text" name="purse" id="purse" value="<?php if(isset($purse)){ echo $purse; } ?>"/></div>
<?php if($have_purse == 1): ?>
<div>Новый кошелек</div>
<div><input type="text" name="new_purse" id="new_purse"/></div>
<div>Подтверждение</div>
<div id="submit_change_purse"><input type="text" name="confirm_purse" id="confirm_purse"/></div>
<?php endif; ?>
<?php if($have_purse == 1): ?>
	<div style="margin-top:10px;"><input type="submit" value="Сменить"/>
<?php else:?>
	<div style="margin-top:10px;"><input type="submit" value="Назначить"/>
<?php endif; ?>
</div>
<?php echo form_csrf (); ?>
</form>
</div><?php $mabilis_ttl=1540976802; $mabilis_last_modified=1450852420; //D:\server\www\projects\articler.img\/templates//main/widgets/change_purse.tpl ?>