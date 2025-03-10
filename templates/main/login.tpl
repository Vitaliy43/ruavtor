<div id="titleExt">
<!--h5>{widget('path')}<span class="ext">{lang('articler_login_page')}</span></h5-->
</div>

{if validation_errors() OR $info_message}
    <div class="errors"> 
        {validation_errors()}
        {$info_message}
    </div>
{/if}

<form action="" method="post" class="form">

	<div class="registration">
   
	<div class="textbox">
		<label for="username" class="left">{lang('articler_login')}</label>
        <input type="text" id="username" size="30" name="username" value="{lang('enter_your_login')}" onfocus="if(this.value=='{lang('enter_your_login')}') this.value='';" onblur="if(this.value=='{lang('enter_your_login')}') this.value='{lang('enter_your_login')}';" />
    </div>
	
	<div class="textbox_spacer"></div>

    <div class="textbox">
        <label for="password" class="left">{lang('articler_password')}</label> 
        <input type="password" size="30" name="password" id="password" value="{lang('articler_password')}" onfocus="if(this.value=='{lang('articler_password')}') this.value='';" onblur="if(this.value=='') this.value='{lang('articler_password')}';"/>
    </div>
	</div>

    {if $cap_image}
    <div class="comment_form_info">
    <div class="textbox captcha">
        <input type="text" name="captcha" id="captcha" value="{lang('captcha')}" onfocus="if(this.value=='{lang('captcha')}') this.value='';" onblur="if(this.value=='') this.value='{lang('captcha')}';"/>
   	</div>
    {$cap_image}
    </div>
    {/if}

    <p class="clear">
        <label><input type="checkbox" name="remember" value="1" id="remember" /> {lang('articler_remember_me')}</label>
    </p>

    <input type="submit" id="submit" class="submit" value="{lang('articler_submit')}" /> 
	
	
    <br /><br />

    <label class="left">&nbsp;</label> 
    <a href="{site_url($modules.auth . '/forgot_password')}">{lang('articler_forgot_password')}</a>
    &nbsp;
    <a href="{site_url($modules.auth . '/register')}">{lang('articler_register')}</a>

{form_csrf()}
</form>
