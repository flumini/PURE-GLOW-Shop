{block name="frontend_index_start"}
{/block}

{block name="frontend_index_doctype"}
{/block}

{block name='frontend_index_header'}
	{include file='frontend/plugins/SwfOnePageCheckout/index/swfHeader.tpl'}
{/block}

{* Message if javascript is disabled *}
{block name="frontend_index_no_script_message"}
{/block}

{block name='frontend_index_before_page'}
{/block}

{* Shop header *}
{block name='frontend_index_navigation'}

	{* Language and Currency bar *}
	{block name='frontend_index_actions'}
	{/block}
	
	{* Shop logo *}
	{block name='frontend_index_logo'}
	{/block}

	{* Shop navigation *}
	{block name='frontend_index_checkout_actions'}
		{include file='frontend/plugins/SwfOnePageCheckout/index/swfCheckoutActions.tpl'}
	{/block}
	
	{block name='frontend_index_navigation_inline'}
	{/block}

{/block}

{* Maincategories navigation top *}
{block name='frontend_index_navigation_categories_top'}
{/block}

{* Search *}
{block name='frontend_index_search'}
{/block}

{* Breadcrumb *}
{block name='frontend_index_breadcrumb'}
{/block}

{* Content top container *}
{block name="frontend_index_content_top"}{/block}
		
{* Sidebar left *}
{block name='frontend_index_content_left'}
{/block}
		
<div id="Swf{$swfPart}Resource">
	{* Main content *}
	{block name='frontend_index_content'}{/block}
</div>
		
{* Sidebar right *}
{block name='frontend_index_content_right'}{/block}

{* Footer *}
{block name="frontend_index_footer"}
{/block}

{block name="frontend_index_shopware_footer"}
{/block}
{block name='frontend_index_body_inline'}
{/block} 