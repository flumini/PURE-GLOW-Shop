Ext.define('Shopware.apps.Dhl.model.Order', {
    /**
     * Extends the standard ExtJS 4
     * @string
     */
    extend: 'Ext.data.Model',
    /**
     * The fields used for this model
     * @array
     */
    fields: [
        { name: 'id', type: 'int' },
        { name: 'orderTime', type: 'date' },
        { name: 'number', type: 'string' },
        { name: 'firstName', type: 'string' },
        { name: 'lastName', type: 'string' },
        { name: 'fullName', type: 'string' },
        { name: 'invoiceAmount', type: 'float' },
        { name: 'dhlLabel', type: 'string' },
        { name: 'dhlShippingNumber', type: 'string' },
        { name: 'statusShipped', type: 'int' },
        { name: 'statusManifested', type: 'int' },
        { name: 'street', type: 'string' },
        { name: 'streetNumber', type: 'string' },
        { name: 'city', type: 'string' },
        { name: 'zip', type: 'string' },
        { name: 'weight', type: 'string' },
        { name: 'postNumber', type: 'string' },
        { name: 'attendance', type: 'string' },
        { name: 'useInsurance', type: 'boolean' },
        { name: 'shippingDate', type: 'date' },
        { name: 'isBulkfreight', type: 'boolean' },
        { name: 'useCod', type: 'boolean' },
        { name: 'reasonForPayment', type: 'string' },
        { name: 'country', type: 'string' },
        { name: 'printOnlyIfCodeable', type: 'printOnlyIfCodeable' }
    ]
});