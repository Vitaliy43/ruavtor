<h1>{lang('register_h1')}</h1>

</br>
<p style="text-align: justify;">Не используйте в качестве логина адрес электронной почты (e-mail)! Правильный читаемый логин должен формироваться следующим образом и может содержать только следующие символы: при создании логина могут использоваться только символы латинского (английского) алфавита (от A до Z - как малые, так и заглавные), цифры 0..9, а также знаки дефис ("-") и подчеркивание ("_") - это все! Никакие другие символы (включая пробелы) в вашем логине участвовать не могут!</p>
</br>

<div id="titleExt">
<!--h5>{widget('path')}<span class="ext">{lang('articler_register')}</span></h5-->
</div>

{if validation_errors() OR $info_message}
    <div class="errors"> 
        {validation_errors()}
        {$info_message}
    </div>
{/if}

<form action="" class="form" method="post">
	<div class="registration">
	
	<div class="textbox">
        <label for="username" class="left">{lang('articler_login')}</label>
        <input type="text" name="username" id="username" value="{set_value('username')}" style="width:250px;"/>
    </div>
	
	<div class="textbox_spacer"></div>
	
    <div class="textbox">
        <label for="email" class="left">{lang('articler_email')}</label>
        <input type="text" name="email" id="email" value="{set_value('email')}" style="width:250px;"/>
    </div>

    <div class="textbox">
        <label for="password" class="left">{lang('articler_password')}</label>
        <input type="password" name="password" id="password" value="{set_value('password')}" style="width:240px;"/>
    </div>
	
	<div class="textbox_spacer"></div>

    <div class="textbox"
        <label for="confirm_password" class="left">{lang('confirm_password')}</label>
        <input type="password" class="text" name="confirm_password" id="confirm_password" style="width:190px;"/>
    </div>
	</div>
    
{if $cap_image}
<table>
	<tr>
		<td>
			<label for="captcha">{$cap_image}</label>
		</td>
		<td>
			<input type="text" name="captcha" id="captcha" value="{lang('captcha')}" onfocus="if(this.value=='{lang('captcha')}') this.value='';" onblur="if(this.value=='') this.value='{lang('captcha')}';"/>
		</td>
	</tr>
</table>
	<!--div class="textbox">
		
	</div-->
    <!--div class="comment_form_info">
	 <div class="textbox captcha">
        
   	</div>
    </div-->
    {/if}
 
    <p class="clear">
        <label for="submit" class="left"></label> 
        <input type="submit" id="submit" class="submit" value="{lang('articler_submit')}" />
    </p>

	
    <label class="left">&nbsp;</label> 
    <a href="{site_url($modules.auth . '/forgot_password')}">{lang('articler_forgot_password')}</a>
    &nbsp;
    <a href="{site_url('auth/login')}">{lang('enter')}</a>

{form_csrf()}
</form>
</br>
<hr>
</br>
{lang('register_message_2')}