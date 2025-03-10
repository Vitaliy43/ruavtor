<?php $finance_for = lang('finance_for');
	$finance_for = str_replace('%login%',$login,$finance_for);
?>
<h2><?php if(isset($finance_for)){ echo $finance_for; } ?></h2>
<input type="hidden" id="user_id" value="<?php if(isset($user_id)){ echo $user_id; } ?>"/>
<table width="40%">
<tr>
	<td><?php echo lang ('earned_total'); ?>:</td>
	<td><?php if(isset($all_earn)){ echo $all_earn; } ?> <?php echo lang ('money'); ?></td>
</tr>
<tr>
	<td><?php echo lang ('paidout_total'); ?>:</td>
	<td><?php if(isset($all_paid)){ echo $all_paid; } ?> <?php echo lang ('money'); ?></td>
</tr>
<tr>
	<td><?php echo lang ('remaining_balance'); ?>:</td>
	<td><?php if(isset($remains)){ echo $remains; } ?> <?php echo lang ('money'); ?></td>
</tr>
<tr>
	<td><?php echo lang ('amount_due'); ?>:</td>
	<td id="enable_payout"><?php if(isset($available_for_pay)){ echo $available_for_pay; } ?> <?php echo lang ('money'); ?></td>
</tr>
</table>

<?php if($available_for_pay >= $lowest_sum_for_pay): ?>
	<div id="ajax_load"><a href="<?php echo site_url ('articler/order_payout'); ?>" onclick="order_payout(this);return false;"><?php echo lang ('request_payment'); ?></a></div>
<?php endif; ?><?php $mabilis_ttl=1538466134; $mabilis_last_modified=1530444531; //D:\server\www\projects\articler.img\/templates//main/author_finance.tpl ?>