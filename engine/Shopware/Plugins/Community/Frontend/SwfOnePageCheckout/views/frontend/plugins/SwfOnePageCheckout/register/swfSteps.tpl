{block name='frontend_register_steps'}
{namespace name="frontend/register/steps"}
<ul>
	<li id="first_step" {if $sStepActive=='basket'}class="active"{/if}>
		{se class="icon" name="CheckoutStepBasketNumber"}{/se}
		{se class="text" name="CheckoutStepBasketText"}{/se}
	</li>
	<li id="last_step" {if $sStepActive=='register' || $sStepActive=='finished'}class="active"{/if}>
		{se class="icon" name="CheckoutStepRegisterNumber"}{/se}
		{se class="text" name="CheckoutStepConfirmText"}{/se}
	</li>
	<li id="last_step" {if $sStepActive=='swfFinished'}class="active"{elseif !$sUserLoggedIn}class="grey"{/if}>
		{se class="icon" name="CheckoutStepConfirmNumber"}{/se}
		{se class="text" name="CheckoutStepFinishText" namespace="SwfOnePageCheckout"}Bestellbestätigung{/se}
	</li>
</ul>
{/block}