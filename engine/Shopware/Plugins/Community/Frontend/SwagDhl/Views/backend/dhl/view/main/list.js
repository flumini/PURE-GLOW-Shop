/**
 *
 */
//{namespace name="backend/dhl/view/main"}
//{block name="backend/dhl/view/main/list"}
Ext.define('Shopware.apps.Dhl.view.main.List', {

    /**
     * Extend from the standard ExtJS 4
     * @string
     */
    extend: 'Ext.grid.Panel',
    border: 0,

    ui: 'shopware-ui',

    /**
     * Alias name for the view. Could be used to get an instance
     * of the view through Ext.widget('premium-main-list')
     * @string
     */
    alias: 'widget.dhl-main-list',

    /**
     * The window uses a border layout, so we need to set
     * a region for the grid panel
     * @string
     */
    region: 'center',

    /**
     * The view needs to be scrollable
     * @string
     */
    autoScroll: true,

    /**
     * Sets up the ui component
     * @return void
     */
    initComponent: function () {
        var me = this;
        me.registerEvents();

        me.dockedItems = [];
        me.store = me.dhlStore;
        me.selModel = me.getGridSelModel();
        me.columns = me.getColumns();
        me.toolbar = me.getToolbar();
        me.dockedItems.push(me.toolbar);

        // Add paging toolbar to the bottom of the grid panel
        me.dockedItems.push({
            dock: 'bottom',
            xtype: 'pagingtoolbar',
            displayInfo: true,
            store: me.store
        });

        me.callParent(arguments);
    },

    /**
     * Creates the selectionModel of the grid with a listener to enable the print-label button
     */
    getGridSelModel: function () {
        var selModel = Ext.create('Ext.selection.CheckboxModel', {
            listeners: {
                selectionchange: function (sm, selections) {
                    var owner = this.view.ownerCt,
                            btn2 = owner.down('button[action=printLabels]');
                    if (btn2) {
                        btn2.setDisabled(selections.length == 0);
                    }
                }
            }
        });

        return selModel;
    },

    /**
     * Defines additional events which will be
     * fired from the component
     *
     * @return void
     */
    registerEvents: function () {
        this.addEvents(

                /**
                 * Event will be fired when the user clicks the customer-icon in the
                 * action column
                 *
                 * @event openOrder
                 * @param [object] record - The record of the clicked row
                 */
                'openOrder',

                /**
                 * Event will be fired when the user clicks on the "tick"-icon, which only appears on non-shipped orders
                 *
                 * @event openAcceptance
                 * @param [object] record = The record of the clicked row
                 */
                'openAcceptance'
        );

        return true;
    },

    /**
     *  Creates the columns
     */
    getColumns: function () {
        var me = this,
                buttons = new Array();

        buttons.push(Ext.create('Ext.button.Button', {
            iconCls: 'sprite-user',
            cls: 'editBtn',
            tooltip: '{s name=dhl/view/main/list/openOrder}open order{/s}',
            handler: function (view, rowIndex, colIndex, item) {
                var store = view.getStore(),
                        record = store.getAt(rowIndex);
                me.fireEvent('openOrder', record);
            }
        }));

        buttons.push(Ext.create('Ext.button.Button', {
            iconCls: 'sprite-pencil',
            cls: 'acceptShipping',
            tooltip: '{s name=dhl/view/main/list/editShipping}edit shipment{/s}',
            handler: function (view, rowIndex, colIndex, item) {
                var store = view.getStore(),
                        record = store.getAt(rowIndex);
                me.fireEvent('openAcceptance', record);
            }
        }));

        var columns = [
            {
                header: '{s name=dhl/view/main/list/orderTime}order time{/s}',
                dataIndex: 'orderTime',
                width: 115,
                renderer: me.dateColumn
            },
            {
                header: '{s name=dhl/view/main/list/orderNumber}order number{/s}',
                dataIndex: 'number',
                flex: 1
            },
            {
                header: '{s name=dhl/view/main/list/customer}customer{/s}',
                dataIndex: 'fullName',
                flex: 1
            },
            {
                header: '{s name=dhl/view/main/list/invoiceAmount}invoice amount{/s}',
                dataIndex: 'invoiceAmount',
                flex: 1
            },
            {
                header: '{s name=dhl/view/main/list/shippingNumber}shipping number{/s}',
                dataIndex: 'dhlShippingNumber',
                width: 140
            },
            {
                header: '{s name=dhl/view/main/list/status}status{/s}',
                renderer: me.renderStatus,
                width: 60
            },
            {
                xtype: 'actioncolumn',
                width: 60,
                items: buttons
            }
        ];

        return columns;
    },

    renderStatus: function (value, metaData, record) {
        var style = 'style="width: 25px; display: inline-block;"',
                result = "";

        if (!record.get('statusShipped')) {
            var title = '{s name=dhl/view/main/list/status_notTransferred}shipment not yet transferred to DHL{/s}';
            result = result + '<div  title="' + title + '" class="sprite-cross" ' + style + '>&nbsp;</div>';
        } else {
            var title = '{s name=dhl/view/main/list/status_transferred}shipment transferred to DHL{/s}';
            result = result + '<div  title="' + title + '" class="sprite-tick" ' + style + '>&nbsp;</div>';
        }

        if (!record.get('statusManifested')) {
            var title = '{s name=dhl/view/main/list/status_notManifested}shipment not yet manifested{/s}';
            result = result + '<div  title="' + title + '" class="sprite-cross" ' + style + '>&nbsp;</div>';
        } else {
            var title = '{s name=dhl/view/main/list/status_manifested}shipment manifested{/s}';
            result = result + '<div  title="' + title + '" class="sprite-tick" ' + style + '>&nbsp;</div>';
        }

        return result;
    },

    dateColumn: function (value, metaData, record) {
        if (value === Ext.undefined) {
            return value;
        }

        return Ext.util.Format.date(value) + ' ' + Ext.util.Format.date(value, 'H:i:s');
    },

    /**
     * Creates the toolbar with a save-button, a delete-button and a textfield to search for articles
     */
    getToolbar: function () {

        var searchField = Ext.create('Ext.form.field.Text', {
            name: 'searchfield',
            cls: 'searchfield',
            action: 'searchDhlOrders',
            width: 170,
            enableKeyEvents: true,
            emptyText: '{s name=dhl/view/main/list/search}Search...{/s}',
            listeners: {
                buffer: 500,
                keyup: function () {
                    if (this.getValue().length >= 3 || this.getValue().length < 1) {
                        /**
                         * @param this Contains the searchfield
                         */
                        this.fireEvent('fieldchange', this);
                    }
                }
            }
        });
        searchField.addEvents('fieldchange');
        var items = [];

        items.push(Ext.create('Ext.button.Button', {
            iconCls: 'printer',
            text: '{s name=dhl/view/main/list/printLabels}Print selected labels{/s}',
            disabled: true,
            action: 'printLabels'
        }));

        items.push('->');
        items.push(searchField);
        items.push({
            xtype: 'tbspacer',
            width: 6
        });

        var toolbar = Ext.create('Ext.toolbar.Toolbar', {
            dock: 'top',
            ui: 'shopware-ui',
            items: items
        });
        return toolbar;
    }
});
//{/block}