{if $sBasketItem.modus != 1 && $sBasketItem.modus != 3 && $sBasketItem.modus != 10 && $sBasketItem.modus != 2 && $sBasketItem.modus != 4}
    <div class="table_row bottom_article_row">

        {* Article informations *}
        <div class="grid_6">
            <div class="first">


                {* Article image *}
                {block name='frontend_checkout_cart_item_image'}
                    {if $sBasketItem.image.src.0}
                        <a href="{url controller=detail sArticle=$sBasketItem.articleID forceSecure}" title="{$sBasketItem.articlename|strip_tags}" class="thumb_image{if {config name=detailmodal}} detail-modal{/if}" target="_blank">
                            <img src="{$sBasketItem.image.src.0}" border="0" alt="{$sBasketItem.articlename}"/>
                        </a>
                    {else}
                        <a href="{url controller=detail sArticle=$sBasketItem.articleID forceSecure}" title="{$sBasketItem.articlename|strip_tags}" class="thumb_image{if {config name=detailmodal}} detail-modal{/if}" target="_blank">
                            <img class="no_image" src="{link file='frontend/_resources/images/no_picture.jpg'}" alt="{$sBasketItem.articlename}"/>
                        </a>
                    {/if}
                {/block}

                {* Article name and order number *}
                {block name='frontend_checkout_cart_item_details'}
                    <div class="basket_details">
                        {* Article name *}
                        {if $sBasketItem.modus ==0}
                            <a class="title{if {config name=detailmodal}} detail-modal{/if}" href="{url controller=detail sArticle=$sBasketItem.articleID forceSecure}" target="_blank" title="{$sBasketItem.articlename|strip_tags}">
                                {$sBasketItem.articlename|strip_tags}
                            </a>
                            <p class="ordernumber">
                                {se name="CartItemInfoId" namespace="frontend/checkout/cart_item"}{/se} {$sBasketItem.ordernumber}
                            </p>
                        {else}
                            {$sBasketItem.articlename}
                        {/if}

                    </div>
                    <div class="clear">&nbsp;</div>
                {/block}
            </div>
        </div>




        {block name='frontend_checkout_cart_item_quantity'}
            {if $sLaststock.articles[$sBasketItem.ordernumber].OutOfStock == true}
                <div class="grid_1">
                    -
                </div>
            {else}
                <div class="grid_1">
                    {$sBasketItem.quantity}
                </div>
            {/if}
        {/block}
        {block name='frontend_checkout_cart_item_price'}
            <div class="grid_2">
                <div class="textright">
                    {$sBasketItem.price|currency} {s name="Star" namespace="frontend/listing/box_article"}{/s}
                </div>
            </div>
        {/block}


        {* Tax price *}
        {block name='frontend_checkout_cart_item_tax_price'}
            <div class="grid_2">
                <div class="textright">
                    {if $sUserData.additional.charge_vat}{$sBasketItem.tax|currency}{else}&nbsp;{/if}
                </div>
            </div>
        {/block}

        {* Article total sum *}
        {block name='frontend_checkout_cart_item_total_sum'}
            <div class="grid_2">
                <div class="textright">
                    <strong>
                        {$sBasketItem.amount|currency}*
                    </strong>
                </div>
            </div>
        {/block}


        <div class="clear">&nbsp;</div>

    </div>
    {* Voucher *}
{elseif $sBasketItem.modus == 2}
    <div class="table_row voucher">
        <div class="grid_6">
            {block name='frontend_checkout_cart_item_voucher_details'}
                <div class="basket_details">
                    <strong class="title">{$sBasketItem.articlename}</strong>

                    <p class="ordernumber">
                        {se name="CartItemInfoId"}{/se}: {$sBasketItem.ordernumber}
                    </p>
                </div>
            {/block}
            <div class="clear">&nbsp;</div>
        </div>
        {* Voucher tax price *}
        {block name='frontend_checkout_cart_item_voucher_tax_price'}
            <div class="grid_2 push_4">
                <div class="textright">
                    {if $sUserData.additional.charge_vat}{$sBasketItem.tax|currency}{else}&nbsp;{/if}
                </div>
            </div>
        {/block}
        {* Voucher price *}
        {block name="frontend_checkout_cart_item_voucher_price"}
            <div class="grid_3 push_3">
                <div class="textright">
                    <strong>
                        {if $sBasketItem.itemInfo}
                            {$sBasketItem.itemInfo}
                        {else}
                            {$sBasketItem.price|currency}*
                        {/if}
                    </strong>
                </div>
                <div class="clear">&nbsp;</div>
            </div>
        {/block}


    </div>
    {* Basket rebate *}
{elseif $sBasketItem.modus == 3}
    <div class="table_row rebate">

        <div class="grid_6">
            {block name='frontend_checkout_cart_item_rebate_detail'}
                <div class="basket_details">
                    <strong class="title">{$sBasketItem.articlename}</strong>
                </div>
            {/block}
            <div class="clear">&nbsp;</div>
        </div>

        {* Rebate tax price *}
        {block name='frontend_checkout_cart_item_rebate_tax_price'}
            <div class="grid_2 push_4">
                <div class="textright">
                    {if $sUserData.additional.charge_vat}{$sBasketItem.tax|currency}{else}&nbsp;{/if}
                </div>
            </div>
        {/block}
        {* Rebate price *}
        {block name='frontend_checkout_cart_item_rebate_price'}
            <div class="grid_3 push_3">
                <div class="textright">
                    <strong>
                        {if $sBasketItem.itemInfo}
                            {$sBasketItem.itemInfo}
                        {else}
                            {$sBasketItem.price|currency}*
                        {/if}
                    </strong>
                </div>
                <div class="clear">&nbsp;</div>
            </div>
        {/block}


    </div>
    {* Selected premium article *}
{elseif $sBasketItem.modus == 1}
    <div class="table_row selected_premium">
        <div class="grid_6">
            {block name='frontend_checkout_cart_item_premium_image'}
                {if $sBasketItem.image.src.0}
                    <a class="thumbnail">
                        <img src="{$sBasketItem.image.src.1}" border="0" alt="{$sBasketItem.articlename} "/>
                    </a>
                {/if}
            {/block}

            {block name='frontend_checkout_cart_item_premium_details'}
                <div class="basket_details">
                    <strong class="title">{$sBasketItem.articlename}</strong>

                    <p class="thankyou">
                        {s name="CartItemInfoPremium"}{/s}
                    </p>
                </div>
            {/block}
            <div class="clear">&nbsp;</div>
        </div>

        {* Premium tax price *}
        {block name='frontend_checkout_cart_item_premium_tax_price'}
            <div class="grid_2 push_4">
                <div class="textright">
                    {if $sUserData.additional.charge_vat}{$sBasketItem.tax|currency}{else}&nbsp;{/if}
                </div>
            </div>
        {/block}

        {* Premium price *}
        {block name="frontend_checkout_cart_item_premium_price"}
            <div class="grid_3 push_3">
                <div class="textright">
                    <strong>
                        {s name="CartItemInfoFree"}{/s}
                    </strong>
                </div>
                <div class="clear">&nbsp;</div>
            </div>
        {/block}



        {block name='frontend_checkout_cart_item_premium_delete'}
            <div class="action">
                <a href="{url action='deleteArticle' sDelete=$sBasketItem.id sTargetAction=$sTargetAction}" class="del" title="{s name='CartItemLinkDelete'}{/s}">&nbsp;</a>
            </div>
        {/block}
    </div>
    {* Extra charge for small quantities *}
{elseif $sBasketItem.modus == 4}
    <div class="table_row small_quantities sm_bottom">
        {block name='frontend_checkout_cart_item_small_quantities_details'}
            <div class="grid_6 swf_grid_6">
                <div class="basket_details">
                    <strong class="title">{$sBasketItem.articlename}</strong>
                </div>
                <div class="clear">&nbsp;</div>
            </div>
        {/block}

        {* Small quanitity tax price *}
        {block name='frontend_checkout_cart_item_small_quantites_tax_price'}
            <div class="grid_2 swf_push_3">
                <div class="textright">
                    {if $sUserData.additional.charge_vat}{$sBasketItem.tax|currency}{else}&nbsp;{/if}
                </div>
            </div>
        {/block}

        {block name='frontend_checkout_Cart_item_small_quantities_price'}
            <div class="grid_3 swf_push_2">
                <div class="textright">
                    <strong>
                        {if $sBasketItem.itemInfo}
                            {$sBasketItem.itemInfo}
                        {else}
                            {$sBasketItem.price|currency} {block name='frontend_checkout_cart_tax_symbol'}*{/block}
                        {/if}
                    </strong>
                </div>
                <div class="clear">&nbsp;</div>
            </div>
        {/block}

    </div>
    {* Bundle discount price *}
{elseif $sBasketItem.modus == 10}
    <div class="table_row bundle_row">

        {block name='frontend_checkout_cart_item_bundle_details'}
            <div class="grid_6">
                <div class="basket_details">
                    <strong class="title">{s name='CartItemInfoBundle'}{/s}</strong>
                </div>
                <div class="clear">&nbsp;</div>
            </div>
        {/block}

        {* Bundle tax price *}
        {block name='frontend_checkout_cart_item_bundle_tax_price'}
            <div class="grid_2 push_4">
                <div class="textright">
                    {if $sUserData.additional.charge_vat}{$sBasketItem.tax|currency}{else}&nbsp;{/if}
                </div>
            </div>
        {/block}

        {* Bundle price *}
        {block name='frontend_checkout_cart_item_bundle_price'}
            <div class="grid_3 push_3">
                <div class="textright">
                    <strong>
                        {$sBasketItem.amount|currency}*
                    </strong>
                </div>
                <div class="clear">&nbsp;</div>
            </div>
        {/block}
    </div>
{/if}




{* Hide tax symbol *}
{block name='frontend_checkout_cart_tax_symbol'}{/block}






