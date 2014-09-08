{namespace name="frontend/account/login"}

{block name='frontend_index_content'}
	{* Error messages *}
	{block name='frontend_account_login_error_messages'}
		{include file="frontend/register/error_message.tpl" error_messages=$sErrorMessages}
	{/block}

	<div id="login">
		{* Existing customer *}
		{block name='frontend_account_login_customer'}
	    	<h2 class="headingbox_dark largesize">{se name="LoginHeaderExistingCustomer"}{/se}</h2>
	    	<div class="inner_container">
		        <form name="sLogin" method="post" action="{url action=login SwfOnePageCheckout="true"}">
		            {if $sTarget}<input name="sTarget" type="hidden" value="{$sTarget|escape}" />{/if}
		            <fieldset>
		                <p>{se name="LoginHeaderFields"}{/se}</p>
		                <p>
		                    <label for="email">{se name='LoginLabelMail'}{/se}</label>
		                    <input name="email" type="text" tabindex="1" value="{$sFormData.email|escape}" id="email" class="text {if $sErrorFlag.email}instyle_error{/if}" />
		                </p>
		                <p class="none">
		                    <label for="passwort">{se name="LoginLabelPassword"}{/se}</label>
		                    <input name="password" type="password" tabindex="2" id="passwort" class="text {if $sErrorFlag.password}instyle_error{/if}" />
		                </p>
		            </fieldset>
		            
		            <p class="password">
		    			<a href="{url action=password}" title="{s name='LoginLinkLostPassword'}{/s}">
		    				{se name="LoginLinkLostPassword"}{/se}
		    			</a>
		    		</p>
		            <div class="action">
		           		<input class="button-middle small" type="submit" value="{s name='LoginLinkLogon'}{/s}" name="Submit"/>	
		            </div>
		        </form>
	    	</div>
	    {/block}
	</div>
{/block}

{block name='frontend_index_start'}
{/block}

{block name='frontend_account_login_new'}
{/block}

{block name='frontend_index_content_left'}
{/block}

{block name='frontend_index_content_right'}
{/block}