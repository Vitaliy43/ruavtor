<form action="<?php if(isset($SELF_URL)){ echo $SELF_URL; } ?>/update_settings" id="user_settings">

<div id="user_edit_block">

        <div class="form_text">Бан после регистрации</div>
	<div class="form_input">
	<input type="checkbox" name="register_ban" <?php if($ban): ?> checked=""<?php endif; ?>/>
	</div>
	<div class="form_overflow"></div>
	<input type="hidden" name="update" value="1"/>

	<div class="form_input">
		<input type="submit" name="button" class="button" value="<?php echo lang ('amt_save'); ?>" onclick="ajax_me('user_settings');" />
	</div>
	<div class="form_overflow"></div>

</div>
<?php echo form_csrf (); ?></form>
<?php $mabilis_ttl=1538476685; $mabilis_last_modified=1475646120; //D:\server\www\projects\articler.img\application\modules\user_manager/templates/settings.tpl ?>