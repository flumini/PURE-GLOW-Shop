{* Main content *}
{block name="frontend_index_content"}
    {block name='frontend_account_payment_error_messages'}
        {include file="frontend/register/error_message.tpl" error_messages=$sErrorMessages}
    {/block}
    <div class="payment_method SwfBox">
        <h3 class="underline">{s name='CheckoutPaymentHeadline'}Zahlungsart{/s}</h3>
        <form name="frmRegister" method="post" action="{url action=savePayment sTarget=$sTarget}" class="payment">

            {include file='frontend/plugins/SwfOnePageCheckout/register/swfPaymentFieldset.tpl' form_data=$sFormData error_flags=$sErrorFlag payment_means=$sPaymentMeans}

            {block name="frontend_account_payment_action_buttons"}
            {/block}
        </form>
    </div>
{/block}
