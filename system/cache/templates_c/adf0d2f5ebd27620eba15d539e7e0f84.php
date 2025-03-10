<div id="sortable">
		  <table id="users_table2">
		  	<thead>
				<th axis="string" width=""><?php echo lang ('amt_id'); ?></th>
				<th axis="string"><?php echo lang ('amt_user_login'); ?></th>
				<th axis="string"><?php echo lang ('amt_email'); ?></th>
				<th axis="string"><?php echo lang ('amt_group'); ?></th>
				<th axis="string"><?php echo lang ('amt_ban'); ?></th>
				<th axis="string"><?php echo lang ('amt_last_ip'); ?></th>
				<th axis="date"><?php echo lang ('amt_last_entry'); ?></th>
				<th axis="date"><?php echo lang ('amt_cr'); ?></th>
				<th axis="none"></th>
			</thead>
			<tbody>
		<?php if(is_true_array($users)){ foreach ($users as $user){ ?>
		<tr id="<?php echo $page['number']; ?>">
			<td class="rightAlign">
			<div align="left"><?php echo $user['id']; ?></div>
			</td>
			<td><?php echo $user['username']; ?></td>
			<td><?php echo $user['email']; ?></td>
			<td><?php echo $user['role_alt_name']; ?></td>
			<td><?php echo $user['banned']; ?></td>
			<td><?php echo $user['last_ip']; ?></td>
			<td><?php echo $user['last_login']; ?></td>
			<td><?php echo $user['created']; ?></td>
			<td  class="rightAlign">
			<img onclick="edit_user(<?php echo $user['id']; ?>);" style="cursor:pointer" src="<?php if(isset($THEME)){ echo $THEME; } ?>/images/edit_page.png" width="16" height="16" title="<?php echo lang ('amt_edit'); ?>" />
			</td>
		</tr>
		<?php }} ?>
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</tfoot>
		  </table>
</div>

<?php if(isset($pagination)){ echo $pagination; } ?>

<p></p>

<!--
<div class="options_bar">
    <form id="tableFilter2" style="width:100%;" onsubmit="users_table2.filter(this.id); return false;">Фильтр:
        <select id="column">
            <option value="1">Логин</option>
            <option value="2">E-Mail</option>
            <option value="3">Группа</option>
        </select>
        <input type="text" id="keyword" />
        <input type="submit" value="Поиск" />
        <input type="reset" value="Очистить" />
    <?php echo form_csrf (); ?></form>
</div>
-->
		<script type="text/javascript">
			window.addEvent('domready', function(){
				users_table2 = new sortableTable('users_table2', {overCls: 'over', onClick: function(){}});
			});
		</script>

<?php $mabilis_ttl=1537947802; $mabilis_last_modified=1450852455; //D:\server\www\projects\articler.img\application\modules\user_manager/templates/users_table_search.tpl ?>