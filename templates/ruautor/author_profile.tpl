<form action="{site_url('avtor/profile')}" method="POST" onsubmit="validate_author_profile();return false;" id="author_profile_form">
	<input type="hidden" name="update" value="1" />
	{if isset($updated)}
		<div>{lang('profile_updated')}</div>
	{/if}
	
	{if isset($set_name)}
		<div>{lang('add_material_specify_name')}</div>
	{/if}
	
	<div>{lang('name')}</div>
	<div>
		<input type="text" name="name" id="name" value="{$name}"/>
	</div>
	
	<div>{lang('surname')}</div>
	<div>
		<input type="text" name="family" id="family" value="{$family}"/>
	</div>
	
	<div style="margin-top:10px;">
		<input type="submit" value="{lang('enter')}"/>
	</div>
	{form_csrf()}
</form>
