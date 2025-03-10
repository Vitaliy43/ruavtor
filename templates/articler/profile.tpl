{literal}
<style type="text/css">
	.user_profile  .input_text{width:50px;margin-bottom:5px;}
</style>
{/literal}
<div class="personal_page">
<div class="block-title">Личный кабинет - Elro</div>
<div class="avatar"><img src="{$avatar_src}" width="{$avatar_sizes.width}" height="{$avatar_sizes.height}"></div>
<div style="margin-bottom:10px;">Имя: {$author}</div>
<div>Авторский рейтинг: 
{if isset($is_moderator) && empty($is_editor)}
<input type="text" name="author_rating" id="author_rating" value="{$author_rating}" class="input_text" style="width:54px;"/>
<input type="hidden" id="old_author_rating" value="{$author_rating}"/>
<input type="button" onclick="change_rating('author','{site_url('moderator/change_rating')}/{$nickname}','{site_url('')}');return false;" value="Изменить" id="button_author_rating"/>
{else:}
<strong>{$author_rating}</strong>
{/if}
</div>
<div>Рейтинг активности: 
{if isset($is_moderator) && empty($is_editor)}
<input type="text" name="rating_activity" id="rating_activity" value="{$rating_activity}" class="input_text"/>
<input type="button" onclick="change_rating('activity','{site_url('moderator/change_rating')}/{$nickname}','{site_url('')}');return false;" value="Изменить" id="button_rating_activity"/>
<input type="hidden" id="old_rating_activity" value="{$rating_activity}"/>

{else:}
<strong>{$rating_activity}</strong>
{/if}
</div>
{if isset($author_group)}
<div>Статус: <strong>{$author_group}</strong></div>
{/if}
{if $num_visites_all > 0}
<div>Просмотров статей:</div>
<div id="all_num_visites">Всего - <b>{$num_visites_all}</b></div>
<div id="average_num_visites">За прошедшие сутки - <b>{$num_visites_avg}</b></div>
{/if}
<div class="posts">
<div><b>Всего публикаций: {$num_articles}</b></div>
<div>Список публикаций:</div>
{if count($articles) > 0}

<div id="block_news" style="margin-top:15px;">

{foreach $articles as $article}
	
	<div class="small_news">
		
			<a href="{$article.url}" target="_blank">
				<h2>
					{$article.header}
				</h2>	
			</a>
			<p style="text-align: justify;"><em>{time_convert_date_to_text($article.data_published)}</em>
			
			<p style="text-align: right;margin-top:-15px;">Рейтинг:
			 {if isset($is_moderator)}
				<input type="text" id="rating_{$article.id}" value="{$article.rating}" class="input_text" style="width:34px;"/>
				<input type="hidden" id="old_rating_{$article.id}" value="{$article.rating}">
				<input type="button" onclick="change_rating_article('{site_url('moderator/change_article_rating')}/{$article.id}','{site_url('')}',{$article.id});return false;" value="Изменить" id="button_rating_{$article.id}"/>
			 {else:}
				 <strong>{$article.rating}</strong>
			 {/if}	
			 </p>	
			 </p>
			<p style="text-align: left;">{$article.annotation}</p>
			<p>
				<a href="{$article.url}" target="_blank" style="color:#FF3000;">
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
{else:}
<div>Публикаций нет.</div>
{/if}
</div>
<div class="author_comments">
<div><b>Комментарии автора: {$num_comments_author}</b></div>
<div>Последние комментарии:</div>
{if count($last_comments_author) > 0}
<table cellpadding="2" cellspacing="2" width="80%">
{foreach $last_comments_author as $comment}
<tr>
{
if(mb_strlen($comment['comment']) > 30):
	$comment_text = mb_substr($comment['comment'],0,30).'...';
else:
	$comment_text = $comment['comment'];
endif;

	$comment_text = mb_ucfirst($comment_text);

}
<td><a href="{$comment.article_url}#comment_{$comment.id}" title="{$comment.comment}" target="_blank">{$comment_text}</a></td>
<td>{time_change_show_data($comment.data)}</td>
</tr>
{/foreach}
</table>
{else:}
<div>Комментариев нет</div>
{/if}
</div>
<div class="user_comments" style="margin-top:10px;">
<div><b>Комментарии к публикациям автора: {$num_comments_for_author}</b></div>
<div>Последние комментарии к публикациям автора:</div>
{if count($last_comments_for_author) > 0}
<table cellpadding="2" cellspacing="2" width="80%">
{foreach $last_comments_for_author as $comment}
<tr>
{
if(mb_strlen($comment['comment']) > 30):
	$comment_text = mb_substr($comment['comment'],0,30).'...';
else:
	$comment_text = $comment['comment'];
endif;

	$comment_text = mb_ucfirst($comment_text);

}
<td><a href="{$comment.article_url}#comment_{$comment.id}" title="{$comment.comment}" target="_blank">{$comment_text}</a></td>
<td>{time_change_show_data($comment.data)}</td>
</tr>

{/foreach}
</table>
{else:}
<div>Комментариев нет</div>
{/if}
</div>
</div>
