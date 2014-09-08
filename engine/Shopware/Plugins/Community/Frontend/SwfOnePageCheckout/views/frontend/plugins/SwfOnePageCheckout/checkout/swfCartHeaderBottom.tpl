<div class="table_head">
    {block name='frontend_checkout_cart_header_field_labels'}
        {* Article informations *}
        {block name='frontend_checkout_cart_header_name'}
            <div class="grid_6">
                {s name="CartColumnName" namespace="frontend/checkout/cart_header"}{/s}
            </div>
        {/block}


        {* Article amount *}
        {block name='frontend_checkout_cart_header_quantity'}
            <div class="grid_1">
                {s name="CartColumnQuantity" namespace="frontend/checkout/cart_header"}{/s}
            </div>
        {/block}

        {* Unit price *}
        {block name='frontend_checkout_cart_header_price'}
            <div class="grid_2">
                <div class="textright">
                    {s name='CartColumnPrice' namespace="frontend/checkout/cart_header"}{/s}
                </div>
            </div>
        {/block}

        {* Article tax *}
        {block name='frontend_checkout_cart_header_tax'}
            <div class="charge_vat grid_2">
                {if $sUserData.additional.charge_vat && !$sUserData.additional.show_net}
                    {se name='CheckoutColumnExcludeTax' namespace="frontend/checkout/confirm_header"}{/se}
                {elseif $sUserData.additional.charge_vat}
                    {se name='CheckoutColumnTax' namespace="frontend/checkout/confirm_header"}{/se}
                {else}&nbsp;{/if}
            </div>
        {/block}

        {* Article total sum *}
        {block name='frontend_checkout_cart_header_total'}
            <div class="grid_2">
                <div class="textright">
                    {s name="CartColumnTotal" namespace="frontend/checkout/cart_header"}{/s}
                </div>
            </div>
        {/block}
    {/block}
</div>