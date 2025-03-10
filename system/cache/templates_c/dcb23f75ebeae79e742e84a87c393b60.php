<div id="reply_plea" style='padding:10px; background:#fff;'>
<h2>Ответ на жалобу</h2>
<form action="<?php echo site_url ('articler/reply_plea'); ?>" method="POST" id="form_reply_plea" onsubmit="reply_plea(this.action,'<?php echo site_url (''); ?>');return false;">
<!--div onclick="change_email();return false;">123</div-->
<input type="hidden" name="plea_id" id="plea_id"/>
<input type="hidden" name="ajax_url" id="ajax_url"/>
<div class="reply_comment" style="margin-top:10px;">
<table width="100%" cellpadding="3" cellspacing="3">

<tr>
<td>Текст жалобы</td>
<td id="plea_text"></td>
</tr>
<tr>
<td>Дата поступления</td>
<td id="plea_time"></td>
</tr>
<tr>
<td>Комментарий</td>
<td id="plea_comment"></td>
</tr>
<tr>
<td>Адресат</td>
<td id="plea_username"></td>
</tr>
<tr>
<td valign="top">
Ответ
</td>
<td valign="top">
<textarea name="answer_plea" id="answer_plea" cols="30" rows="3"></textarea>
</td>
</tr>
</table>
</div>
<div style="margin-top:10px;" id="container_submit"><input type="submit" value="Сообщить"/>
</div>
<?php echo form_csrf (); ?>
</form>
</div>
<?php $mabilis_ttl=1540977842; $mabilis_last_modified=1450852420; //D:\server\www\projects\articler.img\/templates//ruautor/widgets/reply_plea.tpl ?>