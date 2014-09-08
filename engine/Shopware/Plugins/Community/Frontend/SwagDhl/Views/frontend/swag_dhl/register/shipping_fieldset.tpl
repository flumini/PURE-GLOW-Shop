{block name='frontend_register_shipping_fieldset_input_lastname' append}
	<div>
		<label for="postnumber" class="normal">Postnummer: </label>
		<input name="register[shipping][postnumber]" type="text"  id="postnumber" value="{$sFormData.swagDhlPostnumber|escape}" class="text" />
	</div>
{/block}