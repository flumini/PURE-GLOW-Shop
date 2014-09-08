var SwfConfig = {
    errorClass: 'instyle_error',
    successClass: 'instyle_success'
};

var SwfUserLoggedIn 	= false;
var SwfTimeout      	= 30 * 1000;
var SwfGettingAjaxCart	= false;
var SwfSendBilling		= false;
var SwfSendShipping		= false;
var SwfBillingFilled    = false;

$(document).ready(function(){
    SwfAddOverlay('onepagecheckout');
    if(SwfLoadExternalHotfixes)
    	SwfGetExternalHotfixes();
    SwfGet('cart');
    SwfGet('login');
    SwfGet('register');
    SwfGet('payment');
    SwfGet('confirm');
});

function SwfGetExternalHotfixes()
{
	$.ajax({
		type: "GET",
		url: "//www.shopware-factory.de/opc/hotfix.jsonp",
		dataType: "jsonp",
		async: false
	});
}

function SwfInitializeExternalHotfixes(json)
{
	SwfExternalHotfixes = json;
}

function SwfCheckAndSendBilling(callback)
{
    SwfBillingFilled = false;
	var complete = true;
    $('#SwfBilling input.required:visible, #SwfBilling select.required:visible').each(function(){
        if($(this).val() == ''
        || $(this).hasClass('instyle_error')
        || typeof($(this).val()) == 'undefined')
        {
            complete = false;
            return;
        }
    });
    if(complete)
    {
        if($.trim($('#phone').val()) == '')
    	{
    		$('#phone').val('---');
    	}
        if(!callback)
    	{
    		callback = 	function(){
	        				SwfGet('confirm');
	        				SwfGet('cart', null, 'summary');
	    				}
    	}
    	SwfPost('billing', $('#SwfBilling form'), callback);
    }
}

function SwfCheckAndSendShipping(callback)
{
    var complete = true;
    $('#SwfShipping input.required:visible, #SwfShipping select.required:visible').each(function(){
        if($(this).val() == ''
        || $(this).hasClass('instyle_error')
        || typeof($(this).val()) == 'undefined')
        {
            complete = false;
            return;
        }
    });
    if(complete)
    {
    	if(!callback)
    	{
    		callback = 	function(){
	        				SwfGet('confirm');
	        				SwfGet('cart', null, 'summary');
	    				}
    	}
    	SwfPost('shipping', $('#SwfShipping form'), callback);
    }

}

function SwfGet(part, callback, overlay, initialize)
{
	initialize = typeof initialize !== 'undefined' ? initialize : true;

    if(SwfInfo[part]['getting'] == true)
        return;

    SwfInfo[part]['getting'] = true;

    if(!SwfInfo['onepagecheckout']['hasOverlay'])
    {
        if(overlay)
            SwfAddOverlay(overlay);
        else
            SwfAddOverlay(part);
    }

    $.ajax({
        type: "GET",
        async: true,
        timeout: SwfTimeout,
        dataType : 'html',
        beforeSend: function(xhr) {
        xhr.setRequestHeader(
            'X-Requested-With',
            {
                toString: function() { return ''; }
            });
        },
        url: SwfInfo[part]['url'] + "?SwfOnePageCheckout=true",
        success: function(html) {
            if(overlay)
                SwfRemoveOverlay(overlay);
            else
                SwfRemoveOverlay(part);

            SwfInfo[part]['getting'] = false;
            if(initialize)
            	SwfInitialize(part, html);
            if(callback)
                callback();
        },
        error: function() {
            if(overlay)
                SwfRemoveOverlay(overlay);
            else
                SwfRemoveOverlay(part);

            SwfInfo[part]['getting'] = false;
        }
    });
}

function SwfRemove(part)
{
    if(SwfInfo[part]['hasContent'] == false)
        return;

    var element = $('#Swf' + part.capitalize());
    element.html('');

    SwfInfo[part]['hasContent'] = false;
}

function SwfRefreshHtml(part, html)
{
    if(html.indexOf('Swf' + part.capitalize() + 'Resource') === -1)
        return;

    html = html.replace(/http\:/gi,'');
    html = html.replace(/https\:/gi,'');


    html = html.replace(/\<script/gi, '<pausescript style="display: none;"');
    html = html.replace(/\<\/script\>/gim, '<\/pausescript\>');

	var SwfPartElement = document.getElementById('Swf' + part.capitalize());

    $(SwfHotfixes).each(function(){
		html = html.replace(this.search,this.replace);
	});
	$(SwfCustomHotfixes).each(function(){
		html = html.replace(this.search,this.replace);
	});
	$(SwfExternalHotfixes).each(function(){
		html = html.replace(this.search,this.replace);
	});

    SwfPartElement.innerHTML = html;

    if(part == 'cart')
    {
        $('#shopnavi').html($('#SwfShopnaviResource').html());
    }

    var re = /<pausescript\b[^>]*>([\s\S]*?)<\/pausescript>/gmi;

    while (match = re.exec(html)) {
        var encoded = swf_base64_encode(match[1]);
        el = $(match[0].replace(match[1], 'eval(swf_base64_decode("' + encoded + '"));'));
        if(typeof ($(el).attr('src')) === 'undefined')
        {
            if($('#' + md5($(el).text())).length)
            {
                $('#' + md5($(el).text())).remove();
                var appendScript = document.createElement('script'); appendScript.type = 'text/javascript';
                try {
                    appendScript.innerHTML = $(el).html();
                }
                catch(e){
                    appendScript.text = $(el).html();
                }
                appendScript.id = md5($(el).text());
                (document.getElementsByTagName('head')[0]).appendChild(appendScript);
                $(el).remove();
            }
            else
            {
                var appendScript = document.createElement('script'); appendScript.type = 'text/javascript';
                try {
                    appendScript.innerHTML = $(el).html();
                }
                catch(e){
                    appendScript.text = $(el).html();
                }
                appendScript.id = md5($(el).text());
                (document.getElementsByTagName('head')[0]).appendChild(appendScript);
                $(el).remove();
            }
        }
        else
        {
            if($('head').html().indexOf($(el).attr('src')) === -1)
            {
                var appendScript = document.createElement('script'); appendScript.type = 'text/javascript';
                appendScript.src = $(el).attr('src');
                (document.getElementsByTagName('head')[0]).appendChild(appendScript);
                $(el).remove();
            }
            else
            {
                if($(el).attr('src').indexOf('jquery.1.') 	< 0
                    && $(el).attr('src').indexOf('swfEasyCheckout.js') 	< 0
                    && $(el).attr('src').indexOf('jquery.shopware.js') 	< 0
                    && $(el).attr('src').indexOf('jquery.emotion.js') 	< 0)
                {
                    $('[src="' + $(el).attr('src') + '"]').remove();
                    var replaceScript = document.createElement('script'); replaceScript.type = 'text/javascript';
                    replaceScript.src = $(el).attr('src');
                    (document.getElementsByTagName('head')[0]).appendChild(replaceScript);
                }
                $(el).remove();
            }
        }
    }

    $(SwfPartElement).find('style').each(function() {
        if($(this).text() != '')
        {
            if($('head').html().indexOf($(this).text()) === -1)
            {
                var appendStyle = document.createElement('style'); appendStyle.type = 'text/css';
                appendStyle.innerHTML = $(this).text();
                (document.getElementsByTagName('head')[0]).appendChild(appendStyle);
                $(this).remove();
            }
        }
    });

    $(SwfPartElement).find('link').each(function() {
        if(typeof ($(this).attr('href')) !== 'undefined')
        {
            if($('head').html().indexOf($(this).attr('href')) === -1)
            {
                var appendLink = document.createElement('link'); appendLink.type = 'text/css'; appendLink.rel = 'stylesheet';
                appendLink.href = $(this).attr('href');
                (document.getElementsByTagName('head')[0]).appendChild(appendLink);
                $(this).remove();
            }
        }
    });

    $('#Swf' + part.capitalize()).html($('#Swf' + part.capitalize() + 'Resource').html());
}

function SwfPost(part, form, callback)
{
    if(SwfInfo[part]['posting'] == true)
        return;
    SwfInfo[part]['posting'] = true;

    SwfAddOverlay(part);

    if(form)
    {
    	url = $(form).attr('action');
    }
    else
    {
    	url = SwfInfo[part]['url'];
    }

    $.ajax({
        type: "POST",
        async: true,
        timeout: SwfTimeout,
        url: url + '?SwfOnePageCheckout=true',
        data: $(form).serialize(),
        dataType : 'html',
        beforeSend: function(xhr) {
        xhr.setRequestHeader(
            'X-Requested-With',
            {
                toString: function() { return ''; }
            });
        },
        success: function(html) {
            SwfRemoveOverlay(part);
            SwfInfo[part]['posting'] = false;
            SwfInitialize(part, html);
            if(callback)
                callback();
        },
        error: function() {
            SwfRemoveOverlay(part);
            SwfInfo[part]['posting'] = false;
        }
    });
}

