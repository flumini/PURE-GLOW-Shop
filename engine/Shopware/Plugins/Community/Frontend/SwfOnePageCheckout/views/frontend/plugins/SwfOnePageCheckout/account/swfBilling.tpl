{extends file='frontend/account/billing.tpl'}

{block name='frontend_index_content_right'}{/block}
{block name='frontend_account_content_right_logout'}
{/block}


{block name="frontend_index_content"}

	{* Error messages *}
	{block name='frontend_account_error_messages'}
		{include file="frontend/plugins/SwfOnePageCheckout/register/swfErrorMessages.tpl" error_messages=$sErrorMessages}
	{/block}
	
	{* Personal form *}
	<form name="frmRegister" method="post" action="{url action=saveBilling}">
	
		{* Personal fieldset *}
		{block name='frontend_account_personal_information'}
			{include file='frontend/plugins/SwfOnePageCheckout/register/swfPersonalFieldset.tpl' update=true form_data=$sFormData error_flags=$sErrorFlag}
		{/block}
		
		{* Billing fieldset *}
		{block name='frontend_account_billing_information'}
			{include file='frontend/plugins/SwfOnePageCheckout/register/swfBillingFieldset.tpl' update=true form_data=$sFormData error_flags=$sErrorFlag country_list=$sCountryList}
		{/block}
	</form>
{/block}