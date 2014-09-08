{* Footer menu *}
<div class="footer_menu">
	
	<div class="footer_column col1">
		<span class="head">{s name="sFooterServiceHotlineHead"}Service Hotline{/s}</span>
		<p>{s name="sFooterServiceHotline"}Telefonische Unterst&uuml;tzung und Beratung unter:<br /><br /><strong style="font-size:19px;">0180 - 000000</strong><br/>Mo-Fr, 09:00 - 17:00 Uhr{/s}</p>
	</div>
	
	<div class="footer_column col2">
		<span class="head">{s name="sFooterShopNavi1"}Shop Service{/s}</span>
		<ul>
		{foreach from=$sMenu.gBottom item=item  key=key name="counter"}
			<li>
				<a href="{if $item.link}{$item.link}{else}{url controller='custom' sCustom=$item.id title=$item.description}{/if}" title="{$item.description}" {if $item.target}target="{$item.target}"{/if}>
					{$item.description}
				</a>
			</li>
		{/foreach}
		</ul>
	</div>
	<div class="footer_column col3">
		<span class="head">{s name="sFooterShopNavi2"}Informationen{/s}</span>
		<ul>
		{foreach from=$sMenu.gBottom2 item=item key=key name="counter"}
			<li>
				<a href="{if $item.link}{$item.link}{else}{url controller='custom' sCustom=$item.id title=$item.description}{/if}" title="{$item.description}" {if $item.target}target="{$item.target}"{/if}>
					{$item.description}
				</a>
			</li>
		{/foreach}
		</ul>
	</div>
	<div class="footer_column col4 last">
		<span class="head">{s name="sFooterNewsletterHead"}Newsletter{/s}</span>
		<p>
			{s name="sFooterNewsletter"}STAY UP TO DATE - registriere Dich jetzt für den PURE GLOW* Newsletter.
Wir informieren Dich regelmäßig über neue Produkte und Kollektionen, Trends, Aktionen und Events.{/s}
		</p>

<!-- Begin MailChimp Signup Form -->

<div id="mc_embed_signup">
<form action="//pure-glow.us3.list-manage.com/subscribe/post?u=a12d3aa05791ca48f61bd22e4&amp;id=10ba692af6" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
	
<div class="mc-field-group">
	<label for="mce-VORNAME">Vorname </label>
	<input type="text" value="" name="VORNAME" class="required" id="mce-VORNAME">
</div>
<div class="mc-field-group">
	<label for="mce-EMAIL">Mailadresse </label>
	<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
</div>
	<div id="mce-responses" class="clear">
		<div class="response" id="mce-error-response" style="display:none"></div>
		<div class="response" id="mce-success-response" style="display:none"></div>
	</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
    <div style="position: absolute; left: -5000px;"><input type="text" name="b_a12d3aa05791ca48f61bd22e4_10ba692af6" tabindex="-1" value=""></div>
    <div class="clear"><input type="submit" value="Anmelden" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
</form>
</div>
{literal}
<script>// <![CDATA[
var fnames = new Array();var ftypes = new Array();fnames[1]='VORNAME';ftypes[1]='text';fnames[2]='NACHNAME';ftypes[2]='text';fnames[0]='EMAIL';ftypes[0]='email';
try {
    var jqueryLoaded=jQuery;
    jqueryLoaded=true;
} catch(err) {
    var jqueryLoaded=false;
}
var head= document.getElementsByTagName('head')[0];
if (!jqueryLoaded) {
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = '//ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js';
    head.appendChild(script);
    if (script.readyState &#038;&#038; script.onload!==null){
        script.onreadystatechange= function () {
              if (this.readyState == 'complete') mce_preload_check();
        }    
    }
}

var err_style = '';
try{
    err_style = mc_custom_error_style;
} catch(e){
    err_style = '#mc_embed_signup input.mce_inline_error{border-color:#6B0505;} #mc_embed_signup div.mce_inline_error{margin: 0 0 1em 0; padding: 5px 10px; background-color:#6B0505; font-weight: bold; z-index: 1; color:#fff;}';
}
var head= document.getElementsByTagName('head')[0];
var style= document.createElement('style');
style.type= 'text/css';
if (style.styleSheet) {
  style.styleSheet.cssText = err_style;
} else {
  style.appendChild(document.createTextNode(err_style));
}
head.appendChild(style);
setTimeout('mce_preload_check();', 250);

