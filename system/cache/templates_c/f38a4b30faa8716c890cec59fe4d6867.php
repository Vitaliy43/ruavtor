<form action="<?php if(isset($SELF_URL)){ echo $SELF_URL; } ?>/update_author/<?php echo $author['id']; ?>" id="edit_user_form">

<div id="user_edit_block">

        <div class="form_text"><?php echo lang ('amt_author_rating'); ?></div>
	<div class="form_input"><input type="text" name="author_rating" value="<?php echo $author['author_rating']; ?>" class="textbox_long" /></div>
	<div class="form_overflow"></div>

	<div class="form_text"><?php echo lang ('amt_rating_activity'); ?></div>
	<div class="form_input"><input type="text" name="rating_activity" value="<?php echo $author['rating_activity']; ?>" class="textbox_long" /></div>
	<div class="form_overflow"></div>

	<!--div class="form_text"><?php echo lang ('amt_bmp'); ?></div>
	<div class="form_input"><input type="text" name="score" value="<?php echo $author['score']; ?>" class="textbox_long" /></div>
	<div class="form_overflow"></div-->
	
	
	<?php if(isset( $author['sum_statistic'] )): ?>
	<div class="form_text"><?php echo lang ('amt_statistic'); ?></div>
	<div class="form_input"><?php echo $author['sum_statistic']; ?><span class="correct_statistic" title="Кол-во просмотров, видимое пользователям"><?php if(isset( $author['correct_sum_statistic'] )): ?> (<?php echo $author['correct_sum_statistic']; ?>)<?php endif; ?></span></div>
	<div class="form_overflow"></div>
	<?php endif; ?>
	
	<?php if(isset( $author['payments'] )): ?>
	<div class="form_text"><?php echo lang ('amt_edit_payments'); ?></div>
	<div class="form_input"><input type="text" name="payments" value="<?php echo $author['payments']; ?>" class="textbox_long" /></div>
	<div class="form_overflow"></div>
	<?php endif; ?>
	
	<?php if(isset( $author['payouts'] )): ?>
	<div class="form_text"><?php echo lang ('amt_edit_payouts'); ?></div>
	<div class="form_input"><input type="text" name="payouts" value="<?php echo $author['payouts']; ?>" class="textbox_long" /></div>
	<div class="form_overflow"></div>
	<?php endif; ?>

	<div class="form_text"></div>
	<div class="form_input">
		<input type="submit" name="button" class="button" value="<?php echo lang ('amt_save'); ?>" onclick="ajax_me('edit_user_form');" />
		<input type="submit" name="button" class="button" value="<?php echo lang ('amt_cancel'); ?>" onclick="MochaUI.closeWindow($('user_edit_window')); return false;" />
	</div>
	<div class="form_overflow"></div>

</div>
<?php echo form_csrf (); ?></form>
<?php $mabilis_ttl=1535706455; $mabilis_last_modified=1489492616; //D:\server\www\projects\articler.img\application\modules\articler/templates/edit_author.tpl ?>