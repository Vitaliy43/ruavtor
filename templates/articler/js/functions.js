
var content_elements = new Array();

function myAlert(text){
	
	jAlert(text,'Статейник"');
}

function str_replace(search, replace, subject) {
	return subject.split(search).join(replace);
}

function offset_menu(){
	
	var coords_menu = $('#block_menu').position();
	var coords_content = $('#content').position();
	var differ = coords_menu.left - coords_content.left;
	differ = Math.round(differ);
	var standard_differ = 29;
	if(differ != standard_differ){
		var offset = standard_differ - differ;
		$('#block_menu').css({'margin-left' : offset});
	}
}

function getKeyCode(){
	
	 var keycode;
	   if(!event) var event = window.event;
	   if (event.keyCode) keycode = event.keyCode; // IE
	   else if(event.which) keycode = event.which; // all browsers
	   return keycode;
}



function translit(){

	var header = $('#publisher_header').val();
	
	$.ajax({
	type: "POST",
    url: '/articler/translit',
	data: 'type=ajax&str='+header,
    cache: false,
    success: function(data){
		$('#url').val(data);
		}
    });
}

function link_to_get(url){
	
	var	ajax_url = '/templates/articler/images/ajax-loaders/content.gif';
	var content = $('.content').html();

	$.ajax({
	type: "GET",
    url: url,
	dataType: 'json',
	data: 'type=ajax',
    cache: false,
	beforeSend:function(){
		$('.content').html('<div class="ajax_loader"><img src="'+ajax_url+'"></div>');	
	},
	error: function(){
		$('.content').html(content);
		myAlert('Ошибка загрузки!');
	},
    success: function(data){
		$('.content').html(data.content);
		document.title = data.title;
	}
    });
}

function link_to_paginate(url){
	
	var	ajax_url = '/templates/articler/images/ajax-loaders/content.gif';
	var content = $('.content').html();
	

	$.ajax({
	type: "GET",
    url: url,
	dataType: 'json',
	data: 'type=ajax',
    cache: false,
	beforeSend:function(){
		$('.content').html('<div class="ajax_loader"><img src="'+ajax_url+'"></div>');	
	},
	error: function(){
		$('.content').html(content);
		myAlert('Ошибка загрузки!');
	},
    success: function(data){
		$('.content').html(data.content);
		document.title = data.title;
		$.scrollTo('.post',1000);

	}
    });
}

function link_to(url){
	
	var	ajax_url = '/templates/articler/images/ajax-loaders/content.gif';
	var content = $('.content').html();
	if(url.indexOf('novosti') != -1){
		var is_news = true;
	}
	else{
		var is_news = false;
	}

	$.ajax({
	type: "POST",
    url: url,
	dataType: 'json',
	data: 'type=ajax',
    cache: false,
	beforeSend:function(){
		$('.content').html('<div class="ajax_loader"><img src="'+ajax_url+'"></div>');	
	},
	error: function(){
		$('.content').html(content);
		myAlert('Ошибка загрузки!');
	},
    success: function(data){
		$('.content').html(data.content);
		
		if(is_news == false)
			document.title = data.title;
		else
			document.title = 'Новости';
	}
    });
}

function make_payout(url, id){
	
	var	ajax_url = '/templates/articler/images/ajax-loaders/menu.gif';
	var container_link = $('#link_payout_'+id).html();
	
	jConfirm('Вы уверены, что хотите оформить выплату?','Статейник',function(r)
	
{
	if(r == true){
	
	$.ajax({
	type: "POST",
    url: url,
	data: 'type=ajax',
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#link_payout_'+id).html('<img src="'+ajax_url+'" width="8" height="8">');
    },
	error: function(){
		myAlert('Ошибка подгрузки!');
		$('#link_payout_'+id).html(container_link);

	},
    success: function(data){
		if(data.answer == 1){
			$('#payout_'+id).remove();
			$('#num_opened_payouts').text('('+data.num_payouts+')');
		}
		else{
			myAlert(data.content);
			$('#link_payout_'+id).html(container_link);

		}
    }
    });
	}
	}
	);
}

function order_payout(object){
	
	var url = object.href;
	var container_link = $('.content #ajax_load').html();
	var	ajax_url = '/templates/articler/images/ajax-loaders/menu.gif';

	
	$.ajax({
	type: "POST",
    url: url,
	data: 'type=ajax',
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('.content #ajax_load').html('<img src="'+ajax_url+'" width="8" height="8">');
    },
	error: function(){
		myAlert('Ошибка подгрузки!');
		$('.content #ajax_load').html(container_link);

	},
    success: function(data){
		$('.content #ajax_load').html('<b>'+data.content+'</b>');
		$('.content #enable_payout').text('0');
    }
    });
	
}

function show_outer_link(){
	
	$('#article_header').html('"'+$('#publisher_header').val()+'"');
	
	var add_outer_link = $('#add_outer_link').html();
	$.colorbox({html: add_outer_link, open: true, title: 'Добавление внешней ссылки', width:"50%"});
	if($('#hidden_outer_link').length > 0){
		$('.header_action').text('Изменить');
		$('.outer_link').val($('#hidden_outer_link').val());

	}
}

function modal_edit_comment(url,id){
	
	var comment = $('#comment_'+id+' .comment_text').text();
	comment = comment.trim();
	var buffer_length = comment.length;
	var cols = Math.sqrt(buffer_length);
	cols = Math.round(cols * 1.75);
	if(cols < 3)
		cols = 3;
	var rows = Math.round(cols/3);
	
	var content = '<div style="padding:10px 10px 0px 10px;min-width:250px;"><form action="'+url+'" method="post" onsubmit="edit_comment(this.action,'+id+');return false;">';	
	content += '<table cellpadding=2 cellspacing=5>'
	content += '<tr><td valign="top" align="left">Текст</td><td><textarea id="comment_text" name="comment_text" cols="'+cols+'" rows="'+rows+'">'+comment+'</textarea></td></tr>';
	content += '<tr><td></td><td align="left"><div style="padding-top:10px;" id="submit_edit_comment"><input type="submit" value="Редактировать" ></div></td></tr>';
	content += '</table>';
	content += '</form></div>';
	$.colorbox({html: content, open: true, title: 'Редактирование комментария'});

}

