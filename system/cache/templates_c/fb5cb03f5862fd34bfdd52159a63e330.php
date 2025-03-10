<table  width="100%">
		  	<tr style="background-color:#EDEDED">
				<td><b><?php echo lang ('a_name'); ?></b></td>
				<td><b><?php echo lang ('a_folder'); ?></b></td>
				<td><b><?php echo lang ('a_identif'); ?></b></td>
				<td><b><?php echo lang ('a_tpl'); ?></b></td>
				<td><b><?php echo lang ('a_image'); ?></b></td>
				<td></td>
			</tr>
			<tbody>

		<?php if(is_true_array($langs)){ foreach ($langs as $lang){ ?>
		<tr>
			<td><a onclick="edit_lang('<?php echo $lang['id']; ?>');"><?php echo $lang['lang_name']; ?></a></td>
			<td><?php echo $lang['folder']; ?></td>
			<td><?php echo $lang['identif']; ?></td>
			<td><?php echo $lang['template']; ?></td>
			<td><img src="<?php echo $lang['image']; ?>" width="16" height="16" /></td>
			<td><img src="<?php if(isset($THEME)){ echo $THEME; } ?>/images/delete.png" width="16" height="16" style="cursor:pointer;" alt="<?php echo lang ('a_delete'); ?> <?php echo $lang['lang_name']; ?>" title="<?php echo lang ('a_delete'); ?> <?php echo $lang['lang_name']; ?>" onclick="delete_lang('<?php echo $lang['id']; ?>');" /></td>
		</tr>
		<?php }} ?>

		</tbody>
 </table>

<hr/>
<?php echo lang ('a_by_default'); ?>: <select name="folder" id="def_lang_folder" onchange="set_def_lang($('def_lang_folder').value);">
		<?php if(is_true_array($langs)){ foreach ($langs as $lang){ ?>
			<option value="<?php echo $lang['id']; ?>" <?php if($lang['default'] == "1"): ?> selected="selected" <?php endif; ?>><?php echo $lang['lang_name']; ?></option>
		<?php }} ?>
		</select>
<hr/>
<div style="clear:left;" align="center">
<input type="submit" name="button"  class="button" value="Создать" onclick="MochaUI.languages_create_lang_w();" />
</div>
<?php $mabilis_ttl=1537947725; $mabilis_last_modified=1450852414; //D:\server\www\projects\articler.img\/templates/administrator/languages.tpl ?>