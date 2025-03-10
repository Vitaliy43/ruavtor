

<div class="top-navigation">
<ul><li>
    <form id="list_authors" style="width:100%;" action="<?php if(isset($SELF_URL)){ echo $SELF_URL; } ?>/list_authors/">Сортировать по:
       	<?php if(isset($select_sort)){ echo $select_sort; } ?>	
		<div style="padding:3px;">
	<div id="ajax_sort"></div>
</div>
    <?php echo form_csrf (); ?></form>
</li></ul>
</div>
<div class="form_overflow"></div>

<form action="<?php if(isset($SELF_URL)){ echo $SELF_URL; } ?>/actions/" id="users_f" method="post" style="width:100%">
<div id="sortable">
		  <table id="users_table1">
		  	
				<th axis="string" width=""><?php echo lang ('amt_login'); ?></th>
				<th axis="string"><?php echo lang ('amt_user_login'); ?></th>
				<th axis="string" width=""><?php echo lang ('amt_author_name'); ?></th>
				<th axis="string"><?php echo lang ('amt_author_rating'); ?></th>
				<th axis="string"><?php echo lang ('amt_rating_activity'); ?></th>
				<th axis="string"><?php echo lang ('amt_statistic'); ?></th>
				<th axis="string"><?php echo lang ('amt_bmp'); ?></th>
				<th axis="string"><?php echo lang ('amt_payments'); ?></th>
				<th axis="string"><?php echo lang ('amt_payouts'); ?></th>
				<th axis="string"><?php echo lang ('amt_num_articles'); ?></th>
				<th axis="date"><?php echo lang ('amt_author_group'); ?></th>
				<th axis="none"></th>
			
			
			
			
		<?php if(is_true_array($authors)){ foreach ($authors as $author){ ?>
		<tr id="<?php echo $page['number']; ?>">
	
			<td class="rightAlign">
			<div align="left">
			<input type="checkbox" value="<?php echo $author['id']; ?>" name="checkbox_<?php echo $author['id']; ?>" /> <?php echo $author['id']; ?>
			</div>
			</td>
			<td onclick="edit_author(<?php echo $author['id']; ?>,'<?php echo ucfirst ( $author['username'] ); ?>'); return false;" style="cursor:pointer;"><?php echo ucfirst ( $author['username'] ); ?></td>
			<td id="author_<?php echo $author['id']; ?>"><?php echo $author['family']; ?> <?php echo $author['name']; ?></td>
			<td id="author_rating_<?php echo $author['id']; ?>"><?php echo $author['author_rating']; ?></td>
			<td id="rating_activity_<?php echo $author['id']; ?>"><?php echo $author['rating_activity']; ?></td>
			<td id="statistic_<?php echo $author['id']; ?>"><?php echo $author['sum_statistic']; ?></td>
			<td id="score_<?php echo $author['id']; ?>"><?php echo $author['score']; ?></td>
			<td id="payments_<?php echo $author['id']; ?>"><?php echo $author['sum_payments']; ?></td>
			<td id="payouts_<?php echo $author['id']; ?>"><?php echo $author['sum_payouts']; ?></td>
			<td id="num_articles_<?php echo $author['id']; ?>"><?php echo $author['num_articles']; ?></td>
			<td id="author_group_<?php echo $author['id']; ?>"><?php echo $author['author_group']; ?></td>
			<td  class="rightAlign">
			<img onclick="edit_author(<?php echo $author['id']; ?>,'<?php echo ucfirst ( $author['username'] ); ?>');" style="cursor:pointer" src="<?php if(isset($THEME)){ echo $THEME; } ?>/images/edit_page.png" width="16" height="16" title="<?php echo lang ('amt_edit'); ?>" />
			</td>
		</tr>
		<?php }} ?>
		
			
		  </table>
</div>

<p align="right">
<br/>
<?php echo lang ('amt_with_selected'); ?>:
<input type="submit" name="ban"  class="button" value="<?php echo lang ('amt_to_ban'); ?>" onclick="$('users_f').action='<?php if(isset($SELF_URL)){ echo $SELF_URL; } ?>/actions/1/<?php if(isset($cur_page)){ echo $cur_page; } ?>/'; ajax_form('users_f','users_ajax_table');" />
<input type="submit" name="unban"  class="button" value="<?php echo lang ('amt_to_unban'); ?>" onclick="$('users_f').action='<?php if(isset($SELF_URL)){ echo $SELF_URL; } ?>/actions/2/<?php if(isset($cur_page)){ echo $cur_page; } ?>/'; ajax_form('users_f','users_ajax_table');" />
<input type="submit" name="delete"  class="button" style="font-weight:bold;" value="<?php echo lang ('amt_delete'); ?>" onclick="$('users_f').action='<?php if(isset($SELF_URL)){ echo $SELF_URL; } ?>/actions/3/<?php if(isset($cur_page)){ echo $cur_page; } ?>/'; ajax_form('users_f','users_ajax_table');" />
</p>

<?php echo form_csrf (); ?></form>

<div align="center" style="padding:5px;">
<?php if(isset($paginator)){ echo $paginator; } ?>
</div>
		<script type="text/javascript">
		
//			window.addEvent('domready', function(){
//				users_table1 = new sortableTable('users_table1', {overCls: 'over', onClick: function(){}});
//			});
			jq = jQuery.noConflict();
			function ajax_sort(){
				var point=jq('#sort option:selected').val();
				ajax_div('authors_ajax_table', base_url + 'admin/components/cp/articler/list_authors/'+point);

			}


		</script>

<?php $mabilis_ttl=1538476699; $mabilis_last_modified=1486452402; //D:\server\www\projects\articler.img\application\modules\articler/templates/list_authors.tpl ?>