<div id="titleExt">
<!--h5>{widget('path')}<span class="ext">{lang('lang_register')}</span></h5-->
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
        <label for="username" class="left">{lang('lang_login')}</label>
        <input type="text" name="username" id="username" value="{set_value('username')}" style="width:250px;"/>
    </div>
	
	<div class="textbox_spacer"></div>
	
    <div class="textbox">
        <label for="email" class="left">{lang('lang_email')}</label>
        <input type="text" name="email" id="email" value="{set_value('email')}" style="width:250px;"/>
    </div>

    <div class="textbox">
        <label for="password" class="left">{lang('lang_password')}</label>
        <input type="password" name="password" id="password" value="{set_value('password')}" style="width:240px;"/>
    </div>
	
	<div class="textbox_spacer"></div>

    <div class="textbox"
        <label for="confirm_password" class="left">Подтверждение</label>
        <input type="password" class="text" name="confirm_password" id="confirm_password" style="width:190px;"/>
    </div>
	</div>
    
{if $cap_image}
   
    {if $this->CI->config->item('DX_use_recaptcha') == FALSE}
        <div class="textbox">
        <table width="200" cellpadding="0" cellspacing="0">
        	<tr>
        		<td align="left"><label for="captcha" class="left">{$cap_image}</label></td>
        		<td><div class="textbox captcha" style="margin-bottom: 5px;">
        			<input type="text" name="captcha" id="captcha" value="Код протекции" onfocus="if(this.value=='Код протекции') this.value='';" onblur="if(this.value=='') this.value='Код протекции';"/>
   				</div></td>
        	</tr>
        </table>
   		</div>
   	{else:}
   	 <div class="comment_form_info">
    	{$cap_image}
       </div>
    {/if}
    {/if}
 
    <p class="clear">
        <label for="submit" class="left"></label> 
        <input type="submit" id="submit" class="submit" value="{lang('lang_submit')}" />
    </p>

	
    <label class="left">&nbsp;</label> 
    <a href="{site_url($modules.auth . '/forgot_password')}">{lang('lang_forgot_password')}</a>
    &nbsp;
    <a href="{site_url('auth/login')}">Вход</a>

{form_csrf()}
</form>

