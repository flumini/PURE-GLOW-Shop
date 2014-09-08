{if $sArticle.sRelatedArticles && !$sArticle.crossbundlelook}
    <div class="clear">&nbsp;</div>
    <div class="page">&nbsp;</div>
    <div id="related">
        <h2 class="headingbox_nobg" style="margin-bottom: 20px;">{se name='DetailRelatedHeader'}{/se}</h2>
        <div class="container">
        	<div class="listing" id="listing">
	        {foreach from=$sArticle.sRelatedArticles item=sArticleSub key=key name="counter"}
	        	{include file="frontend/listing/box_article.tpl" sArticle=$sArticleSub sTemplate='listing' sShopTheLook = true}
	        	
	        {/foreach}
	        </div>
        </div>
        <div class="clear">&nbsp;</div>
    </div>

{/if}
