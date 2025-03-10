{literal}
<script type="text/javascript">
$(function(){
var current_url = document.location.href;
buffer = current_url.split('#');
if(buffer.length > 1){

comment_id = buffer[2];
if($('#link_all_comments').length > 0){
$('#link_all_comments').css({'display' : 'none'});
var link_all_comments = $('#link_all_comments a').attr('href');
show_all_comments(link_all_comments);
$.scrollTo('#'+comment_id,1000);
}
else{
$.scrollTo('#'+comment_id,1000);

}
}
});

</script>
{/literal}


<!------------------------------------------- про кота -------------------------------------------->
<noindex>
<p style="text-align: justify;">
	<a name="cat"></a></p>
</br>

<p style="text-align: center;"><strong><span style="color: #ff0000;">Я ПОЙМАЛ КОТА!!! </span></strong><strong><span style="color: #339966;">А ВАМ СЛАБО???</span></strong></p>
<p style="text-align: center;">Логическая игра: нужно окружить кота, нажимая на круги, чтобы он не убежал с поля! Если не получилось - игра начнется заново.</p>

</br>
</noindex>

<noindex>
{if isset($advert_under_content)}
	{$advert_under_content}
{else:}
<script type="text/javascript"
 src="/templates/ruautor/js/ta-k1.js"></script>
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
{/if}
</noindex>

</br>

<div align="center"><object width="600" height="450" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="//download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" align="middle"><param name="wmode" value="opaque" /><param name="src" value="/templates/ruautor/js/cat.swf" /><param name="quality" value="high" /><param name="allowscriptaccess" value="sameDomain" /><param name="pluginspage" value="//www.macromedia.com/go/getflashplayer" /><embed width="600" height="450" type="application/x-shockwave-flash" src="/templates/ruautor/js/cat.swf" wmode="opaque" quality="high" allowscriptaccess="sameDomain" pluginspage="//www.macromedia.com/go/getflashplayer" align="middle" /></object></div>
<div class="fusion-clearfix"></div>


<!------------------------------------------- Выведение комментариев -------------------------------------------->
{if $comments_arr}

<div class="comments-info">
            <div class="param-name">{lang('number_of_comments')}:</div>
            <div class="param-value" id="total_comments">{$total_comments}</div>
        </div>
        <ul class="comments">
		{foreach $comments_arr as $comment}
            <li class="comment" id="comment_{$comment.id}">
                <div class="user-avatar">
					{if $comment.avatar}
						{$comment.avatar}
					{else:}
						<div class="figure-2"></div>
                    	<div class="figure-1"></div>
					{/if}
                </div>
                <div class="user-login">
					<a href="{site_url('')}avtory/{$comment.username}" target="_blank" style="color: inherit;text-decoration:underline;">{$comment.author}</a>
				</div>
				{if $is_moderator || $comment.is_possible_delete}
            		<div class="actions">
						<a href="{site_url('comment/delete')}/{$comment.id}" title="{lang('remove_comment')}" onclick="delete_comment(this.href,'{$comment.id}');return false;" id="delete_{$comment.id}" style="text-decoration: none;">
							<img src="/{$THEME}/images/icon_delete.png" width="12" height="12"/>
						</a>
 &nbsp;
						<a href="{site_url('comment/edit')}/{$comment.id}" title="{lang('edit_comment')}" onclick="modal_edit_comment(this,'{$comment.id}');return false;" id="edit_{$comment.id}">
							<img src="/{$THEME}/images/icon_edit.png" width="12" height="12"/>
						</a>
				   </div>
        {/if}
				<br>
                <div class="comment-date"><span>{time_change_show_data($comment.data)}</span></div>
				
                <div class="comment-text">
                   <span>{$comment.comment}{$comment.edited}</span>
                </div>
            </li>
			{/foreach}
        </ul>
		{/if}
		
		<!----------------------------------------- Выведение блока плюсования ----------------------------------------------->
{if $user_id != $article_owner_user_id && empty($is_moderator)}
	{if $allow_plus == 1}
		<!--div id="assess_article" style="margin-bottom:10px;">
			<a href="{site_url('comment/add_plus')}/{$article_id}" onclick="add_plus(this.href, '{$user_id}','{site_url('')}',{$add_plus});return false;" title="Поставить автору плюс" style="color:black; text-decoration: none;">
				<img src="/templates/articler/images/plus.jpg"/>
					&nbsp;&nbsp;Добавить плюс
			</a>
		</div-->
	{elseif $allow_minus == 1}
		<!--div id="assess_article" style="margin-bottom:10px;">
			<a href="{site_url('comment/delete_plus')}/{$article_id}" onclick="delete_plus(this.href, '{$user_id}','{site_url('')}',{$add_plus});return false;" title="Удалить плюс" style="color:black; text-decoration: none;">
				<img src="/templates/articler/images/minus.gif" width='13' height='13'/>
					&nbsp;&nbsp;Удалить плюс (добавлен {time_change_show_data($data_published)})
				</a>
		</div-->

	{/if}
	{if $allow_comments == 1 && $unauthorized}
		<input type="hidden" id="comment_flag" value="unauthorized" name="comment_flag"/>
	{/if}
	
{/if}
<!------------------------------------------------------------------------------------------------------------------------>


		
		<div class="content-title">{lang('leave_comment')}</div>
        <form action="{site_url('articler/add_comment')}" method="post" class="form-comment" onsubmit="add_comment(this);return false;" id="comment_form">
		 <input type="hidden" name="redirect" value="{uri_string()}" />
		 <input type="hidden" name="article_id" id="article_id" value="{$article_id}"/>
		 <input type="hidden" name="user_id" id="user_id" value="{$user_id}"/>
		 {if isset($private)}
			<input type="hidden" name="private" id="private" value="1"/>
		 {/if}
			<input type="hidden" name="article_owner_user_id" id="article_owner_user_id" value="{$article_owner_user_id}"/>
            <label>
			<textarea name="comment_text" id="comment-field" rows="5" cols="40" placeholder="{lang('comment_text')}" class="comment_text"></textarea>
			</label>
			<div id="container_submit">
				<input value="{lang('submit')}" type="submit">
			</div>
			{form_csrf()}
        </form>
