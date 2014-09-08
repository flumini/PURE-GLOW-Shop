{extends file='frontend/checkout/confirm.tpl'}

{block name="frontend_index_header_css_screen" append}
	<link type="text/css" media="all" rel="stylesheet" href="{link file='frontend/plugins/SwfOnePageCheckout/_resources/styles/SwfOnePageCheckout.css'}" />
{/block}

{block name="frontend_index_header_javascript" append}
	<script type="text/javascript" src="{link file='frontend/plugins/SwfOnePageCheckout/_resources/javascript/SwfOnePageCheckout.js'}"></script>
{/block}

{block name='frontend_index_content_left'}{/block}
{block name='frontend_index_content_right'}{/block}

{block name='frontend_index_header_title'}{strip}
	{s name="SwfTitleTag" namespace="SwfOnePageCheckout"}Prüfen und Bestellen{/s}
{/block}

{block name="frontend_index_content"}
	<script type="text/javascript">
		var SwfConfirmOverlayMessage 				= '{s name="SwfConfirmOverlayMessage" namespace="SwfOnePageCheckout"}Bitte geben Sie zunächst Ihre persönlichen Angaben sowie Ihre Adresse an.{/s}';
		var SwfDeleteArticleMessage 				= '{s name="SwfDeleteArticleMessage" namespace="SwfOnePageCheckout"}Möchten Sie den Artikel wirklich aus dem Warenkorb entfernen?{/s}';
		var MaxBasketAmountForStandardItemLayout	= {config name=SwfOnePageCheckout_MaxBasketAmountForStandardItemLayout};
		var SwfBasketQuantity 						= 0;
		var SwfHotfixes						        = {include file="frontend/plugins/SwfOnePageCheckout/_resources/javascript/SwfHotFixes.json"};
		var SwfCustomHotfixes						= {config name=SwfOnePageCheckout_CustomHotFixes};
		var SwfExternalHotfixes						= null;
		var SwfLoadExternalHotfixes					= {if {config name=SwfOnePageCheckout_LoadExternalHotfixes}}true{else}false{/if};
		var SwfInfo                 	= {};
			SwfInfo['cart']             = {};
			SwfInfo['login']            = {};
			SwfInfo['billing']          = {};
			SwfInfo['shipping']         = {};
			SwfInfo['register']         = {};
			SwfInfo['confirm']          = {};
            SwfInfo['payment']          = {};
			SwfInfo['onepagecheckout']  = {};
			SwfInfo['confirmwrapper']   = {};
			SwfInfo['summary']          = {};

			SwfInfo['cart']             ['url']         = '{url controller="checkout" action="confirm" part="cart"}';
			SwfInfo['login']            ['url']         = '{url controller="account" action="login"}';
			SwfInfo['billing']          ['url']         = '{url controller="account" action="billing"}';
			SwfInfo['shipping']         ['url']         = '{url controller="account" action="shipping"}';
			SwfInfo['register']         ['url']         = '{url controller="register"}';
			SwfInfo['confirm']          ['url']         = '{url controller="checkout" action="confirm"}';
            SwfInfo['payment']          ['url']         = '{url controller="account" action="payment"}';
			SwfInfo['onepagecheckout']  ['url']         = '';
			SwfInfo['confirmwrapper']   ['url']         = '';

			SwfInfo['cart']             ['hasContent']  = false;
			SwfInfo['login']            ['hasContent']  = false;
			SwfInfo['billing']          ['hasContent']  = false;
			SwfInfo['shipping']         ['hasContent']  = false;
			SwfInfo['register']         ['hasContent']  = false;
			SwfInfo['confirm']          ['hasContent']  = false;
            SwfInfo['payment']          ['hasContent']  = false;
			SwfInfo['onepagecheckout']  ['hasContent']  = false;
			SwfInfo['confirmwrapper']   ['hasContent']  = false;

			SwfInfo['cart']             ['hasOverlay']  = false;
			SwfInfo['login']            ['hasOverlay']  = false;
			SwfInfo['billing']          ['hasOverlay']  = false;
			SwfInfo['shipping']         ['hasOverlay']  = false;
			SwfInfo['register']         ['hasOverlay']  = false;
			SwfInfo['payment']          ['hasOverlay']  = false;
			SwfInfo['confirm']          ['hasOverlay']  = false;
			SwfInfo['onepagecheckout']  ['hasOverlay']  = false;
			SwfInfo['confirmwrapper']   ['hasOverlay']  = false;
			SwfInfo['summary']          ['hasOverlay']  = false;
	</script>
	<div id="SwfOnepagecheckout">
		
		<div id="SwfTemp">
		</div>

		<div id="SwfCart" class="grid_6">
		</div>

		<div id="SwfCheckout" class="grid_14">

			<div id="SwfLogin">
			
			</div>

			<div id="SwfRegister" class="register">
			
			</div>

			<div id="SwfBilling" class="register">
			
			</div>

			<div id="SwfShipping" class="register">
			
			</div>

			<div id="SwfConfirmwrapper">
				<div id="content">
					<div id="confirm">
                        <div id="SwfPayment">

                        </div>
						<div id="SwfConfirm">
				
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
{/block}
