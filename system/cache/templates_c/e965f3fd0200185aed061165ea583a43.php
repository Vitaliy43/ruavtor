<?php if(isset($is_moderator)): ?>
		<style type="text/css">
			.publication-info li:nth-child(1) {float: left;width: 150px;text-align: left;padding-left: 20px;border-left: 1px solid #ebebeb;}
			.publication-info li:nth-child(2) {float: left;width: 300px;}
			.publication-info li:nth-child(3) {float: left;width: 205px;}
			.publication-info li:nth-child(4) {float: left;width: 210px;}
		</style>
	
<?php endif; ?>
<div class="block-title"><?php echo lang ('publications'); ?></div>
     <ul class="publications-block">
	 <?php if(count($articles) > 0): ?>
	 <?php if(is_true_array($articles)){ foreach ($articles as $article){ ?>
	  <li class="publication">
                <div class="publication-title">
                    <a href="<?php echo $article['url']; ?>" class="publication-name" title="<?php echo $article['header']; ?>"><?php echo splitterWord ( $article['header'] ,75); ?></a>
                    <div class="publication-date"><?php echo time_convert_date_to_text ( $article['data_published'] ,$main_language); ?></div>
                </div>
                <div class="publication-body">
                   <span><?php echo $article['annotation']; ?>
				   </span>...
                </div>
                <ul class="publication-info">
                    <li>
                        <div class="param-name"><?php echo lang ('number_of_comments'); ?>:</div>
                        <div class="param-value"><a href="#"><?php echo $article['comments']; ?></a>
                        <?php if(empty($is_moderator)): ?>
                        	<div class="comment-image"></div>
                        <?php endif; ?>
                        </div>
                    </li>
                    <li>
                        <div class="param-name"><?php echo lang ('rubric'); ?>:</div>
                        <div class="param-value"><a href="/<?php echo $article['heading_name']; ?>" title="<?php echo $article['name_russian']; ?>"><?php echo splitterWord ( $article['name_russian'] ,25); ?></a></div>
                    </li>
                    <li>
                        <div class="param-name"><?php echo lang ('rating'); ?>:</div>
                        <div class="param-value">
						<?php if(isset($is_moderator)): ?>
							<input type="text" id="rating_<?php echo $article['id']; ?>" value="<?php echo $article['rating']; ?>" class="input_text" style="width:34px;"/>
							<input type="button" onclick="change_rating_article('<?php echo site_url ('moderator/change_article_rating'); ?>/<?php echo $article['id']; ?>','<?php echo site_url (''); ?>',<?php echo $article['id']; ?>);return false;" value="<?php echo lang ('change'); ?>" id="button_rating_<?php echo $article['id']; ?>"/>
							<input type="hidden" id="old_rating_<?php echo $article['id']; ?>" value="<?php echo $article['rating']; ?>">
			 			<?php else:?>
				 			<span class="num_rating"><?php echo $article['rating']; ?></span>
				 			<?php if($author == 'guest'): ?>
				 				<a class="arrow-image" href="javascript:void(0)" onclick="modal_change_rating(this,'<?php if(isset($author)){ echo $author; } ?>');return false;"></a>
				 				<a class="arrow-image-red" href="javascript:void(0)" onclick="modal_change_rating(this,'<?php if(isset($author)){ echo $author; } ?>');return false;"></a>
				 			<?php else:?>
				 				<a class="arrow-image" href="<?php echo site_url ('comment/add_plus'); ?>/<?php echo $article['id']; ?>" onclick="add_plus_new(this,'<?php echo site_url (''); ?>');return false;"></a>
				 				<a class="arrow-image-red" href="<?php echo site_url ('comment/add_minus'); ?>/<?php echo $article['id']; ?>" onclick="add_minus(this,'<?php echo site_url (''); ?>');return false;"></a>
				 			<?php endif; ?>
				 			</div>
			 			<?php endif; ?>	
                    </li>
                    <li>
                        <div class="param-name"><?php echo lang ('author'); ?>:</div>
                            <div class="param-value"><a href="<?php echo site_url ('avtory'); ?>/<?php echo $article['username']; ?>" target="_blank" title="<?php echo $article['name']; ?> <?php echo $article['family']; ?>"><?php echo splitterWord ($article['name'].' '.$article['family'],16); ?></a></div>

                </ul>
            </li>
	 	<?php }} ?>
		<div class="pagination" align="left">
			<?php if(isset($paginator)){ echo $paginator; } ?>
		</div>
		<?php else:?>
			<div><?php echo lang ('rubric_empty'); ?></div>
		<?php endif; ?>
	</ul>


<?php $mabilis_ttl=1544621782; $mabilis_last_modified=1530508926; //D:\server\www\projects\articler.img\/templates//ruautor/home_feed_articles.tpl ?>