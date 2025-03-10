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
{if $comments_arr}
<!------------------------------------------- Выведение комментариев -------------------------------------------->
<div id="detail" style="width:60%;">
    <h3 id="total_comments" style="color:black;font-weight:bold; margin-bottom:5px;">Комментариев {$total_comments}</h3>
    {$counter = 1}
    {foreach $comments_arr as $comment}
	
    <div id="comment_{$comment.id}" class="comment{if $counter == 2} next_row{$counter = 0}{/if}" >
    <hr>
        <div class="comment_info">
			<table width="100%">
			<tr>
				<td width="50%">{$comment.avatar}</td>	
				<td></td>
				<td></td>
			</tr>
			<tr>
			<td class="comment_username">
			    <a href="{site_url('')}avtory/{$comment.username}" target="_blank" style="color:black;text-decoration:underline;"><b>{$comment.author}</b></a>
			</td>
			<td class="time_show_data">
			    <span>{time_change_show_data($comment.data)}</span>
			</td>
			<td align="right">
		{if isset($allow_plea)}
			{if $user_id != $comment.user_id}	
				<span>
					<a href="#add_plea" title="Сообщить о нарушении модератору" class="plea_modal" style="color:black;" onclick="plea_modal('{$user_id}','{site_url('comment/add_plea')}/{$comment.id}',{$comment.id},'{site_url('')}');return false;">
<img src="/templates/articler/images/plea.png" width="10" height="10"/>
				</span>
			{/if}
		{/if}
        {if $is_moderator || $comment.is_possible_delete}
            &nbsp;
<a href="{site_url('comment/delete')}/{$comment.id}" title="Удалить комментарий" onclick="delete_comment(this.href,'{$comment.id}');return false;" id="delete_{$comment.id}">
<img src="/{$THEME}/images/icon_delete.png" width="12" height="12"/>
</a>
 &nbsp;
<a href="{site_url('comment/edit')}/{$comment.id}" title="Редактировать комментарий" onclick="modal_edit_comment(this.href,'{$comment.id}');return false;" id="edit_{$comment.id}">
<img src="/{$THEME}/images/icon_edit.png" width="12" height="12"/>
</a>

        {/if}
			</td>
			</tr>
			
			</table>
			
			{if $comment.reply_id}
				
				<span style="font-size:9px;font-weight:bold;">Ответ на комментарий: {
					echo $reply[$comment['reply_id']];
					
				}</span>
			{/if}
        </div>
		<div class="comment_text" style="margin-bottom:10px;margin-left:10px;">
            {$comment.comment}{$comment.edited}
        </div>
			{if $user_id == $article_owner_user_id && $comment.user_id != $user_id && $comment.answered != 1}
			       	<div id="reply_{$comment.id}">

				<a href="#reply_comment" onclick="reply_modal('{$user_id}','{site_url('comment/add_reply')}/{$comment.id}',{$comment.id},'{site_url('')}');return false;" title="Ответить на комментарий" style="color:black;" class="reply_modal">
	<img src="/templates/articler/images/reply.png" width="13" height="13" style="margin-bottom:-2px;"/>
	&nbsp;&nbsp;Ответить на комментарий
	</a>
		</div>
			{/if}
		
    </div>
	
    {$counter++}
    {/foreach}
</div>
<!--------------------------------------------------------------------------------------------------------------------->
{/if}
<input type="hidden" id="total_comments" value="{$total_comments}"/>
<input type="hidden" id="show_num_comments" value="{$show_num_comments}"/>
{if $total_comments > $show_num_comments}
<div id="link_all_comments" style="margin-bottom:10px;">
	<a href="{site_url('all_comments')}/{$article_id}" onclick="show_all_comments(this.href);return false;" style="color:black;text-decoration:underline;">Показать все комментарии</a>
	</div>
	
{/if}

