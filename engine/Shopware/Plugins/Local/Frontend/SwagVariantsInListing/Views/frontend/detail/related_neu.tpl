
{if $sArticle.sRelatedArticles && !$sArticle.crossbundlelook}
    <div class="clear">&nbsp;</div>
    <div class="page">&nbsp;</div>
    <div id="related">
        <h2 class="headingbox_nobg" style="margin-bottom: 20px;">{se name='DetailRelatedHeader'}{/se}</h2>
        <div class="container">
        	<div class="listing" id="listing">
        	{$i = 0}
	        {foreach from=$sArticle.sRelatedArticles item=sArticleSub key=key name="counter"}
	        	{$tmp = '/kunden/399154_22085/webseiten/shop/engine/Shopware/Plugins/Local/Frontend/SwagVariantsInListing/Views/frontend/detail/box_article_neu.tpl'}
	        	{include file=$tmp sArticle=$sArticleSub sTemplate='listing' sShopTheLook = true articleimg=$bilder.$i.swagVariantsInListing.0}
	        	{$i = $i+1}
	        	
	        {/foreach}
			</div>
        </div>
        <div class="clear">&nbsp;</div>
    </div>

{/if}