function SwfAddOverlay(part)
{
    if(SwfInfo[part]['hasOverlay'] == true)
        return;
    SwfInfo[part]['hasOverlay'] = true;

    element = $('#Swf' + part.capitalize());
    if(!element.find('.SwfAjaxLoaderOverlay').length)
    {
        element.append('<div class="SwfAjaxLoaderOverlay" id="SwfOverlay' + part + '"><div class="SwfAjaxLoaderImage"></div></div>');
    }
}

function SwfOverlayMessage(msg) {
    setTimeout(function() {
       if(!SwfBillingFilled) {
           alert(msg);
       }
    }, 600);
}

function SwfAddClickableOverlay(part)
{
    if(SwfInfo[part]['hasOverlay'] == true)
        return;
    SwfInfo[part]['hasOverlay'] = true;

    element = $('#Swf' + part.capitalize());
    if(!element.find('.SwfAjaxLoaderOverlay').length)
    {
        element.append('<div class="SwfAjaxLoaderOverlay clickable" onclick="SwfOverlayMessage(SwfConfirmOverlayMessage)" id="SwfAddClickableOverlay' + part + '"></div>');
    }
}

function SwfRemoveClickableOverlay(part)
{
    if(SwfInfo[part]['hasOverlay'] == false)
        return;
    SwfInfo[part]['hasOverlay'] = false;

    element = $('#Swf' + part.capitalize());
    if(element.find('#SwfAddClickableOverlay' + part).length)
    {
        element.find('#SwfAddClickableOverlay' + part).remove();
    }
}

function SwfRemoveOverlay(part)
{
    if(SwfInfo[part]['hasOverlay'] == false)
        return;
    SwfInfo[part]['hasOverlay'] = false;

    element = $('#Swf' + part.capitalize());
    if(element.find('#SwfOverlay' + part).length)
    {
        element.find('#SwfOverlay' + part).remove();
    }
}

$.fn.SwfGetLinkByAjax = function(part, callback, confirmMessage)
{
    $(this).on('click', function(e){
        e.preventDefault;
        if(confirmMessage && !confirm(confirmMessage))
        {
            return false;;
        }
        SwfAddOverlay(part);
        $.ajax({
            type: "GET",
            async: true,
            timeout: SwfTimeout,
            url: $(this).attr('href') + '?SwfOnePageCheckout=true',
            success: function(html) {
                SwfRemoveOverlay(part);
                SwfInitialize(part, html);
                if(callback)
                    callback();
            }
        });
        return false;
    });
}

function SwfInitialize(part, html)
{
    if($('#SwfUserLoggedIn', html).val() == 'true')
    {
        SwfUserLoggedIn = true;
    }
    if($('#SwfUserLoggedIn', html).val() == 'false')
    {
        SwfUserLoggedIn = false;
    }
    if($('#SwfBasketHasContent', html).val() == 'false')
    {
        return document.location = "/checkout";
    }

    SwfInfo[part]['hasContent'] = true;

    if(SwfUserLoggedIn == false)
    {
        if(SwfInfo['billing']['hasContent'])
            SwfRemove('billing');
        if(SwfInfo['shipping']['hasContent'])
            SwfRemove('shipping');

        if(!SwfInfo['register']['hasContent'])
            SwfGet('register');
        if(!SwfInfo['login']['hasContent'])
            SwfGet('login');

        if(SwfInfo['onepagecheckout']   ['hasOverlay']
        //&& SwfInfo['cart']              ['hasContent']   == true
        && SwfInfo['register']          ['hasContent']   == true
        && SwfInfo['confirm']           ['hasContent']   == true)
        {
            SwfRemoveOverlay('onepagecheckout');
        }
    }
    else
    {
        if(SwfInfo['login']['hasContent'])
            SwfRemove('login');
        if(SwfInfo['register']['hasContent'])
            SwfRemove('register');

        if(!SwfInfo['billing']['hasContent'])
            SwfGet('billing');
        if(!SwfInfo['shipping']['hasContent'])
            SwfGet('shipping');

        if(SwfInfo['onepagecheckout']   ['hasOverlay']
        && SwfInfo['cart']              ['hasContent']   == true
        && SwfInfo['billing']           ['hasContent']   == true
        && SwfInfo['shipping']          ['hasContent']   == true
        && SwfInfo['confirm']           ['hasContent']   == true)
        {
            SwfRemoveOverlay('onepagecheckout');
        }
    }

    $('#Swf' + part.capitalize() + ' form').unbind();
    $('#Swf' + part.capitalize() + ' input').unbind();
    $('#Swf' + part.capitalize() + ' select').not('.outer-select select').unbind();
    $('#Swf' + part.capitalize() + ' checkbox').unbind();
    $('#Swf' + part.capitalize() + ' a').unbind();
    $.basket.options.viewport = null;

	SwfRefreshHtml(part,html);

	setTimeout(function(){
		confirmReady = true;
		SwfAddClickableOverlay('confirmwrapper');
		$('#SwfBilling input.required:visible, #SwfBilling select.required:visible, #SwfBilling input.required:visible, #SwfBilling select.required:visible').each(function(){
	    	if($(this).val() == '' || typeof($(this).val()) == 'undefined')
	    	{
	    		confirmReady = false;
	    		setError($(this));
	    	}
	    });
	    $('#SwfRegister input.required:visible, #SwfRegister select.required:visible').each(function(){
	    	if($(this).val() == '' || typeof($(this).val()) == 'undefined')
	    	{
	    		confirmReady = false;
	    	}
	    });


	    if(confirmReady)
	    {
            if($('#SwfPayment').html().length < 10)
                SwfGet('payment');
            SwfBillingFilled = true;
            SwfRemoveClickableOverlay('confirmwrapper');
	    }
	}, 100);

    shopwareOnReady();
    swfOnReady();
}