<!----------------------------------------- Выведение блока плюсования ----------------------------------------------->
{if $user_id != $article_owner_user_id && empty($is_moderator)}
{if $allow_plus == 1}
<div id="assess_article" style="margin-bottom:10px;">
<a href="{site_url('comment/add_plus')}/{$article_id}" onclick="add_plus(this.href, '{$user_id}','{site_url('')}',{$add_plus});return false;" title="Поставить автору плюс" style="color:black;">
<img src="/templates/articler/images/plus.jpg"/>
&nbsp;&nbsp;Добавить плюс
</a>
</div>
{elseif $allow_minus == 1}
<div id="assess_article" style="margin-bottom:10px;">
<a href="{site_url('comment/delete_plus')}/{$article_id}" onclick="delete_plus(this.href, '{$user_id}','{site_url('')}',{$add_plus});return false;" title="Удалить плюс" style="color:black;">
<img src="/templates/articler/images/minus.gif" width='13' height='13'/>
&nbsp;&nbsp;Удалить плюс (добавлен {time_change_show_data($data_published)})
</a>
</div>

{/if}
{if $allow_comments == 1 && $unauthorized}
<input type="hidden" id="comment_flag" value="unauthorized" name="comment_flag"/>
{/if}
<div id="detail" style="width:60%;">
<h3 style="margin-bottom:10px;" id="post_comment">{lang('post_comment')}
{if isset($allow_plus)}
&nbsp;&nbsp;
{/if}
</h3>
<!------------------------------------------------------------------------------------------------------------------------>



{if $comment_errors}
    <div class="errors"> 
        {$comment_errors}
    </div>
{/if}

{if $can_comment === 1 AND !is_logged_in}
     <p>{sprintf(lang('login_for_comments'), site_url($modules.auth))}</p>
{/if}


<form action="{site_url('articler/add_comment')}" method="post" class="form" onsubmit="add_comment();return false;" id="comment_form">
<!--form action="{site_url('articler/add_comment')}" method="post" class="form" id="comment_form"-->
<!--div onclick="add_comment();return false;">Test</div-->
    <input type="hidden" name="redirect" value="{uri_string()}" />
	<input type="hidden" name="article_id" id="article_id" value="{$article_id}"/>
	<input type="hidden" name="user_id" id="user_id" value="{$user_id}"/>
	{if isset($private)}
		<input type="hidden" name="private" id="private" value="1"/>
	{/if}
	<input type="hidden" name="article_owner_user_id" id="article_owner_user_id" value="{$article_owner_user_id}"/>

    {if $is_logged_in} 
        <p>{lang('lang_logged_in_as')} {$username}. <a href="{site_url('auth/logout')}">{lang('lang_logout')}</a></p>         
    {else:}
	
    <div class="comment_form_info">
    
    <div class="textbox_spacer"></div>
         
    </div>

    <div class="textbox">
        <textarea name="comment_text" id="comment_text" rows="6" cols="50" onfocus="if(this.value=='Текст комментария') this.value='';" onblur="if(this.value=='') this.value='Текст комментария';">{if $_POST['comment_text']}{$_POST['comment_text']}{else:}Текст комментария{/if}</textarea>
    </div>
	
    
    {if !$is_logged_in} 
    
    {if $use_captcha}
    <div class="comment_form_info">
    <div class="textbox captcha">
        <input type="text" name="captcha" id="captcha" value="Код протекции" onfocus="if(this.value=='Код протекции') this.value='';" onblur="if(this.value=='') this.value='Код протекции';"/>
   	</div>
    {$cap_image}
    </div>
    {/if}
    
    {/if}
	<div id="container_submit" style="margin-top:10px;">
		<input type="submit" class="submit" value="{lang('lang_comment_button')}" />
	</div>

    {form_csrf()}
</form>
</div>
{/if}
{else:}
 <input type="hidden" name="redirect" value="{uri_string()}" />
	<input type="hidden" name="article_id" id="article_id" value="{$article_id}"/>
	<input type="hidden" name="user_id" id="user_id" value="{$user_id}"/>
	<input type="hidden" name="article_owner_user_id" id="article_owner_user_id" value="{$article_owner_user_id}"/>
{/if}

