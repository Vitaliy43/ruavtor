{literal}
<style type="text/css">
	.paginator a {color:black;}
</style>
{/literal}
{if count($authors) > 0}
   <div class="block-title">{lang('list_authors')}</div>
      <table class="top-authors">
	  	  <tr>
                <th>#</th>
                <th>{lang('nickname')}</th>
                <th>{lang('name')}</th>
                <th>{lang('number_articles')}</th>
                <th>{lang('rating')}</th>
                <th>{lang('status')}</th>
          </tr>
		  {$counter = 1}
		{foreach $authors as $author}
	  	 <tr>
                <td>{$counter}</td>
				{
					$view_all_articles = lang('view_articles_author');
					$view_all_articles = str_replace('%author%',$author['username'],$view_all_articles);
				}
                <td><a href="{site_url('avtory')}/{$author.username}" target="_blank" title="{$view_all_articles}">{$author.username}</a></td>
                <td>{$author.name} {$author.family}</td>
                <td>{$author.num_articles}</td>
                <td>{$author.author_rating}</td>
                <td>{$author.status}</td>
         </tr>
		 {$counter ++}
		 {/foreach}
	  
	  </table>
	  <div class="pagination" align="left" style="margin-top: 10px;">
		{$paginator}
	</div>

{else:}
<div>{lang('rubric_empty')}</div>
{/if}
