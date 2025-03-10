
<style type="text/css">
.paginator a {color:black;}
</style>
<script type="text/javascript">

$(document).ready(function() {

	$("#list_refers").treeview({
		persist: "location",
		collapsed: true
	});
		
});

</script>


<?php if(count($refers) > 0): ?>
<h2>Список рефералов</h2>
<ul id="list_refers">
<?php if(is_true_array($refers)){ foreach ($refers as $refer){ ?>
	<li>
		<span class="folder" onclick="show_branch(<?php echo $refer['refer_id']; ?>,this);" >
			<?php echo $refer['username']; ?>
		</span>	
		<ul class="children"></ul>
	</li>	
<?php }} ?>
</ul>
<!--table width="100%" class="sandbox" cellpadding="4" cellspacing="2" id="list_refers">
<tr>
<th align="left">Партнер</th>
<th align="left">Реферал</th>
<th align="left">Дата окончания</th>
</tr>
<?php if(is_true_array($refers)){ foreach ($refers as $refer){ ?>
<tr id="refer_<?php echo $refer['id']; ?>">
<td >
<a href="<?php echo site_url ('avtory'); ?>/<?php echo $refer['refername']; ?>" target="_blank" style="text-decoration: underline;"><?php echo ucfirst ( $refer['fullname'] ); ?></a>
</td>
<td >
<a href="<?php echo site_url ('avtory'); ?>/<?php echo $refer['username']; ?>" target="_blank" style="text-decoration: underline;"><?php echo ucfirst ( $refer['username'] ); ?></a>
</td>
<td>
<?php echo date ('Y-m-d H:i:s', $refer['data_end'] ); ?>
</td>
</tr>
<?php }} ?>
</table-->
<div class="paginator">
<?php if(isset($paginator)){ echo $paginator; } ?>
</div>
<?php else:?>
<div>Раздел пуст</div>
<?php endif; ?><?php $mabilis_ttl=1537517290; $mabilis_last_modified=1450852421; //D:\server\www\projects\articler.img\/templates//ruautor/list_refers.tpl ?>