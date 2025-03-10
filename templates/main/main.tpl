<?php
$mtime = microtime();        //Считываем текущее время 
$mtime = explode(" ",$mtime);    //Разделяем секунды и миллисекунды
// Составляем одно число из секунд и миллисекунд
// и записываем стартовое время в переменную  
$tstart = $mtime[1] + $mtime[0];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="https://www.w3.org/1999/xhtml" lang="ru">
<head>
{$page_type = $CI->core->core_data['data_type'];}
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>{$site_title}</title>

<meta name="keywords" content="{$site_keywords}" />
<meta name="description" content="{$site_description}" />
<link rel="icon" href="{$THEME}/images/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="{$THEME}/css/styles.css" />
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="{$THEME}/js/jquery.scrollTo.js"></script>
<script type="text/javascript" src="{$THEME}/js/jquery.zoomtext.js"></script>
<script src="{$THEME}/js/jquery.driving.box.js"></script>
{
	$filesize = 20;
	$message_filesize = str_replace('%kb%',$filesize,lang('allowed_of_the_size_avatar'));
}
<script type="text/javascript">
	var message_filesize = '{$message_filesize}';
	var message_upload = '{lang("articler_upload")}';
	var title_load_avatar = '{lang("load_avatar")}';
	var unknown_error = '{lang("unknown_error")}';
	var site_name = '{$site_name}';
	var advert_confirm_1 = '{lang("advert_confirm_1")}';
	var articler_news = '{lang("articler_news")}';
	var confirm_payout = '{lang("confirm_payout")}';
	var articler_modify = '{lang("modify")}';
	var adding_external_link = '{lang("adding_external_link")}';
	var articler_edit = '{lang("edit")}';
	var articler_edit_comment = '{lang("edit_comment")}';
	var articler_text = '{lang("articler_text")}';
	var not_access_for_comment = '{lang("not_access_for_comment")}';
	var waiting_check_link = '{lang("waiting_check_link")}';
	var articler_upload = '{lang("articler_upload")}';
	var hide_downloader = '{lang("hide_downloader")}';
	var change_avatar = '{lang("change_avatar")}';
	var password_not_empty = '{lang("password_not_empty")}';
	var password_and_confirm_not_match = '{lang("password_and_confirm_not_match")}';
	var purse_field_not_empty = '{lang("purse_field_not_empty")}';
	var new_purse_field_not_empty = '{lang("new_purse_field_not_empty")}';
	var confirm_field_not_empty = '{lang("confirm_field_not_empty")}';
	var email_field_not_empty = '{lang("email_field_not_empty")}';
	var new_email_field_not_empty = '{lang("new_email_field_not_empty")}';
	var email_wrong = '{lang("email_wrong")}';
	var number_of_comments = '{lang("number_of_comments")}';
	var article_is_published = '{lang("article_is_published")}';
	var confirm_moderate_article = '{lang("confirm_moderate_article")}';
	var confirm_unmoderate_article = '{lang("confirm_unmoderate_article")}';
	var confirm_remove_exception = '{lang("confirm_remove_exception")}';
	var confirm_remove_theme = '{lang("confirm_remove_theme")}';
	var confirm_remove_material = '{lang("confirm_remove_material")}';
	var not_remove_published_article = '{lang("not_remove_published_article")}';
	var confirm_remove_article = '{lang("confirm_remove_article")}';
	var confirm_remove_comment = '{lang("confirm_remove_comment")}';
	var not_access_remove_comment = '{lang("not_access_remove_comment")}';
	var articler_theme = '{lang("articler_theme")}';
	var booking_theme = '{lang("booking_theme")}';
	var complaint_moderator_comment = '{lang("complaint_moderator_comment")}';
	var reply_to_comment = '{lang("reply_to_comment")}';
	var missing_response_complaint = '{lang("missing_response_complaint")}';
	var considered = '{lang("considered")}';
	var author_rating = '{lang("author_rating")}';
	var author_rating_cannot_be_less_than_0 = '{lang("author_rating_cannot_be_less_than_0")}';
	var rating_activity = '{lang("rating_activity")}';
	var confirm_change_rating = '{lang("confirm_change_rating")}';
	var ajaxload = '{lang("ajaxload")}';
	var article_rating = '{lang("article_rating")}';
	var article_rating_cannot_be_less_than_0 = '{lang("article_rating_cannot_be_less_than_0")}';
	var confirm_change_rating_article = '{lang("confirm_change_rating_article")}';
	var exceed_limit_change_article_rating = '{lang("exceed_limit_change_article_rating")}';
	var articler_comment_text = '{lang("articler_comment_text")}';
	var did_not_leave_comment = '{lang("did_not_leave_comment")}';
	var empty_field_name = '{lang("empty_field_name")}';
	var empty_field_last_name = '{lang("empty_field_last_name")}';
	var select_rubric_for_publication = '{lang("select_rubric_for_publication")}';
	var rejection_of_the_publication_empty = '{lang("rejection_of_the_publication_empty")}';
	var empty_field_header = '{lang("empty_field_header")}';
	var empty_field_url = '{lang("empty_field_url")}';
	var initial_rating_cannot_be_less_0 = '{lang("initial_rating_cannot_be_less_0")}';
	var initial_rating_cannot_be_less_rating_for_homefeed = '{lang("initial_rating_cannot_be_less_rating_for_homefeed")}';
	var add_payment_cannot_be_less_0 = '{lang("add_payment_cannot_be_less_0")}';
	var initial_rating_cannot_be_empty = '{lang("initial_rating_cannot_be_empty")}';
	var add_payment_cannot_be_empty = '{lang("add_payment_cannot_be_empty")}';
	var url_invalid_characters = '{lang("url_invalid_characters")}';
	var empty_field_title = '{lang("empty_field_title")}';
	var empty_field_code = '{lang("empty_field_code")}';
	var advert_block_attached_rubric = '{lang("advert_block_attached_rubric")}';
	var validate_annotation_less_symbols = '{lang("validate_annotation_less_symbols")}';
	var validate_annotation_more_symbols = '{lang("validate_annotation_more_symbols")}';
	var validate_description_less_symbols = '{lang("validate_description_less_symbols")}';
	var validate_description_more_symbols = '{lang("validate_description_more_symbols")}';
	var validate_keywords_less_symbols = '{lang("validate_keywords_less_symbols")}';
	var validate_keywords_more_symbols = '{lang("validate_keywords_more_symbols")}';
	var url_forbidden_or_wrong = '{lang("url_forbidden_or_wrong")}';
	var url_have_not_link_site = '{lang("url_have_not_link_site")}';
	var referral_add_must_not_less = '{lang("referral_add_must_not_less")}';
	var referral_user_add_must_not_less = '{lang("referral_user_add_must_not_less")}';
	var referral_percent_add_must_not_less = '{lang("referral_percent_add_must_not_less")}';
	var articler_refresh = '{lang("articler_refresh")}';
	var advert_blocks = '{lang("advert_blocks")}';
	var change_rating_must_register = '{lang("change_rating_must_register")}';
	var for_change_rating_go_to_link = '{lang("for_change_rating_go_to_link")}';
	var change_article_rating = '{lang("change_article_rating")}';
	
