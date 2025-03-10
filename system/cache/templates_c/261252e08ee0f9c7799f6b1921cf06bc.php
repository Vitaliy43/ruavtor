<form action="<?php if(isset($SELF_URL)){ echo $SELF_URL; } ?>/update_user/<?php if(isset($id)){ echo $id; } ?>" id="edit_user_form">

<div id="user_edit_block">

        <div class="form_text"><?php echo lang ('amt_user_login'); ?></div>
	<div class="form_input"><input type="text" name="username" value="<?php if(isset($username)){ echo $username; } ?>" class="textbox_long" /></div>
	<div class="form_overflow"></div>

	<div class="form_text"><?php echo lang ('amt_email'); ?></div>
	<div class="form_input"><input type="text" name="email" value="<?php if(isset($email)){ echo $email; } ?>" class="textbox_long" /></div>
	<div class="form_overflow"></div>

	<div class="form_text"><?php echo lang ('amt_group'); ?></div>
	<div class="form_input">
	<select name="role_id">
		<?php if(is_true_array($roles)){ foreach ($roles as $role){ ?>
		  <option value ="<?php echo $role['id']; ?>" <?php if($role['id'] == $role_id): ?> selected="selected" <?php endif; ?>><?php echo $role['alt_name']; ?></option>
		<?php }} ?>
		</select>
	</div>
	<div class="form_overflow"></div>

        <div class="form_text"><?php echo lang ('amt_new_pass'); ?>:</div>
        <div class="form_input"><input type="password" name="new_pass" value="" class="textbox_long" /></div>
        <div class="form_overflow"></div>

        <div class="form_text"><?php echo lang ('amt_new_pass_confirm'); ?>:</div>
        <div class="form_input"><input type="password" name="new_pass_conf" value="" class="textbox_long" /></div>

	<div class="form_overflow"></div>

	<div class="form_text"><?php echo lang ('amt_ban'); ?></div>
	<div class="form_input">
	<select name="banned">
		  <option value ="1" <?php if($banned == "1"): ?> selected="selected" <?php endif; ?>><?php echo lang ('amt_yes'); ?></option>
		  <option value ="0" <?php if($banned == "0"): ?> selected="selected" <?php endif; ?>><?php echo lang ('amt_no'); ?></option>
	</select>
	</div>
	<div class="form_overflow"></div>

	<div class="form_text"><?php echo lang ('amt_ban_reason'); ?></div>
	<div class="form_input"><input type="text" name="ban_reason" value="" class="textbox_long" /></div>
	<div class="form_overflow"></div>

	<div class="form_text"></div>
	<div class="form_input">
		<input type="submit" name="button" class="button" value="<?php echo lang ('amt_save'); ?>" onclick="ajax_me('edit_user_form');" />
		<input type="submit" name="button" class="button" value="<?php echo lang ('amt_cancel'); ?>" onclick="MochaUI.closeWindow($('user_edit_window')); return false;" />
	</div>
	<div class="form_overflow"></div>

</div>
<?php echo form_csrf (); ?></form>
<?php $mabilis_ttl=1537947953; $mabilis_last_modified=1450852455; //D:\server\www\projects\articler.img\application\modules\user_manager/templates/edit_user.tpl ?>