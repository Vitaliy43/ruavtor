<?php $mtime = microtime();        //Считываем текущее время 
$mtime = explode(" ",$mtime);    //Разделяем секунды и миллисекунды
// Составляем одно число из секунд и миллисекунд
// и записываем стартовое время в переменную  
$tstart = $mtime[1] + $mtime[0];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="https://www.w3.org/1999/xhtml" lang="ru">
<head>
<?php $page_type = $CI->core->core_data['data_type'];?>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title><?php if(isset($site_title)){ echo $site_title; } ?></title>

<meta name="keywords" content="<?php if(isset($site_keywords)){ echo $site_keywords; } ?>" />
<meta name="description" content="<?php if(isset($site_description)){ echo $site_description; } ?>" />
<link rel="icon" href="<?php if(isset($THEME)){ echo $THEME; } ?>/images/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="<?php if(isset($THEME)){ echo $THEME; } ?>/css/styles.css" />
<link rel="stylesheet" type="text/css" href="<?php if(isset($THEME)){ echo $THEME; } ?>/css/main.css" />
<style>
    #fixed {
        padding-bottom: 0px;
    }
    #footer {
        margin-bottom: 0px;
    }
</style>

<link rel="stylesheet" type="text/css" href="<?php if(isset($THEME)){ echo $THEME; } ?>/css/tipTip.css" />
<link rel="stylesheet" type="text/css" href="<?php if(isset($THEME)){ echo $THEME; } ?>/css/jdate.css" />
<link rel="stylesheet" type="text/css" href="<?php if(isset($THEME)){ echo $THEME; } ?>/css/colorbox/colorbox.css" />

<!--script type="text/javascript" src="<?php if(isset($THEME)){ echo $THEME; } ?>/js/jquery.js"></script-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php if(isset($THEME)){ echo $THEME; } ?>/js/jquery.scrollTo.js"></script>
<script type="text/javascript" src="<?php if(isset($THEME)){ echo $THEME; } ?>/js/jquery.zoomtext.js"></script>
<script src="<?php if(isset($THEME)){ echo $THEME; } ?>/js/jquery.driving.box.js"></script>
<?php $filesize = 20;
	$message_filesize = str_replace('%kb%',$filesize,lang('allowed_of_the_size_avatar'));
