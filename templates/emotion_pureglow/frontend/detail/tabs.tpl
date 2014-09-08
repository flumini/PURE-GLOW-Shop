{extends file='parent:frontend/detail/tabs.tpl'}

{block name="frontend_detail_tabs"}
<a id="write_comment">&nbsp;</a>
<ul>

	{block name="frontend_detail_tabs_description"}
		<li>
			<a href="#description">{se name='DetailTabsDescription'}{/se}</a>
		</li>
	{/block}
		

	{block name="frontend_detail_description_supplier"}
      <li>
         <a href="#Hersteller">Mehr über {$sArticle.supplierName}</a>
      </li>
   {/block}
	
	{block name="frontend_detail_tabs_rating"}
	
	{if !{config name=VoteDisable}}
		<li>
			<a href="#comments">
				<span>
					{s name='DetailTabsRating'}{/s} 
					
					{if $sArticle.sVoteAverange.count}
						[{$sArticle.sVoteAverange.count}]
					{/if}
				</span>
				
				{if $sArticle.sVoteAverange.count}
                    <span class="star star{$sArticle.sVoteAverange.averange}">Star Rating</span>
   				{/if}
			</a>
		</li>
	{/if}
	{/block}

</ul>
{/block}