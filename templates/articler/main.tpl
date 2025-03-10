<?php
$mtime = microtime();        //Считываем текущее время 
$mtime = explode(" ",$mtime);    //Разделяем секунды и миллисекунды
// Составляем одно число из секунд и миллисекунд
// и записываем стартовое время в переменную  
$tstart = $mtime[1] + $mtime[0];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
{$page_type = $CI->core->core_data['data_type'];}
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>{$site_title} <<(www.ruavtor.ru)>></title>
<meta name="keywords" content="{$site_keywords}" />
<meta name="description" content="{$site_description}" />
<link rel="icon" href="{$THEME}/images/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="{$THEME}/css/style.css" />
<link rel="stylesheet" type="text/css" href="{$THEME}/css/main.css" />
{literal}
<style>
    #fixed {
        padding-bottom: 0px;
    }
    #footer {
        margin-bottom: 0px;
    }
</style>
{/literal}
<link rel="stylesheet" type="text/css" href="{$THEME}/css/tipTip.css" />
<link rel="stylesheet" type="text/css" href="{$THEME}/css/jdate.css" />
<link rel="stylesheet" type="text/css" href="{$THEME}/css/colorbox/colorbox.css" />

<!--script type="text/javascript" src="{$THEME}/js/jquery.js"></script-->
<meta name="google-site-verification" content="G_MxOCm0-U-cDIcTUabJnFpTbTNAiDoRP9vp9xfqWZY" />
<!-- ujtj876km$$jkOp -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="{$THEME}/js/jquery.scrollTo.js"></script>
<script type="text/javascript" src="{$THEME}/js/jquery.zoomtext.js"></script>
<script src="{$THEME}/js/jquery.driving.box.js"></script>
{literal}
<script>
    $(function() {    
        $( '#fixed' ).drivingBox( '#page', 100 );        
    });
		
</script>
{/literal}
	<link rel="stylesheet" type="text/css" href="{$THEME}/js/alerts/jquery.alerts.css" />
	<script type="text/javascript" src="{$THEME}/js/functions.js"></script>
	<script type="text/javascript" src="{$THEME}/js/jquery.alerts.js"></script>
	<script type="text/javascript" src="{$THEME}/js/jdate.js"></script>
	<script type="text/javascript" src="{$THEME}/js/jquery.tipTip.js"></script>
	<script type="text/javascript" src="{$THEME}/js/jquery.livequery.js"></script>
	<script type="text/javascript" src="{$THEME}/js/jquery.colorbox.js"></script>
	<script type="text/javascript">
	{literal}
	$(document).ready(function(){
		$(".inline").colorbox({inline:true, width:"50%"});
		
	});	
	{/literal}
	</script>
	{if isset($first_enter)}
	{literal}
	<script type="text/javascript">
	$(document).ready(function(){
		var first_enter_info = $('#container_first_enter_info').html();
		$.colorbox({html: first_enter_info, open: true, opacity: 0.5});

	});
	
	</script>
	{/literal}
	{elseif isset($first_enter_guest)}
	{literal}
	<script type="text/javascript">
	$(document).ready(function(){
		var first_enter_info_guest = $('#container_first_enter_info_guest').html();
		$.colorbox({html: first_enter_info_guest, open: true, opacity: 0.5});

	});
	
	</script>
	{/literal}
	{/if}
	
	{if $access == 1 || $access == 2}
	<link rel="stylesheet" type="text/css" href="{$THEME}/css/jquery.autocomplete.css" />
	<script type="text/javascript" src="{$THEME}/js/jquery.autocomplete.js"></script>
	<link rel="stylesheet" type="text/css" href="{$THEME}/css/jquery.treeview.css" />
	<script type="text/javascript" src="{$THEME}/js/jquery.treeview.js"></script>
	<script type="text/javascript" src="{$THEME}/js/data.js"></script>
	<script type="text/javascript" src="{$THEME}/js/ajaxfileupload.js"></script>
	
	{/if}
	
{literal}
<script type="text/javascript">
$(function(){
$(".link_info").tipTip();
});
</script>
{/literal}
</head>

<body>

<!--LiveInternet counter--><script type="text/javascript"><!--
new Image().src = "//counter.yadro.ru/hit?r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random();//-->
</script>
<!--/LiveInternet-->

<div id="wrapper">
<table width="100%" cellpadding="0" cellspacing="0">
<tr id="header-wrapper">
<td width="400">
	<div id="logo">
				<li>
	<a id="lg" name="lg" href="{site_url('')}"><span>Ру</span>
	Автор</a></li>
	<noindex><p>On-line издание</p></noindex>
			</div>

</td>
<td align="left">
<div id="menu" style="white-space:nowrap;">
				<ul id="block_menu">