function edit_comment(url,id){
	
	var article_id = $('#article_id').val();
	article_id = encodeURIComponent(article_id);
	var	ajax_url = '/templates/articler/images/ajax-loaders/menu.gif';
	var submit_edit_content = $('#submit_edit_content').html();
	var comment = $('#cboxContent #comment_text').val();
	comment = encodeURIComponent(comment);
	if($('#private').length > 0)
		var privatecom = 1;
	else
		var privatecom = 0; 
	
	$.ajax({
	type: "POST",
    url: url,
	data: 'type=ajax&article_id='+article_id+'&comment='+comment+'&private='+privatecom,
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#submit_edit_comment').replaceWith('<div><img src="'+ajax_url+'" width="8" height="8"></div>');
    },
	error: function(){
		myAlert('Ошибка подгрузки!');
		$('#submit_edit_comment').html(submit_edit_content);

	},
    success: function(data){
		if(data.answer == 1){
			$.colorbox.close();
			$('#comment_'+id+' .comment_text').html(data.content);
		}
		else if(data.answer == -1){
			myAlert('Вы не имеете право на редактирование данного комментария!');
			$('#submit_edit_comment').html(submit_edit_content);
		}
		else{
			myAlert('Ошибка подгрузки!');
			$('#submit_edit_comment').html(submit_edit_content);
		}
	
    }
    });
	
}

function set_link(){
	
	if($('#enable_link').attr('checked') == 'checked'){
		$('.pay_for_link').css({display: 'table-row'});
	}
	else{
		$('.pay_for_link').css({display: 'none'});

	}
}



function add_outer_link(object){
	
	var outer_link = $('#colorbox #outer_link').val();
	outer_link = encodeURIComponent(outer_link);
	var article_id = $('#article_id').val();
	var url = object.action;
	var	ajax_url = '/templates/articler/images/ajax-loaders/menu.gif';
	
	if(outer_link == ''){
		myAlert('Вы не ввели ссылку!');
		return false;
	}
	
	$.ajax({
	type: "POST",
    url: url,
	data: 'type=ajax&outer_link='+outer_link+'&article_id='+article_id,
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#colorbox #ajax_load').html('Подождите, идет проверка ссылки...<img src="'+ajax_url+'" width="8" height="8" style="margin-left:3px;">');
    },
	error: function(){
		$('#colorbox #ajax_load').html('Подгрузка завершилась неудачей');

	},
    success: function(data){
			
		if(data.answer == 1){
			$.colorbox.close();
			myAlert(data.content);
			var address = '<span id="show_add_outer_link"><b>'+data.address+'</b></span>';
			$('#show_add_outer_link').replaceWith(address);
		}
		else{
			$('#colorbox #ajax_load').html(data.content);
		}
		
			
    }
    });
}


function user_edit_article(url,id){

	var check_url = str_replace('update','check',url);
	var edit_link = $('#edit_'+id).html();
	var	ajax_url = '/templates/articler/images/ajax-loaders/menu.gif';
	
	$.ajax({
	type: "POST",
    url: check_url,
	data: 'type=ajax',
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#edit_'+id).html('<img src="'+ajax_url+'" width="8" height="8">');
    },
	error: function(){
		myAlert('Неизвестная ошибка!');
		$('#edit_'+id).html(edit_link);

	},
    success: function(data){
		$('#edit_'+id).html(edit_link);
		if(data.answer == 0){
			myAlert(data.content);
		}
		else{
			document.location.href = url;
		}
    }
    });
}

function isValidEmail (email, strict)
{
 if ( !strict ) email = email.replace(/^\s+|\s+$/g, '');
	 return (/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,4}$/i).test(email);
}

function view_downloader(type){
	
	if(type == 'insert')
		var operation = 'Загрузить';
	else
		var operation = 'Сменить';
	
	if($('#downloader_avatar').css('display')=='none'){
	
		$('#downloader_avatar').show('normal');
		$('#link_downloader_avatar').html('Скрыть загрузчик');
	}
	else{
		$('#downloader_avatar').hide('normal');
		$('#link_downloader_avatar').html(operation + ' аватар');
	
	}
}

function ajaxFileUpload(main_url)
	{
	
	
		$("#loading")
		.ajaxStart(function(){
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
		});
		
		$.ajaxFileUpload
		(
			{	
				url:'/uploads/ajaxfileupload.php',
				secureuri:false,
				fileElementId:'avatar_file',
				dataType: 'json',
				data:{name:'logan', id:'id'},
				cache: false,
				success: function (data, status)
				{
					if(typeof(data.error) != 'undefined')
					{
					
						if(data.error != '')
						{
							myAlert(data.error);
						}else
						{
							$('#container_avatar').html(data.content);
							$('#downloader_avatar').hide();
							$('#link_downloader_avatar').text('Сменить аватар');	
						}
						
					}
					
				},
				error: function (data, status, e)
				{
					
					//myAlert(e);
				}
			}
		)
		
		return false;
			

	}

function change_password(){
	
	var old_password = $('#window_password').val();
	var new_password = $('#window_new_password').val();
	var confirm_password = $('#confirm_password').val();
	var container_submit = $('#submit_change_password').html();
	var	ajax_url = '/templates/articler/images/ajax-loaders/menu.gif';

	
	if(old_password == ''){
		myAlert('Поле "Пароль" не может быть пустым!');
		return false;
	}
	
	
	if(new_password == ''){
		myAlert('Поле "Новый пароль" не может быть пустым!');
		return false;
	}
	
	
	if(new_password != confirm_password){
		myAlert('Пароль и подтверждение не совпадают!');
		$('#window_new_password').focus();
		return false;
	}
	
	var url = document.getElementById('form_change_password').action;
	
	$.ajax({
	type: "POST",
    url: url,
	data: 'type=ajax&old_password='+old_password+'&new_password='+new_password+'&confirm_password='+confirm_password,
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#submit_change_password').html('<img src="'+ajax_url+'" width="8" height="8">');
    },
	error: function(){
		myAlert('Неизвестная ошибка!');
		$('#submit_change_password').html(container_submit);

	},
    success: function(data){
		$('#submit_change_password').html(container_submit);
		if(data.answer == 1){
			$.colorbox.close();
			myAlert(data.content);
		}
		else{
			myAlert(data.content);
		}
    }
    });
	
}

