<div id="SwfHeadResource{$swfPart}">
	{* Http-Tags *}
	{block name="frontend_index_header_meta_http_tags"}
	{/block}

	{* Meta-Tags *}
	{block name='frontend_index_header_meta_tags'}
	{/block}

	{* Internet Explorer 9 specific meta tags *}
	{block name='frontend_index_header_meta_tags_ie9'}
	{/block}

	{* Canonical link *}
	{block name='frontend_index_header_canonical'}{/block}

	{* RSS and Atom feeds *}
	{block name="frontend_index_header_feeds"}
	{/block}

	{* Page title *}
	<title>{block name='frontend_index_header_title'}{strip}
	{if $sBreadcrumb}{foreach from=$sBreadcrumb|array_reverse item=breadcrumb}{$breadcrumb.name} | {/foreach}{/if}{config name=sShopname}
	{/strip}{/block}</title>

	{* Stylesheets and Javascripts *}
	{block name="frontend_index_header_css_screen"}
	{/block}

	{* Print Stylesheets *}
	{block name="frontend_index_header_css_print"}
	{/block}

	{block name="frontend_index_header_javascript_jquery_lib"}
	{/block}

	{block name="frontend_index_header_javascript"}
		<script type="text/javascript">
		//<![CDATA[
		{block name="frontend_index_header_javascript_inline"}
		{/block}
		//]]>
		</script>
		{block name="frontend_index_header_javascript_jquery"}
		    {if !{config name=disableShopwareStatistics} }
		        {include file='widgets/index/statistic_include.tpl'}
		    {/if}
		{/block}
	{/block}


	{block name="frontend_index_header_css_ie"}
	{/block}

	{* remove CSS3Pie *}
	{block name="frontend_index_header_css_ie_screen"}{/block}
</div>