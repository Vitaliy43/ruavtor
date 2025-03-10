
<script type="text/javascript">
	$(document).ready(function(){
		 /**************************/
            var changeFont = $('.font-resizer li');
            $(changeFont).click(function() {
                if (!$(this).hasClass('active')){
                    $(changeFont).removeClass('active');
                    $(this).addClass('active');
                    var fontSize = $(this).css('font-size');
                    $('.content-text').css('font-size', fontSize);
                    $('.comment-text').css('font-size', fontSize);
                }
            });
	});
	
	$(function() {
	$('#change_url').hide();
	$("#link_change_url").click(function() {
		$('#change_url').slideToggle('normal');
		return false;
});
});
	
</script>


 <!--h1 title="<?php if(isset($header)){ echo $header; } ?>"><?php echo splitterWord ($header,80); ?></h1-->
 <h1><?php if(isset($header)){ echo $header; } ?></h1>

        <ul class="content-info">
		<input type="hidden" name="article_id" value="<?php if(isset($id)){ echo $id; } ?>"/>
		<?php if(isset($rating_for_homefeed)): ?>
			<input type="hidden" name="rating_for_homefeed" id="rating_for_homefeed" value="<?php if(isset($rating_for_homefeed)){ echo $rating_for_homefeed; } ?>"/>
		<?php endif; ?>
	<?php if(isset($header) && $is_moderator == 1 && isset($is_select_headings)): ?>
		<li>
			<div class="param-name"><?php echo lang ('heading'); ?>:</div>
                <div class="param-value">
				
				<?php if(isset( $errors['header'] )): ?>
					&nbsp;<?php echo $errors['header']; ?>
				<?php endif; ?>
				<?php if($activity != 2): ?>
					<input type="text" name="header" id="publisher_header" onchange="translit();" value="<?php if(isset($header)){ echo $header; } ?>"/>
				<?php else:?>
					<input type="text" name="header" id="publisher_header" value="<?php if(isset($header)){ echo $header; } ?>"/>
				<?php endif; ?>
				</div>
		</li>
		
	<?php endif; ?>

            <li>
                <div class="param-name"><?php echo lang ('rubric'); ?>:</div>
                <div class="param-value">
				<?php if(isset($is_select_headings)): ?>
					<?php if(isset($headings)){ echo $headings; } ?>
				<?php else:?>
					<a href="<?php echo site_url (''); ?><?php if(isset($headings_url)){ echo $headings_url; } ?>" target="_blank"><?php if(isset($heading_name_russian)){ echo $heading_name_russian; } ?></a>
				<?php endif; ?>
				</div>
            </li>
            <li>
                <div class="param-name"><?php echo lang ('author'); ?>:</div>
                <div class="param-value">
					<?php if(isset($author_name)): ?>
						<a href="<?php echo site_url ('avtory'); ?>/<?php if(isset($username)){ echo $username; } ?>" target="_blank"><?php if(isset($author_name)){ echo $author_name; } ?> <?php if(isset($author_family)){ echo $author_family; } ?></a>

				<?php else:?>
					<a href="<?php echo site_url ('avtory'); ?>/<?php if(isset($username)){ echo $username; } ?>" target="_blank"><?php if(isset($username)){ echo $username; } ?></a>
				<?php endif; ?>
				</div>
            </li>
            <li>
                <div class="param-name" data-test="<?php if(isset($main_language)){ echo $main_language; } ?>"><?php echo lang ('data'); ?>:</div>
                <div class="param-value">
				<?php if($activity == 2): ?>
					<?php echo time_convert_date_to_text ($data_published,$main_language); ?>
				<?php else:?>
					<?php echo time_convert_date_to_text ($data_saved,$main_language); ?>
				<?php endif; ?>
				</div>
            </li>
		
            <li>
                <div class="param-name"><?php echo lang ('rating'); ?>:</div>
                <div class="param-value"  id="article_rating">
				<?php if(isset($rating)): ?>
					<span class="num_rating"><?php if(isset($rating)){ echo $rating; } ?></span>

				<?php else:?>
					<span class="num_rating">0</span>
				<?php endif; ?>
				
				<?php if($author == 'guest'): ?>
				 		<a class="arrow-image" href="javascript:void(0)" onclick="modal_change_rating(this,'<?php if(isset($author)){ echo $author; } ?>');return false;"></a>
				 		<a class="arrow-image-red" href="javascript:void(0)" onclick="modal_change_rating(this,'<?php if(isset($author)){ echo $author; } ?>');return false;"></a>
				 	<?php else:?>
				 		<a class="arrow-image" href="<?php echo site_url ('comment/add_plus'); ?>/<?php if(isset($id)){ echo $id; } ?>" onclick="add_plus_new(this,'<?php echo site_url (''); ?>');return false;"></a>
				 		<a class="arrow-image-red" href="<?php echo site_url ('comment/add_minus'); ?>/<?php if(isset($id)){ echo $id; } ?>" onclick="add_minus(this,'<?php echo site_url (''); ?>');return false;"></a>
				 	<?php endif; ?>
            </li>
			
			<?php if(isset($num_visites_all) && $num_visites_all > 0): ?>
			<li>
				<div class="param-name"><?php echo lang ('views'); ?>:</div>
				<div class="param-value">
					<div id="all_num_visites"><?php echo lang ('total_views'); ?> - <b><?php if(isset($num_visites_all)){ echo $num_visites_all; } ?> <?php if(isset($num_visites_all_correct)){ echo $num_visites_all_correct; } ?></b></div>
					<div id="average_num_visites"><?php echo lang ('views_last_day'); ?> - <b><?php if(isset($num_visites_avg)){ echo $num_visites_avg; } ?> <?php if(isset($num_visites_avg_correct)){ echo $num_visites_avg_correct; } ?></b></div>
				</div>
			</li>
			<?php endif; ?>
			
        </ul>
        <div class="font-resizer">
            <div class="text-1"><?php echo lang ('font_size'); ?>:</div>
            <ul style="font-size: 15px;">
                <li class="active small">А</li>
                <li class="medium" style="font-size: 115%;">А</li>
                <li class="large" style="font-size: 130%;">А</li>
            </ul>
        </div>

