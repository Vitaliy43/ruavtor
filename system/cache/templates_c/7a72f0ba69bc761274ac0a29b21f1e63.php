<div class="block-title">
<a href="http://www.ruavtor.ru/templates/ruautor/js/tea.php" target="_blank">
		      		<button class="add-material" onclick="http://www.ruavtor.ru/templates/ruautor/js/tea.php;return false;"><span style="color: yellow;">Магазин ЧАЯ</span></button>
				</a>
</div>

<div class="block-title"><?php echo lang ('sandbox'); ?></div>
        <div class="sub-title"><?php echo lang ('recent_publications'); ?></div>
        <ul class="sandbox">
		<?php if(is_true_array($articles)){ foreach ($articles as $article){ ?>
			<li>
				<a href="<?php echo $article['url']; ?>"><?php echo $article['header']; ?></a>
			</li>
		<?php }} ?>
            <li>
                <a href="<?php echo site_url ('pesochnica'); ?>"><?php echo lang ('go_sandbox'); ?></a>
            </li>
        </ul>
<?php $mabilis_ttl=1540976799; $mabilis_last_modified=1530514598; //D:\server\www\projects\articler.img\/templates//main/widgets/sandbox.tpl ?>