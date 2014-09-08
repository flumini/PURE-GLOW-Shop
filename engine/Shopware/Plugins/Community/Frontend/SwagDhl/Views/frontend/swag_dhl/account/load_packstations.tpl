{*This template initialize the google maps and load the packstations results via javascript*}
{extends file='frontend/index/header.tpl'}

{* javascript to close the modal window and returns the form values to parent form *}
{block name="frontend_index_header_javascript" append}
	<script type="text/javascript">

		$(document).ready(function () {
			$("#userPackstation").on('submit', function (e) {
				e.preventDefault();

				var packStationId = document.getElementById('psId').value,
					zip = document.getElementById('zip').value,
					city = document.getElementById('city2').value,
					street = document.getElementById('streetName').value,
					streetNumber = document.getElementById('streetNumber').value;

				window.parent.updatePackstationShipping(packStationId, zip, city, street, streetNumber);

				parent.$.modalClose();
			});

			$("div.content").on('click', function (e) {
				e.preventDefault();

				var container = $(e.currentTarget);
				var packStationId = container.find('span.id').html(),
					zip = container.find('span.zip').html(),
					city = container.find('span.city').html(),
					street = container.find('span.street').html(),
					streetNumber = container.find('span.streetNumber').html();

				$('#psId').val(packStationId);
				$('#streetName').val(street);
				$('#streetNumber').val(streetNumber);
				$('#zip').val(zip);
				$('#city2').val(city);
			});
		});

	</script>
	{* Google maps script Loader *}
	{* The mapKey is obtained from Plugin Configuration*}
	<script src="https://maps.googleapis.com/maps/api/js?key={$mapKey}&sensor=false"></script>
	{* Error messages from DHL webservice*}
	<div style="padding: 10px">
		<span style="color: #ff0000">{$errorMessage}</span>
	</div>
	{* Form to get city and PLZ for making SOAP call*}
	<div style="padding-left: 10px">
		<form name="frmPack" method="POST" action="{url controller=dhl action=getPackstations sTarget=$sTarget}">
			{*zip code*}
			<label for="plz">{s name="zipCode" namespace="frontend/account/iFrameSearch"}Bitte geben Sie eine Postleitzahl an:{/s} </label>
			<input type="text" name="plz" id="plz" size="5" value="{$zip}"/><br>
			{*city*}
			<label for="city">{s name="city" namespace="frontend/account/iFrameSearch"}Bitte geben Sie einen Ort an:{/s} </label>
			<input type="text" name="city" id="city" size="25" value="{$city}"/>
			<input type="submit" value="{s namespace="frontend/checkout/confirm_dispatch" name="search"}Suchen{/s}"/>
		</form>
	</div>
	{* Form to fetch values of user selected packstation address *}
	<div style="padding-left: 10px">
		<form id="userPackstation" name="userPackstation" method="POST"
			  action="{url controller=dhl action=selectShipping sTarget=$sTarget pa=true }">
			{*packstation ID*}
			<input type="text" id="psId" name="psId" size="10">
			{*streetName*}
			<input type="text" id="streetName" name="streetName" size="10">
			{*StreetNumber*}
			<input type="text" id="streetNumber" name="streetNumber" size="10">
			{*zip*}
			<input type="text" id="zip" name="zip" size="10">
			{*city*}
			<input type="text" id="city2" name="city2" size="10">
			<input type="submit" value="{s namespace="frontend/register/shipping_fieldset" name='ShippingLinkSend'}Übernehmen{/s}"/>
		</form>
	</div>
	<style>
		.content {
			margin-bottom: 5px;
		}

		.content:hover {
			background-color: #ededed;
			cursor: pointer;
		}

		.enumeration {
			float: left;
			height: 50px;
			width: 30px;
		}

		.clear {
			clear: both;
		}

		#leftColumn {
			width: 28%;
			vertical-align: top;
			padding-bottom: 1px;
			background-color: white;
			color: black;
			font-family: 'Helvetica', 'Arial', sans-serif;
		}

		#leftContent {
			overflow: auto;
			height: 20%;
		}

		.distance {
			float: right;
			height: 50px;
		}
	</style>
	{* iframe display of google maps with the sidepanel *}
	<table>
		{*sidepanel to display the address of all the packstations*}
		<tr>
			{if $packstations|is_array}
				<td id="leftColumn">
					<div id="leftContent">
						{* $packstations are passed from frontend dhl controller after the DHL SOAP call*}
						{* Check with soapUI to see the complete response attributes from DHL*}
						{if isset ($packstations)}
							{foreach $packstations as $key => $packstation}
								{$packstationId = $packstation->packstationId}
								{$address = $packstation->address}
								{$street = $address->street}
								{$streetNumber = $address->streetNo}
								{$zip = $address->zip}
								{$city = $address->city}
								{$location = $packstation->location}
								{$distance = $packstation->distance}
								{if isset($packstationId)}
									<div class="content">
										<div class="enumeration">
											{$key+1}.
										</div>
										<div id="locationAddress">
											<div class="distance">
												{if $distance > 1000}
													{($distance / 1000)|round:2}km
												{else}
													{$distance}m
												{/if}
											</div>
											<div>{s namespace="frontend/account/iFrameSearch" name='packstation'}Packstation{/s}
												<span class="id">{$packstationId}</span>
											</div
											<div>
												<span class="street">{$street}</span>
												<span class="streetNumber">{$streetNumber}</span>
											</div>
											<div>
												<span class="zip">{$zip}</span>
												<span class="city">{$city}</span>
											</div>
										</div>
										<div class="clear"></div>
									</div>
								{/if}
							{/foreach}
						{/if}
					</div>
				</td>
			{/if}
			{* map is displayed in this canvas and should be loaded before the showLocation() javascript method*}
			<td style="vertical-align:top;">
				<div id="googleMap" style="width:600px;height:600px;"></div>
			</td>
		</tr>
	</table>
	{if $mapKey}
		<script type="text/javascript">
			{* Method to display packstations on the Google maps*}
			function showLocation() {
				{* Creating an array of all the parameters required *}
				var packstationIdArray = [];
				var locationArray = [];
				var distanceArray = [];
				var streetArray = [];
				var streetNumberArray = [];
				var zipArray = [];
				var cityArray = [];
				var latArray = [];
				var lngArray = [];

				{if isset($packstations)}
				{foreach $packstations as $packstation}
				{$packstationId = $packstation->packstationId}
				{$location = $packstation->location}
				{$lat = $location->latitude}
				{$lng = $location->longitude}
				{$distance = $location->distance}

				{$address = $packstation->address}
				var streetName = "{$address->street}";
				var streetNumber = "{$address->streetNo}";
				var zip = "{$address->zip}";
				var city = "{$address->city}";

				packstationIdArray.push({$packstationId});
				locationArray.push({$lat}, {$lng});
				distanceArray.push({$distance});
				streetArray.push(streetName);
				streetNumberArray.push(streetNumber);
				zipArray.push(zip);
				cityArray.push(city);
				latArray.push({$lat});
				lngArray.push({$lng});
				{/foreach}
				{/if}

				{* initially loads the map with some random latitude and longitude*}
				var myCenter = new google.maps.LatLng(50.332015318952, 9.4);
				var mapProp = {
					center: myCenter,
					zoom: 6,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
				{* information displayed on the marker click event*}
				var infowindow = new google.maps.InfoWindow();

				{* marker for each packstation *}
				var marker, i;
				var bounds = new google.maps.LatLngBounds();
				for (i = 0; i < latArray.length; i++) {
					var latLng = new google.maps.LatLng(latArray[i], lngArray[i]);

					marker = new google.maps.Marker({
						position: latLng,
						map: map,
						animation: google.maps.Animation.DROP
					});
					bounds.extend(latLng);

					marker.set('packstationId', packstationIdArray[i]);
					marker.set('distance', distanceArray[i]);
					marker.set('street', streetArray[i]);
					marker.set('streetNumber', streetNumberArray[i]);
					marker.set('zip', zipArray[i]);
					marker.set('city', cityArray[i]);

					google.maps.event.addListener(marker, 'click', (function (marker, i) {
						return function () {
							var packstationId = this.get('packstationId');
							var distance = this.get('distance');
							var street = this.get('street');
							var streetNumber = this.get('streetNumber');
							var zip = this.get('zip');
							var city = this.get('city');
							var html = street + ' ' + streetNumber + '<br>' + zip + '<br>' + city + '<br>';
							infowindow.setContent(html);
							infowindow.maxWidth = 400;
							infowindow.open(map, marker);

							{* Update the userPackstation form to update the shipping address *}
							document.getElementById('psId').value = packstationId;
							document.getElementById('streetName').value = street;
							document.getElementById('streetNumber').value = streetNumber;
							document.getElementById('zip').value = zip;
							document.getElementById('city2').value = city;
						}
					})(marker, i));
				}
				map.fitBounds(bounds);
			}
			showLocation();
		</script>
	{/if}
{/block}