<div id="titleExt">
<!--h5><?php echo widget ('path'); ?><span class="ext"><?php echo lang ('articler_login_page'); ?></span></h5-->
</div>

<?php if(validation_errors() OR $info_message): ?>
    <div class="errors"> 
        <?php echo validation_errors (); ?>
        <?php if(isset($info_message)){ echo $info_message; } ?>
    </div>
<?php endif; ?>

<form action="" method="post" class="form">

	<div class="registration">
   
	<div class="textbox">
		<label for="username" class="left"><?php echo lang ('articler_login'); ?></label>
        <input type="text" id="username" size="30" name="username" value="<?php echo lang ('enter_your_login'); ?>" onfocus="if(this.value=='<?php echo lang ('enter_your_login'); ?>') this.value='';" onblur="if(this.value=='<?php echo lang ('enter_your_login'); ?>') this.value='<?php echo lang ('enter_your_login'); ?>';" />
    </div>
	
	<div class="textbox_spacer"></div>

    <div class="textbox">
        <label for="password" class="left"><?php echo lang ('articler_password'); ?></label> 
        <input type="password" size="30" name="password" id="password" value="<?php echo lang ('articler_password'); ?>" onfocus="if(this.value=='<?php echo lang ('articler_password'); ?>') this.value='';" onblur="if(this.value=='') this.value='<?php echo lang ('articler_password'); ?>';"/>
    </div>
	</div>

    <?php if($cap_image): ?>
    <div class="comment_form_info">
    <div class="textbox captcha">
        <input type="text" name="captcha" id="captcha" value="<?php echo lang ('captcha'); ?>" onfocus="if(this.value=='<?php echo lang ('captcha'); ?>') this.value='';" onblur="if(this.value=='') this.value='<?php echo lang ('captcha'); ?>';"/>
   	</div>
    <?php if(isset($cap_image)){ echo $cap_image; } ?>
    </div>
    <?php endif; ?>

    <p class="clear">
        <label><input type="checkbox" name="remember" value="1" id="remember" /> <?php echo lang ('articler_remember_me'); ?></label>
    </p>

    <input type="submit" id="submit" class="submit" value="<?php echo lang ('articler_submit'); ?>" /> 
	
	
    <br /><br />

    <label class="left">&nbsp;</label> 
    <a href="<?php echo site_url ( $modules['auth']  . '/forgot_password'); ?>"><?php echo lang ('articler_forgot_password'); ?></a>
    &nbsp;
    <a href="<?php echo site_url ( $modules['auth']  . '/register'); ?>"><?php echo lang ('articler_register'); ?></a>

<?php echo form_csrf (); ?>
</form>
<?php $mabilis_ttl=1537517441; $mabilis_last_modified=1533640198; //D:\server\www\projects\articler.img\/templates/ruautor/login.tpl ?>