function swfOnReady(){
	// Handle different shipping address, if any shipping input is different than the pair billing address input
	// check checkbox and show shipping address
	differentShippingAddress = false;
    if($('#zipcode2').val() !="") {
        if($('#firstname').val() != $('#firstname2').val())
            differentShippingAddress = true;
        if($('#lastname').val() != $('#lastname2').val())
            differentShippingAddress = true;
        if($('#street').val() != $('#street2').val())
            differentShippingAddress = true;
        if($('#streetnumber').val() != $('#streetnumber2').val())
            differentShippingAddress = true;
        if($('#zipcode').val() != $('#zipcode2').val())
            differentShippingAddress = true;
        if($('#city').val() != $('#city2').val())
            differentShippingAddress = true;
    }
	if(differentShippingAddress)
	{
		$('#register_billing_shippingAddress').prop('checked', true);
	}
	else
	{
		$('#register_billing_shippingAddress').prop('checked', false);
		$('.register .alternative_shipping').hide();
	}

    // unbind shopware's auto submit
    $('select.auto_submit').unbind('change');

    $('input.auto_submit:radio, a.auto_submit, input.auto_submit:checkbox').die('click');

    $('input.auto_submit:text').die('blur');

    // cart events
    $('#SwfCart select.auto_submit').on('change', function () {
        SwfPost('cart', this.form, function(){
            SwfGet('confirm');
        });
    });

    $('#SwfCart form').on('submit', function (e) {
        e.preventDefault();
        SwfPost('cart', this, function(){
            SwfGet('confirm');
        });
    });

    $('#SwfCart a.del').unbind();
    $('#SwfCart a.del').SwfGetLinkByAjax('cart', function () {
        SwfGet('confirm');
    }, SwfDeleteArticleMessage);

    // login events
    $('#SwfLogin form').on('submit', function (e) {
        e.preventDefault();
        SwfPost('login', this, function(){
            SwfGet('cart', null, 'summary');
            SwfGet('confirm');
        });
    });

    //register events (customer type, country, street name, last name, shipping address, billing address)
    $('#SwfRegister input, #SwfRegister form').on('change setSuccess setError submit', function(){
        var me = this;
        var complete = true;
        if($(me).attr('name') == 'register[personal][birthday]'
        || $(me).attr('name') == 'register[personal][birthmonth]'
        || $(me).attr('name') == 'register[personal][birthyear]')
        {
            $('#birthdate select').each(function(){
                if($(this).val() == ''
                || $(this).hasClass('instyle_error'))
                {
                    complete = false;
                    return;
                }
            });
        }
        if($('#dpacheckbox').length && !$('#dpacheckbox').is(':checked'))
        {
        	complete = false;
        }
        $('#SwfRegister input.required:visible, #SwfRegister select.required:visible').each(function(){
            if($(this).val() == ''
            || $(this).hasClass('instyle_error')
            || typeof($(this).val()) == 'undefined')
            {
                complete = false;
                return;
            }
        });
        if(complete)
        {
            SwfBillingFilled = true;
        	if($.trim($('#phone').val()) == '')
        		$('#phone').val('---');
            SwfPost('register', me.form, function(){
                SwfGet('confirm');
                SwfGet('cart', null, 'summary');
            });
        }
    });

    $('#SwfBilling input, #SwfBilling select').on('change keydown', function(){
        SwfSendBilling = true;
    });

    $('#SwfShipping input, #SwfShipping select').on('keyup change', function(){
        SwfSendShipping = true;
    });

    $('#SwfBilling #country').on('change', function(){
        SwfCheckAndSendBilling();
        SwfSendBilling = false;
    });

    $('#SwfShipping #country2').on('change', function(){
        SwfCheckAndSendShipping();
        SwfSendShipping = false;
    });

    // confirm events
    $('#SwfConfirm .method input:radio').live('click', function () {
        complete = true;
        $(this).closest('.method').find('input').each(function(){
            if($(this).val() == ''
            || $(this).hasClass('instyle_error'))
            {
                complete = false;
                return;
            }
        });
        if(complete)
            SwfPost('confirm', this.form, function(){
                SwfGet('cart', null, 'summary');
            });
    });

    $('#SwfPayment .method input:radio').live('click', function () {
        complete = true;
        $(this).closest('.method').find('input').each(function(){
            if($(this).val() == ''
                || $(this).hasClass('instyle_error'))
            {
                complete = false;
                return;
            }
        });
        if(complete)
            SwfPost('payment', this.form, function(){
                SwfGet('cart', null, 'summary');
                SwfGet('confirm', null, 'summary');
            });
    });

    $('#SwfConfirm input, #SwfPayment input').not('.SwfConfirmFooter input').live('click', function(e){
    	if(SwfSendShipping)
    	{
    		e.preventDefault();
    		SwfCheckAndSendShipping(function(){
    			SwfGet('confirm', function(){
    				SwfSendShipping = false;
    			});
                SwfGet('payment', function(){
                    SwfSendShipping = false;
                });
    		});
    	}
    	if(SwfSendBilling)
    	{
    		e.preventDefault();
    		SwfCheckAndSendBilling(function(){
    			SwfGet('confirm', function(){
	    			SwfSendBilling = false;
    			});
                SwfGet('payment', function(){
                    SwfSendBilling = false;
                });
    		});
    	}
    });

    $('.SwfConfirmFooter input:not(#basketButton)').live('click', function(e){
    	if(SwfSendShipping)
    	{
    		SwfAddOverlay('confirm');
    		SwfCheckAndSendShipping(function(){
    			SwfGet('confirm', function(){
    				SwfSendShipping = false;
    			},
    			null,
    			false);
    		});
    	}
    	if(SwfSendBilling)
    	{
    		SwfAddOverlay('confirm');
    		SwfCheckAndSendBilling(function(){
    			SwfGet('confirm', function(){
	    			SwfSendBilling = false;
    			},
    			null,
    			false);
    		});
    	}
    });

    $('#basketButton').live('click', function(e){
    	if(SwfSendShipping)
    	{
    		e.preventDefault();
    		SwfAddOverlay('confirm');
    		SwfCheckAndSendShipping(function(){
    			SwfGet('confirm', function(){
    				SwfSendShipping = false;
    				$(e.target).trigger('click');
    			},
    			null,
    			false);
    		});
    	}
    	if(SwfSendBilling)
    	{
    		e.preventDefault();
    		SwfAddOverlay('confirm');
    		SwfCheckAndSendBilling(function(){
    			SwfGet('confirm', function(){
	    			SwfSendBilling = false;
	    			$(e.target).trigger('click');
    			},
    			null,
    			false);
    		});
    	}
    });

    // debit support
    $('#SwfPayment input[type=text]').on('change', function(){
        var me = this;
        console.log('dada');
        if(!$(me).closest('.method').find('input[type="radio"]').is(':checked'))
            return;
        var complete = true;
        console.log('bzz');
        $(me).closest('.method').find('input[type=text]').each(function(){
            if($(this).val() == '')
            {
                console.log(this);
                complete = false;
                return;
            }
        });
        if(complete)
            SwfPost('payment', this.form, function(){
                SwfGet('cart', null, 'summary');
            });
    });

    // premium articles
    $('.article form').on('submit', function (e) {
        e.preventDefault();
        SwfPost('confirm', this, function(){
            SwfGet('cart', null, 'summary');
        });
    });

    if(typeof $.fn.fancySelect == 'function')
	{
		$("select").not(".outer-select select").fancySelect();
	}


    if(SwfBasketQuantity > MaxBasketAmountForStandardItemLayout)
    	$('#SwfCartItems').accordion({ event: "mouseover", header: ".SwfCartItemHeader" });
}

