(function ($) {
    $(document).ready(function () {
        $('.dhl_actions').click(function () {
            var container = $(this).parents('.alternative_shipping'),
                postNumber = container.find('input[name=postnumber]').val(),
                url = $(this).parents('form.payment').find('input[name=saveShippingUrl]').val();

            if (!postNumber || postNumber.length < 6 || postNumber.length > 10) {
                container.find('input[name=postnumber]').css({
                    'background-color': '#F2DEDE',
                    'color': '#B94A48',
                    'border-color': '#B94A48'
                });
            } else {
                container.parents('.dhl_container').css('opacity', 0.3);

                var postData = {
                    'plz': container.find('.zipcode').val(),
                    'city': container.find('.city').val(),
                    'country': container.find('.own_select').find(':checked').val(),
                    'userId': container.find('input[name=userId]').val(),
                    'identifier': container.find('input[name=identifier]').val(),
                    'number': container.find('input[name=number]').val(),
                    'postnumber': postNumber,
                    'street': container.find('input[name=street]').val(),
                    'streetnumber': container.find('input[name=streetnumber]').val()
                };

                $.post(url, postData).done(function () {
                    container.parents('.dhl_container').css('opacity', 1);
                });
            }
        });

        $('input[name=postnumber]').on('keyup blur', function () {
            var $this = $(this),
                reg = /^\d+$/,
                postNumber = $this.val();
            if (postNumber && postNumber.length >= 6 && postNumber.length <= 10 && reg.test(postNumber)) {
                $this.css({
                    'background-color': '#F0F6E1',
                    'color': '#AABE00',
                    'border-color': '#AABE00'
                });
                $this.parents('.dhl_container').find('.dhl_actions input').prop('disabled', false);
            } else {
                $this.css({
                    'background-color': '#F2DEDE',
                    'color': '#B94A48',
                    'border-color': '#B94A48'
                });
                $this.parents('.dhl_container').find('.dhl_actions input').prop('disabled', true);
            }
        });

        $('.own_modal a').click(function (event) {
            event.preventDefault();
            $.post(this.href, function (data) {
                $.modal(data, '', {
                    'position': 'fixed',
                    'width': 860
                }).find('.close').remove();
            });
        });

        $('.dhl_radio').click(function () {
            var button = $(this),
                url = $(this).parents('form.payment').find('input[name=selectShippingDispatchUrl]').val();

            var data = {
                'identifier': $(this).parents('.dhl_container').find('input[name=identifier]').val()
            };
            $.post(url, data).done(function (response) {
                button.parents('form.payment').submit();
            });
        });
    });
})(jQuery);

function updatePackstationShipping(packStationId, zip, city, street, streetNumber) {
    $('#packstation_zipcode').val(zip);
    $('#packstation_city').val(city);
    $('#packstation_number').val(packStationId);
    $('#packstation_street').val(street);
    $('#packstation_streetnumber').val(streetNumber);
}

function updatePostOfficeShipping(postOfficeId, zip, city, street, streetNumber) {
    $('#postoffice_zipcode').val(zip);
    $('#postoffice_city').val(city);
    $('#postoffice_number').val(postOfficeId);
    $('#postoffice_street').val(street);
    $('#postoffice_streetnumber').val(streetNumber);
}