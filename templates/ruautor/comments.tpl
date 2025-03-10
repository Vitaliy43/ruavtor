{if $comments_arr}
<div id="detail">
    <h3>{$total_comments}</h3>
    {$counter = 1}
    {foreach $comments_arr as $comment}
    <div id="comment_{$comment.id}" class="comment{if $counter == 2} next_row{$counter = 0}{/if}" >
        <div class="comment_info">
            <b>{$comment.author}</b>
            <span>{date('d.m.Y H:i', $comment.date)}</span>
        </div>
		<div class="comment_text">
            {$comment.text}
        </div>
            
    </div>
    {$counter++}
    {/foreach}
</div>
{/if}

<div id="detail">
<h3>{lang('articler_post_comment')}</h3>

{if $comment_errors}
    <div class="errors"> 
        {$comment_errors}
    </div>
{/if}

{if $can_comment === 1 AND !is_logged_in}
     <p>{sprintf(lang('articler_login_for_comments'), site_url($modules.auth))}</p>
{/if}

<form action="" method="post" class="form">
    <input type="hidden" name="comment_item_id" value="{$item_id}" />
    <input type="hidden" name="redirect" value="{uri_string()}" />

    {if $is_logged_in} 
        <p>{lang('articler_logged_in_as')} {$username}. <a href="{site_url('auth/logout')}">{lang('articler_logout')}</a></p>         
    {else:}
	
    <div class="comment_form_info">
    
    <div class="textbox">
        <input type="text" name="comment_author" id="comment_author" value="{if $_POST['comment_author']}{$_POST['comment_author']}{else:}{lang('articler_name')}{/if}" onfocus="if(this.value=='{lang('articler_name')}') this.value='';" onblur="if(this.value=='') this.value='{lang('articler_name')}';" />
    </div>
    
    <div class="textbox_spacer"></div>
       
    <div class="textbox">
        <input type="text" name="comment_email" id="comment_email" value="{if $_POST['comment_email']}{$_POST['comment_email']}{else:}Email{/if}" onfocus="if(this.value=='Email') this.value='';" onblur="if(this.value=='') this.value='Email';" />
    </div>
    
    </div>

    {/if}

    <div class="textbox">
        <textarea name="comment_text" id="comment_text" rows="10" cols="50" onfocus="if(this.value=='{lang('articler_comment_text')}') this.value='';" onblur="if(this.value=='') this.value='{lang('articler_comment_text')}';">{if $_POST['comment_text']}{$_POST['comment_text']}{else:}{lang('articler_comment_text')}{/if}</textarea>
    </div>
	
    
    {if !$is_logged_in} 
    
    {if $use_captcha}
    <div class="comment_form_info">
    <div class="textbox captcha">
        <input type="text" name="captcha" id="captcha" value="{lang('articler_captcha')}" onfocus="if(this.value=='{lang('articler_captcha')}') this.value='';" onblur="if(this.value=='') this.value='{lang('articler_captcha')}';"/>
   	</div>
    {$cap_image}
    </div>
    {/if}
    
    {/if}
	<input type="submit" class="submit" value="{lang('articler_comment_button')}" />

    {form_csrf()}
</form>
</div>