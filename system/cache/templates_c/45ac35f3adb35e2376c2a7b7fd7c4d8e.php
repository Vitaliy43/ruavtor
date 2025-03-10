<?php if($is_added == 1 && $action == 'insert'): ?>
<input type="hidden" name="article_id" id="article_id" value="<?php if(isset($article_id)){ echo $article_id; } ?>"/>
<input type="hidden" name="hidden_article_header" id="hidden_article_header" value="<?php if(isset($article_header)){ echo $article_header; } ?>"/>
<script type="text/javascript">
	$(document).ready(function(){
	
		$('#article_header').html('"'+'<?php if(isset($article_header)){ echo $article_header; } ?>'+'"');
		var add_outer_link = $('#add_outer_link').html();
		$.colorbox({html: add_outer_link, open: true, title: 'Добавление внешней ссылки', width:"50%"});

	});
	
</script>

<?php endif; ?>

<?php if(isset($is_moderator) || isset($is_search)): ?>
	<?php if(isset($frm_search)){ echo $frm_search; } ?>
<?php endif; ?>
<div id="list_articles">

<?php if(isset($cur_page)): ?>
	<input type="hidden" id="cur_page" value="<?php if(isset($cur_page)){ echo $cur_page; } ?>"/>
<?php endif; ?>

<div><?php if(isset($message)){ echo $message; } ?></div>

<?php if(count($articles) > 0): ?>

