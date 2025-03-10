<div id="titleExt"><h5><?php echo widget ('path'); ?><span class="ext"><?php echo lang ('contacts'); ?></span></h5></div>
<div id="contact">
<div class="left">

<?php if($form_errors): ?>
    <div class="errors"> 
        <?php if(isset($form_errors)){ echo $form_errors; } ?>
    </div>
<?php endif; ?>

<?php if($message_sent): ?>
     <?php echo lang ('your_message_sended'); ?>.
<?php endif; ?>

<form action="<?php echo site_url ('feedback'); ?>" method="post">
    <div class="textbox">
    <input type="text" id="name" name="name" class="text" value="<?php if($_POST['name']): ?><?php echo $_POST['name']; ?><?php else:?><?php echo lang ('your name'); ?><?php endif; ?>" onfocus="if(this.value=='<?php echo lang ('your name'); ?>') this.value='';" onblur="if(this.value=='') this.value='<?php echo lang ('your name'); ?>';"/>
    </div>

    <div class="textbox">
        <input type="text" id="email" name="email" class="text" value="<?php if($_POST['email']): ?><?php echo $_POST['email']; ?><?php else:?>Email<?php endif; ?>" onfocus="if(this.value=='Email') this.value='';" onblur="if(this.value=='') this.value='Email';"/>
    </div>

    <div class="textbox">
        <input type="text" id="theme" name="theme" class="text" value="<?php if($_POST['theme']): ?><?php echo $_POST['theme']; ?><?php else:?><?php echo lang ('articler_theme'); ?><?php endif; ?>" onfocus="if(this.value=='<?php echo lang ('articler_theme'); ?>') this.value='';" onblur="if(this.value=='') this.value='<?php echo lang ('articler_theme'); ?>';"/>
    </div>

    <div class="textbox">
        <textarea cols="45" rows="10" name="message" id="message" onfocus="if(this.value=='<?php echo lang ('articler_message_text'); ?>') this.value='';" onblur="if(this.value=='') this.value='<?php echo lang ('articler_message_text'); ?>';"><?php if($_POST['message']): ?><?php echo $_POST['message']; ?><?php else:?><?php echo lang ('articler_message_text'); ?><?php endif; ?></textarea>
    </div>
    
   	<div class="comment_form_info">
	<?php if($captcha_type =='captcha'): ?>    
    	<div class="textbox captcha">
	    <input type="text" name="captcha" id="recaptcha_response_field" value="<?php echo lang ('articler_captcha'); ?>" onfocus="if(this.value=='<?php echo lang ('articler_captcha'); ?>') this.value='';" onblur="if(this.value=='') this.value='<?php echo lang ('articler_captcha'); ?>';"/>
   	</div>
	<?php endif; ?>
    <?php if(isset($cap_image)){ echo $cap_image; } ?>
    </div>
    
    <input type="submit" class="submit" value="<?php echo lang ('articler_comment_button'); ?>" style="margin-left:5px;margin-top:10px;"/>

    <?php echo form_csrf (); ?>
</form>
</div>
<div class="right">
<div id="detail">
<h2 id="title"><?php echo lang ('articler_contacts'); ?></h2>
<?php echo widget ('contacts'); ?>
</div>
</div>
</div><?php $mabilis_ttl=1534147830; $mabilis_last_modified=1534063227; //D:\server\www\projects\articler.img\application\modules\feedback/templates/feedback.tpl ?>