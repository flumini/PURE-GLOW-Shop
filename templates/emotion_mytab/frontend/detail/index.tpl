{* Detailseite des Mastertemplates erben *}
{extends file="parent:frontend/detail/index.tpl"}
 
{block name="frontend_detail_index_tabs_description" append}
    <div id="my_tab">
 
    <h2>{$sArticle.supplierName}</h2>
 
    {$sArticle.supplierDescription}
	</div>
{/block}
