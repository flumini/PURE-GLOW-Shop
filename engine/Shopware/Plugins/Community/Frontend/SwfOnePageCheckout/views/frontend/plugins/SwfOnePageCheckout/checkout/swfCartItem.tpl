{namespace name="frontend/checkout/cart_item"}

{if $sBasketItem.modus != 1 && $sBasketItem.modus != 3 && $sBasketItem.modus != 10 && $sBasketItem.modus != 2 && $sBasketItem.modus != 4}
	
	<form name="basket_change_quantity{$sBasketItem.id}" method="post" action="{url action='changeQuantity' sTargetAction=confirm part=cart}">
		{* Article informations *}
		<div class="table_row SwfCartItemHeader">
			{block name='frontend_checkout_cart_item_details'}
				<div class="basket_name">
					{* Article name *}
					{if $sBasketItem.modus ==0}
						<a class="title {if {config name=detailmodal}} detail-modal{/if}" href="{$sBasketItem.linkDetails}" title="{$sBasketItem.articlename|strip_tags}">
							{$sBasketItem.articlename|strip_tags|truncate:60}
						</a>
					{else}
						{$sBasketItem.articlename}
					{/if}
				</div>
			{/block}
			<table>	
				<tr>
					<td>
						{* Article picture *}
						{block name='frontend_checkout_cart_item_image'}
							{if $sBasketItem.image.src.0}
								<a href="{$sBasketItem.linkDetails}" title="{$sBasketItem.articlename|strip_tags}" class="thumb_image {if {config name=detailmodal}} detail-modal{/if}">
									<img src="{$sBasketItem.image.src.1}" border="0" alt="{$sBasketItem.articlename}" />
								</a>
							{else}
								<img class="no_image" src="{link file='frontend/_resources/images/no_picture.jpg'}" alt="{$sBasketItem.articlename}" />
							{/if}
						{/block}
					</td>
					<td>
						<div class="basket_details">
							{* Article name *}
							{if $sBasketItem.modus ==0}
								<p class="ordernumber">
									{se name="CartItemInfoId" namespace="frontend/checkout/cart_item"}{/se} {$sBasketItem.ordernumber}
								</p>
							{/if}
							{block name='frontend_checkout_cart_item_details_inline'}{/block}
						</div>
					</td>
				</tr>
			</table>
			{block name='frontend_checkout_cart_item_delete_article'}
				<div class="action" style="height:0px;">
					{if $sBasketItem.modus == 0}
						<a href="{url action='deleteArticle' sDelete=$sBasketItem.id sTargetAction=confirm part=cart}" class="del" title="{s name='CartItemLinkDelete '}{/s}">
							&nbsp;
						</a>
						&nbsp;
					{/if}
				</div>
			{/block}	
		</div>
	
		<div class="table_row SwfCartItemDetail">
			<table>	
				{* Delivery informations *}
				{block name='frontend_checkout_cart_item_delivery_informations'}
					<tr>
						<td>
							{s name="CartColumnAvailability" namespace="frontend/checkout/cart_header"}{/s}
						</td>
						<td>
							<div class="delivery">
								{if {config name=BasketShippingInfo}}
									{if $sBasketItem.shippinginfo}
										{include file="frontend/plugins/SwfOnePageCheckout/plugins/index/swfDeliveryInformations.tpl" sArticle=$sBasketItem}
						    		{/if}
						    	{else}
						    		&nbsp;
					    		{/if}
				    		</div>
		    			</td>
					</tr>
				{/block}
				
				{* Article amount *}
				{block name='frontend_checkout_cart_item_quantity'}
				<tr>
					<td>
						{s name="CartColumnQuantity" namespace="frontend/checkout/cart_header"}{/s}
					</td>
					<td>
						{if $sBasketItem.modus == 0}
							<select name="sQuantity" class="auto_submit">
							{section name="i" start=$sBasketItem.minpurchase loop=$sBasketItem.maxpurchase+1 step=$sBasketItem.purchasesteps}
								<option value="{$smarty.section.i.index}" {if $smarty.section.i.index==$sBasketItem.quantity}selected="selected"{/if}>
										{$smarty.section.i.index} 
								</option>
							{/section}
							</select>
							<input type="hidden" name="sArticle" value="{$sBasketItem.id}" />
						{else}
							&nbsp;
						{/if}
					</td>
				</tr>
				{/block}
			
				{* Article price *}
				{block name='frontend_checkout_cart_item_price'}
				<tr>
					<td>
						{s name="CartColumnPrice" namespace="frontend/checkout/cart_header"}{/s}
					</td>
					<td>
						{if !$sBasketItem.modus}{$sBasketItem.price|currency}{block name='frontend_checkout_cart_tax_symbol'}*{/block}{else}&nbsp;{/if}
					</td>
				</tr>
				{/block}
				
				{* Tax price *}
				{block name='frontend_checkout_cart_item_tax_price'}{/block}
				
				{* Article total sum *}
				{block name='frontend_checkout_cart_item_total_sum'}
				<tr>
					<td>
						{s name="CartColumnTotal" namespace="frontend/checkout/cart_header"}{/s}
					</td>
					<td>
						<strong>
							{$sBasketItem.amount|currency}*
						</strong>
					</td>
				</tr>
				{/block}
			</table>
		</div>
	</form>

