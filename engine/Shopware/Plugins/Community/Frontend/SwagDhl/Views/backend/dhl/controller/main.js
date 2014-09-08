Ext.define('Shopware.apps.Dhl.controller.Main', {
    extend: 'Ext.app.Controller',

    init: function () {
        var me = this;

        me.subApplication.dhlStore = me.subApplication.getStore('Orders').load();
        me.mainWindow = me.getView('main.Window').create({
            dhlStore: me.subApplication.dhlStore
        });

        me.callParent(arguments);
    }
});