var mce_preload_checks = 0;
function mce_preload_check(){
    if (mce_preload_checks>40) return;
    mce_preload_checks++;
    try {
        var jqueryLoaded=jQuery;
    } catch(err) {
        setTimeout('mce_preload_check();', 250);
        return;
    }
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'http://downloads.mailchimp.com/js/jquery.form-n-validate.js';
    head.appendChild(script);
    try {
        var validatorLoaded=jQuery("#fake-form").validate({});
    } catch(err) {
        setTimeout('mce_preload_check();', 250);
        return;
    }
    mce_init_form();
}
function mce_init_form(){
    jQuery(document).ready( function($) {
      var options = { errorClass: 'mce_inline_error', errorElement: 'div', onkeyup: function(){}, onfocusout:function(){}, onblur:function(){}  };
      var mce_validator = $("#mc-embedded-subscribe-form").validate(options);
      $("#mc-embedded-subscribe-form").unbind('submit');//remove the validator so we can get into beforeSubmit on the ajaxform, which then calls the validator
      options = { url: 'http://pure-glow.us3.list-manage.com/subscribe/post-json?u=a12d3aa05791ca48f61bd22e4&#038;id=10ba692af6&#038;c=?', type: 'GET', dataType: 'json', contentType: "application/json; charset=utf-8",
                    beforeSubmit: function(){
                        $('#mce_tmp_error_msg').remove();
                        $('.datefield','#mc_embed_signup').each(
                            function(){
                                var txt = 'filled';
                                var fields = new Array();
                                var i = 0;
                                $(':text', this).each(
                                    function(){
                                        fields[i] = this;
                                        i++;
                                    });
                                $(':hidden', this).each(
                                    function(){
                                        var bday = false;
                                        if (fields.length == 2){
                                            bday = true;
                                            fields[2] = {'value':1970};//trick birthdays into having years
                                        }
                                    	if ( fields[0].value=='MM' &#038;&#038; fields[1].value=='DD' &#038;&#038; (fields[2].value=='YYYY' || (bday &#038;&#038; fields[2].value==1970) ) ){
                                    		this.value = '';
									    } else if ( fields[0].value=='' &#038;&#038; fields[1].value=='' &#038;&#038; (fields[2].value=='' || (bday &#038;&#038; fields[2].value==1970) ) ){
                                    		this.value = '';
									    } else {
									        if (/\[day\]/.test(fields[0].name)){
    	                                        this.value = fields[1].value+'/'+fields[0].value+'/'+fields[2].value;									        
									        } else {
    	                                        this.value = fields[0].value+'/'+fields[1].value+'/'+fields[2].value;
	                                        }
	                                    }
                                    });
                            });
                        $('.phonefield-us','#mc_embed_signup').each(
                            function(){
                                var fields = new Array();
                                var i = 0;
                                $(':text', this).each(
                                    function(){
                                        fields[i] = this;
                                        i++;
                                    });
                                $(':hidden', this).each(
                                    function(){
                                        if ( fields[0].value.length != 3 || fields[1].value.length!=3 || fields[2].value.length!=4 ){
                                    		this.value = '';
									    } else {
									        this.value = 'filled';
	                                    }
                                    });
                            });
                        return mce_validator.form();
                    }, 
                    success: mce_success_cb
                };
      $('#mc-embedded-subscribe-form').ajaxForm(options);
      /*
 * Translated default messages for the jQuery validation plugin.
 * Locale: DE
 */
jQuery.extend(jQuery.validator.messages, {
	required: "Dieses Feld ist ein Pflichtfeld.",
	maxlength: jQuery.validator.format("Geben Sie bitte maximal {0} Zeichen ein."),
	minlength: jQuery.validator.format("Geben Sie bitte mindestens {0} Zeichen ein."),
	rangelength: jQuery.validator.format("Geben Sie bitte mindestens {0} und maximal {1} Zeichen ein."),
	email: "Geben Sie bitte eine gültige E-Mail Adresse ein.",
	url: "Geben Sie bitte eine gültige URL ein.",
	date: "Bitte geben Sie ein gültiges Datum ein.",
	number: "Geben Sie bitte eine Nummer ein.",
	digits: "Geben Sie bitte nur Ziffern ein.",
	equalTo: "Bitte denselben Wert wiederholen.",
	range: jQuery.validator.format("Geben Sie bitten einen Wert zwischen {0} und {1}."),
	max: jQuery.validator.format("Geben Sie bitte einen Wert kleiner oder gleich {0} ein."),
	min: jQuery.validator.format("Geben Sie bitte einen Wert größer oder gleich {0} ein."),
	creditcard: "Geben Sie bitte ein gültige Kreditkarten-Nummer ein."
});

    });
}
function mce_success_cb(resp){
    $('#mce-success-response').hide();
    $('#mce-error-response').hide();
    if (resp.result=="success"){
        $('#mce-'+resp.result+'-response').show();
        $('#mce-'+resp.result+'-response').html(resp.msg);
        $('#mc-embedded-subscribe-form').each(function(){
            this.reset();
    	});
    } else {
        var index = -1;
        var msg;
        try {
            var parts = resp.msg.split(' - ',2);
            if (parts[1]==undefined){
                msg = resp.msg;
            } else {
                i = parseInt(parts[0]);
                if (i.toString() == parts[0]){
                    index = parts[0];
                    msg = parts[1];
                } else {
                    index = -1;
                    msg = resp.msg;
                }
            }
        } catch(e){
            index = -1;
            msg = resp.msg;
        }
        try{
            if (index== -1){
                $('#mce-'+resp.result+'-response').show();
                $('#mce-'+resp.result+'-response').html(msg);            
            } else {
                err_id = 'mce_tmp_error_msg';
                html = '

<div id="'+err_id+'" style="'+err_style+'"> '+msg+'</div>


';

                var input_id = '#mc_embed_signup';
                var f = $(input_id);
                if (ftypes[index]=='address'){
                    input_id = '#mce-'+fnames[index]+'-addr1';
                    f = $(input_id).parent().parent().get(0);
                } else if (ftypes[index]=='date'){
                    input_id = '#mce-'+fnames[index]+'-month';
                    f = $(input_id).parent().parent().get(0);
                } else {
                    input_id = '#mce-'+fnames[index];
                    f = $().parent(input_id).get(0);
                }
                if (f){
                    $(f).append(html);
                    $(input_id).focus();
                } else {
                    $('#mce-'+resp.result+'-response').show();
                    $('#mce-'+resp.result+'-response').html(msg);
                }
            }
        } catch(e){
            $('#mce-'+resp.result+'-response').show();
            $('#mce-'+resp.result+'-response').html(msg);
        }
    }
}
// ]]></script>
<!--End mc_embed_signup-->
{/literal}
	</div>
	
</div>