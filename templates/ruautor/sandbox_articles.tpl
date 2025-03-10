{literal}
<style type="text/css">
.paginator a {color:black;}

</style>
{/literal}

{if isset($is_moderator)}
	{literal}
		<style type="text/css">
			.publication-info li:nth-child(1) {float: left;width: 150px;text-align: left;padding-left: 20px;border-left: 1px solid #ebebeb;}
			.publication-info li:nth-child(2) {float: left;width: 300px;}
			.publication-info li:nth-child(3) {float: left;width: 205px;}
			.publication-info li:nth-child(4) {float: left;width: 210px;}
		</style>
	{/literal}
{/if}

<div class="block-title">{lang('publications')}</div>
     <ul class="publications-block">
	 {if count($articles) > 0}
	 {foreach $articles as $article}
	  <li class="publication">
                <div class="publication-title">
                    <a href="{$article.url}" class="publication-name" title="{$article.header}">{splitterWord($article.header,75)}</a>
                    <div class="publication-date">{time_convert_date_to_text($article.data_published,$main_language)}</div>
                </div>
                <div class="publication-body">
                   <span>{$article.annotation}
				   </span>...
                </div>
                <ul class="publication-info">
                    <li>
                        <div class="param-name">{lang('number_of_comments')}:</div>
                        <div class="param-value"><a href="#">{$article.comments}</a>
							{if empty($is_moderator)}
                        		<div class="comment-image"></div>
                        	{/if}                        
                        </div>
                    </li>
                    <li>
                        <div class="param-name">{lang('rubric')}:</div>
                        <div class="param-value"><a href="/{$article.heading_name}" title="{$article.name_russian}">{splitterWord($article.name_russian,25)}</a></div>
                    </li>
                    <li>
                        <div class="param-name">{lang('rating')}:</div>
                         <div class="param-value">
						{if isset($is_moderator)}
							<input type="text" id="rating_{$article.id}" value="{$article.rating}" class="input_text" style="width:34px;"/>
							<input type="button" onclick="change_rating_article('{site_url('moderator/change_article_rating')}/{$article.id}','{site_url('')}',{$article.id});return false;" value="{lang('change')}" id="button_rating_{$article.id}"/>
							<input type="hidden" id="old_rating_{$article.id}" value="{$article.rating}">
			 			{else:}
				 			<span class="num_rating">{$article.rating}</span>
				 			{if $author == 'guest'}
				 				<a class="arrow-image" href="javascript:void(0)" onclick="modal_change_rating(this,'{$author}');return false;"></a>
				 				<a class="arrow-image-red" href="javascript:void(0)" onclick="modal_change_rating(this,'{$author}');return false;"></a>
				 			{else:}
				 				<a class="arrow-image" href="{site_url('comment/add_plus')}/{$article.id}" onclick="add_plus_new(this,'{site_url('')}');return false;"></a>
				 				<a class="arrow-image-red" href="{site_url('comment/add_minus')}/{$article.id}" onclick="add_minus(this,'{site_url('')}');return false;"></a>
				 			{/if}
				 			</div>
			 			{/if}	          
                    </li>
					<li>
                        <div class="param-name">{lang('author')}:</div>
                       		<div class="param-value"><a href="{site_url('avtory')}/{$article.username}" target="_blank" title="{$article.name} {$article.family}">{splitterWord($article['name'].' '.$article['family'],16)}</a></div>
                    </li>
                </ul>
            </li>
			<input type="hidden" id="article_{$article.id}"/>
			
	 	{/foreach}
		<div class="pagination" align="left">
			{$paginator}
		</div>
		{else:}
			<div>{lang('rubric_empty')}</div>
		{/if}
	</ul>
