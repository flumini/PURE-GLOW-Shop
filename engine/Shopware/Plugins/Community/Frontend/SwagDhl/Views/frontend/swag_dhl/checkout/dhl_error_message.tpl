{block name="frontend_index_content_top" append}
	<div class="grid_20 first">
		{if $dhlErrorMessage}
			<div class="error agb_confirm">
				<div class="center">
					<strong>
						{if $dhlErrorMessage == 'packstation'}
							{s namespace="frontend/checkout/confirm_dispatch" name="errorPackstation"}{/s}
						{elseif $dhlErrorMessage == 'postoffice'}
							{s namespace="frontend/checkout/confirm_dispatch" name="errorPostoffice"}{/s}
						{elseif $dhlErrorMessage == 'postnumber'}
							{s namespace="frontend/checkout/confirm_dispatch" name="errorPostnumber"}{/s}
						{elseif $dhlErrorMessage == 'stationNumber'}
							{s namespace="frontend/checkout/confirm_dispatch" name="errorStationNumber"}{/s}
						{elseif $dhlErrorMessage == 'officeNumber'}
							{s namespace="frontend/checkout/confirm_dispatch" name="errorOfficeNumber"}{/s}
						{elseif $dhlErrorMessage == 'city'}
							{s namespace="frontend/checkout/confirm_dispatch" name="errorCity"}{/s}
						{elseif $dhlErrorMessage == 'zip'}
							{s namespace="frontend/checkout/confirm_dispatch" name="errorZip"}{/s}
						{else}
							ERROR: {$dhlErrorMessage}
						{/if}
					</strong>
				</div>
			</div>
		{/if}
	</div>
{/block}