<noindex>
					<li>
					
<a id="tm1" name="tm1" href="{site_url('avtory')}">Авторы</a>
                                       </li>

					<li>
<a id="tm2" name="tm2" href="{site_url('pravila-servisa.html')}">Правила</a>
                                       </li>

					<li>
<a id="tm3" name="tm3" href="{site_url('enter')}">Вход / Регистрация</a>
                                       </li>

					<li>
<a id="tm4" name="tm4" href="{site_url('novosti')}">Новости</a>
                                       </li>

					<li>
<a id="tm5" name="tm5" href="{site_url('contact.html')}">Контакты</a>
                                       </li>
</noindex>

				</ul>
			</div>

</td>			

	</tr>
<tr id="page">
	<td valign="top">
		<div id="sidebar">
					<ul>
	{if $access == 2}
		<li id="container_material" style="height:20px;width:185px;">
	{else:}
		<li id="container_material" style="height:60px;width:185px;">
	{/if}			
{if $is_moderator == 1 && $activity == 2}
{if empty($is_edited)}
<a href="{site_url('moderator/edit')}/{$id}" target="_blank"><div style="border: 4px solid rgb(250, 65, 0); padding: 4px 6px 2px; margin-left: 0px; text-align: center; font-size: 11px;"><b style="color: green;">РЕДАКТИРОВАТЬ МАТЕРИАЛ</b></div></a>
{/if}
{elseif $is_moderator == 1 && $activity < 2}

{else:}
{if $access == 2}

{else:}
<a href="{site_url('transit')}" target="_blank"><div style="border: 4px solid rgb(250, 65, 0); padding: 4px 6px 2px; margin-left: 0px; text-align: center; font-size: 11px;"><b style="color: green;">ДОБАВИТЬ МАТЕРИАЛ</b></div></a>
{/if}

{/if}
</li>

						<li>
<hr>
<noindex>

{if $access == 1}
<!------------------------------- Блок модальных окон -------------------------------->
<div style="display:none;">
{$modal_windows.change_email}
{$modal_windows.change_password}
{$modal_windows.change_purse}
{$modal_windows.reply_comment}
{$modal_windows.add_plea}
{$modal_windows.add_outer_link}
{if isset($first_enter)}
<div id="container_first_enter_info">
{$modal_windows.first_enter_info}
</div>
{/if}

</div>

<!------------------------------------------------------------------------------------------------------------->
<div id="profile">
<b id="header_profile">Личный кабинет {ucfirst($login)}</b>

{if isset($avatar_src)}
<div id="container_avatar"><img src="{$avatar_src}" width="{$avatar_sizes.width}" height="{$avatar_sizes.height}" border="1"></div>
<a href="#download_avatar" onclick="view_downloader('update');return false;" id="link_downloader_avatar">Сменить аватар</a>
<div id="downloader_avatar">
<img id="loading" src="/templates/articler/images/ajax-loaders/loading.gif" style="display:none;">
<form action="" method="POST" enctype="multipart/form-data">
<input id="avatar_file" type="file" size="15" name="avatar_file" class="input">
<button class="button" id="buttonUpload" onclick="return ajaxFileUpload('{site_url('')}');">Загрузить</button>
</form>
</div>
{else:}
<div id="container_avatar"></div>
<a href="#download_avatar" onclick="view_downloader('insert');return false;" id="link_downloader_avatar">Загрузить аватар</a>
<div id="downloader_avatar">
<img id="loading" src="{site_url('')}templates/articler/images/ajax-loaders/loading.gif" style="display:none;">
<form action="" method="POST" enctype="multipart/form-data">
<input id="avatar_file" type="file" size="15" name="avatar_file" class="input">
<button class="button" id="buttonUpload" onclick="return ajaxFileUpload('{site_url('')}');">Загрузить</button>
</form>
</div>

{/if}

<br>
<a href="{site_url('list_articles')}">Список статей</a>
<br>

<a href="{site_url('list_comments')}">Список комментариев</a>

<br>
<a href="#change_email" class="inline">Сменить email</a>

<br>
<a href="#change_password" class="inline">Сменить пароль</a>

<br>
{if $have_purse == 1}
	<a href="#change_purse" class="inline">Сменить кошелек</a>
{else:}
	<a href="#change_purse" class="inline">Назначить кошелек</a>
{/if}
<br>
<span>Черновиков: <b>{$num_articles_rough}</b></span>
<br>
<span>На модерации: <b>{$num_moderate_articles}</b></span>
<br>
<span>На доработке: <b>{$num_articles_rejected}</b></span>
<br>
<span>Всего комментариев: <b id="num_comments">{$num_comments}</b></span>
<br>
<a href="{site_url('avtor/profile')}">Профиль автора</a>
<br>
<span>Авторский рейтинг: <b id="your_rating">{$user_rating}</b></span>

