
<?php if(isset($is_moderator) and $is_moderator == 1 and empty($show)): ?>
	<form action="<?php echo site_url ('moderator/resolution'); ?>/<?php if(isset($id)){ echo $id; } ?>" id="moderate_form" method="POST" enctype="multipart/form-data">
<?php endif; ?>
<?php if($activity == 2): ?>
	<p><em><?php echo time_convert_date_to_text ($data_published,$main_language); ?></em></p>
<?php else:?>
	<p><em><?php echo time_convert_date_to_text ($data_saved,$main_language); ?></em></p>
<?php endif; ?>
<?php if(isset($is_select_headings)): ?>
<label class="field-wrap">Рубрика<span class="select-mark">▼</span>
	<?php if(isset($headings)){ echo $headings; } ?>
</label>
<?php endif; ?>

<?php if(isset($is_moderator) and $is_moderator == 1): ?>
<style type="text/css">
.article_content {border: 1px solid #8C7E7E;}
</style>
<script type="text/javascript">

$(function() {
	$('#change_url').hide();
	$("#link_change_url").click(function() {
		$('#change_url').slideToggle('normal');
		return false;
});
});
</script>

<input type="hidden" name="article_id" value="<?php if(isset($id)){ echo $id; } ?>"/>
<?php if(isset($rating_for_homefeed)): ?>
<input type="hidden" name="rating_for_homefeed" id="rating_for_homefeed" value="<?php if(isset($rating_for_homefeed)){ echo $rating_for_homefeed; } ?>"/>
<?php endif; ?>

<?php endif; ?>
<?php if(isset($header) && $is_moderator == 1 && isset($is_select_headings)): ?>
<label class="field-wrap" style="margin-bottom: 2px;"><?php echo lang ('heading'); ?>
<?php if(isset( $errors['header'] )): ?>
&nbsp;<?php echo $errors['header']; ?>
<?php endif; ?>
</label>
<?php if($activity != 2): ?>
	<input type="text" name="header" id="publisher_header" onchange="translit();" value="<?php if(isset($header)){ echo $header; } ?>"/>
<?php else:?>
	<input type="text" name="header" id="publisher_header" value="<?php if(isset($header)){ echo $header; } ?>"/>
<?php endif; ?>
<?php endif; ?>

<?php if(isset($url) && $activity != 2): ?>
<div>URL
<?php if(isset( $errors['url_empty'] )): ?>
&nbsp;<?php echo $errors['url_empty']; ?>
<?php elseif (isset( $errors['url_wrong'] ) ): ?>
&nbsp;<?php echo $errors['url_wrong']; ?>
<?php elseif (isset( $errors['url_exists'] ) ): ?>
&nbsp;<?php echo $errors['url_exists']; ?>
<?php endif; ?>
</div>

<?php if($url != ''): ?>
<input type="text" name="url" id="url" value="<?php if(isset($url)){ echo $url; } ?>"/>
<?php else:?>
<input type="text" name="url" id="url"/>
<?php endif; ?>

<?php elseif (isset($url) && $activity == 2): ?>

<?php if(isset($is_moderator)): ?>
<div style="margin-top:-10px;margin-bottom:5px;padding-left:3px;">
	<a href="#change_url" onclick="change_url();return false;" id="link_change_url">
		<?php echo lang ('modify_url'); ?>
	</a>
</div>
<div id="change_url">
<input type="text" name="url" id="url" value="<?php if(isset($url)){ echo $url; } ?>" />
</div>


<?php endif; ?>

<?php endif; ?>
<?php if(isset($author_name)): ?>
	<p style="text-align: left;"><?php echo lang ('author'); ?>: <strong><a href="<?php echo site_url ('avtory'); ?>/<?php if(isset($username)){ echo $username; } ?>" style="color:black;text-decoration:underline;" target="_blank"><?php if(isset($author_name)){ echo $author_name; } ?> <?php if(isset($author_family)){ echo $author_family; } ?></a></strong>
	<?php if(empty($is_select_headings)): ?>
<br />	<?php echo lang ('rubric'); ?>: <strong><a href="<?php echo site_url (''); ?><?php if(isset($headings_url)){ echo $headings_url; } ?>" style="color:black;text-decoration:underline;" target="_blank"><?php if(isset($headings)){ echo $headings; } ?></a></strong>
<?php endif; ?>
	 </p>

<?php else:?>
	<p style="text-align: left;"><?php echo lang ('author'); ?>: <strong><a href="<?php echo site_url ('avtory'); ?>/<?php if(isset($username)){ echo $username; } ?>" style="color:black;text-decoration:underline;" target="_blank"><?php if(isset($username)){ echo $username; } ?></a></strong>
	<?php if(empty($is_select_headings)): ?>
<br />	<?php echo lang ('rubric'); ?>: <strong><?php if(isset($headings)){ echo $headings; } ?></strong>
<?php endif; ?>
<?php endif; ?>
<?php if(isset($rating)): ?>
<p style="text-align: right;margin-top:-10px;" id="article_rating"><?php echo lang ('rating'); ?>: <strong><?php if(isset($rating)){ echo $rating; } ?></strong></p>

<?php endif; ?>

<?php if(isset($num_visites_all) && $num_visites_all > 0): ?>
<div><?php echo lang ('views'); ?>:</div>
<div id="all_num_visites"><?php echo lang ('total_views'); ?> - <b><?php if(isset($num_visites_all)){ echo $num_visites_all; } ?></b></div>
<div id="average_num_visites"><?php echo lang ('views_last_day'); ?> - <b><?php if(isset($num_visites_avg)){ echo $num_visites_avg; } ?></b></div>
<?php endif; ?>
<?php if(isset($is_edited)): ?>
<label class="field-wrap" style="margin-top:5px;"><?php echo lang ('annotation'); ?>
<textarea id="annotation" name="annotation" rows="3" style="width:100%;">
<?php if(isset($annotation)){ echo $annotation; } ?>
</textarea>
</label>
<label class="field-wrap" style="margin-top:5px;"><?php echo lang ('description_meta'); ?>
<textarea id="description" name="description" rows="3" style="width:100%;">
<?php if(isset($description)){ echo $description; } ?>
</textarea>
</label>

<label class="field-wrap" style="margin-top:5px;"><?php echo lang ('keywords_meta'); ?>
<textarea id="keywords" name="keywords" rows="2" style="width:100%;">
<?php if(isset($keywords)){ echo $keywords; } ?>
</textarea>
</label>

<label class="field-wrap"><?php echo lang ('image'); ?>
	<?php if(isset( $errors['upload_image'] )): ?>
		<span><?php echo $errors['upload_image']; ?></span>
	<?php endif; ?>
	<input type="file" name="image"/>
</label>
<div><?php echo lang ('full_text'); ?></div>
<?php endif; ?>
<div class="article_content">
<?php if(isset($content)){ echo $content; } ?>
</div>
<?php if(isset($is_moderator) and $is_moderator == 1): ?>
<br>
<?php if(empty($is_edited) and $activity == 2): ?>

<?php elseif (isset($is_edited) and $activity == 2): ?>
<input type="submit" name="publish" value="<?php echo lang ('modify'); ?>" onclick="validate_moderate_form('<?php if(isset($id)){ echo $id; } ?>','publish');return false;"/>
<input type="hidden" name="is_published" value="1"/>
<?php else:?>

<?php if($activity == 1 and empty($show)): ?>
<div>
<?php if(isset($outer_link)): ?>
<input type="hidden" name="outer_link" id="outer_link" value="<?php echo $outer_link['link']; ?>"/>
<table width="60%" cellspacing="3">
<tr>
<td nowrap=""><?php echo lang ('external_link'); ?>:</td>
<td nowrap=""><a href="<?php echo $outer_link['link']; ?>" target="_blank"><?php echo $outer_link['link']; ?></a></td>
<td align="left" nowrap=""><input type="checkbox" id="enable_link" onclick="set_link();">&nbsp;<?php echo lang ('attach_link'); ?></td>
</tr>
<tr class="pay_for_link">
<?php $buffer = $rating_for_homefeed - 1;
$initial_rating = lang('initial_rating');
$initial_rating = str_replace('%balls%',$buffer,$initial_rating);
?>
<td nowrap=""><?php if(isset($initial_rating)){ echo $initial_rating; } ?></td>
<td><input type="text" id="initial_rating" value="0" name="initial_rating"/>
</td>
<td></td>
</tr>
<tr class="pay_for_link">
<td nowrap=""><?php echo lang ('money_bonus'); ?></td>
<td><input type="text" id="add_score" value="0" name="add_score" /></td>
<td></td>
</tr>
</table>

<?php endif; ?>
<br>
<div style="margin-bottom:10px;">
	<span>
		<input type="checkbox" name="is_special" style="margin-left:-8px;">
	</span>
	<span>
	<?php $bonus_leaving = lang('bonus_leaving_sandbox');
		$bonus_leaving = str_replace('%bonus%',bonus_for_homefeed,$bonus_leaving);
		
	?>
		<?php if(isset($bonus_leaving)){ echo $bonus_leaving; } ?>
	</span>
</div>
<input type="submit" name="publish" value="<?php echo lang ('publish'); ?>" onclick="validate_moderate_form('<?php if(isset($id)){ echo $id; } ?>','publish');return false;"/>
<input type="hidden" name="hidden_publish" id="hidden_publish" value="0"/>
</div><br>
<div>
<input type="submit" name="reject" value="<?php echo lang ('reject_with_statement'); ?>" onclick="validate_moderate_form('<?php if(isset($id)){ echo $id; } ?>','reject');return false;"/>
<input type="hidden" name="hidden_reject" id="hidden_reject" value="0"/>
</div>

<div>
<textarea name="reason" id="moderate_reason"></textarea>
</div>
<?php elseif ($activity == 2 and empty($show)): ?>
<div>
<br><br>
<input type="submit" name="publish" value="<?php echo lang ('modify'); ?>" onclick="validate_moderate_form('<?php if(isset($id)){ echo $id; } ?>','publish');return false;"/>
<input type="hidden" name="hidden_publish" id="hidden_publish" value="1"/>
</div>
<?php endif; ?>

<?php endif; ?>
<?php if(isset($is_moderator) and $is_moderator == 1): ?>
<?php echo form_csrf (); ?>
<?php endif; ?>

</form>
<?php endif; ?>
<?php if(isset($is_moderator) && $is_moderator == 1 && empty($is_edited)): ?>
<div style="margin-bottom:10px;"><a href="<?php echo site_url ('moderator/edit'); ?>/<?php if(isset($id)){ echo $id; } ?>" target="_blank"><?php echo lang ('edit_article'); ?></a></div>
<?php endif; ?>
<?php $mabilis_ttl=1537934903; $mabilis_last_modified=1530434694; //D:\server\www\projects\articler.img\/templates//main/article_moderated.tpl ?>