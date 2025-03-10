<div id="user_manager_tabs">
<h4 title=""><?php echo lang ('amt_users'); ?></h4>
<div style="padding:3px;">
	<div id="users_ajax_table"></div>
</div>
<h4 title=""><?php echo lang ('amt_authors'); ?></h4>
<div style="padding:3px;">
	<div id="authors_ajax_table"></div>
</div>

<h4 title=""><?php echo lang ('amt_to_create'); ?></h4>
<div style="padding:3px;">

	<div class="form_text"></div>
	<div class="form_input"><h3><?php echo lang ('amt_create_new_user'); ?></h3></div>
	<div class="form_overflow"></div>

		<form action="<?php if(isset($SELF_URL)){ echo $SELF_URL; } ?>/create_user/" id="user_create" method="post" style="width:100%">
			<div class="form_text"><?php echo lang ('amt_user_login'); ?>:</div>
			<div class="form_input"><input type="text" name="username" value="" class="textbox_long" /></div>
			<div class="form_overflow"></div>

                        <div class="form_text"><?php echo lang ('amt_new_pass'); ?>:</div>
                        <div class="form_input"><input type="password" name="password" value="" class="textbox_long" /></div>
			<div class="form_overflow"></div>

                        <div class="form_text"><?php echo lang ('amt_new_pass_confirm'); ?>:</div>
                        <div class="form_input"><input type="password" name="password_conf" value="" class="textbox_long" /></div>
                        <div class="form_overflow"></div>

                        <div class="form_text"><?php echo lang ('amt_email'); ?>:</div>
			<div class="form_input"><input type="text" name="email" value="" class="textbox_long" /></div>
			<div class="form_overflow"></div>

			<div class="form_text"><?php echo lang ('amt_group'); ?>:</div>
			<div class="form_input">
				<select name="role">
				<?php if(is_true_array($roles)){ foreach ($roles as $role){ ?>
				  <option value ="<?php echo $role['id']; ?>"><?php echo $role['alt_name']; ?></option>
				<?php }} ?>
				</select>
			</div>
			<div class="form_overflow"></div>

			<div class="form_text"></div>
			<div class="form_input">
			<input type="submit" name="button" class="button" value="<?php echo lang ('amt_to_create'); ?>" onclick="ajax_me('user_create');" />
			</div>
			<div class="form_overflow"></div>
		<?php echo form_csrf (); ?></form>
</div>

<h4 title=""><?php echo lang ('amt_search'); ?></h4>
<div style="padding:3px;">

	<div class="form_text"></div>
	<div class="form_input"><h3><?php echo lang ('amt_user_search'); ?></h3></div>
	<div class="form_overflow"></div>

	<form action="<?php if(isset($SELF_URL)){ echo $SELF_URL; } ?>/search/" id="user_search" method="post" style="width:100%">

	<div class="form_text"><?php echo lang ('amt_login_or_mail'); ?></div>
	<div class="form_input">
	<input type="text" name="s_data" id="s_data" value="" class="textbox_long" />
	</div>
	<div class="form_overflow"></div>

	<div class="form_text"><?php echo lang ('amt_group'); ?>:</div>
	<div class="form_input">
		<select name="role" id="role">
		 <option value ="0"><?php echo lang ('amt_all_groups'); ?></option>
		<?php if(is_true_array($roles)){ foreach ($roles as $role){ ?>
		  <option value ="<?php echo $role['id']; ?>"><?php echo $role['alt_name']; ?></option>
		<?php }} ?>
		</select>
	</div>
	<div class="form_overflow"></div>

	<div class="form_text"></div>
	<div class="form_input">
		<input type="submit" class="button" value="<?php echo lang ('amt_search'); ?>" onclick="ajax_form('user_search','u_search_result');" />
	</div>
	<div class="form_overflow"></div>
	<?php echo form_csrf (); ?></form>

	<hr/>

	<div id="u_search_result"></div>

</div> <!-- /user_manager_tabs -->

<h4 title=""><?php echo lang ('amt_groups'); ?></h4>
    <div style="padding:3px;" id="groups_block"></div>

<h4 title="">Настройки регистрации</h4>
    <div style="padding:3px;" id="settings_block"></div>

</div>
<script type="text/javascript">
		var users_tabs = new SimpleTabs('user_manager_tabs', {
	    	selector: 'h4'
		});

		ajax_div('users_ajax_table', base_url + 'admin/components/cp/user_manager/genre_user_table/');
		ajax_div('authors_ajax_table', base_url + 'admin/components/cp/articler/list_authors/');
		ajax_div('settings_block', base_url + 'admin/components/cp/user_manager/settings/');
		ajax_div('groups_block', base_url + 'admin/components/cp/user_manager/groups_index/');
		ajax_div('perms_editor_block', base_url + 'admin/components/cp/user_manager/show_edit_prems_tpl/');

		function edit_user(user_id)
		{
			//create user_edit window
				new MochaUI.Window({
					id: 'user_edit_window',
					title: 'Пользователь ID: ' + user_id,
					loadMethod: 'xhr',
					contentURL: base_url + 'admin/components/cp/user_manager/edit_user/' + user_id,
					width: 490,
					height: 300
				});
		}
		
		function edit_author(user_id, username)
		{
			//create author_edit window
				new MochaUI.Window({
					id: 'user_edit_window',
					title: 'Редактирование автора ' + username,
					loadMethod: 'xhr',
					contentURL: base_url + 'admin/components/cp/articler/edit_author/' + user_id,
					width: 500,
					height: 300
				});
		}
		
</script>

<?php $mabilis_ttl=1538476684; $mabilis_last_modified=1475229154; //D:\server\www\projects\articler.img\application\modules\user_manager/templates/main.tpl ?>