?>
<script type="text/javascript">
	var message_filesize = '<?php if(isset($message_filesize)){ echo $message_filesize; } ?>';
	var message_upload = '<?php echo lang ("articler_upload"); ?>';
	var title_load_avatar = '<?php echo lang ("load_avatar"); ?>';
	var unknown_error = '<?php echo lang ("unknown_error"); ?>';
	var site_name = '<?php if(isset($site_name)){ echo $site_name; } ?>';
	var advert_confirm_1 = '<?php echo lang ("advert_confirm_1"); ?>';
	var articler_news = '<?php echo lang ("articler_news"); ?>';
	var confirm_payout = '<?php echo lang ("confirm_payout"); ?>';
	var articler_modify = '<?php echo lang ("modify"); ?>';
	var adding_external_link = '<?php echo lang ("adding_external_link"); ?>';
	var articler_edit = '<?php echo lang ("edit"); ?>';
	var articler_edit_comment = '<?php echo lang ("edit_comment"); ?>';
	var articler_text = '<?php echo lang ("articler_text"); ?>';
	var not_access_for_comment = '<?php echo lang ("not_access_for_comment"); ?>';
	var waiting_check_link = '<?php echo lang ("waiting_check_link"); ?>';
	var articler_upload = '<?php echo lang ("articler_upload"); ?>';
	var hide_downloader = '<?php echo lang ("hide_downloader"); ?>';
	var change_avatar = '<?php echo lang ("change_avatar"); ?>';
	var password_not_empty = '<?php echo lang ("password_not_empty"); ?>';
	var password_and_confirm_not_match = '<?php echo lang ("password_and_confirm_not_match"); ?>';
	var purse_field_not_empty = '<?php echo lang ("purse_field_not_empty"); ?>';
	var new_purse_field_not_empty = '<?php echo lang ("new_purse_field_not_empty"); ?>';
	var confirm_field_not_empty = '<?php echo lang ("confirm_field_not_empty"); ?>';
	var email_field_not_empty = '<?php echo lang ("email_field_not_empty"); ?>';
	var new_email_field_not_empty = '<?php echo lang ("new_email_field_not_empty"); ?>';
	var email_wrong = '<?php echo lang ("email_wrong"); ?>';
	var number_of_comments = '<?php echo lang ("number_of_comments"); ?>';
	var article_is_published = '<?php echo lang ("article_is_published"); ?>';
	var confirm_moderate_article = '<?php echo lang ("confirm_moderate_article"); ?>';
	var confirm_unmoderate_article = '<?php echo lang ("confirm_unmoderate_article"); ?>';
	var confirm_remove_exception = '<?php echo lang ("confirm_remove_exception"); ?>';
	var confirm_remove_theme = '<?php echo lang ("confirm_remove_theme"); ?>';
	var confirm_remove_material = '<?php echo lang ("confirm_remove_material"); ?>';
	var not_remove_published_article = '<?php echo lang ("not_remove_published_article"); ?>';
	var confirm_remove_article = '<?php echo lang ("confirm_remove_article"); ?>';
	var confirm_remove_comment = '<?php echo lang ("confirm_remove_comment"); ?>';
	var not_access_remove_comment = '<?php echo lang ("not_access_remove_comment"); ?>';
	var articler_theme = '<?php echo lang ("articler_theme"); ?>';
	var booking_theme = '<?php echo lang ("booking_theme"); ?>';
	var complaint_moderator_comment = '<?php echo lang ("complaint_moderator_comment"); ?>';
	var reply_to_comment = '<?php echo lang ("reply_to_comment"); ?>';
	var missing_response_complaint = '<?php echo lang ("missing_response_complaint"); ?>';
	var considered = '<?php echo lang ("considered"); ?>';
	var author_rating = '<?php echo lang ("author_rating"); ?>';
	var author_rating_cannot_be_less_than_0 = '<?php echo lang ("author_rating_cannot_be_less_than_0"); ?>';
	var rating_activity = '<?php echo lang ("rating_activity"); ?>';
	var confirm_change_rating = '<?php echo lang ("confirm_change_rating"); ?>';
	var ajaxload = '<?php echo lang ("ajaxload"); ?>';
	var article_rating = '<?php echo lang ("article_rating"); ?>';
	var article_rating_cannot_be_less_than_0 = '<?php echo lang ("article_rating_cannot_be_less_than_0"); ?>';
	var confirm_change_rating_article = '<?php echo lang ("confirm_change_rating_article"); ?>';
	var exceed_limit_change_article_rating = '<?php echo lang ("exceed_limit_change_article_rating"); ?>';
	var articler_comment_text = '<?php echo lang ("articler_comment_text"); ?>';
	var did_not_leave_comment = '<?php echo lang ("did_not_leave_comment"); ?>';
	var empty_field_name = '<?php echo lang ("empty_field_name"); ?>';
	var empty_field_last_name = '<?php echo lang ("empty_field_last_name"); ?>';
	var select_rubric_for_publication = '<?php echo lang ("select_rubric_for_publication"); ?>';
	var rejection_of_the_publication_empty = '<?php echo lang ("rejection_of_the_publication_empty"); ?>';
	var empty_field_header = '<?php echo lang ("empty_field_header"); ?>';
	var empty_field_url = '<?php echo lang ("empty_field_url"); ?>';
	var initial_rating_cannot_be_less_0 = '<?php echo lang ("initial_rating_cannot_be_less_0"); ?>';
	var initial_rating_cannot_be_less_rating_for_homefeed = '<?php echo lang ("initial_rating_cannot_be_less_rating_for_homefeed"); ?>';
	var add_payment_cannot_be_less_0 = '<?php echo lang ("add_payment_cannot_be_less_0"); ?>';
	var initial_rating_cannot_be_empty = '<?php echo lang ("initial_rating_cannot_be_empty"); ?>';
	var add_payment_cannot_be_empty = '<?php echo lang ("add_payment_cannot_be_empty"); ?>';
	var url_invalid_characters = '<?php echo lang ("url_invalid_characters"); ?>';
	var empty_field_title = '<?php echo lang ("empty_field_title"); ?>';
	var empty_field_code = '<?php echo lang ("empty_field_code"); ?>';
	var advert_block_attached_rubric = '<?php echo lang ("advert_block_attached_rubric"); ?>';
	var validate_annotation_less_symbols = '<?php echo lang ("validate_annotation_less_symbols"); ?>';
	var validate_annotation_more_symbols = '<?php echo lang ("validate_annotation_more_symbols"); ?>';
	var validate_description_less_symbols = '<?php echo lang ("validate_description_less_symbols"); ?>';
	var validate_description_more_symbols = '<?php echo lang ("validate_description_more_symbols"); ?>';
	var validate_keywords_less_symbols = '<?php echo lang ("validate_keywords_less_symbols"); ?>';
	var validate_keywords_more_symbols = '<?php echo lang ("validate_keywords_more_symbols"); ?>';
	var url_forbidden_or_wrong = '<?php echo lang ("url_forbidden_or_wrong"); ?>';
	var url_have_not_link_site = '<?php echo lang ("url_have_not_link_site"); ?>';
	var referral_add_must_not_less = '<?php echo lang ("referral_add_must_not_less"); ?>';
	var referral_user_add_must_not_less = '<?php echo lang ("referral_user_add_must_not_less"); ?>';
	var referral_percent_add_must_not_less = '<?php echo lang ("referral_percent_add_must_not_less"); ?>';
	var articler_refresh = '<?php echo lang ("articler_refresh"); ?>';
	var advert_blocks = '<?php echo lang ("advert_blocks"); ?>';
	var change_rating_must_register = '<?php echo lang ("change_rating_must_register"); ?>';
	var for_change_rating_go_to_link = '<?php echo lang ("for_change_rating_go_to_link"); ?>';
	var change_article_rating = '<?php echo lang ("change_article_rating"); ?>';
	