function change_purse(object){
	
	var type_change = $('#type_change').val();
	var	ajax_url = '/templates/articler/images/ajax-loaders/menu.gif';
	var purse = $('#purse').val();
	var url = object.action;
	var request = 'type=ajax&purse='+purse+'&type_change='+type_change;
	
	if(purse == ''){
		myAlert('Поле для ввода кощелька не может быть пустым!');
		return false;
	}
	if(type_change == 'update'){
		var new_purse = $('#new_purse').val();
		var confirm_purse = $('#confirm_purse').val();
		
		if(new_purse == ''){
			myAlert('Поле "Новый кошелек" не может быть пустым!');
			return false;
		}
		if(confirm_purse == ''){
			myAlert('Поле "Подтверждение" не может быть пустым!');
			return false;
		}
		
		if(new_purse != confirm_purse){
			myAlert('Пароль и подтверждение не совпадают!');
			return false;

		}	
		
		request += '&new_purse='+new_purse;
	}
	
	
	
	$.ajax({
	type: "POST",
    url: url,
	data: request,
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#submit_change_purse').html('<img src="'+ajax_url+'" width="8" height="8">');
    },
	error: function(){
		myAlert('Неизвестная ошибка!');
		$('#submit_change_purse').html(container_submit);

	},
    success: function(data){
		$('#submit_change_purse').html(container_submit);
		if(data.answer == 1){
			$.colorbox.close();
		}
		else{
			myAlert(data.content);
		}
    }
    });

}

function change_email(){
	
	var old_email = $('#window_email').val();
	var new_email = $('#window_new_email').val();
	var confirm_email = $('#confirm_email').val();
	var container_submit = $('#submit_change_email').html();
	var	ajax_url = '/templates/articler/images/ajax-loaders/menu.gif';

	
	if(old_email == ''){
		myAlert('Поле "Email" не может быть пустым!');
		return false;
	}
	if(!isValidEmail(old_email)){
	
		myAlert('Введен некорректный email');
		$('#window_email').focus();
		return false;
	}
	
	if(new_email == ''){
		myAlert('Поле "Новый email" не может быть пустым!');
		return false;
	}
	if(!isValidEmail(new_email)){
	
		myAlert('Введен некорректный email');
		$('#window_new_email').focus();
		return false;
	}
	
	if(new_email != confirm_email){
		myAlert('Пароль и подтверждение не совпадают!');
		$('#window_new_email').focus();
		return false;
	}
	
	var url = document.getElementById('form_change_email').action;
	
	$.ajax({
	type: "POST",
    url: url,
	data: 'type=ajax&old_email='+old_email+'&new_email='+new_email+'&confirm_email='+confirm_email,
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#submit_change_email').html('<img src="'+ajax_url+'" width="8" height="8">');
    },
	error: function(){
		myAlert('Неизвестная ошибка!');
		$('#submit_change_email').html(container_submit);

	},
    success: function(data){
		$('#submit_change_email').html(container_submit);
		if(data.answer == 1){
			$.colorbox.close();
			myAlert(data.content);
		}
		else{
			myAlert(data.content);
		}
    }
    });
	
}

function edit_article(url){
	
	var edit_container_link = $('#edit_container_link').html();
	var	ajax_url = 'templates/articler/images/ajax-loaders/menu.gif';

	
	$.ajax({
	type: "POST",
    url: url,
	data: 'type=ajax',
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#edit_container_link').html('<img src="'+ajax_url+'" width="8" height="8">');
    },
	error: function(){
		myAlert('Неизвестная ошибка!');

	},
    success: function(data){
		$('#edit_container_link').html(edit_container_link);
		$('#article_content').html(data.content);
    }
    });
}

function show_all_comments(url){
	
	var link_all_comments = $('#link_all_comments').html();
	var	ajax_url = '/templates/articler/images/ajax-loaders/menu.gif';
	var count_comments = $('.comment').size();
	
	$.ajax({
	type: "POST",
    url: url,
	data: 'type=ajax&next_elem='+count_comments,
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#link_all_comments').html('<img src="'+ajax_url+'" width="8" height="8">');
    },
	error: function(){
		myAlert('Неизвестная ошибка!');
		$('#link_all_comments').html(link_all_comments);

	},
    success: function(data){
		$('#link_all_comments').html(link_all_comments);
		if(data.answer == 1){
			$('#link_all_comments').before(data.content);
			$('#link_all_comments').remove();
			$('#total_comments').html('Комментариев '+data.num_comments);
		}
		else{
			myAlert(data.content);
		}
    }
    });
}


function send_moderate(url,id,activity){
	
	var ajax_url = 'templates/articler/images/ajax-loaders/menu.gif';
	var new_url = str_replace('send','cancel',url);
	var container = $('#send_'+id).html();
	var cur_page = $('#cur_page').val();
	var offset = $('.paginator strong').text();
	
	if(activity == 2){
		myAlert('Статья уже опубликована!');
		return false;
	}
	
	jConfirm('Вы уверены, что хотите отправить на модерацию данную статью?','Статейник',function(r)
	
{
	if(r == true){
		
	 $.ajax({
	type: "POST",
    url: url,
	data: 'type=ajax&cur_page='+cur_page+'&offset='+offset,
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#send_'+id).html('<img src="'+ajax_url+'" width="8" height="8">');
    },
	error: function(){
		$('#send_'+id).html(container);
		myAlert('Неизвестная ошибка!');

	},
    success: function(data){
	if(data.answer == 0){
    	$('#send_'+id).html(container);
		myAlert(data.content);
	}
	else{
	
		$('#list_articles').html(data.content);
	
	}
    }
    });
	}
	}
	);
	
}