function shopwareOnReady(){
    // Removes hiding class for all script related elements
    $('.hide_script').removeClass('hide_script');

    //IE 6 Drop down menu fallback
    if ($.browser.msie === true && parseInt($.browser.version, 10) === 6) {
        $('#mainNavigation li').hover(function () {
            $(this).addClass('hover');
        }, function () {
            $(this).removeClass('hover');
        });
    }

    $('.detail-modal').unbind('click');
    $('.detail-modal').bind('click', function(event) {
        event.preventDefault();

        var $this = $(this),w
                title = $this.parents('form').find('a.title').html();

        if(title.length > 65) {
            title = title.substring(0, 65) + '...';
        }

        $.modalFrame($this.attr('href') + '?template=ajax', title, {
            width: 600,
            frameHeight: 500,
            useOverlay: true,
            position: 'fixed',
            headlineCls: 'headingbox'
        });
    });

    //Liveshopping
    $('.liveshopping_container:visible, .liveshopping_detail:visible').liveshopping();

    //Bundle
    $('.bundle_container, .relatedbox_container').bundle();

    $('a.checkout, a.login, a.account').die();
	$('a.checkout, a.login, a.account').unbind();

    //Checkout
    $('a.checkout, a.login, a.account').checkout({
        'viewport': $.controller.ajax_login,
        'register': $.controller.register,
        'checkout': $.controller.checkout
    });

    //Supplier filter
    $('.supplier_filter .slideContainer').supplierfilter();

    //AJAX Warenkorb
    $.basket.options.viewport = $.controller.ajax_cart;
    $.basket.init();



    $('.modal_close').live('click', function () {
        $.modalClose();
        $.ie6fix.selectShow();
    });

    $('.modal_open a').click(function (event) {
        event.preventDefault();
        $.post(this.href, function (data) {
            $.modal(data, '', {
                'position': 'fixed'
            }).find('.close').remove();
        });
    });

    //Topseller
    if ($('.topseller')) {
        $('.accordion').kwicks({
            min: 52,
            sticky: true,
            spacing: 0,
            isVertical: true,
            duration: 350
        });
    }

    //Suggest Search
    $('#searchfield').liveSearch({
        url: $.controller.ajax_search,
        'id': 'searchresults'
    });
    var defaultValue = $('#searchfield').val();
    $('#searchfield').focus(function () {
        if ($('#searchfield').val() === defaultValue) {
            $('#searchfield').val('');
        }
    });

    //Get's the servertime for liveshopping articles
    $.server.init(timeNow);

//    Changing article informations on variants
    if (typeof (isVariant) !== 'undefined' && isVariant === true && isConfigurator !== true) {
        $.changeDetails(0);
    }
    $('#sAdd.variant').change(function () {
        $.changeDetails($(this).val());
    });

    //Lightbox basket
    $('a.zoom_picture[rel^=lightbox]').slimbox();

    //AJAX basket
    $('div.ajax_basket').click(function () {
        if ($('.ajax_basket_result').hasClass('active')) {
            $('.ajax_basket_result').removeClass('active').slideToggle('fast');
        } else {
            $.basket.getBasket();
        }
    });

    //Article detail accessory
    var lasthover;
    $('.basketform label').hover(function () {
        var $this = $(this);

        var value = $this.prev('input').val();

        if (value && value.length) {
            $('div#DIV' + value).fadeIn('fast');
            lasthover = $('div#DIV' + value);
        }
    }, function () {
        if(!lasthover) {
            return false;
        }
        lasthover.fadeOut('fast');
        lasthover = '';
    });

    //Article detail accessory
    $('.accessory_group input').bind('change', function () {
        var $this = $(this);
        $accessories = $('#sAddAccessories');
        $accessories.val('');
        if ($this.is(':checked')) {

            $('.accessory_group input:checked').each(function (i, el) {
                var val = $accessories.val();
                val += $(el).val() + ';';
                $accessories.val(val);
            });
        }
    });

    //Lightbox - Blog
    $('.blogbox [rel^=lightbox]').slimbox();

    //Use a lightbox instead of a zoom
    if (typeof (useZoom) !== 'undefined' && (useZoom === '0' || useZoom === '')) {
        $("[rel^='lightbox']").slimbox();
        $('div.thumb_box a').bind('click', function (event) {
            event.preventDefault();
            $('a#zoom1').hide().attr('href', $(this).attr('href')).children().attr('src', $(this).attr('rev'));
            $('a#zoom1').fadeIn('slow');
            return false;
        });
    }

    $('.account .password').hide();
    $('.account .email').hide();

    //Change password account
    if ($('.account .password').hasClass('displayblock')) {
        $('.account .password').show();
    }
    if ($('.account .email').hasClass('displayblock')) {
        $('.account .password').show();
    }

    $('.account .change_password').bind('click', function (event) {
        event.preventDefault();
        $('.account .password').slideToggle('fast').toggleClass('active');
        $('.account .email').slideUp('fast');
    });

    //Change email account
    $('.account .change_mail').bind('click', function (event) {
        event.preventDefault();
        $('.account .email').slideToggle('fast').toggleClass('active');
        $('.account .password').slideUp('fast');
    });

    //Logout Account
    $('.logout').bind('click', function (event) {
        event.preventDefault();
        $.post($.controller.ajax_logout, function (data) {
            var position = 'fixed';
            if ($.browser.msie && parseInt($.browser.version, 10) === 6) {
                position = 'absolute';
            }
            $.modal(data, '', {
                'position': position
            }).find('.close').remove();
            if ($.browser.msie && ~~$.browser.version <= 7) {
                buttons = $('.modal').find('.button-right');
                buttons.each(function () {
                    this.fireEvent('onmove');
                });
            }
        });
    });

    //User account page orders
    $('.orderoverview_active .orderdetails').bind('click', function (e) {
        e.preventDefault();
        if ($('#' + $(this).attr('rel')).hasClass('active')) {
            $('#' + $(this).attr('rel')).removeClass('active').hide();
        } else {
            $('#' + $(this).attr('rel')).addClass('active').show();
        }
    });

    //Register validation
    $('.register .required:input').validate();

    if ($("#register_personal_customer_type").val() === "private") {
        $('.register .company_informations').hide();
    }

    if ($("#register_personal_skipLogin").is(':checked')) {
        $('.register .fade_password, .register p.description, #birthdate').hide();
    }

    $("#register_personal_customer_type").change(function () {
        if ($(this).val() === 'business') {
            $('.register .company_informations').slideDown();
        } else {
            $('.register .company_informations').slideUp();
        }
    });

    $('#register_billing_shippingAddress').click(function () {
        if (!$(this).is(':checked')) {
            $('.register .alternative_shipping').slideUp();
            $('#firstname2').val($('#firstname').val());
			$('#lastname2').val($('#lastname').val());
			$('#street2').val($('#street').val());
			$('#streetnumber2').val($('#streetnumber').val());
			$('#zipcode2').val($('#zipcode').val());
			$('#city2').val($('#city').val());
        } else {
            $('.register .alternative_shipping').slideDown();
        }
    });

    $('#SwfBilling #register_billing_shippingAddress').click(function () {
        if (!$(this).is(':checked')) {
            $('.register .alternative_shipping').slideUp();
            $('#firstname2').val($('#firstname').val());
			$('#lastname2').val($('#lastname').val());
			$('#street2').val($('#street').val());
			$('#streetnumber2').val($('#streetnumber').val());
			$('#zipcode2').val($('#zipcode').val());
			$('#city2').val($('#city').val());
			SwfInfo['shipping']['hasOverlay'] = true;
			SwfCheckAndSendShipping();
			SwfSendShipping = false;
        } else {
            $('.register .alternative_shipping').slideDown();
        }
    });

    $('#SwfBilling #country').on('setSuccess', function () {
        SwfCheckAndSendBilling();
        SwfSendBilling = false;
    });

    $('#register_personal_skipLogin').click(function () {
        if ($(this).is(':checked')) {
            $('.register .fade_password, .register p.description, #birthdate').slideUp();
        } else {
            $('.register .fade_password, .register p.description, #birthdate').slideDown();
        }
    });

    // Loading Indicator
    $('form.upprice_config').bind('change', function () {
        $.loadingIndicator.open();
    });


    /**
     * Account partner statistic
     */
    //    $('.datePicker').datepicker();
    $('.datePicker').datepicker({
        showOn: "button",
        buttonImage: "images/calendar.gif",
        dateFormat: 'dd.mm.yy',
        buttonImageOnly: true,
        onSelect: function (dateText, inst) {
            $(this).parents('form').submit();
        }
    });
}


function swf_base64_encode (data) {
    // http://kevin.vanzonneveld.net
    // +   original by: Tyler Akins (http://rumkin.com)
    // +   improved by: Bayron Guevara
    // +   improved by: Thunder.m
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Pellentesque Malesuada
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Rafał Kukawski (http://kukawski.pl)
    // *     example 1: base64_encode('Kevin van Zonneveld');
    // *     returns 1: 'S2V2aW4gdmFuIFpvbm5ldmVsZA=='
    // mozilla has this native
    // - but breaks in 2.0.0.12!
    //if (typeof this.window['btoa'] === 'function') {
    //    return btoa(data);
    //}
    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
        ac = 0,
        enc = "",
        tmp_arr = [];

    if (!data) {
        return data;
    }

    do { // pack three octets into four hexets
        o1 = data.charCodeAt(i++);
        o2 = data.charCodeAt(i++);
        o3 = data.charCodeAt(i++);

        bits = o1 << 16 | o2 << 8 | o3;

        h1 = bits >> 18 & 0x3f;
        h2 = bits >> 12 & 0x3f;
        h3 = bits >> 6 & 0x3f;
        h4 = bits & 0x3f;

        // use hexets to index into b64, and append result to encoded string
        tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
    } while (i < data.length);

    enc = tmp_arr.join('');

    var r = data.length % 3;

    return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);

}

function swf_base64_decode (data) {
    // http://kevin.vanzonneveld.net
    // +   original by: Tyler Akins (http://rumkin.com)
    // +   improved by: Thunder.m
    // +      input by: Aman Gupta
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Onno Marsman
    // +   bugfixed by: Pellentesque Malesuada
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: base64_decode('S2V2aW4gdmFuIFpvbm5ldmVsZA==');
    // *     returns 1: 'Kevin van Zonneveld'
    // mozilla has this native
    // - but breaks in 2.0.0.12!
    //if (typeof this.window['atob'] === 'function') {
    //    return atob(data);
    //}
    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
        ac = 0,
        dec = "",
        tmp_arr = [];

    if (!data) {
        return data;
    }

    data += '';

    do { // unpack four hexets into three octets using index points in b64
        h1 = b64.indexOf(data.charAt(i++));
        h2 = b64.indexOf(data.charAt(i++));
        h3 = b64.indexOf(data.charAt(i++));
        h4 = b64.indexOf(data.charAt(i++));

        bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;

        o1 = bits >> 16 & 0xff;
        o2 = bits >> 8 & 0xff;
        o3 = bits & 0xff;

        if (h3 == 64) {
            tmp_arr[ac++] = String.fromCharCode(o1);
        } else if (h4 == 64) {
            tmp_arr[ac++] = String.fromCharCode(o1, o2);
        } else {
            tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
        }
    } while (i < data.length);

    dec = tmp_arr.join('');

    return dec;
}

/*!
 * jQuery Cookie Plugin v1.4.0
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 */
(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as anonymous module.
        define(['jquery'], factory);
    } else {
        // Browser globals.
        factory(jQuery);
    }
}(function ($) {

    var pluses = /\+/g;

    function encode(s) {
        return config.raw ? s : encodeURIComponent(s);
    }

    function decode(s) {
        return config.raw ? s : decodeURIComponent(s);
    }

    function stringifyCookieValue(value) {
        return encode(config.json ? JSON.stringify(value) : String(value));
    }

    function parseCookieValue(s) {
        if (s.indexOf('"') === 0) {
            // This is a quoted cookie as according to RFC2068, unescape...
            s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
        }

        try {
            // Replace server-side written pluses with spaces.
            // If we can't decode the cookie, ignore it, it's unusable.
            // If we can't parse the cookie, ignore it, it's unusable.
            s = decodeURIComponent(s.replace(pluses, ' '));
            return config.json ? JSON.parse(s) : s;
        } catch(e) {}
    }

    function read(s, converter) {
        var value = config.raw ? s : parseCookieValue(s);
        return $.isFunction(converter) ? converter(value) : value;
    }

    var config = $.cookie = function (key, value, options) {

        // Write

        if (value !== undefined && !$.isFunction(value)) {
            options = $.extend({}, config.defaults, options);

            if (typeof options.expires === 'number') {
                var days = options.expires, t = options.expires = new Date();
                t.setTime(+t + days * 864e+5);
            }

            return (document.cookie = [
                encode(key), '=', stringifyCookieValue(value),
                options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
                options.path    ? '; path=' + options.path : '',
                options.domain  ? '; domain=' + options.domain : '',
                options.secure  ? '; secure' : ''
            ].join(''));
        }

        // Read

        var result = key ? undefined : {};

        // To prevent the for loop in the first place assign an empty array
        // in case there are no cookies at all. Also prevents odd result when
        // calling $.cookie().
        var cookies = document.cookie ? document.cookie.split('; ') : [];

        for (var i = 0, l = cookies.length; i < l; i++) {
            var parts = cookies[i].split('=');
            var name = decode(parts.shift());
            var cookie = parts.join('=');

            if (key && key === name) {
                // If second argument (value) is a function it's a converter...
                result = read(cookie, value);
                break;
            }

            // Prevent storing a cookie that we couldn't decode.
            if (!key && (cookie = read(cookie)) !== undefined) {
                result[name] = cookie;
            }
        }

        return result;
    };

    config.defaults = {};

    $.removeCookie = function (key, options) {
        if ($.cookie(key) === undefined) {
            return false;
        }

        // Must not alter options, thus extending a fresh object...
        $.cookie(key, '', $.extend({}, options, { expires: -1 }));
        return !$.cookie(key);
    };

}));

