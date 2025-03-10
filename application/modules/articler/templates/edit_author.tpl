<form action="{$SELF_URL}/update_author/{$author.id}" id="edit_user_form">

<div id="user_edit_block">

        <div class="form_text">{lang('amt_author_rating')}</div>
	<div class="form_input"><input type="text" name="author_rating" value="{$author.author_rating}" class="textbox_long" /></div>
	<div class="form_overflow"></div>

	<div class="form_text">{lang('amt_rating_activity')}</div>
	<div class="form_input"><input type="text" name="rating_activity" value="{$author.rating_activity}" class="textbox_long" /></div>
	<div class="form_overflow"></div>

	<!--div class="form_text">{lang('amt_bmp')}</div>
	<div class="form_input"><input type="text" name="score" value="{$author.score}" class="textbox_long" /></div>
	<div class="form_overflow"></div-->
	
	
	{if isset($author.sum_statistic)}
	<div class="form_text">{lang('amt_statistic')}</div>
	<div class="form_input">{$author.sum_statistic}<span class="correct_statistic" title="Кол-во просмотров, видимое пользователям">{if isset($author.correct_sum_statistic)} ({$author.correct_sum_statistic}){/if}</span></div>
	<div class="form_overflow"></div>
	{/if}
	
	{if isset($author.payments)}
	<div class="form_text">{lang('amt_edit_payments')}</div>
	<div class="form_input"><input type="text" name="payments" value="{$author.payments}" class="textbox_long" /></div>
	<div class="form_overflow"></div>
	{/if}
	
	{if isset($author.payouts)}
	<div class="form_text">{lang('amt_edit_payouts')}</div>
	<div class="form_input"><input type="text" name="payouts" value="{$author.payouts}" class="textbox_long" /></div>
	<div class="form_overflow"></div>
	{/if}

	<div class="form_text"></div>
	<div class="form_input">
		<input type="submit" name="button" class="button" value="{lang('amt_save')}" onclick="ajax_me('edit_user_form');" />
		<input type="submit" name="button" class="button" value="{lang('amt_cancel')}" onclick="MochaUI.closeWindow($('user_edit_window')); return false;" />
	</div>
	<div class="form_overflow"></div>

</div>
{form_csrf()}</form>