</script>
{if $THEME == 'articler'}
{literal}
<script>
    $(function() {    
        $( '#fixed' ).drivingBox( '#page', 100 );        
    });
		
</script>
{/literal}
{/if}
	<link rel="stylesheet" type="text/css" href="{$THEME}/js/alerts/jquery.alerts.css" />
	<script type="text/javascript" src="{$THEME}/js/functions.js"></script>
	<script type="text/javascript" src="{$THEME}/js/jquery.alerts.js"></script>
	<script type="text/javascript" src="{$THEME}/js/jdate.js"></script>
	<script type="text/javascript" src="{$THEME}/js/jquery.tipTip.js"></script>
	<script type="text/javascript" src="{$THEME}/js/jquery.livequery.js"></script>
	<script type="text/javascript" src="{$THEME}/js/jquery.colorbox.js"></script>
	<script type="text/javascript" src="{$THEME}/js/overlib/overlib.js"></script>
	<script type="text/javascript">
	{literal}
	$(document).ready(function(){
		$(".inline").colorbox({inline:true, width:"50%"});
		$('.navigation-block').click(function () {
                $('nav').fadeIn();
            });
            $('navigation-block').click(function (event) {
                $target = $(event.target);
                if (!$target.closest($('.window-nav')).length) $('nav').fadeOut();
                if ($target.hasClass('close-window')) $('nav').fadeOut();
            });
		
	});	
	{/literal}
	</script>
	{if isset($first_enter)}
	{literal}
	<script type="text/javascript">
		var enter_info = $('#container_first_enter_info').html();
	</script>
	{/literal}
	{elseif isset($first_enter_guest)}
		{literal}
	<script type="text/javascript">
		var enter_info = $('#container_first_enter_info_guest').html();
	</script>
		{/literal}	
	{/if}
	
	{literal}
	<script type="text/javascript">
	$(document).ready(function(){
		if(typeof(enter_info) != 'undefined')
			$.colorbox({html: enter_info, open: true, opacity: 0.5});

		$('.navigation').click(function () {
                $('.navigation-block').fadeIn();
            });
            $('.navigation-block').click(function (event) {
                $target = $(event.target);
                if (!$target.closest($('.window-nav')).length) $('.navigation-block').fadeOut();
                if ($target.hasClass('close-window')) $('.navigation-block').fadeOut();
            });
            /****************************/
            var subMenu = $('.sub-menu');
            var menuControl = $('.menu-control');
            $(menuControl).click(function () {
                if($(subMenu).hasClass('visible')) {
                    $(subMenu).removeClass('visible');
                    $(menuControl).removeClass('menu-active');
                    $('.account-menu').css('border-radius', '5px');
                } else {
                    $(subMenu).addClass('visible');
                    $(menuControl).addClass('menu-active');
                    $('.account-menu').css('border-radius', '5px 5px 0 0');
                }
            });
            /************* for navi-block *************/
            var h_hght = 135;
            var h_mrg = 0;
            $(function(){
                $(window).scroll(function(){
                    var top = $(this).scrollTop();
                    var elem = $('#navi-block');
                    if (top+h_mrg < h_hght) {
                        elem.css('top', (h_hght-top));
                    } else {
                        elem.css('top', h_mrg);
                    }
                });
            });
            /****************/
            $('.hint-mark').click(function(){
                if($(this).hasClass('active')){
                    $('.hint').fadeOut(1000);
                    $(this).removeClass('active');
                } else {
                    $('.hint').fadeIn(1000);
                    $(this).addClass('active');
                }
            });
	});
	
	</script>
	{/literal}
	
	
	{if $access == 1 || $access == 2}
	
		<link rel="stylesheet" type="text/css" href="{$THEME}/css/jquery.treeview.css" />
		<script type="text/javascript" src="{$THEME}/js/jquery.treeview.js"></script>
		<script type="text/javascript" src="{$THEME}/js/ajaxfileupload.js"></script>
	{/if}
	<link rel="stylesheet" type="text/css" href="{$THEME}/css/jquery.autocomplete.css" />
	<script type="text/javascript" src="{$THEME}/js/jquery.autocomplete.js"></script>
	<script type="text/javascript" src="{$THEME}/js/data.js"></script>

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
<div  id="overDiv"  style="position:absolute;  visibility:hidden; z-index:1000;"></div>  
<div class="main">

	 <div class="right-column">
		{if isset($is_index)}
        	<div class="block-title">{lang('home_message_header')}</div>
       {/if}

		<div class="content">
			{$content}
		</div>

    </div>
    <div class="left-column">

		<div id="navi-block" class="navi-block" style="top: 135px;">
			
			<button class="navigation">{lang('navigation')}</button>

		
			{if $is_moderator == 1 && $activity == 2}
				{if empty($is_edited) && $id}
					<a href="{site_url('moderator/edit')}/{$id}" target="_blank">
						<button class="add-material">{lang('edit_material')}</button>
					</a>

				{/if}
				{elseif $is_moderator == 1 && $activity < 2}

				{else:}
				{if $access == 2}

				{else:}
				<a href="{site_url('transit')}" target="_blank">
		      		<button class="add-material" onclick="add_article();return false;">{lang('add_material')}</button>
				</a>
			{/if}

			{/if}

		</div>
        
		
		<!----------------------------------------- Top 5 Author's section ------------------------------------------------->


			{$top_authors}

        <!----------------------------------------- Sandbox's section ------------------------------------------------------>
			{$sandbox}
        <div class="block-title">{lang('responses')}</div>
       <!----------------------------------------- Recent comment's section ------------------------------------------------------>
		{$last_comments}

