<h1><?php echo lang ('register_h1'); ?></h1>

</br>
<p style="text-align: justify;">Не используйте в качестве логина адрес электронной почты (e-mail)! Правильный читаемый логин должен формироваться следующим образом и может содержать только следующие символы: при создании логина могут использоваться только символы латинского (английского) алфавита (от A до Z - как малые, так и заглавные), цифры 0..9, а также знаки дефис ("-") и подчеркивание ("_") - это все! Никакие другие символы (включая пробелы) в вашем логине участвовать не могут!</p>
</br>

<div id="titleExt">
<!--h5><?php echo widget ('path'); ?><span class="ext"><?php echo lang ('articler_register'); ?></span></h5-->
</div>

<?php if(validation_errors() OR $info_message): ?>
    <div class="errors"> 
        <?php echo validation_errors (); ?>
        <?php if(isset($info_message)){ echo $info_message; } ?>
    </div>
<?php endif; ?>

<form action="" class="form" method="post">
	<div class="registration">
	
	<div class="textbox">
        <label for="username" class="left"><?php echo lang ('articler_login'); ?></label>
        <input type="text" name="username" id="username" value="<?php echo set_value ('username'); ?>" style="width:250px;"/>
    </div>
	
	<div class="textbox_spacer"></div>
	
    <div class="textbox">
        <label for="email" class="left"><?php echo lang ('articler_email'); ?></label>
        <input type="text" name="email" id="email" value="<?php echo set_value ('email'); ?>" style="width:250px;"/>
    </div>

    <div class="textbox">
        <label for="password" class="left"><?php echo lang ('articler_password'); ?></label>
        <input type="password" name="password" id="password" value="<?php echo set_value ('password'); ?>" style="width:240px;"/>
    </div>
	
	<div class="textbox_spacer"></div>

    <div class="textbox"
        <label for="confirm_password" class="left"><?php echo lang ('confirm_password'); ?></label>
        <input type="password" class="text" name="confirm_password" id="confirm_password" style="width:190px;"/>
    </div>
	</div>
    
<?php if($cap_image): ?>
<table>
	<tr>
		<td>
			<label for="captcha"><?php if(isset($cap_image)){ echo $cap_image; } ?></label>
		</td>
		<td>
			<input type="text" name="captcha" id="captcha" value="<?php echo lang ('captcha'); ?>" onfocus="if(this.value=='<?php echo lang ('captcha'); ?>') this.value='';" onblur="if(this.value=='') this.value='<?php echo lang ('captcha'); ?>';"/>
		</td>
	</tr>
</table>
	<!--div class="textbox">
		
	</div-->
    <!--div class="comment_form_info">
	 <div class="textbox captcha">
        
   	</div>
    </div-->
    <?php endif; ?>
 
    <p class="clear">
        <label for="submit" class="left"></label> 
        <input type="submit" id="submit" class="submit" value="<?php echo lang ('articler_submit'); ?>" />
    </p>

	
    <label class="left">&nbsp;</label> 
    <a href="<?php echo site_url ( $modules['auth']  . '/forgot_password'); ?>"><?php echo lang ('articler_forgot_password'); ?></a>
    &nbsp;
    <a href="<?php echo site_url ('auth/login'); ?>"><?php echo lang ('enter'); ?></a>

<?php echo form_csrf (); ?>
</form>
</br>
<hr>
</br>
<?php echo lang ('register_message_2'); ?><?php $mabilis_ttl=1533724753; $mabilis_last_modified=1533640129; //D:\server\www\projects\articler.img\/templates/ruautor/register.tpl ?>