function cancel_moderate(url,id,activity){
	
	var ajax_url = 'templates/articler/images/ajax-loaders/menu.gif';
	var new_url = str_replace('cancel','send',url);
	var container = $('#cancel_'+id).html();
	var cur_page = $('#cur_page').val();
	var offset = $('.paginator strong').text();

	if(activity == 2){
		myAlert('Статья уже опубликована!');
		return false;
	}
	
	jConfirm('Вы уверены, что хотите снять с модерации данную статью?','Статейник',function(r)
	
{
	if(r == true){
		
	 $.ajax({
	type: "POST",
    url: url,
	data: 'type=ajax&cur_page='+cur_page+'&offset='+offset,
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#cancel_'+id).html('<img src="'+ajax_url+'" width="8" height="8">');
    },
	error: function(){
		$('#cancel_'+id).html(container);
		myAlert('Неизвестная ошибка!');

	},
    success: function(data){
	if(data.answer == 0){
    	$('#cancel_'+id).html(container);
		myAlert(data.content);
	}
	else{
	
			$('#list_articles').html(data.content);

		
	}
    }
    });
	}
	}
	);
	
}

function delete_exception(object,id){
	
	var ajax_url = '/templates/articler/images/ajax-loaders/menu.gif';
	var container = $('#delete_'+id).html();
	
	jConfirm('Вы уверены, что хотите удалить эту страницу исключений?','Статейник',function(r)
	
{
	if(r == true){
		
	 $.ajax({
	type: "POST",
    url: object.href,
	data: 'type=ajax',
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#delete_'+id).html('<img src="'+ajax_url+'" width="8" height="8">');
    },
	error: function(){
		$('#delete_'+id).html(container);
		myAlert('Неизвестная ошибка!');

	},
    success: function(data){
	if(data.answer == 1){
		$('#row_'+id).remove();
	}
	else{
		$('#delete_'+id).html(container);
		myAlert('Неизвестная ошибка!');
	}
    }
    });
	}
	}
	);
}

function delete_giventopic(object,id){
	
	var ajax_url = 'templates/articler/images/ajax-loaders/menu.gif';
	var container = $('#delete_'+id).html();
	
	jConfirm('Вы уверены, что хотите удалить данную тему?','Статейник',function(r)
	
{
	if(r == true){
		
	 $.ajax({
	type: "POST",
    url: object.href,
	data: 'type=ajax',
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#delete_'+id).html('<img src="'+ajax_url+'" width="8" height="8">');
    },
	error: function(){
		$('#delete_'+id).html(container);
		myAlert('Неизвестная ошибка!');

	},
    success: function(data){
	if(data.answer == 1){
		$('#row_'+id).remove();
	}
	else{
		$('#delete_'+id).html(container);
		myAlert(data.content);
	}
    }
    });
	}
	}
	);
	
}

function delete_private_article(url,id){
	
	var ajax_url = 'templates/articler/images/ajax-loaders/menu.gif';
	var container = $('#delete_'+id).html();
	
	
	jConfirm('Вы уверены, что хотите удалить данный материал?','Статейник',function(r)
	
{
	if(r == true){
		
	 $.ajax({
	type: "POST",
    url: url,
	data: 'type=ajax',
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#delete_'+id).html('<img src="'+ajax_url+'" width="8" height="8">');
    },
	error: function(){
		$('#delete_'+id).html(container);
		myAlert('Неизвестная ошибка!');

	},
    success: function(data){
	if(data.answer == 1){
		$('#row_'+id).remove();
	}
	else{
		$('#delete_'+id).html(container);
		myAlert(data.content);
	}
    }
    });
	}
	}
	);
	
}


function delete_article(url,id,activity){
	
	var ajax_url = 'templates/articler/images/ajax-loaders/menu.gif';
	var container = $('#delete_'+id).html();
	
	if(activity == 2){
		myAlert('Невозможно удалить опубликованную статью!');
		return false;
	}
	
	jConfirm('Вы уверены, что хотите удалить данную статью?','Статейник',function(r)
	
{
	if(r == true){
		
	 $.ajax({
	type: "POST",
    url: url,
	data: 'type=ajax',
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    $('#delete_'+id).html('<img src="'+ajax_url+'" width="8" height="8">');
    },
	error: function(){
		$('#delete_'+id).html(container);
		myAlert('Неизвестная ошибка!');

	},
    success: function(data){
	if(data.answer == 1){
		$('#row_'+id).remove();
	}
	else{
		$('#delete_'+id).html(container);
		myAlert(data.content);
	}
    }
    });
	}
	}
	);
	
}

function delete_comment(url,id){
    var ajax_url = '/templates/articler/images/ajax-loaders/menu.gif';
    var container_delete = $('#delete_'+id).html();
	var article_id = $('#article_id').val();
	article_id = encodeURIComponent(article_id);
	if($('#private').length > 0)
		var privatecom = 1;
	else
		var privatecom = 0;

	jConfirm('Вы уверены, что хотите удалить данный комментарий?','Статейник',function(r)

{
	if(r == true){
	
    $.ajax({
    type: "POST",
    url: url,
    data: 'type=ajax&article_id='+article_id+'&private='+privatecom,
    dataType: 'json',
    cache: false,
    beforeSend: function() {
        $('#delete_'+id).html('<img src="'+ajax_url+'" width="8" height="8">');
    },
    error: function(){
        myAlert('Неизвестная ошибка!');
        $('#delete_'+id).html(container_delete);
    },
    success: function(data){
        if(data.answer == 1){
			var before = $('#comment_'+id).before();
			before.remove();
            $('#comment_'+id).remove();
			$('#total_comments').text('Комментариев '+data.num_comments);
        }
		else if(data.answer == -1){
			myAlert('Вы не имеете право на удаление данного комментария!');
        	$('#delete_'+id).html(container_delete);
		}
        else{
       		myAlert('Неизвестная ошибка!');
        	$('#delete_'+id).html(container_delete);
		}
    }
    });
	
	}
	}
	);
}



