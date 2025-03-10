{literal}
<style type="text/css">
	.user_profile  .input_text{width:50px;margin-bottom:5px;}
	.publication-info li:nth-child(1) {float: left;width: 200px;text-align: left;padding-left: 20px;border-left: 1px solid #ebebeb;}
	.publication-info li:nth-child(2) {float: left;width: 350px;}
	.publication-info li:nth-child(3) {float: left;width: 298px; text-align: right; padding-right: 20px;}
</style>
{/literal}
<div class="personal_page">
{
	$dashboard = lang('dashboard');
	$dashboard = str_replace('%login%',$nickname,$dashboard);
}
<div class="block-title">{$dashboard}</div>
<div class="avatar" style="margin-top: 15px;"><img src="{$avatar_src}" width="{$avatar_sizes.width}" height="{$avatar_sizes.height}"></div>
<div class="content-block" style="padding: 10px;">
<div class="param">
  <div class="param-name">{lang('name')}:</div>
      <div class="param-value" style="font-weight: normal"> {$author_name}</div>
  </div>
 <div class="param">
    <div class="param-name">{lang('author_rating')}:</div>
    <div class="param-value">
	 {if isset($is_moderator) && empty($is_editor)}
		<input type="text" name="author_rating" id="author_rating" value="{$author_rating}" class="input_text" style="width:54px;"/>
		<input type="hidden" id="old_author_rating" value="{$author_rating}"/>
		<input type="button" onclick="change_rating('author','{site_url('moderator/change_rating')}/{$nickname}','{site_url('')}');return false;" value="{lang('change')}" id="button_author_rating"/>
	{else:}
		<b>{$author_rating}</b>
	{/if}
	 </div>
 </div>
<div class="param">
   <div class="param-name">{lang('rating_activity')}:</div>
   <div class="param-value">
    {if isset($is_moderator) && empty($is_editor)}
		<input type="text" name="rating_activity" id="rating_activity" value="{$rating_activity}" class="input_text"/>
		<input type="button" onclick="change_rating('activity','{site_url('moderator/change_rating')}/{$nickname}','{site_url('')}');return false;" value="{lang('change')}" id="button_rating_activity"/>
		<input type="hidden" id="old_rating_activity" value="{$rating_activity}"/>

	{else:}
		<b>{$rating_activity}</b>
	{/if}
	</div>
</div>
{if isset($author_group)}
<div class="param">
    <div class="param-name">{lang('status')}:</div>
    <div class="param-value"><b>{$author_group}</b></div>
</div>
{/if}

{if $num_visites_all > 0}
<div class="param">
  <div class="param-name">{lang('articles_views')}:</div><br>
  <div class="param-value" style="margin-left: 25px; margin-top: -50px;">{lang('total_views')} - <b>{$num_visites_all} ({$num_visites_all_correct})</b></div><br>
  <div class="param-value" style="margin-left: 25px; margin-top: -70px;">{lang('views_last_day')} - <b>{$num_visites_avg} ({$num_visites_avg_correct})</b></div>
</div>
{/if}


<div class="param">
  <div class="param-name">{lang('totally_publications')}:</div>
  <div class="param-value"><b>{$num_articles}</b></div>
</div>

</div>

<div class="block-title" style="margin-top: 30px;">{lang('publications_list')}:</div>

{if count($articles) > 0}

<ul>

{foreach $articles as $article}
	
	<li class="publication">
		
		<div class="publication-title">
           <a href="{$article.url}" class="publication-name" target="_blank" style="text-decoration: underline; color:#455f7c;" title="{$article.header}">{splitterWord($article.header,75)}</a>
           <div class="publication-date">{time_convert_date_to_text($article.data_published,$main_language)}</div>
        </div>
			
		<div class="publication-body">
              <span>{$article.annotation}</span>...
         </div>
		  <ul class="publication-info">
               <li>
                  <div class="param-name">{lang('number_of_comments')}:</div>
                  <div class="param-value"><a href="#">{$article.comments}</a><div class="comment-image"></div></div>
               </li>
                <li>
                   <div class="param-name">{lang('rubric')}:</div>
                   <div class="param-value"><a href="/{$article.heading_name}">{$article.heading_name_russian}</a></div>
                </li>
                <li>
                   <div class="param-name">{lang('rating')}:</div>
                   <div class="param-value">
				  {if isset($is_moderator)}
					<input type="text" id="rating_{$article.id}" value="{$article.rating}" class="input_text" style="width:34px;"/>
					<input type="hidden" id="old_rating_{$article.id}" value="{$article.rating}">
					<input type="button" onclick="change_rating_article('{site_url('moderator/change_article_rating')}/{$article.id}','{site_url('')}',{$article.id});return false;" value="{lang('change')}" id="button_rating_{$article.id}"/>
			 	{else:}
				 	<span class="num_rating">{$article.rating}</span>
			 	{/if}	
				   {if $author == 'guest'}
				 		<a class="arrow-image" href="javascript:void(0)" onclick="modal_change_rating(this,'{$author}');return false;"></a>
				 		<a class="arrow-image-red" href="javascript:void(0)" onclick="modal_change_rating(this,'{$author}');return false;"></a>
				 	{else:}
				 		<a class="arrow-image" href="{site_url('comment/add_plus')}/{$article.id}" onclick="add_plus_new(this,'{site_url('')}');return false;"></a>
				 		<a class="arrow-image-red" href="{site_url('comment/add_minus')}/{$article.id}" onclick="add_minus(this,'{site_url('')}');return false;"></a>
				 	{/if}
                </li>
           </ul>
			 
	</li>
	<input type="hidden" id="article_{$article.id}"/>
{/foreach}
</ul>
<div class="pagination" align="center">
    {$paginator}
</div>
{else:}
	<div>{lang('no_publications')}</div>
{/if}

<div class="clearfix"></div>

<div class="author_comments sub-title comments_author" style="margin-top: 30px;">
	{lang('author_comments')}: 
	<div class="value">{$num_comments_author}</div>
</div>
<div class="sub-block">{lang('recent_comments')}:</div>
<div class="content-block" style="padding: 15px;">
	
{if count($last_comments_author) > 0}

   <ul class="comment-links" style="width: 100%;">
{foreach $last_comments_author as $comment}
{
if(mb_strlen($comment['comment']) > 30):
	$comment_text = mb_substr($comment['comment'],0,30).'...';
else:
	$comment_text = $comment['comment'];
endif;

	$comment_text = mb_ucfirst($comment_text);

}

   <li style=" display: inline-block;width: 100%;height: 25px;">
        <a href="{$comment.article_url}#comment_{$comment.id}" title="{$comment.comment}" target="_blank" style=" color: #455F7C; font-weight: 600;">{$comment_text}</a>
            <div class="comment-time" style="display: inline-block; float: right;">
               <div class="date" style="display: inline-block;">{time_change_show_data($comment.data)}</div>
            </div>
   </li>
{/foreach}
	</ul>
		
{else:}
     <div class="no-comments">{lang('no_comments')}</div>
{/if}
</div>

<div class="author_comments sub-title comments_author" style="margin-top: 30px;">
	{lang('comments_author_publications')}: 
	<div class="value">{$num_comments_for_author}</div>
</div>
<div class="sub-block">{lang('recent_comments_author_publications')}:</div>
<div class="content-block" style="padding: 15px;">
{if count($last_comments_for_author) > 0}

   <ul class="comment-links" style="width: 100%;">
{foreach $last_comments_for_author as $comment}
{
if(mb_strlen($comment['comment']) > 30):
	$comment_text = mb_substr($comment['comment'],0,30).'...';
else:
	$comment_text = $comment['comment'];
endif;

	$comment_text = mb_ucfirst($comment_text);

}

   <li style=" display: inline-block;width: 100%;height: 25px;">
        <a href="{$comment.article_url}#comment_{$comment.id}" title="{$comment.comment}" target="_blank" style=" color: #455F7C; font-weight: 600;">{$comment_text}</a>
            <div class="comment-time" style="display: inline-block; float: right;">
               <div class="date" style="display: inline-block;">{time_change_show_data($comment.data)}</div>
            </div>
   </li>
{/foreach}
	</ul>
		
{else:}
     <div class="no-comments">{lang('no_comments')}</div>
{/if}
</div>

</div>




