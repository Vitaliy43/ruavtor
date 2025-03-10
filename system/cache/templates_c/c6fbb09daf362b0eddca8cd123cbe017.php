<div id="user_manager_tabs">
<h4 title="">Рейтинги</h4>
<div style="padding:3px;">
	<div id="rating_system"></div>
</div>

<h4 title="">Комментирование и плюсование</h4>
<div style="padding:3px;">
	<div id="comments_system"></div>

</div>

<h4 title="">Статусы авторов</h4>
	 <div style="padding:3px;" id="author_groups"></div>

<h4 title="">Настройки почты</h4>
    <div style="padding:3px;" id="mail_settings"></div>

<h4 title="">Интерфейс</h4>
    <div style="padding:3px;" id="interface_settings"></div>
	
<h4 title="">Финансы</h4>
    <div style="padding:3px;" id="finance_system"></div>
	
<h4 title="">Статистика</h4>
    <div style="padding:3px;" id="statistic_settings"></div>
	
</div>
<script type="text/javascript">

		var users_tabs = new SimpleTabs('user_manager_tabs', {
	    	selector: 'h4'
		});

		ajax_div('rating_system', base_url + 'admin/components/cp/articler/rating_system/');
		ajax_div('comments_system', base_url + 'admin/components/cp/articler/comments_system/');
		ajax_div('author_groups', base_url + 'admin/components/cp/articler/author_groups/');
		ajax_div('finance_system', base_url + 'admin/components/cp/articler/finance_system/');
		ajax_div('mail_settings', base_url + 'admin/components/cp/articler/mail_settings');
		ajax_div('interface_settings', base_url + 'admin/components/cp/articler/interface_settings');
		ajax_div('statistic_settings', base_url + 'admin/components/cp/articler/statistic_settings');

		function edit_user(user_id)
		{
			//create user_edit window
				new MochaUI.Window({
					id: 'user_edit_window',
					title: 'Пользователь ID: ' + user_id,
					loadMethod: 'xhr',
					contentURL: base_url + 'admin/components/cp/articler/edit_user/' + user_id,
					width: 490,
					height: 300
				});
		}
		
		
</script>

<?php $mabilis_ttl=1538468567; $mabilis_last_modified=1486630890; //D:\server\www\projects\articler.img\application\modules\articler/templates/main.tpl ?>