</script>
<?php if($THEME == 'articler'): ?>
<script>
    $(function() {    
        $( '#fixed' ).drivingBox( '#page', 100 );        
    });
		
</script>

<?php endif; ?>
	<link rel="stylesheet" type="text/css" href="<?php if(isset($THEME)){ echo $THEME; } ?>/js/alerts/jquery.alerts.css" />
	<script type="text/javascript" src="<?php if(isset($THEME)){ echo $THEME; } ?>/js/functions.js"></script>
	<script type="text/javascript" src="<?php if(isset($THEME)){ echo $THEME; } ?>/js/jquery.alerts.js"></script>
	<script type="text/javascript" src="<?php if(isset($THEME)){ echo $THEME; } ?>/js/jdate.js"></script>
	<script type="text/javascript" src="<?php if(isset($THEME)){ echo $THEME; } ?>/js/jquery.tipTip.js"></script>
	<script type="text/javascript" src="<?php if(isset($THEME)){ echo $THEME; } ?>/js/jquery.livequery.js"></script>
	<script type="text/javascript" src="<?php if(isset($THEME)){ echo $THEME; } ?>/js/jquery.colorbox.js"></script>
	<script type="text/javascript" src="<?php if(isset($THEME)){ echo $THEME; } ?>/js/overlib/overlib.js"></script>
	<script type="text/javascript">
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
	
	</script>
	<?php if(isset($first_enter)): ?>
	<script type="text/javascript">
		var enter_info = $('#container_first_enter_info').html();
	</script>
	
	<?php elseif (isset($first_enter_guest)): ?>
	<script type="text/javascript">
		var enter_info = $('#container_first_enter_info_guest').html();
	</script>
			
	<?php endif; ?>
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
	
	
	
	<?php if($access == 1 || $access == 2): ?>
	
		<link rel="stylesheet" type="text/css" href="<?php if(isset($THEME)){ echo $THEME; } ?>/css/jquery.treeview.css" />
		<script type="text/javascript" src="<?php if(isset($THEME)){ echo $THEME; } ?>/js/jquery.treeview.js"></script>
		<script type="text/javascript" src="<?php if(isset($THEME)){ echo $THEME; } ?>/js/ajaxfileupload.js"></script>
	<?php endif; ?>
	<link rel="stylesheet" type="text/css" href="<?php if(isset($THEME)){ echo $THEME; } ?>/css/jquery.autocomplete.css" />
	<script type="text/javascript" src="<?php if(isset($THEME)){ echo $THEME; } ?>/js/jquery.autocomplete.js"></script>
	<script type="text/javascript" src="<?php if(isset($THEME)){ echo $THEME; } ?>/js/data.js"></script>
