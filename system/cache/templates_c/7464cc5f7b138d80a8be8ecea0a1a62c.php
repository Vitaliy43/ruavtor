<div class="block-title after-navi"><?php echo lang ('top_authors'); ?></div>
        <table class="authors-top">
            <tr>
                <th style="padding-top: 6px;" title="Среднее кол-во просмотров в сутки"><span><img src="/templates/ruautor/images/eye.png" width="14" height="14"/></span></th>
                <th><?php echo lang ('nickname'); ?></th>
            </tr>
			<?php $counter = 1?>
			<?php if(is_true_array($authors)){ foreach ($authors as $author){ ?>
            <tr>
                <!--td><?php if(isset($counter)){ echo $counter; } ?></td-->
                <td><?php echo round ( $author['num_views'] /30); ?></td>
                <td><a href="/avtory/<?php echo $author['username']; ?>" target="_blank"><?php echo ucfirst ( $author['username'] ); ?></a></td>
            </tr>
			<?php $counter++?>
			<?php }} ?>
        </table><?php $mabilis_ttl=1535705325; $mabilis_last_modified=1530515210; //D:\server\www\projects\articler.img\/templates//ruautor/widgets/top_authors.tpl ?>