<br>
<span>Рейтинг активности: <b id="your_rating_activity">{$user_rating_activity}</b></span>
<br>
{if isset($author_group)}
<span>Статус: <b>{$author_group}</b></span>
<br>
{/if}
{if $num_visites_all > 0}
<span>Просмотров статей</span>
<br>
<span>&nbsp;&nbsp;Всего: <b>{$num_visites_all}</b></span>
<br>
<span>&nbsp;&nbsp;За прошедшие сутки: <b>{$num_visites_avg}</b></span>
<br>
{/if}
<a href="{site_url('avtor/refers')}">Раздел рефералов</a>
<br>
{if $num_payments_all > 0}
<span>Начислено WMR</span>
<br>
<span>&nbsp;&nbsp;Всего: <b>{$num_payments_all}</b></span>
<br>
<span>&nbsp;&nbsp;За прошедшие сутки: <b>{$num_payments_daily}</b></span>
<br>
&nbsp;&nbsp;<a href="{site_url('avtor/finance')}">Подробнее..</a>
<br>
{/if}
<a href="{site_url('auth/logout')}">Выход</a>
<br>
</div>
{elseif $access == 2}
<!------------------------------- Блок модальных окон модератора-------------------------------->
<div style="display:none;">
{$modal_windows.reply_plea}
</div>

<b>{$profile}</b>
<br>
<span>Всего статей: <b>{$num_articles}</b></span>
<br>
<span>Опубликованных: <b>{$num_published_articles}</b></span>
<br>
<span>Черновиков: <b>{$num_articles_rough}</b></span>
<br>
<span>На модерации: <b>{$num_moderate_articles}</b></span>
<br>
<span>На доработке: <b>{$num_articles_rejected}</b></span>
<br>
<a href="{site_url('moderator/public_articles')}">Опубликованные статьи</a>
<br>
<a href="{site_url('moderator/moderate_articles')}">Статьи для модерации
{if $num_moderate_articles > 0}
<b>({$num_moderate_articles})</b>
{/if}
</a>
<br>
<a href="{site_url('moderator/rubricator')}">Рубрикатор</a>
<br>
{if empty($is_editor)}
<a href="{site_url('moderator/pleas')}">Жалобы
 <b id="num_unconsidered_pleas">({$num_unconsidered_pleas})</b>
</a>
<br>
<span>Рефералы</span>
<br>
&nbsp;&nbsp;<a href="{site_url('moderator/refers')}">Список
</a>
<br>
&nbsp;&nbsp;<a href="{site_url('moderator/refer_settings')}">Настройки
</a>
<br>
<a href="{site_url('moderator/payouts/open')}">Заявки на выплаты
 <b id="num_opened_payouts">({$num_unclosed_payouts})</b>
</a>
<br>
{/if}
{if $num_visites_all > 0}
<span>Просмотров статей</span>
<br>
<span>&nbsp;&nbsp;Всего: <b>{$num_visites_all}</b></span>
<br>
<span>&nbsp;&nbsp;За прошедшие сутки: <b>{$num_visites_avg}</b></span>
<br>
{/if}
<a href="{site_url('private/list_articles')}">Приватный раздел</a>
<br>
<a href="{site_url('moderator/giventopics')}">Заданные темы</a>
<br>
<a href="{site_url('auth/logout')}">Выход</a>
<br>

{else:}
{if isset($first_enter_guest)}

<div style="display:none;">
<div id="container_first_enter_info_guest">
{$modal_windows.first_enter_info_guest}
</div>
</div>
{/if}

{/if}

							<b>РУБРИКАТОР</b>
		
{$counter = 0}							
{foreach $main_headings as $heading}	

<br>
<a id="r{$counter}" name="r{$counter}" href="{site_url('')}{$heading.name}/">{$heading.name_russian}</a>
 
{$counter++}
{/foreach}						
							
<hr>
							<b>Особые Рубрики</b>
							
{$counter = 0}							
{foreach $add_headings as $heading}	

<br>
<a id="or{$counter}" name="or{$counter}" href="{site_url('')}{$heading.name}/">{$heading.name_russian}</a>
 
{$counter++}
{/foreach}
							
</noindex>
						</li>

					</ul>
					<ul>

<br>
<hr>

<!----------------------------------------- Блок песочницы ------------------------------------------------------>
{$sandbox}

<!--------------------------------------------------------------------------------------------------------------->
<hr>

<!----------------------------------------- Блок последних комментариев ------------------------------------------------------>
{$last_comments}

<!--------------------------------------------------------------------------------------------------------------->



