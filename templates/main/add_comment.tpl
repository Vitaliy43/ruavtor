{if isset($one_comments)}
<div class="comments-info">
            <div class="param-name">{lang('number_of_comments')}:</div>
            <div class="param-value" id="total_comments">1</div>
        </div>
        <ul class="comments">
{/if}
<li class="comment" id="comment_{$id}">
               <div class="user-avatar">
					{if $avatar}
						{$avatar}
					{else:}
						<div class="figure-2"></div>
                    	<div class="figure-1"></div>
					{/if}
                </div>
                <div class="user-login">
					{$author}
				</div>
				{if $reply_comment}
					<div style="font-size:9px;font-weight:bold;">{lang('reply_to_comment')}: {$reply_comment}</div>
				{/if}
				
					<div class="actions">
						<a href="{site_url('comment/delete')}/{$id}" title="{lang('remove_comment')}" onclick="delete_comment(this.href,'{$id}');return false;" id="delete_{$id}" style="text-decoration: none;">
							<img src="/{$THEME}/images/icon_delete.png" width="12" height="12"/>
						</a>
 &nbsp;
						<a href="{site_url('comment/edit')}/{$id}" title="{lang('edit_comment')}" onclick="modal_edit_comment(this,'{$id}');return false;" id="edit_{$id}">
							<img src="/{$THEME}/images/icon_edit.png" width="12" height="12"/>
						</a>
				 </div>
				<br>
                <div class="comment-date"><span>{$date}</span></div>
				
                <div class="comment-text">
                   <span>{$comment}</span>
                </div>
            </li>
{if isset($one_comments)}
	</ul>
{/if}
