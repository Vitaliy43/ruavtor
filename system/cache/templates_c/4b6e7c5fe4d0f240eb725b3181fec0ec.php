<?php if($tree): ?>
    <ul >
        <?php if(is_true_array($tree)){ foreach ($tree as $item){ ?>
        <li ><a style="display:block;width:100%;"><div style="display:block;width:100%;"><div onclick="cats_options(<?php echo $item['id']; ?>,'<?php if(isset($page_lang)){ echo $page_lang; } ?>');" ><?php echo $item['name']; ?></div>
<img onclick="edit_category(<?php echo $item['id']; ?>); return false;" class="penedit" src="<?php if(isset($THEME)){ echo $THEME; } ?>/images/tree/edit_dir.png" align="right" border="0" alt="<?php echo lang ('a_change_cat_data'); ?>" title="<?php echo lang ('a_change_cat_data'); ?>">
<img onclick="ajax_div('page', base_url + 'admin/categories/create_form/<?php echo $item['id']; ?>'); return false;" class="penedit" src="<?php if(isset($THEME)){ echo $THEME; } ?>/images/tree/add_subdir.png" align="right" border="0" alt="<?php echo lang ('a_add_subcat'); ?>" title="<?php echo lang ('a_add_subcat'); ?>">
<img onclick="ajax_div('page', base_url + 'admin/pages/index/category/<?php echo $item['id']; ?>'); return false;" class="penedit" src="<?php if(isset($THEME)){ echo $THEME; } ?>/images/tree/add_page.png" align="right" border="0" alt="<?php echo lang ('a_add_article'); ?>" title="<?php echo lang ('a_add_article'); ?>">
</div></a>  <?php if($item['subtree']): ?>
                <?php $this->view("cats_tree_css.tpl", array('tree' => $item['subtree'], 'page_lang' => $page_lang, 'THEME' => $THEME)); ?>
            <?php endif; ?>
            </li>
        <?php }} ?>
    </ul>
<?php endif; ?>
<?php $mabilis_ttl=1538465350; $mabilis_last_modified=1450852413; //D:\server\www\projects\articler.img\/templates/administrator/cats_tree_css.tpl ?>