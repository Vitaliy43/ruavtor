{literal}
<script type="text/javascript">
	
	function show_articles(advert_id){
		var content = $('.container_list_articles').html();
		$.colorbox({html: content, open: true, opacity: 0.5});
		$('#advert_id').val(advert_id);
	}
	
	
	
	
</script>
{/literal}
<h2>Рекламные блоки</h2>
<div id="list_adverts">
	<div id="add_advert" style="margin-bottom:15px;text-align:center;">
		<a href="{site_url('moderator/advert_add')}" style="color:inherit;text-decoration:none;" target="_blank">
			<img width="16" height="16" src="/{$THEME}/images/add.png" style="margin-bottom:-3px;"/>
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
	{foreach $blocks as $block}
		<tr>
			<td align="left" valign="top">
				{$block.name}
			</td>
			<td valign="top">
				{$block.type_russian}
			</td>
			<td valign="top">
				<table class="content_block">
					<tr>
						<!--td valign="top">
							<textarea class="code" style="width: 250px;height: 50px;" rows="2" cols="6" value="{$block.code}">
								{$block.code}
							</textarea>
						</td>
						<td valign="top">
							<button onclick="update_block(this,'{$block.id}');return false;" style="margin-left: 5px; padding: 3px;">Обновить</button>
						</td-->
						<div style="max-width: 400px; overflow: scroll; max-height: 200px; margin-bottom: 10px;">
							{$block.code}
						</div>
					</tr>
				</table>
				
				
			</td>
			
			<td align="center">
				<a href="/moderator/advert_update/{$block.id}" title="Редактировать блок" style="text-decoration: none;">
					<img src="/templates/ruautor/images/icon_edit.png"/>
				</a>
				
				<a href="/moderator/advert_delete/{$block.id}" title="Удалить блок" onclick="delete_advert(this,{$block.id});return false;" style="margin-left: 10px; text-decoration: none;">
					<img src="/templates/ruautor/images/icon_delete.png"/>
				</a>
			</td>
			
		</tr>
	{/foreach}

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
	{foreach $default_blocks as $block}
		<tr>
			<td align="left" valign="top">
				{$block.name}
			</td>
			<td valign="top">
				{$block.type_russian}
			</td>
			<td valign="top">
				<table class="content_block">
					<tr>
						<div style="max-width: 400px; overflow: scroll; max-height: 200px; margin-bottom: 10px;">
							{$block.code}
						</div>
					</tr>
				</table>
				
				
			</td>
			
			<td align="center">
				<a href="/moderator/default_advert_update/{$block.id}" title="Редактировать блок" style="text-decoration: none;">
					<img src="/templates/ruautor/images/icon_edit.png"/>
				</a>
				
			</td>
			
		</tr>
	{/foreach}

</table>
</div>
<div class="container_list_articles" style="display: none;">
	{$list_articles}
</div>
<input type="hidden" id="advert_id"/>