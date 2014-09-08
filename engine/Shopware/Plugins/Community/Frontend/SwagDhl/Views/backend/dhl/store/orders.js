Ext.define('Shopware.apps.Dhl.store.Orders', {

    /**
     * Extend for the standard ExtJS 4
     * @string
     */
    extend: 'Ext.data.Store',
    /**
     * Auto load the store after the component
     * is initialized
     * @boolean
     */
    autoLoad: false,
    /**
     * Amount of data loaded at once
     * @integer
     */
    pageSize: 20,
    remoteFilter: true,
    remoteSort: true,
    /**
     * Define the used model for this store
     * @string
     */
    model: 'Shopware.apps.Dhl.model.Order',

    /**
     * Configure the data communication
     * @object
     */
    proxy: {
        type: 'ajax',
        /**
         * Configure the url mapping for the different
         * @object
         */
        api: {
            //read out all articles
            read: '{url controller="dhl" action="getOrders"}'
        },

        /**
         * Configure the data reader
         * @object
         */
        reader: {
            type: 'json',
            root: 'data',
            //total values, used for paging
            totalProperty: 'total'
        }
    }
});