<script type="text/javascript">
$(function(){
$(".link_info").tipTip();
});
</script>

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
		<?php if(isset($is_index)): ?>
        	<div class="block-title"><?php echo lang ('home_message_header'); ?></div>
       <?php endif; ?>

		<div class="content">
			<?php if(isset($content)){ echo $content; } ?>
		</div>

    </div>
    <div class="left-column">

		<div id="navi-block" class="navi-block" style="top: 135px;">
			
			<button class="navigation"><?php echo lang ('navigation'); ?></button>

		
			<?php if($is_moderator == 1 && $activity == 2): ?>
				<?php if(empty($is_edited) && $id): ?>
					<a href="<?php echo site_url ('moderator/edit'); ?>/<?php if(isset($id)){ echo $id; } ?>" target="_blank">
						<button class="add-material"><?php echo lang ('edit_material'); ?></button>
					</a>

				<?php endif; ?>
				<?php elseif ($is_moderator == 1 && $activity < 2): ?>

				<?php else:?>
				<?php if($access == 2): ?>

				<?php else:?>
				<a href="<?php echo site_url ('transit'); ?>" target="_blank">
		      		<button class="add-material" onclick="add_article();return false;"><?php echo lang ('add_material'); ?></button>
				</a>
			<?php endif; ?>

			<?php endif; ?>

		</div>
        
		
		<!----------------------------------------- Top 5 Author's section ------------------------------------------------->


			<?php if(isset($top_authors)){ echo $top_authors; } ?>

        <!----------------------------------------- Sandbox's section ------------------------------------------------------>
			<?php if(isset($sandbox)){ echo $sandbox; } ?>
        <div class="block-title"><?php echo lang ('responses'); ?></div>
       <!----------------------------------------- Recent comment's section ------------------------------------------------------>
		<?php if(isset($last_comments)){ echo $last_comments; } ?>

</div>

</div>

<div class="header">
    <div class="header-block">
	<a href="<?php echo site_url (''); ?>">
		<div class="logo"></div>
	</a>
        <button class="tagline"><?php echo lang ('home_tagline'); ?></button>
<a href="/contests/informer-of-the-contest-1-a-referral-contest_7926.html">
        <button class="competition"><?php echo lang ('contest'); ?></button>
</a>
        <!--form class="form-authorization" action="#" onsubmit="return false"-->
		<?php if(validation_errors() OR $info_message): ?>
			<script type="text/javascript">
				var error = '<?php if(isset($info_message)){ echo $info_message; } ?>';
				alert(error);
			</script>
		<?php endif; ?>
		<?php if($access == 0): ?>
        <form class="form-authorization" action="/auth/login" method="POST">
            <div class="to-reg"><a href="/auth/register" style="color: inherit;"><?php echo lang ('articler_register'); ?></a></div>
            <div class="forget-pass"><a href="/auth/forgot_password" style="color: inherit;"><?php echo lang ('articler_forgot_password'); ?></a></div>
            <label><input id="username" placeholder="<?php echo lang ('your_login'); ?>" type="text" onfocus="if(this.value=='<?php echo lang ('enter_your_login'); ?>') this.value='';" onblur="if(this.value=='') this.value='<?php echo lang ('enter_your_login'); ?>';" name="username"></label>
            <label><input id="password" placeholder="<?php echo lang ('your_password'); ?>" type="password" onfocus="if(this.value=='<?php echo lang ('articler_password'); ?>') this.value='';" onblur="if(this.value=='') this.value='<?php echo lang ('articler_password'); ?>';" name="password"></label>
            <label class="checkbox-label"><?php echo lang ('articler_remember_me'); ?><input type="checkbox"></label>
            <input value="<?php echo lang ('enter'); ?>" type="submit">
			<?php echo form_csrf (); ?>
        </form>
		<?php else:?>
		
