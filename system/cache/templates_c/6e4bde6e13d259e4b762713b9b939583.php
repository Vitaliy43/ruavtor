<div class="form_text"></div>
	<div class="form_input"><h3>Настройка комментирования и плюсования</h3></div>
	<div class="form_overflow"></div>

		<form action="<?php if(isset($SELF_URL)){ echo $SELF_URL; } ?>/comments_settings/" id="comments_settings" method="post" style="width:100%">
			<div class="form_text" ><?php echo $per_page_comments['show_key']; ?></div>
			<div class="form_input"><input type="text" name="per_page_comments" value="<?php echo $per_page_comments['value']; ?>" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
		<div class="form_text"><?php echo $num_last_comments['show_key']; ?></div>
                        <div class="form_input"><input type="text" name="num_last_comments" value="<?php echo $num_last_comments['value']; ?>" 				class="textbox_long" /></div>
			<div class="form_overflow"></div>
                <div class="form_text"><?php echo $add_rating_by_plus['show_key']; ?></div>
				
                <div class="form_input"><input type="text" name="add_rating_by_plus" value="<?php echo $add_rating_by_plus['value']; ?>" class="textbox_long" /></div>
			<div class="form_overflow"></div>
				
			<div class="form_text"><?php echo $limit_on_add_plus['show_key']; ?></div>
                        <div class="form_input"><input type="text" name="limit_on_add_plus" value="<?php echo $limit_on_add_plus['value']; ?>" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
			<div class="form_text"><?php echo $quantity_articles_for_assess['show_key']; ?></div>
                        <div class="form_input"><input type="text" name="quantity_articles_for_assess" value="<?php echo $quantity_articles_for_assess['value']; ?>" class="textbox_long" /></div>
			<div class="form_overflow"></div>

			<div class="form_text"></div>
			<div class="form_input">
			<input type="submit" name="button" class="button" value="Изменить" onclick="ajax_me('comments_settings');" />
			</div>
			<div class="form_overflow"></div>
		<?php echo form_csrf (); ?></form><?php $mabilis_ttl=1538468569; $mabilis_last_modified=1450852452; //D:\server\www\projects\articler.img\application\modules\articler/templates/comments_system.tpl ?>