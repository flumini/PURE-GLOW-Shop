{* Add stylesheet *}
{block name="frontend_index_header_css_screen" append}
<link type="text/css" media="screen, projection" rel="stylesheet" href="{link file='frontend/_resources/css/swag_lightbox.css'}" />
{/block}

{* Add javascript *}
{block name="frontend_index_header_javascript_jquery" append}
<script type="text/javascript" src="{link file='frontend/_resources/js/jquery.swag_lightbox.js'}"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('a[rel^=lightbox]').swagLightbox({
			fadeTo: {$SwagLightbox.fadeTo},
			fadeSpeed: {$SwagLightbox.fadeSpeed},
            resizeSpeed: {$SwagLightbox.resizeSpeed}
		});
	});
</script>
{/block}

{* Add image informations *}
{block name="frontend_detail_image" append}
<div class="displaynone thumbnails">
	<div class="thumbs">
		{if $sArticle.image.src.0}
			<span{if $sArticle.ordernumber} class="{$sArticle.ordernumber}"{/if}>{$sArticle.image.src.0}</span>
		{/if}
		{foreach from=$sArticle.images item=sArticleImage}
			{if $sArticleImage.src.0}
				<span{if $sArticleImage.relations} class="{$sArticleImage.relations} {if $sArticleImage.relations != $sArticle.ordernumber}displaynone{/if}"{/if}>{$sArticleImage.src.0}</span>
			{/if}
		{/foreach}
	</div>
	
	<div class="images">
		{if $sArticle.image.src.5}
			<span{if $sArticle.ordernumber} class="{$sArticle.ordernumber}"{/if}>{$sArticle.image.src.5}</span>
		{/if}
		{foreach from=$sArticle.images item=sArticleImage}
			{if $sArticleImage.src.5}
				<span{if $sArticleImage.relations} class="{$sArticleImage.relations} {if $sArticleImage.relations != $sArticle.ordernumber}displaynone{/if}"{/if}>{$sArticleImage.src.5}</span>
			{/if}
		{/foreach}
	</div>
</div>
{/block}

{block name='frontend_detail_image_main'}
	{if $sArticle.image.res.relations}
		<div id="img{$sArticle.image.res.relations}" style="display:none">
			<a href="{$sArticle.image.src.13}"
				title="{$sArticle.supplierName} {$sArticle.articleName}" 
				{if {config name='USEZOOMPLUS'}}class="cloud-zoom-gallery"{/if}
				rel="lightbox">
				
	    		<img src="{$sArticle.image.src.5}" alt="{$sArticle.supplierName} {$sArticle.articleName}" title="{$sArticle.supplierName} {$sArticle.articleName}" />
	    	</a>
		</div>
	{/if}
	<a id="zoom1" href="{$sArticle.image.src.13}" title="{$sArticle.supplierName} {$sArticle.articleName}" {if {config name='USEZOOMPLUS'}}class="cloud-zoom"{/if} rel="lightbox[{$sArticle.ordernumber}]">
	    	
		
	<img src="{$sArticle.image.src.5}" alt="{$sArticle.supplierName} {$sArticle.articleName}" title="{$sArticle.supplierName} {$sArticle.articleName}" />
	</a>
{/block}
