/**
 *
 */
//{namespace name="backend/dhl/view/main"}
//{block name="backend/dhl/view/main/detail"}
Ext.define('Shopware.apps.Dhl.view.main.Detail', {
    extend: 'Enlight.app.Window',
    alias: 'widget.dhl-main-detail',
    cls: 'createWindow',
    layout: 'border',
    autoShow: true,
    title: '{s name=dhl/view/main/detail/title}order details{/s}',
    border: 0,
    width: 700,
    height: 590,
    footerButton: false,

    initComponent: function () {
        var me = this;

        me.dhlForm = me.createFormPanel();

        me.dockedItems = [
            {
                xtype: 'toolbar',
                ui: 'shopware-ui',
                dock: 'bottom',
                cls: 'shopware-toolbar',
                items: me.createButtons()
            }
        ];
        me.items = [me.dhlForm];
        me.callParent(arguments);
    },

    createButtons: function () {
        var me = this;
        var buttons = ['->',
            {
                text: '{s name=dhl/view/main/detail/close}Close{/s}',
                cls: 'secondary',
                scope: me,
                handler: me.destroy
            }
        ];

        if (!me.record.get('statusShipped')) {
            buttons.push({
                text: '{s name=dhl/view/main/detail/sendOrder}Send order to DHL{/s}',
                action: 'sendOrder',
                cls: 'primary'
            });
        }
        else {
            buttons.push({
                text: '{s name=dhl/view/main/detail/cancelOrder}Cancel order{/s}',
                action: 'cancelOrder',
                cls: 'primary'
            });
        }

        return buttons;
    },

    createFormPanel: function () {
        var me = this;

        var form = Ext.create('Ext.form.Panel', {
            collapsible: false,
            split: false,
            region: 'center',
            defaults: {
                labelStyle: 'font-weight: 700; text-align: right;',
                labelWidth: 80,
                anchor: '100%'
            },
            cls: 'shopware-form',
            border: 0,
            bodyPadding: 10,
            items: [
                {
                    xtype: 'fieldset',
                    title: '{s name=dhl/view/main/detail/shippingAddress}shipping address{/s}',
                    items: [
                        {
                            xtype: 'container',
                            layout: 'column',
                            items: [
                                me.createLeftContainer(),
                                me.createRightContainer()
                            ]
                        }
                    ]
                },
                {
                    xtype: 'fieldset',
                    name: 'additionalInfos',
                    title: '{s name=dhl/view/main/detail/additionalOptions}additional options{/s}',
                    items: [
                        {
                            xtype: 'container',
                            layout: 'column',
                            items: [
                                me.createLeftInfoContainer(),
                                me.createRightInfoContainer()
                            ]
                        },
                        {
                            xtype: 'button',
                            text: '{s name=dhl/view/main/detail/save}Save{/s}',
                            action: 'saveInfoData',
                            cls: 'primary small'
                        }
                    ]
                }
            ]
        });

        return form;
    },

    createLeftContainer: function () {
        var me = this;
        return Ext.create('Ext.container.Container', {
            columnWidth: .5,
            border: false,
            layout: 'anchor',
            defaults: {
                anchor: '95%',
                labelWidth: 100,
                minWidth: 250,
                labelStyle: 'font-weight: 700;',
                style: {
                    margin: '0 0 10px'
                },
                allowBlank: false
            },
            items: [
                {
                    xtype: 'textfield',
                    fieldLabel: '{s name=dhl/view/main/detail/firstName}first name{/s}',
                    value: me.record.get('firstName'),
                    name: 'firstName'
                },
                {
                    xtype: 'hidden',
                    name: 'orderNumber',
                    value: me.record.get('number')
                },
                {
                    xtype: 'hidden',
                    name: 'shippingNumber',
                    value: me.record.get('dhlShippingNumber')
                },
                {
                    xtype: 'textfield',
                    fieldLabel: '{s name=dhl/view/main/detail/street}street{/s}',
                    value: me.record.get('street'),
                    name: 'street',
                    id: 'street'
                },
                {
                    xtype: 'textfield',
                    fieldLabel: '{s name=dhl/view/main/detail/zip}zip code{/s}',
                    value: me.record.get('zip'),
                    name: 'zip'
                },
                {
                    xtype: 'textfield',
                    fieldLabel: '{s name=dhl/view/main/detail/country}country{/s}',
                    value: me.record.get('country'),
                    name: 'country',
                    allowBlank: true,
                    readOnly: true
                }
            ]
        });
    },

    createRightContainer: function () {
        var me = this;
        return Ext.create('Ext.container.Container', {
            columnWidth: .5,
            border: false,
            layout: 'anchor',
            defaults: {
                anchor: '95%',
                labelWidth: 90,
                minWidth: 250,
                labelStyle: 'font-weight: 700;',
                style: {
                    margin: '0 0 10px'
                },
                allowBlank: false
            },
            items: [
                {
                    xtype: 'textfield',
                    fieldLabel: '{s name=dhl/view/main/detail/lastName}last name{/s}',
                    value: me.record.get('lastName'),
                    name: 'lastName'
                },
                {
                    xtype: 'textfield',
                    fieldLabel: '{s name=dhl/view/main/detail/streetNumber}street number{/s}',
                    value: me.record.get('streetNumber'),
                    name: 'streetNumber',
                    id: 'streetNumber'
                },
                {
                    xtype: 'textfield',
                    fieldLabel: '{s name=dhl/view/main/detail/city}city{/s}',
                    value: me.record.get('city'),
                    name: 'city'
                },
                {
                    xtype: 'numberfield',
                    fieldLabel: '{s name=dhl/view/main/detail/postNumber}post number{/s}',
                    value: me.record.get('postNumber'),
                    name: 'postNumber',
                    readOnly: true,
                    allowBlank: true
                }
            ]
        });
    },

    createLeftInfoContainer: function () {
        var me = this;
        return Ext.create('Ext.container.Container', {
            columnWidth: .5,
            border: false,
            layout: 'anchor',
            defaults: {
                anchor: '95%',
                labelWidth: 100,
                minWidth: 250,
                labelStyle: 'font-weight: 700;',
                style: {
                    margin: '0 0 10px'
                },
                allowBlank: false
            },
            items: [
                {
                    xtype: 'numberfield',
                    fieldLabel: '{s name=dhl/view/main/detail/weight}weight{/s}',
                    value: me.record.get('weight'),
                    name: 'weight',
                    maxValue: 31.5,
                    minValue: 0.1
                },
                {
                    xtype: 'checkbox',
                    name: 'useCod',
                    fieldLabel: '{s name=dhl/view/main/detail/useCOD}send by cash on delivery{/s}',
                    inputValue: 1,
                    uncheckedValue: 0,
                    checked: me.record.get('useCod'),
                    readOnly: true,
                    listeners: {
                        afterrender: function (checkbox) {
                            var rFp = Ext.ComponentQuery.query('#reasonForPayment')[0],
                                iA = Ext.ComponentQuery.query('#invoiceAmount')[0];
                            if (checkbox.value) {
                                rFp.allowBlank = false;
                                rFp.setVisible(true);
                                iA.setVisible(true);
                            }
                            else {
                                rFp.allowBlank = true;
                                rFp.setVisible(false);
                                iA.setVisible(false);
                            }
                        }
                    }
                },
                {
                    xtype: 'textfield',
                    fieldLabel: '{s name=dhl/view/main/detail/reasonForPayment}reason for payment{/s}',
                    name: 'reasonForPayment',
                    id: 'reasonForPayment',
                    allowBlank: true,
                    hidden: true,
                    hideMode: 'visibility',
                    value: '{s name=dhl/view/main/detail/reasonForPaymentValue}order number: {/s}' + me.record.get('number')
                },
                {
                    xtype: 'textfield',
                    fieldLabel: '{s name=dhl/view/main/detail/invoiceAmount}invoice amount{/s}',
                    name: 'invoiceAmount',
                    id: 'invoiceAmount',
                    value: me.record.get('invoiceAmount'),
                    readOnly: true,
                    allowBlank: true,
                    hidden: true,
                    hideMode: 'visibility'
                }
            ]
        })
    },

    createRightInfoContainer: function () {
        var me = this,
            today = new Date(),
            store = Ext.create('Shopware.apps.Dhl.store.Attendances'),
            attendance = me.record.get('attendance');
        store.getProxy().extraParams.country = me.record.get('country');

        store.on('load', function(store, records) {
            if(!attendance) {
                attendance = records[0].get('value');
                Ext.ComponentQuery.query('#attendance')[0].setValue(attendance);
            }
        });

        return Ext.create('Ext.container.Container', {
            columnWidth: .5,
            border: false,
            layout: 'anchor',
            defaults: {
                anchor: '95%',
                labelWidth: 100,
                minWidth: 250,
                labelStyle: 'font-weight: 700;',
                style: {
                    margin: '0 0 10px'
                },
                allowBlank: false
            },
            items: [
                {
                    xtype: 'combo',
                    store: store.load(),
                    displayField: 'value',
                    valueField: 'value',
                    fieldLabel: '{s name=dhl/view/main/detail/attendance}select attendance{/s}',
                    value: attendance,
                    name: 'attendance',
                    id: 'attendance',
                    editable: false
                },
                {
                    xtype: 'datefield',
                    name: 'shippingDate',
                    value: today,
                    format: 'd-m-Y',
                    allowBlank: false,
                    labelWidth: 155,
                    columnWidth: 0.6,
                    labelStyle: 'font-weight: bold',
                    fieldLabel: '{s name=dhl/view/main/detail/shippingDate}shipping date{/s}',
                    minValue: today
                },
                {
                    xtype: 'checkbox',
                    fieldLabel: '{s name=dhl/view/main/detail/useInsurance}use higher insurance up to 2500€{/s}',
                    name: 'useInsurance',
                    inputValue: 1,
                    uncheckedValue: 0,
                    checked: me.record.get('useInsurance')
                },
                {
                    xtype: 'checkbox',
                    fieldLabel: '{s name=dhl/view/main/detail/bulkFreight}bulky freight{/s}',
                    name: 'isBulkfreight',
                    inputValue: 1,
                    uncheckedValue: 0,
                    checked: me.record.get('isBulkfreight'),
                    listeners: {
                        change: function (checkbox, value) {
                            if (value && me.record.get('street') == 'Packstation') {
                                Ext.Msg.alert('{s name=dhl/view/main/detail/attention}Attention!{/s}', '{s name=dhl/view/main/detail/message_bulkFreight}Sending bulky freight to Packstation is not allowed!{/s}');
                                checkbox.setValue(0);
                            }
                        },
                        added: function (checkbox) {
                            if (checkbox.value && me.record.get('street') == 'Packstation') {
                                Ext.Msg.alert('{s name=dhl/view/main/detail/attention}Attention!{/s}', '{s name=dhl/view/main/detail/message_bulkFreight}Sending bulky freight to Packstation is not allowed!{/s}');
                                var street = Ext.ComponentQuery.query('#street')[0],
                                    streetNumber = Ext.ComponentQuery.query('#streetNumber')[0];
                                street.markInvalid('{s name=dhl/view/main/detail/attention}Attention!{/s}');
                                streetNumber.markInvalid('{s name=dhl/view/main/detail/attention}Attention!{/s}');
                            }
                        }
                    }
                },
                {
                    xtype: 'checkbox',
                    fieldLabel: '{s name=dhl/view/main/detail/codeable}print only if codeable{/s}',
                    name: 'printOnlyIfCodeable',
                    inputValue: 1,
                    uncheckedValue: 0,
                    checked: 1,
                    listeners: {
                        change: function (checkbox, value) {
                            if (!value) {
                                Ext.Msg.alert('{s name=dhl/view/main/detail/attention}Attention!{/s}', '{s name=dhl/view/main/detail/message_codeable}If you disable this option, you will be charged by DHL 0.15€ more for this order, as it has to be coded by hand. Turn it off only if you are sure.{/s}');
                            }
                        }
                    }
                }
            ]
        })
    }
});
//{/block}