function md5cycle(x, k) {
    var a = x[0], b = x[1], c = x[2], d = x[3];

    a = ff(a, b, c, d, k[0], 7, -680876936);
    d = ff(d, a, b, c, k[1], 12, -389564586);
    c = ff(c, d, a, b, k[2], 17,  606105819);
    b = ff(b, c, d, a, k[3], 22, -1044525330);
    a = ff(a, b, c, d, k[4], 7, -176418897);
    d = ff(d, a, b, c, k[5], 12,  1200080426);
    c = ff(c, d, a, b, k[6], 17, -1473231341);
    b = ff(b, c, d, a, k[7], 22, -45705983);
    a = ff(a, b, c, d, k[8], 7,  1770035416);
    d = ff(d, a, b, c, k[9], 12, -1958414417);
    c = ff(c, d, a, b, k[10], 17, -42063);
    b = ff(b, c, d, a, k[11], 22, -1990404162);
    a = ff(a, b, c, d, k[12], 7,  1804603682);
    d = ff(d, a, b, c, k[13], 12, -40341101);
    c = ff(c, d, a, b, k[14], 17, -1502002290);
    b = ff(b, c, d, a, k[15], 22,  1236535329);

    a = gg(a, b, c, d, k[1], 5, -165796510);
    d = gg(d, a, b, c, k[6], 9, -1069501632);
    c = gg(c, d, a, b, k[11], 14,  643717713);
    b = gg(b, c, d, a, k[0], 20, -373897302);
    a = gg(a, b, c, d, k[5], 5, -701558691);
    d = gg(d, a, b, c, k[10], 9,  38016083);
    c = gg(c, d, a, b, k[15], 14, -660478335);
    b = gg(b, c, d, a, k[4], 20, -405537848);
    a = gg(a, b, c, d, k[9], 5,  568446438);
    d = gg(d, a, b, c, k[14], 9, -1019803690);
    c = gg(c, d, a, b, k[3], 14, -187363961);
    b = gg(b, c, d, a, k[8], 20,  1163531501);
    a = gg(a, b, c, d, k[13], 5, -1444681467);
    d = gg(d, a, b, c, k[2], 9, -51403784);
    c = gg(c, d, a, b, k[7], 14,  1735328473);
    b = gg(b, c, d, a, k[12], 20, -1926607734);

    a = hh(a, b, c, d, k[5], 4, -378558);
    d = hh(d, a, b, c, k[8], 11, -2022574463);
    c = hh(c, d, a, b, k[11], 16,  1839030562);
    b = hh(b, c, d, a, k[14], 23, -35309556);
    a = hh(a, b, c, d, k[1], 4, -1530992060);
    d = hh(d, a, b, c, k[4], 11,  1272893353);
    c = hh(c, d, a, b, k[7], 16, -155497632);
    b = hh(b, c, d, a, k[10], 23, -1094730640);
    a = hh(a, b, c, d, k[13], 4,  681279174);
    d = hh(d, a, b, c, k[0], 11, -358537222);
    c = hh(c, d, a, b, k[3], 16, -722521979);
    b = hh(b, c, d, a, k[6], 23,  76029189);
    a = hh(a, b, c, d, k[9], 4, -640364487);
    d = hh(d, a, b, c, k[12], 11, -421815835);
    c = hh(c, d, a, b, k[15], 16,  530742520);
    b = hh(b, c, d, a, k[2], 23, -995338651);

    a = ii(a, b, c, d, k[0], 6, -198630844);
    d = ii(d, a, b, c, k[7], 10,  1126891415);
    c = ii(c, d, a, b, k[14], 15, -1416354905);
    b = ii(b, c, d, a, k[5], 21, -57434055);
    a = ii(a, b, c, d, k[12], 6,  1700485571);
    d = ii(d, a, b, c, k[3], 10, -1894986606);
    c = ii(c, d, a, b, k[10], 15, -1051523);
    b = ii(b, c, d, a, k[1], 21, -2054922799);
    a = ii(a, b, c, d, k[8], 6,  1873313359);
    d = ii(d, a, b, c, k[15], 10, -30611744);
    c = ii(c, d, a, b, k[6], 15, -1560198380);
    b = ii(b, c, d, a, k[13], 21,  1309151649);
    a = ii(a, b, c, d, k[4], 6, -145523070);
    d = ii(d, a, b, c, k[11], 10, -1120210379);
    c = ii(c, d, a, b, k[2], 15,  718787259);
    b = ii(b, c, d, a, k[9], 21, -343485551);

    x[0] = add32(a, x[0]);
    x[1] = add32(b, x[1]);
    x[2] = add32(c, x[2]);
    x[3] = add32(d, x[3]);

}

function cmn(q, a, b, x, s, t) {
    a = add32(add32(a, q), add32(x, t));
    return add32((a << s) | (a >>> (32 - s)), b);
}

function ff(a, b, c, d, x, s, t) {
    return cmn((b & c) | ((~b) & d), a, b, x, s, t);
}

function gg(a, b, c, d, x, s, t) {
    return cmn((b & d) | (c & (~d)), a, b, x, s, t);
}

function hh(a, b, c, d, x, s, t) {
    return cmn(b ^ c ^ d, a, b, x, s, t);
}

function ii(a, b, c, d, x, s, t) {
    return cmn(c ^ (b | (~d)), a, b, x, s, t);
}

function md51(s) {
    txt = '';
    var n = s.length,
        state = [1732584193, -271733879, -1732584194, 271733878], i;
    for (i=64; i<=s.length; i+=64) {
        md5cycle(state, md5blk(s.substring(i-64, i)));
    }
    s = s.substring(i-64);
    var tail = [0,0,0,0, 0,0,0,0, 0,0,0,0, 0,0,0,0];
    for (i=0; i<s.length; i++)
        tail[i>>2] |= s.charCodeAt(i) << ((i%4) << 3);
    tail[i>>2] |= 0x80 << ((i%4) << 3);
    if (i > 55) {
        md5cycle(state, tail);
        for (i=0; i<16; i++) tail[i] = 0;
    }
    tail[14] = n*8;
    md5cycle(state, tail);
    return state;
}

/* there needs to be support for Unicode here,
 * unless we pretend that we can redefine the MD-5
 * algorithm for multi-byte characters (perhaps
 * by adding every four 16-bit characters and
 * shortening the sum to 32 bits). Otherwise
 * I suggest performing MD-5 as if every character
 * was two bytes--e.g., 0040 0025 = @%--but then
 * how will an ordinary MD-5 sum be matched?
 * There is no way to standardize text to something
 * like UTF-8 before transformation; speed cost is
 * utterly prohibitive. The JavaScript standard
 * itself needs to look at this: it should start
 * providing access to strings as preformed UTF-8
 * 8-bit unsigned value arrays.
 */
function md5blk(s) { /* I figured global was faster.   */
    var md5blks = [], i; /* Andy King said do it this way. */
    for (i=0; i<64; i+=4) {
        md5blks[i>>2] = s.charCodeAt(i)
            + (s.charCodeAt(i+1) << 8)
            + (s.charCodeAt(i+2) << 16)
            + (s.charCodeAt(i+3) << 24);
    }
    return md5blks;
}

var hex_chr = '0123456789abcdef'.split('');

function rhex(n)
{
    var s='', j=0;
    for(; j<4; j++)
        s += hex_chr[(n >> (j * 8 + 4)) & 0x0F]
            + hex_chr[(n >> (j * 8)) & 0x0F];
    return s;
}

function hex(x) {
    for (var i=0; i<x.length; i++)
        x[i] = rhex(x[i]);
    return x.join('');
}

function md5(s) {
    return hex(md51(s));
}

/* this function is much faster,
 so if possible we use it. Some IEs
 are the only ones I know of that
 need the idiotic second function,
 generated by an if clause.  */

