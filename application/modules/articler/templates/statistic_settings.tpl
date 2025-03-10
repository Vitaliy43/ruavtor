<div class="form_text"></div>
	<div class="form_input"><h3>Настройки статистики</h3></div>
	<div class="form_overflow"></div>

		<form action="{$SELF_URL}/submit_statistic_settings/" id="submit_statistic_settings" method="post" style="width:100%">
			<div class="form_text" >{$use_limit_visites.show_key}</div>
			<div class="form_input" >
			<input type="checkbox" name="use_limit_visites" class="textbox_long" style="width:10px;margin-left:2px;"
			{if $use_limit_visites.value == '1'}
			checked=""
			{/if}
			/></div>
			<div class="form_overflow"></div>
			
			<div class="form_text" >{$limit_visites.show_key}</div>
			<div class="form_input"><input type="text" name="limit_visites" value="{$limit_visites.value}" class="textbox_long" /></div>
			<div class="form_overflow"></div>

			<div class="form_text"></div>
			<div class="form_input">
			<input type="submit" name="button" class="button" value="Изменить" onclick="ajax_me('submit_statistic_settings');" />
			</div>
			<div class="form_overflow"></div>
		{form_csrf()}</form>