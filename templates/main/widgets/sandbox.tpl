<div class="block-title">
<a href="http://www.ruavtor.ru/templates/ruautor/js/tea.php" target="_blank">
		      		<button class="add-material" onclick="http://www.ruavtor.ru/templates/ruautor/js/tea.php;return false;"><span style="color: yellow;">Магазин ЧАЯ</span></button>
				</a>
</div>

<div class="block-title">{lang('sandbox')}</div>
        <div class="sub-title">{lang('recent_publications')}</div>
        <ul class="sandbox">
		{foreach $articles as $article}
			<li>
				<a href="{$article.url}">{$article.header}</a>
			</li>
		{/foreach}
            <li>
                <a href="{site_url('pesochnica')}">{lang('go_sandbox')}</a>
            </li>
        </ul>
