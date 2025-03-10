{literal}
<style type="text/css">

.last_articles li a {text-align:left !important;}

</style>
{/literal}
<a href="{site_url('pesochnica')}">
<h3>Песочница</h3>
</a>
<div><b>Последние публикации:</b></div>
<div  class="last_articles">
{foreach $articles as $article}
<div>
<a href="{$article.url}">{$article.header}
</a><br>
{$article.in_sandbox}
</div>

{/foreach}
</div>
