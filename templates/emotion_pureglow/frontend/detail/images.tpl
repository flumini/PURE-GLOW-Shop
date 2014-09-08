<div id='img_1_{$sArticle.ordernumber}' class="displaynone">
	{if $sArticle.image.src.1}
		<a title="{$sArticle.supplierName} {$sArticle.articleName}" class="bundleImg" style="background-image: url({$sArticle.image.src.3});cursor:pointer"></a>
	{else}
		<a title="{$sArticle.supplierName} {$sArticle.articleName}" class="bundleImg" style="background-image: url(../../resource/images/no_picture.jpg);cursor:pointer"></a>
	{/if}
	
	{foreach from=$sArticle.images item=sArticleImage}
    	{if $sArticleImage.relations} 
            <div id="img_1_{$sArticleImage.relations}" class="displaynone"> 
            
            {if $sArticleImage.src.1} 
                    <a title="{$sArticle.supplierName} {$sArticle.articleName}" class="bundleImg" style="background-image: url({$sArticleImage.src.3});cursor:pointer"></a>
            {else} 
					<a title="{$sArticle.supplierName} {$sArticle.articleName}" class="bundleImg" style="background-image: url(../../resource/images/no_picture.jpg);cursor:pointer"></a>
            {/if} 
            </div> 
    	{/if} 
	{/foreach} 
</div>

{* Variant picture *}
{*if $sArticle.image.res.relations}
	<div id="img{$sArticle.image.res.relations}" style="display:none">
	    <a href="{$sArticle.image.src.5}"  title="{$sArticle.supplierName} {$sArticle.image.res.description}" >
	    <img src="{$sArticle.image.src.4}" alt="{$sArticle.supplierName} {$sArticle.articleName}" border="0" title="{$sArticle.supplierName} {$sArticle.articleName}" /> </a>
	</div>
{/if*}

{* Thumbnails *}
{if $sArticle.images}
	<div class="space border">&nbsp;</div>
	<div class="space">&nbsp;</div>
	<div class="thumb_box">
        {if $sArticle.image.src.4}
            <a href="{$sArticle.image.src.5}"
            title="{$sArticle.supplierName} {$sArticle.articleName}"
            style="background-repeat: no-repeat; background-position: center center; background-color:#fff; background-image: url({$sArticle.image.src.12});"
            {if {config name=sUSEZOOMPLUS}}class="cloud-zoom-gallery"{/if}
            rev="{$sArticle.image.src.5}">
        </a>
        {/if}
		{foreach from=$sArticle.images item=sArticleImage}
			{if $sArticleImage.relations}
		
			    {* Main picture variant *}
			    <div id="img{$sArticleImage.relations}" class="displaynone">
			    	<a rel="lightbox[{$sArticleImage.relations}]" 
			    	   {if {config name=sUSEZOOMPLUS}}class="cloud-zoom-gallery"{/if}
			    	   href="{$sArticleImage.src.5}" 
			    	   title="{$sArticle.supplierName} {$sArticle.articleName}">
			    	   <img src="{$sArticleImage.src.5}" title="{$sArticle.articleName}" />
			    	</a>
		   		</div>
		    
			    {* Thumbnail variant *}
			    <a id="thumb{$sArticleImage.relations}" 
			       href="{$sArticleImage.src.5}" 
			       title="{$sArticle.supplierName} {$sArticle.articleName}" 
			       rev="{$sArticleImage.src.5}" 
			       {if {config name=sUSEZOOMPLUS}}class="cloud-zoom-gallery"{/if}
			       style="background-repeat: no-repeat; background-position: center center; background-color:#fff; background-image: url({$sArticleImage.src.12});">
			    </a>
		    {else}
			     <a href="{$sArticleImage.src.5}" 
			        title="{$sArticle.supplierName} {$sArticle.articleName}"
			        rev="{$sArticleImage.src.5}" 
			        {if {config name=sUSEZOOMPLUS}}class="cloud-zoom-gallery"{/if}
			        style="background-repeat: no-repeat; background-position: center center; background-color:#fff; background-image: url({$sArticleImage.src.12});">
			     </a>
		    {/if}
		{/foreach}
		<div class="clear">&nbsp;</div>
	</div>
	<div class="clear">&nbsp;</div>
{/if}