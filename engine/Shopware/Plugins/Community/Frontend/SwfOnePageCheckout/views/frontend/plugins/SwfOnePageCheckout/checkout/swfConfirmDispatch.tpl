{if $sDispatches}
<div class="dispatch-methods SwfBox">
	<form method="POST" action="{url controller='checkout' action='confirm' SwfOnePageCheckout='true'}" class="payment">

		<h3 class="underline">{s name='CheckoutDispatchHeadline'}Versandart{/s}</h3>

		{if $sDispatches|count>1}
			{foreach from=$sDispatches name="sDispatches" item=dispatch}
				<div class="grid_12 method {if $smarty.foreach.sDispatches.last}last{/if}">
					{block name='frontend_checkout_dispatch_fieldset_input_radio'}
						<div class="grid_5 first">
							<input id="confirm_dispatch{$dispatch.id}" type="radio" class="radio auto_submit" value="{$dispatch.id}" name="sDispatch" {if $dispatch.id eq $sDispatch.id}checked="checked"{/if} />
							<label class="description" for="confirm_dispatch{$dispatch.id}">{$dispatch.name}</label>
						</div>
					{/block}
					<div class="grid_7 last">
						{block name='frontend_checkout_dispatch_fieldset_description'}
							{if $dispatch.description}
								{$dispatch.description}
							{/if}
						{/block}
					</div>
				</div>
			{/foreach}

			{block name="frontend_checkout_shipping_action_buttons"}
			{/block}
		{else}
			<div class="grid_12 method last">
				{block name='frontend_checkout_dispatch_fieldset_input_radio'}
					<div class="grid_5 first">
						<label class="description">{$sDispatch.name}</label>
					</div>
				{/block}

				{block name='frontend_checkout_dispatch_fieldset_description'}
					{if $sDispatch.description}
						<div class="grid_7 last">
							{$sDispatch.description}
						</div>
					{/if}
				{/block}
			</div>
		{/if}
	</form>
</div>
{/if}
