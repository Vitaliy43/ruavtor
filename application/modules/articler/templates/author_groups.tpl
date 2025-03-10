<div class="form_text"><h2>Статусы авторов</h2></div>
	<div class="form_input"></div>
	<div class="form_overflow"></div>
	
	<div class="form_text"></div>
	<div class="form_input"><h3>{$minimum_num_points_publicist.show_key}</h3></div>
	<div class="form_overflow"></div>
	
	<form action="{$SELF_URL}/submit_author_groups/" id="submit_author_groups" method="post" style="width:100%">
			<div class="form_text" >Минимальный авторский рейтинг</div>
			<div class="form_input"><input type="text" name="minimum_num_points_publicist" value="{$minimum_num_points_publicist.value}" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
		<div class="form_text">Минимальное кол-во статей</div>
                   <div class="form_input"><input type="text" name="minimum_num_articles_publicist" value="{$minimum_num_articles_publicist.value}" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
			<div class="form_text"></div>
	<div class="form_input"><h3>{$minimum_num_points_journalist.show_key}</h3></div>
	<div class="form_overflow"></div>
	
			<div class="form_text" >Минимальный авторский рейтинг</div>
			<div class="form_input"><input type="text" name="minimum_num_points_journalist" value="{$minimum_num_points_journalist.value}" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
		<div class="form_text">Минимальное кол-во статей</div>
                   <div class="form_input"><input type="text" name="minimum_num_articles_journalist" value="{$minimum_num_articles_journalist.value}" 	class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
			<div class="form_text"></div>
	<div class="form_input"><h3>{$minimum_num_points_expert.show_key}</h3></div>
	<div class="form_overflow"></div>
	
			<div class="form_text" >Минимальный авторский рейтинг</div>
			<div class="form_input"><input type="text" name="minimum_num_points_expert" value="{$minimum_num_points_expert.value}" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
		<div class="form_text">Минимальное кол-во статей</div>
                   <div class="form_input"><input type="text" name="minimum_num_articles_expert" value="{$minimum_num_articles_expert.value}" 	class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
			<div class="form_text"></div>
			<div class="form_input">
			<input type="submit" name="button" class="button" value="Изменить" onclick="ajax_me('submit_author_groups');" />
			</div>
               
		{form_csrf()}</form>
