<?php include ('D:\server\www\projects\articler.img\application\libraries\mabilis/functions/func.truncate.php');  ?><!-- Top search panel -->
<div class="top-navigation">
    <div style="float:left;">
        <div style="padding-left:10px;">
            <form style="width:100%;" onsubmit="return false;" method="post" action="<?php if(isset($BASE_URL)){ echo $BASE_URL; } ?>admin/admin_search" id="g_search_form">
                <input type="text" value="<?php echo lang ('a_search_pages'); ?>..." name="search_text" class="textbox_long" onclick="if (this.value=='<?php echo lang ('a_search_pages'); ?>...') this.value='';" onblur="if (this.value=='') this.value='<?php echo lang ('a_search_pages'); ?>...';" />
                <input type="submit" value="Search" class="search_submit" onclick="ajax_form('g_search_form', 'page');"/>

                <a href="javascript:ajax_div('page', base_url + 'admin/admin_search/advanced_search')"><?php echo lang ('a_advanced_search'); ?></a>
            </form>
        </div>
    </div>

    <div align="right" style="padding:7px 13px;">

        <input type="button" class="button_silver_130" value="<?php echo lang ('a_create_page'); ?>" onclick="ajax_div('page', base_url + 'admin/pages/index'); return false;" />
        <span style="padding:5px;"></span>
        <input type="button" class="button_silver_130" value="<?php echo lang ('a_create_cat'); ?>" onclick="ajax_div('page', base_url + 'admin/categories/create_form'); return false;" />

    </div>
</div>
    <script>
        function edit_comment(id)
        { 
            new MochaUI.Window({
                id: 'edit_comment_window',
                title: 'Редактирование комментария',
                loadMethod: 'xhr',
                contentURL: base_url + 'admin/components/cp/comments/edit/' + id + '/dashboard',
                width: 500,
                height: 280
            });
        }
    </script>