<!------------------------------- Section of modal windows -------------------------------->
<?php if($access == 1): ?>
<div style="display:none;">
<?php echo $modal_windows['change_email']; ?>
<?php echo $modal_windows['change_password']; ?>
<?php echo $modal_windows['change_purse']; ?>
<?php echo $modal_windows['reply_comment']; ?>
<?php echo $modal_windows['add_plea']; ?>
<?php echo $modal_windows['add_outer_link']; ?>
<?php if(isset($first_enter)): ?>
<div id="container_first_enter_info">
<?php echo $modal_windows['first_enter_info']; ?>
</div>
<?php endif; ?>
</div>
<?php endif; ?>


<!------------------------------------------------------------------------------------------------------------->
 <div class="user-avatar">
 	<?php if(isset($avatar_src)): ?>
   		<!--img src="<?php if(isset($avatar_src)){ echo $avatar_src; } ?>" width="<?php echo $avatar_sizes['width']; ?>" height="<?php echo $avatar_sizes['height']; ?>" border="1"-->
   		<!--img src="<?php if(isset($avatar_src)){ echo $avatar_src; } ?>" width="<?php echo $avatar_sizes['width']; ?>" height="<?php echo $avatar_sizes['height']; ?>" border="1" style="cursor: pointer;" onclick="modal_change_avatar('<?php echo site_url (''); ?>');" title="Change your avatar"-->
   		<img src="<?php if(isset($avatar_src)){ echo $avatar_src; } ?>" width="40" height="40" border="1" style="cursor: pointer;" onclick="modal_change_avatar('<?php echo site_url (''); ?>');" title="<?php echo lang ('change_avatar'); ?>">
	<?php endif; ?>
 </div>
        <div class="user-login" <?php if(isset($avatar_src)): ?>title="<?php echo lang ('change_avatar'); ?>"<?php else:?>title="<?php echo lang ('load_avatar'); ?>"<?php endif; ?> onclick="modal_change_avatar('<?php echo site_url (''); ?>');"><?php echo ucfirst ($login); ?></div>

        <div class="account-menu">
            <div class="menu-title"><?php echo lang ('account'); ?></div>
            <div class="menu-control">
                <span class="figure-3"></span>
            </div>
            <ul class="sub-menu">
				
                <li>
                    <ul class="account-info">
						<li>
                            <div class="param-name"><?php echo lang ('all_articles'); ?>:</div>
                            <div class="param-value"><?php if(isset($num_articles)){ echo $num_articles; } ?></div>
                        </li>
						<li>
                            <div class="param-name"><?php echo lang ('published'); ?>:</div>
                            <div class="param-value"><?php if(isset($num_published_articles)){ echo $num_published_articles; } ?></div>
                        </li>
                        <li>
                            <div class="param-name"><?php echo lang ('draft_articles'); ?>:</div>
                            <div class="param-value"><?php if(isset($num_articles_rough)){ echo $num_articles_rough; } ?></div>
                        </li>
                        <li>
                            <div class="param-name"><?php echo lang ('moderation_articles'); ?>:</div>
                            <div class="param-value"><?php if(isset($num_moderate_articles)){ echo $num_moderate_articles; } ?></div>
                        </li>
                        <li>
                            <div class="param-name"><?php echo lang ('on_revision_articles'); ?>:</div>
                            <div class="param-value"><?php if(isset($num_articles_rejected)){ echo $num_articles_rejected; } ?></div>
                        </li>
                        <?php if($is_moderator == 0): ?>
                        <li>
                            <div class="param-name"><?php echo lang ('author_rating'); ?>:</div>
                            <div class="param-value" id="your_rating"><?php if(isset($user_rating)){ echo $user_rating; } ?></div>
                        </li>
                        <li>
                            <div class="param-name"><?php echo lang ('rating_activity'); ?>:</div>
                            <div class="param-value" id="your_rating_activity"><?php if(isset($user_rating_activity)){ echo $user_rating_activity; } ?></div>
                            <div class="hint-mark">i<p class="hint"><?php echo lang ('user_activity_rating'); ?></p></div>
                        </li>
                         <?php endif; ?>
                         <li>
                            <div class="param-name"><?php echo lang ('articles_views'); ?></div>
                            <div class="param-value"></div>
                        </li>
                        <li>
                            <div class="param-name">&nbsp;&nbsp;<?php echo lang ('total_views'); ?>:</div>
                            <div class="param-value"><?php if(isset($num_visites_all)){ echo $num_visites_all; } ?></div>
                        </li>
                        <li>
                            <div class="param-name">&nbsp;&nbsp;<?php echo lang ('views_last_day'); ?>:</div>
                            <div class="param-value"><?php if(isset($num_visites_avg)){ echo $num_visites_avg; } ?></div>
                        </li>
                       
						<?php if(isset($author_group)): ?>
						 <li>
                            <div class="param-name"><?php echo lang ('status'); ?>:</div>
                            <div class="param-value"><?php if(isset($author_group)){ echo $author_group; } ?></div>
                        </li>
						<?php endif; ?>
						<?php if($num_payments_all > 0): ?>
						<li>
							<div class="param-name"><?php echo lang ('accrued_money'); ?></div>
							<div class="param-value"></div>
						</li>
						<li>
							<div class="param-name">&nbsp;&nbsp;<?php echo lang ('total_views'); ?></div>
							<div class="param-value"><?php echo sprintf ("%01.1f",$num_payments_all); ?></div>
						</li>
						<li>
							<div class="param-name">&nbsp;&nbsp;<?php echo lang ('over_past_week'); ?></div>
							<div class="param-value"><?php echo sprintf ("%01.1f",$num_payments_weekly); ?></div>
						</li>
						<?php endif; ?>
                    </ul>
                </li>
                <?php if($is_moderator == 0): ?>
                <li>
					<span>
						<a href="<?php echo site_url ('author/profile'); ?>"><?php echo lang ('author_profile'); ?></a>
					</span>
				</li>
				 <li>
					<span>
						<a href="<?php echo site_url ('author/finance'); ?>"><?php echo lang ('finance_section'); ?></a>
					</span>
				</li>
                <li>
					<span>
						<a href="<?php echo site_url ('author/refers'); ?>"><?php echo lang ('referral_section'); ?></a>
					</span>
				</li>
                <li>
					<span>
						<a href="<?php echo site_url ('list_articles'); ?>"><?php echo lang ('articles_list'); ?></a>
					</span>
				</li>
                <li>
					<span>
						<a href="<?php echo site_url ('list_comments'); ?>"><?php echo lang ('comments_list'); ?></a>
					</span>
				</li>
                <li>
					<span>
						<a href="#change_email" class="inline"><?php echo lang ('change_email'); ?></a>
					</span>
				</li>
				 <li>
					<span>
						<a href="#change_password" class="inline"><?php echo lang ('change_password'); ?></a>
					</span>
				</li>
                <li>
					<span>
						<?php if($have_purse == 1): ?>
							<a href="#change_purse" class="inline"><?php echo lang ('change_purse'); ?></a>
						<?php else:?>
							<a href="#change_purse" class="inline"><?php echo lang ('assign_purse'); ?></a>
						<?php endif; ?>
					</span>
				</li>
				<?php else:?>
					<li>
						<span>
							<a href="<?php echo site_url ('moderator/public_articles'); ?>"><?php echo lang ('published_articles'); ?></a>
						</span>
					</li>
					<li>
						<span>
							<a href="<?php echo site_url ('moderator/moderate_articles'); ?>"><?php echo lang ('articles_for_moderation'); ?>
							<span>
							<?php if($num_moderate_articles > 0): ?>
								(<?php if(isset($num_moderate_articles)){ echo $num_moderate_articles; } ?>)
							<?php endif; ?>
							</span>
							</a>
						</span>
					</li>
					<li>
						<span>
							<a href="<?php echo site_url ('moderator/rubricator'); ?>"><?php echo lang ('rubricator'); ?></a>
						</span>
					</li>
					<?php if(empty($is_editor)): ?>
						<li>
							<span>
								<a href="<?php echo site_url ('moderator/pleas'); ?>"><?php echo lang ('pleas'); ?> <span id="num_unconsidered_pleas">(<?php if(isset($num_unconsidered_pleas)){ echo $num_unconsidered_pleas; } ?>)</span></a>
							</span>
						</li>
						<li>
							<span>
								<a href="<?php echo site_url ('moderator/refers'); ?>"><?php echo lang ('referrals_list'); ?></a>
							</span>
						</li>
						<li>
							<span>
								<a href="<?php echo site_url ('moderator/refer_settings'); ?>"><?php echo lang ('referrals_settings'); ?></a>
							</span>
						</li>
						<li>
							<span>
								<a href="<?php echo site_url ('moderator/payouts/open'); ?>"><?php echo lang ('payouts_requests'); ?> <span id="num_opened_payouts">(<?php if(isset($num_unclosed_payouts)){ echo $num_unclosed_payouts; } ?>)</span></a>
							</span>
						</li>
						<li>
							<span>
								<a href="<?php echo site_url ('private/list_articles'); ?>"><?php echo lang ('private_section'); ?></a>
							</span>
						</li>
						<li>
							<span>
								<a href="<?php echo site_url ('moderator/giventopics'); ?>"><?php echo lang ('assigned_topics'); ?></a>
							</span>
						</li>
						<li>
							<span>
								<a href="<?php echo site_url ('moderator/adverts'); ?>"><?php echo lang ('advert_blocks'); ?></a>
							</span>
						</li>
					<?php endif; ?>
				<?php endif; ?>
                <li>
					<span>
						<a href="<?php echo site_url ('auth/logout'); ?>"><?php echo lang ('articler_logout'); ?></a>
					</span>
				</li>
            </ul>
        </div>

		
		<?php endif; ?>
    </div>
