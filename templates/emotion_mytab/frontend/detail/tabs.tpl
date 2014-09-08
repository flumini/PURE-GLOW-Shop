{extends file='parent:frontend/detail/tabs.tpl'}
 
{* Neuen Reiter fuer die Tabnavigation hinzufuegen *}
{block name="frontend_detail_tabs_related" append}
	<li>
		<a href="#my_tab">{se name='DetailTabsMyTab'}Mehr über{/se} {$sArticle.supplierName}</a>
	</li>
{/block}