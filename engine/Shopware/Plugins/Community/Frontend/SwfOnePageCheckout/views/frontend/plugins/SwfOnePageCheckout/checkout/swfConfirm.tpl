{extends file='frontend/checkout/confirm.tpl'}

{namespace name="frontend/checkout/confirm"}

{* Main content *}
{block name="frontend_index_content"}
    {* Error messages *}
    {block name='frontend_checkout_confirm_error_messages'}
        {include file="frontend/plugins/SwfOnePageCheckout/checkout/swfErrorMessages.tpl"}
    {/block}

    {* Dispatch selection *}
    {block name='frontend_checkout_confirm_shipping'}
        {include file="frontend/plugins/SwfOnePageCheckout/checkout/swfConfirmDispatch.tpl"}
    {/block}

    <div id="SwfConfirmBlocks">
    {* Newsletter registration *}
    {block name='frontend_checkout_confirm_newsletter'}
        {if !$sUserData.additional.user.newsletter && {config name=newsletter}}
            <div class="clear"></div>
        {/if}
    {/block}

    {* Billing address *}
    {block name='frontend_checkout_confirm_left_billing_address'}
    {/block}

    {* Shipping address *}
    {block name='frontend_checkout_confirm_left_shipping_address'}
    {/block}

    {* Payment method *}
    {block name='frontend_checkout_confirm_left_payment_method'}
    {/block}

    {if {config name=commentvoucherarticle}||{config name=premiumarticles}||{config name=bonussystem} && {config name=bonus_system_active} && {config name=displaySlider}}
        <div class="additional-options grid_16 first">

            <div class="inner_container">

                {* Voucher and add article *}
                {if {config name=commentvoucherarticle}}

                    {block name='frontend_checkout_table_footer_left_add_voucher'}
                    {/block}

                    {block name='frontend_checkout_table_footer_left_add_article'}
                    {/block}

                    {* Comment functionality *}
                    {block name='frontend_checkout_confirm_comment'}
                    {/block}
                {/if}

                {* Premiums articles *}
                {block name='frontend_checkout_confirm_premiums'}
                    {if $sPremiums}
                        {if {config name=premiumarticles}}
                            <div class="SwfBox SwfPremiums">
                                <h2 class="headingbox">{s name="sCartPremiumsHeadline" namespace="frontend/checkout/premiums"}{/s}</h2>
                                {include file='frontend/checkout/premiums.tpl'}
                            </div>
                        {/if}
                    {/if}
                {/block}
            </div>
        </div>
    {/if}

    <div class="additional_footer SwfBox SwfConfirmFooter">
