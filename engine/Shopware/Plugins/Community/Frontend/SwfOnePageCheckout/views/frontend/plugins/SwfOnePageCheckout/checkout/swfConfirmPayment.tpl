{if $sRegisterFinished}
	<div class="payment_method SwfBox">
		<form name="" method="POST" action="{url controller=checkout action=confirm}" class="payment">
			<h3 class="underline">{s name='CheckoutPaymentHeadline'}Zahlungsart{/s}</h3>
			
			{foreach from=$sPayments item=payment_mean name=register_payment_mean}
				<div class="grid_12 method {if $smarty.foreach.register_payment_mean.last}last{/if}">
					{block name='frontend_checkout_payment_fieldset_input_radio'}
		            {if !{config name='IgnoreAGB'}}
		                <input type="hidden" class="agb-checkbox" name="sAGB" value="{if $sAGBChecked}1{else}0{/if}" />
		            {/if}
					<div class="grid_5 first">
						<input type="radio" name="register[payment]" class="radio auto_submit" value="{$payment_mean.id}" id="payment_mean{$payment_mean.id}"{if $payment_mean.id eq $sPayment.id} checked="checked"{/if} />
						<label class="description" for="payment_mean{$payment_mean.id}">{$payment_mean.description}</label>
					</div>
					{/block}
					
					{block name='frontend_checkout_payment_fieldset_description'}
					<div class="grid_7 last">
						{include file="string:{$payment_mean.additionaldescription}"}
					</div>
					{/block}
					
					{block name='frontend_checkout_payment_fieldset_template'}
					<div class="payment_logo_{$payment_mean.name}"></div>
					{if "frontend/plugins/payment/`$payment_mean.template`"|template_exists}
						<div class="space">&nbsp;</div>
						<div class="grid_5 first">&nbsp;</div>
						<div class="grid_7 bankdata">
							{if $payment_mean.id eq $sPayment.id}
								{include file="frontend/plugins/payment/`$payment_mean.template`" form_data=$sPayment.data}
							{else}
								{include file="frontend/plugins/payment/`$payment_mean.template`"}
							{/if}
						</div>
					{/if}
					{/block}
				</div>
			{/foreach}
			<div class="clear">&nbsp;</div>
		</form>
	</div>
{/if}