<div class="form_text"></div>
	<div class="form_input"><h3>Настройки статистики</h3></div>
	<div class="form_overflow"></div>

		<form action="<?php if(isset($SELF_URL)){ echo $SELF_URL; } ?>/submit_statistic_settings/" id="submit_statistic_settings" method="post" style="width:100%">
			<div class="form_text" ><?php echo $use_limit_visites['show_key']; ?></div>
			<div class="form_input" >
			<input type="checkbox" name="use_limit_visites" class="textbox_long" style="width:10px;margin-left:2px;"
			<?php if($use_limit_visites['value']  == '1'): ?>
			checked=""
			<?php endif; ?>
			/></div>
			<div class="form_overflow"></div>
			
			<div class="form_text" ><?php echo $limit_visites['show_key']; ?></div>
			<div class="form_input"><input type="text" name="limit_visites" value="<?php echo $limit_visites['value']; ?>" class="textbox_long" /></div>
			<div class="form_overflow"></div>

			<div class="form_text"></div>
			<div class="form_input">
			<input type="submit" name="button" class="button" value="Изменить" onclick="ajax_me('submit_statistic_settings');" />
			</div>
			<div class="form_overflow"></div>
		<?php echo form_csrf (); ?></form><?php $mabilis_ttl=1538468569; $mabilis_last_modified=1450852452; //D:\server\www\projects\articler.img\application\modules\articler/templates/statistic_settings.tpl ?>