function delete_plus(url,user_id,main_url,num){
	
	var ajax_url = main_url+'templates/articler/images/ajax-loaders/menu.gif';
	var assess_article = $('#assess_article').html();
	
	$.ajax({
	type: "POST",
    url: url,
	data: 'type=ajax',
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#assess_article').html('<img src="'+ajax_url+'" width="8" height="8">');
    },
	error: function(){
		myAlert('Неизвестная ошибка!');
		$('#assess_article').html(assess_article);
	},
    success: function(data){
		if(data.answer == 1){
			$('#assess_article').html(data.content);
			$('#article_rating strong').text(data.rating);

		}
		else{
		myAlert('Неизвестная ошибка!');
		$('#assess_article').html(assess_article);		}
    }
    });
}

function modal_booking(){
		
	var content = '<h2>Тема "'+$('#giventopics option:selected').text()+'"</h2>';
	var giventopic_id = $('#giventopics option:selected').val();
	content += '<table><tr><td>'+$('#booking_message').text()+'</td></tr>';
	content += '<tr><td id="booking_answer">'+$('#container_call_booking').html()+'</td></tr>';
	content += '</table>';
	$.colorbox({html:content,title:'Бронирование темы',width:"50%"});
	$('#booking_form #giventopics').val(giventopic_id);

	
}

function call_booking(){
	
	$('#booking_form').submit();
	
}

function plea_modal(user_id,url,comment_id,main_url){
	
	var ajax_url = main_url+'templates/articler/images/ajax-loaders/menu.gif';
	var link_reply = $('#reply_'+comment_id).html();
	var comment_info = $('#comment_'+comment_id+' .comment_username').html()+'&nbsp;от&nbsp;'+$('#comment_'+comment_id+' .time_show_data').html();
	var plea = $('#add_plea').html();
	var comment_text = $('#comment_'+comment_id+' .comment_text').html();

	$('#form_add_plea').attr({action: url});
	$('#add_plea h2').html('Жалоба модератору на комментарий '+comment_info);
	$('.plea_modal').colorbox({html: plea, inline:true, width:"50%"});
	$('#add_plea #comment_id').val(comment_id);
	$('#add_plea .comment_text').html(comment_text);
	$('#add_plea #ajax_url').val(main_url+'templates/articler/images/ajax-loaders/menu.gif');
		
}


function reply_modal(user_id,url,comment_id,main_url){
	
	var ajax_url = main_url+'templates/articler/images/ajax-loaders/menu.gif';
	var link_reply = $('#reply_'+comment_id).html();
	var reply_comment = $('#reply_comment').html();
	var comment_text = $('#comment_'+comment_id+' .comment_text').html();
	var comment_info = $('#comment_'+comment_id+' .comment_info').html();
	$('#form_reply_comment').attr({action: url});
	$('#reply_comment .comment_text').html(comment_text);
	$('#reply_comment h2').html('Ответ на комментарий '+comment_info);
	$('.reply_modal').colorbox({html: reply_comment, inline:true, width:"50%"});
	$('#comment_id').val(comment_id);
	$('#ajax_url').val(ajax_url);
		
}

function reply_plea_modal(url,plea_id,main_url){
	
	var ajax_url = main_url + 'templates/articler/images/ajax-loaders/menu.gif';
	var plea_text = $('#list_pleas #plea_' + plea_id).html();
	var plea_comment = $('#list_pleas #comment_' + plea_id).html();
	var plea_time = $('#list_pleas #time_'+plea_id).html();
	var plea_username = $('#list_pleas #username_'+plea_id).html();
	
	$('#reply_plea #plea_text').html(plea_text);
	$('#reply_plea #plea_time').html(plea_time);
	$('#reply_plea #plea_comment').html(plea_comment);
	$('#reply_plea #plea_username').html(plea_username);
	var reply_plea = $('#reply_plea').html();
	$('.reply_modal').colorbox({html: reply_plea, inline:true, width:"50%"});
	$('#plea_id').val(plea_id);
	$('#ajax_url').val(ajax_url);

		
}

function reply_plea(url,main_url){
	
	var answer_plea = $('#reply_plea #answer_plea').val();
	var plea_id = $('#reply_plea #plea_id').val();
	var ajax_url = main_url + 'templates/articler/images/ajax-loaders/menu.gif';
	var container_submit = $('#reply_plea #container_submit').html();
	if(answer_plea == ''){
		myAlert('Отсутствует ответ на жалобу!');
		return false;
	}
	

	$.ajax({
	type: "POST",
    url: url,
	data: 'type=ajax&answer_plea='+answer_plea+'&plea_id='+plea_id,
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#reply_plea #container_submit').html('<img src="'+ajax_url+'" width="8" height="8">');
    },
	error: function(){
		myAlert('Неизвестная ошибка!');
		$('#reply_plea #container_submit').html(container_submit);

	},
    success: function(data){
		$('#reply_plea #container_submit').html(container_submit);

		if(data.answer == 1){
		
			$('.reply_modal').colorbox.close();
			$('#link_reply_'+plea_id).html(data.considered);
			$('#num_unconsidered_pleas').html('('+data.num_pleas+')');
			$('#status_plea_'+plea_id).text('Рассмотрена');
		}
		else{
			myAlert(data.text);
		}
    }
    });
	
}


