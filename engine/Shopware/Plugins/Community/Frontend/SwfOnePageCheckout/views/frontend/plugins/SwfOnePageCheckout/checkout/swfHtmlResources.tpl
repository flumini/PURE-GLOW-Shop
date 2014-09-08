{extends file='frontend/checkout/confirm.tpl'}

{block name="frontend_index_header_css_screen" append}
	<link type="text/css" media="all" rel="stylesheet" href="{link file='frontend/plugins/SwfOnePageCheckout/_resources/styles/SwfOnePageCheckout.css'}" />
{/block}

{block name='frontend_index_content_left'}{/block}

{block name="frontend_index_content"}
	<div class="table grid_6 cart">
		<div class="table_head">
			<div class="grid_5">Warenkorb</div>
		</div>
		{* Article items *}
	    {foreach name=basket from=$sBasket.content item=sBasketItem key=key}
	        {block name='frontend_checkout_cart_item'}
	            {include file='frontend/plugins/SwfOnePageCheckout/checkout/swfCartItem.tpl'}
	        {/block}
	    {/foreach}

	    <div class="table">
		    {* Table footer *}
			{block name='frontend_checkout_confirm_confirm_footer'}
		    	{include file="frontend/checkout/confirm_footer.tpl"}
			{/block}
		</div>
	</div>  

	{* Existing customer *}
	{block name='frontend_account_login_customer'}
		<div class="grid_14" id="login">	
	    	<h2 class="headingbox_dark largesize">{se name="LoginHeaderExistingCustomer" namespace="frontend/account/login"}{/se}</h2>
	    	<div class="inner_container">
		        <form name="sLogin" method="post" action="{url action=login controller=account}">
		            {if $sTarget}<input name="sTarget" type="hidden" value="{$sTarget|escape}" />{/if}
		            <fieldset>
		                <p>{se name="LoginHeaderFields" namespace="frontend/account/login"}{/se}</p>
		                <p>
		                    <label for="email">{se name='LoginLabelMail' namespace="frontend/account/login"}{/se}</label>
		                    <input name="email" type="text" tabindex="1" value="{$sFormData.email|escape}" id="email" class="text {if $sErrorFlag.email}instyle_error{/if}" />
		                </p>
		                <p class="none">
		                    <label for="passwort">{se name="LoginLabelPassword" namespace="frontend/account/login"}{/se}</label>
		                    <input name="password" type="password" tabindex="2" id="passwort" class="text {if $sErrorFlag.password}instyle_error{/if}" />
		                </p>
		            </fieldset>
		            
		            <p class="password">
		    			<a href="{url action=password}" title="{s name='LoginLinkLostPassword'}{/s}">
		    				{se name="LoginLinkLostPassword" namespace="frontend/account/login"}{/se}
		    			</a>
		    		</p>
		            <div class="action">
		           		<input class="button-middle small" type="submit" value="{s name='LoginLinkLogon' namespace="frontend/account/login"}{/s}" name="Submit"/>	
		            </div>
		        </form>
	    	</div>
	    </div>
    {/block}

    <div class="grid_14 register" id="center">

			{block name='frontend_register_index_dealer_register'}
		    {* Included for compatibility reasons *}
		    {/block}
			{block name='frontend_register_index_cgroup_header'}
			{if $register.personal.form_data.sValidation}
			{* Include information related to registration for other customergroups then guest, this block get overridden by b2b essentials plugin *}
				<div class="supplier_register">
					<div class="inner_container">
							<h1>{$sShopname} {s name='RegisterHeadlineSupplier' namespace='frontend/register/index'}{/s}</h1>
							<strong>{s name='RegisterInfoSupplier' namespace='frontend/register/index'}{/s}</strong><br />
							<a href="{url controller='account'}" class="account">{s name='RegisterInfoSupplier2' namespace='frontend/register/index'}{/s}</a>

							<div class="space">&nbsp;</div>

							<h4 class="bold">{s name='RegisterInfoSupplier3' namespace='frontend/register/index'}{/s}</h4>

							<h5 class="bold">{s name='RegisterInfoSupplier4' namespace='frontend/register/index'}{/s}</h5>{s name='RegisterInfoSupplier5' namespace='frontend/register/index'}{/s}
							<div class="space">&nbsp;</div>

						   <h5 class="bold">{s name='RegisterInfoSupplier6' namespace='frontend/register/index'}{/s}</h5>{s name='RegisterInfoSupplier7' namespace='frontend/register/index'}{/s}
					</div>
				</div>
			{/if}
			{/block}

			
		<form method="post" action="{url action=saveRegister}">
			
			{include file="frontend/register/error_message.tpl" error_messages=$register->personal->error_messages}
			{include file="frontend/register/personal_fieldset.tpl" form_data=$register->personal->form_data error_flags=$register->personal->error_flags}
			
			{include file="frontend/register/error_message.tpl" error_messages=$register->billing->error_messages}
			{include file="frontend/register/billing_fieldset.tpl" form_data=$register->billing->form_data error_flags=$register->billing->error_flags country_list=$register->billing->country_list}
			
			{include file="frontend/register/error_message.tpl" error_messages=$register->shipping->error_messages}
			{include file="frontend/register/shipping_fieldset.tpl" form_data=$register->shipping->form_data error_flags=$register->shipping->error_flags country_list=$register->shipping->country_list}
			
			<div class="payment_method register_last"></div>

			{* Privacy checkbox *}
			{if !$update}
				{if {config name=ACTDPRCHECK}}
					{block name='frontend_register_index_input_privacy'}
						<div class="privacy">
							<input name="register[personal][dpacheckbox]" type="checkbox" id="dpacheckbox"{if $form_data.dpacheckbox} checked="checked"{/if} value="1" class="chkbox" />
							<label for="dpacheckbox" class="chklabel{if $register->personal->error_flags.dpacheckbox} instyle_error{/if}">{s name='RegisterLabelDataCheckbox'}{/s}</label>
							<div class="clear">&nbsp;</div>
						</div>
					{/block}
				{/if}
			{/if}
		</form>

		<div class="personal_settings">
	    		<div id="confirm">
					{* Dispatch selection *}
				    {block name='frontend_checkout_confirm_shipping'}
				        {include file="frontend/checkout/confirm_dispatch.tpl"}
				    {/block}

				    {* Payment selection *}
				    {block name='frontend_checkout_confirm_payment'}
				        {include file='frontend/checkout/confirm_payment.tpl'}
				    {/block}
				</div>
	    </div>

	    {* Required fields hint *}
		<div class="required_fields">
			{s name='RegisterPersonalRequiredText' namespace='frontend/register/personal_fieldset'}{/s}
		</div>
	    

	    

	</div>
{/block}
