{* Postoffice form *}
<div class="alternative_shipping">
	<input type="hidden" name="identifier" value="postoffice">
	<input type="hidden" name="userId" value="{$sUserId}">

	{* postnumber *}
	<div>
		<label for="postnumber">{s namespace="frontend/register/shipping_fieldset" name='RegisterShippingLabelPostnumber'}Postnummer*:{/s}</label>
		<input name="postnumber" class="dhl_textfield full" type="text" id="postoffice_postnumber"
			   value="{$postNumber}"/>
	</div>

	{* street *}
	<div>
		<input name="street" type="hidden" id="postoffice_street" value="{$postOffice->street}"/>
		<input name="streetnumber" type="hidden" id="postoffice_streetnumber" value="{$postOffice->streetNumber}"/>
	</div>

	{* Postoffice *}
	<div>
		<label for="postoffice_number">{s namespace="frontend/register/shipping_fieldset" name='RegisterShippingLabelFindPostoffice'}Postfilialen Nummer*:{/s}</label>
		<input name="number" class="dhl_textfield full" type="text" id="postoffice_number" value="{$postOffice->officeNumber}" readonly/>
	</div>

	{* Zip + City *}
	<div>
		<label for="postoffice_zipcode">{s namespace="frontend/register/shipping_fieldset" name='RegisterShippingLabelCity'}Postleitzahl und Ort*:{/s}</label>
		<input name="zipcode" type="text" id="postoffice_zipcode" value="{$postOffice->zip|escape}" maxlength="5"
			   class="zipcode dhl_textfield" readonly/>
		<input name="city" type="text" id="postoffice_city" value="{$postOffice->city|escape}" class="city dhl_textfield" readonly/>
	</div>

	{* Action button *}
	<div class="actions dhl_actions">
		{assign value=$postNumber|count_characters var="count"}
		<input type="button"
			   value="{s namespace="frontend/register/shipping_fieldset" name='ShippingLinkSend'}Übernehmen{/s}"
			   class="button-right small" {if !$postNumber || $count < 6 || $count > 10} disabled="disabled"{/if} />
	</div>
</div>