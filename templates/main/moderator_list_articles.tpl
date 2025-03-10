{if $is_added == 1 && $action == 'insert'}
<input type="hidden" name="article_id" id="article_id" value="{$article_id}"/>
<input type="hidden" name="hidden_article_header" id="hidden_article_header" value="{$article_header}"/>
{literal}
<script type="text/javascript">
	$(document).ready(function(){
	{/literal}
		$('#article_header').html('"'+'{$article_header}'+'"');
		var add_outer_link = $('#add_outer_link').html();
		{literal}
		$.colorbox({html: add_outer_link, open: true, title: 'Добавление внешней ссылки', width:"50%"});

	});
	{/literal}
</script>

{/if}
{if isset($is_moderator)}
	{$frm_search}
{/if}
<div id="list_articles">
{if isset($cur_page)}
<input type="hidden" id="cur_page" value="{$cur_page}"/>
{/if}
<div>{$message}</div>
{if count($articles) > 0}

<ul class="publications-block resources">
	{foreach $articles as $article}

            <li class="publication" id="row_{$article.id}">
                <div class="publication-title">
                    <a href="{$article.url}" class="publication-name" target="_blank">{$article.header}</a>
                    <div class="publication-date">{time_convert_date_to_text($article.data_published,$main_language)}</div>
                </div>
                <div class="publication-body">
                   <span>{$article.annotation}</span>...
                </div>
                <ul class="publication-info">
                    <li>
                        
						
{if empty($is_moderator)}
	<div class="param-name" id="status_{$article.id}">Статус:</div> 
	<div class="param-value">
{else:}
	<p style="text-align: left;">Рубрика: <a href="/{$article.heading}" target="_blank">{$article.heading_name}</a></p>
{/if}
	</div>
                    </li>
                </ul>
				
				 <ul class="publication-info">
                    <li>
                        <div class="param-name">Рейтинг:</div>
                        <div class="param-value">
					{if isset($is_moderator)}
						<input type="text" id="rating_{$article.id}" value="{$article.rating}" class="input_text" style="width:34px;"/>
						<input type="hidden" id="old_rating_{$article.id}" value="{$article.rating}">
						<input type="button" onclick="change_rating_article('{site_url('moderator/change_article_rating')}/{$article.id}','{site_url('')}',{$article.id});return false;" value="Изменить" id="button_rating_{$article.id}"/>
			 		{else:}
				 		{$article.rating}
			 		{/if}	
						</div>
                    </li>
                </ul>
                
				{if $article.payment || $article.num_visites}
				 	<ul class="publication-info">
                    	<li>
							<div class="param-name">Начисления:</div>
							<div class="param-value">
								{if $article.payment}
									<p>Выход из песочницы: {$article.payment} wmr</p>
								{/if}
								{if $article.num_visites}
									<p>За посещаемость: {$article.num_visites} wmr</p>
								{/if}
							</div>
						</li>
					</ul>
				{/if}
                <ul class="publication-info actions">
                    <li>
                        <div class="param-name">Действия:</div>
                        <div class="param-value">
						{if $article.activity != 2}
						<a href="{site_url('publish/update')}/{$article.id}" title="Х䠪�谮⠲� ��಼�" id="edit_{$article.id}" target="_blank" style="color: inherit;">
							<button class="to-edit">Редактировать</button>
						</a>
						{/if}
						<a href="{site_url('publish/delete')}/{$article.id}" title="Ӥ૨�� ��಼�" onclick="delete_article(this.href,'{$article.id}','{$article.activity}');return false;" id="delete_{$article.id}">
						    <button class="to-delete">Удалить статью</button>
						</a>
						</div>
                    </li>
                </ul>
			
            </li>
	{/foreach}

        </ul>
<div class="pagination">
{$paginator}
</div>
{else:}
<div>Раздел пуст</div>
{/if}
</div>
