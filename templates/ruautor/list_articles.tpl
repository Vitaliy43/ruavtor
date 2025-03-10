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

{if isset($is_moderator) || isset($is_search)}
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
                	{if $article.activity == 2}
                    	<a href="{$article.url}" class="publication-name" target="_blank" title="{$article.header}">{splitterWord($article.header,75)}</a>
                    	<div class="publication-date">{time_convert_date_to_text($article.data_published,$main_language)}</div>
                    {else:}
                    	{if  isset($is_moderator) }
							<a href="{site_url('moderate/article')}/{$article.id}" target="_blank" title="{$article.header}">{splitterWord($article.header,75)}</a>
						{else:}
							<a href="{site_url('raw/article')}/{$article.id}" target="_blank" title="{$article.header}">{splitterWord($article.header,75)}</a>
                    	{/if}
                    {/if}
                </div>
                
                <div class="publication-body">
                   <span>{$article.annotation}</span>...
                </div>
                
                <ul class="publication-info">
                    <li>
						{if isset($is_moderator)}
							<div class="param-name" id="status_{$article.id}">{lang('status')}:</div> 
							<div class="param-value">
							{if $article.activity == 0}
								{lang('status_draft')}
							{elseif $article.activity == 1}
								{lang('status_moderated')}
							{elseif $article.activity == -1}
								<span class="link_info" {if isset($article.rejection_reason)}title="{$article.rejection_reason}"{/if}>Отвергнуто</span>
							{else:}
								{lang('status_published')}
							{/if}
							</div>
						{else:}
							<div class="param-name">{lang('rubric')}:</div>
							<div class="param-value">
							<a href="{site_url()}{$article.heading_name}" title="{$article.heading_name}" target="_blank">{splitterWord($article.heading_name_russian,26)}</a>
						</div>
						{/if}
							
                    </li>
                </ul>
			{if !$is_self}
				<ul class="publication-info">
                    <li>
						{if $article.author_name}
							<div class="param-name">{lang('author')}:</div>
							<div class="param-value">
								<a href="{site_url('avtory')}/{$article.username}" title="{$article.author}">{splitterWord($article.author,26)}</a>
							</div>
						{else:}
							<div class="param-name">{lang('login')}:</div>
							<div class="param-value">
								<a href="{site_url('avtory')}/{$article.username}" title="{$article.username}">{ucfirst($article.username)}</a>
							</div>
						{/if}
					</li>
				</ul>
			{/if}
				
				
				 <ul class="publication-info">
                    <li>
                        <div class="param-name">{lang('rating')}:</div>
                        <div class="param-value">
					{if isset($is_moderator)}
						{if $article.rating}
							<input type="text" id="rating_{$article.id}" value="{$article.rating}" class="input_text" style="width:34px;"/>
						{else:}
							<input type="text" id="rating_{$article.id}" value="0" class="input_text" style="width:34px;"/>
						{/if}
						<input type="hidden" id="old_rating_{$article.id}" value="{$article.rating}">
						<input type="button" onclick="change_rating_article('{site_url('moderator/change_article_rating')}/{$article.id}','{site_url('')}',{$article.id});return false;" value="{lang('change')}" id="button_rating_{$article.id}"/>
			 		{else:}
				 		{$article.rating}
			 		{/if}	
						</div>
                    </li>
                </ul>
                {if isset($is_moderator)}
                
                {if $article.link}
                	<ul class="publication-info">
                		<li>
                			<div class="param-name">{lang('external_link')}:</div>
                			<div class="param-value"><a href="{$article.link}" target="_blank">{$article.link}</a></div>
                		</li>
                	</ul>
                {/if}
                
                {if $article.is_special}
                	<ul class="publication-info">
                		<li>
                			<div class="param-name" style="text-decoration:underline;">{lang('special_article')}:</div>
                		</li>
                	</ul>
                {/if}
                
				{if $article.payment || $article.num_visites}
				 	
                    		{if $article.payment}
                    			<ul class="publication-info">
                    				<li>
                    					<div class="param-name">
                    						{lang('fees_leaving_sandbox')}:
                    					</div>
                    					<div class="param-value">
                    						{$article.payment} {lang('wmr')}
                    					</div>
                    				</li>
                    			</ul>
                    		{/if}
                    		{if $article.num_visites}
                    			<ul class="publication-info">
                    				<li>
                    					<div class="param-name">
                    						{lang('fees_visits')}:
                    					</div>
                    					<div class="param-value">
                    						{$article.num_visites} {lang('wmr')}
                    					</div>
                    				</li>
                    			</ul>
                    		{/if}
								
				{/if}
				{/if}
				{if $is_moderator && !$is_editor}
					<ul class="publication-info">
							<li>
								 <div class="param-name">{lang('advert_blocks')}:</div>
                        		<div class="param-value" id="p-{$article.id}">
									{$article.adverts}
								</div>
							</li>
						   
					</ul>
				{/if}
				
				{if $article.activity != 2 || $is_moderator}
                <ul class="publication-info actions">
                    <li>
                        <div class="param-name">{lang('actions')}:</div>
                        
						{if $article.activity != 2 && !$is_moderator}
							<a href="{site_url('publish/update')}/{$article.id}" title="{lang('edit_article')}" id="edit_{$article.id}" target="_blank" style="color: inherit;">
								<button class="to-edit">{lang('edit')}</button>
							</a>
							<a href="{site_url('publish/delete')}/{$article.id}" title="{lang('remove_article')}" onclick="delete_article(this.href,'{$article.id}','{$article.activity}');return false;" id="delete_{$article.id}">
						    <button class="to-delete">{lang('remove_article')}</button>
							</a>
							
						{elseif $is_moderator}
							<a href="{site_url('moderator/edit')}/{$article.id}" title="{lang('edit_article')}" id="edit_{$article.id}" target="_blank">
								<button class="to-edit">{lang('edit')}</button>
							</a>
						{/if}
						
						{if $article.activity == 0 || $article.activity == -1}
							<a href="{site_url('publish/add')}/{$article.id}" title="Отправить статью на модерацию" onclick="send_moderate(this.href,'{$article.id}','{$article.activity}');return false;" id="send_{$article.id}">
                        		<button class="to-check">{lang('to_moderation')}</button>
							</a>
						{/if}
						
                    </li>
                </ul>
				{/if}
            </li>
	{/foreach}

</ul>
<div class="pagination">
	{$paginator}
</div>
{else:}
<div>
{if isset($is_search) && isset($_REQUEST['search'])}
	{lang('nothing_found')}
{elseif isset($is_search)}

{else:}
	{lang('nothing_found')}
{/if}
</div>
{/if}
</div>
