
<style type="text/css">
	.paginator a {color:black;}
</style>

<?php if(count($authors) > 0): ?>
   <div class="block-title"><?php echo lang ('list_authors'); ?></div>
      <table class="top-authors">
	  	  <tr>
                <th>#</th>
                <th><?php echo lang ('nickname'); ?></th>
                <th><?php echo lang ('name'); ?></th>
                <th><?php echo lang ('number_articles'); ?></th>
                <th><?php echo lang ('rating'); ?></th>
                <th><?php echo lang ('status'); ?></th>
          </tr>
		  <?php $counter = 1?>
		<?php if(is_true_array($authors)){ foreach ($authors as $author){ ?>
	  	 <tr>
                <td><?php if(isset($counter)){ echo $counter; } ?></td>
				<?php $view_all_articles = lang('view_articles_author');
					$view_all_articles = str_replace('%author%',$author['username'],$view_all_articles);
				?>
                <td><a href="<?php echo site_url ('avtory'); ?>/<?php echo $author['username']; ?>" target="_blank" title="<?php if(isset($view_all_articles)){ echo $view_all_articles; } ?>"><?php echo $author['username']; ?></a></td>
                <td><?php echo $author['name']; ?> <?php echo $author['family']; ?></td>
                <td><?php echo $author['num_articles']; ?></td>
                <td><?php echo $author['author_rating']; ?></td>
                <td><?php echo $author['status']; ?></td>
         </tr>
		 <?php $counter ++?>
		 <?php }} ?>
	  
	  </table>
	  <div class="pagination" align="left" style="margin-top: 10px;">
		<?php if(isset($paginator)){ echo $paginator; } ?>
	</div>

<?php else:?>
<div><?php echo lang ('rubric_empty'); ?></div>
<?php endif; ?>
<?php $mabilis_ttl=1534313437; $mabilis_last_modified=1533916210; //D:\server\www\projects\articler.img\/templates//ruautor/list_authors.tpl ?>