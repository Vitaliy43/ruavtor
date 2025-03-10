
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

<div id="container_form_search" style="background: #e5e9ff;;">
<h1>Поиск по сайту "Руавтор"</h1>
<form action="<?php if(isset($action)){ echo $action; } ?>" method="get" name="frm_search_articles" id="frm_search_articles" style="margin-top:10px;width:100%;">
<input type="hidden" name="search_status" id="search_status" value="waiting">
  <table border="0" align="center" style="width: 100%; " cellpadding="4" cellspacing="4">
  
  	<tr>
		<td>
			Рубрики
		</td>
		<td>
			<?php if(isset($headings)){ echo $headings; } ?>
		</td>
		<td nowrap="">Дата публикации</td>
		<td nowrap="" align="left">
				<span>
					От <input id="data1" name="data1" type="text" value="<?php if(isset($data1)){ echo $data1; } ?>" style="width: 75px;">
				</span>
				<span>
				&nbsp;До
					<input id="data2" name="data2" type="text" value="<?php if(isset($data2)){ echo $data2; } ?>" style="width: 75px;">
				</span>
		</td>
	</tr>
		<?php if($is_moderator): ?>
	    	<tr>
      <td>
        Рейтинг
      </td>
      <td>От
	  	<input type="text" maxlength="4" size="4" name="rating_from" value="<?php if(isset($rating_from)){ echo $rating_from; } ?>" id="rating_from"/>
	    &nbsp;До
		<input type="text" maxlength="4" size="4" name="rating_to" value="<?php if(isset($rating_to)){ echo $rating_to; } ?>" id="rating_to"/>
      </td>
      <td>
        <span >Особая статья <input type="checkbox" name="is_special" <?php if(isset($is_special)){ echo $is_special; } ?> id="is_special"/></span>
      </td>
      <td>
	 	<span>С внешней ссылкой <input type="checkbox" name="outer_link" <?php if(isset($outer_link)){ echo $outer_link; } ?> id="search_outer_link"/></span>
		       
      </td>
     </tr>
		<?php endif; ?>
    <tr>
      <td valign="top">
        Поиск по тексту
      </td>
      <td valign="top">
	  Содержится в заголовке<br>
        <input type="text" name="header" size="30" maxlength="50" value="<?php if(isset($header)){ echo $header; } ?>" id="header"/>
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
			<input type="text" id="search_authors" name="author" value="<?php if(isset($author)){ echo $author; } ?>" size="31" maxlength="50"/>
		</td>
		<td colspan="2" align="right">
		<div style="margin-top: 2px;">
			<input type="submit" name="search" value="Искать" style="margin-right:5px;padding: 3px;"/>
			<input type="button" name="reset" value="Сброс" style="margin-right:45px;padding: 3px; padding-left: 6px; padding-right: 6px;" onclick="reset_filter();return false;"/>
		</div>
			
			
		</td>
	</tr>
  </table>
  <?php echo form_csrf (); ?>
</form>
</div><?php $mabilis_ttl=1540976943; $mabilis_last_modified=1490270611; //D:\server\www\projects\articler.img\/templates//main/forms/search_articles.tpl ?>