<div id="board_content">

    <div style="width:70%;float:left;margin-right:20px;margin-left:5px; ">
        <div style="background-color: #BBD45F; padding:10px; font-weight:bold;">
            <img src="<?php if(isset($THEME)){ echo $THEME; } ?>/images/documents-text.png" width="16" height="16" align="top" style="padding-right:15px;" />
            <?php echo lang ('a_pages'); ?>
        </div>
        <div>

            <h3 style="padding:3px;margin-top:5px;"><?php echo lang ('a_new_pages'); ?></h3>         
            <table class="s_table" width="100%" cellpadding="3">
                <tbody>
                    <?php if(is_true_array($latest)){ foreach ($latest as $l){ ?>
                        <tr>
                            <td><a href="#" onclick="ajax_div('page','<?php if(isset($BASE_URL)){ echo $BASE_URL; } ?>admin/pages/edit/<?php echo $l['id']; ?>'); return false;" ><?php echo func_truncate ( $l['title'] , 40, '...'); ?></a></td>
                            <td><a href="#" onclick="cats_options(<?php echo $l['category']; ?>); return false;" ><?php echo func_truncate (get_category_name( $l['category'] ), 20, '...'); ?></a></td>
                            <td>
                                <a href="<?php if(isset($BASE_URL)){ echo $BASE_URL; } ?><?php echo $l['cat_url']; ?><?php echo $l['url']; ?>" target="_blank"><?php echo func_truncate ( $l['url'] , 20, '...'); ?></a> 
                            </td>
                            <td><?php echo date ('Y-m-d H:i:s', $l['created']); ?></td>
                            <td align="right">
                                <img onclick="ajax_div('page','<?php if(isset($BASE_URL)){ echo $BASE_URL; } ?>admin/pages/edit/<?php echo $l['id']; ?>/<?php echo $l['lang']; ?>');" style="cursor:pointer" src="<?php if(isset($THEME)){ echo $THEME; } ?>/images/edit_page.png" width="16" height="16" title="<?php echo lang ('a_edit'); ?>" /> 
                            </td>
                        </tr>
                    <?php }} ?>
                </tbody>
            </table>

            <h3 style="padding:3px;margin-top:5px;"><?php echo lang ('a_updated_pages'); ?></h3>         
            <table class="s_table" width="100%" cellpadding="3">
                <tbody>
                    <?php if(is_true_array($updated)){ foreach ($updated as $l){ ?>
                        <tr>
                            <td><a href="#" onclick="ajax_div('page','<?php if(isset($BASE_URL)){ echo $BASE_URL; } ?>admin/pages/edit/<?php echo $l['id']; ?>'); return false;" ><?php echo func_truncate ( $l['title'] , 40, '...'); ?></a></td>
                            <td><a href="#" onclick="cats_options(<?php echo $l['category']; ?>); return false;" ><?php echo func_truncate (get_category_name( $l['category'] ), 20, '...'); ?></a></td>
                            <td>
                                <a href="<?php if(isset($BASE_URL)){ echo $BASE_URL; } ?><?php echo $l['cat_url']; ?><?php echo $l['url']; ?>" target="_blank"><?php echo func_truncate ( $l['url'] , 20, '...'); ?></a> 
                            </td>
                            <td><?php echo date ('Y-m-d H:i:s', $l['updated']); ?></td>
                            <td align="right">
                                <img onclick="ajax_div('page','<?php if(isset($BASE_URL)){ echo $BASE_URL; } ?>admin/pages/edit/<?php echo $l['id']; ?>/<?php echo $l['lang']; ?>');" style="cursor:pointer" src="<?php if(isset($THEME)){ echo $THEME; } ?>/images/edit_page.png" width="16" height="16" title="<?php echo lang ('a_edit'); ?>" /> 
                            </td>
                        </tr>
                    <?php }} ?>                     
                </tbody>
            </table>
        </div>

    </div>

    <div style="float:left; width:25%; ">

        <div class="l_box" style="background-color: #ACBFC5; border-top:0px;">
            <img src="<?php if(isset($THEME)){ echo $THEME; } ?>/images/application-browser.png" width="16" height="16" align="top" style="padding-right:15px;" />
            <b><?php echo lang ('a_system'); ?></b>
        </div>

        <div class="l_box">
            <?php echo lang ('a_version'); ?>: <?php if(isset($cms_number)){ echo $cms_number; } ?> <br />
            <?php if($sys_status['is_update']  == TRUE): ?>
                <a href="#" onclick="ajax_div('page', base_url + 'admin/sys_upgrade');return false;"><?php echo lang ('a_updates_to_version'); ?> <?php if(isset($next_v)){ echo $next_v; } ?></a>
            <?php else:?>
                <?php echo lang ('a_no_updates'); ?>.
            <?php endif; ?>
            <br/>
            <a href="#" onclick="ajax_div('page', base_url + 'admin/sys_info');return false;"><?php echo lang ('a_info'); ?></a> 
        </div>

        <div class="l_box" style="background-color: #ACBFC5; border-top:0px;">
            <img src="<?php if(isset($THEME)){ echo $THEME; } ?>/images/application-list.png" width="16" height="16" align="top" style="padding-right:15px;" />
            <b><?php echo lang ('a_stat'); ?></b>
        </div>

        <div class="l_box">
            <?php echo lang ('a_pages'); ?>: <?php if(isset($total_pages)){ echo $total_pages; } ?> <br />
            <?php echo lang ('a_cats'); ?>: <?php if(isset($total_cats)){ echo $total_cats; } ?> <br />
            <?php echo lang ('a_comments'); ?>: <?php if(isset($total_comments)){ echo $total_comments; } ?> <br />
        </div>

        <div class="l_box" style="background-color: #ACBFC5;">
            <img src="<?php if(isset($THEME)){ echo $THEME; } ?>/images/balloon.png" width="16" height="16" align="top" style="padding-right:15px;" />
            <b><?php echo lang ('a_last_comm'); ?></b>
        </div>

        <div class="l_box">
            <?php if(is_true_array($comments)){ foreach ($comments as $c){ ?>
                <div class="d_comment" onclick="edit_comment(<?php echo $c['id']; ?>);">
                    <span style="font-size:11px;"><?php echo date ('d-m-Y H:i',  $c['date'] ); ?></span>
                    <br/>
                    <i><?php echo $c['user_name']; ?>:</i> <?php echo func_truncate ( $c['text'] , 50, '...'); ?>
                    <hr style="border-top:1px solid #A5A5A5;" />
                </div>
            <?php }} ?>
        </div>
        
        <?php ($hook = get_hook('admin_tpl_dashboard_sidebar')) ? eval($hook) : NULL;?>
        
        
        
        <div class="l_box" style="background-color: #ACBFC5;">
            <img src="<?php if(isset($THEME)){ echo $THEME; } ?>/images/balloon.png" width="16" height="16" align="top" style="padding-right:15px;" />
            <b><?php echo lang ('a_cms_news'); ?></b>
        </div>
                        <?php ($hook = get_hook('admin_tpl_dashboard_center')) ? eval($hook) : NULL;?>
             <?php if(count($api_news) > 1): ?>
        <div class="l_box">            
               <?php if(is_true_array($api_news)){ foreach ($api_news as $a){ ?>
                    <span style="font-size:11px;"><?php echo date ('d-m-Y H:i',  $a['publish_date'] ); ?>
                    <a style="padding-left:10px;" target="_blank" href="http://www.imagecms.net/blog/news/<?php echo $a['url']; ?>">>>></a>
                    </span>
                    <br/> <?php echo func_truncate (strip_tags( $a['prev_text'] ), 100); ?>
                    <hr style="border-top:1px solid #A5A5A5;" />
                
            <?php }} ?>
        </div>
 <?php endif; ?>
        

    </div>

</div>
<?php $mabilis_ttl=1538465352; $mabilis_last_modified=1450852414; //D:\server\www\projects\articler.img\/templates/administrator/dashboard.tpl ?>