</div>

</div>

<div class="header">
    <div class="header-block">
	<a href="{site_url('')}">
		<div class="logo"></div>
	</a>
        <button class="tagline">{lang('home_tagline')}</button>
<a href="/contests/informer-of-the-contest-1-a-referral-contest_7926.html">
        <button class="competition">{lang('contest')}</button>
</a>
        <!--form class="form-authorization" action="#" onsubmit="return false"-->
		{if validation_errors() OR $info_message}
			<script type="text/javascript">
				var error = '{$info_message}';
				alert(error);
			</script>
		{/if}
		{if $access == 0}
        <form class="form-authorization" action="/auth/login" method="POST">
            <div class="to-reg"><a href="/auth/register" style="color: inherit;">{lang('articler_register')}</a></div>
            <div class="forget-pass"><a href="/auth/forgot_password" style="color: inherit;">{lang('articler_forgot_password')}</a></div>
            <label><input id="username" placeholder="{lang('your_login')}" type="text" onfocus="if(this.value=='{lang('enter_your_login')}') this.value='';" onblur="if(this.value=='') this.value='{lang('enter_your_login')}';" name="username"></label>
            <label><input id="password" placeholder="{lang('your_password')}" type="password" onfocus="if(this.value=='{lang('articler_password')}') this.value='';" onblur="if(this.value=='') this.value='{lang('articler_password')}';" name="password"></label>
            <label class="checkbox-label">{lang('articler_remember_me')}<input type="checkbox"></label>
            <input value="{lang('enter')}" type="submit">
			{form_csrf()}
        </form>
		{else:}
		
