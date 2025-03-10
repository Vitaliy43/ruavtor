
<div id="block_news">

{foreach $articles as $article}
	
	<div class="small_news">
		
			<a href="{$article.url}">
				<h2>
					{$article.header}
				</h2>	
			</a>
			<p style="text-align: justify;"><em>{time_convert_date_to_text($article.data_published)}</em></p>
			<p style="text-align: right;margin-top:-15px;">Рейтинг:
			 {if isset($is_moderator) and $is_moderator}
				<input type="text" id="rating_{$article.id}" value="{$article.rating}" class="input_text" style="width:34px;"/>
				<input type="hidden" id="old_rating_{$article.id}" value="{$article.rating}">
				<input type="button" onclick="change_rating_article('{site_url('moderator/change_article_rating')}/{$article.id}','{site_url('')}',{$article.id});return false;" value="Изменить" id="button_rating_{$article.id}"/>
			 {else:}
				 <strong>{$article.rating}</strong>
			 {/if}	
			 </p>
			 		<p style="text-align: left;">{$article.annotation}</p>
			<p style="text-align: right;">Автор: <a href="{site_url('avtory')}/{$article.username}" style="color:black;text-decoration:underline;" target="_blank"><strong>{$article.name} {$article.family}</strong></a></p>
			<p>{$page.prev_text}</p>
			<p>
				<a href="{$article.url}">
					<b>Читать полностью</b>
				</a>
			</p>
		
	</div>
	<input type="hidden" id="article_{$article.id}"/>
{/foreach}
</div>
<div class="pagination" align="center">
    {$paginator}
</div>