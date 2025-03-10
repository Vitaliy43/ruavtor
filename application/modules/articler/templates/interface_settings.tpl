<div class="form_text"></div>
	<div class="form_input"><h3>Настройки интерфейса</h3></div>
	<div class="form_overflow"></div>

		<form action="{$SELF_URL}/submit_interface_settings/" id="submit_interface_settings" method="post" style="width:100%">
			<div class="form_text" >{$interface_language.show_key}</div>
			<div class="form_input">{$languages_box}</div>
			<div class="form_overflow"></div>
		
			<div class="form_text" >{$per_page_elements.show_key}</div>
			<div class="form_input"><input type="text" name="per_page_elements" value="{$per_page_elements.value}" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
			<div class="form_text" >{$num_last_articles_sandbox.show_key}</div>
			<div class="form_input"><input type="text" name="num_last_articles_sandbox" value="{$num_last_articles_sandbox.value}" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
			<div class="form_text" >{$modal_box_for_user.show_key}</div>
			<div class="form_input" style="text-align:left;margin-left:-125px;">
			{if $modal_box_for_user.value}
				<input type="checkbox" name="modal_box_for_user" checked="checked" class="textbox_long" />
			{else:}
				<input type="checkbox" name="modal_box_for_user" class="textbox_long" />
			{/if}
			</div>
			<div class="form_overflow"></div>
			
			<div class="form_text" >{$modal_box_for_guest.show_key}</div>
			<div class="form_input" style="text-align:left;margin-left:-125px;">
			{if $modal_box_for_guest.value}
				<input type="checkbox" name="modal_box_for_guest" checked="checked" class="textbox_long" />
			{else:}
				<input type="checkbox" name="modal_box_for_guest" class="textbox_long" />
			{/if}
			</div>
			<div class="form_overflow"></div>

			<div class="form_text"></div>
			<div class="form_input">
			<input type="submit" name="button" class="button" value="Изменить" onclick="ajax_me('submit_interface_settings');" />
			</div>
			<div class="form_overflow"></div>
		{form_csrf()}</form>