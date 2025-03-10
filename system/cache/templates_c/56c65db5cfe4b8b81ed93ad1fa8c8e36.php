<div class="form_text"></div>
	<div class="form_input"><h3>Настройка почтовых уведомлений и шаблонов</h3></div>
	<div class="form_overflow"></div>

		<form action="<?php if(isset($SELF_URL)){ echo $SELF_URL; } ?>/submit_mail_settings/" id="submit_mail_settings" method="post" style="width:100%">
			<div class="form_text" ><?php echo $main_email['show_key']; ?></div>
			<div class="form_input"><input type="text" name="main_email" value="<?php echo $main_email['value']; ?>" class="textbox_long" /></div>
			<div class="form_overflow"></div>

			<div class="form_text"><?php echo $template_add_comment['show_key']; ?></div>
                        <div class="form_input">
						<textarea name="template_add_comment"><?php echo $template_add_comment['text']; ?></textarea>
						</div>
			<div class="form_overflow"></div>
			
			<div class="form_text"><?php echo $template_reply_comment['show_key']; ?></div>
                        <div class="form_input">
						<textarea name="template_reply_comment"><?php echo $template_reply_comment['text']; ?></textarea>
						</div>
			<div class="form_overflow"></div>
			
			<div class="form_text"><?php echo $template_publish_article['show_key']; ?></div>
                        <div class="form_input">
						<textarea name="template_publish_article"><?php echo $template_publish_article['text']; ?></textarea>
						</div>
			<div class="form_overflow"></div>
			
			<div class="form_text"><?php echo $template_reject_article['show_key']; ?></div>
                        <div class="form_input">
						<textarea name="template_reject_article"><?php echo $template_reject_article['text']; ?></textarea>
						</div>
			<div class="form_overflow"></div>
			
			<div class="form_text"><?php echo $template_change_status['show_key']; ?></div>
                        <div class="form_input">
						<textarea name="template_change_status"><?php echo $template_change_status['text']; ?></textarea>
						</div>
			<div class="form_overflow"></div>
			
			<div class="form_text"><?php echo $template_add_plea['show_key']; ?></div>
                        <div class="form_input">
						<textarea name="template_add_plea"><?php echo $template_add_plea['text']; ?></textarea>
						</div>
			<div class="form_overflow"></div>

			<div class="form_text"></div>
			<div class="form_input">
			<input type="submit" name="button" class="button" value="Изменить" onclick="ajax_me('submit_mail_settings');" />
			</div>
			<div class="form_overflow"></div>
		<?php echo form_csrf (); ?></form><?php $mabilis_ttl=1538468568; $mabilis_last_modified=1450852452; //D:\server\www\projects\articler.img\application\modules\articler/templates/mail_settings.tpl ?>