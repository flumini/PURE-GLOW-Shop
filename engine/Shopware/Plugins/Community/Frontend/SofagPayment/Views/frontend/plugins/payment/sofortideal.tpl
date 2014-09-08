<span id = "sofort_payment_template_ideal" >
{if $sofortIdealIsRecommended}
    <p id = "sofort_payment_text_ideal" >{$sofortIdealRecommendedText}</p >


                {else}


    <br >
{/if}
    {if $sofortIdealIsShowingBanner}
        <a href = "{$sofortIdealLink}" target = "_blank" ><img src = '{$sofortIdealBanner}' alt = '{$sofortIdealAlt}' /></a >
    {/if}
    {if $sofortIdealIsShowingLogo}
        <a href = "{$sofortIdealLink}" target = "_blank" ><img src = '{$sofortIdealLogo}'
                                                               alt = '{$sofortIdealAlt}' /></a >
    {/if}
</span >
<div class = "sofagIdealSelect" style = "text-align:center;" >
    <label for = "sofag_ideal_bank_select" >{$sofortIdealWelcomeMessage}</label >
    <select name = "sofag_ideal_bank_select" id = "sofag_ideal_bank_select" >
        {foreach item=item from=$sofortIdealBanks}
            <option value = "{$item['code']}" >{$item['name']}</option >
        {/foreach}
    </select >
</div >
<script language = 'javascript' >
    $( document ).ready( function ()
    {
        $( "#sofag_ideal_bank_select" ).on( 'change',function ()
        {
            var data = "sofag_ideal_bank_select=" + $( "#sofag_ideal_bank_select" ).val();
            $.ajax( {
                type:  "POST",
                async: false,
                url:   "{url controller='Account' action='savePayment'}",
                data:  data
            } );
        } ).change();
    } );
</script >