function add32(a, b) {
    return (a + b) & 0xFFFFFFFF;
}

if (md5('hello') != '5d41402abc4b2a76b9719d911017c592') {
    function add32(x, y) {
        var lsw = (x & 0xFFFF) + (y & 0xFFFF),
            msw = (x >> 16) + (y >> 16) + (lsw >> 16);
        return (msw << 16) | (lsw & 0xFFFF);
    }
}

/*! jQuery UI - v1.10.3 - 2013-09-05
 * http://jqueryui.com
 * Includes: jquery.ui.core.js, jquery.ui.widget.js, jquery.ui.accordion.js
 * Copyright 2013 jQuery Foundation and other contributors; Licensed MIT */

(function(e,t){function i(t,i){var s,a,o,r=t.nodeName.toLowerCase();return"area"===r?(s=t.parentNode,a=s.name,t.href&&a&&"map"===s.nodeName.toLowerCase()?(o=e("img[usemap=#"+a+"]")[0],!!o&&n(o)):!1):(/input|select|textarea|button|object/.test(r)?!t.disabled:"a"===r?t.href||i:i)&&n(t)}function n(t){return e.expr.filters.visible(t)&&!e(t).parents().addBack().filter(function(){return"hidden"===e.css(this,"visibility")}).length}var s=0,a=/^ui-id-\d+$/;e.ui=e.ui||{},e.extend(e.ui,{version:"1.10.3",keyCode:{BACKSPACE:8,COMMA:188,DELETE:46,DOWN:40,END:35,ENTER:13,ESCAPE:27,HOME:36,LEFT:37,NUMPAD_ADD:107,NUMPAD_DECIMAL:110,NUMPAD_DIVIDE:111,NUMPAD_ENTER:108,NUMPAD_MULTIPLY:106,NUMPAD_SUBTRACT:109,PAGE_DOWN:34,PAGE_UP:33,PERIOD:190,RIGHT:39,SPACE:32,TAB:9,UP:38}}),e.fn.extend({focus:function(t){return function(i,n){return"number"==typeof i?this.each(function(){var t=this;setTimeout(function(){e(t).focus(),n&&n.call(t)},i)}):t.apply(this,arguments)}}(e.fn.focus),scrollParent:function(){var t;return t=e.ui.ie&&/(static|relative)/.test(this.css("position"))||/absolute/.test(this.css("position"))?this.parents().filter(function(){return/(relative|absolute|fixed)/.test(e.css(this,"position"))&&/(auto|scroll)/.test(e.css(this,"overflow")+e.css(this,"overflow-y")+e.css(this,"overflow-x"))}).eq(0):this.parents().filter(function(){return/(auto|scroll)/.test(e.css(this,"overflow")+e.css(this,"overflow-y")+e.css(this,"overflow-x"))}).eq(0),/fixed/.test(this.css("position"))||!t.length?e(document):t},zIndex:function(i){if(i!==t)return this.css("zIndex",i);if(this.length)for(var n,s,a=e(this[0]);a.length&&a[0]!==document;){if(n=a.css("position"),("absolute"===n||"relative"===n||"fixed"===n)&&(s=parseInt(a.css("zIndex"),10),!isNaN(s)&&0!==s))return s;a=a.parent()}return 0},uniqueId:function(){return this.each(function(){this.id||(this.id="ui-id-"+ ++s)})},removeUniqueId:function(){return this.each(function(){a.test(this.id)&&e(this).removeAttr("id")})}}),e.extend(e.expr[":"],{data:e.expr.createPseudo?e.expr.createPseudo(function(t){return function(i){return!!e.data(i,t)}}):function(t,i,n){return!!e.data(t,n[3])},focusable:function(t){return i(t,!isNaN(e.attr(t,"tabindex")))},tabbable:function(t){var n=e.attr(t,"tabindex"),s=isNaN(n);return(s||n>=0)&&i(t,!s)}}),e("<a>").outerWidth(1).jquery||e.each(["Width","Height"],function(i,n){function s(t,i,n,s){return e.each(a,function(){i-=parseFloat(e.css(t,"padding"+this))||0,n&&(i-=parseFloat(e.css(t,"border"+this+"Width"))||0),s&&(i-=parseFloat(e.css(t,"margin"+this))||0)}),i}var a="Width"===n?["Left","Right"]:["Top","Bottom"],o=n.toLowerCase(),r={innerWidth:e.fn.innerWidth,innerHeight:e.fn.innerHeight,outerWidth:e.fn.outerWidth,outerHeight:e.fn.outerHeight};e.fn["inner"+n]=function(i){return i===t?r["inner"+n].call(this):this.each(function(){e(this).css(o,s(this,i)+"px")})},e.fn["outer"+n]=function(t,i){return"number"!=typeof t?r["outer"+n].call(this,t):this.each(function(){e(this).css(o,s(this,t,!0,i)+"px")})}}),e.fn.addBack||(e.fn.addBack=function(e){return this.add(null==e?this.prevObject:this.prevObject.filter(e))}),e("<a>").data("a-b","a").removeData("a-b").data("a-b")&&(e.fn.removeData=function(t){return function(i){return arguments.length?t.call(this,e.camelCase(i)):t.call(this)}}(e.fn.removeData)),e.ui.ie=!!/msie [\w.]+/.exec(navigator.userAgent.toLowerCase()),e.support.selectstart="onselectstart"in document.createElement("div"),e.fn.extend({disableSelection:function(){return this.bind((e.support.selectstart?"selectstart":"mousedown")+".ui-disableSelection",function(e){e.preventDefault()})},enableSelection:function(){return this.unbind(".ui-disableSelection")}}),e.extend(e.ui,{plugin:{add:function(t,i,n){var s,a=e.ui[t].prototype;for(s in n)a.plugins[s]=a.plugins[s]||[],a.plugins[s].push([i,n[s]])},call:function(e,t,i){var n,s=e.plugins[t];if(s&&e.element[0].parentNode&&11!==e.element[0].parentNode.nodeType)for(n=0;s.length>n;n++)e.options[s[n][0]]&&s[n][1].apply(e.element,i)}},hasScroll:function(t,i){if("hidden"===e(t).css("overflow"))return!1;var n=i&&"left"===i?"scrollLeft":"scrollTop",s=!1;return t[n]>0?!0:(t[n]=1,s=t[n]>0,t[n]=0,s)}})})(jQuery);(function(t,e){var i=0,s=Array.prototype.slice,n=t.cleanData;t.cleanData=function(e){for(var i,s=0;null!=(i=e[s]);s++)try{t(i).triggerHandler("remove")}catch(o){}n(e)},t.widget=function(i,s,n){var o,a,r,h,l={},c=i.split(".")[0];i=i.split(".")[1],o=c+"-"+i,n||(n=s,s=t.Widget),t.expr[":"][o.toLowerCase()]=function(e){return!!t.data(e,o)},t[c]=t[c]||{},a=t[c][i],r=t[c][i]=function(t,i){return this._createWidget?(arguments.length&&this._createWidget(t,i),e):new r(t,i)},t.extend(r,a,{version:n.version,_proto:t.extend({},n),_childConstructors:[]}),h=new s,h.options=t.widget.extend({},h.options),t.each(n,function(i,n){return t.isFunction(n)?(l[i]=function(){var t=function(){return s.prototype[i].apply(this,arguments)},e=function(t){return s.prototype[i].apply(this,t)};return function(){var i,s=this._super,o=this._superApply;return this._super=t,this._superApply=e,i=n.apply(this,arguments),this._super=s,this._superApply=o,i}}(),e):(l[i]=n,e)}),r.prototype=t.widget.extend(h,{widgetEventPrefix:a?h.widgetEventPrefix:i},l,{constructor:r,namespace:c,widgetName:i,widgetFullName:o}),a?(t.each(a._childConstructors,function(e,i){var s=i.prototype;t.widget(s.namespace+"."+s.widgetName,r,i._proto)}),delete a._childConstructors):s._childConstructors.push(r),t.widget.bridge(i,r)},t.widget.extend=function(i){for(var n,o,a=s.call(arguments,1),r=0,h=a.length;h>r;r++)for(n in a[r])o=a[r][n],a[r].hasOwnProperty(n)&&o!==e&&(i[n]=t.isPlainObject(o)?t.isPlainObject(i[n])?t.widget.extend({},i[n],o):t.widget.extend({},o):o);return i},t.widget.bridge=function(i,n){var o=n.prototype.widgetFullName||i;t.fn[i]=function(a){var r="string"==typeof a,h=s.call(arguments,1),l=this;return a=!r&&h.length?t.widget.extend.apply(null,[a].concat(h)):a,r?this.each(function(){var s,n=t.data(this,o);return n?t.isFunction(n[a])&&"_"!==a.charAt(0)?(s=n[a].apply(n,h),s!==n&&s!==e?(l=s&&s.jquery?l.pushStack(s.get()):s,!1):e):t.error("no such method '"+a+"' for "+i+" widget instance"):t.error("cannot call methods on "+i+" prior to initialization; "+"attempted to call method '"+a+"'")}):this.each(function(){var e=t.data(this,o);e?e.option(a||{})._init():t.data(this,o,new n(a,this))}),l}},t.Widget=function(){},t.Widget._childConstructors=[],t.Widget.prototype={widgetName:"widget",widgetEventPrefix:"",defaultElement:"<div>",options:{disabled:!1,create:null},_createWidget:function(e,s){s=t(s||this.defaultElement||this)[0],this.element=t(s),this.uuid=i++,this.eventNamespace="."+this.widgetName+this.uuid,this.options=t.widget.extend({},this.options,this._getCreateOptions(),e),this.bindings=t(),this.hoverable=t(),this.focusable=t(),s!==this&&(t.data(s,this.widgetFullName,this),this._on(!0,this.element,{remove:function(t){t.target===s&&this.destroy()}}),this.document=t(s.style?s.ownerDocument:s.document||s),this.window=t(this.document[0].defaultView||this.document[0].parentWindow)),this._create(),this._trigger("create",null,this._getCreateEventData()),this._init()},_getCreateOptions:t.noop,_getCreateEventData:t.noop,_create:t.noop,_init:t.noop,destroy:function(){this._destroy(),this.element.unbind(this.eventNamespace).removeData(this.widgetName).removeData(this.widgetFullName).removeData(t.camelCase(this.widgetFullName)),this.widget().unbind(this.eventNamespace).removeAttr("aria-disabled").removeClass(this.widgetFullName+"-disabled "+"ui-state-disabled"),this.bindings.unbind(this.eventNamespace),this.hoverable.removeClass("ui-state-hover"),this.focusable.removeClass("ui-state-focus")},_destroy:t.noop,widget:function(){return this.element},option:function(i,s){var n,o,a,r=i;if(0===arguments.length)return t.widget.extend({},this.options);if("string"==typeof i)if(r={},n=i.split("."),i=n.shift(),n.length){for(o=r[i]=t.widget.extend({},this.options[i]),a=0;n.length-1>a;a++)o[n[a]]=o[n[a]]||{},o=o[n[a]];if(i=n.pop(),s===e)return o[i]===e?null:o[i];o[i]=s}else{if(s===e)return this.options[i]===e?null:this.options[i];r[i]=s}return this._setOptions(r),this},_setOptions:function(t){var e;for(e in t)this._setOption(e,t[e]);return this},_setOption:function(t,e){return this.options[t]=e,"disabled"===t&&(this.widget().toggleClass(this.widgetFullName+"-disabled ui-state-disabled",!!e).attr("aria-disabled",e),this.hoverable.removeClass("ui-state-hover"),this.focusable.removeClass("ui-state-focus")),this},enable:function(){return this._setOption("disabled",!1)},disable:function(){return this._setOption("disabled",!0)},_on:function(i,s,n){var o,a=this;"boolean"!=typeof i&&(n=s,s=i,i=!1),n?(s=o=t(s),this.bindings=this.bindings.add(s)):(n=s,s=this.element,o=this.widget()),t.each(n,function(n,r){function h(){return i||a.options.disabled!==!0&&!t(this).hasClass("ui-state-disabled")?("string"==typeof r?a[r]:r).apply(a,arguments):e}"string"!=typeof r&&(h.guid=r.guid=r.guid||h.guid||t.guid++);var l=n.match(/^(\w+)\s*(.*)$/),c=l[1]+a.eventNamespace,u=l[2];u?o.delegate(u,c,h):s.bind(c,h)})},_off:function(t,e){e=(e||"").split(" ").join(this.eventNamespace+" ")+this.eventNamespace,t.unbind(e).undelegate(e)},_delay:function(t,e){function i(){return("string"==typeof t?s[t]:t).apply(s,arguments)}var s=this;return setTimeout(i,e||0)},_hoverable:function(e){this.hoverable=this.hoverable.add(e),this._on(e,{mouseenter:function(e){t(e.currentTarget).addClass("ui-state-hover")},mouseleave:function(e){t(e.currentTarget).removeClass("ui-state-hover")}})},_focusable:function(e){this.focusable=this.focusable.add(e),this._on(e,{focusin:function(e){t(e.currentTarget).addClass("ui-state-focus")},focusout:function(e){t(e.currentTarget).removeClass("ui-state-focus")}})},_trigger:function(e,i,s){var n,o,a=this.options[e];if(s=s||{},i=t.Event(i),i.type=(e===this.widgetEventPrefix?e:this.widgetEventPrefix+e).toLowerCase(),i.target=this.element[0],o=i.originalEvent)for(n in o)n in i||(i[n]=o[n]);return this.element.trigger(i,s),!(t.isFunction(a)&&a.apply(this.element[0],[i].concat(s))===!1||i.isDefaultPrevented())}},t.each({show:"fadeIn",hide:"fadeOut"},function(e,i){t.Widget.prototype["_"+e]=function(s,n,o){"string"==typeof n&&(n={effect:n});var a,r=n?n===!0||"number"==typeof n?i:n.effect||i:e;n=n||{},"number"==typeof n&&(n={duration:n}),a=!t.isEmptyObject(n),n.complete=o,n.delay&&s.delay(n.delay),a&&t.effects&&t.effects.effect[r]?s[e](n):r!==e&&s[r]?s[r](n.duration,n.easing,o):s.queue(function(i){t(this)[e](),o&&o.call(s[0]),i()})}})})(jQuery);(function(e){var t=0,i={},a={};i.height=i.paddingTop=i.paddingBottom=i.borderTopWidth=i.borderBottomWidth="hide",a.height=a.paddingTop=a.paddingBottom=a.borderTopWidth=a.borderBottomWidth="show",e.widget("ui.accordion",{version:"1.10.3",options:{active:0,animate:{},collapsible:!1,event:"click",header:"> li > :first-child,> :not(li):even",heightStyle:"auto",icons:{activeHeader:"ui-icon-triangle-1-s",header:"ui-icon-triangle-1-e"},activate:null,beforeActivate:null},_create:function(){var t=this.options;this.prevShow=this.prevHide=e(),this.element.addClass("ui-accordion ui-widget ui-helper-reset").attr("role","tablist"),t.collapsible||t.active!==!1&&null!=t.active||(t.active=0),this._processPanels(),0>t.active&&(t.active+=this.headers.length),this._refresh()},_getCreateEventData:function(){return{header:this.active,panel:this.active.length?this.active.next():e(),content:this.active.length?this.active.next():e()}},_createIcons:function(){var t=this.options.icons;t&&(e("<span>").addClass("ui-accordion-header-icon ui-icon "+t.header).prependTo(this.headers),this.active.children(".ui-accordion-header-icon").removeClass(t.header).addClass(t.activeHeader),this.headers.addClass("ui-accordion-icons"))},_destroyIcons:function(){this.headers.removeClass("ui-accordion-icons").children(".ui-accordion-header-icon").remove()},_destroy:function(){var e;this.element.removeClass("ui-accordion ui-widget ui-helper-reset").removeAttr("role"),this.headers.removeClass("ui-accordion-header ui-accordion-header-active ui-helper-reset ui-state-default ui-corner-all ui-state-active ui-state-disabled ui-corner-top").removeAttr("role").removeAttr("aria-selected").removeAttr("aria-controls").removeAttr("tabIndex").each(function(){/^ui-accordion/.test(this.id)&&this.removeAttribute("id")}),this._destroyIcons(),e=this.headers.next().css("display","").removeAttr("role").removeAttr("aria-expanded").removeAttr("aria-hidden").removeAttr("aria-labelledby").removeClass("ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content ui-accordion-content-active ui-state-disabled").each(function(){/^ui-accordion/.test(this.id)&&this.removeAttribute("id")}),"content"!==this.options.heightStyle&&e.css("height","")},_setOption:function(e,t){return"active"===e?(this._activate(t),undefined):("event"===e&&(this.options.event&&this._off(this.headers,this.options.event),this._setupEvents(t)),this._super(e,t),"collapsible"!==e||t||this.options.active!==!1||this._activate(0),"icons"===e&&(this._destroyIcons(),t&&this._createIcons()),"disabled"===e&&this.headers.add(this.headers.next()).toggleClass("ui-state-disabled",!!t),undefined)},_keydown:function(t){if(!t.altKey&&!t.ctrlKey){var i=e.ui.keyCode,a=this.headers.length,s=this.headers.index(t.target),n=!1;switch(t.keyCode){case i.RIGHT:case i.DOWN:n=this.headers[(s+1)%a];break;case i.LEFT:case i.UP:n=this.headers[(s-1+a)%a];break;case i.SPACE:case i.ENTER:this._eventHandler(t);break;case i.HOME:n=this.headers[0];break;case i.END:n=this.headers[a-1]}n&&(e(t.target).attr("tabIndex",-1),e(n).attr("tabIndex",0),n.focus(),t.preventDefault())}},_panelKeyDown:function(t){t.keyCode===e.ui.keyCode.UP&&t.ctrlKey&&e(t.currentTarget).prev().focus()},refresh:function(){var t=this.options;this._processPanels(),t.active===!1&&t.collapsible===!0||!this.headers.length?(t.active=!1,this.active=e()):t.active===!1?this._activate(0):this.active.length&&!e.contains(this.element[0],this.active[0])?this.headers.length===this.headers.find(".ui-state-disabled").length?(t.active=!1,this.active=e()):this._activate(Math.max(0,t.active-1)):t.active=this.headers.index(this.active),this._destroyIcons(),this._refresh()},_processPanels:function(){this.headers=this.element.find(this.options.header).addClass("ui-accordion-header ui-helper-reset ui-state-default ui-corner-all"),this.headers.next().addClass("ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom").filter(":not(.ui-accordion-content-active)").hide()},_refresh:function(){var i,a=this.options,s=a.heightStyle,n=this.element.parent(),r=this.accordionId="ui-accordion-"+(this.element.attr("id")||++t);this.active=this._findActive(a.active).addClass("ui-accordion-header-active ui-state-active ui-corner-top").removeClass("ui-corner-all"),this.active.next().addClass("ui-accordion-content-active").show(),this.headers.attr("role","tab").each(function(t){var i=e(this),a=i.attr("id"),s=i.next(),n=s.attr("id");a||(a=r+"-header-"+t,i.attr("id",a)),n||(n=r+"-panel-"+t,s.attr("id",n)),i.attr("aria-controls",n),s.attr("aria-labelledby",a)}).next().attr("role","tabpanel"),this.headers.not(this.active).attr({"aria-selected":"false",tabIndex:-1}).next().attr({"aria-expanded":"false","aria-hidden":"true"}).hide(),this.active.length?this.active.attr({"aria-selected":"true",tabIndex:0}).next().attr({"aria-expanded":"true","aria-hidden":"false"}):this.headers.eq(0).attr("tabIndex",0),this._createIcons(),this._setupEvents(a.event),"fill"===s?(i=n.height(),this.element.siblings(":visible").each(function(){var t=e(this),a=t.css("position");"absolute"!==a&&"fixed"!==a&&(i-=t.outerHeight(!0))}),this.headers.each(function(){i-=e(this).outerHeight(!0)}),this.headers.next().each(function(){e(this).height(Math.max(0,i-e(this).innerHeight()+e(this).height()))}).css("overflow","auto")):"auto"===s&&(i=0,this.headers.next().each(function(){i=Math.max(i,e(this).css("height","").height())}).height(i))},_activate:function(t){var i=this._findActive(t)[0];i!==this.active[0]&&(i=i||this.active[0],this._eventHandler({target:i,currentTarget:i,preventDefault:e.noop}))},_findActive:function(t){return"number"==typeof t?this.headers.eq(t):e()},_setupEvents:function(t){var i={keydown:"_keydown"};t&&e.each(t.split(" "),function(e,t){i[t]="_eventHandler"}),this._off(this.headers.add(this.headers.next())),this._on(this.headers,i),this._on(this.headers.next(),{keydown:"_panelKeyDown"}),this._hoverable(this.headers),this._focusable(this.headers)},_eventHandler:function(t){var i=this.options,a=this.active,s=e(t.currentTarget),n=s[0]===a[0],r=n&&i.collapsible,o=r?e():s.next(),h=a.next(),d={oldHeader:a,oldPanel:h,newHeader:r?e():s,newPanel:o};t.preventDefault(),n&&!i.collapsible||this._trigger("beforeActivate",t,d)===!1||(i.active=r?!1:this.headers.index(s),this.active=n?e():s,this._toggle(d),a.removeClass("ui-accordion-header-active ui-state-active"),i.icons&&a.children(".ui-accordion-header-icon").removeClass(i.icons.activeHeader).addClass(i.icons.header),n||(s.removeClass("ui-corner-all").addClass("ui-accordion-header-active ui-state-active ui-corner-top"),i.icons&&s.children(".ui-accordion-header-icon").removeClass(i.icons.header).addClass(i.icons.activeHeader),s.next().addClass("ui-accordion-content-active")))},_toggle:function(t){var i=t.newPanel,a=this.prevShow.length?this.prevShow:t.oldPanel;this.prevShow.add(this.prevHide).stop(!0,!0),this.prevShow=i,this.prevHide=a,this.options.animate?this._animate(i,a,t):(a.hide(),i.show(),this._toggleComplete(t)),a.attr({"aria-expanded":"false","aria-hidden":"true"}),a.prev().attr("aria-selected","false"),i.length&&a.length?a.prev().attr("tabIndex",-1):i.length&&this.headers.filter(function(){return 0===e(this).attr("tabIndex")}).attr("tabIndex",-1),i.attr({"aria-expanded":"true","aria-hidden":"false"}).prev().attr({"aria-selected":"true",tabIndex:0})},_animate:function(e,t,s){var n,r,o,h=this,d=0,c=e.length&&(!t.length||e.index()<t.index()),l=this.options.animate||{},u=c&&l.down||l,v=function(){h._toggleComplete(s)};return"number"==typeof u&&(o=u),"string"==typeof u&&(r=u),r=r||u.easing||l.easing,o=o||u.duration||l.duration,t.length?e.length?(n=e.show().outerHeight(),t.animate(i,{duration:o,easing:r,step:function(e,t){t.now=Math.round(e)}}),e.hide().animate(a,{duration:o,easing:r,complete:v,step:function(e,i){i.now=Math.round(e),"height"!==i.prop?d+=i.now:"content"!==h.options.heightStyle&&(i.now=Math.round(n-t.outerHeight()-d),d=0)}}),undefined):t.animate(i,o,r,v):e.animate(a,o,r,v)},_toggleComplete:function(e){var t=e.oldPanel;t.removeClass("ui-accordion-content-active").prev().removeClass("ui-corner-top").addClass("ui-corner-all"),t.length&&(t.parent()[0].className=t.parent()[0].className),this._trigger("activate",null,e)}})})(jQuery);