<div style="margin-bottom: 20px;" id="sbar-left"></div>



					</ul>

				</div>
		<!-- end #sidebar -->



<div style="position: absolute; margin-top: 70px; margin-left: 200px;" id="fixed">
<noindex>
<br><br><br><br><br>

{$uptolike}


<b><a href="{$THEME}/js/qwer.php" target="_blank">Как начать зарабатывать в интернете? Узнай!</a></b>
<br>
<hr />


  <script type="text/javascript" src="{$THEME}/js/ra-SHS.js"></script>

<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</noindex>
</div>



	</td>
	<td width="800" valign="top" align="left">
	<div id="content">
					<div class="post">
					
					<div class="title">
						 <h1>{$site_title}</h1>
    					</div>
			
						<div style="clear: both;"></div>
						<div class="entry">

<hr>
<noindex>
<script type="text/javascript"
 src="{$THEME}/js/ta-k1.js"></script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</noindex>


<hr>


{if isset($id)}
<script type="text/javascript">
{literal}

	$(function()
	     {
	       $('#fontsize_increase').click(function(event)
	         {event.preventDefault();
	         $('.content').zoomtext({increment: "+=1"});
	         }
	       );
	 
	       $('#fontsize_reset').click(function(event)
	         {event.preventDefault();
	         $('.content').zoomtext({recovery: true});
	         }
	       );
	 
	      $('#fontsize_decrease').click(function(event)
	         {event.preventDefault();
	         $('.content').zoomtext({increment: "-=3"});
	         }
	       );
		 });

{/literal}
</script>

<div style="margin-bottom:10px;">Размер шрифта:
 <span style=cursor:pointer;" id="fontsize_increase"><img src="{$THEME}/images/increase.png" width="16" height="16" style="vertical-align:middle;"></span>
 <span id="fontsize_decrease" style="cursor:pointer;margin-left:-5px;"><img src="{$THEME}/images/decrease.png" width="16" height="16" style="vertical-align:middle;"></span>
 <span style="text-decoration:underline;margin-left:-5px;cursor:pointer;" id="fontsize_reset">Сброс</span>
 </div>
 <hr>
{/if}
<div class="content" style="margin-bottom: 15px;">
{$content}
</div>

<noindex>
{literal}
<script type='text/javascript' id='s-4477c8d9f69d035b'> (function() {
var v = document.createElement('script'); v.type = 'text/javascript';
v.async = true; v.src =
'http://video103.ru/video/?align=bottom&format=rotator&height=350&platformId=5293&width=550&sig=4477c8d9f69d035b';
v.charset = 'utf-8'; var s =
document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(v, s); })(); </script>
{/literal}
<script type="text/javascript"
 src="{$THEME}/js/ta-k1.js"></script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</noindex>





						</div>
					</div>
				</div>
	<!-- end #content -->
	
	</td>
	<td>
	

	</td>
</tr>

</table>
		

				
<!--div style="position: absolute; margin-top: 270px; margin-left: 200px;"-->



				
				

<!--/index-->

<div id="footer">
	<p style="text-align: right; "><big>Copyright © 2011-2014. <a href="http://www.ruavtor.ru/" title="Мнение администрации и редакции ресурса может не совпадать с мнением авторов">Информационный познавательный интернет-журнал "РуАвтор"</a></big>
<span title="Некоторые текстовые, графические, аудио и видео материалы, размещенные на нашем сайте, могут содержать информацию предназначенную для пользователей старше 16 лет (На основании №436-ФЗ от 29.12.2010 года - О защите детей от информации, причиняющей вред их здоровью и развитию.)" style="border: 2px solid rgb(250, 65, 0); padding: 2px 3px 2px 4px; margin-left: 0px; text-align: center; font-size: 12px;"><b style="color: green;">16+</b></span>
         <div style="text-align: right; "><span style="color: #333333;">При полном или частичном копировании материалов активная прямая гипер-ссылка на <b>www.RuAvtor.ru</b> обязательна!  </span>
         </div>

<noindex>

         <div style="text-align: right;">
| <a id="ad" name="ad">Отказ от гарантий (Disclaimer)</a> |  
<script type="text/javascript">document.getElementById("ad").setAttribute("href","http://ruavtor.ru/disclaimer");</script>
         </div>
</noindex>

<div style="text-align: left;"><span style="color: #aeaeae;">
<?php
// Делаем все то же самое, чтобы получить текущее время 
$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0];
$totaltime = ($mtime - $tstart);//Вычисляем разницу 
// Выводим не экран 
printf ("[ %f ]", $totaltime);
?>
</span>
         </div>


        </p>
</div>
<!-- end #footer -->

</body>
</html>