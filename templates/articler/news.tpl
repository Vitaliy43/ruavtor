
{$category.short_desc}
{if $no_pages}
        <p>{$no_pages}</p>
{/if}
<!--ul>
{foreach $pages as $page}
	<li><em>{date('d.m.Y',$page.publish_date)}</em> <a href="{site_url($page.full_url)}">{$page.title}</a></li>
{/foreach}
</ul-->
<div id="block_news">

{foreach $pages as $page}
	<div class="small_news">
		
			
			<a href="{site_url($page.full_url)}">
				<h2>
					{$page.title}
				</h2>	
			</a>
			<p style="text-align: justify;"><em>{time_text_data($page.publish_date)}</em></p>
			<p>{$page.prev_text}</p>
			<p>
				<a href="{site_url($page.full_url)}">
					<b>Читать полностью</b>
				</a>
			</p>
		
	</div>
{/foreach}
</div>
<div class="pagination" align="center">
    {$pagination}
</div>
