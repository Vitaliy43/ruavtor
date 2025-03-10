<div class="form_text"></div>
	<div class="form_input"><h3>Настройки интерфейса</h3></div>
	<div class="form_overflow"></div>

		<form action="<?php if(isset($SELF_URL)){ echo $SELF_URL; } ?>/submit_interface_settings/" id="submit_interface_settings" method="post" style="width:100%">
			<div class="form_text" ><?php echo $interface_language['show_key']; ?></div>
			<div class="form_input"><?php if(isset($languages_box)){ echo $languages_box; } ?></div>
			<div class="form_overflow"></div>
		
			<div class="form_text" ><?php echo $per_page_elements['show_key']; ?></div>
			<div class="form_input"><input type="text" name="per_page_elements" value="<?php echo $per_page_elements['value']; ?>" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
			<div class="form_text" ><?php echo $num_last_articles_sandbox['show_key']; ?></div>
			<div class="form_input"><input type="text" name="num_last_articles_sandbox" value="<?php echo $num_last_articles_sandbox['value']; ?>" class="textbox_long" /></div>
			<div class="form_overflow"></div>
			
			<div class="form_text" ><?php echo $modal_box_for_user['show_key']; ?></div>
			<div class="form_input" style="text-align:left;margin-left:-125px;">
			<?php if($modal_box_for_user['value']): ?>
				<input type="checkbox" name="modal_box_for_user" checked="checked" class="textbox_long" />
			<?php else:?>
				<input type="checkbox" name="modal_box_for_user" class="textbox_long" />
			<?php endif; ?>
			</div>
			<div class="form_overflow"></div>
			
			<div class="form_text" ><?php echo $modal_box_for_guest['show_key']; ?></div>
			<div class="form_input" style="text-align:left;margin-left:-125px;">
			<?php if($modal_box_for_guest['value']): ?>
				<input type="checkbox" name="modal_box_for_guest" checked="checked" class="textbox_long" />
			<?php else:?>
				<input type="checkbox" name="modal_box_for_guest" class="textbox_long" />
			<?php endif; ?>
			</div>
			<div class="form_overflow"></div>

			<div class="form_text"></div>
			<div class="form_input">
			<input type="submit" name="button" class="button" value="Изменить" onclick="ajax_me('submit_interface_settings');" />
			</div>
			<div class="form_overflow"></div>
		<?php echo form_csrf (); ?></form><?php $mabilis_ttl=1538468568; $mabilis_last_modified=1530182179; //D:\server\www\projects\articler.img\application\modules\articler/templates/interface_settings.tpl ?>