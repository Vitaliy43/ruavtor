<form action="{$SELF_URL}/update_settings" id="user_settings">

<div id="user_edit_block">

        <div class="form_text">Бан после регистрации</div>
	<div class="form_input">
	<input type="checkbox" name="register_ban" {if $ban} checked=""{/if}/>
	</div>
	<div class="form_overflow"></div>
	<input type="hidden" name="update" value="1"/>

	<div class="form_input">
		<input type="submit" name="button" class="button" value="{lang('amt_save')}" onclick="ajax_me('user_settings');" />
	</div>
	<div class="form_overflow"></div>

</div>
{form_csrf()}</form>
