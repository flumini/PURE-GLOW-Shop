/**
 * Shopware ExtJs controller
 */
//{namespace name="backend/dhl/view/main"}
//{block name="backend/dhl/controller/dhl"}
Ext.define('Shopware.apps.Dhl.controller.Dhl', {
    extend: 'Ext.app.Controller',

    refs: [
        { ref: 'list', selector: 'dhl-main-list' }
    ],

    init: function () {
        var me = this;

        me.control({
            'dhl-main-list textfield[action=searchDhlOrders]': {
                fieldchange: me.onSearch
            },
            'dhl-main-list': {
                'openOrder': me.openOrder,
                'openAcceptance': me.openAcceptance
            },
            'dhl-main-list button[action=printLabels]': {
                'click': me.printLabels
            },
            'dhl-main-detail button[action=sendOrder]': {
                click: me.sendOrder
            },
            'dhl-main-detail button[action=cancelOrder]': {
                click: me.cancelOrder
            },
            'dhl-main-detail button[action=saveShippingData]': {
                click: me.saveShippingDetails
            },
            'dhl-main-detail button[action=saveInfoData]': {
                click: me.saveShippingDetails
            }
        });
    },

    cancelOrder: function (btn) {
        var me = this,
            win = btn.up('window'),
            form = win.down('form'),
            values = form.getValues();

        win.setLoading(true);
        Ext.Ajax.request({
            url: '{url controller=dhl action=cancelOrder}',
            params: {
                shippingNumber: values.shippingNumber
            },
            success: function (response) {
                response = Ext.JSON.decode(response.responseText);
                var message = response.message;

                win.setLoading(false);
                if (response.success) {
                    win.destroy();
                    me.subApplication.dhlStore.load();
                } else {
                    Ext.Msg.show({
                        title: '{s name=dhl/controller/attention}Attention!{/s}',
                        msg: '{s name=dhl/controller/message_cancelOrder}The order was NOT canceled with the following message: {/s}' + message,
                        buttons: Ext.Msg.OK,
                        icon: Ext.Msg.WARNING
                    });

                    me.subApplication.dhlStore.load();
                }
            }
        });
    },

    saveShippingDetails: function (btn) {
        var me = this,
            win = btn.up('window'),
            form = win.down('form'),
            values = form.getValues();

        if (form.getForm().isValid()) {
            win.setLoading(true);
            Ext.Ajax.request({
                url: '{url controller=dhl action=saveShippingDetails}',
                params: {
                    firstName: values.firstName,
                    lastName: values.lastName,
                    city: values.city,
                    zip: values.zip,
                    street: values.street,
                    streetNumber: values.streetNumber,
                    orderNumber: values.orderNumber,
                    weight: values.weight,
                    postNumber: values.postNumber,
                    attendance: values.attendance,
                    useInsurance: values.useInsurance,
                    isBulkfreight: values.isBulkfreight
                },
                success: function () {
                    win.setLoading(false);

                    me.subApplication.dhlStore.load();
                }
            });
        }
    },

    sendOrder: function (btn) {
        var me = this,
            win = btn.up('window'),
            form = win.down('form'),
            values = form.getValues();

        if (!form.getForm().isValid()) {
            return;
        }

        win.setLoading(true);
        Ext.Ajax.request({
            url: '{url controller=dhl action=sendOrder}',
            params: {
                firstName: values.firstName,
                lastName: values.lastName,
                city: values.city,
                zip: values.zip,
                street: values.street,
                streetNumber: values.streetNumber,
                insurance: values.insurance,
                useInsurance: values.useInsurance,
                orderNumber: values.orderNumber,
                weight: values.weight,
                postNumber: values.postNumber,
                attendance: values.attendance,
                shippingDate: values.shippingDate,
                isBulkfreight: values.isBulkfreight,
                useCod: values.useCod,
                invoiceAmount: values.invoiceAmount,
                reasonForPayment: values.reasonForPayment,
                printOnlyIfCodeable: values.printOnlyIfCodeable
            },
            success: function (response) {
                response = Ext.JSON.decode(response.responseText);
                var message = response.message;
                win.setLoading(false);

                if (response.success) {
                    if (message != "ok") {
                        Ext.Msg.show({
                            title: '{s name=dhl/controller/attention}Attention!{/s}',
                            msg: '{s name=dhl/controller/message_warning}A warning has occured: {/s}' + message + ".<br />",
                            buttons: Ext.Msg.OK,
                            icon: Ext.Msg.WARNING
                        });
                    }
                    win.destroy();
                    me.subApplication.dhlStore.load();
                } else {
                    Ext.Msg.show({
                        title: '{s name=dhl/controller/attention}Attention!{/s}',
                        msg: '{s name=dhl/controller/message_error}An error has occured: {/s}' + message + ".<br />",
                        buttons: Ext.Msg.OK,
                        icon: Ext.Msg.ERROR
                    });

                    win.destroy();
                    me.subApplication.dhlStore.load();
                }
            }
        });
    },

    openAcceptance: function (record) {
        this.getView('main.Detail').create({ record: record});
    },

    printLabels: function () {
        var me = this,
            grid = me.getList(),
            selections = grid.selModel.getSelection(),
            itemString = [];
        Ext.each(selections, function (item) {
            itemString += "," + item.get('number');
        });
        itemString = itemString.slice(1, itemString.length);

        Ext.Ajax.request({
            url: "{url controller=dhl action=printLabels}",
            params: {
                selections: itemString,
                first: true
            },
            success: function (response) {
                response = Ext.JSON.decode(response.responseText);
                var message = response.message;
                if(response.success) {
                    new Ext.Window({
                        title: "iframe",
                        width: 1,
                        hidden: true,
                        height: 1,
                        layout: 'fit',
                        items: [
                            {
                                xtype: "component",
                                autoEl: {
                                    tag: "iframe",
                                    src: "{url controller=dhl action=printLabels}" + "/selections/" + itemString
                                }
                            }
                        ]
                    }).show().hide();
                } else {
                    Ext.Msg.show({
                        title: '{s name=dhl/controller/attention}Attention!{/s}',
                        msg: '{s name=dhl/controller/message_warning}A warning has occured: {/s}' + message,
                        buttons: Ext.Msg.OK,
                        icon: Ext.Msg.WARNING
                    });
                }
            }
        });
    },

    openOrder: function (record) {
        Shopware.app.Application.addSubApplication({
            name: 'Shopware.apps.Order',
            action: 'detail',
            params: {
                orderId: record.get('id')
            }
        });
    },

    onSearch: function (field) {
        var me = this,
            store = me.subApplication.dhlStore;

        //If the search-value is empty, reset the filter
        if (field.getValue().length == 0) {
            store.clearFilter();
        } else {
            //This won't reload the store
            store.filters.clear();
            //Loads the store with a special filter
            store.filter('searchValue', field.getValue());
        }
    }
});
//{/block}