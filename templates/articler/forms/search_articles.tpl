{literal}
	<script type="text/javascript">
	$(document).ready(function(){
		$("#data1").attachDatepicker();
		$("#data2").attachDatepicker();
	
	if(authors.length > 0)	{
	
	$("#search_authors").autocomplete(authors, {
		width: 320,
		max: 4,
		highlight: false,
		multiple: true,
		multipleSeparator: " ",
		scroll: true,
		scrollHeight: 300
	});
	
	}
	
	});
	
	</script>
{/literal}
<div id="container_form_search">
<form action="{$action}" method="post" name="frm_search_articles" id="frm_search_articles" style="margin-top:10px;width:100%;">
<input type="hidden" name="search_status" id="search_status" value="waiting">
  <table border="0" align="center" style="width: 100%;">
  
  	<tr>
		<td>
			Рубрики
		</td>
		<td>
			{$headings}
		</td>
		<td nowrap="">Дата публикации</td>
		<td nowrap="" align="left">
				<span>
					От <input id="data1" name="data1" type="text" value="{$data1}">
				</span>
				<span>
				&nbsp;До
					<input id="data2" name="data2" type="text" value="{$data2}">
				</span>
		</td>
	</tr>
	    <tr>
      <td>
        Рейтинг
      </td>
      <td>От
	  	<input type="text" maxlength="4" size="4" name="rating_from" value="{$rating_from}" id="rating_from"/>
	    &nbsp;До
		<input type="text" maxlength="4" size="4" name="rating_to" value="{$rating_to}" id="rating_to"/>
      </td>
      <td>
        Особая статья <input type="checkbox" name="is_special" {$is_special} id="is_special"/>
      </td>
      <td>
	 	Прикреплена внешняя ссылка <input type="checkbox" name="outer_link" {$outer_link} id="search_outer_link"/>
		       
      </td>
     </tr>

    <tr>
      <td valign="top">
        Поиск
      </td>
      <td valign="top">
	  Содержится в заголовке<br>
        <input type="text" name="header" size="30" maxlength="50" value="{$header}" id="header"/>
      </td>
      <td valign="top" colspan="2">
       	Содержится в аннотации или статье<br>
	       <textarea name="article" rows="1" style="width:90%;" id="search_article"></textarea>

	  </td>
    </tr>
	<tr>
		<td>
			Автор или логин
		</td>
		<td valign="top" align="left">
			<input type="text" id="search_authors" name="author" value="{$author}" size="31" maxlength="50"/>
		</td>
		<td colspan="2" align="right">
			<input type="submit" name="search" value="Искать" style="margin-right:5px;"/>
			<input type="button" name="reset" value="Сброс" style="margin-right:32px;" onclick="reset_filter();return false;"/>

		</td>
	</tr>
  </table>
  {form_csrf()}
</form>
</div>