{* Voucher *}
{elseif $sBasketItem.modus == 2}
	<div class="table_row voucher">
		<table>
			<tr>
				<td>
					<div class="thumb_image">
						<img src="{link file="/templates/_emotion/frontend/_resources/images/icons/ico-basket_voucher.png"}">
					</div>
				</td>
				<td>
					{block name='frontend_checkout_cart_item_voucher_details'}
						<div class="basket_details">
							<strong class="title">{$sBasketItem.articlename}</strong>
							<p class="ordernumber">
							{se name="CartItemInfoId" namespace="frontend/checkout/cart_item"}{/se}: {$sBasketItem.ordernumber}
							</p>
						</div>
					{/block}
				</td>
			</tr>
			<tr>
				<td>
					{s name="CartColumnTotal" namespace="frontend/checkout/cart_header"}{/s}
				</td>
				<td>
					{* Tax price *}
					{block name='frontend_checkout_cart_item_voucher_tax_price'}{/block}
					
					{block name='frontend_checkout_cart_item_voucher_price'}
						<strong>
						{if $sBasketItem.itemInfo}
							{$sBasketItem.itemInfo}
						{else}
							{$sBasketItem.price|currency} {block name='frontend_checkout_cart_tax_symbol'}*{/block}
						{/if}
						</strong>
					{/block}
				</td>
			</tr>
		</table>
		
		{block name='frontend_checkout_cart_item_voucher_delete'}
		<div class="action">
			<a href="{url action='deleteArticle' sDelete=voucher sTargetAction=confirm part=cart}" class="del" title="{s name='CartItemLinkDelete'}{/s}">&nbsp;</a>
		</div>
		{/block}
	</div>

{* Basket rebate *}
{elseif $sBasketItem.modus == 3}
<div class="table_row rebate">

	<div class="grid_1">
		{block name='frontend_checkout_cart_item_rebate_detail'}
		<div class="basket_details">
			<strong class="title">{$sBasketItem.articlename}</strong>
		</div>
		{/block}
		<div class="clear">&nbsp;</div>
	</div>
	
	{* Tax price *}
	{block name='frontend_checkout_cart_item_rebate_tax_price'}{/block}
	
	{block name='frontend_checkout_cart_item_rebate_price'}
	<div class="grid_2">
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

{* Selected premium article *}
{elseif $sBasketItem.modus == 1}
	<div class="table_row selected_premium">
		<table>
			<tr>
				<td>
					{block name='frontend_checkout_cart_item_premium_image'}
						<span class="premium_img">
							{se name="sCartItemFree"}GRATIS!{/se}
						</span>
					{/block}
				</td>
				<td>
					{block name='frontend_checkout_cart_item_premium_details'}
						<div class="basket_details">
							<strong class="title">{$sBasketItem.articlename}</strong>
						
							<p class="thankyou">
								{s name="CartItemInfoPremium"}{/s}
							</p>
						</div>
					{/block}
				</td>
			</tr>
			{* Article price *}
			{block name='frontend_checkout_cart_item_premium_price'}
				<tr>
					<td>
						{s name="CartColumnPrice" namespace="frontend/checkout/cart_header"}{/s}
					</td>
					<td>
						<strong>{s name="CartItemInfoFree"}{/s}</strong>
					</td>
				</tr>
			{/block}
		</table>
		
		{* Tax price *}
		{block name='frontend_checkout_cart_item_premium_tax_price'}{/block}
		
		{block name='frontend_checkout_cart_item_premium_delete'}
		<div class="action">
			<a href="{url action='deleteArticle' sDelete=$sBasketItem.id sTargetAction=confirm part=cart}" class="del" title="{s name='CartItemLinkDelete'}{/s}">&nbsp;</a>
		</div>
		{/block}
	</div>

{* Extra charge for small quantities *}
{elseif $sBasketItem.modus == 4}
	<div class="table_row small_quantities">
		<table>
			<tr>
				<td>
					{block name='frontend_checkout_cart_item_small_quantities_details'}
						<div class="basket_details">
							<strong class="title">{$sBasketItem.articlename}</strong>
						</div>
						<div class="clear">&nbsp;</div>
					{/block}
				</td>
			</tr>
			<tr>
				<td>
					{s name="CartColumnTotal" namespace="frontend/checkout/cart_header"}{/s}
				</td>
				<td>
					{block name='frontend_checkout_Cart_item_small_quantities_price'}
						<strong>
							{if $sBasketItem.itemInfo}
								{$sBasketItem.itemInfo}
							{else}
								{$sBasketItem.price|currency} {block name='frontend_checkout_cart_tax_symbol'}*{/block}
							{/if}
						</strong>
					{/block}
				</td>
			</tr>
		</table>
	</div>
		
	{* Tax price *}
	{block name='frontend_checkout_cart_item_small_quantites_tax_price'}{/block}
			
{* Bundle discount price *}
{elseif $sBasketItem.modus == 10}
	<div class="table_row bundle_row">
		
		{block name='frontend_checkout_cart_item_bundle_details'}
		<div class="grid_2">
			<div class="basket_details">
				<strong class="title">{s name='CartItemInfoBundle'}{/s}</strong>
			</div>
			<div class="clear">&nbsp;</div>
		</div>
		{/block}
		
		{* Tax price *}
		{block name='frontend_checkout_cart_item_bundle_tax_price'}{/block}
		
		{block name='frontend_checkout_cart_item_bundle_price'}
		<div class="grid_2 push_4">
			<div class="textright">
				<strong>
					{$sBasketItem.amount|currency} {block name='frontend_checkout_cart_tax_symbol'}*{/block}
				</strong>
			</div>
			<div class="clear">&nbsp;</div>
		</div>
		{/block}
	</div>
{/if}