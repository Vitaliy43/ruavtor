
<script type="text/javascript">
$(document).ready(function(){
	
//		$('.article_header').html('"'+'<?php if(isset($header)){ echo $header; } ?>'+'"');
		//$('.article_header').text(123);
//		myAlert('<?php if(isset($header)){ echo $header; } ?>');
	});
	


</script>

<div class="container_editor">
 <div class="block-title"><?php echo lang ('add_material'); ?></div>

<?php if($id == 0): ?>
<form action="<?php echo site_url ('publish'); ?>" method="POST" onsubmit="validate_publish();return false;" name="form_publish" class="edit-block" enctype="multipart/form-data">
<?php else:?>
<form action="<?php echo site_url ('publish/update'); ?>/<?php if(isset($id)){ echo $id; } ?>" method="POST" onsubmit="validate_publish();return false;" name="form_publish" class="edit-block" enctype="multipart/form-data">
<?php if(isset($outer_link)): ?>
<input type="hidden" name="hidden_outer_link" id="hidden_outer_link" value="<?php if(isset($outer_link)){ echo $outer_link; } ?>"/>
<?php endif; ?>
<input type="hidden" name="article_id" id="article_id" value="<?php if(isset($id)){ echo $id; } ?>"/>
<?php endif; ?>

<label class="field-wrap"><?php echo lang ('rubric'); ?><span class="select-mark">▼</span>
<?php if(isset( $errors['headings'] )): ?>
&nbsp;<?php echo $errors['headings']; ?>
<?php endif; ?>
	<?php if(isset($headings)){ echo $headings; } ?>
</label>

<label class="field-wrap"><?php echo lang ('title'); ?>
<?php if(isset( $errors['header'] )): ?>
&nbsp;<?php echo $errors['header']; ?>
<?php endif; ?>
</label>
<input type="text" name="header" id="publisher_header" onchange="translit();" value="<?php if(isset($header)){ echo $header; } ?>" style="width:75%;"/>
<label class="field-wrap">URL
<?php if(isset( $errors['url_empty'] )): ?>
&nbsp;<?php echo $errors['url_empty']; ?>
<?php elseif (isset( $errors['url_wrong'] ) ): ?>
&nbsp;<?php echo $errors['url_wrong']; ?>
<?php elseif (isset( $errors['url_exists'] ) ): ?>
&nbsp;<?php echo $errors['url_exists']; ?>
<?php endif; ?>
</label>
<?php if($url != ''): ?>
<input type="text" name="url" id="url" value="<?php if(isset($url)){ echo $url; } ?>" style="width:75%;"/>
<?php else:?>
<input type="text" name="url" id="url" style="width:75%;"/>
<?php endif; ?>
<label class="field-wrap"><?php echo lang ('annotation_briefly'); ?>
	<textarea id="annotation" name="annotation" rows="5" cols="30" placeholder="<?php echo lang ('annotation'); ?>"><?php if(isset($annotation)){ echo $annotation; } ?></textarea>
</label>

<label class="field-wrap"><?php echo lang ('description'); ?>
	<textarea id="description" name="description" rows="5" cols="30" placeholder="<?php echo lang ('description_meta'); ?>"><?php if(isset($description)){ echo $description; } ?></textarea>
</label>

<label class="field-wrap"><?php echo lang ('keywords'); ?>
	<textarea id="keywords" name="keywords" rows="5" cols="30" placeholder="<?php echo lang ('keywords_meta'); ?>"><?php if(isset($keywords)){ echo $keywords; } ?></textarea>
</label>
<label class="field-wrap"><?php echo lang ('image'); ?>
	<?php if(isset( $errors['upload_image'] )): ?>
		<span><?php echo $errors['upload_image']; ?></span>
	<?php endif; ?>
	<input type="file" name="image"/>
</label>
<div><?php echo lang ('full_text'); ?></div>
<div id="container_editor">
 <?php if(isset($editor)){ echo $editor; } ?>
</div>
<?php if($type == 'update'): ?>
<div id="container_outer_link">
<?php echo lang ('external_link_to_site'); ?>:
<?php if(isset($outer_link)): ?>
<span id="show_add_outer_link">
<b><?php if(isset($outer_link)){ echo $outer_link; } ?></b>
</span>
<a href="#add_outer_link" style="margin-left:10px;" title="<?php echo lang ('changing_external_link'); ?>" onclick="show_outer_link();"><?php echo lang ('change'); ?></a>
<?php else:?>
<a href="#add_outer_link" style="margin-left:10px;" title="<?php echo lang ('adding_external_link'); ?>" id="show_add_outer_link" onclick="show_outer_link();"><?php echo lang ('add'); ?></a>
<?php endif; ?>
</div>
<?php endif; ?>
<div class="button_publish">
<input type="submit" name="submit" value="<?php echo lang ('save'); ?>"/>
</div>
<?php echo form_csrf (); ?>
</form>
</div>
<?php $mabilis_ttl=1534316893; $mabilis_last_modified=1534232265; //D:\server\www\projects\articler.img\/templates//ruautor/publisher.tpl ?>