{extends file='parent:frontend/checkout/cart_footer_left.tpl'}


{block name='frontend_checkout_table_footer_left_add_voucher'}

	{* Deliveryfree *}
	{if $sShippingcostsDifference}
		<div class="box_cart_info">
		<p>
			<strong>{se name="CartInfoFreeShipping" namespace="frontend/checkout/cart"}{/se}</strong>
			{se name="CartInfoFreeShippingDifference" namespace="frontend/checkout/cart"}{/se}
		</p>
		</div>
	{/if}
{/block}


{block name='frontend_checkout_table_footer_left_add_article'}{/block}