<form method="post" action="{if $sPayment.embediframe || $sPayment.action}{url action='payment'}{else}{url action='finish'}{/if}">

    {if !$sUserData.additional.user.newsletter && {config name=newsletter}}
        <input type="hidden" class="newsletter-checkbox" name="sNewsletter" value="{if $sNewsletter}1{else}0{/if}"/>
    {/if}

    {if {config name=commentvoucherarticle}}
        <div class="user-comment">
            <h2 class="headingbox">{s name="ConfirmLabelComment" namespace="frontend/checkout/confirm"}{/s}</h2>
            <textarea name="sComment" rows="3">{$sComment|escape}</textarea>
        </div>
    {/if}

    {block name='frontend_checkout_confirm_footer'}

        {* Include country specific notice message *}

        {if {config name=countrynotice} && $sCountry.notice && {include file="string:{$sCountry.notice}"} !== ""}
            {* Include country specific notice message *}
            <p class="SwfFull">
            {include file="string:{$sCountry.notice}" }
            </p>
                    {/if}
                    
                    
                    {* Display the right of cancelation *}
                    {if {config name=revocationnotice}}
                        <div class="confirm_accept modal_open SwfFull  {if {config name=SwfOnePageCheckout_ArticlesBottomCheckout} == 1}bottom_article_agb{/if}">
                            {s name="ConfirmTextRightOfRevocationNew"}<p>Bitte beachten Sie bei Ihrer Bestellung auch unsere <a href="{url controller=custom sCustom=8 forceSecure}" data-modal-height="500" data-modal-width="800">Widerrufsbelehrung</a>.</p>{/s}
                        </div>
                        {if {config name=SwfOnePageCheckout_ArticlesBottomCheckout} == 1}
                           {block name='frontend_checkout_confirm_agb_checkbox'}
                                <div class="agb_accept SwfFull bottom_article_agb">
                                    {if !{config name='IgnoreAGB'}}
                                        <input type="checkbox" class="left" name="sAGB" id="sAGB" {if $sAGBChecked} checked="checked"{/if} />
                                    {/if}
                                    <label for="sAGB" class="chklabel modal_open {if $sAGBError}instyle_error{/if}">{s name="ConfirmTerms"}{/s}</label>
                                </div>
                            {/block}


                            {if !$sUserData.additional.user.newsletter && {config name=newsletter}}
                                <div class="SwfFull bottom_article_agb">
                                    <input type="checkbox" name="sNewsletter" id="sNewsletter" value="1" class="chkbox"{if $sNewsletter} checked="checked"{/if} />
                                    <label for="sNewsletter" class="chklabel">
                                        {s name="ConfirmLabelNewsletter"}{/s}
                                    </label>
                                </div>
                            {/if}

                        {/if}
                    {/if}

                    {if {config name=additionalfreetext}}
                        <p class="SwfFull">
                            {s name="ConfirmInfoChange"}{/s}<br/>
                            {s name="ConfirmInfoPaymentData"}{/s}
                        </p>
                    {/if}

                {/block}
                {if   {config name=SwfOnePageCheckout_ArticlesBottomCheckout} == 1}

                        {include file='frontend/plugins/SwfOnePageCheckout/checkout/swfCartBottom.tpl'}

                {else}
                    {block name='frontend_checkout_confirm_agb_checkbox'}
                        <div class="agb_accept SwfFull">
                            {if !{config name='IgnoreAGB'}}
                                <input type="checkbox" class="left" name="sAGB" id="sAGB" {if $sAGBChecked} checked="checked"{/if} />
                            {/if}
                            <label for="sAGB" class="chklabel modal_open {if $sAGBError}instyle_error{/if}">{s name="ConfirmTerms"}{/s}</label>
                        </div>
                    {/block}

                    {if !$sUserData.additional.user.newsletter && {config name=newsletter}}
                        <div class="SwfFull">
                            <input type="checkbox" name="sNewsletter" id="sNewsletter" value="1" class="chkbox"{if $sNewsletter} checked="checked"{/if} />
                            <label for="sNewsletter" class="chklabel">
                                {s name="ConfirmLabelNewsletter"}{/s}
                            </label>
                        </div>
                    {/if}
                {/if}

                {if !$sLaststock.hideBasket}
                    {block name='frontend_checkout_confirm_submit'}
                        {* Submit order button *}
                        <div class="actions SwfFull">
                            {if $sPayment.embediframe || $sPayment.action}
                                <input type="submit" class="button-right large" id="basketButton" value="{s name='ConfirmDoPayment'}Zahlung durchfÃ¼hren{/s}" />
                            {else}
                                <input type="submit" class="button-right large" id="basketButton" value="{s name='ConfirmActionSubmit'}{/s}" />
                            {/if}
                        </div>
                    {/block}
                {else}
                    {block name='frontend_checkout_confirm_stockinfo'}
                        <div class="error SwfFull">
                            <div class="center">
                                <strong>
                                    {s name='ConfirmErrorStock'}Ein Artikel aus Ihrer Bestellung ist nicht mehr verfügbar! Bitte entfernen Sie die Position aus dem Warenkorb!{/s}
                                </strong>
                            </div>
                        </div>
                    {/block}
                {/if}

                {block name='frontend_checkout_confirm_agb'}
                {/block}

            </form>
        </div>  
    </div>        
{/block}
