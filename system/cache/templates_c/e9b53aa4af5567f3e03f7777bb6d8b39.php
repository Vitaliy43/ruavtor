<div class="top-navigation">
    <ul>
        <li style="padding:5px;"><input type="button" class="button_silver_130" onclick="ajax_div('page', base_url + 'admin/widgets_manager/create_tpl'); return false;" value="<?php echo lang ('a_create_widget'); ?>" /></li>
    </ul>
</div>
<div class="form_overflow"></div>

<?php if($widgets): ?>
<div id="sortable">
		  <table id="widgets_table" >
		  	<thead>
                <th width="5px" axis="number"><?php echo lang ('a_id'); ?></th>
                <th axis="string"><?php echo lang ('a_n'); ?></th>
                <th axis="string"><?php echo lang ('a_type'); ?></th>
                <th axis="string"><?php echo lang ('a_desc'); ?></th>
                <th axis="date"><?php echo lang ('a_cr'); ?></th>
                <th></th>
			</thead>
			<tbody>
            <?php if(is_true_array($widgets)){ foreach ($widgets as $widget){ ?>
    		<tr>
                <td><?php echo $widget['id']; ?></td>
                <td <?php if($widget['config']  == TRUE): ?> onclick="edit_widget(<?php echo $widget['id']; ?>);" <?php endif; ?>  <?php if($widget['type']  == 'html'): ?> onclick="edit_widget_html(<?php echo $widget['id']; ?>);" <?php endif; ?> ><?php echo $widget['name']; ?></td>
                <td>
                    <?php switch(  $widget['type']  ){ default: break; ?>
                        <?php case 'module':?>
                            <?php echo lang ('a_module'); ?> <?php echo $widget['data']; ?>
                        <?php break?>
                        <?php case 'html':?>
                            <?php echo lang ('a_html'); ?>
                        <?php break?>
                    <?php } ?>
                </td>
                <td><?php echo $widget['description']; ?></td>
                <td><?php echo date ('d-m-Y', $widget['created'] ); ?></td>
                <td align="right">
                    <?php if($widget['config']  == TRUE): ?>
                        <img src="<?php if(isset($THEME)){ echo $THEME; } ?>/images/edit.png" title="<?php echo lang ('a_change_data'); ?>" onclick="ajax_div('page', base_url + 'admin/widgets_manager/edit_module_widget/<?php echo $widget['id']; ?>'); return false;" style="cursor:pointer;" />
                        <img src="<?php if(isset($THEME)){ echo $THEME; } ?>/images/module_admin.png" title="<?php echo lang ('a_widget_sett'); ?>" onclick="edit_widget(<?php echo $widget['id']; ?>); return false;" style="cursor:pointer;" />
                    <?php endif; ?>
                    <?php if($widget['type']  == 'html'): ?>
                        <img src="<?php if(isset($THEME)){ echo $THEME; } ?>/images/edit.png" title="<?php echo lang ('a_sett'); ?>" onclick="edit_widget_html(<?php echo $widget['id']; ?>); return false;" style="cursor:pointer;" />
                    <?php endif; ?>

                    <img src="<?php if(isset($THEME)){ echo $THEME; } ?>/images/delete.png"  onclick="confim_delete_widget('<?php echo $widget['name']; ?>');" title="<?php echo lang ('a_delete'); ?>"  style="cursor:pointer;" />
                </td>
      		</tr>
            <?php }} ?>
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</tfoot>
		  </table>
</div>
            <script type="text/javascript">
                window.addEvent('domready', function(){
                    widgets_table = new sortableTable('widgets_table', {overCls: 'over', sortOn: -1 ,onClick: function(){}});
                    widgets_table.altRow();
                });
            </script>
    

<?php endif; ?><?php $mabilis_ttl=1533724547; $mabilis_last_modified=1450852413; //D:\server\www\projects\articler.img\/templates/administrator/widgets_list.tpl ?>