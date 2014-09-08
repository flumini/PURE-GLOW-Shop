{block name='frontend_index_content'}
	<script type="text/javascript">
		var SwfBasketQuantity = {$sBasket.Quantity};
	</script>
	<div class="table">
		{* Table head *}
		{block name='frontend_checkout_cart_cart_head'}
			{include file='frontend/plugins/SwfOnePageCheckout/checkout/swfCartHeader.tpl'}
		{/block}

		{* Article items *}
		{block name='frontend_checkout_cart_item_outer'}
			<div id="SwfCartItems">
				{foreach name=basket from=$sBasket.content item=sBasketItem key=key}
					{block name='frontend_checkout_cart_item'}

						{include file='frontend/plugins/SwfOnePageCheckout/checkout/swfCartItem.tpl'}
					{/block}
				{/foreach}
			</div>
		{/block}
	</div>
	<div class="table SwfEditBasket">
		<div class="cart">
			<div class="table_row">
				<div class="box">
					<a href="{url controller=checkout action=cart}">{s name="SwfEditBasket" namespace="SwfOnePageCheckout"}Warenkorb bearbeiten{/s}</a>
				</div>
			</div>
		</div>
	</div>
	{if {config name=commentvoucherarticle}}
		<div class="table">
			<div id="basket">
				<div class="cart">
					<div class="table_row">
						<div class="box">
							{* Error messages *}
							{block name='frontend_checkout_cart_error_messages'}
								{include file="frontend/plugins/SwfOnePageCheckout/checkout/swfErrorMessages.tpl"}
							{/block}
							{block name='frontend_checkout_cart_footer_left'}
								{include file="frontend/plugins/SwfOnePageCheckout/checkout/swfCartFooterLeft.tpl"}
							{/block}
						</div>
					</div>
				</div>
			</div>
		</div>
	{/if}
	<div class="table" id="SwfSummary">
		{* Table foot *}
		{block name='frontend_checkout_cart_cart_footer'}
			{include file='frontend/plugins/SwfOnePageCheckout/checkout/swfCartFooter.tpl'}
		{/block}
	</div>
{/block}
