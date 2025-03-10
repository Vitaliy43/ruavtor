<div class="form_text"></div>
	<div class="form_input"><h3>Настройка почтовых уведомлений и шаблонов</h3></div>
	<div class="form_overflow"></div>

		<form action="{$SELF_URL}/submit_mail_settings/" id="submit_mail_settings" method="post" style="width:100%">
			<div class="form_text" >{$main_email.show_key}</div>
			<div class="form_input"><input type="text" name="main_email" value="{$main_email.value}" class="textbox_long" /></div>
			<div class="form_overflow"></div>

			<div class="form_text">{$template_add_comment.show_key}</div>
                        <div class="form_input">
						<textarea name="template_add_comment">{$template_add_comment.text}</textarea>
						</div>
			<div class="form_overflow"></div>
			
			<div class="form_text">{$template_reply_comment.show_key}</div>
                        <div class="form_input">
						<textarea name="template_reply_comment">{$template_reply_comment.text}</textarea>
						</div>
			<div class="form_overflow"></div>
			
			<div class="form_text">{$template_publish_article.show_key}</div>
                        <div class="form_input">
						<textarea name="template_publish_article">{$template_publish_article.text}</textarea>
						</div>
			<div class="form_overflow"></div>
			
			<div class="form_text">{$template_reject_article.show_key}</div>
                        <div class="form_input">
						<textarea name="template_reject_article">{$template_reject_article.text}</textarea>
						</div>
			<div class="form_overflow"></div>
			
			<div class="form_text">{$template_change_status.show_key}</div>
                        <div class="form_input">
						<textarea name="template_change_status">{$template_change_status.text}</textarea>
						</div>
			<div class="form_overflow"></div>
			
			<div class="form_text">{$template_add_plea.show_key}</div>
                        <div class="form_input">
						<textarea name="template_add_plea">{$template_add_plea.text}</textarea>
						</div>
			<div class="form_overflow"></div>

			<div class="form_text"></div>
			<div class="form_input">
			<input type="submit" name="button" class="button" value="Изменить" onclick="ajax_me('submit_mail_settings');" />
			</div>
			<div class="form_overflow"></div>
		{form_csrf()}</form>