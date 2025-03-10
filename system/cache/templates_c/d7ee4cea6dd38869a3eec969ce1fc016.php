<div class="top-navigation">
<ul><li>
    <!--form id="tableFilter1" style="width:100%;" onsubmit="users_table1.filter(this.id); return false;"><?php echo lang ('amt_filter'); ?>:
        <select id="column">
            <option value="1"><?php echo lang ('amt_user_login'); ?></option>
            <option value="2"><?php echo lang ('amt_email'); ?></option>
            <option value="3"><?php echo lang ('amt_group'); ?></option>
        </select>
        <input type="text" id="keyword" />
        <input type="submit" value="<?php echo lang ('amt_search'); ?>" />
        <input type="reset" value="<?php echo lang ('amt_clean'); ?>" />
    <?php echo form_csrf (); ?></form-->
	<form id="list_users" style="width:100%;" action="<?php if(isset($SELF_URL)){ echo $SELF_URL; } ?>/genre_user_table/">Сортировать по:
       	<?php if(isset($select_sort)){ echo $select_sort; } ?>	
		<div style="padding:3px;">
	<div id="ajax_sort"></div>
</div>
    <?php echo form_csrf (); ?></form>
</li></ul>
</div>
<div class="form_overflow"></div>

<form action="<?php if(isset($SELF_URL)){ echo $SELF_URL; } ?>/actions/" id="users_f" method="post" style="width:100%">
<div id="sortable">
		  <table id="users_table1" >
				<th axis="string" width=""><?php echo lang ('amt_login'); ?></th>
				<th axis="string"><?php echo lang ('amt_user_login'); ?></th>
				<th axis="string"><?php echo lang ('amt_email'); ?></th>
				<th axis="string"><?php echo lang ('amt_group'); ?></th>
				<th axis="string"><?php echo lang ('amt_ban'); ?></th>
				<th axis="string"><?php echo lang ('amt_last_ip'); ?></th>
				<th axis="date"><?php echo lang ('amt_last_entry'); ?></th>
				<th axis="date"><?php echo lang ('amt_cr'); ?></th>
				<th axis="none"></th>
		<?php if(is_true_array($users)){ foreach ($users as $user){ ?>
		<tr id="<?php echo $page['number']; ?>">
			<td class="rightAlign">
			<div align="left">
			<input type="checkbox" value="<?php echo $user['id']; ?>" name="checkbox_<?php echo $user['id']; ?>" /> <?php echo $user['id']; ?>
			</div>
			</td>
			<td onclick="edit_user(<?php echo $user['id']; ?>); return false;"><?php echo $user['username']; ?></td>
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
		  </table>
</div>

<p align="right">
<br/>
<?php echo lang ('amt_with_selected'); ?>:
<input type="submit" name="ban"  class="button" value="<?php echo lang ('amt_to_ban'); ?>" onclick="$('users_f').action='<?php if(isset($SELF_URL)){ echo $SELF_URL; } ?>/actions/1/<?php if(isset($cur_page)){ echo $cur_page; } ?>/'; ajax_form('users_f','users_ajax_table');" />
<input type="submit" name="unban"  class="button" value="<?php echo lang ('amt_to_unban'); ?>" onclick="$('users_f').action='<?php if(isset($SELF_URL)){ echo $SELF_URL; } ?>/actions/2/<?php if(isset($cur_page)){ echo $cur_page; } ?>/'; ajax_form('users_f','users_ajax_table');" />
<input type="submit" name="delete"  class="button" style="font-weight:bold;" value="<?php echo lang ('amt_delete'); ?>" onclick="$('users_f').action='<?php if(isset($SELF_URL)){ echo $SELF_URL; } ?>/actions/3/<?php if(isset($cur_page)){ echo $cur_page; } ?>/'; ajax_form('users_f','users_ajax_table');" />
</p>

<?php echo form_csrf (); ?></form>

<div align="center" style="padding:5px;">
<?php if(isset($paginator)){ echo $paginator; } ?>
</div>
		<script type="text/javascript">
			window.addEvent('domready', function(){
				users_table1 = new sortableTable('users_table1', {overCls: 'over', onClick: function(){}});
			});
			
			jq = jQuery.noConflict();
			function users_ajax_sort(){
				var point=jq('#users_sort option:selected').val();
				ajax_div('users_ajax_table', base_url + 'admin/components/cp/user_manager/genre_user_table/'+point);

			}
			
		</script>

<?php $mabilis_ttl=1538476685; $mabilis_last_modified=1450852455; //D:\server\www\projects\articler.img\application\modules\user_manager/templates/users_table.tpl ?>