{namespace name="frontend/index/checkout_actions"}

{block name="frontend_index_checkout_actions_my_options"}
{/block}

{block name="frontend_index_checkout_actions_account"}
{/block}

{block name="frontend_index_checkout_actions_notepad"}
{/block}

<div id="SwfShopnaviResource">
    
	{block name="frontend_index_checkout_actions_cart"}
    <div class="grid_6 newbasket{if $sBasketQuantity} active{/if}">
    
		<div class="grid_2 last icon">
			<a href="{url controller='checkout' action='cart'}" title="{s namespace='frontend/index/checkout_actions' name='IndexLinkCart'}{/s}">
				{if $sUserLoggedIn}{s name='IndexLinkCheckout'}{/s}{else}{s namespace='frontend/index/checkout_actions' name='IndexLinkCart'}{/s}{/if}
			</a>
		</div>

		<div class="grid_5 first last display">
			{*<div class="basket_left">*}
				{*<span>*}
					{*<a href="{url controller='checkout' action='cart'}" title="{s namespace='frontend/index/checkout_actions' name='IndexLinkCart'}{/s}">*}
						{*{s namespace='frontend/index/checkout_actions' name='IndexLinkCart'}{/s}*}
					{*</a>*}
				{*</span>*}
			{*</div>*}
			{*<div class="basket_right">*}
				{*<span class="amount">{$sBasketAmount|currency}*</span>*}
			{*</div>*}
            <div class="top">
                <a href="{url controller='checkout' action='cart' namespace='frontend/index/checkout_actions'}" title="{s name='IndexLinkCart' namespace='frontend/index/checkout_actions'}{/s}" class="uppercase bold">{s name='IndexLinkCart' namespace='frontend/index/checkout_actions'}{/s}</a>
                <div class="display_basket">
                    <span class="quantity">{$sBasketQuantity} {s name='IndexInfoArticles' namespace='frontend/index/checkout_actions'}{/s}</span>
                    <span class="sep">|</span>
                    <span class="amount">{$sBasketAmount|currency}*</span>
                </div>
            </div>


            <div class="ajax_basket_container hide_script">
                <div class="ajax_basket">
                    {s name='IndexActionShowPositions' namespace='frontend/index/checkout_actions'}{/s}
                    {* Ajax loader *}
                    <div class="ajax_loader">&nbsp;</div>
                </div>
            </div>



		</div>




		{*{if $sBasketQuantity > 0}*}
			{*<a href="{url controller='checkout' action='cart'}" class="quantity">{$sBasketQuantity}</a>*}
		{*{/if}*}
		{**}
        <div class="clear">&nbsp;</div>
    </div>
	{/block}
	
    {block name="frontend_index_checkout_actions_inner"}{/block}
    
</div>