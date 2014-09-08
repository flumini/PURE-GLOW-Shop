{block name='frontend_index_content_bottom'}
    <script type="text/javascript">
        var SwfBasketQuantity = {$sBasket.Quantity};
    </script>
    <div class="clear"></div>
    <div class="table">


        {* Table head *}
        {block name='frontend_checkout_cart_cart_head_bottom'}
            {include file='frontend/plugins/SwfOnePageCheckout/checkout/swfCartHeaderBottom.tpl'}
        {/block}

        {* Article items *}
        {block name='frontend_checkout_cart_item_outer_bottom'}
            <div id="SwfCartItems">
                {foreach name=basket from=$sBasket.content item=sBasketItem key=key}
                    {block name='frontend_checkout_cart_item_bottom'}

                        {include file='frontend/plugins/SwfOnePageCheckout/checkout/swfCartItemBottom.tpl'}
                    {/block}
                {/foreach}
            </div>
        {/block}
    </div>
    {if {config name=commentvoucherarticle}}
        <div class="table">
            <div id="basket">
                <div class="cart">
                    <div class="table_row">
                        <div class="box">
                            {* Error messages *}
                            {block name='frontend_checkout_cart_error_messages_bottom'}
                                {include file="frontend/plugins/SwfOnePageCheckout/checkout/swfErrorMessages.tpl"}
                            {/block}
                            {block name='frontend_checkout_cart_footer_left_bottom'}
                                {include file="frontend/plugins/SwfOnePageCheckout/checkout/swfCartFooterLeft.tpl"}
                            {/block}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {/if}
    <div class="table bottom" id="SwfSummary">

        {* Table foot *}
        {block name='frontend_checkout_cart_cart_footer_bottom'}
            {include file='frontend/plugins/SwfOnePageCheckout/checkout/swfCartFooter.tpl'}
        {/block}
    </div>
    <div class="clear"></div>
{/block}
