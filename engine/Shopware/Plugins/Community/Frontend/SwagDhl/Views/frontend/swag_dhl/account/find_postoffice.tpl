{* iFrame window to load the google maps for postoffices finder *}
{*needs to be extended for googlemaps sidePanel *}

<div>
	<iframe id="frame" src="{url controller=dhl action=getPostoffices plz=$zip city=$city sTarget=$sTarget}" width="1000" height="800">
	</iframe>
</div>