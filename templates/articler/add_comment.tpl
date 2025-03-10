 {if isset($all_comments)}
 <div style="width:60%;">
 {foreach $comments as $comment}
 <div id="comment_{$comment.id}" class="comment">
    <table width="100%">
		<tr>
			<td width="50%">
				{$avatar}
			</td>
			<td colspan="2"></td>
		</tr>
        <tr class="comment_info">
            <td>{$comment.author}</td>
            <td><span>{time_change_show_data($comment.data)}</span></td>
			<td>
			{if $user_id == $article_owner_user_id && $comment.user_id != $user_id && $comment.answered != 1}
			       	<div id="reply_{$comment.id}">

				<a href="#reply_comment" onclick="reply_modal({$user_id},'{site_url('comment/add_reply')}/{$comment.id}',{$comment.id},'{site_url('')}');return false;" title="Ответить на комментарий" style="color:black;" class="reply_modal">
	<img src="/templates/articler/images/reply.png" width="13" height="13" style="margin-bottom:-2px;"/>
	&nbsp;&nbsp;Ответить на комментарий
	</a>
		</div>
			{/if}   
			</td>
        </tr>
		<tr>
		<td width="50%">
		<div class="comment_text">
            {$comment.comment}
        </div>
		</td>
        </tr>
		</table> 
   </div>
   <hr/>
  {/foreach}
  </div>
 
 {else:}
     <hr class="hr"/>

 <div id="comment_{$id}" class="comment">
 		<table width="100%">
		<tr>
			<td width="50%">
				{$avatar}
			</td>
			<td colspan="2"></td>
		</tr>
        <tr>
            <td><b>{$author}</b></td>
            <td><span>{$date}</span></td>
			<td align="right">
			{if $reply_comment}
				<span style="font-size:9px;font-weight:bold;">Ответ на комментарий: {$reply_comment}</span>
			{/if}
			<span>
			<a href="{site_url('comment/delete')}/{$id}" title="Удалить комментарий" onclick="delete_comment(this.href,'{$id}');return false;" id="delete_{$id}">
<img src="/{$THEME}/images/icon_delete.png" width="12" height="12"/>
</a>
 &nbsp;
<a href="{site_url('comment/edit')}/{$id}" title="Редактировать комментарий" onclick="modal_edit_comment(this.href,'{$id}');return false;" id="edit_{$id}">
<img src="/{$THEME}/images/icon_edit.png" width="12" height="12"/>
</a>
			</span>
			</td>
        </tr>
		<tr>
		<td width="50%">
		<div class="comment_text">
            {$comment}
        </div>
		</td>
		<td colspan="2"></td>
		</tr>
         </table>   
   </div>

  {/if}