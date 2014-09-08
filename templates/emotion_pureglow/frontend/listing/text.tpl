
{* Categorie headline *}
{block name="frontend_listing_text"}
{if $sCategoryContent.cmsheadline}
	<div class="cat_text">
		<div class="inner_container">
		<div class="mapping"><img src="{$sCategoryContent.media.path}"/></div>
		    <h1>{$sCategoryContent.cmsheadline}</h1>
		    
		    {if $sCategoryContent.cmstext}
		    	{$sCategoryContent.cmstext}
		    {/if}
		    
		    
	    </div>
	</div>
{/if}
{/block}