<!------------------------------- Section of modal windows -------------------------------->
{if $access == 1}
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
{/if}


<!------------------------------------------------------------------------------------------------------------->
 <div class="user-avatar">
 	{if isset($avatar_src)}
   		<!--img src="{$avatar_src}" width="{$avatar_sizes.width}" height="{$avatar_sizes.height}" border="1"-->
   		<!--img src="{$avatar_src}" width="{$avatar_sizes.width}" height="{$avatar_sizes.height}" border="1" style="cursor: pointer;" onclick="modal_change_avatar('{site_url('')}');" title="Change your avatar"-->
   		<img src="{$avatar_src}" width="40" height="40" border="1" style="cursor: pointer;" onclick="modal_change_avatar('{site_url('')}');" title="{lang('change_avatar')}">
	{/if}
 </div>
        <div class="user-login" {if isset($avatar_src)}title="{lang('change_avatar')}"{else:}title="{lang('load_avatar')}"{/if} onclick="modal_change_avatar('{site_url('')}');">{ucfirst($login)}</div>

        <div class="account-menu">
            <div class="menu-title">{lang('account')}</div>
            <div class="menu-control">
                <span class="figure-3"></span>
            </div>
            <ul class="sub-menu">
				
                <li>
                    <ul class="account-info">
						<li>
                            <div class="param-name">{lang('all_articles')}:</div>
                            <div class="param-value">{$num_articles}</div>
                        </li>
						<li>
                            <div class="param-name">{lang('published')}:</div>
                            <div class="param-value">{$num_published_articles}</div>
                        </li>
                        <li>
                            <div class="param-name">{lang('draft_articles')}:</div>
                            <div class="param-value">{$num_articles_rough}</div>
                        </li>
                        <li>
                            <div class="param-name">{lang('moderation_articles')}:</div>
                            <div class="param-value">{$num_moderate_articles}</div>
                        </li>
                        <li>
                            <div class="param-name">{lang('on_revision_articles')}:</div>
                            <div class="param-value">{$num_articles_rejected}</div>
                        </li>
                        {if $is_moderator == 0}
                        <li>
                            <div class="param-name">{lang('author_rating')}:</div>
                            <div class="param-value" id="your_rating">{$user_rating}</div>
                        </li>
                        <li>
                            <div class="param-name">{lang('rating_activity')}:</div>
                            <div class="param-value" id="your_rating_activity">{$user_rating_activity}</div>
                            <div class="hint-mark">i<p class="hint">{lang('user_activity_rating')}</p></div>
                        </li>
                         {/if}
                         <li>
                            <div class="param-name">{lang('articles_views')}</div>
                            <div class="param-value"></div>
                        </li>
                        <li>
                            <div class="param-name">&nbsp;&nbsp;{lang('total_views')}:</div>
                            <div class="param-value">{$num_visites_all}</div>
                        </li>
                        <li>
                            <div class="param-name">&nbsp;&nbsp;{lang('views_last_day')}:</div>
                            <div class="param-value">{$num_visites_avg}</div>
                        </li>
                       
						{if isset($author_group)}
						 <li>
                            <div class="param-name">{lang('status')}:</div>
                            <div class="param-value">{$author_group}</div>
                        </li>
						{/if}
						{if $num_payments_all > 0}
						<li>
							<div class="param-name">{lang('accrued_money')}</div>
							<div class="param-value"></div>
						</li>
						<li>
							<div class="param-name">&nbsp;&nbsp;{lang('total_views')}</div>
							<div class="param-value">{sprintf("%01.1f",$num_payments_all)}</div>
						</li>
						<li>
							<div class="param-name">&nbsp;&nbsp;{lang('over_past_week')}</div>
							<div class="param-value">{sprintf("%01.1f",$num_payments_weekly)}</div>
						</li>
						{/if}
                    </ul>
                </li>
                {if $is_moderator == 0}
                <li>
					<span>
						<a href="{site_url('author/profile')}">{lang('author_profile')}</a>
					</span>
				</li>
				 <li>
					<span>
						<a href="{site_url('author/finance')}">{lang('finance_section')}</a>
					</span>
				</li>
                <li>
					<span>
						<a href="{site_url('author/refers')}">{lang('referral_section')}</a>
					</span>
				</li>
                <li>
					<span>
						<a href="{site_url('list_articles')}">{lang('articles_list')}</a>
					</span>
				</li>
                <li>
					<span>
						<a href="{site_url('list_comments')}">{lang('comments_list')}</a>
					</span>
				</li>
                <li>
					<span>
						<a href="#change_email" class="inline">{lang('change_email')}</a>
					</span>
				</li>
				 <li>
					<span>
						<a href="#change_password" class="inline">{lang('change_password')}</a>
					</span>
				</li>
                <li>
					<span>
						{if $have_purse == 1}
							<a href="#change_purse" class="inline">{lang('change_purse')}</a>
						{else:}
							<a href="#change_purse" class="inline">{lang('assign_purse')}</a>
						{/if}
					</span>
				</li>
				{else:}
					<li>
						<span>
							<a href="{site_url('moderator/public_articles')}">{lang('published_articles')}</a>
						</span>
					</li>
					<li>
						<span>
							<a href="{site_url('moderator/moderate_articles')}">{lang('articles_for_moderation')}
							<span>
							{if $num_moderate_articles > 0}
								({$num_moderate_articles})
							{/if}
							</span>
							</a>
						</span>
					</li>
					<li>
						<span>
							<a href="{site_url('moderator/rubricator')}">{lang('rubricator')}</a>
						</span>
					</li>
					{if empty($is_editor)}
						<li>
							<span>
								<a href="{site_url('moderator/pleas')}">{lang('pleas')} <span id="num_unconsidered_pleas">({$num_unconsidered_pleas})</span></a>
							</span>
						</li>
						<li>
							<span>
								<a href="{site_url('moderator/refers')}">{lang('referrals_list')}</a>
							</span>
						</li>
						<li>
							<span>
								<a href="{site_url('moderator/refer_settings')}">{lang('referrals_settings')}</a>
							</span>
						</li>
						<li>
							<span>
								<a href="{site_url('moderator/payouts/open')}">{lang('payouts_requests')} <span id="num_opened_payouts">({$num_unclosed_payouts})</span></a>
							</span>
						</li>
						<li>
							<span>
								<a href="{site_url('private/list_articles')}">{lang('private_section')}</a>
							</span>
						</li>
						<li>
							<span>
								<a href="{site_url('moderator/giventopics')}">{lang('assigned_topics')}</a>
							</span>
						</li>
						<li>
							<span>
								<a href="{site_url('moderator/adverts')}">{lang('advert_blocks')}</a>
							</span>
						</li>
					{/if}
				{/if}
                <li>
					<span>
						<a href="{site_url('auth/logout')}">{lang('articler_logout')}</a>
					</span>
				</li>
            </ul>
        </div>

		
		{/if}
    </div>