function reply_comment(){
	
	var url = $('#form_reply_comment').attr('action');
	var article_id = $('#article_id').val();
	var user_id = $('#user_id').val();
	var reply = $('#reply').val();
	reply = encodeURIComponent(reply);
	var flag_reply = $('#flag_reply').val();
	var comment_id = $('#comment_id').val();
	var ajax_url = $('#ajax_url').val();
	var container_reply = $('#reply_'+comment_id).html();
	var container_reply_comment = $('#reply_comment').html();
	//myAlert(co);return false;
	
	$.ajax({
	type: "POST",
    url: url,
	data: 'type=ajax&article_id='+article_id+'&comment_text='+reply+'&user_id='+user_id,
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#reply_'+comment_id).html('<img src="'+ajax_url+'" width="8" height="8">');
    	$('#reply_comment').html('<img src="'+ajax_url+'" width="8" height="8">');
    },
	error: function(){
		myAlert('Неизвестная ошибка!');
		$('#reply_'+comment_id).html(container_reply);
		$('#reply_comment').html(container_reply_comment);
	},
    success: function(data){
		$('#reply_'+comment_id).html(container_reply);
		$('#reply_comment').html(container_reply_comment);
//		myAlert(comment_id);
		if(data.answer == 1){
		
			$('.reply_modal').colorbox.close();
			$('#reply_'+comment_id).remove();
			if($('#total_comments').length>0){
				$('#total_comments').after(data.content);
				$('#total_comments').html('Комментариев '+data.num_comments);
				$('#num_comments').text(data.your_num_comments);
				$('#your_rating_activity').text(data.rating);
			}
			else{
				$('#post_comment').after(data.content);
				$('#num_comments').text(data.your_num_comments);
				$('#your_rating_activity').text(data.rating);
			}
		}
		else{
		myAlert('Неизвестная ошибка!');
		}
    }
    });
	
}

function change_rating(type,url,main_url){
	
	if(type == 'author'){
		var show_type = 'авторский рейтинг';
		var rating = $('#author_rating').val();
		var old_rating = $('#old_author_rating').val();
		var container = $('#button_author_rating').val();
		var type_container = 'author_rating';
		
		if(rating < 0){
			myAlert('Авторский рейтинг не может быть меньше 0!');
			return false;
		}
	}
	else{
		var show_type = 'рейтинг активности';
		var rating = $('#rating_activity').val();
		var old_rating = $('#old_rating_activity').val();
		var container = $('#button_rating_activity').html();
		var type_container = 'rating_activity';

	}
		
	var ajax_url = main_url+'templates/articler/images/ajax-loaders/menu.gif';
	var old_rating = $('#'+type_container).val();

	
	jConfirm('Вы уверены, что хотите изменить '+show_type+' данного пользователя?','Статейник',function(r)
	
{
	if(r == true){
		
	 $.ajax({
	type: "POST",
    url: url,
	data: 'type=ajax&type_rating='+type+'&rating='+rating,
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	//$('#button_'+type_container).html('<img src="'+ajax_url+'" width="8" height="8">');
		$('#button_'+type_container).val('Подгрузка');
    },
	error: function(){
		$('#button_'+type_container).val(container);
		myAlert('Неизвестная ошибка!');

	},
    success: function(data){
		$('#button_'+type_container).val(container);
	if(data.answer == 1){
		$('#'+type_container).val(data.content);
	}
	else{	
		$('#'+type_container).val(old_rating);
		myAlert(data.content);
	}
    }
    });
	}
	}
	);
}



function change_rating_article(url,main_url,id){
	
		var show_type = 'рейтинг статьи';
		var rating = $('#rating_'+id).val();
		var old_rating = $('#old_rating_'+id).val();
		var container = $('#button_rating_'+id).val();
		//myAlert(container);
		//return false;
		var type_container = 'author_rating';
		
		if(rating < 0){
			myAlert('Рейтинг статьи не может быть меньше 0!');
			return false;
		}
	
		
	var ajax_url = main_url+'templates/articler/images/ajax-loaders/menu.gif';

	jConfirm('Вы уверены, что хотите изменить рейтинг данной статьи?','Статейник',function(r)
	
{
	if(r == true){
		
	 $.ajax({
	type: "POST",
    url: url,
	data: 'type=ajax&rating='+rating+'&old_rating='+old_rating,
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#button_rating_'+id).val('Подгрузка');
    },
	error: function(){
		$('#button_rating_'+id).val(container);
		myAlert('Неизвестная ошибка!');

	},
    success: function(data){
		$('#button_rating_'+id).val(container);
	if(data.answer == 1){
		$('#rating_'+id).val(data.content);
		if(data.is_quit_sandbox == 1 && $('#article_'+id).length > 0)
			$('#article_'+id).remove();
		if($('#author_rating').length > 0)
			$('#author_rating').val(data.user_rating);
			$('#old_rating_'+id).val(data.content);
	}
	else{	
		$('#rating_'+id).val(old_rating);
		myAlert(data.content);
	}
    }
    });
	}
	}
	);
}


function add_plea(url,main_url){
	
	var comment_id = $('#add_plea #comment_id').val();
	var article_id = $('#article_id').val();
	var plea = $('#plea').val();
	plea = encodeURIComponent(plea);
	var ajax_url = main_url+'templates/articler/images/ajax-loaders/menu.gif';
	var container_submit = $('#container_submit').html();
	
	$.ajax({
	type: "POST",
    url: url,
	data: 'type=ajax&comment_id='+comment_id+'&plea='+plea+'&article_id='+article_id,
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#container_submit').html('<img src="'+ajax_url+'" width="8" height="8">');
    },
	error: function(){
		myAlert('Неизвестная ошибка!');
		$('#container_submit').html(container_submit);
	},
    success: function(data){
		$('#container_submit').html(container_submit);
	if(data.answer == 1){
			$.colorbox.close();
			myAlert(data.content);
		}
		else{
			myAlert(data.content);
		}
	}	
    });
}


function add_plus(url,user_id,main_url,num){
	
	var ajax_url = main_url+'templates/articler/images/ajax-loaders/menu.gif';
	var assess_article = $('#assess_article').html();
	
	$.ajax({
	type: "POST",
    url: url,
	data: 'type=ajax',
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#assess_article').html('<img src="'+ajax_url+'" width="8" height="8">');
    },
	error: function(){
		myAlert('Неизвестная ошибка!');
		$('#assess_article').html(assess_article);
	},
    success: function(data){
		if(data.answer == 1){
			$('#assess_article').html(data.content);
			//var curr_rating = $('#article_rating b').text();
			//var new_rating = (curr_rating * 1) + num;
			$('#article_rating strong').text(data.rating);
		}
		else{
		myAlert('Неизвестная ошибка!');
		$('#assess_article').html(assess_article);		}
    }
    });
}

