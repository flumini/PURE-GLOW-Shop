{block name='frontend_detail_index_supplier'}
{if $ReihenSupplierImgConfig->name == 'Nein'}
<p class="supplier">{se name="DetailFromNew"}Hersteller:{/se} {$sArticle.supplierName}</p>
{/if}
{if $ReihenSupplierImgConfig->link == 'Ja'}
<a href="{url controller='supplier' sSupplier=$sArticle.supplierID}" target="{$information.target}">
{/if}
<img src="{$sArticle.supplierImg}" alt="{$sArticle.supplierName}" style="{if $ReihenSupplierImgConfig->width != ''}width: {$ReihenSupplierImgConfig->width}px;{/if}{if $ReihenSupplierImgConfig->margintop != ''}margin-top: {$ReihenSupplierImgConfig->margintop}px;{/if}{if $ReihenSupplierImgConfig->marginleft != ''}margin-left: {$ReihenSupplierImgConfig->marginleft}px;{/if}{if $ReihenSupplierImgConfig->marginright != ''}margin-right: {$ReihenSupplierImgConfig->marginright}px;{/if}{if $ReihenSupplierImgConfig->marginbottom != ''}margin-bottom: {$ReihenSupplierImgConfig->marginbottom}px;{/if}"/>
{if $ReihenSupplierImgConfig->link == 'Ja'}
</a>
{/if}
{/block}