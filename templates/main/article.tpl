{literal}
<script type="text/javascript">
	$(document).ready(function(){
		 /**************************/
            var changeFont = $('.font-resizer li');
            $(changeFont).click(function() {
                if (!$(this).hasClass('active')){
                    $(changeFont).removeClass('active');
                    $(this).addClass('active');
                    var fontSize = $(this).css('font-size');
                    $('.content-text').css('font-size', fontSize);
                    $('.comment-text').css('font-size', fontSize);
                }
            });
	});
	
	$(function() {
	$('#change_url').hide();
	$("#link_change_url").click(function() {
		$('#change_url').slideToggle('normal');
		return false;
});
});
	
</script>
{/literal}

 <!--h1 title="{$header}">{splitterWord($header,80)}</h1-->
 <h1>{$header}</h1>

        <ul class="content-info">
		<input type="hidden" name="article_id" value="{$id}"/>
		{if isset($rating_for_homefeed)}
			<input type="hidden" name="rating_for_homefeed" id="rating_for_homefeed" value="{$rating_for_homefeed}"/>
		{/if}
	{if isset($header) && $is_moderator == 1 && isset($is_select_headings)}
		<li>
			<div class="param-name">{lang('heading')}:</div>
                <div class="param-value">
				
				{if isset($errors.header)}
					&nbsp;{$errors.header}
				{/if}
				{if $activity != 2}
					<input type="text" name="header" id="publisher_header" onchange="translit();" value="{$header}"/>
				{else:}
					<input type="text" name="header" id="publisher_header" value="{$header}"/>
				{/if}
				</div>
		</li>
		
	{/if}

            <li>
                <div class="param-name">{lang('rubric')}:</div>
                <div class="param-value">
				{if isset($is_select_headings)}
					{$headings}
				{else:}
					<a href="{site_url('')}{$headings_url}" target="_blank">{$heading_name_russian}</a>
				{/if}
				</div>
            </li>
            <li>
                <div class="param-name">{lang('author')}:</div>
                <div class="param-value">
					{if isset($author_name)}
						<a href="{site_url('avtory')}/{$username}" target="_blank">{$author_name} {$author_family}</a>

				{else:}
					<a href="{site_url('avtory')}/{$username}" target="_blank">{$username}</a>
				{/if}
				</div>
            </li>
            <li>
                <div class="param-name" data-test="{$main_language}">{lang('data')}:</div>
                <div class="param-value">
				{if $activity == 2}
					{time_convert_date_to_text($data_published,$main_language)}
				{else:}
					{time_convert_date_to_text($data_saved,$main_language)}
				{/if}
				</div>
            </li>
		
            <li>
                <div class="param-name">{lang('rating')}:</div>
                <div class="param-value"  id="article_rating">
				{if isset($rating)}
					<span class="num_rating">{$rating}</span>

				{else:}
					<span class="num_rating">0</span>
				{/if}
				
				{if $author == 'guest'}
				 		<a class="arrow-image" href="javascript:void(0)" onclick="modal_change_rating(this,'{$author}');return false;"></a>
				 		<a class="arrow-image-red" href="javascript:void(0)" onclick="modal_change_rating(this,'{$author}');return false;"></a>
				 	{else:}
				 		<a class="arrow-image" href="{site_url('comment/add_plus')}/{$id}" onclick="add_plus_new(this,'{site_url('')}');return false;"></a>
				 		<a class="arrow-image-red" href="{site_url('comment/add_minus')}/{$id}" onclick="add_minus(this,'{site_url('')}');return false;"></a>
				 	{/if}
            </li>
			
			{if isset($num_visites_all) && $num_visites_all > 0}
			<li>
				<div class="param-name">{lang('views')}:</div>
				<div class="param-value">
					<div id="all_num_visites">{lang('total_views')} - <b>{$num_visites_all} {$num_visites_all_correct}</b></div>
					<div id="average_num_visites">{lang('views_last_day')} - <b>{$num_visites_avg} {$num_visites_avg_correct}</b></div>
				</div>
			</li>
			{/if}
			
        </ul>
        <div class="font-resizer">
            <div class="text-1">{lang('font_size')}:</div>
            <ul style="font-size: 15px;">
                <li class="active small">А</li>
                <li class="medium" style="font-size: 115%;">А</li>
                <li class="large" style="font-size: 130%;">А</li>
            </ul>
        </div>

<noindex>
{if isset($advert_over_content)}
	{$advert_over_content}
{else:}
<script type="text/javascript"
 src="/templates/ruautor/js/ta-k1.js"></script>
<script type="text/javascript"
src="https://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
{/if}
</noindex>

<br />
<p style="text-align: center;"><a href="#cat"> П о й м а й &nbsp;&nbsp; к о т а ! </a></p>
<br />
		
        <div class="content-block">
		{if isset($picture)}
            <div class="pictures-block">
                <img class="content-photo" src="{$picture}" alt="Фото статьи">
               <div class="pictures-block" style="margin: -0px;">
<noindex>
{if isset($advert_under_image)}
				{$advert_under_image}
{else:}
<script type="text/javascript" src="/templates/ruautor/js/ra-SHS.js"></script>
<script type="text/javascript"
src="https://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>

{/if}
</noindex>
</div>
            </div>
			
			{/if}


            <div class="content-text">
            	{if isset($picture)}
            		{cutImage($picture,$content)}
            	{else:}
               		{$content}
               	{/if}
            </div>
			

<noindex>
{literal}
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-format="autorelaxed"
     data-ad-client="ca-pub-8689715513247014"
     data-ad-slot="2918805187"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
{/literal}
</noindex>			
			
			
			{if isset($is_moderator) && $is_moderator == 1 && empty($is_edited)}
			<div style="margin-bottom:10px; margin-left: 10px; margin-top: 10px;"><a href="{site_url('moderator/edit')}/{$id}" target="_blank">{lang('edit_article')}</a></div>
		{/if}
            <div class="clearfix"></div>
        </div>

      