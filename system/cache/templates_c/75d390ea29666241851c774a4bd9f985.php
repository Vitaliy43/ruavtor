<form action="<?php if(isset($BASE_URL)){ echo $BASE_URL; } ?>admin/settings/save" method="post" id="save_form" style="width:100%;">

    <div id="settings_tabs">

        <h4 title="Настройки"><?php echo lang ('a_sett'); ?></h4>
        <div>
            <div class="form_text"><?php echo lang ('a_site_title'); ?>:</div>
            <div class="form_input"><input type="text" name="title" value="<?php if(isset($site_title)){ echo $site_title; } ?>" class="textbox_long" /></div>
            <div class="form_overflow"></div>

            <div class="form_text"><?php echo lang ('a_short_title'); ?>:</div>
            <div class="form_input"><input type="text" name="short_title" value="<?php if(isset($site_short_title)){ echo $site_short_title; } ?>" class="textbox_long" /></div>
            <div class="form_overflow"></div>

            <div class="form_text"><?php echo lang ('a_desc'); ?>:</div>
            <div class="form_input"><input type="text" name="description" value="<?php if(isset($site_description)){ echo $site_description; } ?>" class="textbox_long" /></div>
            <div class="form_overflow"></div>

            <div class="form_text"><?php echo lang ('a_key_words'); ?>:</div>
            <div class="form_input"><input type="text" name="keywords" value="<?php if(isset($site_keywords)){ echo $site_keywords; } ?>" class="textbox_long" /></div>
            <div class="form_overflow"></div>

            <div class="form_text"><?php echo lang ('a_google_id'); ?>:</div>
            <div class="form_input"><input type="text" name="google_analytics_id" value="<?php if(isset($google_analytics_id)){ echo $google_analytics_id; } ?>" class="textbox_long" />
                <div class="lite">Код должен быть в формате "ua-54545845"</div>
            </div>
            <div class="form_overflow"></div>

            <div class="form_text">G.Webmaster:</div>
            <div class="form_input"><input type="text" name="google_webmaster" value="<?php if(isset($google_webmaster)){ echo $google_webmaster; } ?>" class="textbox_long" /></div>
            <div class="form_overflow"></div>

            <div class="form_text">Я.Вэбмастер:</div>
            <div class="form_input"><input type="text" name="yandex_webmaster" value="<?php if(isset($yandex_webmaster)){ echo $yandex_webmaster; } ?>" class="textbox_long" /></div>
            <div class="form_overflow"></div>

            <div class="form_text"><?php echo lang ('a_editor_theme'); ?>:</div>
            <div class="form_input">
                <select name="editor_theme">
                    <?php if(is_true_array($editor_themes)){ foreach ($editor_themes as $theme => $v){ ?>
                    <option value="<?php if(isset($theme)){ echo $theme; } ?>" <?php if($theme_selected == $theme): ?> selected="selected" <?php endif; ?> ><?php if(isset($v)){ echo $v; } ?></option>
                    <?php }} ?>
                </select> <div class="lite"><?php echo lang ('a_after_reboot'); ?></div>
            </div>
            <div class="form_overflow"></div>

            <div class="form_text"><?php echo lang ('a_tpl'); ?>:</div>

            <div class="form_input">
                <select name="template">
                    <?php if(is_true_array($templates)){ foreach ($templates as $k => $v){ ?>
                    <option value="<?php if(isset($k)){ echo $k; } ?>" <?php if($template_selected == $k): ?> selected="selected" <?php endif; ?> ><?php if(isset($k)){ echo $k; } ?></option>
                    <?php }} ?>
                </select>
            </div>
            <div class="form_overflow"></div>

            <div class="form_text"><?php echo lang ('a_site_shutdown'); ?>:</div>
            <div class="form_input">
                <select name="site_offline">
                    <?php if(is_true_array($work_values)){ foreach ($work_values as $k => $v){ ?>
                    <option value="<?php if(isset($k)){ echo $k; } ?>" <?php if($site_offline == $k): ?> selected="selected" <?php endif; ?> ><?php if(isset($v)){ echo $v; } ?></option>
                    <?php }} ?>
                </select>
            </div>
            <div class="form_overflow"></div>

            <div class="form_text"><?php echo lang ('a_lang_select'); ?></div>
            <div class="form_input">
                <select name="lang_sel">
                    <?php $arr = get_lang_admin_folders()?>
                    <?php if(is_true_array($arr)){ foreach ($arr as $a){ ?>
                    <option value="<?php if(isset($a)){ echo $a; } ?>" <?php if($lang_sel == $a): ?>selected="selected"<?php endif; ?>> <?php echo str_replace('_lang', '', $a)?> <?php if($a == 'english_lang'): ?>(beta)<?php endif; ?> </option>
                    <?php }} ?>
                </select>
            </div>
            <div class="form_overflow"></div>
        </div>

        <h4 title="Настройки"><?php echo lang ('a_main_page'); ?></h4>
        <div>
            <div class="form_text"><?php echo lang ('a_category'); ?>: <input type="radio" name="main_type" value="category" <?php if($main_type == "category"): ?> checked="checked" <?php endif; ?> /> </div>
            <div class="form_input">
                <select name="main_page_cat">
                    <?php $this->view("cats_select.tpl", $this->template_vars); ?>
                </select>
            </div>
            <div class="form_overflow"></div>

            <div class="form_text"><?php echo lang ('a_page'); ?>: <input type="radio" name="main_type" value="page" <?php if($main_type == "page"): ?> checked="checked" <?php endif; ?> /></div>
            <div class="form_input">
                <input type="text" name="main_page_pid" class="textbox_long" style="width:100px" value="<?php if(isset($main_page_id)){ echo $main_page_id; } ?>" /> - <?php echo lang ('a_page_id'); ?>
            </div>
            <div class="form_overflow"></div>

            <div class="form_text"><?php echo lang ('a_module'); ?>: <input type="radio" name="main_type" value="module" <?php if($main_type == "module"): ?> checked="checked" <?php endif; ?> /></div>
            <div class="form_input">
                <select name="main_page_module">
                    <?php if(is_true_array($modules)){ foreach ($modules as $m){ ?>
                    <?php $mData = modules::run('admin/components/get_module_info',$m['name'])?>
                    <?php //if $mData['main_page'] === true?>
                    <option <?php if($m['name'] == $main_page_module): ?>selected="selected"<?php endif; ?> value="<?php echo $m['name']; ?>"><?php echo $mData['menu_name']?></option>
                    <?php ///if?>
                    <?php }} ?>
                </select>
            </div>
            <div class="form_overflow"></div>
        </div>



        <h4 title="SEO"><?php echo lang ('a_meta_tags'); ?></h4>
        <div>

            <div class="form_text"></div>
            <div class="form_input"><b><?php echo lang ('a_print_in_meta_tags'); ?>:</b></div>
            <div class="form_overflow"></div>

            <div class="form_text"><?php echo lang ('a_site_title'); ?></div>
            <div class="form_input">
                <select name="add_site_name">
                    <option value="1" <?php if($add_site_name == "1"): ?>selected="selected"<?php endif; ?>><?php echo lang ('a_yes'); ?></option>
                    <option value="0" <?php if($add_site_name == "0"): ?>selected="selected"<?php endif; ?> ><?php echo lang ('a_no'); ?></option>
                </select>
            </div>

            <div class="form_overflow"></div>

            <div class="form_text"><?php echo lang ('a_cat_name'); ?></div>
            <div class="form_input">
                <select name="add_site_name_to_cat">
                    <option value="1" <?php if($add_site_name_to_cat == "1"): ?>selected="selected"<?php endif; ?>><?php echo lang ('a_yes'); ?></option>
                    <option value="0" <?php if($add_site_name_to_cat == "0"): ?>selected="selected"<?php endif; ?>><?php echo lang ('a_no'); ?></option>
                </select>
            </div>

            <div class="form_overflow"></div>

            <div class="form_text"><?php echo lang ('a_separator'); ?></div>
            <div class="form_input">
                <input type="text" value="<?php if(isset($delimiter)){ echo $delimiter; } ?>" name="delimiter" class="textbox_long" style="width:80px;" />
            </div>

            <div class="form_overflow"></div>

            <div class="form_text"></div>
            <div class="form_input"><b><?php echo lang ('a_page_meta_tags'); ?>:</b></div>
            <div class="form_overflow"></div>

            <div class="form_text"><b><?php echo lang ('a_meta_keywords'); ?></b><br/><?php echo lang ('a_if_not_pointed'); ?>:</div>
            <div class="form_input">
                <select name="create_keywords">
                    <option value="auto" <?php if($create_keywords == "auto"): ?>selected="selected"<?php endif; ?>><?php echo lang ('a_auto_form'); ?></option>
                    <option value="empty" <?php if($create_keywords == "empty"): ?>selected="selected"<?php endif; ?>><?php echo lang ('a_leave_empty'); ?></option>
                </select>
            </div>

            <div class="form_overflow"></div>

            <div class="form_text"><b><?php echo lang ('a_meta_description'); ?></b><br/><?php echo lang ('a_if_not_pointed1'); ?>:</div>
            <div class="form_input">
                <select name="create_description">
                    <option value="auto" <?php if($create_description == "auto"): ?>selected="selected"<?php endif; ?>><?php echo lang ('a_auto_form'); ?></option>
                    <option value="empty" <?php if($create_description == "empty"): ?>selected="selected"<?php endif; ?>><?php echo lang ('a_leave_empty'); ?></option>
                </select>
            </div>



            <div class="form_overflow"></div>


        </div>

    </div>

    <div class="form_text"></div>
    <div class="form_input">
        <input type="submit" name="button" class="button" value="<?php echo lang ('a_save'); ?>" onclick="ajax_me('save_form');" />
    </div>

    <?php echo form_csrf (); ?></form>
<script type="text/javascript">
    var settings_tabs = new SimpleTabs('settings_tabs', {
        selector: 'h4'
    });
</script>

<?php $mabilis_ttl=1537947716; $mabilis_last_modified=1450852413; //D:\server\www\projects\articler.img\/templates/administrator/settings.tpl ?>