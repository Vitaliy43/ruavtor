<div class="sub-title">{lang('recent_replies')}</div>
        <ul class="reviews">
		{foreach $comments as $comment}
			{if strlen($comment.comment) >= $length_last_comment}
				{$comment_text = mb_substr($comment.comment,0,$length_last_comment).'..'}
			{else:}
				{$comment_text = $comment.comment}
			{/if}
		<li>
            <div class="review-name">{$comment.name} {$comment.family}</div>
            <div class="review-text">
               <a href="{$comment.article_url}#comment_{$comment.id}">{$comment_text}</a>
            </div>
        </li>
		{/foreach}
        </ul>
