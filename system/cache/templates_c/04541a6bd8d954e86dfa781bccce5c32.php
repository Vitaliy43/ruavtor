<?php if($comments_arr): ?>
<div id="detail">
    <h3><?php if(isset($total_comments)){ echo $total_comments; } ?></h3>
    <?php $counter = 1?>
    <?php if(is_true_array($comments_arr)){ foreach ($comments_arr as $comment){ ?>
    <div id="comment_<?php echo $comment['id']; ?>" class="comment<?php if($counter == 2): ?> next_row<?php $counter = 0?><?php endif; ?>" >
        <div class="comment_info">
            <b><?php echo $comment['author']; ?></b>
            <span><?php echo date ('d.m.Y H:i',  $comment['date'] ); ?></span>
        </div>
		<div class="comment_text">
            <?php echo $comment['text']; ?>
        </div>
            
    </div>
    <?php $counter++?>
    <?php }} ?>
</div>
<?php endif; ?>

<div id="detail">
<h3><?php echo lang ('articler_post_comment'); ?></h3>

<?php if($comment_errors): ?>
    <div class="errors"> 
        <?php if(isset($comment_errors)){ echo $comment_errors; } ?>
    </div>
<?php endif; ?>

<?php if($can_comment === 1 AND !is_logged_in): ?>
     <p><?php echo sprintf (lang('articler_login_for_comments'), site_url( $modules['auth'] )); ?></p>
<?php endif; ?>

<form action="" method="post" class="form">
    <input type="hidden" name="comment_item_id" value="<?php if(isset($item_id)){ echo $item_id; } ?>" />
    <input type="hidden" name="redirect" value="<?php echo uri_string (); ?>" />

    <?php if($is_logged_in): ?> 
        <p><?php echo lang ('articler_logged_in_as'); ?> <?php if(isset($username)){ echo $username; } ?>. <a href="<?php echo site_url ('auth/logout'); ?>"><?php echo lang ('articler_logout'); ?></a></p>         
    <?php else:?>
	
    <div class="comment_form_info">
    
    <div class="textbox">
        <input type="text" name="comment_author" id="comment_author" value="<?php if($_POST['comment_author']): ?><?php echo $_POST['comment_author']; ?><?php else:?><?php echo lang ('articler_name'); ?><?php endif; ?>" onfocus="if(this.value=='<?php echo lang ('articler_name'); ?>') this.value='';" onblur="if(this.value=='') this.value='<?php echo lang ('articler_name'); ?>';" />
    </div>
    
    <div class="textbox_spacer"></div>
       
    <div class="textbox">
        <input type="text" name="comment_email" id="comment_email" value="<?php if($_POST['comment_email']): ?><?php echo $_POST['comment_email']; ?><?php else:?>Email<?php endif; ?>" onfocus="if(this.value=='Email') this.value='';" onblur="if(this.value=='') this.value='Email';" />
    </div>
    
    </div>

    <?php endif; ?>

    <div class="textbox">
        <textarea name="comment_text" id="comment_text" rows="10" cols="50" onfocus="if(this.value=='<?php echo lang ('articler_comment_text'); ?>') this.value='';" onblur="if(this.value=='') this.value='<?php echo lang ('articler_comment_text'); ?>';"><?php if($_POST['comment_text']): ?><?php echo $_POST['comment_text']; ?><?php else:?><?php echo lang ('articler_comment_text'); ?><?php endif; ?></textarea>
    </div>
	
    
    <?php if(!$is_logged_in): ?> 
    
    <?php if($use_captcha): ?>
    <div class="comment_form_info">
    <div class="textbox captcha">
        <input type="text" name="captcha" id="captcha" value="<?php echo lang ('articler_captcha'); ?>" onfocus="if(this.value=='<?php echo lang ('articler_captcha'); ?>') this.value='';" onblur="if(this.value=='') this.value='<?php echo lang ('articler_captcha'); ?>';"/>
   	</div>
    <?php if(isset($cap_image)){ echo $cap_image; } ?>
    </div>
    <?php endif; ?>
    
    <?php endif; ?>
	<input type="submit" class="submit" value="<?php echo lang ('articler_comment_button'); ?>" />

    <?php echo form_csrf (); ?>
</form>
</div><?php $mabilis_ttl=1534145587; $mabilis_last_modified=1534060984; //D:\server\www\projects\articler.img\/templates/ruautor/comments.tpl ?>