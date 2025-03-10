<div id="titleExt"><h5>{widget('path')}<span class="ext">{lang('contacts')}</span></h5></div>
<div id="contact">
<div class="left">

{if $form_errors}
    <div class="errors"> 
        {$form_errors}
    </div>
{/if}

{if $message_sent}
     {lang('your_message_sended')}.
{/if}

<form action="{site_url('feedback')}" method="post">
    <div class="textbox">
    <input type="text" id="name" name="name" class="text" value="{if $_POST.name}{$_POST.name}{else:}{lang('your name')}{/if}" onfocus="if(this.value=='{lang('your name')}') this.value='';" onblur="if(this.value=='') this.value='{lang('your name')}';"/>
    </div>

    <div class="textbox">
        <input type="text" id="email" name="email" class="text" value="{if $_POST.email}{$_POST.email}{else:}Email{/if}" onfocus="if(this.value=='Email') this.value='';" onblur="if(this.value=='') this.value='Email';"/>
    </div>

    <div class="textbox">
        <input type="text" id="theme" name="theme" class="text" value="{if $_POST.theme}{$_POST.theme}{else:}{lang('articler_theme')}{/if}" onfocus="if(this.value=='{lang('articler_theme')}') this.value='';" onblur="if(this.value=='') this.value='{lang('articler_theme')}';"/>
    </div>

    <div class="textbox">
        <textarea cols="45" rows="10" name="message" id="message" onfocus="if(this.value=='{lang('articler_message_text')}') this.value='';" onblur="if(this.value=='') this.value='{lang('articler_message_text')}';">{if $_POST.message}{$_POST.message}{else:}{lang('articler_message_text')}{/if}</textarea>
    </div>
    
   	<div class="comment_form_info">
	{if $captcha_type =='captcha'}    
    	<div class="textbox captcha">
	    <input type="text" name="captcha" id="recaptcha_response_field" value="{lang('articler_captcha')}" onfocus="if(this.value=='{lang('articler_captcha')}') this.value='';" onblur="if(this.value=='') this.value='{lang('articler_captcha')}';"/>
   	</div>
	{/if}
    {$cap_image}
    </div>
    
    <input type="submit" class="submit" value="{lang('articler_comment_button')}" style="margin-left:5px;margin-top:10px;"/>

    {form_csrf()}
</form>
</div>
<div class="right">
<div id="detail">
<h2 id="title">{lang('articler_contacts')}</h2>
{widget('contacts')}
</div>
</div>
</div>