<div class="rdTreeFirebug">
<ul id="desktop_tree">
<li><a style="display:block;width:100%;"><img class="penedit-root" onclick="ajax_div('page', base_url + 'admin/categories/create_form/0'); return false;"  src="<?php if(isset($THEME)){ echo $THEME; } ?>/images/tree/add_subdir.png" align="right" border="0" alt="<?php echo lang ('a_add_subcat'); ?>" title="<?php echo lang ('a_add_subcat'); ?>">
<img class="penedit-root" onclick="ajax_div('page', base_url + 'admin/pages/index/category/0'); return false;" src="<?php if(isset($THEME)){ echo $THEME; } ?>/images/tree/add_page.png" align="right" border="0" alt="<?php echo lang ('a_add_article'); ?>" title="<?php echo lang ('a_add_article'); ?>">
<div id="root_tree" ondblclick='myTree.expandAll()' onclick="cats_options(0,'');" style="display:inline;" title="<?php echo lang ('a_dbl_click'); ?>"><?php echo $_SERVER['SERVER_NAME']; ?></div>
</a>
<?php $this->view("cats_tree_css.tpl", $data) ?>
</li>
</ul>
</div>
<script>
var myTree = new rdTree('desktop_tree');
myTree.select("root_tree");
</script>
<?php $mabilis_ttl=1538465350; $mabilis_last_modified=1450852413; //D:\server\www\projects\articler.img\/templates/administrator/cats_sidebar.tpl ?>