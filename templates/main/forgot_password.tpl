<div id="titleExt"><h5>{widget('path')}<span class="ext">{lang('articler_forgot_password')}</span></h5></div>

{if validation_errors() OR $info_message}
    <div class="errors"> 
        {validation_errors()}
        {$info_message}
    </div>
{/if}

<form action="" class="form" method="post">

	<div class="comment_form_info">
	
    <div class="textbox">
        <input type="text" size="30" name="login" id="login" value="{lang('articler_username_or_mail')}" onfocus="if(this.value=='{lang('articler_username_or_mail')}') this.value='';" onblur="if(this.value=='') this.value='{lang('articler_username_or_mail')}';" />
    </div>
	
	<br /><br /><br /><br />
    <input type="submit" id="submit" class="submit" value="{lang('articler_submit')}" />
	
    <br/><br />
	</div>
    <label class="left">&nbsp;</label> 
    <a href="{site_url('auth/login')}">{lang('enter')}</a>
    &nbsp;
    <a href="{site_url($modules.auth . '/register')}">{lang('articler_register')}</a>

{form_csrf()}
</form>
