<form action="<?php echo site_url ('avtor/profile'); ?>" method="POST" onsubmit="validate_author_profile();return false;" id="author_profile_form">
	<input type="hidden" name="update" value="1" />
	<?php if(isset($updated)): ?>
		<div><?php echo lang ('profile_updated'); ?></div>
	<?php endif; ?>
	
	<?php if(isset($set_name)): ?>
		<div><?php echo lang ('add_material_specify_name'); ?></div>
	<?php endif; ?>
	
	<div><?php echo lang ('name'); ?></div>
	<div>
		<input type="text" name="name" id="name" value="<?php if(isset($name)){ echo $name; } ?>"/>
	</div>
	
	<div><?php echo lang ('surname'); ?></div>
	<div>
		<input type="text" name="family" id="family" value="<?php if(isset($family)){ echo $family; } ?>"/>
	</div>
	
	<div style="margin-top:10px;">
		<input type="submit" value="<?php echo lang ('enter'); ?>"/>
	</div>
	<?php echo form_csrf (); ?>
</form>
<?php $mabilis_ttl=1535556348; $mabilis_last_modified=1530438862; //D:\server\www\projects\articler.img\/templates//ruautor/author_profile.tpl ?>