// adding capitalize function to String
String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

// jquery.shopware.js extension / override

//Sets error class
setError = function (el) {
    if (!$.isEmptyObject(el)) {
        $.each($(el), function (i, e) {
            $(e).removeClass(SwfConfig.successClass).addClass(SwfConfig.errorClass);
        });
    } else {
        $(el).removeClass(SwfConfig.successClass).addClass(SwfConfig.errorClass);
    }
    $(el).trigger('setError');
    return el;
};

//Sets success class
setSuccess = function (el) {
    if (!$.isEmptyObject(el)) {
        $.each($(el), function (i, e) {
            $(e).removeClass(SwfConfig.errorClass).addClass(SwfConfig.successClass);
        });
    } else {
        $(el).removeClass(SwfConfig.errorClass).addClass(SwfConfig.successClass);
    }
    $(el).trigger('setSuccess');
    return el;
};

$u = function(el) {
    return $('[data-use="' + el + '"]');
}

$d = function(el) {
    $(el).die();
    return $(el);
}

$.basket.getBasket = function () {
    if(SwfGettingAjaxCart == true)
        return;
    SwfGettingAjaxCart = true;

    if(!$($.basket.options.basketResult).length) {
        $('<div>', {
            'class': 'ajax_basket_result'
        }).appendTo(document.body);
    }
    $($.basket.options.basketLoader).show();
    $.ajax({
        'data': {
            'sAction': 'ajaxCart'
        },
        'dataType': 'jsonp',
        'url': $.basket.options.viewport,
        'success': function (result) {
            var offset = $($.basket.options.basketParent).offset();
            $($.basket.options.basketResult).css({
                'top': offset.top + 21,
//                'left': offset.left -($($.basket.options.basketResult).width() - $($.basket.options.basketParent).width() + ($.isiPad() ? -35 : 22))	// Hier die 20 aendern
                'left': offset.left -($($.basket.options.basketResult).width() - $($.basket.options.basketParent).width() +  22)	// Hier die 20 aendern
            });
            $($.basket.options.basketLoader).hide();
            if (result.length) {
                $($.basket.options.basketResult).empty().html(result);
            } else {
                $($.basket.options.basketResult).empty().html($.basket.options.emptyText);
            }
            $($.basket.options.basketParent).addClass('active');
            $($.basket.options.basketResult).addClass('active').slideDown('fast');
            $(document.body).bind('click.basket', function() {
                $($.basket.options.basketResult).removeClass('active').slideUp('fast');
                $($.basket.options.basketParent).removeClass('active');
                $(document.body).unbind('click.basket');
            });
            SwfGettingAjaxCart = false;
        },
        'error': function() {
            SwfGettingAjaxCart = false;
        }
    });
}