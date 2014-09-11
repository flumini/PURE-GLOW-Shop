{extends file="parent:frontend/index/footer.tpl"}

<div id="footer">

	{block name='frontend_index_footer_menu'}
		{include file='frontend/index/menu_footer.tpl'}
		<div class="clear"></div>
	{/block}

</div>

	{block name='frontend_index_footer_copyright'}
	<div class="bottom">
		{block name='frontend_index_footer_vatinfo'}
		<div class="footer_info">
		<p><img src="media/image/Zahlungsmittel.jpg" alt="Zahlungsmittel" /></p>
		<div class="social-cont" style="opacity: 1; top: 56px;">
		<ul class="social-menu">
	    <li><a class="glyph hvr" style="font-size: 38px" href="http://eepurl.com/2uPKT" target="_blank">✉</a></li>
	    <li><a class="glyph hvr" style="font-size: 30px" data-icon="&#xe0e9;" target="_blank" href="http://www.facebook.com/PureGlowHamburg"></a></li>	
	    <li><a class="glyph hvr" style="font-size: 30px" data-icon="&#xe0fe;" target="_blank" href="http://www.instagram.com/pureglow"></a></li>	
		</ul>
		</div>
            {if $sOutputNet}
				<p>{s name='FooterInfoExcludeVat'}&nbsp;{/s}</p>
			{else}
				<p>{s name='FooterInfoIncludeVat'}&nbsp;{/s}</p>
			{/if}
		</div>
		{/block}
		<div class="footer_copyright">
			<span>{s name="IndexCopyright"}Copyright &copy; 2012 shopware AG{/s}</span>
		</div>
	</div>
	{/block}