</div>

</div>
<div class="navigation-block">
    <div class="window-nav">
        <div class="title-window"><?php echo lang ('navigation'); ?></div>
        <div class="close-window"><?php echo lang ('close'); ?></div>
        <ul class="nav-pages">
            <li><a href="<?php echo site_url ('authors'); ?>"><?php echo lang ('authors'); ?></a></li>
            <li><a href="<?php echo site_url ('rules.html'); ?>"><?php echo lang ('rules'); ?></a></li>
            <li><a href="<?php echo site_url ('news'); ?>"><?php echo lang ('url_news'); ?></a></li>
            <li><a href="<?php echo site_url ('contact.html'); ?>"><?php echo lang ('contacts'); ?></a></li>
            <li><a href="<?php echo site_url ('search'); ?>"><?php echo lang ('site_search'); ?></a></li>
        </ul>
		<?php $counter = 0?>	
        <div class="block-title"><?php echo lang ('rubrics'); ?></div>
        <ul class="rubrics">
		 <li> <ul>
		<?php if(is_true_array($main_headings)){ foreach ($main_headings as $heading){ ?>	

              <li style="white-space: nowrap;"><span class="hidden-link" data-link="<?php echo site_url (''); ?><?php echo $heading['name']; ?>/"><?php echo $heading['name_russian']; ?></span></li>
				 <?php $counter++?>
				<?php if($counter == 5): ?>
					</ul></li><li><ul>
					<?php $counter = 0?>
				<?php endif; ?>
	
			<?php }} ?>
			</ul></li>
        </ul>
		
        <div class="block-title"><?php echo lang ('special_rubrics'); ?></div>
		
		<ul class="rubrics">
		 <li> <ul>
		 <?php $counter = 0?>	
		<?php if(is_true_array($add_headings)){ foreach ($add_headings as $heading){ ?>	

             <li style="white-space: nowrap;"><span class="hidden-link" data-link="<?php echo site_url (''); ?><?php echo $heading['name']; ?>/"><?php echo $heading['name_russian']; ?></span></li>
				 <?php $counter++?>
				<?php if($counter == 4): ?>
					</ul></li><li><ul>
					<?php $counter = 0?>
				<?php endif; ?>
	
			<?php }} ?>
			</ul></li>
        </ul>
    </div>
</div>


<div class="footer">
    <div class="content-block">
		<?php $footer_message = lang('footer_message');
			$footer_message = str_replace('%date%',date('Y'),$footer_message);
		?>
       <?php if(isset($footer_message)){ echo $footer_message; } ?>
    </div>
</div>
<script type="text/javascript" src="/js/arrow34.js"></script>
<script>$('.hidden-link').replaceWith(function(){return'<a href="'+$(this).data('link')+'">'+$(this).text()+'</a>';})</script>


</body></html><?php $mabilis_ttl=1542122699; $mabilis_last_modified=1535366445; //D:\server\www\projects\articler.img\/templates/ruautor/main.tpl ?>