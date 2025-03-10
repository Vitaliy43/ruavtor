
<style type="text/css">
.paginator a {color:black;}
</style>

<h2><?php echo lang ('comments_list'); ?></h2>
<?php if(count($comments) > 0): ?>
<table width="100%" class="sandbox">
<tr>
<th align="left"><?php echo lang ('article'); ?></th>
<th align="left"><?php echo lang ('rubric'); ?></th>
<th align="left"><?php echo lang ('comment_text'); ?></th>
<th align="left"><?php echo lang ('date'); ?></th>
</tr>
<?php if(is_true_array($comments)){ foreach ($comments as $comment){ ?>
<tr>
<td>
<a href="<?php echo $comment['article_url']; ?>#comment_<?php echo $comment['id']; ?>"><?php echo $comment['article_header']; ?></a>
</td>
<td>
<a href="<?php echo site_url (''); ?><?php echo $comment['heading_name']; ?>">
<?php echo $comment['heading_name_russian']; ?>
</a>
</td>
<td>
<?php echo $comment['comment']; ?>
</td>
<td>
<?php echo time_change_show_data ( $comment['data'] ); ?>
</td>
</tr>
<?php }} ?>
</table>
<div class="paginator">
<?php if(isset($paginator)){ echo $paginator; } ?>
</div>
<?php else:?>
<div><?php echo lang ('rubric_empty'); ?></div>
<?php endif; ?><?php $mabilis_ttl=1535556354; $mabilis_last_modified=1530441964; //D:\server\www\projects\articler.img\/templates//ruautor/list_comments.tpl ?>