function add_comment(){
	
	var ajax_url = '/templates/articler/images/ajax-loaders/menu.gif';
	var comment_text = $('#comment_text').val();
	comment_text = encodeURIComponent(comment_text);
	var article_id = $('#article_id').val();
	var user_id = $('#user_id').val();
	var article_owner_user_id = $('#article_owner_user_id').val();
	var container_submit = $('#container_submit').html();
	
	if(comment_text == '' || comment_text == 'Текст комментария'){
		myAlert('Вы не оставили комментария!');
		return false;
	}
	
	var url = document.getElementById('comment_form').action;
	if($('#private').length > 0)
		var privatecom = 1;
	else
		var privatecom = 0;
	
	$.ajax({
	type: "POST",
    url: url,
	data: 'type=ajax&article_id='+article_id+'&user_id='+user_id+'&comment_text='+comment_text+'&article_owner_user_id='+article_owner_user_id+'&private='+privatecom,
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#container_submit').html('<img src="'+ajax_url+'" width="8" height="8">');
    },
	error: function(){
		myAlert('Неизвестная ошибка!');
		$('#container_submit').html(container_submit);

	},
    success: function(data){
		$('#container_submit').html(container_submit);
		if(data.answer == 1){
			if($('#total_comments').length>0){
				$('#total_comments').after(data.content);
				$('#total_comments').html('Комментариев '+data.num_comments);
				$('#num_comments').text(data.your_num_comments);
				$('#your_rating_activity').text(data.rating);
				$.scrollTo('#total_comments',1000);
			}
			else{
				$('#post_comment').after(data.content);
				$('#num_comments').text(data.your_num_comments);
				$('#your_rating_activity').text(data.rating);
			}
				$('#comment_text').val('Текст комментария');
			
		}
		else{
			myAlert(data.content);
		}

    }
    });
	
	
}

function validate_author_profile(object){
	
	var name = $('#name').val();
	var family = $('#family').val();
	
	if(name == ''){
		myAlert('Не заполнено поле "Имя"!');
		return false;
	}
	
	if(family == ''){
		myAlert('Не заполнено поле "Фамилия"!');
		return false;
	}
	
	document.getElementById('author_profile_form').submit();

}

function validate_moderate_form(id,type){
	
	var heading = $('#headings option:selected').val();
	var reason = $('#moderate_reason').val();
	var url = $('#url').val();
	var header = $('#publisher_header').val();
	var rating_for_homefeed = $('#rating_for_homefeed').val();
	
	if(type == 'publish'){
		$('#hidden_publish').val(1);
	}
	else{
		$('#hidden_reject').val(1);

	}
	
	
	if(heading == 0){
		myAlert('Выберите рубрику для публикации!');
		return false;
	}
	
	if(type == 'reject' && reason.length < 3){
		myAlert('Формулировка отказа от публикации пустая или слишком короткая!');
		return false;
	}
	
	if(header == ''){
		myAlert('Поле "Заголовок" не должно быть пустым!');
		return false;
	}
	
	if(url == ''){
		myAlert('Поле "Url" не должно быть пустым!');
		return false;
	}
	
	
	
	
	if($('#outer_link').length > 0){
		
		if($('#enable_link').attr('checked') == 'checked'){
			var initial_rating = $('#initial_rating').val();
			var add_bmp = $('#add_bmp').val();
			if(initial_rating < 0){
				myAlert('В поле "Начальный рейтинг" не может быть значение ниже 0!');
				return false;
			}
			else if(initial_rating >= rating_for_homefeed){
				myAlert('В поле "Начальный рейтинг" должно быть значение менее '+rating_for_homefeed+' баллов!');
				return false;
			}
			else if(add_bmp < 0){
				myAlert('В поле "Денежная добавка" не может быть значение ниже 0!');
				return false;
			}
			
			if(initial_rating == ''){
				myAlert('Поле "Начальный рейтинг" не может быть пустым!');
				return false;
			}
			else if(add_bmp < 0){
				myAlert('Поле "Денежная добавка" не может быть пустым!');
				return false;
			}
			
			
		}
	}
	
	document.getElementById('moderate_form').submit();
	
}

function validate_giventopic_form(){
	
	var url = $('#name').val();
	var header = $('#name_russian').val();
	
	if(header == ''){
		myAlert('Поле "Название" не должно быть пустым!');
		return false;
	}
	
	if(url == ''){
		myAlert('Поле "Url" не должно быть пустым!');
		return false;
	}
	

	if(url.indexOf('/') != -1 || url.indexOf('.') != -1 || url.indexOf(':') != -1){
		myAlert('В поле "Url" присутствуют недопустимые символы!');
		return false;
	}
	
	$('#form_add_giventopic').submit();
}

function validate_rubric_form(){
	
	var url = $('#name').val();
	var header = $('#name_russian').val();
	
	if(header == ''){
		myAlert('Поле "Название" не должно быть пустым!');
		return false;
	}
	
	if(url == ''){
		myAlert('Поле "Url" не должно быть пустым!');
		return false;
	}
	

	if(url.indexOf('/') != -1 || url.indexOf('.') != -1 || url.indexOf(':') != -1){
		myAlert('В поле "Url" присутствуют недопустимые символы!');
		return false;
	}
	
	$('#form_add_rubric').submit();
}

function validate_private_form(){
	
	var url = $('#url').val();
	var header = $('#publisher_header').val();
	
	if(header == ''){
		myAlert('Поле "Заголовок" не должно быть пустым!');
		return false;
	}
	
	if(url == ''){
		myAlert('Поле "Url" не должно быть пустым!');
		return false;
	}
	

	if(url.indexOf('/') != -1 || url.indexOf('.') != -1 || url.indexOf(':') != -1){
		myAlert('В поле "Url" присутствуют недопустимые символы!');
		return false;
	}
	
	$('#form_add_private').submit();

}

