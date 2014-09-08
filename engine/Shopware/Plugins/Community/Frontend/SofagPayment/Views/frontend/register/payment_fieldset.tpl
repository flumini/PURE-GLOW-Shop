{block name='frontend_register_payment_fieldset_description'}
    {if $payment_mean.name == 'sofortbanking' || $payment_mean.name == 'sofortideal'}
        <div class = "grid_10 last" >
            {include file="string:{$payment_mean.additionaldescription}"}
            {if "frontend/plugins/payment/`$payment_mean.name`.tpl"|template_exists}
                {include file="frontend/plugins/payment/`$payment_mean.name`.tpl"}
            {/if}
        </div >
    {else}
        {$smarty.block.parent}
    {/if}
{/block}

{block name='frontend_checkout_payment_fieldset_description'}
    {if $payment_mean.name == 'sofortbanking' || $payment_mean.name == 'sofortideal'}
        <div class = "grid_10 last" >
            {include file="string:{$payment_mean.additionaldescription}"}
            {if "frontend/plugins/payment/`$payment_mean.name`.tpl"|template_exists}
                {include file="frontend/plugins/payment/`$payment_mean.name`.tpl"}
            {/if}
        </div >
    {else}
        {$smarty.block.parent}
    {/if}
{/block}