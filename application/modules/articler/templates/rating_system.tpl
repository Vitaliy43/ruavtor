<div class="form_text"></div>
	<div class="form_input"><h3>Настройка рейтингов активности</h3></div>
	<div class="form_overflow"></div>

		<form action="{$SELF_URL}/rating_activity/" id="rating_activity" method="post" style="width:100%">
			<div class="form_text" >{$activity_day.show_key}</div>
			<div class="form_input"><input type="text" name="activity_day" value="{$activity_day.value}" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
                        <div class="form_text">{$unactivity_day.show_key}</div>
                        <div class="form_input"><input type="text" name="unactivity_day" value="{$unactivity_day.value}" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
			<div class="form_text">{$lowest_rating.show_key}</div>
                        <div class="form_input"><input type="text" name="lowest_rating" value="{$lowest_rating.value}" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
			<div class="form_text">{$highest_rating.show_key}</div>
                        <div class="form_input"><input type="text" name="highest_rating" value="{$highest_rating.value}" class="textbox_long" /></div>
			<div class="form_overflow"></div>

			<div class="form_text"></div>
			<div class="form_input">
			<input type="submit" name="button" class="button" value="Изменить" onclick="ajax_me('rating_activity');" />
			</div>
			<div class="form_overflow"></div>
		{form_csrf()}</form>
		
	<div class="form_text"></div>
	<div class="form_input"><h3>Настройка авторского рейтинга</h3></div>
	<div class="form_overflow"></div>
			<form action="{$SELF_URL}/rating_author/" id="rating_author" method="post" style="width:100%">
	{foreach $rating_for_homefeed as $rating}
	
	<div class="form_text">{$rating.show_key}</div>
    <div class="form_input"><input type="text" name="rating_for_homefeed_{$rating.heading_id}" value="{$rating.value}" class="textbox_long" /></div>
	<div class="form_overflow"></div>	
	{/foreach}
	
	<!--div class="form_text">{$rating_for_homefeed_2.show_key}.{$type_headings.2} рубрики</div>
    <div class="form_input"><input type="text" name="rating_for_homefeed_2" value="{$rating_for_homefeed_2.value}" class="textbox_long" /></div>
	<div class="form_overflow"></div-->
	
	<div class="form_text">{$hold_for_homefeed.show_key}</div>
    <div class="form_input"><input type="text" name="hold_for_homefeed" value="{$hold_for_homefeed.value}" class="textbox_long" /></div>
	<div class="form_overflow"></div>
	
	<div class="form_text">{$add_for_homefeed.show_key}</div>
    <div class="form_input"><input type="text" name="add_for_homefeed" value="{$add_for_homefeed.value}" class="textbox_long" /></div>
	<div class="form_overflow"></div>
	
	<div class="form_text">{$bonus_for_homefeed.show_key}</div>
    <div class="form_input"><input type="text" name="bonus_for_homefeed" value="{$bonus_for_homefeed.value}" class="textbox_long" /></div>
	<div class="form_overflow"></div>
	
	<div class="form_text"></div>
			<div class="form_input">
			<input type="submit" name="button" class="button" value="Изменить" onclick="ajax_me('rating_author');" />
			</div>
	
				{form_csrf()}</form>
