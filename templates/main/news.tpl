
{$category.short_desc}
{if $no_pages}
        <p>{$no_pages}</p>
{/if}

<div id="block_news">

{foreach $pages as $page}
	<div class="small_news">
		
			
			<a href="{site_url($page.full_url)}">
				<h2>
					{$page.title}
				</h2>	
			</a>
			<p style="text-align: justify;"><em>{time_text_data($page.publish_date, false, $main_language)}</em></p>
			<p>{$page.prev_text}</p>
			<p>
				<a href="{site_url($page.full_url)}">
					<b>{lang('read_more')}</b>
				</a>
			</p>
		
	</div>
{/foreach}
</div>
<div class="pagination" align="center">
    {$pagination}
</div>
