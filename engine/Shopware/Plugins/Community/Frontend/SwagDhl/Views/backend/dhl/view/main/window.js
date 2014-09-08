/**
 *
 */
//{namespace name="backend/dhl/view/main"}
//{block name="backend/dhl/view/main/window"}
Ext.define('Shopware.apps.Dhl.view.main.Window', {
    extend: 'Enlight.app.Window',
    title: '{s name=dhl/view/main/window/title}orders with DHL shipping{/s}',
    cls: Ext.baseCSSPrefix + 'swagdhl-window',
    alias: 'widget.dhl-main-window',
    border: false,
    autoShow: true,
    layout: 'border',
    height: '90%',
    width: 925,

    stateful: true,
    stateId: 'shopware-swagdhl-window',

    /**
     * Initializes the component and builds up the main interface
     *
     * @return void
     */
    initComponent: function () {
        var me = this;

        me.items = [
            {
                xtype: 'dhl-main-list',
                dhlStore: me.dhlStore
            }
        ];

        me.callParent(arguments);
    }
});
//{/block}