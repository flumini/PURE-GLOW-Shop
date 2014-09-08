{if true == $dreiscArticleSupplierLogoStyles.enableScale}
    {if $sCategoryContent.template eq "article_listing_1col.tpl"}
        {assign var="dreiscSupplierLogoWidth" value=$dreiscArticleSupplierLogoStyles.oneColWidth}
        {assign var="dreiscSupplierLogoTemplate" value="listing-1col"}
    {elseif $sCategoryContent.template eq "article_listing_2col.tpl"}
        {assign var="dreiscSupplierLogoWidth" value=$dreiscArticleSupplierLogoStyles.twoColWidth}
        {assign var="dreiscSupplierLogoTemplate" value="listing-2col"}
    {elseif $sCategoryContent.template eq "article_listing_3col.tpl"}
        {assign var="dreiscSupplierLogoWidth" value=$dreiscArticleSupplierLogoStyles.threeColWidth}
        {assign var="dreiscSupplierLogoTemplate" value="listing-3col"}
    {elseif $sCategoryContent.template eq "article_listing_4col.tpl"}
        {assign var="dreiscSupplierLogoWidth" value=$dreiscArticleSupplierLogoStyles.fourColWidth}
        {assign var="dreiscSupplierLogoTemplate" value="listing-4col"}
    {else}
        {assign var="dreiscSupplierLogoWidth" value=$dreiscArticleSupplierLogoStyles.fourColWidth}
        {assign var="dreiscSupplierLogoTemplate" value="listing-4col"}
    {/if}
{/if}

{if $sArticle.supplierImg}
<div class="dreisc_article_supplier dreisc_article_supplier_{$dreiscSupplierLogoTemplate}" style=""><img src="{if true == $dreiscArticleSupplierLogoStyles.enableScale}{url controller=DreiscArticleSupplierLogo action=get}?path={$sArticle.supplierImg}&w={$dreiscSupplierLogoWidth}{else}{$sArticle.supplierImg}{/if}"></div>
{/if}