{literal}
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

{/literal}
{if count($refers) > 0}
<h2>Список рефералов</h2>
<ul id="list_refers">
{foreach $refers as $refer}
	<li>
		<span class="folder" onclick="show_branch({$refer.refer_id},this);" >
			{$refer.username}
		</span>	
		<ul class="children"></ul>
	</li>	
{/foreach}
</ul>
<!--table width="100%" class="sandbox" cellpadding="4" cellspacing="2" id="list_refers">
<tr>
<th align="left">Партнер</th>
<th align="left">Реферал</th>
<th align="left">Дата окончания</th>
</tr>
{foreach $refers as $refer}
<tr id="refer_{$refer.id}">
<td >
<a href="{site_url('avtory')}/{$refer.refername}" target="_blank" style="text-decoration: underline;">{ucfirst($refer.fullname)}</a>
</td>
<td >
<a href="{site_url('avtory')}/{$refer.username}" target="_blank" style="text-decoration: underline;">{ucfirst($refer.username)}</a>
</td>
<td>
{date('Y-m-d H:i:s',$refer.data_end)}
</td>
</tr>
{/foreach}
</table-->
<div class="paginator">
{$paginator}
</div>
{else:}
<div>Раздел пуст</div>
{/if}