
<?php if($is_update): ?>
	<h2>Редактировать рекламный блок</h2>
<?php else:?>
	<h2>Добавить рекламный блок</h2>
<?php endif; ?>

<?php if($message): ?>
	<div style="color: red;"><?php if(isset($message)){ echo $message; } ?></div>
<?php endif; ?>
<div class="container_editor">
	<?php if($is_update): ?>
		<form action="<?php echo site_url ('moderator/advert_update/'.$advert['id']); ?>" method="POST" name="form_add_advert" id="form_add_advert" onsubmit="validate_advert_form();return false;">
	<?php else:?>
		<form action="<?php echo site_url ('moderator/advert_add'); ?>" method="POST" name="form_add_advert" id="form_add_advert" onsubmit="validate_advert_form();return false;">
	<?php endif; ?>


<table cellspacing="10" style="margin-top: 10px;">
	<tr>
		<td valign="top">Название</td>
		<td valign="top" align="center" style="padding-left: 20px;">
			<?php if(isset($advert['name'])): ?>
				<input type="text" name="name" id="advert_name" value="<?php echo $advert['name']; ?>" style="width: 200px;"/>
			<?php else:?>
				<input type="text" name="name" id="advert_name" value="" style="width: 200px;"/>
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="font-size: 3px;">&nbsp;</td>
	</tr>
	<tr>
		<td valign="top">Место отображения</td>
		<td valign="top" align="center" style="padding-left: 20px;">
			<?php if(isset($types_box)){ echo $types_box; } ?>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="font-size: 3px;">&nbsp;</td>
	</tr>
	<tr>
		<td valign="top">Код</td>
		<td valign="top" align="center" style="padding-left: 20px;">
			<textarea name="code" rows="2" cols="10" style="width: 200px;" id="advert_code"><?php echo $advert['code']; ?></textarea>
		</td>
	</tr>
</table>


<div class="button_publish">
	<input type="submit" name="submit" value="Сохранить"/>
</div>
<br>
<?php echo form_csrf (); ?>
</form>
</div>


<?php $mabilis_ttl=1540917331; $mabilis_last_modified=1507637879; //D:\server\www\projects\articler.img\/templates//main/advert_add.tpl ?>