{block name="frontend_detail_description"}
<div id="description">
	
	{* Headline *}
	{block name='frontend_detail_description_title'}
		<h2>{s name="DetailDescriptionHeader"}{/s} "{$sArticle.articleName}"</h2>
	{/block}
	
	{* Properties *}
	{if $sArticle.sProperties}
		{block name='frontend_detail_description_properties'}
		<table cellspacing="0">
			{foreach from=$sArticle.sProperties item=sProperty}
				<tr>
					<td>
						{$sProperty.name}
					</td>
					<td>
						{$sProperty.value}
					</td>
				</tr>	
			{/foreach}
		</table>
		{/block}
	{/if}
	
	{* Article description *}
	{block name='frontend_detail_description_text'}
	{$sArticle.description_long|replace:"<table":"<table id=\"zebra\""}

	{if empty($sArticle.attr2) && $sArticle.attr2 != "Beauty"}
	<p><strong>Größen</strong>: Dieser Artikel fällt größengerecht aus</p>
	{elseif $sArticle.attr2 != "Beauty"}
	<p><strong>Größen</strong>: {$sArticle.attr2}</p>
	{/if}
	
	{/block}
	
	
	{* Downloads *}
	{block name='frontend_detail_description_downloads'}
	{if $sArticle.sDownloads}
		<div class="space">&nbsp;</div>
		<h2>{se name="DetailDescriptionHeaderDownloads"}{/se}</h2>
		
		{foreach from=$sArticle.sDownloads item=download}
			<a href="{$download.filename}" target="_blank" class="ico link">
				{se name="DetailDescriptionLinkDownload"}{/se} {$download.description}
			</a>		
		{/foreach}
	{/if}
	{/block}
	
		
	{* Our comment *}
	{if $sArticle.attr3}
		{block name='frontend_detail_description_our_comment'}
		<div class="space">&nbsp;</div>
		<div id="unser_kommentar">
			<h2>{se name='DetailDescriptionComment'}{/se} "{$sArticle.articleName}"</h2>
			<blockquote>{$sArticle.attr3}</blockquote>
		</div>	
		{/block}
	{/if}
	

									
	
</div>

		{if $sArticle.supplierDescription}
        <div class="space">&nbsp;</div>
 
 		<div id="Hersteller">
        	<h2>Über {$sArticle.supplierName}</h2>
                {$sArticle.supplierDescription}
		</div>
    	{/if}

{/block}