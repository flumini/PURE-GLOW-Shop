Ext.define('Shopware.apps.Dhl', {

    extend: 'Enlight.app.SubApplication',

    loadPath: '{url action=load}',
    bulkLoad: true,

    controllers: [ 'Main', 'Dhl' ],

    views: [
        'main.Window',
        'main.List',
        'main.Detail'
    ],

    models: [ 'Order', 'Attendance' ],
    stores: [ 'Orders', 'Attendances' ],

    launch: function () {
        var me = this,
                mainController = me.getController('Main');

        return mainController.mainWindow;
    }
});