<ul class="publications-block resources">
	<?php if(is_true_array($articles)){ foreach ($articles as $article){ ?>

            <li class="publication" id="row_<?php echo $article['id']; ?>">
            
                <div class="publication-title">
                	<?php if($article['activity']  == 2): ?>
                    	<a href="<?php echo $article['url']; ?>" class="publication-name" target="_blank" title="<?php echo $article['header']; ?>"><?php echo splitterWord ( $article['header'] ,75); ?></a>
                    	<div class="publication-date"><?php echo time_convert_date_to_text ( $article['data_published'] ,$main_language); ?></div>
                    <?php else:?>
                    	<?php if(isset($is_moderator)): ?>
							<a href="<?php echo site_url ('moderate/article'); ?>/<?php echo $article['id']; ?>" target="_blank" title="<?php echo $article['header']; ?>"><?php echo splitterWord ( $article['header'] ,75); ?></a>
						<?php else:?>
							<a href="<?php echo site_url ('raw/article'); ?>/<?php echo $article['id']; ?>" target="_blank" title="<?php echo $article['header']; ?>"><?php echo splitterWord ( $article['header'] ,75); ?></a>
                    	<?php endif; ?>
                    <?php endif; ?>
                </div>
                
                <div class="publication-body">
                   <span><?php echo $article['annotation']; ?></span>...
                </div>
                
                <ul class="publication-info">
                    <li>
						<?php if(isset($is_moderator)): ?>
							<div class="param-name" id="status_<?php echo $article['id']; ?>"><?php echo lang ('status'); ?>:</div> 
							<div class="param-value">
							<?php if($article['activity']  == 0): ?>
								<?php echo lang ('status_draft'); ?>
							<?php elseif ( $article['activity']  == 1 ): ?>
								<?php echo lang ('status_moderated'); ?>
							<?php elseif ( $article['activity']  == -1 ): ?>
								<span class="link_info" <?php if(isset( $article['rejection_reason'] )): ?>title="<?php echo $article['rejection_reason']; ?>"<?php endif; ?>>Отвергнуто</span>
							<?php else:?>
								<?php echo lang ('status_published'); ?>
							<?php endif; ?>
							</div>
						<?php else:?>
							<div class="param-name"><?php echo lang ('rubric'); ?>:</div>
							<div class="param-value">
							<a href="<?php echo site_url (); ?><?php echo $article['heading_name']; ?>" title="<?php echo $article['heading_name']; ?>" target="_blank"><?php echo splitterWord ( $article['heading_name_russian'] ,26); ?></a>
						</div>
						<?php endif; ?>
							
                    </li>
                </ul>
			<?php if(!$is_self): ?>
				<ul class="publication-info">
                    <li>
						<?php if($article['author_name']): ?>
							<div class="param-name"><?php echo lang ('author'); ?>:</div>
							<div class="param-value">
								<a href="<?php echo site_url ('avtory'); ?>/<?php echo $article['username']; ?>" title="<?php echo $article['author']; ?>"><?php echo splitterWord ( $article['author'] ,26); ?></a>
							</div>
						<?php else:?>
							<div class="param-name"><?php echo lang ('login'); ?>:</div>
							<div class="param-value">
								<a href="<?php echo site_url ('avtory'); ?>/<?php echo $article['username']; ?>" title="<?php echo $article['username']; ?>"><?php echo ucfirst ( $article['username'] ); ?></a>
							</div>
						<?php endif; ?>
					</li>
				</ul>
			<?php endif; ?>
				
				
				 <ul class="publication-info">
                    <li>
                        <div class="param-name"><?php echo lang ('rating'); ?>:</div>
                        <div class="param-value">
					<?php if(isset($is_moderator)): ?>
						<?php if($article['rating']): ?>
							<input type="text" id="rating_<?php echo $article['id']; ?>" value="<?php echo $article['rating']; ?>" class="input_text" style="width:34px;"/>
						<?php else:?>
							<input type="text" id="rating_<?php echo $article['id']; ?>" value="0" class="input_text" style="width:34px;"/>
						<?php endif; ?>
						<input type="hidden" id="old_rating_<?php echo $article['id']; ?>" value="<?php echo $article['rating']; ?>">
						<input type="button" onclick="change_rating_article('<?php echo site_url ('moderator/change_article_rating'); ?>/<?php echo $article['id']; ?>','<?php echo site_url (''); ?>',<?php echo $article['id']; ?>);return false;" value="<?php echo lang ('change'); ?>" id="button_rating_<?php echo $article['id']; ?>"/>
			 		<?php else:?>
				 		<?php echo $article['rating']; ?>
			 		<?php endif; ?>	
						</div>
                    </li>
                </ul>
                <?php if(isset($is_moderator)): ?>
                
                <?php if($article['link']): ?>
                	<ul class="publication-info">
                		<li>
                			<div class="param-name"><?php echo lang ('external_link'); ?>:</div>
                			<div class="param-value"><a href="<?php echo $article['link']; ?>" target="_blank"><?php echo $article['link']; ?></a></div>
                		</li>
                	</ul>
                <?php endif; ?>
                
                <?php if($article['is_special']): ?>
                	<ul class="publication-info">
                		<li>
                			<div class="param-name" style="text-decoration:underline;"><?php echo lang ('special_article'); ?>:</div>
                		</li>
                	</ul>
                <?php endif; ?>
                
				<?php if($article['payment']  ||  $article['num_visites']): ?>
				 	
                    		<?php if($article['payment']): ?>
                    			<ul class="publication-info">
                    				<li>
                    					<div class="param-name">
                    						<?php echo lang ('fees_leaving_sandbox'); ?>:
                    					</div>
                    					<div class="param-value">
                    						<?php echo $article['payment']; ?> <?php echo lang ('wmr'); ?>
                    					</div>
                    				</li>
                    			</ul>
                    		<?php endif; ?>
                    		<?php if($article['num_visites']): ?>
                    			<ul class="publication-info">
                    				<li>
                    					<div class="param-name">
                    						<?php echo lang ('fees_visits'); ?>:
                    					</div>
                    					<div class="param-value">
                    						<?php echo $article['num_visites']; ?> <?php echo lang ('wmr'); ?>
                    					</div>
                    				</li>
                    			</ul>
                    		<?php endif; ?>
								
				<?php endif; ?>
				<?php endif; ?>
				<?php if($is_moderator && !$is_editor): ?>
					<ul class="publication-info">
							<li>
								 <div class="param-name"><?php echo lang ('advert_blocks'); ?>:</div>
                        		<div class="param-value" id="p-<?php echo $article['id']; ?>">
									<?php echo $article['adverts']; ?>
								</div>
							</li>
						   
					</ul>
				<?php endif; ?>
				
				<?php if($article['activity']  != 2 || $is_moderator): ?>
                <ul class="publication-info actions">
                    <li>
                        <div class="param-name"><?php echo lang ('actions'); ?>:</div>
                        
						<?php if($article['activity']  != 2 && !$is_moderator): ?>
							<a href="<?php echo site_url ('publish/update'); ?>/<?php echo $article['id']; ?>" title="<?php echo lang ('edit_article'); ?>" id="edit_<?php echo $article['id']; ?>" target="_blank" style="color: inherit;">
								<button class="to-edit"><?php echo lang ('edit'); ?></button>
							</a>
							<a href="<?php echo site_url ('publish/delete'); ?>/<?php echo $article['id']; ?>" title="<?php echo lang ('remove_article'); ?>" onclick="delete_article(this.href,'<?php echo $article['id']; ?>','<?php echo $article['activity']; ?>');return false;" id="delete_<?php echo $article['id']; ?>">
						    <button class="to-delete"><?php echo lang ('remove_article'); ?></button>
							</a>
							
						<?php elseif ($is_moderator): ?>
							<a href="<?php echo site_url ('moderator/edit'); ?>/<?php echo $article['id']; ?>" title="<?php echo lang ('edit_article'); ?>" id="edit_<?php echo $article['id']; ?>" target="_blank">
								<button class="to-edit"><?php echo lang ('edit'); ?></button>
							</a>
						<?php endif; ?>
						
						<?php if($article['activity']  == 0 ||  $article['activity']  == -1): ?>
							<a href="<?php echo site_url ('publish/add'); ?>/<?php echo $article['id']; ?>" title="Отправить статью на модерацию" onclick="send_moderate(this.href,'<?php echo $article['id']; ?>','<?php echo $article['activity']; ?>');return false;" id="send_<?php echo $article['id']; ?>">
                        		<button class="to-check"><?php echo lang ('to_moderation'); ?></button>
							</a>
						<?php endif; ?>
						
                    </li>
                </ul>
				<?php endif; ?>
            </li>
	<?php }} ?>

</ul>
<div class="pagination">
	<?php if(isset($paginator)){ echo $paginator; } ?>
</div>
<?php else:?>
<div>
<?php if(isset($is_search) && isset($_REQUEST['search'])): ?>
	<?php echo lang ('nothing_found'); ?>
<?php elseif (isset($is_search)): ?>

<?php else:?>
	<?php echo lang ('nothing_found'); ?>
<?php endif; ?>
</div>
<?php endif; ?>
</div>
<?php $mabilis_ttl=1535560429; $mabilis_last_modified=1535473501; //D:\server\www\projects\articler.img\/templates//ruautor/list_articles.tpl ?>