<form action="{site_url('avtor/profile')}" method="POST" onsubmit="validate_author_profile();return false;" id="author_profile_form">
	<input type="hidden" name="update" value="1" />
	{if isset($updated)}
		<div>Профиль обновлен!</div>
	{/if}
	
	{if isset($set_name)}
		<div>Для добавления материала необходимо указать имя и фамилию.</div>
	{/if}
	
	<div>Имя</div>
	<div>
		<input type="text" name="name" id="name" value="{$name}"/>
	</div>
	
	<div>Фамилия</div>
	<div>
		<input type="text" name="family" id="family" value="{$family}"/>
	</div>
	
	<div style="margin-top:10px;">
		<input type="submit" value="Ввод"/>
	</div>
	{form_csrf()}
</form>
