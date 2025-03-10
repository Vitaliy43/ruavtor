
<script type="text/javascript">
	
	function show_articles(advert_id){
		var content = $('.container_list_articles').html();
		$.colorbox({html: content, open: true, opacity: 0.5});
		$('#advert_id').val(advert_id);
	}
	
	
	
	
</script>

<h2>Рекламные блоки</h2>
<div id="list_adverts">
	<div id="add_advert" style="margin-bottom:15px;text-align:center;">
		<a href="<?php echo site_url ('moderator/advert_add'); ?>" style="color:inherit;text-decoration:none;" target="_blank">
			<img width="16" height="16" src="/<?php if(isset($THEME)){ echo $THEME; } ?>/images/add.png" style="margin-bottom:-3px;"/>
			<span style="text-decoration:underline;color:#166D66;">Добавить рекламный блок</span>
		</a>
	</div>
<div class="block_tabs">
	
</div>
<table width="100%">
	<tr>
		<th align="left">Название</th>
		<th align="left">Тип</th>
		<th align="left" >Содержимое</th>
		<th>Действия</th>
	</tr>
	<?php if(is_true_array($blocks)){ foreach ($blocks as $block){ ?>
		<tr>
			<td align="left" valign="top">
				<?php echo $block['name']; ?>
			</td>
			<td valign="top">
				<?php echo $block['type_russian']; ?>
			</td>
			<td valign="top">
				<table class="content_block">
					<tr>
						<!--td valign="top">
							<textarea class="code" style="width: 250px;height: 50px;" rows="2" cols="6" value="<?php echo $block['code']; ?>">
								<?php echo $block['code']; ?>
							</textarea>
						</td>
						<td valign="top">
							<button onclick="update_block(this,'<?php echo $block['id']; ?>');return false;" style="margin-left: 5px; padding: 3px;">Обновить</button>
						</td-->
						<div style="max-width: 400px; overflow: scroll; max-height: 200px; margin-bottom: 10px;">
							<?php echo $block['code']; ?>
						</div>
					</tr>
				</table>
				
				
			</td>
			
			<td align="center">
				<a href="/moderator/advert_update/<?php echo $block['id']; ?>" title="Редактировать блок" style="text-decoration: none;">
					<img src="/templates/ruautor/images/icon_edit.png"/>
				</a>
				
				<a href="/moderator/advert_delete/<?php echo $block['id']; ?>" title="Удалить блок" onclick="delete_advert(this,<?php echo $block['id']; ?>);return false;" style="margin-left: 10px; text-decoration: none;">
					<img src="/templates/ruautor/images/icon_delete.png"/>
				</a>
			</td>
			
		</tr>
	<?php }} ?>

</table>
</div>
<hr width="100%" style="margin-top: 20px;"/>
<div style="margin-top: 15px;">
	<h3>Рекламные блоки по умолчанию</h3>
	<table width="100%">
	<tr>
		<th align="left">Название</th>
		<th align="left">Тип</th>
		<th align="left" >Содержимое</th>
		<th>Действия</th>
	</tr>
	<?php if(is_true_array($default_blocks)){ foreach ($default_blocks as $block){ ?>
		<tr>
			<td align="left" valign="top">
				<?php echo $block['name']; ?>
			</td>
			<td valign="top">
				<?php echo $block['type_russian']; ?>
			</td>
			<td valign="top">
				<table class="content_block">
					<tr>
						<div style="max-width: 400px; overflow: scroll; max-height: 200px; margin-bottom: 10px;">
							<?php echo $block['code']; ?>
						</div>
					</tr>
				</table>
				
				
			</td>
			
			<td align="center">
				<a href="/moderator/default_advert_update/<?php echo $block['id']; ?>" title="Редактировать блок" style="text-decoration: none;">
					<img src="/templates/ruautor/images/icon_edit.png"/>
				</a>
				
			</td>
			
		</tr>
	<?php }} ?>

</table>
</div>
<div class="container_list_articles" style="display: none;">
	<?php if(isset($list_articles)){ echo $list_articles; } ?>
</div>
<input type="hidden" id="advert_id"/><?php $mabilis_ttl=1540917362; $mabilis_last_modified=1507637590; //D:\server\www\projects\articler.img\/templates//main/adverts.tpl ?>