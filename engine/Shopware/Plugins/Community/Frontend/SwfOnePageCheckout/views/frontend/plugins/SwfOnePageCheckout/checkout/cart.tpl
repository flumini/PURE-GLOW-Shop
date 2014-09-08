{block name="frontend_checkout_actions_confirm"}
	{if !$sMinimumSurcharge && !$sDispatchNoOrder}
	    <a href="{url controller=register}" title="{s name='CheckoutActionsLinkProceed' namespace="frontend/checkout/actions"}{/s}" class="button-right large right" >
			{se name="CheckoutActionsLinkProceed" namespace="frontend/checkout/actions"}{/se}
	    </a>
	    <div class="clear"></div>
	{/if}
{/block}

{block name="frontend_checkout_actions_confirm"}
	<a href="{url controller=register}" title="{s name='CheckoutActionsLinkProceed'}{/s}" class="button-right large right" >
		{se name="CheckoutActionsLinkProceed"}{/se}
	</a>
{/block}