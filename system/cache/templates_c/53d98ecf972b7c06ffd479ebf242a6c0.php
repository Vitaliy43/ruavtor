<div class="form_text"></div>
	<div class="form_input"><h3>Настройка рейтингов активности</h3></div>
	<div class="form_overflow"></div>

		<form action="<?php if(isset($SELF_URL)){ echo $SELF_URL; } ?>/rating_activity/" id="rating_activity" method="post" style="width:100%">
			<div class="form_text" ><?php echo $activity_day['show_key']; ?></div>
			<div class="form_input"><input type="text" name="activity_day" value="<?php echo $activity_day['value']; ?>" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
                        <div class="form_text"><?php echo $unactivity_day['show_key']; ?></div>
                        <div class="form_input"><input type="text" name="unactivity_day" value="<?php echo $unactivity_day['value']; ?>" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
			<div class="form_text"><?php echo $lowest_rating['show_key']; ?></div>
                        <div class="form_input"><input type="text" name="lowest_rating" value="<?php echo $lowest_rating['value']; ?>" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
			<div class="form_text"><?php echo $highest_rating['show_key']; ?></div>
                        <div class="form_input"><input type="text" name="highest_rating" value="<?php echo $highest_rating['value']; ?>" class="textbox_long" /></div>
			<div class="form_overflow"></div>

			<div class="form_text"></div>
			<div class="form_input">
			<input type="submit" name="button" class="button" value="Изменить" onclick="ajax_me('rating_activity');" />
			</div>
			<div class="form_overflow"></div>
		<?php echo form_csrf (); ?></form>
		
	<div class="form_text"></div>
	<div class="form_input"><h3>Настройка авторского рейтинга</h3></div>
	<div class="form_overflow"></div>
			<form action="<?php if(isset($SELF_URL)){ echo $SELF_URL; } ?>/rating_author/" id="rating_author" method="post" style="width:100%">
	<?php if(is_true_array($rating_for_homefeed)){ foreach ($rating_for_homefeed as $rating){ ?>
	
	<div class="form_text"><?php echo $rating['show_key']; ?></div>
    <div class="form_input"><input type="text" name="rating_for_homefeed_<?php echo $rating['heading_id']; ?>" value="<?php echo $rating['value']; ?>" class="textbox_long" /></div>
	<div class="form_overflow"></div>	
	<?php }} ?>
	
	<!--div class="form_text"><?php echo $rating_for_homefeed_2['show_key']; ?>.<?php echo $type_headings['2']; ?> рубрики</div>
    <div class="form_input"><input type="text" name="rating_for_homefeed_2" value="<?php echo $rating_for_homefeed_2['value']; ?>" class="textbox_long" /></div>
	<div class="form_overflow"></div-->
	
	<div class="form_text"><?php echo $hold_for_homefeed['show_key']; ?></div>
    <div class="form_input"><input type="text" name="hold_for_homefeed" value="<?php echo $hold_for_homefeed['value']; ?>" class="textbox_long" /></div>
	<div class="form_overflow"></div>
	
	<div class="form_text"><?php echo $add_for_homefeed['show_key']; ?></div>
    <div class="form_input"><input type="text" name="add_for_homefeed" value="<?php echo $add_for_homefeed['value']; ?>" class="textbox_long" /></div>
	<div class="form_overflow"></div>
	
	<div class="form_text"><?php echo $bonus_for_homefeed['show_key']; ?></div>
    <div class="form_input"><input type="text" name="bonus_for_homefeed" value="<?php echo $bonus_for_homefeed['value']; ?>" class="textbox_long" /></div>
	<div class="form_overflow"></div>
	
	<div class="form_text"></div>
			<div class="form_input">
			<input type="submit" name="button" class="button" value="Изменить" onclick="ajax_me('rating_author');" />
			</div>
	
				<?php echo form_csrf (); ?></form>
<?php $mabilis_ttl=1538468568; $mabilis_last_modified=1450852452; //D:\server\www\projects\articler.img\application\modules\articler/templates/rating_system.tpl ?>