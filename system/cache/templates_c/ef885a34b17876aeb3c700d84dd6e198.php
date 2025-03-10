<div class="form_text"></div>
	<div class="form_input"><h3>Финансовая система</h3></div>
	<div class="form_overflow"></div>

<form action="<?php if(isset($SELF_URL)){ echo $SELF_URL; } ?>/submit_finance_system/" id="submit_finance_system" method="post" style="width:100%">

			<div class="form_text"><h3>Гонорары авторам в BMP</h3></div>
			<div class="form_input"></div>
			<div class="form_overflow"></div>
			
			<div class="form_text" ><?php echo $pay_publicist['show_key']; ?></div>
			<div class="form_input"><input type="text" name="pay_publicist" value="<?php echo $pay_publicist['value']; ?>" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
			<div class="form_text" ><?php echo $pay_journalist['show_key']; ?></div>
			<div class="form_input"><input type="text" name="pay_journalist" value="<?php echo $pay_journalist['value']; ?>" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
			<div class="form_text" ><?php echo $pay_expert['show_key']; ?></div>
			<div class="form_input"><input type="text" name="pay_expert" value="<?php echo $pay_expert['value']; ?>" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
			<div class="form_text"><h3>Условия выплаты за статьи</h3></div>
			<div class="form_input"></div>
			<div class="form_overflow"></div>
			
			<div class="form_text" ><?php echo $lowest_rating_for_pay['show_key']; ?></div>
			<div class="form_input"><input type="text" name="lowest_rating_for_pay" value="<?php echo $lowest_rating_for_pay['value']; ?>" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
			<div class="form_text" ><?php echo $lowest_num_articles_for_pay['show_key']; ?></div>
			<div class="form_input"><input type="text" name="lowest_num_articles_for_pay" value="<?php echo $lowest_num_articles_for_pay['value']; ?>" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
			<div class="form_text" ><?php echo $lowest_sum_for_pay['show_key']; ?></div>
			<div class="form_input"><input type="text" name="lowest_sum_for_pay" value="<?php echo $lowest_sum_for_pay['value']; ?>" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
			<div class="form_text"><h3>Условия выплаты за посещаемость</h3></div>
			<div class="form_input"></div>
			<div class="form_overflow"></div>
			
			<div class="form_text" ><?php echo $num_visites_for_pay['show_key']; ?></div>
			<div class="form_input"><input type="text" name="num_visites_for_pay" value="<?php echo $num_visites_for_pay['value']; ?>" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
			<div class="form_text" ><?php echo $pay_for_visit['show_key']; ?></div>
			<div class="form_input"><input type="text" name="pay_for_visit" value="<?php echo $pay_for_visit['value']; ?>" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
			<div class="form_text" ><?php echo $corrective_q['show_key']; ?></div>
			<div class="form_input"><input type="text" name="corrective_q" value="<?php echo $corrective_q['value']; ?>" class="textbox_long" /></div>
			<div class="form_overflow"></div>

			<div class="form_text"></div>
			<div class="form_input">
			<input type="submit" name="button" class="button" value="Изменить" onclick="ajax_me('submit_finance_system');" />
			</div>
			<div class="form_overflow"></div>
		<?php echo form_csrf (); ?></form><?php $mabilis_ttl=1538468569; $mabilis_last_modified=1485759674; //D:\server\www\projects\articler.img\application\modules\articler/templates/finance_system.tpl ?>