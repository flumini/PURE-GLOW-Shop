Ext.define( 'Shopware.apps.SofagLogView.store.List', {
    extend:   'Ext.data.Store',
    autoLoad: false,
    pageSize: 20,
    model:    'Shopware.apps.SofagLogView.model.Main'
} );