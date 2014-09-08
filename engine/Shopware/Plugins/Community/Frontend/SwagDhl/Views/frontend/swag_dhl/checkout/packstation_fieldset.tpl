{* Packstation form *}
<div class="alternative_shipping">
	<input type="hidden" name="identifier" value="packstation">
	<input type="hidden" name="userId" value="{$sUserId}">

	{* postnumber *}
	<div>
		<label for="postnumber">{s namespace="frontend/register/shipping_fieldset" name='RegisterShippingLabelPostnumber'}Postnummer*:{/s}</label>
		<input name="postnumber" class="dhl_textfield full" type="text" id="packstation_postnumber"
			   value="{$postNumber}"/>
	</div>

	{* Street *}
	<div>
		<input name="street" type="hidden" id="packstation_street" value="{$packStation->street}"/>
		<input name="streetnumber" type="hidden" id="packstation_streetnumber" value="{$packStation->streetNumber}"/>
	</div>

	{* Packstation *}
	<div>
		<label for="packstation_number">{s namespace="frontend/register/shipping_fieldset" name='RegisterShippingLabelFindPackstation'}Packstation Nummer*:{/s}</label>
		<input name="number" class="dhl_textfield full" type="text" id="packstation_number" value="{$packStation->stationNumber}" readonly/>
	</div>

	{* Zip + City *}
	<div>
		<label for="packstation_zipcode">{s namespace="frontend/register/shipping_fieldset" name='RegisterShippingLabelCity'}Postleitzahl und Stadt*:{/s}</label>
		<input name="zipcode" type="text" id="packstation_zipcode" value="{$packStation->zip|escape}" maxlength="5"
			   class="zipcode dhl_textfield" readonly/>
		<input name="city" type="text" id="packstation_city" value="{$packStation->city|escape}" class="city dhl_textfield" readonly/>
	</div>

	{* Action button *}
	<div class="actions dhl_actions">
		{assign value=$postNumber|count_characters var="count"}
		<input type="button"
			   value="{s namespace="frontend/register/shipping_fieldset" name='ShippingLinkSend'}Übernehmen{/s}"
			   class="button-right small" {if !$postNumber || $count < 6 || $count > 10} disabled="disabled"{/if} />
	</div>
</div>