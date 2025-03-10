
<?php echo $category['short_desc']; ?>
<?php if($no_pages): ?>
        <p><?php if(isset($no_pages)){ echo $no_pages; } ?></p>
<?php endif; ?>

<div id="block_news">

<?php if(is_true_array($pages)){ foreach ($pages as $page){ ?>
	<div class="small_news">
		
			
			<a href="<?php echo site_url ( $page['full_url'] ); ?>">
				<h2>
					<?php echo $page['title']; ?>
				</h2>	
			</a>
			<p style="text-align: justify;"><em><?php echo time_text_data ( $page['publish_date'] , false,$main_language); ?></em></p>
			<p><?php echo $page['prev_text']; ?></p>
			<p>
				<a href="<?php echo site_url ( $page['full_url'] ); ?>">
					<b><?php echo lang ('read_more'); ?></b>
				</a>
			</p>
		
	</div>
<?php }} ?>
</div>
<div class="pagination" align="center">
    <?php if(isset($pagination)){ echo $pagination; } ?>
</div>
<?php $mabilis_ttl=1542122699; $mabilis_last_modified=1542037844; //D:\server\www\projects\articler.img\/templates/ruautor/news.tpl ?>