
<div id="list_articles">
	<?php if(isset($message)): ?>
	<div><?php if(isset($message)){ echo $message; } ?></div>
	<?php endif; ?>
	
<?php if(count($exceptions) > 0): ?>
<h2>
Страницы исключений
</h2>
<div id="add_article" style="margin-bottom:15px;text-align:center;">
		<a href="<?php echo site_url ('moderator/refer_exceptions/add'); ?>" style="color:inherit;text-decoration:none;">
			<img width="16" height="16" src="/<?php if(isset($THEME)){ echo $THEME; } ?>/images/add.png" style="margin-bottom:-3px;"/>
			<span style="text-decoration:underline;margin-left:-9px;color:#166D66;">Добавить исключение</span>
		</a>
	</div>
<table width="50%">
	<tr>
		<th>Ссылка</th>
		<th>Описание</th>
		<th></th>
	</tr>
	<?php if(is_true_array($exceptions)){ foreach ($exceptions as $exception){ ?>
		<tr id="row_<?php echo $exception['id']; ?>">
		
			<td><?php echo $exception['page']; ?></td>
			<td><?php echo $exception['description']; ?></td>
			<td>
				<a href="<?php echo site_url ('moderator/refer_exceptions/delete'); ?>/<?php echo $exception['id']; ?>" title="Удалить исключение" onclick="delete_exception(this,<?php echo $exception['id']; ?>);return false;" id="delete_<?php echo $exception['id']; ?>">
				<img src="/<?php if(isset($THEME)){ echo $THEME; } ?>/images/icon_delete.png" width="12" height="12"/>
				</a>
			</td>
		</tr>
	<?php }} ?>

</table>

<div id="container_refer_add" style="margin-top: 15px;">
<div>Период реферальной добавки, дней</div>
	<input type="text" id="add_refer" value="<?php echo $arr['add']; ?>"/><span style="display: none;" >..Обновлено</span>
<div>Начисление юзеру во время льготного периода, %</div>
	<input type="text" id="add_user" value="<?php echo $arr['add_user']; ?>"/><span style="display: none;" >..Обновлено</span>
<div> Процент рефералу от привлеченных юзеров, %</div>
	<input type="text" id="add_user_refer" value="<?php echo $arr['add_refer']; ?>"/><span style="display: none;" >..Обновлено</span>
	<div style="margin-top: 5px;" ><button value="Обновить" onclick="change_settings();" id="refer_add_button">Обновить</button>
</div>
</div>
<?php else:?>
	<div>Раздел пуст</div>
<?php endif; ?>
</div><?php $mabilis_ttl=1537515800; $mabilis_last_modified=1450852421; //D:\server\www\projects\articler.img\/templates//ruautor/refer_settings.tpl ?>