</div>

</div>
<div class="navigation-block">
    <div class="window-nav">
        <div class="title-window">{lang('navigation')}</div>
        <div class="close-window">{lang('close')}</div>
        <ul class="nav-pages">
            <li><a href="{site_url('authors')}">{lang('authors')}</a></li>
            <li><a href="{site_url('rules.html')}">{lang('rules')}</a></li>
            <li><a href="{site_url('news')}">{lang('url_news')}</a></li>
            <li><a href="{site_url('contact.html')}">{lang('contacts')}</a></li>
            <li><a href="{site_url('search')}">{lang('site_search')}</a></li>
        </ul>
		{$counter = 0}	
        <div class="block-title">{lang('rubrics')}</div>
        <ul class="rubrics">
		 <li> <ul>
		{foreach $main_headings as $heading}	

              <li style="white-space: nowrap;"><span class="hidden-link" data-link="{site_url('')}{$heading.name}/">{$heading.name_russian}</span></li>
				 {$counter++}
				{if $counter == 5}
					</ul></li><li><ul>
					{$counter = 0}
				{/if}
	
			{/foreach}
			</ul></li>
        </ul>
		
        <div class="block-title">{lang('special_rubrics')}</div>
		
		<ul class="rubrics">
		 <li> <ul>
		 {$counter = 0}	
		{foreach $add_headings as $heading}	

             <li style="white-space: nowrap;"><span class="hidden-link" data-link="{site_url('')}{$heading.name}/">{$heading.name_russian}</span></li>
				 {$counter++}
				{if $counter == 4}
					</ul></li><li><ul>
					{$counter = 0}
				{/if}
	
			{/foreach}
			</ul></li>
        </ul>
    </div>
</div>


<div class="footer">
    <div class="content-block">
		{
			$footer_message = lang('footer_message');
			$footer_message = str_replace('%date%',date('Y'),$footer_message);
		}
       {$footer_message}
    </div>
</div>
<script type="text/javascript" src="/js/arrow34.js"></script>
{literal}
<script>$('.hidden-link').replaceWith(function(){return'<a href="'+$(this).data('link')+'">'+$(this).text()+'</a>';})</script>
{/literal}

</body></html>