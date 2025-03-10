<?php if(isset($one_comments)): ?>
<div class="comments-info">
            <div class="param-name"><?php echo lang ('number_of_comments'); ?>:</div>
            <div class="param-value" id="total_comments">1</div>
        </div>
        <ul class="comments">
<?php endif; ?>
<li class="comment" id="comment_<?php if(isset($id)){ echo $id; } ?>">
               <div class="user-avatar">
					<?php if($avatar): ?>
						<?php if(isset($avatar)){ echo $avatar; } ?>
					<?php else:?>
						<div class="figure-2"></div>
                    	<div class="figure-1"></div>
					<?php endif; ?>
                </div>
                <div class="user-login">
					<?php if(isset($author)){ echo $author; } ?>
				</div>
				<?php if($reply_comment): ?>
					<div style="font-size:9px;font-weight:bold;"><?php echo lang ('reply_to_comment'); ?>: <?php if(isset($reply_comment)){ echo $reply_comment; } ?></div>
				<?php endif; ?>
				
					<div class="actions">
						<a href="<?php echo site_url ('comment/delete'); ?>/<?php if(isset($id)){ echo $id; } ?>" title="<?php echo lang ('remove_comment'); ?>" onclick="delete_comment(this.href,'<?php if(isset($id)){ echo $id; } ?>');return false;" id="delete_<?php if(isset($id)){ echo $id; } ?>" style="text-decoration: none;">
							<img src="/<?php if(isset($THEME)){ echo $THEME; } ?>/images/icon_delete.png" width="12" height="12"/>
						</a>
 &nbsp;
						<a href="<?php echo site_url ('comment/edit'); ?>/<?php if(isset($id)){ echo $id; } ?>" title="<?php echo lang ('edit_comment'); ?>" onclick="modal_edit_comment(this,'<?php if(isset($id)){ echo $id; } ?>');return false;" id="edit_<?php if(isset($id)){ echo $id; } ?>">
							<img src="/<?php if(isset($THEME)){ echo $THEME; } ?>/images/icon_edit.png" width="12" height="12"/>
						</a>
				 </div>
				<br>
                <div class="comment-date"><span><?php if(isset($date)){ echo $date; } ?></span></div>
				
                <div class="comment-text">
                   <span><?php if(isset($comment)){ echo $comment; } ?></span>
                </div>
            </li>
<?php if(isset($one_comments)): ?>
	</ul>
<?php endif; ?>
<?php $mabilis_ttl=1535557293; $mabilis_last_modified=1530431257; //D:\server\www\projects\articler.img\/templates/ruautor//add_comment.tpl ?>