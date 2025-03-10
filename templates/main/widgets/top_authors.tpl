<div class="block-title after-navi">{lang('top_authors')}</div>
        <table class="authors-top">
            <tr>
                <th style="padding-top: 6px;" title="Среднее кол-во просмотров в сутки"><span><img src="/templates/ruautor/images/eye.png" width="14" height="14"/></span></th>
                <th>{lang('nickname')}</th>
            </tr>
			{$counter = 1}
			{foreach $authors as $author}
            <tr>
                <!--td>{$counter}</td-->
                <td>{round($author.num_views/30)}</td>
                <td><a href="/avtory/{$author.username}" target="_blank">{ucfirst($author.username)}</a></td>
            </tr>
			{$counter++}
			{/foreach}
        </table>