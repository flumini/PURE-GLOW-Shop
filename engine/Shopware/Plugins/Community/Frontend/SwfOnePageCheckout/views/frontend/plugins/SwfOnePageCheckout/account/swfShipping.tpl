{extends file='frontend/account/shipping.tpl'}

{block name='frontend_index_content_right'}{/block}

{block name='frontend_index_content'}

	{* Error messages *}
	{block name='frontend_account_shipping_error_messages'}
		{include file="frontend/plugins/SwfOnePageCheckout/register/swfErrorMessages.tpl" error_messages=$sErrorMessages}
	{/block}

	{* Shipping form *}
	<form name="frmRegister" method="post" action="{url action=saveShipping}">
	
		{* Shipping fieldset *}
		{block name='frontend_account_shipping_fieldset'}
			{include file='frontend/plugins/SwfOnePageCheckout/register/swfShippingFieldset.tpl' form_data=$sFormData error_flags=$sErrorFlag country_list=$sCountryList}
		{/block}

	</form>
{/block}