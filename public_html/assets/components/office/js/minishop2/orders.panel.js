OfficeExt.panel.Orders = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        layout: 'anchor',
        defaults: {border: false, autoHeight: true},
        hideMode: 'offsets',
        border: false,
        cls: 'main-wrapper',
        items: [{
            xtype: 'minishop2-grid-orders',
        }]
    });
    OfficeExt.panel.Orders.superclass.constructor.call(this, config);
};
Ext.extend(OfficeExt.panel.Orders, MODx.Panel);
Ext.reg('minishop2-panel-orders', OfficeExt.panel.Orders);