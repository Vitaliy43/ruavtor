<div id="titleExt"><h5><?php echo widget ('path'); ?><span class="ext"><?php echo lang ('articler_forgot_password'); ?></span></h5></div>

<?php if(validation_errors() OR $info_message): ?>
    <div class="errors"> 
        <?php echo validation_errors (); ?>
        <?php if(isset($info_message)){ echo $info_message; } ?>
    </div>
<?php endif; ?>

<form action="" class="form" method="post">

	<div class="comment_form_info">
	
    <div class="textbox">
        <input type="text" size="30" name="login" id="login" value="<?php echo lang ('articler_username_or_mail'); ?>" onfocus="if(this.value=='<?php echo lang ('articler_username_or_mail'); ?>') this.value='';" onblur="if(this.value=='') this.value='<?php echo lang ('articler_username_or_mail'); ?>';" />
    </div>
	
	<br /><br /><br /><br />
    <input type="submit" id="submit" class="submit" value="<?php echo lang ('articler_submit'); ?>" />
	
    <br/><br />
	</div>
    <label class="left">&nbsp;</label> 
    <a href="<?php echo site_url ('auth/login'); ?>"><?php echo lang ('enter'); ?></a>
    &nbsp;
    <a href="<?php echo site_url ( $modules['auth']  . '/register'); ?>"><?php echo lang ('articler_register'); ?></a>

<?php echo form_csrf (); ?>
</form>
<?php $mabilis_ttl=1533813569; $mabilis_last_modified=1533640229; //D:\server\www\projects\articler.img\/templates/ruautor/forgot_password.tpl ?>