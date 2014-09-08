{namespace name="frontend/register/index"}

{block name="frontend_index_content"}
	<form method="post" action="{url action=saveRegister}">
		{include file="frontend/plugins/SwfOnePageCheckout/register/swfErrorMessages.tpl" error_messages=$register->personal->error_messages}
		{include file="frontend/plugins/SwfOnePageCheckout/register/swfPersonalFieldset.tpl" form_data=$register->personal->form_data error_flags=$register->personal->error_flags}
		
		{include file="frontend/plugins/SwfOnePageCheckout/register/swfErrorMessages.tpl" error_messages=$register->billing->error_messages}
		{include file="frontend/plugins/SwfOnePageCheckout/register/swfBillingFieldset.tpl" form_data=$register->billing->form_data error_flags=$register->billing->error_flags country_list=$register->billing->country_list}
		
		{include file="frontend/plugins/SwfOnePageCheckout/register/swfErrorMessages.tpl" error_messages=$register->shipping->error_messages}
		{include file="frontend/plugins/SwfOnePageCheckout/register/swfShippingFieldset.tpl" form_data=$register->shipping->form_data error_flags=$register->shipping->error_flags country_list=$register->shipping->country_list}

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
{/block}