{block name='frontend_checkout_dispatch_fieldset_input_radio'}
	{foreach from=$dhlDispatchIds item=dhlDispatchId}
		{if $dhlDispatchId == $sDispatch.id}
			{assign var=isDhlAndOnlyDispatch value=true}
		{/if}
		{if $dhlDispatchId == $dispatch.id}
			{assign var=isDhlDispatchId value=true}
		{/if}
	{/foreach}

	{if $isDhlAndOnlyDispatch && $sDispatches|count==1}
		{assign var=dispatch value=$sDispatch}
	{/if}

	{if $isDhlDispatchId}
		<div class="grid_4 first dhl_container">
			<input type="hidden" name="identifier" value="default_dhl">
			<input type="hidden" name="selectShippingDispatchUrl"
				   value="{url controller=dhl action=selectShippingDispatch}">
			<input type="hidden" name="saveShippingUrl" value="{url controller=dhl action=saveShipping}">
			<input id="confirm_dispatch{$dispatch.id}" type="radio" class="radio dhl_radio" value="{$dispatch.id}"
				   name="sDispatch"
				   {if $dispatch.id eq $sDispatch.id AND ($dhlDispatch=="default_dhl" OR $dhlDispatch=="")}checked="checked"{/if} />
			<label class="description underline" for="confirm_dispatch{$dispatch.id}">{s namespace="frontend/checkout/confirm_dispatch" name="shippingName"}DHL Versand{/s}</label>

			<div class="shipping-address">
				{if $sUserData.shippingaddress.company}
					<p>
						{$sUserData.shippingaddress.company}
						{if $sUserData.shippingaddress.department}
							<br/>
							{$sUserData.shippingaddress.department}
						{/if}
					</p>
				{/if}

				<p>
					{if $sUserData.shippingaddress.salutation eq "mr"}
						{s name="ConfirmSalutationMr" namespace="frontend/checkout/confirm_left"}Herr{/s}
					{else}
						{s name="ConfirmSalutationMs" namespace="frontend/checkout/confirm_left"}Frau{/s}
					{/if}
					{$sUserData.shippingaddress.firstname} {$sUserData.shippingaddress.lastname}<br/>
					{if $postNumber}{$postNumber}<br/>{/if}
					{$sUserData.shippingaddress.street} {$sUserData.shippingaddress.streetnumber}<br/>
					{$sUserData.shippingaddress.zipcode} {$sUserData.shippingaddress.city}<br/>
					{if $sUserData.additional.stateShipping.shortcode}{$sUserData.additional.stateShipping.shortcode} - {/if}{$sUserData.additional.countryShipping.countryname}
				</p>

				{* Action buttons *}
				<div class="actions">
					<a href="{url controller=account action=shipping sTarget=checkout}" class="button-middle small">
						{s name="ConfirmLinkChangeShipping" namespace="frontend/checkout/confirm_left"}{/s}
					</a>

					<a href="{url controller=account action=selectShipping sTarget=checkout}"
					   class="button-middle small">
						{s name="ConfirmLinkSelectShipping" namespace="frontend/checkout/confirm_left"}{/s}
					</a>
				</div>
			</div>
		</div>
	{else}
		{$smarty.block.parent}
	{/if}
{/block}

{block name='frontend_checkout_dispatch_fieldset_description'}
	{if $isDhlDispatchId AND $dispatch.countryID == 2}
		{if $showPostOffice}
			<div class="grid_7 first dhl_container">
				<input id="dhl_postoffice" type="radio" class="radio dhl_radio" value="{$dispatch.id}"
					   {if $dispatch.id eq $sDispatch.id AND $dhlDispatch=="postoffice"}checked="checked"{/if}
					   name="sDispatch"/>
				<label class="description"
					   for="dhl_postoffice">{s namespace="frontend/checkout/confirm_dispatch" name="ShipToPostofficeHeading"}Zu Postfiliale senden{/s}</label>
				<span id="psWindow" class="own_modal">
					<a href="{url controller=dhl action=findPostoffice sTarget=checkout}"
					   class="button-middle small">{s namespace="frontend/checkout/confirm_dispatch" name="search"}Suchen{/s}</a>
				</span>
				{include file="frontend/swag_dhl/checkout/postoffice_fieldset.tpl"}
			</div>
		{/if}

		{if $showPackStation}
			<div class="grid_7 first dhl_container">
				<input id="dhl_packstation" type="radio" class="radio dhl_radio" value="{$dispatch.id}"
					   {if $dispatch.id eq $sDispatch.id AND $dhlDispatch=="packstation"}checked="checked"{/if}
					   name="sDispatch"/>
				<label class="description"
					   for="dhl_packstation">{s namespace="frontend/checkout/confirm_dispatch" name="ShipToPackstationHeading"}Zu Packstation senden{/s}</label>
				<span id="psWindow" class="own_modal">
					<a href="{url controller=dhl action=findPackstation sTarget=checkout}"
					   class="button-middle small">{s namespace="frontend/checkout/confirm_dispatch" name="search"}Suchen{/s}</a>
				</span>
				{include file="frontend/swag_dhl/checkout/packstation_fieldset.tpl"}
			</div>
		{/if}
	{else}
		{$smarty.block.parent}
	{/if}
{/block}

{block name="frontend_checkout_shipping_action_buttons"}{/block}