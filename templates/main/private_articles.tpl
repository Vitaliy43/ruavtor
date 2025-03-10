
{literal}
<style type="text/css">
	.publication-info li:nth-child(1) {float: left;width: 200px;text-align: left;padding-left: 20px;border-left: 1px solid #ebebeb;}
	.publication-info li:nth-child(2) {float: left;width: 270px;}
	.publication-info li:nth-child(3) {float: left;width: 400px; text-align: right; padding-right: 20px;}
</style>
{/literal}
<div id="add_article" style="margin-bottom:15px;text-align:center;">
	<a href="{site_url('private/add')}" style="color:inherit;text-decoration:none;" target="_blank">
		<img width="16" height="16" src="/{$THEME}/images/add.png" style="margin-bottom:-3px;"/>
		<span style="text-decoration:underline;color:#166D66;">Добавить материал</span>
	</a>
</div>
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
                <ul class="publication-info">
                	<li>
                		<div class="param-name">Дата создания:</div>
                		<div class="param-value">{time_change_show_data($article.data_added)}</div>
                	</li>
                </ul>
                <div class="publication-body">
                   <span>{$article.annotation}</span>...
                </div>
                    
                <ul class="publication-info actions">
                    <li>
                        <div class="param-name">Действия:</div>
                        
							<a href="{site_url('private/update')}/{$article.id}" title="Редактировать статью" id="edit_{$article.id}" target="_blank" style="color: inherit;">
								<button class="to-edit">Редактировать</button>
							</a>
							<a href="{site_url('publish/delete')}/{$article.id}" title="Удалить статью" onclick="delete_private_article(this.href,'{$article.id}');return false;" id="delete_{$article.id}">
						    	<button class="to-delete">Удалить статью</button>
							</a>
						
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

