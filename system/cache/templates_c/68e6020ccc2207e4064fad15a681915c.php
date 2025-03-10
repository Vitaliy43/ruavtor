<div class="sub-title"><?php echo lang ('recent_replies'); ?></div>
        <ul class="reviews">
		<?php if(is_true_array($comments)){ foreach ($comments as $comment){ ?>
			<?php if(strlen( $comment['comment'] ) >= $length_last_comment): ?>
				<?php $comment_text = mb_substr( $comment['comment'] ,0,$length_last_comment).'..' ?>
			<?php else:?>
				<?php $comment_text =  $comment['comment']  ?>
			<?php endif; ?>
		<li>
            <div class="review-name"><?php echo $comment['name']; ?> <?php echo $comment['family']; ?></div>
            <div class="review-text">
               <a href="<?php echo $comment['article_url']; ?>#comment_<?php echo $comment['id']; ?>"><?php if(isset($comment_text)){ echo $comment_text; } ?></a>
            </div>
        </li>
		<?php }} ?>
        </ul>
<?php $mabilis_ttl=1540977846; $mabilis_last_modified=1530514756; //D:\server\www\projects\articler.img\/templates/main//widgets/last_comments.tpl ?>