function validate_publish(){
	
	var heading = $('#headings option:selected').val();
	var url = $('#url').val();
	var header = $('#publisher_header').val();
	var description = $('#description').val();
	var keywords = $('#keywords').val();
	var annotation = $('#annotation').val();
	var editor = $('*[name=editor]').val();
	description = description.trim();
	annotation = annotation.trim();
	keywords = keywords.trim();
	
	if(heading == 0){
		myAlert('Выберите рубрику для публикации!');
		return false;
	}
	
	if(header == ''){
		myAlert('Поле "Заголовок" не должно быть пустым!');
		return false;
	}
	
	if(url == ''){
		myAlert('Поле "Url" не должно быть пустым!');
		return false;
	}
	
	if(annotation.length < 150){
		myAlert('В поле "Аннотация" должно быть не менее 150 символов!');
		return false;
	}
	else if(annotation.length > 300){
		myAlert('В поле "Аннотация" должно быть не более 300 символов!');
		return false;
	}
	
	if(description.length < 100){
		myAlert('В поле "Описание" должно быть не менее 100 символов!');
		return false;
	}
	else if(description.length > 200){
		myAlert('В поле "Описание" должно быть не более 200 символов!');
		return false;
	}
	
	if(keywords.length < 40){
		myAlert('В поле "Ключевые слова" должно быть не менее 40 символов!');
		return false;
	}
	else if(keywords.length > 100){
		myAlert('В поле "Ключевые слова" должно быть не более 100 символов!');
		return false;
	}
	
	if(url.indexOf('/') != -1 || url.indexOf('.') != -1 || url.indexOf(':') != -1){
		myAlert('В поле "Url" присутствуют недопустимые символы!');
		return false;
	}
	
	document.form_publish.submit();
	
}

function reset_filter(){
	
	$('#frm_search_articles #headings').val(0);
	$('#frm_search_articles #data1').val('00.00.0000');
	$('#frm_search_articles #data2').val('00.00.0000');
	$('#frm_search_articles #rating_from').val('');
	$('#frm_search_articles #rating_to').val('');
	$('#frm_search_articles #header').val('');
	$('#frm_search_articles #search_article').val('');
	$('#frm_search_articles #is_special').removeAttr('checked');
	$('#frm_search_articles #search_outer_link').removeAttr('checked');
}

function autoselect(object){
	$(object).select();
}

function check_refer(page,buffer_enter_url,user_id){
	
	var edit_container_link = $('#edit_container_link').html();
	var	ajax_url = 'templates/articler/images/ajax-loaders/menu.gif';

	$.ajax({
	type: "POST",
    url: '/check_refer',
	data: 'type=ajax&page='+page,
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#edit_container_link').html('<img src="'+ajax_url+'" width="8" height="8">');
    },
	error: function(){
		myAlert('Неизвестная ошибка!');

	},
    success: function(data){
		if(data.answer == 1){
			var new_url = buffer_enter_url+'?refer='+user_id;
			$('#url_after').val(new_url);
		}
		else{
			myAlert('Указанный url недоступен либо запрещен к использованию!');
		}
		
    }
    });

}

function generate_refer(url,user_id){
	url = str_replace('www.','',url);
	var enter_url = $('#url_before').val();
	var buffer_enter_url = enter_url;
	enter_url = str_replace('www.','',enter_url);
	var pos = enter_url.indexOf(url);
	if(pos != -1){
		var buffer = enter_url.split(url);
		if(buffer.length > 1){
			check_refer(buffer[1],buffer_enter_url,user_id);

		}
		else{
			myAlert('Данный url не содержит ссылки на сайт!');

		}
			
	}
	else{
		myAlert('Данный url не содержит ссылки на сайт!');
	}
}

function change_settings(){
	
	var ajax_url = '/templates/articler/images/ajax-loaders/menu.gif';
	var add_refer = $('#add_refer').val();
	var add_user = $('#add_user').val();
	var add_user_refer = $('#add_user_refer').val();
	if(add_refer < 1){
		myAlert('Значение реферальной добавки не должно быть ниже 1');
		return false;
	}
	
	if(add_user < 1){
		myAlert('Значение начисления юзеру не должно быть ниже 1');
		return false;
	}
	
	if(add_user_refer < 1){
		myAlert('Значение процента рефералу не должно быть ниже 1');
		return false;
	}

	$.ajax({
	type: "POST",
    url: '/moderator/refer_settings/add_refer',
	data: 'type=ajax&add_refer='+add_refer+'&add_user='+add_user+'&add_user_refer='+add_user_refer,
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$('#refer_add button').val('Секунду..');
    },
	error: function(){
		myAlert('Неизвестная ошибка!');
    	$('#refer_add button').val('Обновить');
	},
    success: function(data){
		if(data.answer == 1){
    		$('#refer_add button').val('Обновить');
			$('#add_refer').next().show();
			$('#add_user').next().show();
			$('#add_user_refer').next().show();
		}
		else{
			myAlert('Неизвестная ошибка!');
    		$('#container_refer_add button').val('Обновить');
		}
    }
    });
}

function show_branch(refer_id,object){
	
	var ajax_url = '/templates/articler/images/ajax-loaders/menu.gif';
	var container = $(object).next().html();

	$.ajax({
	type: "POST",
    url: '/moderator/refers/branch/'+refer_id,
	data: 'type=ajax',
	dataType: 'json',
    cache: false,
    beforeSend: function() {
    	$(object).next().html('<img src="'+ajax_url+'" width="8" height="8">');
    },
	error: function(){
		myAlert('Неизвестная ошибка!');
		$(object).next().html('');
	},
    success: function(data){
		if(data.answer == 1){
			$(object).next().html(data.content);

		}
		else{
			myAlert('Неизвестная ошибка!');
			$(object).next().html('');
		}
    }
    });
}
