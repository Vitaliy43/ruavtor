

<div class="top-navigation">
<ul><li>
    <form id="list_authors" style="width:100%;" action="{$SELF_URL}/list_authors/">Сортировать по:
       	{$select_sort}	
		<div style="padding:3px;">
	<div id="ajax_sort"></div>
</div>
    {form_csrf()}</form>
</li></ul>
</div>
<div class="form_overflow"></div>

<form action="{$SELF_URL}/actions/" id="users_f" method="post" style="width:100%">
<div id="sortable">
		  <table id="users_table1">
		  	
				<th axis="string" width="">{lang('amt_login')}</th>
				<th axis="string">{lang('amt_user_login')}</th>
				<th axis="string" width="">{lang('amt_author_name')}</th>
				<th axis="string">{lang('amt_author_rating')}</th>
				<th axis="string">{lang('amt_rating_activity')}</th>
				<th axis="string">{lang('amt_statistic')}</th>
				<th axis="string">{lang('amt_bmp')}</th>
				<th axis="string">{lang('amt_payments')}</th>
				<th axis="string">{lang('amt_payouts')}</th>
				<th axis="string">{lang('amt_num_articles')}</th>
				<th axis="date">{lang('amt_author_group')}</th>
				<th axis="none"></th>
			
			
			
			
		{foreach $authors as $author}
		<tr id="{$page.number}">
	
			<td class="rightAlign">
			<div align="left">
			<input type="checkbox" value="{$author.id}" name="checkbox_{$author.id}" /> {$author.id}
			</div>
			</td>
			<td onclick="edit_author({$author.id},'{ucfirst($author.username)}'); return false;" style="cursor:pointer;">{ucfirst($author.username)}</td>
			<td id="author_{$author.id}">{$author.family} {$author.name}</td>
			<td id="author_rating_{$author.id}">{$author.author_rating}</td>
			<td id="rating_activity_{$author.id}">{$author.rating_activity}</td>
			<td id="statistic_{$author.id}">{$author.sum_statistic}</td>
			<td id="score_{$author.id}">{$author.score}</td>
			<td id="payments_{$author.id}">{$author.sum_payments}</td>
			<td id="payouts_{$author.id}">{$author.sum_payouts}</td>
			<td id="num_articles_{$author.id}">{$author.num_articles}</td>
			<td id="author_group_{$author.id}">{$author.author_group}</td>
			<td  class="rightAlign">
			<img onclick="edit_author({$author.id},'{ucfirst($author.username)}');" style="cursor:pointer" src="{$THEME}/images/edit_page.png" width="16" height="16" title="{lang('amt_edit')}" />
			</td>
		</tr>
		{/foreach}
		
			
		  </table>
</div>

<p align="right">
<br/>
{lang('amt_with_selected')}:
<input type="submit" name="ban"  class="button" value="{lang('amt_to_ban')}" onclick="$('users_f').action='{$SELF_URL}/actions/1/{$cur_page}/'; ajax_form('users_f','users_ajax_table');" />
<input type="submit" name="unban"  class="button" value="{lang('amt_to_unban')}" onclick="$('users_f').action='{$SELF_URL}/actions/2/{$cur_page}/'; ajax_form('users_f','users_ajax_table');" />
<input type="submit" name="delete"  class="button" style="font-weight:bold;" value="{lang('amt_delete')}" onclick="$('users_f').action='{$SELF_URL}/actions/3/{$cur_page}/'; ajax_form('users_f','users_ajax_table');" />
</p>

{form_csrf()}</form>

<div align="center" style="padding:5px;">
{$paginator}
</div>

{literal}
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
{/literal}