<noindex>
<?php if(isset($advert_over_content)): ?>
	<?php if(isset($advert_over_content)){ echo $advert_over_content; } ?>
<?php else:?>
<script type="text/javascript"
 src="/templates/ruautor/js/ta-k1.js"></script>
<script type="text/javascript"
src="https://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<?php endif; ?>
</noindex>

<br />
<p style="text-align: center;"><a href="#cat"> П о й м а й &nbsp;&nbsp; к о т а ! </a></p>
<br />
		
        <div class="content-block">
		<?php if(isset($picture)): ?>
            <div class="pictures-block">
                <img class="content-photo" src="<?php if(isset($picture)){ echo $picture; } ?>" alt="Фото статьи">
               <div class="pictures-block" style="margin: -0px;">
<noindex>
<?php if(isset($advert_under_image)): ?>
				<?php if(isset($advert_under_image)){ echo $advert_under_image; } ?>
<?php else:?>
<script type="text/javascript" src="/templates/ruautor/js/ra-SHS.js"></script>
<script type="text/javascript"
src="https://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>

<?php endif; ?>
</noindex>
</div>
            </div>
			
			<?php endif; ?>


            <div class="content-text">
            	<?php if(isset($picture)): ?>
            		<?php echo cutImage ($picture,$content); ?>
            	<?php else:?>
               		<?php if(isset($content)){ echo $content; } ?>
               	<?php endif; ?>
            </div>
			

<noindex>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-format="autorelaxed"
     data-ad-client="ca-pub-8689715513247014"
     data-ad-slot="2918805187"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>

</noindex>			
			
			
			<?php if(isset($is_moderator) && $is_moderator == 1 && empty($is_edited)): ?>
			<div style="margin-bottom:10px; margin-left: 10px; margin-top: 10px;"><a href="<?php echo site_url ('moderator/edit'); ?>/<?php if(isset($id)){ echo $id; } ?>" target="_blank"><?php echo lang ('edit_article'); ?></a></div>
		<?php endif; ?>
            <div class="clearfix"></div>
        </div>

      <?php $mabilis_ttl=1540917629; $mabilis_last_modified=1533731242; //D:\server\www\projects\articler.img\/templates//main/article.tpl ?>