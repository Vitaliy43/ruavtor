<div style="margin-left:5px;">
<div>
	<h3><?php echo lang ('generator_referral_link'); ?></h3>
<div><label><?php echo lang ('initial_link'); ?>:</label><input type="text" id="url_before" maxlength="200" style="width:300px;margin-left: 20px;"/>
<button value="generate" onclick="generate_refer('<?php echo site_url (); ?>',<?php if(isset($user_id)){ echo $user_id; } ?>);return false;"><?php echo lang ('generate'); ?></button>
</div>
<div style="margin-top: 10px;"><label><?php echo lang ('after_link'); ?>:</label><input type="text" id="url_after" maxlength="200" style="width:420px;" readonly="" onclick="autoselect(this);"/></div>
</div>
<div id="generator_result"></div>
<?php if(isset($days_refer)): ?>
<?php $tills_end_grace_period = lang('tills_end_grace_period');
	$tills_end_grace_period = str_replace('%days%',$days_refer,tills_end_grace_period);
?>
	<div><?php if(isset($tills_end_grace_period)){ echo $tills_end_grace_period; } ?></div>
<?php endif; ?>
<input type="hidden" id="user_id" value="<?php if(isset($user_id)){ echo $user_id; } ?>"/>
<?php if(count($refers) > 0): ?>
<table width="40%" style="margin-top: 20px;">
<tr>
	<th><?php echo lang ('referral'); ?></th>
	<th><?php echo  ('date'); ?></th>
</tr>
<?php if(is_true_array($refers)){ foreach ($refers as $refer){ ?>
<tr>
	<td><a href="<?php echo site_url ('avtory'); ?>/<?php echo $refer['username']; ?>" target="_blank"><?php echo $refer['username']; ?></a></td>
	<td><?php echo time_change_show_data ( $refer['data'] ); ?></td>
</tr>
<?php }} ?>
</table>
<?php endif; ?>
</div><?php $mabilis_ttl=1537534698; $mabilis_last_modified=1530439971; //D:\server\www\projects\articler.img\/templates//ruautor/author_refers.tpl ?>