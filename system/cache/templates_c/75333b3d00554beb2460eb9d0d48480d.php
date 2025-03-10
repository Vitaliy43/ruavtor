<div id="add_plea" style='padding:10px; background:#fff;'>
<h2>Жалоба модератору на комментарий </h2>
<form action="<?php echo site_url ('articler/add_plea'); ?>" method="POST" id="form_add_plea" onsubmit="add_plea(this.action,'<?php echo site_url (''); ?>');return false;">
<!--div onclick="change_email();return false;">123</div-->
<input type="hidden" name="comment_id" id="comment_id"/>
<input type="hidden" name="ajax_url" id="ajax_url"/>
<div class="reply_comment" style="margin-top:10px;">
<table width="100%" cellpadding="3" cellspacing="3">
<tr>
<th><?php echo lang ('plea'); ?></th>
<th><?php echo lang ('comment_text'); ?></th>
</tr>
<tr>
<td valign="top">
<textarea name="plea" id="plea" cols="30" rows="3"></textarea>
</td>
<td valign="top">
<div class="comment_text"></div>
</td>
</tr>
</table>
</div>
<div style="margin-top:10px;" id="container_submit"><input type="submit" value="<?php echo lang ('s_message'); ?>"/>
</div>
<?php echo form_csrf (); ?>
</form>
</div>
<?php $mabilis_ttl=1540976802; $mabilis_last_modified=1530515713; //D:\server\www\projects\articler.img\/templates//main/widgets/add_plea.tpl ?>