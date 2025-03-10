
<style type="text/css">
	.user_profile  .input_text{width:50px;margin-bottom:5px;}
	.publication-info li:nth-child(1) {float: left;width: 200px;text-align: left;padding-left: 20px;border-left: 1px solid #ebebeb;}
	.publication-info li:nth-child(2) {float: left;width: 350px;}
	.publication-info li:nth-child(3) {float: left;width: 298px; text-align: right; padding-right: 20px;}
</style>

<div class="personal_page">
<?php $dashboard = lang('dashboard');
	$dashboard = str_replace('%login%',$nickname,$dashboard);
?>
<div class="block-title"><?php if(isset($dashboard)){ echo $dashboard; } ?></div>
<div class="avatar" style="margin-top: 15px;"><img src="<?php if(isset($avatar_src)){ echo $avatar_src; } ?>" width="<?php echo $avatar_sizes['width']; ?>" height="<?php echo $avatar_sizes['height']; ?>"></div>
<div class="content-block" style="padding: 10px;">
<div class="param">
  <div class="param-name"><?php echo lang ('name'); ?>:</div>
      <div class="param-value" style="font-weight: normal"> <?php if(isset($author_name)){ echo $author_name; } ?></div>
  </div>
 <div class="param">
    <div class="param-name"><?php echo lang ('author_rating'); ?>:</div>
    <div class="param-value">
	 <?php if(isset($is_moderator) && empty($is_editor)): ?>
		<input type="text" name="author_rating" id="author_rating" value="<?php if(isset($author_rating)){ echo $author_rating; } ?>" class="input_text" style="width:54px;"/>
		<input type="hidden" id="old_author_rating" value="<?php if(isset($author_rating)){ echo $author_rating; } ?>"/>
		<input type="button" onclick="change_rating('author','<?php echo site_url ('moderator/change_rating'); ?>/<?php if(isset($nickname)){ echo $nickname; } ?>','<?php echo site_url (''); ?>');return false;" value="<?php echo lang ('change'); ?>" id="button_author_rating"/>
	<?php else:?>
		<b><?php if(isset($author_rating)){ echo $author_rating; } ?></b>
	<?php endif; ?>
	 </div>
 </div>
<div class="param">
   <div class="param-name"><?php echo lang ('rating_activity'); ?>:</div>
   <div class="param-value">
    <?php if(isset($is_moderator) && empty($is_editor)): ?>
		<input type="text" name="rating_activity" id="rating_activity" value="<?php if(isset($rating_activity)){ echo $rating_activity; } ?>" class="input_text"/>
		<input type="button" onclick="change_rating('activity','<?php echo site_url ('moderator/change_rating'); ?>/<?php if(isset($nickname)){ echo $nickname; } ?>','<?php echo site_url (''); ?>');return false;" value="<?php echo lang ('change'); ?>" id="button_rating_activity"/>
		<input type="hidden" id="old_rating_activity" value="<?php if(isset($rating_activity)){ echo $rating_activity; } ?>"/>

	<?php else:?>
		<b><?php if(isset($rating_activity)){ echo $rating_activity; } ?></b>
	<?php endif; ?>
	</div>
</div>
<?php if(isset($author_group)): ?>
<div class="param">
    <div class="param-name"><?php echo lang ('status'); ?>:</div>
    <div class="param-value"><b><?php if(isset($author_group)){ echo $author_group; } ?></b></div>
</div>
<?php endif; ?>

<?php if($num_visites_all > 0): ?>
<div class="param">
  <div class="param-name"><?php echo lang ('articles_views'); ?>:</div><br>
  <div class="param-value" style="margin-left: 25px; margin-top: -50px;"><?php echo lang ('total_views'); ?> - <b><?php if(isset($num_visites_all)){ echo $num_visites_all; } ?> (<?php if(isset($num_visites_all_correct)){ echo $num_visites_all_correct; } ?>)</b></div><br>
  <div class="param-value" style="margin-left: 25px; margin-top: -70px;"><?php echo lang ('views_last_day'); ?> - <b><?php if(isset($num_visites_avg)){ echo $num_visites_avg; } ?> (<?php if(isset($num_visites_avg_correct)){ echo $num_visites_avg_correct; } ?>)</b></div>
</div>
<?php endif; ?>


<div class="param">
  <div class="param-name"><?php echo lang ('totally_publications'); ?>:</div>
  <div class="param-value"><b><?php if(isset($num_articles)){ echo $num_articles; } ?></b></div>
</div>

</div>

<div class="block-title" style="margin-top: 30px;"><?php echo lang ('publications_list'); ?>:</div>

<?php if(count($articles) > 0): ?>

<ul>

<?php if(is_true_array($articles)){ foreach ($articles as $article){ ?>
	
	<li class="publication">
		
		<div class="publication-title">
           <a href="<?php echo $article['url']; ?>" class="publication-name" target="_blank" style="text-decoration: underline; color:#455f7c;" title="<?php echo $article['header']; ?>"><?php echo splitterWord ( $article['header'] ,75); ?></a>
           <div class="publication-date"><?php echo time_convert_date_to_text ( $article['data_published'] ,$main_language); ?></div>
        </div>
			
		<div class="publication-body">
              <span><?php echo $article['annotation']; ?></span>...
         </div>
		  <ul class="publication-info">
               <li>
                  <div class="param-name"><?php echo lang ('number_of_comments'); ?>:</div>
                  <div class="param-value"><a href="#"><?php echo $article['comments']; ?></a><div class="comment-image"></div></div>
               </li>
                <li>
                   <div class="param-name"><?php echo lang ('rubric'); ?>:</div>
                   <div class="param-value"><a href="/<?php echo $article['heading_name']; ?>"><?php echo $article['heading_name_russian']; ?></a></div>
                </li>
                <li>
                   <div class="param-name"><?php echo lang ('rating'); ?>:</div>
                   <div class="param-value">
				  <?php if(isset($is_moderator)): ?>
					<input type="text" id="rating_<?php echo $article['id']; ?>" value="<?php echo $article['rating']; ?>" class="input_text" style="width:34px;"/>
					<input type="hidden" id="old_rating_<?php echo $article['id']; ?>" value="<?php echo $article['rating']; ?>">
					<input type="button" onclick="change_rating_article('<?php echo site_url ('moderator/change_article_rating'); ?>/<?php echo $article['id']; ?>','<?php echo site_url (''); ?>',<?php echo $article['id']; ?>);return false;" value="<?php echo lang ('change'); ?>" id="button_rating_<?php echo $article['id']; ?>"/>
			 	<?php else:?>
				 	<span class="num_rating"><?php echo $article['rating']; ?></span>
			 	<?php endif; ?>	
				   <?php if($author == 'guest'): ?>
				 		<a class="arrow-image" href="javascript:void(0)" onclick="modal_change_rating(this,'<?php if(isset($author)){ echo $author; } ?>');return false;"></a>
				 		<a class="arrow-image-red" href="javascript:void(0)" onclick="modal_change_rating(this,'<?php if(isset($author)){ echo $author; } ?>');return false;"></a>
				 	<?php else:?>
				 		<a class="arrow-image" href="<?php echo site_url ('comment/add_plus'); ?>/<?php echo $article['id']; ?>" onclick="add_plus_new(this,'<?php echo site_url (''); ?>');return false;"></a>
				 		<a class="arrow-image-red" href="<?php echo site_url ('comment/add_minus'); ?>/<?php echo $article['id']; ?>" onclick="add_minus(this,'<?php echo site_url (''); ?>');return false;"></a>
				 	<?php endif; ?>
                </li>
           </ul>
			 
	</li>
	<input type="hidden" id="article_<?php echo $article['id']; ?>"/>
<?php }} ?>
</ul>
<div class="pagination" align="center">
    <?php if(isset($paginator)){ echo $paginator; } ?>
</div>
<?php else:?>
	<div><?php echo lang ('no_publications'); ?></div>
<?php endif; ?>

<div class="clearfix"></div>

<div class="author_comments sub-title comments_author" style="margin-top: 30px;">
	<?php echo lang ('author_comments'); ?>: 
	<div class="value"><?php if(isset($num_comments_author)){ echo $num_comments_author; } ?></div>
</div>
<div class="sub-block"><?php echo lang ('recent_comments'); ?>:</div>
<div class="content-block" style="padding: 15px;">
	
<?php if(count($last_comments_author) > 0): ?>

   <ul class="comment-links" style="width: 100%;">
<?php if(is_true_array($last_comments_author)){ foreach ($last_comments_author as $comment){ ?>
<?php if(mb_strlen($comment['comment']) > 30):
	$comment_text = mb_substr($comment['comment'],0,30).'...';
else:
	$comment_text = $comment['comment'];
endif;

	$comment_text = mb_ucfirst($comment_text);

?>

   <li style=" display: inline-block;width: 100%;height: 25px;">
        <a href="<?php echo $comment['article_url']; ?>#comment_<?php echo $comment['id']; ?>" title="<?php echo $comment['comment']; ?>" target="_blank" style=" color: #455F7C; font-weight: 600;"><?php if(isset($comment_text)){ echo $comment_text; } ?></a>
            <div class="comment-time" style="display: inline-block; float: right;">
               <div class="date" style="display: inline-block;"><?php echo time_change_show_data ( $comment['data'] ); ?></div>
            </div>
   </li>
<?php }} ?>
	</ul>
		
<?php else:?>
     <div class="no-comments"><?php echo lang ('no_comments'); ?></div>
<?php endif; ?>
</div>

<div class="author_comments sub-title comments_author" style="margin-top: 30px;">
	<?php echo lang ('comments_author_publications'); ?>: 
	<div class="value"><?php if(isset($num_comments_for_author)){ echo $num_comments_for_author; } ?></div>
</div>
<div class="sub-block"><?php echo lang ('recent_comments_author_publications'); ?>:</div>
<div class="content-block" style="padding: 15px;">
<?php if(count($last_comments_for_author) > 0): ?>

   <ul class="comment-links" style="width: 100%;">
<?php if(is_true_array($last_comments_for_author)){ foreach ($last_comments_for_author as $comment){ ?>
<?php if(mb_strlen($comment['comment']) > 30):
	$comment_text = mb_substr($comment['comment'],0,30).'...';
else:
	$comment_text = $comment['comment'];
endif;

	$comment_text = mb_ucfirst($comment_text);

?>

   <li style=" display: inline-block;width: 100%;height: 25px;">
        <a href="<?php echo $comment['article_url']; ?>#comment_<?php echo $comment['id']; ?>" title="<?php echo $comment['comment']; ?>" target="_blank" style=" color: #455F7C; font-weight: 600;"><?php if(isset($comment_text)){ echo $comment_text; } ?></a>
            <div class="comment-time" style="display: inline-block; float: right;">
               <div class="date" style="display: inline-block;"><?php echo time_change_show_data ( $comment['data'] ); ?></div>
            </div>
   </li>
<?php }} ?>
	</ul>
		
<?php else:?>
     <div class="no-comments"><?php echo lang ('no_comments'); ?></div>
<?php endif; ?>
</div>

</div>




<?php $mabilis_ttl=1534311528; $mabilis_last_modified=1534056829; //D:\server\www\projects\articler.img\/templates//ruautor/profile.tpl ?>