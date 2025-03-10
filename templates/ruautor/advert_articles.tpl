{literal}
<style type="text/css">
	.publication-info li:nth-child(1) {float: left;width: 200px;text-align: left;padding-left: 20px;border-left: 1px solid #ebebeb;}
	.publication-info li:nth-child(2) {float: left;width: 270px;}
	.publication-info li:nth-child(3) {float: left;width: 400px; text-align: right; padding-right: 20px;}
</style>
{/literal}
<div class="advert_block">
	<h2>Рекламный блок - {$advert.name}</h2>
	<div>Расположение блока - {$advert.type_russian}</div>
	<div>
		<label>Код блока</label>
		<div>{$advert.code}</div>
	</div>
</div>
<div class="selects_block">
	
</div>

     <ul class="publications-block">
	 {if count($articles) > 0}
	 {foreach $articles as $article}
	  <li class="publication">
                <div class="publication-title">
                    <a href="{$article.url}" class="publication-name" title="{$article.header}">{splitterWord($article.header,75)}</a>
                    <div class="publication-date">{time_convert_date_to_text($article.data_published)}</div>
                </div>
                <div class="publication-body">
                   <span>{$article.annotation}
				   </span>...
                </div>
                <ul class="publication-info">
                    <li>
                        <div class="param-name">Комментариев:</div>
                        <div class="param-value"><a href="#">{$article.comments}</a>
                      		{if empty($is_moderator)}
                        		<div class="comment-image"></div>
                        	{/if}  
                        </div>
                    </li>
					
                    <li>
                        <div class="param-name">Рейтинг:</div>
                        <div class="param-value">
						{if isset($is_moderator) and $is_moderator}
							<input type="text" id="rating_{$article.id}" value="{$article.rating}" class="input_text" style="width:34px;"/>
							<input type="hidden" id="old_rating_{$article.id}" value="{$article.rating}">
							<input type="button" onclick="change_rating_article('{site_url('moderator/change_article_rating')}/{$article.id}','{site_url('')}',{$article.id});return false;" value="Изменить" id="button_rating_{$article.id}"/>
			 			{else:}
				 			<span class="num_rating">{$article.rating}</span>
				 			{if $author == 'guest'}
				 				<a class="arrow-image" href="javascript:void(0)" onclick="modal_change_rating(this,'{$author}');return false;"></a>
				 				<a class="arrow-image-red" href="javascript:void(0)" onclick="modal_change_rating(this,'{$author}');return false;"></a>
				 			{else:}
				 				<a class="arrow-image" href="{site_url('comment/add_plus')}/{$article.id}" onclick="add_plus_new(this,'{site_url('')}');return false;"></a>
				 				<a class="arrow-image-red" href="{site_url('comment/add_minus')}/{$article.id}" onclick="add_minus(this,'{site_url('')}');return false;"></a>
				 			{/if}
			 			{/if}	
                    </li>
                    <li>
                        <div class="param-name">Автор:</div>
                       		<div class="param-value"><a href="{site_url('avtory')}/{$article.username}" target="_blank">{$article.name} {$article.family}</a></div>
                    </li>
                </ul>
            </li>
	 	{/foreach}
		<div class="pagination" align="left">
			{$paginator}
		</div>
		{else:}
			<div>Раздел пуст</div>
		{/if}
	</ul>
