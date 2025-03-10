<div id="reply_comment" style='padding:10px; background:#fff;'>
<h2><?php echo lang ('reply_to_comment'); ?></h2>
<form action="#empty" method="POST" id="form_reply_comment" onsubmit="reply_comment();return false;">
<!--div onclick="change_email();return false;">123</div-->
<input type="hidden" id="comment_id"/>
<input type="hidden" id="ajax_url"/>
<input type="hidden" id="flag_reply" name="flag_reply" value="1"/>
<div class="comment_text"></div>
<div class="reply_comment" style="margin-top:10px;">
<textarea name="reply" id="reply" cols="30" rows="3"></textarea>
</div>
<div style="margin-top:10px;"><input type="submit" value="Ответить"/>
</div>
<?php echo form_csrf (); ?>
</form>
</div>
<?php $mabilis_ttl=1540977847; $mabilis_last_modified=1530514849; //D:\server\www\projects\articler.img\/templates/main//widgets/reply_comment.tpl ?>