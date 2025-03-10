{literal}
<style type="text/css">

.last_articles li a {text-align:left !important;}

</style>
{/literal}

<div><b>Последние отклики</b></div>
<div  class="last_articles">
{foreach $comments as $comment}
<div>
<div class="author">
{$comment.name} {$comment.family}
</div>
<div>

{if strlen($comment.comment) >= $length_last_comment}
{$comment_text = mb_substr($comment.comment,0,$length_last_comment).'..'}
{else:}
{$comment_text = $comment.comment}
{/if}
<a href="{$comment.article_url}#comment_{$comment.id}">{$comment_text}</a>
</div>
<div>
{str_repeat('.',40)}
</div>
{/foreach}
</div>
