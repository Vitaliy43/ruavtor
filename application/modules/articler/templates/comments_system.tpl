<div class="form_text"></div>
	<div class="form_input"><h3>Настройка комментирования и плюсования</h3></div>
	<div class="form_overflow"></div>

		<form action="{$SELF_URL}/comments_settings/" id="comments_settings" method="post" style="width:100%">
			<div class="form_text" >{$per_page_comments.show_key}</div>
			<div class="form_input"><input type="text" name="per_page_comments" value="{$per_page_comments.value}" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
		<div class="form_text">{$num_last_comments.show_key}</div>
                        <div class="form_input"><input type="text" name="num_last_comments" value="{$num_last_comments.value}" 				class="textbox_long" /></div>
			<div class="form_overflow"></div>
                <div class="form_text">{$add_rating_by_plus.show_key}</div>
				
                <div class="form_input"><input type="text" name="add_rating_by_plus" value="{$add_rating_by_plus.value}" class="textbox_long" /></div>
			<div class="form_overflow"></div>
				
			<div class="form_text">{$limit_on_add_plus.show_key}</div>
                        <div class="form_input"><input type="text" name="limit_on_add_plus" value="{$limit_on_add_plus.value}" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
			<div class="form_text">{$quantity_articles_for_assess.show_key}</div>
                        <div class="form_input"><input type="text" name="quantity_articles_for_assess" value="{$quantity_articles_for_assess.value}" class="textbox_long" /></div>
			<div class="form_overflow"></div>

			<div class="form_text"></div>
			<div class="form_input">
			<input type="submit" name="button" class="button" value="Изменить" onclick="ajax_me('comments_settings');" />
			</div>
			<div class="form_overflow"></div>
		{form_csrf()}</form>